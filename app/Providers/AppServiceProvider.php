<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Models\EvalYear;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Validator::extend('unique_school_year', function ($attribute, $value, $parameters, $validator) {
      $table = $parameters[0];
      $columnStart = $parameters[1];
      $columnEnd = $parameters[2];

      $exceptId = isset($parameters[3]) ? $parameters[3] : null;

      $query = EvalYear::where(function ($query) use ($value, $columnStart, $columnEnd, $exceptId) {
        $query->where($columnStart, '<=', $value)
          ->where($columnEnd, '>=', $value);

        if ($exceptId) {
          $query->where('id', '<>', $exceptId);
        }
      });

      return !$query->exists();
    });
  }
}