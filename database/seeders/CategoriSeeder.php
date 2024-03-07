<?php

namespace Database\Seeders;

use App\Models\Category;

use Illuminate\Database\Seeder;

class CategoriSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Category::create([
            'name' => 'Pemrograman',
            'instruction' => 'Pilihlah salah satu jawaban yang menurut anda benar',
            'img' => 'pemrograman.jpg',
            'bts_nilai' => '0-100'
        ]);
        \App\Models\Category::create([
            'name' => 'Matematika',
            'instruction' => 'Pilihlah salah satu jawaban yang menurut anda benar',
            'img' => 'matematika.jpg',
            'bts_nilai' => '0-100'
        ]);
    }
}
