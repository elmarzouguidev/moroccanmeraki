<?php

use App\Models\Tools\Country;
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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->ulid()->unique()->nullable();

            $table->foreignIdFor(Country::class)->nullable()->index()->constrained();

            $table->string('code', 200)->nullable()->unique();
            $table->string('name', 100)->unique();
            $table->string('slug')
                ->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->activeFields();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
