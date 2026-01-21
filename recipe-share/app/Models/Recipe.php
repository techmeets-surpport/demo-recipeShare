<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'cooking_time',
        'servings',
        'difficulty',
        'main_image',
        'is_public',
        'views_count',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'recipe_tags');
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class)->orderBy('display_order');
    }

    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('step_number');
    }

    public function images()
    {
        return $this->hasMany(RecipeImage::class)->orderBy('display_order');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites()->count();
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeWithRelations($query)
    {
        return $query->with(['user', 'category', 'tags', 'ingredients', 'steps', 'images']);
    }

    public function getDifficultyLabelAttribute()
    {
        return match($this->difficulty) {
            'easy' => '簡単',
            'medium' => '普通',
            'hard' => '難しい',
            default => $this->difficulty,
        };
    }
}
