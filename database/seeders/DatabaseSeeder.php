<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CreateSeeder::class,
            PopulateSeeder::class,
            LockSeeder::class,
            NewLockSeeder::class,
        ]);
        $this->command->info('Database seeded!');
    }
}
