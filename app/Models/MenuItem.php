<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class MenuItem extends Model
{
    //
    use HasFactory, Notifiable;

    protected $fillable = [
        'category_id',
        'tab_id',
        'name',
        'photo',
        'price',
        'is_available',
        'available_from',
        'available_until'
    ];

    protected $casts = [
        'available_from' => "datetime:H:i",
        'available_until' => 'datetime:H:i',
        'image' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function Tab()
    {
        return $this->belongsTo(Tab::class);
    }
}
