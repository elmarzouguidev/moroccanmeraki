<?php

use App\Enums\Tools\CurrencyType;
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
        Schema::create('prices', function (Blueprint $table) {

            $table->id();
            $table->uuid()->nullable()->unique();
            $table->morphs('priceable');

            $table->string('label')->nullable();
            $table->string('slug')->nullable();

            $table->string('currency')->default(CurrencyType::USD->value);
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('discount_price')->default(0);

            $table->json('options')->nullable();

            $table->activeFields();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
