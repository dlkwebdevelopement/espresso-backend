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

        $chennaiStore = Store::firstOrCreate(
            ['name' => 'Main Espresso Chennai'],
            [
                'location'       => 'Chennai, India',
                'contact_number' => '+91 44 1234 5678',
                'is_active'      => true,
            ]
        );

        // 2. Define Categories, Sub-Categories and Items
        // Exactly 5 items are marked is_weekly_favorite (★) for Featured Brews section
        $catalog = [

            // ─── Category 1: Beverages ────────────────────────────────────────
            'Beverages' => [
                'Hot Coffee' => [
                    ['name' => 'Spanish Latte',     'image' => 'uploads/items/imgg.jpeg',   'price' => 2.500, 'is_popular' => true,  'is_weekly_favorite' => true],  // ★
                    ['name' => 'Flat White',         'image' => 'uploads/items/imgg1.jpeg',  'price' => 2.300, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Cappuccino',         'image' => 'uploads/items/imgg2.jpeg',  'price' => 2.000, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Americano',          'image' => 'uploads/items/imgg3.jpeg',  'price' => 1.800, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Macchiato',          'image' => 'uploads/items/imgg4.jpeg',  'price' => 2.100, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Latte',              'image' => 'uploads/items/imgg12.jpeg', 'price' => 2.200, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
                'Cold Coffee' => [
                    ['name' => 'Iced Spanish Latte',          'image' => 'uploads/items/imgg5.jpeg',  'price' => 2.800, 'is_popular' => true,  'is_weekly_favorite' => true],  // ★
                    ['name' => 'Cold Brew',                   'image' => 'uploads/items/imgg6.jpeg',  'price' => 2.500, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Iced Americano',              'image' => 'uploads/items/imgg7.jpeg',  'price' => 2.200, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Iced Mocha',                  'image' => 'uploads/items/imgg8.jpeg',  'price' => 2.900, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Brown Sugar Shaken Espresso', 'image' => 'uploads/items/imgg13.jpeg', 'price' => 3.100, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Honey Almond Cold Brew',      'image' => 'uploads/items/img.jpeg',   'price' => 3.200, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
                'Signature' => [
                    ['name' => 'Espresso Special',          'image' => 'uploads/items/img1.jpeg', 'price' => 3.500, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Caramel Macchiato Special', 'image' => 'uploads/items/img2.jpeg', 'price' => 3.800, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Honey Almond Latte',        'image' => 'uploads/items/img3.jpeg', 'price' => 4.200, 'is_popular' => true,  'is_weekly_favorite' => true],  // ★
                    ['name' => 'Salted Caramel Mocha',      'image' => 'uploads/items/img4.jpeg', 'price' => 4.500, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Lavender Oat Latte',        'image' => 'uploads/items/img5.jpeg', 'price' => 4.300, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
            ],

            // ─── Category 2: Bakery ───────────────────────────────────────────
            'Bakery' => [
                'Croissants' => [
                    ['name' => 'Butter Croissant',  'image' => 'uploads/items/img6.jpeg',  'price' => 1.200, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Cheese Croissant',  'image' => 'uploads/items/img7.jpeg',  'price' => 1.400, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Almond Croissant',  'image' => 'uploads/items/img8.jpeg',  'price' => 1.600, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Chocolate Croissant','image' => 'uploads/items/imgg9.jpeg', 'price' => 1.500, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
                'Muffins' => [
                    ['name' => 'Blueberry Muffin',        'image' => 'uploads/items/img9.jpeg',  'price' => 1.500, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Double Chocolate Muffin', 'image' => 'uploads/items/imgg9.jpeg', 'price' => 1.600, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Banana Walnut Muffin',    'image' => 'uploads/items/img6.jpeg',  'price' => 1.700, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
                'Cakes' => [
                    ['name' => 'Tiramisu',         'image' => 'uploads/items/imgg10.jpeg', 'price' => 2.500, 'is_popular' => true,  'is_weekly_favorite' => true],  // ★
                    ['name' => 'Saffron Cake',     'image' => 'uploads/items/imgg11.jpeg', 'price' => 2.800, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'New York Cheesecake','image' => 'uploads/items/img1.jpeg', 'price' => 3.000, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Chocolate Lava Cake','image' => 'uploads/items/img2.jpeg', 'price' => 3.200, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
            ],

            // ─── Category 3: Tea & Infusions ─────────────────────────────────
            'Tea & Infusions' => [
                'Classic Tea' => [
                    ['name' => 'English Breakfast Tea', 'image' => 'uploads/items/imgg.jpeg',  'price' => 1.500, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Earl Grey',              'image' => 'uploads/items/imgg1.jpeg', 'price' => 1.500, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Chamomile',              'image' => 'uploads/items/imgg2.jpeg', 'price' => 1.800, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Peppermint Infusion',   'image' => 'uploads/items/imgg3.jpeg', 'price' => 1.800, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
                'Specialty Tea' => [
                    ['name' => 'Matcha Latte',          'image' => 'uploads/items/img3.jpeg', 'price' => 2.800, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Saffron Milk Tea',      'image' => 'uploads/items/img4.jpeg', 'price' => 3.200, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Iced Matcha Latte',     'image' => 'uploads/items/img5.jpeg', 'price' => 3.000, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Masala Chai',           'image' => 'uploads/items/imgg5.jpeg','price' => 2.200, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Butterfly Pea Latte',   'image' => 'uploads/items/imgg6.jpeg','price' => 3.500, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
            ],

            // ─── Category 4: Smoothies & Juices ──────────────────────────────
            'Smoothies & Juices' => [
                'Smoothies' => [
                    ['name' => 'Mango Tango Smoothie',      'image' => 'uploads/items/imgg7.jpeg',  'price' => 2.900, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Strawberry Banana Smoothie','image' => 'uploads/items/imgg8.jpeg',  'price' => 2.700, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Green Detox Smoothie',      'image' => 'uploads/items/img.jpeg',    'price' => 3.100, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Mixed Berry Blast',         'image' => 'uploads/items/imgg13.jpeg', 'price' => 3.000, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Avocado Dream',             'image' => 'uploads/items/img1.jpeg',   'price' => 3.500, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
                'Fresh Juices' => [
                    ['name' => 'Fresh Orange Juice',   'image' => 'uploads/items/img2.jpeg', 'price' => 2.200, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Watermelon Mint Juice','image' => 'uploads/items/img3.jpeg', 'price' => 2.500, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Lemonade',             'image' => 'uploads/items/img4.jpeg', 'price' => 1.900, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Pomegranate Juice',    'image' => 'uploads/items/img5.jpeg', 'price' => 2.800, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
            ],

            // ─── Category 5: Snacks ───────────────────────────────────────────
            'Snacks' => [
                'Sandwiches' => [
                    ['name' => 'Grilled Chicken Sandwich','image' => 'uploads/items/img6.jpeg',  'price' => 2.800, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Tuna Melt Sandwich',      'image' => 'uploads/items/img7.jpeg',  'price' => 2.500, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Veggie Wrap',             'image' => 'uploads/items/img8.jpeg',  'price' => 2.200, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'BLT Club',                'image' => 'uploads/items/img9.jpeg',  'price' => 3.000, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
                'Light Bites' => [
                    ['name' => 'Cheese Platter',          'image' => 'uploads/items/imgg10.jpeg', 'price' => 3.500, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Hummus & Pita',           'image' => 'uploads/items/imgg11.jpeg', 'price' => 2.800, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Granola Bar',             'image' => 'uploads/items/imgg12.jpeg', 'price' => 1.200, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Mixed Nuts',              'image' => 'uploads/items/imgg.jpeg',   'price' => 1.500, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Avocado Toast',           'image' => 'uploads/items/imgg1.jpeg',  'price' => 3.200, 'is_popular' => true,  'is_weekly_favorite' => false],
                ],
            ],

            // ─── Category 6: Seasonal Specials ───────────────────────────────
            'Seasonal Specials' => [
                'Summer' => [
                    ['name' => 'Mango Cold Brew',         'image' => 'uploads/items/imgg2.jpeg', 'price' => 3.500, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Coconut Iced Latte',      'image' => 'uploads/items/imgg3.jpeg', 'price' => 3.200, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Passion Fruit Cooler',    'image' => 'uploads/items/imgg4.jpeg', 'price' => 3.000, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Strawberry Cold Foam',    'image' => 'uploads/items/imgg5.jpeg', 'price' => 3.800, 'is_popular' => false, 'is_weekly_favorite' => false],
                ],
                'Winter' => [
                    ['name' => 'Spiced Apple Cider',      'image' => 'uploads/items/imgg6.jpeg',  'price' => 2.800, 'is_popular' => true,  'is_weekly_favorite' => false],
                    ['name' => 'Hot Chocolate Deluxe',    'image' => 'uploads/items/imgg7.jpeg',  'price' => 3.000, 'is_popular' => true,  'is_weekly_favorite' => true],  // ★
                    ['name' => 'Cinnamon Cardamom Latte', 'image' => 'uploads/items/imgg8.jpeg',  'price' => 3.300, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Gingerbread Latte',       'image' => 'uploads/items/imgg9.jpeg',  'price' => 3.500, 'is_popular' => false, 'is_weekly_favorite' => false],
                    ['name' => 'Pumpkin Spice Latte',     'image' => 'uploads/items/imgg10.jpeg', 'price' => 3.700, 'is_popular' => true,  'is_weekly_favorite' => false],
                ],
            ],
        ];

        // Categories that get Size + Sugar Level attributes
        $beverageCategories = ['Beverages', 'Tea & Infusions', 'Smoothies & Juices', 'Seasonal Specials'];

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
                    // Create item for Bahrain Store
                    $itemB = Item::create([
                        'store_id'         => $bahrainStore->id,
                        'sub_category_id'  => $subCategory->id,
                        'name'             => $itemData['name'],
                        'description'      => "Freshly prepared {$itemData['name']} from Espresso Bahrain.",
                        'base_price'       => $itemData['price'],
                        'image_path'       => $itemData['image'],
                        'is_weekly_favorite' => $itemData['is_weekly_favorite'] ?? false,
                        'is_popular'       => $itemData['is_popular'] ?? false,
                        'is_available'     => true,
                    ]);

                    // Create item for Chennai Store
                    $itemC = Item::create([
                        'store_id'         => $chennaiStore->id,
                        'sub_category_id'  => $subCategory->id,
                        'name'             => $itemData['name'],
                        'description'      => "Freshly prepared {$itemData['name']} from Espresso Chennai.",
                        'base_price'       => $itemData['price'],
                        'image_path'       => $itemData['image'],
                        'is_weekly_favorite' => $itemData['is_weekly_favorite'] ?? false,
                        'is_popular'       => $itemData['is_popular'] ?? false,
                        'is_available'     => true,
                    ]);

                    // Add Size + Sugar Level attributes for drink categories
                    if (in_array($catName, $beverageCategories)) {
                        foreach ([$itemB, $itemC] as $item) {
                            $sizeAttr = $item->attributes()->create(['name' => 'Size']);
                            $sizeAttr->options()->create(['value' => 'Small',  'price_adjustment' => 0]);
                            $sizeAttr->options()->create(['value' => 'Medium', 'price_adjustment' => 0.400]);
                            $sizeAttr->options()->create(['value' => 'Large',  'price_adjustment' => 0.700]);

                            $sugarAttr = $item->attributes()->create(['name' => 'Sugar Level']);
                            $sugarAttr->options()->create(['value' => 'No Sugar',     'price_adjustment' => 0]);
                            $sugarAttr->options()->create(['value' => 'Half Sugar',   'price_adjustment' => 0]);
                            $sugarAttr->options()->create(['value' => 'Normal Sugar', 'price_adjustment' => 0]);
                        }
                    }
                }
            }
        }
    }
}
