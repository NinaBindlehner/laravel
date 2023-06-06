<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*DB::table('users')->insert([
            'firstname' => "Nina",
            'lastname' => "Bindlehner",
            'email' => "nina.bindi@test.at",
            'password' => "test1234",
            'image' => "https://www.zooroyal.at/magazin/wp-content/uploads/2022/09/katze-im-herbst-760x570-1-scaled.jpg",
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);*/

        //insert
        $user1 = new \App\Models\User;
        $user1->firstname = "Nina2";
        $user1->lastname = "Bindlehner";
        $user1->email = "nina.bindi@test.at";
        $user1->password = bcrypt('test1234'); //Passwort verschlüsseln
        $user1->image = "https://www.zooroyal.at/magazin/wp-content/uploads/2022/09/katze-im-herbst-760x570-1-scaled.jpg";
        $user1->save();

        $user2 = new \App\Models\User;
        $user2->firstname = "Max";
        $user2->lastname = "Mustermann";
        $user2->email = "max.muster@test.at";
        $user2->password = bcrypt('test1234');
        $user2->image = "https://www.zooroyal.at/magazin/wp-content/uploads/2022/09/katze-im-herbst-760x570-1-scaled.jpg";
        $user2->save();

        $user3 = new \App\Models\User;
        $user3->firstname = "Jane";
        $user3->lastname = "Doe";
        $user3->email = "jane.doe@test.at";
        $user3->password = bcrypt('test1234');
        $user3->image = "https://www.zooroyal.at/magazin/wp-content/uploads/2022/09/katze-im-herbst-760x570-1-scaled.jpg";
        $user3->save();

        //update
        //$user = App\Models\User::find(1);
        $user1->firstname= 'Nina';
        $user1->save();

        //delete
        //$user = App\Models\User::find(1);
        $user3->delete();

        //geht a ned...
        //$user3 = App\Models\User::firstOrCreate(['firstname' => 'Max','lastname' => 'Mustermann']);

        /*$padlet2 = new \App\Models\Padlet;
        $padlet2->title = "Neues Padlet2";
        $padlet2->description = "Das ist ein neues Padlet2";*/
        //$padlet2->is_public = true;

        /*$padlet3 = new \App\Models\Padlet;
        $padlet3->title = "Neues Padlet3";
        $padlet3->description = "Das ist ein neues Padlet3";*/
        //$padlet3->is_public = true;

        /*$user1->padlets()->saveMany([$padlet2, $padlet3]);
        $user1->save();*/
    }
}
