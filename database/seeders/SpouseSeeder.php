<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpouseSeeder extends Seeder
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
        DB::table('spouses')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Seed spouses data
        $spouses = [
            // Generasi 1 - Kakek-Nenek
            [
                'family_member_id1' => 1, // Opung Doli Nainggolan
                'family_member_id2' => 2, // Opung Boru Nainggolan
                'marriage_date' => '1965-06-15',
                'marriage_location' => 'Samosir',
                'status' => 'married',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'family_member_id1' => 3, // Opung Doli Sihotang
                'family_member_id2' => 4, // Opung Boru Sihotang
                'marriage_date' => '1963-08-20',
                'marriage_location' => 'Samosir',
                'status' => 'married',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Generasi 2 - Orang Tua
            [
                'family_member_id1' => 5, // Amang Nainggolan
                'family_member_id2' => 8, // Inang Sihotang
                'marriage_date' => '1997-05-10',
                'marriage_location' => 'Medan',
                'status' => 'married',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Generasi 3 - Ego dan Saudara
            [
                'family_member_id1' => 11, // Ego Nainggolan
                'family_member_id2' => 16, // Istri Simanjuntak
                'marriage_date' => '2021-09-25',
                'marriage_location' => 'Jakarta',
                'status' => 'married',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'family_member_id1' => 12, // Abang Nainggolan
                'family_member_id2' => 21, // Parumaen Sitorus
                'marriage_date' => '2020-03-15',
                'marriage_location' => 'Jakarta',
                'status' => 'married',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'family_member_id1' => 22, // Hela Panjaitan
                'family_member_id2' => 13, // Anggi Nainggolan
                'marriage_date' => '2023-11-20',
                'marriage_location' => 'Jakarta',
                'status' => 'married',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Convert marriage_date to Carbon instances
        foreach ($spouses as &$spouse) {
            if (!empty($spouse['marriage_date'])) {
                $spouse['marriage_date'] = Carbon::parse($spouse['marriage_date']);
            }
        }
        
        // Insert data into spouses table
        DB::table('spouses')->insert($spouses);
        
        $this->command->info('Spouses table seeded successfully!');
    }
}
