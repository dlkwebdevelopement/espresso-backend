<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\ItemAttributeOption;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks for truncation
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        ItemAttributeOption::truncate();
        ItemAttribute::truncate();
        Item::truncate();
        SubCategory::truncate();
        Category::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Ensure at least one store exists
        $store = Store::firstOrCreate(
            ['name' => 'Main Espresso'],
            [
                'location' => 'Manama, Bahrain',
                'contact_number' => '+973 12345678',
                'is_active' => true,
            ]
        );

        // 2. Define Categories and Sub-Categories
        $catalog = [
            'Beverages' => [
                'Hot Coffee' => ['Spanish Latte', 'Flat White', 'Cappuccino'],
                'Cold Coffee' => ['Iced Spanish Latte', 'Cold Brew', 'Iced Americano'],
                'Signature' => ['Espresso Special', 'Caramel Macchiato'],
            ],
            'Bakery' => [
                'Croissants' => ['Butter Croissant', 'Cheese Croissant', 'Almond Croissant'],
                'Muffins' => ['Blueberry Muffin', 'Double Chocolate Muffin'],
                'Cakes' => ['Tiramisu', 'Saffron Cake'],
            ]
        ];

        foreach ($catalog as $catName => $subCats) {
            $category = Category::create([
                'name' => $catName,
                'slug' => Str::slug($catName),
                'is_active' => true,
            ]);

            foreach ($subCats as $subName => $items) {
                $subCategory = SubCategory::create([
                    'category_id' => $category->id,
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                    'is_active' => true,
                ]);

                foreach ($items as $itemName) {
                    $item = Item::create([
                        'store_id' => $store->id,
                        'sub_category_id' => $subCategory->id,
                        'name' => $itemName,
                        'description' => "Freshly prepared {$itemName} from Espresso.",
                        'base_price' => rand(1500, 3500) / 1000, // Random price between 1.500 and 3.500
                        'is_available' => true,
                    ]);

                    // Add dynamic attributes if it's a beverage
                    if ($catName === 'Beverages') {
                        $sizeAttr = $item->attributes()->create(['name' => 'Size']);
                        $sizeAttr->options()->create(['value' => 'Small', 'price_adjustment' => 0]);
                        $sizeAttr->options()->create(['value' => 'Medium', 'price_adjustment' => 0.400]);
                        $sizeAttr->options()->create(['value' => 'Large', 'price_adjustment' => 0.700]);

                        $sugarAttr = $item->attributes()->create(['name' => 'Sugar Level']);
                        $sugarAttr->options()->create(['value' => 'No Sugar', 'price_adjustment' => 0]);
                        $sugarAttr->options()->create(['value' => 'Half Sugar', 'price_adjustment' => 0]);
                        $sugarAttr->options()->create(['value' => 'Normal Sugar', 'price_adjustment' => 0]);
                    }
                }
            }
        }
    }
}
