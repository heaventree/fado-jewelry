<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Collection;
use App\Models\Gemstone;
use App\Models\Metal;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyProductsSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch reference data
        $silver   = Metal::where('slug', 'sterling-silver')->first();
        $gold9y   = Metal::where('slug', '9ct-yellow-gold')->first();
        $gold14y  = Metal::where('slug', '14ct-yellow-gold')->first();
        $gold18y  = Metal::where('slug', '18ct-yellow-gold')->first();
        $white9   = Metal::where('slug', '9ct-white-gold')->first();
        $rose9    = Metal::where('slug', '9ct-rose-gold')->first();
        $twotone  = Metal::where('slug', 'two-tone-yellow-white-gold')->first();

        $diamond  = Gemstone::where('slug', 'diamond')->first();
        $emerald  = Gemstone::where('slug', 'emerald')->first();
        $cz       = Gemstone::where('slug', 'cubic-zirconia')->first();
        $sapphire = Gemstone::where('slug', 'sapphire')->first();
        $amethyst = Gemstone::where('slug', 'amethyst')->first();
        $pearl    = Gemstone::where('slug', 'pearl')->first();

        $catRings     = Category::where('slug', 'rings')->first();
        $catCrosses   = Category::where('slug', 'crosses')->first();
        $catPendants  = Category::where('slug', 'pendants')->first();
        $catEarrings  = Category::where('slug', 'earrings')->first();
        $catBracelets = Category::where('slug', 'bracelets-bangles')->first();
        $catCufflinks = Category::where('slug', 'cufflinks')->first();
        $catBrooches  = Category::where('slug', 'brooches')->first();
        $catTieTacks  = Category::where('slug', 'tie-tacks')->first();

        // Ring sizes to attach (US sizes typically used)
        $ringSizeSlugs = ['5-0', '6-0', '7-0', '8-0', '9-0'];

        // Image paths — one set of files per product, named "{slug}-{n}.jpg",
        // physically copied from the Ochaka "jewelry" demo product photos
        // (public/images/ochaka/products/jewelry/product-*.jpg) into
        // public/storage/products/demo/{slug}-{n}.jpg. They're still generic
        // stock jewellery photography, not real FADO product shots — but the
        // filenames now actually correspond to the product they're attached
        // to, and (critically) the files genuinely exist on disk, fixing the
        // previous round-robin "products/demo/product-N.jpg" pool which
        // didn't match any real file and rendered as broken images site-wide.
        $imagesFor = function (string $slug, int $count): array {
            $picks = [];
            for ($i = 1; $i <= $count; $i++) {
                $picks[] = "products/demo/{$slug}-{$i}.jpg";
            }
            return $picks;
        };

        // NOTE — TEST/DUMMY DATA ONLY: a subset of variants below set 'second_metal'
        // and/or 'colour' (e.g. Claddagh Ring's 14ct gold variant, Trinity Knot Ring's
        // two-tone variant, Corrib Claddagh Ring, Butterfly Earrings, Forget Me Not
        // Bangle, Celtic Harp Brooch). These values are not real FADO catalogue data —
        // they exist purely so the product page's Second Metal/Finish and Colour spec-
        // table rows have at least one real, non-null example to verify against. Every
        // other variant intentionally still has both fields null, since real second-
        // metal/colour combinations don't exist yet for the rest of the catalogue.
        $products = [

            // ── CLADDAGH ────────────────────────────────────────────────────
            [
                'collection' => 'claddagh',
                'category'   => 'rings',
                'name'       => 'Claddagh Ring',
                'desc'       => 'The classic Irish Claddagh ring — two hands holding a crowned heart. A timeless symbol of love, loyalty and friendship, handcrafted in sterling silver.',
                'bestseller' => true,
                'variants'   => [
                    ['metal' => $silver, 'price' => 69.00, 'stock' => 10],
                    ['metal' => $gold9y, 'price' => 149.00, 'sale' => 119.00, 'stock' => 5, 'gem' => $cz],
                    ['metal' => $gold14y, 'price' => 249.00, 'stock' => 3, 'gem' => $diamond, 'second_metal' => $white9],
                ],
                'has_sizes' => true,
                'images'    => 3,
            ],
            [
                'collection' => 'claddagh',
                'category'   => 'pendants',
                'name'       => 'Claddagh Pendant',
                'desc'       => 'A beautifully detailed Claddagh pendant, perfect as a gift or keepsake. Available in sterling silver and gold.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 55.00, 'stock' => 12],
                    ['metal' => $gold9y, 'price' => 135.00, 'stock' => 6],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'claddagh',
                'category'   => 'earrings',
                'name'       => 'Claddagh Drop Earrings',
                'desc'       => 'Delicate Claddagh drop earrings in sterling silver, perfect for everyday wear or a special occasion.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 45.00, 'stock' => 15],
                    ['metal' => $gold9y, 'price' => 110.00, 'stock' => 8],
                ],
                'images' => 2,
            ],

            // ── CORRIB CLADDAGH ─────────────────────────────────────────────
            [
                'collection' => 'corrib-claddagh',
                'category'   => 'rings',
                'name'       => 'Corrib Claddagh Ring',
                'desc'       => 'A contemporary reinterpretation of the traditional Claddagh, inspired by the flowing waters of the River Corrib. Modern lines meet ancient symbolism.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 79.00, 'stock' => 8, 'colour' => 'Oxidised Black'],
                    ['metal' => $white9, 'price' => 165.00, 'stock' => 4, 'gem' => $diamond],
                ],
                'has_sizes' => true,
                'images'    => 3,
            ],
            [
                'collection' => 'corrib-claddagh',
                'category'   => 'pendants',
                'name'       => 'Corrib Claddagh Pendant',
                'desc'       => 'The Corrib Claddagh pendant — a modern Irish heirloom with flowing curves inspired by the River Corrib.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 65.00, 'stock' => 10],
                    ['metal' => $gold9y, 'price' => 145.00, 'stock' => 5],
                ],
                'images' => 2,
            ],

            // ── TRINITY ─────────────────────────────────────────────────────
            [
                'collection' => 'trinity',
                'category'   => 'rings',
                'name'       => 'Trinity Knot Ring',
                'desc'       => 'The eternal Celtic trinity knot — no beginning, no end. A symbol of the triple goddess and life\'s unending cycle, rendered in fine silver and gold.',
                'bestseller' => true,
                'variants'   => [
                    ['metal' => $silver, 'price' => 59.00, 'sale' => 45.00, 'stock' => 12],
                    ['metal' => $gold9y, 'price' => 139.00, 'stock' => 6],
                    ['metal' => $twotone, 'price' => 189.00, 'stock' => 3, 'second_metal' => $white9],
                ],
                'has_sizes' => true,
                'images'    => 3,
            ],
            [
                'collection' => 'trinity',
                'category'   => 'earrings',
                'name'       => 'Trinity Knot Stud Earrings',
                'desc'       => 'Petite trinity knot studs — subtle Celtic symbolism for everyday elegance.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 39.00, 'stock' => 20],
                    ['metal' => $gold9y, 'price' => 95.00, 'stock' => 10],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'trinity',
                'category'   => 'pendants',
                'name'       => 'Trinity Knot Pendant',
                'desc'       => 'A polished trinity knot pendant in sterling silver — the perfect Celtic keepsake.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 49.00, 'stock' => 15],
                    ['metal' => $gold14y, 'price' => 199.00, 'stock' => 4, 'gem' => $emerald],
                ],
                'images' => 2,
            ],

            // ── AN RÍ ────────────────────────────────────────────────────────
            [
                'collection' => 'an-ri',
                'category'   => 'rings',
                'name'       => 'An Rí Celtic Ring',
                'desc'       => 'Inspired by the high kings of Ireland, the An Rí ring features bold Celtic knotwork befitting Irish royalty.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 85.00, 'stock' => 8],
                    ['metal' => $gold18y, 'price' => 349.00, 'stock' => 2],
                ],
                'has_sizes' => true,
                'images'    => 2,
            ],
            [
                'collection' => 'an-ri',
                'category'   => 'cufflinks',
                'name'       => 'An Rí Celtic Cufflinks',
                'desc'       => 'Regal Celtic knotwork cufflinks, handcrafted in sterling silver. Ideal for weddings and formal occasions.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 95.00, 'stock' => 10],
                    ['metal' => $gold9y, 'price' => 175.00, 'stock' => 4],
                ],
                'images' => 2,
            ],

            // ── LIVIA ───────────────────────────────────────────────────────
            [
                'collection' => 'livia',
                'category'   => 'rings',
                'name'       => 'Livia Dress Ring',
                'desc'       => 'The Livia ring — graceful, flowing curves for the modern Irish woman. Available in silver, gold and rose gold.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 75.00, 'stock' => 10],
                    ['metal' => $rose9, 'price' => 155.00, 'sale' => 129.00, 'stock' => 6, 'gem' => $amethyst, 'second_metal' => $gold9y],
                    ['metal' => $gold9y, 'price' => 165.00, 'stock' => 4, 'gem' => $sapphire],
                ],
                'has_sizes' => true,
                'images'    => 3,
            ],
            [
                'collection' => 'livia',
                'category'   => 'bracelets-bangles',
                'name'       => 'Livia Bangle',
                'desc'       => 'An elegant bangle from the Livia collection — flowing lines in polished sterling silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 89.00, 'stock' => 8],
                    ['metal' => $gold9y, 'price' => 179.00, 'stock' => 3],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'livia',
                'category'   => 'earrings',
                'name'       => 'Livia Hoop Earrings',
                'desc'       => 'Sculptural hoop earrings from the Livia collection, in polished sterling silver or gold.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 55.00, 'stock' => 12],
                    ['metal' => $rose9, 'price' => 115.00, 'stock' => 6],
                ],
                'images' => 2,
            ],

            // ── SHEELIN ─────────────────────────────────────────────────────
            [
                'collection' => 'sheelin',
                'category'   => 'pendants',
                'name'       => 'Sheelin Lake Pendant',
                'desc'       => 'Still waters and quiet beauty — the Sheelin pendant captures the serene Irish lakeland in flowing silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 65.00, 'stock' => 10],
                    ['metal' => $gold9y, 'price' => 145.00, 'stock' => 5, 'gem' => $pearl],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'sheelin',
                'category'   => 'brooches',
                'name'       => 'Sheelin Brooch',
                'desc'       => 'An elegant brooch inspired by the still waters of Lough Sheelin, hand-finished in sterling silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 79.00, 'stock' => 6],
                    ['metal' => $gold9y, 'price' => 159.00, 'stock' => 3],
                ],
                'images' => 2,
            ],

            // ── HIGH CROSSES ────────────────────────────────────────────────
            [
                'collection' => 'high-crosses',
                'category'   => 'pendants',
                'name'       => 'Celtic High Cross Pendant',
                'desc'       => 'Ireland\'s iconic high cross, rendered in fine sterling silver. Inspired by the great monastic crosses of Muiredach and Clonmacnoise.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 79.00, 'stock' => 12],
                    ['metal' => $gold14y, 'price' => 219.00, 'stock' => 4],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'high-crosses',
                'category'   => 'crosses',
                'name'       => 'Clonmacnoise Cross Pendant',
                'desc'       => 'Faithful to the original 10th-century Clonmacnoise Cross, this pendant captures centuries of Irish craftsmanship.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 89.00, 'stock' => 10],
                    ['metal' => $gold18y, 'price' => 299.00, 'stock' => 2, 'gem' => $diamond],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'high-crosses',
                'category'   => 'crosses',
                'name'       => 'Muiredach Cross Brooch',
                'desc'       => 'Inspired by the intricately carved panels of the Muiredach High Cross at Monasterboice.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 95.00, 'stock' => 8],
                ],
                'images' => 2,
            ],

            // ── NEWGRANGE ───────────────────────────────────────────────────
            [
                'collection' => 'newgrange',
                'category'   => 'pendants',
                'name'       => 'Newgrange Spiral Pendant',
                'desc'       => 'The triple spiral of Newgrange — a 5,000-year-old symbol of rebirth and the eternal cycle of life — in sterling silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 69.00, 'stock' => 14],
                    ['metal' => $gold9y, 'price' => 149.00, 'stock' => 6],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'newgrange',
                'category'   => 'rings',
                'name'       => 'Newgrange Spiral Ring',
                'desc'       => 'The ancient spiral of Newgrange, brought to life as a bold sterling silver ring.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 79.00, 'stock' => 10],
                    ['metal' => $gold9y, 'price' => 159.00, 'stock' => 4],
                ],
                'has_sizes' => true,
                'images'    => 2,
            ],

            // ── IRISH FOLKLORE ──────────────────────────────────────────────
            [
                'collection' => 'irish-folklore',
                'category'   => 'pendants',
                'name'       => 'Celtic Harp Pendant',
                'desc'       => 'Ireland\'s national symbol — the ancient harp — delicately rendered in polished sterling silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 65.00, 'stock' => 10],
                    ['metal' => $gold9y, 'price' => 140.00, 'stock' => 5],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'irish-folklore',
                'category'   => 'brooches',
                'name'       => 'Tara Brooch Replica',
                'desc'       => 'Inspired by the 8th-century Tara Brooch, one of Ireland\'s greatest treasures of Celtic metalwork.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 125.00, 'stock' => 6],
                    ['metal' => $gold14y, 'price' => 295.00, 'stock' => 2, 'gem' => $amethyst],
                ],
                'images' => 3,
            ],

            // ── SHAMROCK ────────────────────────────────────────────────────
            [
                'collection' => 'shamrock',
                'category'   => 'pendants',
                'name'       => 'Shamrock Pendant',
                'desc'       => 'Ireland\'s beloved three-leafed shamrock, handcrafted in sterling silver. A classic Irish gift.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 45.00, 'stock' => 20],
                    ['metal' => $gold9y, 'price' => 110.00, 'stock' => 10],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'shamrock',
                'category'   => 'earrings',
                'name'       => 'Shamrock Stud Earrings',
                'desc'       => 'Petite sterling silver shamrock studs — a touch of Ireland for every day.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 35.00, 'stock' => 25],
                    ['metal' => $gold9y, 'price' => 85.00, 'stock' => 12],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'shamrock',
                'category'   => 'tie-tacks',
                'name'       => 'Shamrock Tie-tack',
                'desc'       => 'A sterling silver shamrock tie-tack — the perfect Irish accessory for weddings and formal occasions.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 29.00, 'stock' => 15],
                ],
                'images' => 2,
            ],

            // ── THE GARDEN COLLECTION ────────────────────────────────────────
            [
                'collection' => 'the-garden-collection',
                'category'   => 'rings',
                'name'       => 'Wild Daisy Engagement Ring',
                'desc'       => 'A delicate wild daisy ring from The Jewellery Garden — a perfect choice for nature-inspired engagements. Each petal hand-set in fine silver.',
                'bestseller' => true,
                'variants'   => [
                    ['metal' => $silver, 'price' => 99.00, 'stock' => 8],
                    ['metal' => $white9, 'price' => 225.00, 'stock' => 4, 'gem' => $diamond],
                    ['metal' => $rose9, 'price' => 215.00, 'sale' => 179.00, 'stock' => 4, 'gem' => $cz, 'second_metal' => $white9],
                ],
                'has_sizes' => true,
                'images'    => 3,
            ],
            [
                'collection' => 'the-garden-collection',
                'category'   => 'pendants',
                'name'       => 'Bluebell Pendant',
                'desc'       => 'A graceful bluebell pendant from The Jewellery Garden collection. Wildflower beauty in fine sterling silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 75.00, 'stock' => 10],
                    ['metal' => $gold9y, 'price' => 155.00, 'stock' => 5, 'gem' => $sapphire],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'the-garden-collection',
                'category'   => 'earrings',
                'name'       => 'Butterfly Earrings',
                'desc'       => 'Delicate butterfly drop earrings from The Jewellery Garden — whimsical, feminine, perfect for weddings.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 65.00, 'stock' => 12, 'gem' => $cz, 'colour' => 'White'],
                    ['metal' => $rose9, 'price' => 135.00, 'stock' => 6, 'second_metal' => $gold9y, 'colour' => 'Rose & Yellow'],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'the-garden-collection',
                'category'   => 'bracelets-bangles',
                'name'       => 'Forget Me Not Bangle',
                'desc'       => 'A delicate forget-me-not bangle from The Jewellery Garden, hand-finished in sterling silver with blue enamel detail.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 89.00, 'stock' => 9, 'colour' => 'Blue Enamel'],
                    ['metal' => $gold9y, 'price' => 179.00, 'stock' => 4],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'the-garden-collection',
                'category'   => 'pendants',
                'name'       => 'Honey Bee Pendant',
                'desc'       => 'A charming honey bee pendant from The Jewellery Garden — nature-inspired and finely detailed in sterling silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 69.00, 'stock' => 11, 'gem' => $cz],
                    ['metal' => $gold9y, 'price' => 149.00, 'sale' => 119.00, 'stock' => 5],
                ],
                'images' => 2,
            ],

            // ── ADDITIONAL CATEGORY COVERAGE ─────────────────────────────────
            [
                'collection' => 'trinity',
                'category'   => 'bracelets-bangles',
                'name'       => 'Trinity Knot Bangle',
                'desc'       => 'A polished trinity knot bangle — the eternal Celtic knot wrapped around the wrist in fine sterling silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 95.00, 'stock' => 7],
                    ['metal' => $gold9y, 'price' => 189.00, 'stock' => 3, 'second_metal' => $white9],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'shamrock',
                'category'   => 'cufflinks',
                'name'       => 'Shamrock Cufflinks',
                'desc'       => 'Classic shamrock cufflinks in sterling silver — a refined Irish touch for formal occasions.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 75.00, 'stock' => 10],
                    ['metal' => $gold9y, 'price' => 155.00, 'stock' => 4],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'irish-folklore',
                'category'   => 'brooches',
                'name'       => 'Celtic Harp Brooch',
                'desc'       => 'Ireland\'s national symbol rendered as an elegant brooch, hand-finished in sterling silver.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 89.00, 'stock' => 6, 'colour' => 'Antique Silver'],
                    ['metal' => $gold14y, 'price' => 259.00, 'stock' => 2, 'gem' => $emerald],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'high-crosses',
                'category'   => 'tie-tacks',
                'name'       => 'Celtic Cross Tie-tack',
                'desc'       => 'A discreet Celtic high cross tie-tack in sterling silver, perfect for weddings and formal wear.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 35.00, 'stock' => 18],
                    ['metal' => $gold9y, 'price' => 79.00, 'stock' => 6],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'high-crosses',
                'category'   => 'crosses',
                'name'       => 'Ardboe Cross Pendant',
                'desc'       => 'Inspired by the Ardboe High Cross in County Tyrone, this pendant honours one of Ireland\'s great early Christian monuments.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 85.00, 'stock' => 9],
                    ['metal' => $gold9y, 'price' => 175.00, 'sale' => 149.00, 'stock' => 4, 'gem' => $sapphire],
                ],
                'images' => 2,
            ],
            [
                'collection' => 'an-ri',
                'category'   => 'brooches',
                'name'       => 'An Rí Celtic Brooch',
                'desc'       => 'Bold Celtic knotwork brooch from the An Rí collection, fit for Irish royalty.',
                'variants'   => [
                    ['metal' => $silver, 'price' => 99.00, 'stock' => 7],
                    ['metal' => $gold18y, 'price' => 389.00, 'stock' => 1, 'gem' => $diamond],
                ],
                'images' => 2,
            ],
        ];

        // Category lookup map
        $categoryMap = [
            'rings'            => $catRings,
            'crosses'          => $catCrosses,
            'pendants'         => $catPendants,
            'earrings'         => $catEarrings,
            'bracelets-bangles'=> $catBracelets,
            'cufflinks'        => $catCufflinks,
            'brooches'         => $catBrooches,
            'tie-tacks'        => $catTieTacks,
        ];

        // US ring sizes to create per ring product
        $usRingSizes = [5.0, 6.0, 7.0, 8.0, 9.0, 10.0];

        foreach ($products as $data) {
            $slug = Str::slug($data['name']);

            $product = Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name'              => $data['name'],
                    'slug'              => $slug,
                    'description'       => $data['desc'],
                    'short_description' => null,
                    'is_active'         => true,
                    'is_bestseller'     => $data['bestseller'] ?? false,
                    'opencart_id'       => null,
                ]
            );

            // Attach category
            if (!empty($data['category']) && isset($categoryMap[$data['category']])) {
                $cat = $categoryMap[$data['category']];
                if ($cat && !$product->categories->contains($cat->id)) {
                    $product->categories()->syncWithoutDetaching([$cat->id]);
                }
            }

            // Attach collection
            if (!empty($data['collection'])) {
                $col = Collection::where('slug', $data['collection'])->first();
                if ($col && !$product->collections->contains($col->id)) {
                    $product->collections()->syncWithoutDetaching([$col->id]);
                }
            }

            // Add variants (skip if already exist)
            if ($product->variants->isEmpty()) {
                foreach ($data['variants'] as $v) {
                    if (!$v['metal']) continue;
                    ProductVariant::create([
                        'product_id'      => $product->id,
                        'metal_id'        => $v['metal']->id,
                        'gemstone_id'     => $v['gem']?->id ?? null,
                        'second_metal_id' => $v['second_metal']?->id ?? null,
                        'sku'             => strtoupper(Str::limit($slug, 10, '')) . '-' . strtoupper(Str::limit($v['metal']->slug, 4, '')),
                        'price_eur'       => $v['price'],
                        'sale_price_eur'  => $v['sale'] ?? null,
                        'stock'           => $v['stock'],
                        'is_active'       => true,
                        'colour'          => $v['colour'] ?? null,
                    ]);
                }
            }

            // Add images (skip if already exist)
            if ($product->images->isEmpty()) {
                $images = $imagesFor($slug, $data['images'] ?? 2);
                foreach ($images as $i => $path) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path'       => $path,
                        'sort_order' => $i,
                        'is_primary' => $i === 0,
                    ]);
                }
            }

            // Add ring sizes (skip if already exist)
            if (!empty($data['has_sizes']) && $product->sizes->isEmpty()) {
                foreach ($usRingSizes as $usSize) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'us_size'    => $usSize,
                        'stock'      => 5,
                    ]);
                }
            }
        }
    }
}
