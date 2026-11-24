<?php

namespace App\Observers;

use App\Models\SelectedCategory;

class SelectedCategoryObserver
{

    public function created(SelectedCategory $selectedCategory): void
    {
        cache()->forget('selectedCategories');
    }

    public function updated(SelectedCategory $selectedCategory): void
    {
        cache()->forget('selectedCategories');
    }

    public function deleted(SelectedCategory $selectedCategory): void
    {
        cache()->forget('selectedCategories');
    }

    public function restored(SelectedCategory $selectedCategory): void
    {
        cache()->forget('selectedCategories');
    }

    public function forceDeleted(SelectedCategory $selectedCategory): void
    {
        cache()->forget('selectedCategories');
    }
}