<?php

namespace Database\Seeders;

use App\Models\{Catagory, Color, Company, Offer, Product, Tag};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreCatalogSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->call(ColorSeeder::class);
            $categories = collect([
                'Outerwear' => 'Jackets and coats for cool mornings, outdoor days, and changing seasons.',
                'Knitwear' => 'Comfortable sweaters and cardigans for everyday layering.',
                'Hoodies' => 'Pullovers and zip-up hoodies for relaxed everyday outfits.',
                'Tops' => 'T-shirts, polos, and blouses for casual and smart everyday wear.',
                'Dresses' => 'Versatile dresses for evenings, occasions, and everyday styling.',
                'Skirts' => 'Easy-to-style skirts for comfortable everyday outfits.',
            ])->mapWithKeys(fn ($desc, $name) => [$name => Catagory::firstOrCreate(['name' => $name], ['desc' => $desc])]);

            $companies = collect([
                'Northline Studio' => ['Modern layers and simple wardrobe staples.', 'northline'],
                'Harbor Basics' => ['Comfortable essentials for everyday dressing.', 'harbor'],
                'Willow Atelier' => ['Versatile womenswear with thoughtful silhouettes.', 'willow'],
                'Ridge Outdoor' => ['Practical outerwear for changing seasons.', 'ridge'],
            ])->mapWithKeys(fn ($data, $name) => [$name => Company::firstOrCreate(['name' => $name], [
                'desc' => $data[0], 'image' => 'assets/images/company-' . $data[1] . '.svg',
            ])]);

            $tags = collect(['Everyday', 'Winter', 'Layering', 'Lightweight', 'Occasion', 'New Arrival', 'Best Seller', 'Limited Stock'])
                ->mapWithKeys(fn ($name) => [$name => Tag::firstOrCreate(['name' => $name])]);

            // Curated sample merchandise, prices, and fictional suppliers for a portfolio shop.
            // Paths refer to bundled photos and remain portable between localhost and the server.
            $rows = [
                ['Red Quilted Puffer Jacket', 'Outerwear', 'Ridge Outdoor', '79.90', '44.00', 26, true, 'products/product_0.jpg', ['Winter', 'Best Seller'], 'A red quilted jacket with a zip front and standing collar for everyday cold-weather layering.', 'Red'],
                ['Grey Open-Front Cardigan', 'Knitwear', 'Willow Atelier', '49.90', '25.00', 34, true, 'products/product_1.jpg', ['Layering', 'Everyday'], 'A long grey cardigan with an open front and relaxed fit, easy to pair with tees and trousers.', 'Grey'],
                ['Colour-Block Crew-Neck T-Shirt', 'Tops', 'Harbor Basics', '24.90', '11.00', 58, true, 'products/product_2.jpg', ['Everyday', 'Lightweight'], 'A short-sleeve crew-neck tee with navy, white, and rose colour-block panels.', 'Multicolor'],
                ['Black Cut-Out Midi Dress', 'Dresses', 'Willow Atelier', '64.90', '32.00', 18, true, 'products/product_3.jpg', ['Occasion', 'New Arrival'], 'A fitted black dress with structured shoulders and a waist cut-out for evening styling.', 'Black'],
                ['Sage Cable-Knit Cardigan', 'Knitwear', 'Northline Studio', '59.90', '31.00', 22, true, 'products/product_4.jpg', ['Winter', 'Layering'], 'A textured sage cardigan with a shawl collar and button fastening.', 'Sage'],
                ['Yellow Hooded Anorak', 'Outerwear', 'Ridge Outdoor', '69.90', '36.00', 30, true, 'products/product_5.jpg', ['Lightweight', 'New Arrival'], 'A bright yellow hooded anorak with a half zip and a relaxed silhouette.', 'Yellow'],
                ['Grey Pleated Everyday Skirt', 'Skirts', 'Willow Atelier', '39.90', '19.00', 25, true, 'products/product_6.jpg', ['Everyday', 'New Arrival'], 'A grey pleated skirt with a clean waistband for pairing with simple tops and knitwear.', 'Grey'],
                ['Grey Zip-Up Hoodie', 'Hoodies', 'Harbor Basics', '44.90', '22.00', 42, true, 'products/product_7.jpg', ['Everyday', 'Layering'], 'A grey zip-up hoodie with drawstrings and front pockets for easy layering.', 'Grey'],
                ['Black Dotted Chiffon Dress', 'Dresses', 'Willow Atelier', '74.90', '38.00', 12, true, 'products/product_8.jpg', ['Occasion', 'Best Seller'], 'A flowing black dress with a dotted sheer overlay and long sleeves.', 'Black'],
                ['Black Long-Sleeve Essential Tee', 'Tops', 'Harbor Basics', '27.90', '12.00', 64, true, 'products/product_9.jpg', ['Everyday', 'Best Seller'], 'A simple black long-sleeve crew-neck top for casual everyday outfits.', 'Black'],
                ['Blue Pique Polo Shirt', 'Tops', 'Northline Studio', '34.90', '16.00', 38, true, 'products/product_10.jpg', ['Everyday', 'Lightweight'], 'A blue short-sleeve polo shirt with a classic collar and button placket.', 'Blue'],
                ['Navy Chunky-Knit Cardigan', 'Knitwear', 'Willow Atelier', '54.90', '28.00', 20, true, 'home/demo3/product-0-1.jpg', ['Winter', 'Layering'], 'A navy chunky-knit cardigan with an open front for relaxed seasonal layering.', 'Navy'],
                ['Blue Crew-Neck Sweater', 'Knitwear', 'Northline Studio', '42.90', '21.00', 32, false, 'home/demo3/product-1-1.jpg', ['Everyday', 'Winter'], 'A blue crew-neck sweater with ribbed edges and a comfortable regular fit.', 'Blue'],
                ['Black Music Graphic Hoodie', 'Hoodies', 'Harbor Basics', '46.90', '24.00', 24, false, 'home/demo3/product-2-1.jpg', ['Everyday', 'New Arrival'], 'A black pullover hoodie with a white music graphic and a front pouch pocket.', 'Black'],
                ['Mint Puff-Sleeve Blouse', 'Tops', 'Willow Atelier', '36.90', '18.00', 28, false, 'home/demo3/product-3-1.jpg', ['Lightweight', 'New Arrival'], 'A mint blouse with short puff sleeves and a softly textured finish.', 'Mint'],
                ['Black Outdoor Graphic Hoodie', 'Hoodies', 'Northline Studio', '49.90', '25.00', 19, false, 'home/demo3/product-4.jpg', ['Layering', 'Everyday'], 'A black pullover hoodie with an orange outdoor graphic and front pouch pocket.', 'Black'],
                ['Grey Essential Pullover Hoodie', 'Hoodies', 'Harbor Basics', '39.90', '19.00', 48, false, 'home/demo3/product-5.jpg', ['Everyday', 'Best Seller'], 'A plain grey pullover hoodie with a drawstring hood and roomy front pocket.', 'Grey'],
                ['Black Relaxed Long-Sleeve Top', 'Tops', 'Willow Atelier', '29.90', '14.00', 36, false, 'home/demo3/product-6.jpg', ['Everyday', 'Layering'], 'A relaxed black long-sleeve top for styling with skirts, denim, or tailored trousers.', 'Black'],
                ['Pink Check Cropped Jacket', 'Outerwear', 'Willow Atelier', '69.90', '35.00', 8, false, 'home/demo3/product-7.jpg', ['New Arrival', 'Limited Stock'], 'A cropped pink check jacket with a contrast collar and a statement silhouette.', 'Pink'],
                ['Grey Casual Zip Hoodie', 'Hoodies', 'Northline Studio', '45.90', '23.00', 31, false, 'home/demo3/product-8.jpg', ['Everyday', 'Layering'], 'A grey zip hoodie with a small chest detail, drawstring hood, and front pockets.', 'Grey'],
                ['Cream Tie-Neck Ribbed Top', 'Tops', 'Willow Atelier', '32.90', '15.00', 27, false, 'home/demo3/product-9.jpg', ['Lightweight', 'New Arrival'], 'A cream ribbed long-sleeve top with a small tie-neck detail.', 'Cream'],
                ['White Statement-Collar Blouse', 'Tops', 'Willow Atelier', '37.90', '18.00', 21, false, 'home/demo3/product-10.jpg', ['Everyday', 'Occasion'], 'A white short-sleeve blouse with an oversized collar for a polished everyday look.', 'White'],
            ];

            $products = collect($rows)->mapWithKeys(function ($row) use ($categories, $companies, $tags) {
                [$name, $category, $company, $price, $cost, $stock, $featured, $image, $labels, $desc, $colorName] = $row;
                $product = Product::firstOrCreate(['name' => $name], [
                    'catagory_id' => $categories[$category]->id, 'desc' => $desc,
                    'price' => $price, 'original_price' => $price, 'int_price' => $cost,
                    'quantity' => $stock, 'featured' => $featured,
                ]);
                $color = Color::firstOrCreate(['name' => $colorName]);
                $product->images()->firstOrCreate(
                    ['path' => 'assets/images/' . $image],
                    ['color_id' => $color->id, 'product_id' => $product->id],
                );
                $product->companies()->syncWithoutDetaching([$companies[$company]->id]);
                $product->tags()->syncWithoutDetaching(collect($labels)->map(fn ($label) => $tags[$label]->id)->all());

                return [$name => $product];
            });

            foreach ([
                ['Store Welcome Sale', 'global', 'percentage', '0.05', null],
                ['Knitwear Season Special', 'categories', 'percentage', '0.15', null],
                ['Outerwear Spotlight', 'products', 'fixed_amount', '10.00', null],
                ['Welcome Coupon', 'coupon', 'percentage', '0.10', 'WELCOME10'],
            ] as [$name, $type, $discountType, $discount, $code]) {
                $offer = Offer::firstOrCreate(['name' => $name], [
                    'type' => $type, 'discount_type' => $discountType, 'discount_value' => $discount,
                    'code' => $code, 'is_active' => true,
                    'start_date' => now()->subDay()->startOfDay(), 'end_date' => now()->addDays(90)->endOfDay(),
                ]);
                if ($type === 'categories') {
                    $offer->categories()->syncWithoutDetaching([$categories['Knitwear']->id]);
                } elseif ($type === 'products') {
                    $offer->products()->syncWithoutDetaching([
                        $products['Red Quilted Puffer Jacket']->id, $products['Yellow Hooded Anorak']->id,
                    ]);
                }
            }
        });
    }
}
