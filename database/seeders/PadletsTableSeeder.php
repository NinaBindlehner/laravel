<?php

namespace Database\Seeders;

use App\Models\Padlet;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;

class PadletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*DB::table('padlets')->insert([
            'title' => "Nina's erstes Padlet",
            'description' => "Dann schau ma mal was passiert",
            'is_public' => true,
            'user_id' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);*/

        $padlet = new Padlet();
        $padlet->title = "Padlet1";
        $padlet->description = "Das ist mein erstes Padlet";
        $padlet->is_public = true;
        $padlet->user_id = 1;

        //User holen
        $user = User::first();
        $padlet->user()->associate($user);

        $padlet->save();


        $padlet2 = new Padlet();
        $padlet2->title = "Padlet2";
        $padlet2->description = "Das ist ein neues Padlet2";
        $padlet2->is_public = false;
        $padlet2->user_id = 2;
        $padlet2->user()->associate($user);
        $padlet2->save();

        $padlet3 = new Padlet();
        $padlet3->title = "Padlet3";
        $padlet3->description = "Das ist ein neues Padlet3";
        $padlet3->is_public = true;
        $padlet3->user_id = 2;

        //User wird zu Padlet3 hinzugefügt
        $padlet3->user()->associate($user);
        $padlet3->save();

        //User, Padlets und Rollen verbinden
        $users = User::all()->pluck("id");
        $roles = Role::all()->pluck("id");
        $padlet3->users()->syncWithPivotValues($users, ['role_id' => $roles[1]]);

        $padlet3->save();

        $padlet4 = new Padlet();
        $padlet4->title = "Padlet4";
        $padlet4->description = "Das ist ein neues Padlet4";
        $padlet4->is_public = true;
        $padlet4->user_id = 2;
        $padlet4->user()->associate($user);
        $padlet4->save();

    }
}
