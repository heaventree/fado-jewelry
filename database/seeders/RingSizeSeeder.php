<?php

namespace Database\Seeders;

use App\Models\ProductSize;
use Illuminate\Database\Seeder;

class RingSizeSeeder extends Seeder
{
    /**
     * Seeds the reference ring sizes without tying them to a product.
     * These are US sizes — each product will copy from this list or
     * create its own ProductSize rows when stock is assigned.
     *
     * Because product_sizes requires a product_id FK, this seeder
     * instead writes to a standalone ring_sizes lookup table if one
     * exists, or skips if the schema keeps sizes per-product only.
     *
     * For now we document the canonical size list here so the import
     * command and admin UI can reference it directly from this class.
     */
    public const SIZES = [
        4.0, 4.5, 5.0, 5.5, 6.0, 6.5, 7.0, 7.5,
        8.0, 8.5, 9.0, 9.5, 10.0, 10.5, 11.0, 12.0, 13.0,
    ];

    public function run(): void
    {
        // product_sizes is per-product — no standalone seed needed.
        // The SIZES constant above is consumed by the OpenCart importer
        // and admin product forms.
        $this->command->info('Ring sizes are per-product — no standalone rows to seed. Use RingSizeSeeder::SIZES for reference.');
    }
}
