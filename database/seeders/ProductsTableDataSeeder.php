<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductsTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 20; $i++) { 
	    	Product::create([
	            'name' => Str::random(10),
                'price' => 100.00,
                'category' => 'Fruit',
	            'image' => '',
	        ]);
    	}
    }
}
