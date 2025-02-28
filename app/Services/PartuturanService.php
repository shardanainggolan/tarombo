<?php

namespace App\Services;

use App\Models\Person;
use App\Models\PartuturanRule;

class PartuturanService
{
    private $cache = [];
    private $currentUser;
    private $maxDepth = 7;

    public function __construct(?Person $user = null)
    {
        $this->currentUser = $user ?? auth()->user()->person;
    }

    public function getAllRelationships(): array
    {
        return [
            'hulahula' => $this->getHulaHulaRelations(),
            'dongantubu' => $this->getDonganTubuRelations(),
            'boru' => $this->getBoruRelations()
        ];
    }

    private function getHulaHulaRelations(): array
    {
        return $this->traverseRelations(
            start: $this->currentUser->mother ?? $this->currentUser->wife,
            direction: 'asc',
            group: 'HUL',
            exclude: ['children']
        );
    }

    private function getDonganTubuRelations(): array
    {
        $marga = $this->currentUser->marga;
        $sameMargaPeople = Person::where('marga', $marga)
            ->with('father', 'mother')
            ->get();

        return $sameMargaPeople->map(function ($person) {
            $distance = $this->currentUser->getMargaDistance($person);
            
            return $this->matchDonganTubuRule($person, $distance);
        })->filter()->values()->toArray();
    }

    private function getBoruRelations(): array
    {
        return $this->traverseRelations(
            start: $this->currentUser->sisters(),
            direction: 'desc',
            group: 'BOR',
            include: ['children', 'spouses']
        );
    }

    private function traverseRelations(
        $start, 
        string $direction, 
        string $group, 
        array $exclude = [], 
        array $include = []
    ): array {
        $tree = $this->buildFamilyTree($start, $direction);
        $rules = PartuturanRule::where('group_code', $group)
            ->orderBy('priority', 'desc')
            ->get();

        $results = [];
        foreach ($tree as $node) {
            foreach ($rules as $rule) {
                if ($this->matchRule($node, $rule, $group)) {
                    $results[] = $this->formatResult($node, $rule);
                    break;
                }
            }
        }
        
        return $results;
    }

    private function matchRule(array $node, PartuturanRule $rule, string $group): bool
    {
        $person = $node['person'];
        
        // Base Condition
        $baseMatch = $this->matchBaseConditions($person, $rule);
        
        // Group Specific Conditions
        $groupMatch = match($group) {
            'HUL' => $this->matchHulaHulaConditions($node, $rule),
            'DOT' => $this->matchDonganTubuConditions($person, $rule),
            'BOR' => $this->matchBoruConditions($node, $rule),
            default => false
        };

        return $baseMatch && $groupMatch;
    }

    private function matchHulaHulaConditions(array $node, PartuturanRule $rule): bool
    {
        // Contoh: Bona Ni Ari harus melalui 3 generasi maternal
        $path = $node['path'];
        $expectedPath = explode('.', $rule->relationship_code);
        
        $pathMatch = $this->matchPath($path, $expectedPath);
        $generationMatch = $node['depth'] >= $rule->min_generation 
            && $node['depth'] <= $rule->max_generation;

        return $pathMatch && $generationMatch;
    }

    private function matchDonganTubuConditions(Person $person, PartuturanRule $rule): bool
    {
        $distance = $this->currentUser->getMargaDistance($person);
        
        return $distance['is_same_line'] 
            && $distance['distance'] == $rule->generation_level
            && ($person->gender === $rule->gender || $rule->gender === 'both');
    }

    private function matchBoruConditions(array $node, PartuturanRule $rule): bool
    {
        // Contoh: Pariban harus unmarried
        if ($rule->term === 'Pariban') {
            return is_null($node['person']->marriages);
        }
        
        return $this->matchPath($node['path'], explode('.', $rule->relationship_code));
    }
}