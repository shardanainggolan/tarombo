<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PartuturanRule;

class PartuturanRulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'Hula-hula' => 'Keluarga pihak ibu/istri',
            'Dongan Tubu' => 'Saudara semarga',
            'Boru' => 'Keluarga pihak perempuan'
        ];

        foreach ($groups as $name => $desc) {
            PartuturanGroup::create(['name' => $name, 'description' => $desc]);
        }

        $this->createHulaHulaRules();
        $this->createDonganTubuRules();
        $this->createBoruRules();
    }

    private function createHulaHulaRules()
    {
        $group = PartuturanGroup::where('name', 'Hula-hula')->first();
        
        $rules = [
            [
                'relationship_code' => 'mother.father.father',
                'term' => 'Bona Ni Ari',
                'description' => 'Tulang dari ompu kandung',
                'gender' => 'male',
                'generation_level' => -3
            ],
            [
                'relationship_code' => 'mother.father',
                'term' => 'Bona Tulang',
                'description' => 'Tulang dari ayah',
                'gender' => 'male',
                'generation_level' => -2
            ],
            // ... semua aturan Hula-hula
        ];
        
        foreach ($rules as $rule) {
            PartuturanRule::create(array_merge($rule, [
                'group_id' => $group->id,
                'priority' => 100 + $rule['generation_level']
            ]));
        }
    }

    private function createDonganTubuRules()
    {
        $group = PartuturanGroup::where('name', 'Dongan Tubu')->first();
        
        $rules = [
            [
                'relationship_code' => 'self.marga.ancestor',
                'term' => 'Ompu Parsadaan',
                'description' => 'Ompu yang bisa dilacak sebagai persamaan marga',
                'generation_level' => -4,
                'is_direct_line' => true
            ],
            [
                'relationship_code' => 'father.father',
                'term' => 'Ompu Suhut',
                'description' => 'Ompu kandung (ayahnya ayah)',
                'gender' => 'male',
                'generation_level' => -2
            ],
            // ... semua aturan Dongan Tubu
        ];
        
        foreach ($rules as $rule) {
            PartuturanRule::create(array_merge($rule, [
                'group_id' => $group->id,
                'priority' => 200 + $rule['generation_level']
            ]));
        }
    }

    private function createBoruRules()
    {
        $group = PartuturanGroup::where('name', 'Boru')->first();
        
        $rules = [
            [
                'relationship_code' => 'father.sister.daughter',
                'term' => 'Ito',
                'description' => 'Saudara perempuan satu marga',
                'gender' => 'female'
            ],
            [
                'relationship_code' => 'mother.brother.son',
                'term' => 'Lae',
                'description' => 'Anak laki-laki tulang',
                'gender' => 'male'
            ],
            // ... semua aturan Boru
        ];
        
        foreach ($rules as $rule) {
            PartuturanRule::create(array_merge($rule, [
                'group_id' => $group->id,
                'priority' => 300
            ]));
        }
    }
}
