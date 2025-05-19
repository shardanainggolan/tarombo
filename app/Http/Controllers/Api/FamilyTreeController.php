<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Services\SapaanService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FamilyTreeController extends Controller
{
    protected $sapaanService;

    public function __construct(SapaanService $sapaanService)
    {
        $this->sapaanService = $sapaanService;
    }

    /**
     * Get complete family tree structure for visualization
     *
     * @param int $familyId
     * @return JsonResponse
     */
    public function getFamilyTree(int $familyId): JsonResponse
    {
        $family = Family::with('marga')->findOrFail($familyId);
        
        // Get all family members
        $members = FamilyMember::where('family_id', $familyId)
            ->with(['father', 'mother', 'spouses'])
            ->get();
        
        // Prepare nodes and edges
        $nodes = [];
        $edges = [];
        
        foreach ($members as $member) {
            // Add node
            $nodes[] = [
                'id' => $member->id,
                'name' => $member->full_name,
                'gender' => $member->gender,
                'birth_date' => $member->birth_date ? $member->birth_date->format('Y-m-d') : null,
                'death_date' => $member->death_date ? $member->death_date->format('Y-m-d') : null,
                'marga' => $family->marga ? $family->marga->name : null,
                'profile_image' => $member->profile_image ?? null,
                'additional_info' => [
                    'nickname' => $member->nickname,
                    'birth_place' => $member->birth_place,
                    'bio' => $member->bio,
                    'order_in_siblings' => $member->order_in_siblings,
                ]
            ];
            
            // Add parent-child edges
            if ($member->father_id) {
                $edges[] = [
                    'source' => $member->father_id,
                    'target' => $member->id,
                    'relationship_type' => 'parent-child',
                    'additional_info' => [
                        'parent_type' => 'father'
                    ]
                ];
            }
            
            if ($member->mother_id) {
                $edges[] = [
                    'source' => $member->mother_id,
                    'target' => $member->id,
                    'relationship_type' => 'parent-child',
                    'additional_info' => [
                        'parent_type' => 'mother'
                    ]
                ];
            }
            
            // Add spouse edges
            foreach ($member->spouses as $spouse) {
                // Only add the edge once (from lower ID to higher ID to avoid duplicates)
                if ($member->id < $spouse->id) {
                    $edges[] = [
                        'source' => $member->id,
                        'target' => $spouse->id,
                        'relationship_type' => 'spouse',
                        'additional_info' => [
                            'marriage_date' => $spouse->pivot->marriage_date ? date('Y-m-d', strtotime($spouse->pivot->marriage_date)) : null,
                            'marriage_status' => $spouse->pivot->status
                        ]
                    ];
                }
            }
        }
        
        return response()->json([
            'family' => [
                'id' => $family->id,
                'name' => $family->family_name,
                'marga' => $family->marga ? $family->marga->name : null
            ],
            'nodes' => $nodes,
            'edges' => $edges
        ]);
    }
    
    /**
     * Get sapaan between two family members
     *
     * @param int $fromId
     * @param int $toId
     * @return JsonResponse
     */
    public function getSapaan(int $fromId, int $toId): JsonResponse
    {
        $from = FamilyMember::findOrFail($fromId);
        $to = FamilyMember::findOrFail($toId);
        
        $sapaan = $this->sapaanService->getSapaan($from, $to);
        $relationship = $this->sapaanService->detectRelationship($from, $to);
        
        return response()->json([
            'from' => [
                'id' => $from->id,
                'name' => $from->full_name
            ],
            'to' => [
                'id' => $to->id,
                'name' => $to->full_name
            ],
            'sapaan' => $sapaan,
            'relationship' => $relationship
        ]);
    }
    
    /**
     * Get all sapaan for one family member to all other members
     *
     * @param int $memberId
     * @return JsonResponse
     */
    public function getAllSapaan(int $memberId): JsonResponse
    {
        $member = FamilyMember::findOrFail($memberId);
        $family = Family::findOrFail($member->family_id);
        
        // Get all other family members
        $otherMembers = FamilyMember::where('family_id', $family->id)
            ->where('id', '!=', $member->id)
            ->get();
        
        $sapaanList = [];
        
        foreach ($otherMembers as $otherMember) {
            $sapaan = $this->sapaanService->getSapaan($member, $otherMember);
            $relationship = $this->sapaanService->detectRelationship($member, $otherMember);
            
            $sapaanList[] = [
                'to' => [
                    'id' => $otherMember->id,
                    'name' => $otherMember->full_name
                ],
                'sapaan' => $sapaan,
                'relationship' => $relationship
            ];
        }
        
        return response()->json([
            'member' => [
                'id' => $member->id,
                'name' => $member->full_name
            ],
            'sapaan_list' => $sapaanList
        ]);
    }
    
    /**
     * Get family tree with sapaan from perspective of a specific member
     *
     * @param int $familyId
     * @param int $perspectiveMemberId
     * @return JsonResponse
     */
    public function getFamilyTreeWithSapaan(int $familyId, int $perspectiveMemberId): JsonResponse
    {
        $family = Family::with('marga')->findOrFail($familyId);
        $perspectiveMember = FamilyMember::findOrFail($perspectiveMemberId);
        
        // Get all family members
        $members = FamilyMember::where('family_id', $familyId)
            ->with(['father', 'mother', 'spouses'])
            ->get();
        
        // Prepare nodes and edges
        $nodes = [];
        $edges = [];
        
        foreach ($members as $member) {
            // Get sapaan if not the perspective member
            $sapaan = null;
            $relationship = null;
            
            if ($member->id !== $perspectiveMember->id) {
                $sapaan = $this->sapaanService->getSapaan($perspectiveMember, $member);
                $relationship = $this->sapaanService->detectRelationship($perspectiveMember, $member);
            }
            
            // Add node
            $nodes[] = [
                'id' => $member->id,
                'name' => $member->full_name,
                'gender' => $member->gender,
                'birth_date' => $member->birth_date ? $member->birth_date->format('Y-m-d') : null,
                'death_date' => $member->death_date ? $member->death_date->format('Y-m-d') : null,
                'marga' => $family->marga ? $family->marga->name : null,
                'profile_image' => $member->profile_image ?? null,
                'sapaan' => $sapaan,
                'relationship' => $relationship,
                'is_perspective_member' => $member->id === $perspectiveMember->id,
                'additional_info' => [
                    'nickname' => $member->nickname,
                    'birth_place' => $member->birth_place,
                    'bio' => $member->bio,
                    'order_in_siblings' => $member->order_in_siblings,
                ]
            ];
            
            // Add parent-child edges
            if ($member->father_id) {
                $edges[] = [
                    'source' => $member->father_id,
                    'target' => $member->id,
                    'relationship_type' => 'parent-child',
                    'additional_info' => [
                        'parent_type' => 'father'
                    ]
                ];
            }
            
            if ($member->mother_id) {
                $edges[] = [
                    'source' => $member->mother_id,
                    'target' => $member->id,
                    'relationship_type' => 'parent-child',
                    'additional_info' => [
                        'parent_type' => 'mother'
                    ]
                ];
            }
            
            // Add spouse edges
            foreach ($member->spouses as $spouse) {
                // Only add the edge once (from lower ID to higher ID to avoid duplicates)
                if ($member->id < $spouse->id) {
                    $edges[] = [
                        'source' => $member->id,
                        'target' => $spouse->id,
                        'relationship_type' => 'spouse',
                        'additional_info' => [
                            'marriage_date' => $spouse->pivot->marriage_date ? date('Y-m-d', strtotime($spouse->pivot->marriage_date)) : null,
                            'marriage_status' => $spouse->pivot->status
                        ]
                    ];
                }
            }
        }
        
        return response()->json([
            'family' => [
                'id' => $family->id,
                'name' => $family->family_name,
                'marga' => $family->marga ? $family->marga->name : null
            ],
            'perspective_member' => [
                'id' => $perspectiveMember->id,
                'name' => $perspectiveMember->full_name
            ],
            'nodes' => $nodes,
            'edges' => $edges
        ]);
    }
}
