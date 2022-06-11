<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LockSeeder extends Seeder
{

    public function run()
    {
        $path = 'resources/sql/locks.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Created the Schemas Locks!');
    }
}
