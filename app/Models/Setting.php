<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
        'description',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match($setting->type) {
            'bool'  => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'int'   => (int) $setting->value,
            'json'  => json_decode($setting->value, true),
            'date'  => $setting->value ? new \Carbon\Carbon($setting->value) : null,
            default => $setting->value,
        };
    }
}
