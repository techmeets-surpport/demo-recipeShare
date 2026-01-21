<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'recipe_id',
        'step_number',
        'description',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
