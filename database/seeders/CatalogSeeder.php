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

        // 1. Ensure stores exist
        $bahrainStore = Store::firstOrCreate(
            ['name' => 'Main Espresso Bahrain'],
            [
                'location'       => 'Manama, Bahrain',
                'contact_number' => '+973 12345678',
                'is_active'      => true,
            ]
        );

        // 2. Define Categories, Sub-Categories and Items
        // Exactly 5 items are marked is_weekly_favorite (★) for Featured Brews section
        $catalog = [
            'Classics' => [
                'Espresso' => [
                    ['name' => 'Espresso', 'price' => 1.600, 'description' => 'Rich single-shot espresso with a smooth crema.', 'image' => null],
                ],
                'Black Coffee' => [
                    ['name' => 'Long Black (Hot/Iced)', 'price' => 1.800, 'description' => 'Espresso topped with hot water for a bold, smooth taste.', 'image' => null],
                ],
                'Milk Coffee' => [
                    ['name' => 'Flat White', 'price' => 2.200, 'description' => 'Double espresso with silky steamed milk.', 'image' => null],
                    ['name' => 'Latte (Hot/Iced)', 'price' => 2.200, 'description' => 'Creamy espresso blended with steamed milk.', 'image' => null],
                ],
                'Specialty Latte' => [
                    ['name' => 'Sea Salt Latte (Hot/Iced)', 'price' => 2.500, 'description' => 'Smooth latte finished with a touch of sea salt.', 'image' => null],
                ]
            ],
            'Mocha' => [
                'White Chocolate' => [
                    ['name' => 'White Mocha (Hot/Iced)', 'price' => 2.500, 'description' => 'Espresso blended with creamy white chocolate.', 'image' => null],
                ],
                'Dark Chocolate' => [
                    ['name' => 'Dark Mocha (Hot/Iced)', 'price' => 2.500, 'description' => 'Rich dark chocolate mixed with premium espresso.', 'image' => null],
                ],
                'Chocolate Drinks' => [
                    ['name' => 'Hot Chocolate (Hot/Iced)', 'price' => 2.500, 'description' => 'Creamy premium cocoa served hot or iced.', 'image' => null],
                ]
            ],
            'Signature Coffees' => [
                'House Specials' => [
                    ['name' => 'Spanish Latte (Hot/Iced)', 'price' => 2.500, 'description' => 'Espresso with sweet condensed milk and steamed milk.', 'image' => null],
                ],
                'Sweet Latte' => [
                    ['name' => 'Salted Caramel Latte (Hot/Iced)', 'price' => 2.500, 'description' => 'Espresso with buttery salted caramel.', 'image' => null],
                ],
                'Coconut Coffee' => [
                    ['name' => 'Cococcino Latte (Hot/Iced)', 'price' => 2.750, 'description' => 'Creamy coconut-inspired espresso latte.', 'image' => null],
                ]
            ],
            'Drip Coffee' => [
                'Pour Over' => [
                    ['name' => 'V60 (Hot/Iced)', 'price' => 2.750, 'description' => 'Hand-poured coffee with clean and bright flavors.', 'image' => null],
                ],
                'Cold Brew' => [
                    ['name' => 'Cold Brew', 'price' => 2.750, 'description' => 'Smooth slow-steeped cold coffee.', 'image' => null],
                ]
            ],
            'Non Coffee' => [
                'Matcha' => [
                    ['name' => 'Classic Matcha (Hot/Iced)', 'price' => 2.750, 'description' => 'Ceremonial-grade Japanese matcha.', 'image' => null],
                    ['name' => 'Strawberry Matcha (Iced)', 'price' => 3.000, 'description' => 'Refreshing matcha layered with sweet strawberry.', 'image' => null],
                ]
            ],
            'Bakery & Desserts' => [
                'Danish Pastries' => [
                    ['name' => 'Strawberry Danish', 'price' => 1.500, 'description' => 'Flaky Danish pastry filled with strawberry.', 'image' => null],
                    ['name' => 'Mushroom Danish', 'price' => 1.500, 'description' => 'Savory pastry with creamy mushroom filling.', 'image' => null],
                    ['name' => 'Pizza Danish', 'price' => 1.500, 'description' => 'Danish topped with pizza sauce and cheese.', 'image' => null],
                    ['name' => 'Feta Cheese Danish', 'price' => 1.500, 'description' => 'Buttery pastry filled with feta cheese.', 'image' => null],
                    ['name' => 'Salted Caramel Danish', 'price' => 1.500, 'description' => 'Flaky pastry with rich salted caramel filling.', 'image' => null],
                    ['name' => 'Strawberry Cheesecake Danish', 'price' => 1.950, 'description' => 'Cheesecake-filled Danish with strawberry topping.', 'image' => null],
                ],
                'Croissants' => [
                    ['name' => 'Butter Croissant', 'price' => 1.100, 'description' => 'Authentic buttery French croissant.', 'image' => null],
                    ['name' => 'Three Cheese Croissant', 'price' => 1.500, 'description' => 'Croissant filled with three premium cheeses.', 'image' => null],
                ],
                'Specialty Pastries' => [
                    ['name' => 'Strawberry Cruffin', 'price' => 1.950, 'description' => 'Croissant-muffin pastry with strawberry filling.', 'image' => null],
                ],
                'Brownies' => [
                    ['name' => 'Brownies', 'price' => 1.100, 'description' => 'Rich and fudgy chocolate brownie.', 'image' => null],
                ],
                'Cookies' => [
                    ['name' => 'Classic Cookies', 'price' => 1.250, 'description' => 'Freshly baked buttery cookies.', 'image' => null],
                    ['name' => 'Kinder Cookies', 'price' => 1.650, 'description' => 'Soft cookies with Kinder chocolate pieces.', 'image' => null],
                    ['name' => 'Salted Caramel Cookies', 'price' => 1.650, 'description' => 'Cookies infused with salted caramel.', 'image' => null],
                ],
                'Cakes' => [
                    ['name' => 'San Sebastián Cake', 'price' => 3.250, 'description' => 'Creamy Basque burnt cheesecake.', 'image' => null],
                    ['name' => 'Chocolate Crunchy Cake', 'price' => 2.750, 'description' => 'Moist chocolate cake with crunchy topping.', 'image' => null],
                    ['name' => 'Tiramisu Cake', 'price' => 2.750, 'description' => 'Italian coffee-flavored mascarpone cake.', 'image' => null],
                    ['name' => 'London Cake', 'price' => 2.750, 'description' => 'Soft sponge cake with creamy layers.', 'image' => null],
                    ['name' => 'Mango Cheesecake', 'price' => 1.950, 'description' => 'Creamy cheesecake topped with sweet mango.', 'image' => null],
                ]
            ]
        ];



        foreach ($catalog as $catName => $subCats) {
            $category = Category::create([
                'name'      => $catName,
                'slug'      => Str::slug($catName),
                'is_active' => true,
            ]);

            foreach ($subCats as $subName => $items) {
                $subCategory = SubCategory::create([
                    'category_id' => $category->id,
                    'name'        => $subName,
                    'slug'        => Str::slug($subName),
                    'is_active'   => true,
                ]);

                foreach ($items as $itemData) {
                    $originalName = $itemData['name'];
                    $cleanName = str_replace([' (Hot/Iced)', ' (Iced)'], '', $originalName);

                    // Create item for Bahrain Store
                    $itemB = Item::create([
                        'store_id'         => $bahrainStore->id,
                        'sub_category_id'  => $subCategory->id,
                        'name'             => $cleanName,
                        'description'      => $itemData['description'] ?? null,
                        'base_price'       => $itemData['price'],
                        'image_path'       => $itemData['image'] ?? null,
                        'is_weekly_favorite' => $itemData['is_weekly_favorite'] ?? false,
                        'is_popular'       => $itemData['is_popular'] ?? false,
                        'is_available'     => true,
                    ]);

                    foreach ([$itemB] as $item) {
                        // Temperature Attribute based on original name
                        if (str_contains($originalName, '(Hot/Iced)')) {
                            $tempAttr = $item->attributes()->create(['name' => 'Temperature']);
                            $tempAttr->options()->create(['value' => 'Hot', 'price_adjustment' => 0]);
                            $tempAttr->options()->create(['value' => 'Iced', 'price_adjustment' => 0]);
                        } elseif (str_contains($originalName, '(Iced)')) {
                            $tempAttr = $item->attributes()->create(['name' => 'Temperature']);
                            $tempAttr->options()->create(['value' => 'Iced', 'price_adjustment' => 0]);
                        }
                    }
                }
            }
        }
    }
}
