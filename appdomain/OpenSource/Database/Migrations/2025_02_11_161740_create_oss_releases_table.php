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
        Schema::create('oss_releases', static function (Blueprint $table): void {
            // `{repository_id}/{major_version_and_more}` -> `my_repo/2.x`
            $table->string('id')/* Does not work with SQLite `->collation('utf8_bin')` */->primary();

            $table->string('tag_name');
            $table->string('name');
            $table->string('major_version_and_more');
            $table->text('body')->nullable();
            $table->boolean('draft');
            $table->boolean('prerelease');

            $table->string('oss_repository_id');

            $table->bigInteger('github_id')->unsigned();
            $table->string('github_html_url');

            $table->timestamp('created_at');
            $table->timestamp('published_at');

            $table->foreign('oss_repository_id')->references('id')->on('oss_repositories');

            $table->unique(['tag_name', 'oss_repository_id']);
        });
    }
};
