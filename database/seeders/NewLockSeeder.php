<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewLockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = 'resources/sql/newlocks.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Added new locks');
    }
}
