<?php

namespace App\Services;

use App\Models\Recipe;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RecipeService
{
    public function createRecipe(array $data)
    {
        return DB::transaction(function () use ($data) {
            $recipe = Recipe::create([
                'user_id' => auth()->id(),
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'cooking_time' => $data['cooking_time'],
                'servings' => $data['servings'],
                'difficulty' => $data['difficulty'],
                'main_image' => $this->saveImage($data['main_image'], 'recipes'),
                'is_public' => $data['is_public'] ?? true,
            ]);

            if (isset($data['tags'])) {
                $recipe->tags()->attach($data['tags']);
            }

            if (isset($data['sub_images'])) {
                foreach ($data['sub_images'] as $index => $image) {
                    if ($image) {
                        $recipe->images()->create([
                            'image_path' => $this->saveImage($image, 'recipes'),
                            'display_order' => $index + 1,
                        ]);
                    }
                }
            }

            foreach ($data['ingredients'] as $index => $ingredient) {
                if (!empty($ingredient['name'])) {
                    $recipe->ingredients()->create([
                        'name' => $ingredient['name'],
                        'quantity' => $ingredient['quantity'],
                        'display_order' => $index + 1,
                    ]);
                }
            }

            foreach ($data['steps'] as $index => $step) {
                if (!empty($step['description'])) {
                    $recipe->steps()->create([
                        'step_number' => $index + 1,
                        'description' => $step['description'],
                    ]);
                }
            }

            return $recipe;
        });
    }

    public function updateRecipe(Recipe $recipe, array $data)
    {
        return DB::transaction(function () use ($recipe, $data) {
            $updateData = [
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'cooking_time' => $data['cooking_time'],
                'servings' => $data['servings'],
                'difficulty' => $data['difficulty'],
                'is_public' => $data['is_public'] ?? true,
            ];

            if (isset($data['main_image'])) {
                Storage::disk('public')->delete($recipe->main_image);
                $updateData['main_image'] = $this->saveImage($data['main_image'], 'recipes');
            }

            $recipe->update($updateData);

            $recipe->tags()->sync($data['tags'] ?? []);

            if (isset($data['sub_images'])) {
                foreach ($recipe->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $recipe->images()->delete();

                foreach ($data['sub_images'] as $index => $image) {
                    if ($image) {
                        $recipe->images()->create([
                            'image_path' => $this->saveImage($image, 'recipes'),
                            'display_order' => $index + 1,
                        ]);
                    }
                }
            }

            $recipe->ingredients()->delete();
            foreach ($data['ingredients'] as $index => $ingredient) {
                if (!empty($ingredient['name'])) {
                    $recipe->ingredients()->create([
                        'name' => $ingredient['name'],
                        'quantity' => $ingredient['quantity'],
                        'display_order' => $index + 1,
                    ]);
                }
            }

            $recipe->steps()->delete();
            foreach ($data['steps'] as $index => $step) {
                if (!empty($step['description'])) {
                    $recipe->steps()->create([
                        'step_number' => $index + 1,
                        'description' => $step['description'],
                    ]);
                }
            }

            return $recipe;
        });
    }

    public function deleteRecipe(Recipe $recipe)
    {
        return $recipe->delete();
    }

    private function saveImage($image, $directory)
    {
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $path = $image->storeAs($directory, $filename, 'public');
        return $path;
    }
}
