<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FamilyMemberSeeder extends Seeder
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
        DB::table('family_members')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Seed family members data
        
        // Generasi 1 (Kakek-Nenek)
        $generation1 = [
            // Keluarga Nainggolan - Generasi 1
            [
                'id' => 1,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Opung Doli Nainggolan',
                'gender' => 'male',
                'birth_date' => '1940-01-01',
                'death_date' => null,
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Opung',
                'birth_place' => 'Samosir',
                'bio' => 'Kakek dari keluarga Nainggolan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Opung Boru Nainggolan',
                'gender' => 'female',
                'birth_date' => '1945-01-01',
                'death_date' => null,
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Opung',
                'birth_place' => 'Samosir',
                'bio' => 'Nenek dari keluarga Nainggolan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Keluarga Sihotang - Generasi 1
            [
                'id' => 3,
                'family_id' => 2, // Keluarga Sihotang
                'full_name' => 'Opung Doli Sihotang',
                'gender' => 'male',
                'birth_date' => '1938-03-15',
                'death_date' => '2015-07-20',
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Opung',
                'birth_place' => 'Samosir',
                'bio' => 'Kakek dari keluarga Sihotang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'family_id' => 2, // Keluarga Sihotang
                'full_name' => 'Opung Boru Sihotang',
                'gender' => 'female',
                'birth_date' => '1942-05-10',
                'death_date' => null,
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Opung',
                'birth_place' => 'Samosir',
                'bio' => 'Nenek dari keluarga Sihotang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Generasi 2 (Orang Tua dan Saudara)
        $generation2 = [
            // Keluarga Nainggolan - Generasi 2
            [
                'id' => 5,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Amang Nainggolan',
                'gender' => 'male',
                'birth_date' => '1970-05-15',
                'death_date' => null,
                'father_id' => 1, // Opung Doli Nainggolan
                'mother_id' => 2, // Opung Boru Nainggolan
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Amang',
                'birth_place' => 'Medan',
                'bio' => 'Ayah dari keluarga Nainggolan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Amang Uda Nainggolan',
                'gender' => 'male',
                'birth_date' => '1972-08-20',
                'death_date' => null,
                'father_id' => 1, // Opung Doli Nainggolan
                'mother_id' => 2, // Opung Boru Nainggolan
                'order_in_siblings' => 2,
                'profile_picture_path' => null,
                'nickname' => 'Uda',
                'birth_place' => 'Medan',
                'bio' => 'Adik laki-laki ayah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Namboru Nainggolan',
                'gender' => 'female',
                'birth_date' => '1975-11-30',
                'death_date' => null,
                'father_id' => 1, // Opung Doli Nainggolan
                'mother_id' => 2, // Opung Boru Nainggolan
                'order_in_siblings' => 3,
                'profile_picture_path' => null,
                'nickname' => 'Boru',
                'birth_place' => 'Medan',
                'bio' => 'Adik perempuan ayah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Keluarga Sihotang - Generasi 2
            [
                'id' => 8,
                'family_id' => 2, // Keluarga Sihotang
                'full_name' => 'Inang Sihotang',
                'gender' => 'female',
                'birth_date' => '1975-04-12',
                'death_date' => null,
                'father_id' => 3, // Opung Doli Sihotang
                'mother_id' => 4, // Opung Boru Sihotang
                'order_in_siblings' => 2,
                'profile_picture_path' => null,
                'nickname' => 'Inang',
                'birth_place' => 'Pematang Siantar',
                'bio' => 'Ibu dari keluarga Nainggolan, berasal dari marga Sihotang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 9,
                'family_id' => 2, // Keluarga Sihotang
                'full_name' => 'Tulang Sihotang',
                'gender' => 'male',
                'birth_date' => '1973-02-25',
                'death_date' => null,
                'father_id' => 3, // Opung Doli Sihotang
                'mother_id' => 4, // Opung Boru Sihotang
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Tulang',
                'birth_place' => 'Pematang Siantar',
                'bio' => 'Kakak laki-laki ibu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 10,
                'family_id' => 2, // Keluarga Sihotang
                'full_name' => 'Nantulang Sihotang',
                'gender' => 'female',
                'birth_date' => '1977-09-05',
                'death_date' => null,
                'father_id' => 3, // Opung Doli Sihotang
                'mother_id' => 4, // Opung Boru Sihotang
                'order_in_siblings' => 3,
                'profile_picture_path' => null,
                'nickname' => 'Nantulang',
                'birth_place' => 'Pematang Siantar',
                'bio' => 'Adik perempuan ibu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Generasi 3 (Ego dan Saudara)
        $generation3 = [
            // Keluarga Nainggolan - Generasi 3 (Ego dan Saudara)
            [
                'id' => 11,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Ego Nainggolan',
                'gender' => 'male',
                'birth_date' => '2000-06-15',
                'death_date' => null,
                'father_id' => 5, // Amang Nainggolan
                'mother_id' => 8, // Inang Sihotang
                'order_in_siblings' => 2,
                'profile_picture_path' => null,
                'nickname' => 'Ego',
                'birth_place' => 'Jakarta',
                'bio' => 'Anggota utama keluarga Nainggolan untuk demonstrasi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 12,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Abang Nainggolan',
                'gender' => 'male',
                'birth_date' => '1998-03-20',
                'death_date' => null,
                'father_id' => 5, // Amang Nainggolan
                'mother_id' => 8, // Inang Sihotang
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Abang',
                'birth_place' => 'Jakarta',
                'bio' => 'Kakak laki-laki Ego',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 13,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Anggi Nainggolan',
                'gender' => 'female',
                'birth_date' => '2002-11-10',
                'death_date' => null,
                'father_id' => 5, // Amang Nainggolan
                'mother_id' => 8, // Inang Sihotang
                'order_in_siblings' => 3,
                'profile_picture_path' => null,
                'nickname' => 'Anggi',
                'birth_place' => 'Jakarta',
                'bio' => 'Adik perempuan Ego',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Sepupu dari pihak ayah
            [
                'id' => 14,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Iboto Nainggolan',
                'gender' => 'male',
                'birth_date' => '2001-05-25',
                'death_date' => null,
                'father_id' => 6, // Amang Uda Nainggolan
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Iboto',
                'birth_place' => 'Medan',
                'bio' => 'Sepupu dari pihak ayah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Sepupu dari pihak ibu (pariban)
            [
                'id' => 15,
                'family_id' => 2, // Keluarga Sihotang
                'full_name' => 'Pariban Sihotang',
                'gender' => 'female',
                'birth_date' => '2001-08-15',
                'death_date' => null,
                'father_id' => 9, // Tulang Sihotang
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Pariban',
                'birth_place' => 'Pematang Siantar',
                'bio' => 'Sepupu dari pihak ibu (pariban)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Keluarga Simanjuntak - Istri Ego
            [
                'id' => 16,
                'family_id' => 3, // Keluarga Simanjuntak
                'full_name' => 'Istri Simanjuntak',
                'gender' => 'female',
                'birth_date' => '2000-09-20',
                'death_date' => null,
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 2,
                'profile_picture_path' => null,
                'nickname' => 'Istri',
                'birth_place' => 'Balige',
                'bio' => 'Istri Ego dari marga Simanjuntak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Keluarga Simanjuntak - Saudara Istri
            [
                'id' => 17,
                'family_id' => 3, // Keluarga Simanjuntak
                'full_name' => 'Lae Simanjuntak',
                'gender' => 'male',
                'birth_date' => '1998-07-12',
                'death_date' => null,
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Lae',
                'birth_place' => 'Balige',
                'bio' => 'Kakak laki-laki istri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 18,
                'family_id' => 3, // Keluarga Simanjuntak
                'full_name' => 'Eda Simanjuntak',
                'gender' => 'female',
                'birth_date' => '2002-04-30',
                'death_date' => null,
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 3,
                'profile_picture_path' => null,
                'nickname' => 'Eda',
                'birth_place' => 'Balige',
                'bio' => 'Adik perempuan istri',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Generasi 4 (Anak-anak)
        $generation4 = [
            // Keluarga Nainggolan - Generasi 4 (Anak-anak Ego)
            [
                'id' => 19,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Anak Nainggolan',
                'gender' => 'male',
                'birth_date' => '2022-03-10',
                'death_date' => null,
                'father_id' => 11, // Ego Nainggolan
                'mother_id' => 16, // Istri Simanjuntak
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Anak',
                'birth_place' => 'Jakarta',
                'bio' => 'Anak laki-laki Ego',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 20,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Boru Nainggolan',
                'gender' => 'female',
                'birth_date' => '2024-01-15',
                'death_date' => null,
                'father_id' => 11, // Ego Nainggolan
                'mother_id' => 16, // Istri Simanjuntak
                'order_in_siblings' => 2,
                'profile_picture_path' => null,
                'nickname' => 'Boru',
                'birth_place' => 'Jakarta',
                'bio' => 'Anak perempuan Ego',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Keluarga Sitorus - Menantu (untuk Abang)
            [
                'id' => 21,
                'family_id' => 4, // Keluarga Sitorus
                'full_name' => 'Parumaen Sitorus',
                'gender' => 'female',
                'birth_date' => '1999-05-20',
                'death_date' => null,
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Parumaen',
                'birth_place' => 'Tarutung',
                'bio' => 'Istri Abang dari marga Sitorus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Keluarga Panjaitan - Menantu (untuk Anggi)
            [
                'id' => 22,
                'family_id' => 5, // Keluarga Panjaitan
                'full_name' => 'Hela Panjaitan',
                'gender' => 'male',
                'birth_date' => '2000-11-05',
                'death_date' => null,
                'father_id' => null,
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Hela',
                'birth_place' => 'Siborong-borong',
                'bio' => 'Suami Anggi dari marga Panjaitan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Keponakan (anak Abang)
            [
                'id' => 23,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Keponakan Nainggolan',
                'gender' => 'male',
                'birth_date' => '2021-07-25',
                'death_date' => null,
                'father_id' => 12, // Abang Nainggolan
                'mother_id' => 21, // Parumaen Sitorus
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Keponakan',
                'birth_place' => 'Jakarta',
                'bio' => 'Anak laki-laki Abang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Generasi 5 (Cucu)
        $generation5 = [
            // Keluarga Nainggolan - Generasi 5 (Cucu)
            [
                'id' => 24,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Pahompu Nainggolan',
                'gender' => 'male',
                'birth_date' => '2042-06-15',
                'death_date' => null,
                'father_id' => 19, // Anak Nainggolan
                'mother_id' => null,
                'order_in_siblings' => 1,
                'profile_picture_path' => null,
                'nickname' => 'Pahompu',
                'birth_place' => 'Jakarta',
                'bio' => 'Cucu laki-laki Ego',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 25,
                'family_id' => 1, // Keluarga Nainggolan
                'full_name' => 'Pahompu Boru Nainggolan',
                'gender' => 'female',
                'birth_date' => '2044-09-20',
                'death_date' => null,
                'father_id' => 19, // Anak Nainggolan
                'mother_id' => null,
                'order_in_siblings' => 2,
                'profile_picture_path' => null,
                'nickname' => 'Pahompu Boru',
                'birth_place' => 'Jakarta',
                'bio' => 'Cucu perempuan Ego',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        
        // Combine all generations and insert into family_members table
        $familyMembers = array_merge($generation1, $generation2, $generation3, $generation4, $generation5);
        
        // Convert birth_date and death_date to Carbon instances
        foreach ($familyMembers as &$member) {
            if (!empty($member['birth_date'])) {
                $member['birth_date'] = Carbon::parse($member['birth_date']);
            }
            if (!empty($member['death_date'])) {
                $member['death_date'] = Carbon::parse($member['death_date']);
            }
        }
        
        // Insert data into family_members table
        FamilyMember::insert($familyMembers);
        
        $this->command->info('Family members table seeded successfully!');
    }
}
