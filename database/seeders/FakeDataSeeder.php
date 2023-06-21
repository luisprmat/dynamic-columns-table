<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Seeder;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sizes = [
            'XS',
            'S',
            'M',
            'L',
            'XL',
            'XXL',
        ];

        foreach ($sizes as $size) {
            Size::create(['name' => $size]);
        }

        $colors = [
            'Rojo',
            'Azul',
            'Verde',
            'Amarillo',
            'Negro',
            'Blanco',
        ];

        foreach ($colors as $color) {
            Color::create(['name' => $color]);
        }

        $productNames = collect([
            'Camiseta',
            'Camisa Polo',
            'Sudadera con Capucha',
            'Sudadera',
            'Chaqueta',
            'Jeans',
            'Pantalones',
        ]);

        $productColors = Color::pluck('name', 'id');
        $productSizes = Size::all();

        // We will create 10 products
        for ($i = 1; $i <= 10; $i++) {
            $product = Product::create([
                // Pick one random name from the Collection
                'name' => $productNames->random(),
                'code' => rand(1, 1000),
            ]);

            foreach ($productColors as $colorId => $colorName) {
                // We generate up to 6 random sizes
                // But they shouldn't be repeated, so we have $usedSizes
                $usedSizes = [];
                for ($j = 0; $j < rand(0, 6); $j++) {
                    $size = $productSizes->whereNotIn('id', $usedSizes)->random();
                    if ($size && $colorId) {
                        $usedSizes[] = $size->id;

                        // We use hasMany relationship to create record
                        $product->productColorSizes()->create([
                            'size_id' => $size->id,
                            'color_id' => $colorId,
                            'reference_number' => str($product->code)->append(
                                '-',
                                str($colorName)
                                    ->limit(2, '')
                                    ->upper(),
                                '-',
                                str($size->name)
                                    ->upper(),
                            ),
                        ]);
                    }
                }
            }
        }
    }
}
