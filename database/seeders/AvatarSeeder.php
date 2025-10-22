<?php

namespace Database\Seeders;

use App\Infrastructure\Models\Avatar;
use Illuminate\Database\Seeder;

class AvatarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $avatars = [
            [
                'url' => 'https://example.com/avatars/avatar1.png',
                'coins' => 0, // Free avatar
            ],
            [
                'url' => 'https://example.com/avatars/avatar2.png',
                'coins' => 100,
            ],
            [
                'url' => 'https://example.com/avatars/avatar3.png',
                'coins' => 250,
            ],
            [
                'url' => 'https://example.com/avatars/avatar4.png',
                'coins' => 500,
            ],
            [
                'url' => 'https://example.com/avatars/avatar5.png',
                'coins' => 750,
            ],
            [
                'url' => 'https://example.com/avatars/avatar6.png',
                'coins' => 1000,
            ],
            [
                'url' => 'https://example.com/avatars/avatar7.png',
                'coins' => 1500,
            ],
            [
                'url' => 'https://example.com/avatars/avatar8.png',
                'coins' => 2000,
            ],
        ];

        foreach ($avatars as $avatarData) {
            Avatar::create($avatarData);
        }
    }
}
