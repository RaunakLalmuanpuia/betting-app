<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'mobile' => '9999999999', // fixed 10-digit unique mobile
                'password' => Hash::make('password'), // you can customize this
            ]
        );
        $admin->assignRole('Admin');

        // Helper function to generate unique 10-digit mobiles
        $existingMobiles = [$admin->mobile];
        function generateUniqueMobile(array &$existingMobiles) {
            do {
                $mobile = '9' . mt_rand(100000000, 999999999); // starts with 9 + 9 digits
            } while (in_array($mobile, $existingMobiles));
            $existingMobiles[] = $mobile;
            return $mobile;
        }

        // Create 49 Player users
        for ($i = 1; $i <= 49; $i++) {
            $mobile = generateUniqueMobile($existingMobiles);
            $player = User::firstOrCreate(
                ['email' => "player{$i}@example.com"],
                [
                    'name' => "Player {$i}",
                    'mobile' => $mobile,
                    'password' => Hash::make('password'),
                ]
            );
            $player->assignRole('Player');
        }
    }
}
