<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nom' => 'administrateur', "description" => null],
            ['nom' => 'superviseur', "description" => null],
            ['nom' => 'agent', "description" => null]
            ];
        DB::table('roles')->insert($roles);
    }
}
