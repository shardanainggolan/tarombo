<?php

namespace App\Services;

use App\Models\Person;
use App\Models\PartuturanRule;

class PartuturanService
{
    protected $visited = [];
    protected $rules;
    
    public function __construct()
    {
        $this->rules = PartuturanRule::orderBy('priority', 'desc')->get();
    }

    public function generateRelationships(Person $person)
    {
        $this->visited = [];
        $relationships = [];
        
        $this->traverseFamily($person, $person, 0, $relationships);
        
        return $relationships;
    }

    private function traverseFamily(
        Person $root, 
        Person $current, 
        $depth, 
        &$relationships
    ) {
        if(in_array($current->id, $this->visited)) return;
        $this->visited[] = $current->id;

        // Cek hubungan dengan root
        if($current->id !== $root->id) {
            $relationshipType = $this->determineRelationship($root, $current);
            if($relationshipType) {
                $relationships[] = [
                    'relative' => $current,
                    'relationship' => $relationshipType
                ];
            }
        }

        // Traverse ke atas (orang tua)
        if($current->father) {
            $this->traverseFamily($root, $current->father, $depth + 1, $relationships);
        }
        if($current->mother) {
            $this->traverseFamily($root, $current->mother, $depth + 1, $relationships);
        }

        // Traverse ke samping (saudara)
        $siblings = $this->getSiblings($current);
        foreach($siblings as $sibling) {
            $this->traverseFamily($root, $sibling, $depth, $relationships);
        }

        // Traverse ke bawah (anak)
        foreach($current->children as $child) {
            $this->traverseFamily($root, $child->person, $depth + 1, $relationships);
        }
    }

    private function getSiblings(Person $person)
    {
        return Person::where(function($query) use ($person) {
                $query->where('father_id', $person->father_id)
                    ->where('mother_id', $person->mother_id);
            })
            ->where('id', '!=', $person->id)
            ->get();
    }

    private function determineRelationship(Person $from, Person $to)
    {
        $path = $this->findRelationshipPath($from, $to);
        $margaCheck = $this->checkMargaRelationship($from, $to);
        
        return $this->matchAdvancedRules($from, $to, $path, $margaCheck);
    }

    private function checkMargaRelationship($from, $to)
    {
        // Implementasi algoritma pengecekan generasi dan garis keturunan
        // Mengembalikan array [generation => N, is_direct => bool]
    }

    private function matchAdvancedRules($from, $to, $path, $margaCheck)
    {
        $rules = PartuturanRule::with('group')
            ->orderBy('priority', 'desc')
            ->get();

        foreach ($rules as $rule) {
            if ($this->matchSpecialCases($rule, $from, $to, $path, $margaCheck)) {
                return $rule;
            }
        }
        
        return null;
    }

    private function matchSpecialCases($rule, $from, $to, $path, $margaCheck)
    {
        switch ($rule->group->name) {
            case 'Dongan Tubu':
                return $this->matchDonganTubu($rule, $from, $to, $margaCheck);
                
            case 'Hula-hula':
                return $this->matchHulaHula($rule, $path);
                
            case 'Boru':
                return $this->matchBoru($rule, $path, $from, $to);
        }
    }

    private function matchDonganTubu($rule, $from, $to, $margaCheck)
    {
        return $from->marga === $to->marga && 
               $rule->generation_level === $margaCheck['generation'] &&
               $rule->is_direct_line === $margaCheck['is_direct'];
    }

    private function matchHulaHula($rule, $path)
    {
        $requiredPath = explode('.', $rule->relationship_code);
        return $this->arrayMatch($requiredPath, $path);
    }

    private function matchBoru($rule, $path, $from, $to)
    {
        if ($rule->term === 'Pariban') {
            return $this->isPariban($from, $to);
        }
        return $this->arrayMatch(explode('.', $rule->relationship_code), $path);
    }

    private function isPariban($from, $to)
    {
        // Pariban = anak perempuan dari tulang (saudara laki-laki ibu)
        return $to->mother && 
               $to->mother->gender === 'female' &&
               $to->mother->mother_id === $from->mother_id;
    }


    private function findRelationshipPath(Person $from, Person $to)
    {
        // Implementasi BFS untuk mencari jalur hubungan
        $queue = new \SplQueue();
        $queue->enqueue([$from, []]);
        $visited = [];

        while(!$queue->isEmpty()) {
            [$current, $path] = $queue->dequeue();

            if($current->id === $to->id) {
                return $path;
            }

            if(!in_array($current->id, $visited)) {
                $visited[] = $current->id;

                // Cek ke orang tua
                if($current->father) {
                    $newPath = array_merge($path, ['father']);
                    $queue->enqueue([$current->father, $newPath]);
                }
                if($current->mother) {
                    $newPath = array_merge($path, ['mother']);
                    $queue->enqueue([$current->mother, $newPath]);
                }

                // Cek ke anak
                foreach($current->children as $child) {
                    $newPath = array_merge($path, ['child']);
                    $queue->enqueue([$child->person, $newPath]);
                }

                // Cek ke saudara
                $siblings = $this->getSiblings($current);
                foreach($siblings as $sibling) {
                    $newPath = array_merge($path, ['sibling']);
                    $queue->enqueue([$sibling, $newPath]);
                }
            }
        }

        return [];
    }

    private function matchRule(array $path, PartuturanRule $rule)
    {
        $rulePath = explode('.', $rule->relationship_code);
        
        if(count($path) !== count($rulePath)) {
            return false;
        }

        foreach($path as $index => $segment) {
            if($rulePath[$index] !== $segment) {
                return false;
            }
        }

        return true;
    }
}