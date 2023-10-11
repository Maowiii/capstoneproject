<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('score_weights', function (Blueprint $table) {
      $table->bigIncrements('score_weight_id');
      $table->integer('eval_id');
      $table->integer('self_eval_weight');
      $table->integer('ic1_weight');
      $table->integer('ic2_weight');
      $table->integer('is_weight');
      $table->integer('bh_weight');
      $table->integer('kra_weight');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('integer_in_score_weights', function (Blueprint $table) {
      //
    });
  }
};
