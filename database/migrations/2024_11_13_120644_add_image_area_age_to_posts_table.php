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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('image')->nullable(); // 画像用のカラムを追加
            $table->string('area')->nullable();  // 地域を表すカラムを追加
            $table->integer('age')->nullable();  // 年齢を表すカラムを追加
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('image');
            $table->dropColumn('area');
            $table->dropColumn('age');
        });
    }
};
