<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private const REQUEST_CACHE_KEY = '19house.settings.values';

    protected static function booted(): void
    {
        static::saved(fn () => request()->attributes->remove(self::REQUEST_CACHE_KEY));
        static::deleted(fn () => request()->attributes->remove(self::REQUEST_CACHE_KEY));
    }

    public static function valueFor(string $key, string|int|null $default = null): string|int|null
    {
        $settings = request()->attributes->get(self::REQUEST_CACHE_KEY);

        if ($settings === null) {
            $settings = static::query()->pluck('value', 'key')->all();
            request()->attributes->set(self::REQUEST_CACHE_KEY, $settings);
        }

        return $settings[$key] ?? $default;
    }
}
