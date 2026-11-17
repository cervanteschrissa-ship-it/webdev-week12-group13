<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $alice = User::factory()->create([
            'name'  => 'Alice',
            'email' => 'alice@example.com',
        ]);

        $bob = User::factory()->create([
            'name'  => 'Bob',
            'email' => 'bob@example.com',
        ]);

        // 12 books so the list shows a second page (10 per page)
        $alice->books()->saveMany(Book::factory()->count(12)->make());
        $bob->books()->saveMany(Book::factory()->count(4)->make());
    }
}