<?php

namespace Database\Seeders;

use App\Models\Entry;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use DateTime;

class EntriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('entries')->insert([
            'title' => "Nina's erster Entry",
            'description' => "Das ist der erste Eintrag",
            'padlet_id' => 1,
            'user_id' => 1,
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);

        $entry2 = new Entry();
        $entry2->title = "Entry2";
        $entry2->description = "Das ist mein zweiter Eintrag";
        $entry2->padlet_id = 3;
        $entry2->user_id = 2;
        $entry2->save();
    }
}
