<?php 

namespace App\Actions;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ClearProductListCacheAction
{
    public static function execute()
    {
        $cache_key_prefix = Product::CACHE_LIST_KEY_PREFIX;

        $cacheKeys = Cache::getKeysByPattern("*" . $cache_key_prefix . "*");
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }
}