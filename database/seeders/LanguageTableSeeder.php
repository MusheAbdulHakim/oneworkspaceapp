<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $langage = [
            "ar" => "Arabic",
            "da" => "Danish",
            "de" => "German",
            "en" => "English",
            "es" => "Spanish",
            "fr" => "French",
            "it" => "Italian",
            "ja" => "Japanese",
            "nl" => "Dutch",
            "pl" => "Polish",
            "pt" => "Portuguese",
            "ru" => "Russian",
            "tr" => "Turkish"
        ];
        foreach ($langage as  $key => $value) {
            $check = Language::where('code',$key)->first();
            if(empty($check))
            {
                $langage_data           = new Language();
                $langage_data->code     = $key;
                $langage_data->name     = $value;
                $langage_data->status   = 1;
                $langage_data->save();
            }
        }
    }
}
