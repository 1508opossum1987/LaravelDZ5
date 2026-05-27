<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (!$this->app->runningInConsole() && Schema::hasTable('categories')) {
                try {
                    $navigationCategories = Category::query()
                        ->with(['children' => function ($query) {
                            $query->where('active', true)->orderBy('name');
                        }])
                        ->whereNull('parent_id')
                        ->where('active', true)
                        ->orderBy('name')
                        ->get();

                    $view->with('navigationCategories', $navigationCategories);
                } catch (\Exception $e) {
                    $view->with('navigationCategories', collect());
                }
            }
        });
    }
}
