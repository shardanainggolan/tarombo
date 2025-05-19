<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SapaanTerm;
use Illuminate\Support\Facades\DB;

class SapaanTermSeeder extends Seeder
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
        DB::table('sapaan_terms')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Seed sapaan terms data
        $sapaanTerms = [
            // Sapaan untuk keluarga inti
            [
                'id' => 1,
                'term' => 'Amang',
                'description' => 'Sapaan untuk ayah',
                'marga_id' => null, // Berlaku untuk semua marga
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'term' => 'Inang',
                'description' => 'Sapaan untuk ibu',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'term' => 'Abang',
                'description' => 'Sapaan untuk kakak laki-laki',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'term' => 'Anggi',
                'description' => 'Sapaan untuk adik (laki-laki atau perempuan)',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'term' => 'Anak',
                'description' => 'Sapaan untuk anak laki-laki',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'term' => 'Boru',
                'description' => 'Sapaan untuk anak perempuan',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Sapaan untuk keluarga besar
            [
                'id' => 7,
                'term' => 'Ompung Doli',
                'description' => 'Sapaan untuk kakek',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 8,
                'term' => 'Ompung Boru',
                'description' => 'Sapaan untuk nenek',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'term' => 'Amang Uda',
                'description' => 'Sapaan untuk adik laki-laki ayah',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'term' => 'Amang Tua',
                'description' => 'Sapaan untuk kakak laki-laki ayah',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 11,
                'term' => 'Namboru',
                'description' => 'Sapaan untuk saudara perempuan ayah',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'term' => 'Tulang',
                'description' => 'Sapaan untuk saudara laki-laki ibu',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'term' => 'Nantulang',
                'description' => 'Sapaan untuk saudara perempuan ibu',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 14,
                'term' => 'Iboto',
                'description' => 'Sapaan untuk sepupu dari pihak ayah',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 15,
                'term' => 'Pariban',
                'description' => 'Sapaan untuk sepupu dari pihak ibu (anak tulang)',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Sapaan untuk hubungan pernikahan
            [
                'id' => 16,
                'term' => 'Lae',
                'description' => 'Sapaan untuk saudara laki-laki istri',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 17,
                'term' => 'Eda',
                'description' => 'Sapaan untuk saudara perempuan istri',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 18,
                'term' => 'Hela',
                'description' => 'Sapaan untuk suami dari anak perempuan',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 19,
                'term' => 'Parumaen',
                'description' => 'Sapaan untuk istri dari anak laki-laki',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 20,
                'term' => 'Pahompu',
                'description' => 'Sapaan untuk cucu (laki-laki atau perempuan)',
                'marga_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Insert data into sapaan_terms table
        SapaanTerm::insert($sapaanTerms);
        
        $this->command->info('Sapaan terms table seeded successfully!');
    }
}
