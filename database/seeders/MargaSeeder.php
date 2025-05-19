<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marga;
use Illuminate\Support\Facades\DB;

class MargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate the table to avoid duplicate entries
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('margas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Seed margas data
        $margas = [
            [
                'id' => 1,
                'name' => 'Nainggolan',
                'description' => 'Marga Batak Toba yang berasal dari daerah Samosir, termasuk dalam kelompok Sumba.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Sihotang',
                'description' => 'Marga Batak Toba yang berasal dari daerah Samosir, termasuk dalam kelompok Sumba.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Simanjuntak',
                'description' => 'Marga Batak Toba yang berasal dari daerah Uluan, termasuk dalam kelompok Sumba.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Sitorus',
                'description' => 'Marga Batak Toba yang berasal dari daerah Samosir, termasuk dalam kelompok Lontung.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Panjaitan',
                'description' => 'Marga Batak Toba yang berasal dari daerah Porsea, termasuk dalam kelompok Sumba.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Insert data into margas table
        Marga::insert($margas);
        
        $this->command->info('Margas table seeded successfully!');
    }
}
