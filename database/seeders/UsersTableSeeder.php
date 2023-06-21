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
        $user1->role = "admin";
        $user1->save();

        $user2 = new \App\Models\User;
        $user2->firstname = "Max";
        $user2->lastname = "Mustermann";
        $user2->email = "max.muster@test.at";
        $user2->password = bcrypt('test1234');
        $user2->image = "https://www.tipps-vom-experten.de/uploads/2018/04/mann-schoen-gesicht-lacht-fotolia-viacheslav-iakobchuk-700xpl.jpg";
        $user2->role = "admin";
        $user2->save();

        $user3 = new \App\Models\User;
        $user3->firstname = "Jane";
        $user3->lastname = "Doe";
        $user3->email = "jane.doe@test.at";
        $user3->password = bcrypt('test1234');
        $user3->image = "https://www.schaebens.de/wp-content/uploads/2019/03/schaebens-experten-m%C3%A4nner-selbstbewusste-frau-lacht.jpeg";
        $user3->role = "viewer";
        $user3->save();

        //update
        $user1->firstname= 'Nina';
        $user1->save();

        //delete
        //$user = App\Models\User::find(1);
        //$user3->delete();

    }
}
