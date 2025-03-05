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
        Schema::create('oss_repositories', static function (Blueprint $table): void {
            $table->string('id')/* Does not work with SQLite `->collation('utf8_bin')` */->primary(); // UserRepoData->name

            $table->string('full_name');
            $table->string('description')->nullable();
            $table->string('language')->nullable();
            $table->json('languages')->nullable();

            /**
             * The size of the repository, in kilobytes.
             * Size is calculated hourly. When a repository
             * is initially created, the size is 0.
             *
             * Therefore, allows for up to 16.77GB.
             *
             * @see https://docs.github.com/en/rest/repos/repos?apiVersion=2022-11-28#list-repositories-for-the-authenticated-user
             * @see https://dev.mysql.com/doc/refman/8.4/en/integer-types.html
             */
            $table->smallInteger('size')->unsigned();
            $table->json('topics')->nullable();
            $table->boolean('archived');
            $table->json('license')->nullable();

            $table->bigInteger('github_id')->unsigned();
            $table->string('github_html_url');

            $table->timestamp('pushed_at');
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
        });
    }
};
