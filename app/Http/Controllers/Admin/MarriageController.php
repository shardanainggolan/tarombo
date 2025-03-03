<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marriage;
use App\Models\Person;
use App\Models\FamilyGroup;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MarriageController extends Controller
{
    /**
    * Display a listing of the resource.
    */
   public function index(Request $request)
   {
       if ($request->ajax()) {
           $data = Marriage::with(['husband', 'wife'])->latest();
           
           return DataTables::of($data)
               ->addIndexColumn()
               ->addColumn('husband_name', function ($row) {
                   return $row->husband->first_name . ' ' . $row->husband->last_name . ' (' . $row->husband->marga->name . ')';
               })
               ->addColumn('wife_name', function ($row) {
                   return $row->wife->first_name . ' ' . $row->wife->last_name . ' (' . $row->wife->marga->name . ')';
               })
               ->addColumn('marriage_date', function ($row) {
                   return $row->marriage_date ? $row->marriage_date->format('d M Y') : '-';
               })
               ->addColumn('status', function ($row) {
                   if ($row->divorce_date) {
                       return '<span class="badge bg-danger">Bercerai (' . $row->divorce_date->format('d M Y') . ')</span>';
                   }
                   return $row->is_current ? 
                       '<span class="badge bg-success">Aktif</span>' : 
                       '<span class="badge bg-secondary">Tidak Aktif</span>';
               })
               ->addColumn('action', function ($row) {
                   $actionBtn = '
                       <div class="d-flex align-items-center">
                           <a href="' . route('admin.marriages.edit', $row->id) . '" class="btn btn-icon btn-label-info waves-effect me-2">
                               <i class="ti ti-pencil"></i>
                           </a>
                           <button type="button" id="' . $row->id . '" class="delete-record btn btn-icon btn-label-danger waves-effect">
                               <i class="ti ti-trash"></i>
                           </button>
                       </div>
                   ';
                   return $actionBtn;
               })
               ->rawColumns(['action', 'status'])
               ->make(true);
       }
       
       return view('admin.pages.marriages.index');
   }

   /**
    * Show the form for creating a new resource.
    */
   public function create(Request $request)
   {
       // If person_id is provided, pre-select that person
       $preSelectedPerson = null;
       $preSelectedGender = null;
       
       if ($request->has('person_id')) {
           $preSelectedPerson = Person::findOrFail($request->person_id);
           $preSelectedGender = $preSelectedPerson->gender;
       }
       
       // Get potential spouses based on Batak culture rules
       $potentialHusbands = $this->getPotentialHusbands($preSelectedPerson);
       $potentialWives = $this->getPotentialWives($preSelectedPerson);
       
       return view('admin.pages.marriages.create', compact(
           'preSelectedPerson', 
           'preSelectedGender', 
           'potentialHusbands', 
           'potentialWives'
       ));
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(Request $request)
   {
       $request->validate([
           'husband_id' => 'required|exists:people,id',
           'wife_id' => 'required|exists:people,id|different:husband_id',
           'marriage_date' => 'nullable|date',
           'divorce_date' => 'nullable|date|after_or_equal:marriage_date',
           'is_current' => 'boolean',
           'marriage_order' => 'required|integer|min:1',
           'notes' => 'nullable|string'
       ]);
       
       // Ensure husband is male and wife is female
       $husband = Person::findOrFail($request->husband_id);
       $wife = Person::findOrFail($request->wife_id);
       
       if ($husband->gender !== 'male') {
           return back()->withErrors(['husband_id' => 'Suami harus berjenis kelamin laki-laki.'])->withInput();
       }
       
       if ($wife->gender !== 'female') {
           return back()->withErrors(['wife_id' => 'Istri harus berjenis kelamin perempuan.'])->withInput();
       }
       
       // Check for same marga (prohibited in Batak culture)
       if ($husband->marga_id === $wife->marga_id) {
           return back()->withErrors(['general' => 'Pernikahan tidak diperbolehkan karena suami dan istri memiliki marga yang sama.'])->withInput();
       }
       
       // If this is marked as current, update other marriages to not current
       if ($request->has('is_current') && $request->is_current) {
           Marriage::where('husband_id', $request->husband_id)
               ->where('is_current', true)
               ->update(['is_current' => false]);
               
           Marriage::where('wife_id', $request->wife_id)
               ->where('is_current', true)
               ->update(['is_current' => false]);
       }
       
       // Create the marriage
       $marriage = Marriage::create($request->all());
       
       // Create or update family group
       $familyGroup = FamilyGroup::firstOrCreate(
           ['father_id' => $husband->id, 'mother_id' => $wife->id],
           ['notes' => 'Auto-created from marriage']
       );
       
       // Recalculate display order
       $familyGroup->recalculateDisplayOrder();
       
       return redirect()->route('admin.marriages.index')
           ->with('success', 'Pernikahan berhasil ditambahkan');
   }

   /**
    * Show the form for editing the specified resource.
    */
   public function edit(Marriage $marriage)
   {
       $marriage->load(['husband', 'wife']);
       
       return view('admin.pages.marriages.edit', compact('marriage'));
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(Request $request, Marriage $marriage)
   {
       $request->validate([
           'marriage_date' => 'nullable|date',
           'divorce_date' => 'nullable|date|after_or_equal:marriage_date',
           'is_current' => 'boolean',
           'marriage_order' => 'required|integer|min:1',
           'notes' => 'nullable|string'
       ]);
       
       $oldIsCurrentValue = $marriage->is_current;
       
       // If changing to current, update other marriages
       if (!$oldIsCurrentValue && $request->has('is_current') && $request->is_current) {
           Marriage::where('husband_id', $marriage->husband_id)
               ->where('id', '!=', $marriage->id)
               ->where('is_current', true)
               ->update(['is_current' => false]);
               
           Marriage::where('wife_id', $marriage->wife_id)
               ->where('id', '!=', $marriage->id)
               ->where('is_current', true)
               ->update(['is_current' => false]);
       }
       
       // Update marriage
       $marriage->update($request->all());
       
       // Update family group if needed (e.g., if divorce status changed)
       if ($request->has('divorce_date') && $request->divorce_date) {
           // If newly divorced, handle family group updates
           $familyGroup = FamilyGroup::where('father_id', $marriage->husband_id)
               ->where('mother_id', $marriage->wife_id)
               ->first();
               
           if ($familyGroup) {
               // Optional: Update family group notes or status
               $familyGroup->update(['notes' => $familyGroup->notes . ' (Pernikahan berakhir: ' . $request->divorce_date . ')']);
           }
       }
       
       return redirect()->route('admin.marriages.index')
           ->with('success', 'Pernikahan berhasil diperbarui');
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy(Marriage $marriage)
   {
       try {
           // Check for children associated with this marriage
           $childrenCount = $marriage->children()->count();
           
           if ($childrenCount > 0) {
               return response()->json([
                   'status' => 'error',
                   'message' => "Tidak dapat menghapus pernikahan karena memiliki $childrenCount anak terkait."
               ], 422);
           }
           
           // Delete family group
           FamilyGroup::where('father_id', $marriage->husband_id)
               ->where('mother_id', $marriage->wife_id)
               ->delete();
           
           $marriage->delete();
           
           return response()->json([
               'status' => 'success',
               'message' => 'Pernikahan berhasil dihapus'
           ]);
       } catch (\Exception $e) {
           return response()->json([
               'status' => 'error',
               'message' => 'Gagal menghapus pernikahan. ' . $e->getMessage()
           ], 422);
       }
   }
   
   /**
    * Get potential husbands based on Batak culture marriage rules.
    */
   private function getPotentialHusbands($preSelectedPerson = null)
   {
       // If pre-selected person is male, only return that person
       if ($preSelectedPerson && $preSelectedPerson->gender === 'male') {
           return collect([$preSelectedPerson]);
       }
       
       // Otherwise, get all eligible males
       return Person::where('gender', 'male')
           ->when($preSelectedPerson, function ($query) use ($preSelectedPerson) {
               // Exclude people with same marga (prohibited in Batak culture)
               return $query->where('marga_id', '!=', $preSelectedPerson->marga_id);
           })
           ->orderBy('first_name')
           ->get();
   }
   
   /**
    * Get potential wives based on Batak culture marriage rules.
    */
   private function getPotentialWives($preSelectedPerson = null)
   {
       // If pre-selected person is female, only return that person
       if ($preSelectedPerson && $preSelectedPerson->gender === 'female') {
           return collect([$preSelectedPerson]);
       }
       
       // Otherwise, get all eligible females
       return Person::where('gender', 'female')
           ->when($preSelectedPerson, function ($query) use ($preSelectedPerson) {
               // Exclude people with same marga (prohibited in Batak culture)
               return $query->where('marga_id', '!=', $preSelectedPerson->marga_id);
           })
           ->orderBy('first_name')
           ->get();
   }
}
