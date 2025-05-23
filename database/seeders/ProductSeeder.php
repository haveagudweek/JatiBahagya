<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Lemari',
                'image' => 'images/category-cabinet.png'
            ],
            [
                'name' => 'Kamar Tidur',
                'image' => 'images/category-bedroom.png'
            ],
            [
                'name' => 'Kamar Mandi',
                'image' => 'images/category-bathroom.png'
            ],
            [
                'name' => 'Ruang Tamu',
                'image' => 'images/category-living-room.png'
            ],
            [
                'name' => 'Ruang Dapur',
                'image' => 'images/category-kitchen.png'
            ],
            [
                'name' => 'Ruang Kantor',
                'image' => 'images/category-office.png'
            ]
        ];

        foreach ($categories as $category) {
            $newImageName = strtoupper(Str::random(10)) . '.png';
            $newImagePath = 'categories/' . $newImageName;

            Storage::disk('public')->put($newImagePath, file_get_contents(public_path($category['image'])));

            Category::firstOrCreate([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
            ], [
                'image' => $newImagePath
            ]);
        }

        $brands = [
            'Tanpa Brand',
            'Jati Bahagya'
        ];

        foreach ($brands as $brandName) {
            Brand::firstOrCreate([
                'name' => $brandName,
            ]);
        }

        $productsData = [
            [
                'name' => 'MICKE Meja Kerja Minimalis',
                'description' => 'MICKE adalah meja dengan desain yang sederhana dan tampilan baru, cocok diletakkan di berbagai ruangan. Dengan dimensi fleksibel dan warna modern.',
                'category' => 'ruang-kantor',
                'image' => 'images/micke-black.jpg',
                'discount' => 15, // Diskon 15%
                'brand' => 'Jati Bahagya',
                'attributes' => [
                    'Warna' => ['Putih', 'Hitam'],
                    'Ukuran' => ['105x50 cm', '120x60 cm'],
                ],
                'variants' => [
                    [
                        'Warna' => 'Putih',
                        'Ukuran' => '105x50 cm',
                        'image' => 'images/micke-white.jpg',
                        'price' => 1200000,
                        'stock' => 10,
                        'discount' => 15, // Diskon 15%
                    ],
                    [
                        'Warna' => 'Hitam',
                        'Ukuran' => '105x50 cm',
                        'image' => 'images/micke-black.jpg',
                        'price' => 1150000,
                        'stock' => 5,
                        'discount' => 15, // Diskon 15%
                    ],
                    [
                        'Warna' => 'Putih',
                        'Ukuran' => '120x60 cm',
                        'image' => 'images/micke-white.jpg',
                        'price' => 1300000,
                        'stock' => 7,
                        'discount' => 15, // Diskon 15%
                    ],
                    [
                        'Warna' => 'Hitam',
                        'Ukuran' => '120x60 cm',
                        'image' => 'images/micke-black.jpg',
                        'price' => 1250000,
                        'stock' => 6,
                        'discount' => 15, // Diskon 15%
                    ],
                ],
            ],
            [
                'name' => 'HEMNES Tempat Tidur Kayu Jati',
                'description' => 'Tempat tidur dengan rangka kayu jati solid yang kokoh dan tahan lama. Desain klasik dengan sentuhan modern.',
                'category' => 'kamar-tidur',
                'image' => 'images/hemnes.jpg',
                'discount' => 15, // Diskon 15%
                'brand' => 'Jati Bahagya',
                'attributes' => [
                    'Warna' => ['Natural', 'Dark Brown'],
                    'Ukuran' => ['Single', 'Double', 'King Size'],
                ],
                'variants' => [
                    [
                        'Warna' => 'Natural',
                        'Ukuran' => 'Single',
                        'image' => 'images/hemnes.jpg',
                        'price' => 3500000,
                        'stock' => 8,
                        'discount' => 10, // Diskon 10%
                    ],
                    [
                        'Warna' => 'Natural',
                        'Ukuran' => 'Double',
                        'image' => 'images/hemnes.jpg',
                        'price' => 4500000,
                        'stock' => 5,
                        'discount' => 15, // Diskon 15%
                    ],
                    [
                        'Warna' => 'Dark Brown',
                        'Ukuran' => 'Double',
                        'image' => 'images/hemnes.jpg',
                        'price' => 4700000,
                        'stock' => 6,
                        'discount' => 15, // Diskon 15%
                    ],
                    [
                        'Warna' => 'Dark Brown',
                        'Ukuran' => 'King Size',
                        'image' => 'images/hemnes.jpg',
                        'price' => 5500000,
                        'stock' => 4,
                        'discount' => 15, // Diskon 15%
                    ],
                ],
            ],
            [
                'name' => 'LACK Meja Kopi Minimalis',
                'description' => 'Meja kopi sederhana dengan desain minimalis yang cocok untuk ruang tamu kecil maupun besar. Tersedia dalam berbagai warna.',
                'category' => 'ruang-tamu',
                'image' => 'images/lack-black.jpg',
                'brand' => 'Tanpa Brand',
                'discount' => 15, // Diskon 15%
                'attributes' => [
                    'Warna' => ['Hitam', 'Putih'],
                    'Bahan' => ['Kayu Lapis', 'MDF'],
                ],
                'variants' => [
                    [
                        'Warna' => 'Hitam',
                        'Bahan' => 'Kayu Lapis',
                        'image' => 'images/lack-black.jpg',
                        'price' => 750000,
                        'stock' => 12,
                        'discount' => 15, // Diskon 15%
                    ],
                    [
                        'Warna' => 'Putih',
                        'Bahan' => 'Kayu Lapis',
                        'image' => 'images/lack-white.jpg',
                        'price' => 780000,
                        'stock' => 10,
                        'discount' => 5, // Diskon 5%
                    ],
                    [
                        'Warna' => 'Putih',
                        'Bahan' => 'MDF',
                        'image' => 'images/lack-white.jpg',
                        'price' => 650000,
                        'stock' => 15,
                        'discount' => 15, // Diskon 15%
                    ],
                    [
                        'Warna' => 'Hitam',
                        'Bahan' => 'MDF',
                        'image' => 'images/lack-black.jpg',
                        'price' => 600000,
                        'stock' => 8,
                        'discount' => 10, // Diskon 10%
                    ],
                ],
            ],
            [
                'name' => 'MALM Kabinet Penyimpanan',
                'description' => 'Kabinet penyimpanan dengan laci yang luas dan desain modern. Cocok untuk kamar tidur atau ruang kerja.',
                'category' => 'lemari',
                'image' => 'images/malm-white.jpg',
                'discount' => 15, // Diskon 15%
                'brand' => 'Jati Bahagya',
                'attributes' => [
                    'Warna' => ['Putih', 'Hitam'],
                    'Jumlah Laci' => ['4 Laci'],
                ],
                'variants' => [
                    [
                        'Warna' => 'Putih',
                        'Jumlah Laci' => '4 Laci',
                        'image' => 'images/malm-white.jpg',
                        'price' => 2800000,
                        'stock' => 7,
                        'discount' => 15, // Diskon 15%
                    ],
                    [
                        'Warna' => 'Hitam',
                        'Jumlah Laci' => '4 Laci',
                        'image' => 'images/malm-black.jpg',
                        'price' => 2900000,
                        'stock' => 6,
                        'discount' => 15, // Diskon 15%
                    ],
                ],
            ],
        ];

        $allVariants = [];

        foreach ($productsData as $data) {
            $category = Category::where('slug', $data['category'])->firstOrFail();
            $brand = Brand::where('name', $data['brand'])->firstOrFail();

            $productSlug = Str::slug($data['name']);
            $sourceImagePath = public_path($data['image']);
            $ext = pathinfo($data['image'], PATHINFO_EXTENSION);
            $productImagePath = "products/{$productSlug}.{$ext}";

            if (File::exists($sourceImagePath)) {
                if (!Storage::disk('public')->exists($productImagePath)) {
                    Storage::disk('public')->put($productImagePath, File::get($sourceImagePath));
                }
            } else {
                throw new \Exception("File gambar tidak ditemukan: {$sourceImagePath}");
            }

            $product = Product::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => 0,
                'stock' => 0,
                'discount' => $data['discount'] ?? 0, // Add discount field
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'image' => $productImagePath,
                'is_new_product' => true,
            ]);

            $attributeValueMap = [];

            foreach ($data['attributes'] as $attrName => $values) {
                $attribute = Attribute::firstOrCreate([
                    'name' => $attrName,
                    'product_id' => $product->id,
                ]);

                foreach ($values as $value) {
                    $attrValue = AttributeValue::firstOrCreate([
                        'attribute_id' => $attribute->id,
                        'value' => $value,
                    ]);
                    $attributeValueMap[$attrName][$value] = $attrValue->id;
                }
            }

            $variants = [];

            foreach ($data['variants'] as $variant) {
                $skuParts = [$product->name];
                $imageParts = [];
                $valueIds = [];

                foreach ($variant as $key => $val) {
                    if (in_array($key, ['price', 'stock', 'image', 'discount'])) continue;
                    $skuParts[] = strtoupper(Str::slug($val));
                    $imageParts[] = Str::slug($val);
                    $valueIds[] = $attributeValueMap[$key][$val];
                }

                $sku = implode('-', $skuParts);

                $variantImageExt = pathinfo($variant['image'], PATHINFO_EXTENSION);
                $variantImageName = "{$productSlug}-" . implode('-', $imageParts) . ".{$variantImageExt}";
                $variantImagePath = "products/{$variantImageName}";
                $variantSourcePath = public_path($variant['image']);

                if (File::exists($variantSourcePath)) {
                    if (!Storage::disk('public')->exists($variantImagePath)) {
                        Storage::disk('public')->put($variantImagePath, File::get($variantSourcePath));
                    }
                } else {
                    throw new \Exception("File gambar varian tidak ditemukan: {$variantSourcePath}");
                }

                $createdVariant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => $sku,
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'discount' => $variant['discount'] ?? 0,
                    'image' => $variantImagePath,
                ]);

                $createdVariant->attributeValues()->sync($valueIds);
                $variants[] = $createdVariant;
            }

            $allVariants[$product->id] = $variants;
        }
    }
}
