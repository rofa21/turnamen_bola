<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use App\Models\MatchSchedule;
use App\Models\Operator;
use App\Models\Player;
use App\Models\PlayerDocument;
use App\Models\PlayerVerification;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $ku10 = AgeCategory::where('name', 'KU-10')->first();
        $ku12 = AgeCategory::where('name', 'KU-12')->first();

        // Ensure dummy documents folder exists
        Storage::disk('public')->makeDirectory('documents');

        // Demo Operators (SSB)
        $operatorsData = [
            ['name' => 'SSB Garuda Muda Sruweng', 'pic_name' => 'Ahmad Fauzi', 'phone' => '085876543210', 'district' => 'Sruweng', 'username' => 'op_garudamuda', 'category' => $ku10],
            ['name' => 'SSB Putra Pandan Kuning', 'pic_name' => 'Budi Santoso', 'phone' => '081234567890', 'district' => 'Kebumen', 'username' => 'op_pandankuning', 'category' => $ku12],
            ['name' => 'SSB Meteor Kebumen', 'pic_name' => 'Rian Hidayat', 'phone' => '087712345678', 'district' => 'Pejagoan', 'username' => 'op_meteorkebumen', 'category' => $ku10],
            ['name' => 'SSB Bina Remaja Kutowinangun', 'pic_name' => 'Hendra Wijaya', 'phone' => '085612345678', 'district' => 'Kutowinangun', 'username' => 'op_binaremaja', 'category' => $ku12],
        ];

        $operators = [];
        foreach ($operatorsData as $data) {
            $category = $data['category'];
            unset($data['category']);
            $op = Operator::updateOrCreate(
                ['username' => $data['username']],
                array_merge($data, ['password' => Hash::make('password123'), 'status' => 'active'])
            );
            $operators[] = ['op' => $op, 'category' => $category];
        }

        // Create teams
        $playersData = [
            // SSB Garuda Muda - KU-10
            [
                ['name' => 'Rizki Firmansyah', 'nik' => '3305010101160001', 'birth_date' => '2016-03-15', 'jersey_number' => 1, 'position' => 'Kiper'],
                ['name' => 'Ahmad Hafidz', 'nik' => '3305010201160002', 'birth_date' => '2016-05-22', 'jersey_number' => 7, 'position' => 'Penyerang'],
                ['name' => 'Dika Saputra', 'nik' => '3305010301160003', 'birth_date' => '2016-08-10', 'jersey_number' => 10, 'position' => 'Gelandang'],
                ['name' => 'Fajar Nugroho', 'nik' => '3305010401160004', 'birth_date' => '2016-11-03', 'jersey_number' => 5, 'position' => 'Bek'],
                ['name' => 'Gilang Pratama', 'nik' => '3305010501160005', 'birth_date' => '2016-02-28', 'jersey_number' => 3, 'position' => 'Bek'],
            ],
            // SSB Pandan Kuning - KU-12
            [
                ['name' => 'Rizky Ramadhan', 'nik' => '3305020101140001', 'birth_date' => '2014-04-12', 'jersey_number' => 7, 'position' => 'Penyerang'],
                ['name' => 'Ahmad Dani', 'nik' => '3305020201140002', 'birth_date' => '2014-06-18', 'jersey_number' => 10, 'position' => 'Gelandang'],
                ['name' => 'Bagas Pratama', 'nik' => '3305020301140003', 'birth_date' => '2014-09-05', 'jersey_number' => 1, 'position' => 'Kiper'],
                ['name' => 'Candra Dewangga', 'nik' => '3305020401140004', 'birth_date' => '2014-12-20', 'jersey_number' => 4, 'position' => 'Bek'],
                ['name' => 'Eko Prasetyo', 'nik' => '3305020501140005', 'birth_date' => '2014-01-07', 'jersey_number' => 8, 'position' => 'Gelandang'],
            ],
            // SSB Meteor Kebumen - KU-10
            [
                ['name' => 'Farel Haditama', 'nik' => '3305030101160001', 'birth_date' => '2016-07-14', 'jersey_number' => 9, 'position' => 'Penyerang'],
                ['name' => 'Galih Prabowo', 'nik' => '3305030201160002', 'birth_date' => '2016-04-25', 'jersey_number' => 2, 'position' => 'Bek'],
                ['name' => 'Hendra Kurniawan', 'nik' => '3305030301160003', 'birth_date' => '2016-10-11', 'jersey_number' => 6, 'position' => 'Gelandang'],
                ['name' => 'Ilham Maulana', 'nik' => '3305030401160004', 'birth_date' => '2016-03-30', 'jersey_number' => 11, 'position' => 'Penyerang'],
            ],
            // SSB Bina Remaja - KU-12
            [
                ['name' => 'Joko Widodo', 'nik' => '3305040101140001', 'birth_date' => '2014-08-17', 'jersey_number' => 7, 'position' => 'Penyerang'],
                ['name' => 'Kevin Pratama', 'nik' => '3305040201140002', 'birth_date' => '2014-02-14', 'jersey_number' => 1, 'position' => 'Kiper'],
                ['name' => 'Lutfi Hakim', 'nik' => '3305040301140003', 'birth_date' => '2014-05-19', 'jersey_number' => 5, 'position' => 'Bek'],
            ],
        ];

        $teams = [];
        foreach ($operators as $index => $opData) {
            $op = $opData['op'];
            $category = $opData['category'];
            $team = Team::updateOrCreate(
                ['operator_id' => $op->id, 'age_category_id' => $category->id],
                [
                    'operator_id' => $op->id,
                    'age_category_id' => $category->id,
                    'name' => $op->name,
                    'district' => $op->district,
                    'jersey_color' => ['Biru-Putih', 'Kuning-Hitam', 'Merah-Putih', 'Hijau-Putih'][$index],
                    'manager_name' => $op->pic_name,
                    'manager_phone' => $op->phone,
                ]
            );

            // Add players
            $pData = $playersData[$index] ?? [];
            foreach ($pData as $pIdx => $pd) {
                $player = Player::updateOrCreate(
                    ['nik' => $pd['nik']],
                    array_merge($pd, [
                        'team_id' => $team->id,
                        'age_category_id' => $category->id,
                        'birth_place' => 'Kebumen',
                        'registration_number' => Player::generateRegistrationNumber($category->id, 2026),
                    ])
                );

                // Seed sample documents for players
                // Every player has Foto, Akta, KK, plus ONE supporting doc (KIA, Ijazah, or NISN)
                $docTypesSeeded = ['foto', 'akta', 'kk'];
                if ($pIdx % 3 === 0) $docTypesSeeded[] = 'kia';
                elseif ($pIdx % 3 === 1) $docTypesSeeded[] = 'ijazah';
                else $docTypesSeeded[] = 'nisn';

                foreach ($docTypesSeeded as $type) {
                    PlayerDocument::updateOrCreate(
                        ['player_id' => $player->id, 'type' => $type],
                        [
                            'original_name' => "dokumen_{$type}_{$player->id}.jpg",
                            'file_path' => "documents/sample_{$type}.jpg",
                        ]
                    );
                }

                // Create verification records
                $ageValid = $player->checkAgeValidity();
                $verStatus = $ageValid ? 'approved' : 'pending';
                if ($index === 1 && in_array($pd['name'], ['Rizky Ramadhan', 'Ahmad Dani'])) {
                    $verStatus = 'pending';
                }
                if ($index === 2 && in_array($pd['name'], ['Ilham Maulana'])) {
                    $verStatus = 'rejected';
                }

                PlayerVerification::updateOrCreate(
                    ['player_id' => $player->id],
                    ['status' => $verStatus, 'age_valid' => $ageValid, 'notes' => $verStatus === 'rejected' ? 'Dokumen perlu diperbarui' : 'Dokumen lengkap & terverifikasi']
                );
            }

            $teams[] = $team;
        }

        // Demo Match Schedules
        if (count($teams) >= 4) {
            $matches = [
                ['age_category_id' => $ku10->id, 'home_team_id' => $teams[0]->id, 'away_team_id' => $teams[2]->id, 'round' => 'penyisihan', 'group_name' => 'Grup A', 'match_date' => '2026-07-25', 'match_time' => '08:00', 'location' => 'Stadion Chandradimuka Kebumen', 'status' => 'scheduled'],
                ['age_category_id' => $ku12->id, 'home_team_id' => $teams[1]->id, 'away_team_id' => $teams[3]->id, 'round' => 'penyisihan', 'group_name' => 'Grup B', 'match_date' => '2026-07-25', 'match_time' => '09:30', 'location' => 'Stadion Chandradimuka Kebumen', 'status' => 'scheduled'],
            ];

            foreach ($matches as $match) {
                MatchSchedule::create($match);
            }
        }
    }
}
