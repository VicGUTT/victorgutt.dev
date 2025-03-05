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
        Schema::create('oss_documentations', static function (Blueprint $table): void {
            // `{release_id}/{path}` -> `my_repo/2.x/some/docs/path`
            $table->string('id')/* Does not work with SQLite `->collation('utf8_bin')` */->primary();

            $table->string('name');
            $table->string('path');
            $table->string('sha', 40)/* Does not work with SQLite `->collation('utf8_bin')` */->unique(); // sha1 hash

            /**
             * Bytes ?
             *
             * If yes, allows for up to 16.77MB.
             *
             * @see https://docs.github.com/en/rest/repos/contents?apiVersion=2022-11-28#get-repository-content
             * @see https://dev.mysql.com/doc/refman/8.4/en/integer-types.html
             */
            $table->smallInteger('size')->unsigned();
            $table->text('content')->nullable();
            $table->text('rendered_content')->nullable();
            $table->text('rendered_table_of_content')->nullable();

            $table->string('oss_release_id');

            $table->string('github_html_url');

            $table->foreign('oss_release_id')->references('id')->on('oss_releases');
        });
    }
};
