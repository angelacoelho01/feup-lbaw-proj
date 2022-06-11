<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateSeeder extends Seeder
{
    /**
     * Run the database creation seed.
     *
     * @return void
     */
    public function run()
    {
        $path = 'resources/sql/create.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Created the Schema');
    }
}
