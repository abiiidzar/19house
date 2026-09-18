<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = [
        'slot', 'type', 'eyebrow', 'title', 'subtitle', 'button_label', 'button_path',
        'image_path', 'mobile_image_path', 'text_position', 'text_color', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['sort_order' => 'integer', 'is_active' => 'boolean'];
    }
}
