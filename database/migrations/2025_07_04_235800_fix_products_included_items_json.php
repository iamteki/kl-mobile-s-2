<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Fix double-encoded JSON in included_items
        $products = DB::table('products')->whereNotNull('included_items')->get();
        
        foreach ($products as $product) {
            $value = $product->included_items;
            
            // Try to decode
            $decoded = json_decode($value, true);
            
            // If it's a string, it's double-encoded
            if (is_string($decoded)) {
                $finalValue = json_decode($decoded, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['included_items' => json_encode($finalValue)]);
                }
            }
        }
        
        // Do the same for addons if needed
        $products = DB::table('products')->whereNotNull('addons')->get();
        
        foreach ($products as $product) {
            $value = $product->addons;
            
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                $finalValue = json_decode($decoded, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    DB::table('products')
                        ->where('id', $product->id)
                        ->update(['addons' => json_encode($finalValue)]);
                }
            }
        }
    }

    public function down()
    {
        // This migration is not reversible
    }
};