<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $jsonPath = database_path('data/Case_Products.json');
        if (File::exists($jsonPath)) {
            $jsonData = File::get($jsonPath);
            $data = json_decode($jsonData, true);
            foreach ($data as $productData) {
                Product::create([
                    'product_id' => $productData['product_id'],
                    'title' => $productData['title'],
                    'category_id' => $productData['category_id'],
                    'category_title' => $productData['category_title'],
                    'author' => $productData['author'],
                    'list_price' => $productData['list_price'],
                    'stock_quantity' => $productData['stock_quantity'],
                ]);
            }
        } else {
            echo "JSON file not found.";
        }
    }
}
