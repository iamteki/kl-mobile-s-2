<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $packages = DB::table('packages')->get();
        
        foreach ($packages as $package) {
            $updates = [];
            
            // Fix features field
            if ($package->features) {
                $value = $package->features;
                
                // Check if it's double-encoded by looking for escaped quotes
                if (strpos($value, '\\\"') !== false || (substr($value, 0, 1) === '"' && substr($value, -1) === '"')) {
                    // First decode
                    $decoded = json_decode($value, true);
                    
                    // If result is a string, decode again
                    if (is_string($decoded)) {
                        $finalValue = json_decode($decoded, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($finalValue)) {
                            $updates['features'] = json_encode($finalValue);
                        }
                    }
                }
            }
            
            // Fix items field if it contains data
            if ($package->items) {
                $value = $package->items;
                
                if (strpos($value, '\\\"') !== false || (substr($value, 0, 1) === '"' && substr($value, -1) === '"')) {
                    $decoded = json_decode($value, true);
                    
                    if (is_string($decoded)) {
                        $finalValue = json_decode($decoded, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($finalValue)) {
                            $updates['items'] = json_encode($finalValue);
                        }
                    }
                }
            }
            
            // Update if there are changes
            if (!empty($updates)) {
                DB::table('packages')
                    ->where('id', $package->id)
                    ->update($updates);
            }
        }
    }

    public function down()
    {
        // This migration is not reversible
    }
};