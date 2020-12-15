<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'category'=>'Good Morning'
        ]);
        Category::create([
            'category'=>'Good Night'
        ]);
        Category::create([
            'category'=>'Gujarati Quotes'
        ]);
        Category::create([
            'category'=>'Hindi Quotes'
        ]);
    }
}
