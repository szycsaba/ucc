<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = explode('|', (string) env('SEED_USERNAMES', ''));
        $emails = explode('|', (string) env('SEED_EMAILS', ''));
        $passwords = explode('|', (string) env('SEED_PASSWORDS', ''));
        $roles = explode('|', (string) env('SEED_ROLES', ''));

        $n = count($names);
        if (count($emails) !== $n || count($passwords) !== $n || count($roles) !== $n) {
            throw new RuntimeException('Seed lists must have the same length: SEED_USERNAMES/SEED_EMAILS/SEED_PASSWORDS/SEED_ROLES');
        }

        for ($i = 0; $i < $n; $i++) {
            User::updateOrCreate(
                ['email' => $emails[$i]],
                [
                    'name' => $names[$i],
                    'password' => $passwords[$i],
                    'role' => $roles[$i],
                ],
            );
        }
    }
}
