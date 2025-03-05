<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resume_data', static function (Blueprint $table): void {
            $table->id();

            $table->string('locale', 2)->unique();
            $table->json('me');
            $table->json('experiences');
            $table->json('educations');
            $table->json('languages');
            $table->json('techs');

            $table->timestamps();
        });
    }
};
