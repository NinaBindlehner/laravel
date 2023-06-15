<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$roles = ['admin', 'editor', 'viewer']; //ev. admin, provider, customer oder owner oder so statt CRUD
        $roles = [
            ['title' => 'admin'],
            //['title' => 'editor'],
            ['title' => 'viewer'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
