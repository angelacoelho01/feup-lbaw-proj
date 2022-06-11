<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateSeeder extends Seeder
{

    public function run()
    {
        $path = 'resources/sql/populate.sql';
        DB::unprepared(file_get_contents($path));
        $this->command->info('Populated the Schema');
    }
}
