<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Family;
use Illuminate\Support\Facades\DB;

class FamilySeeder extends Seeder
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
        DB::table('families')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Seed families data
        $families = [
            [
                'id' => 1,
                'family_name' => 'Keluarga Nainggolan',
                'marga_id' => 1, // Nainggolan
                'description' => 'Keluarga utama untuk demonstrasi family tree Batak',
                'user_id' => 1, // Menambahkan user_id
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'family_name' => 'Keluarga Sihotang',
                'marga_id' => 2, // Sihotang
                'description' => 'Keluarga dari pihak ibu',
                'user_id' => 1, // Menambahkan user_id
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'family_name' => 'Keluarga Simanjuntak',
                'marga_id' => 3, // Simanjuntak
                'description' => 'Keluarga dari pihak istri',
                'user_id' => 1, // Menambahkan user_id
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'family_name' => 'Keluarga Sitorus',
                'marga_id' => 4, // Sitorus
                'description' => 'Keluarga dari pihak menantu',
                'user_id' => 1, // Menambahkan user_id
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'family_name' => 'Keluarga Panjaitan',
                'marga_id' => 5, // Panjaitan
                'description' => 'Keluarga dari pihak besan',
                'user_id' => 1, // Menambahkan user_id
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Insert data into families table
        Family::insert($families);
        
        $this->command->info('Families table seeded successfully!');
    }
}
