<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScTeacher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if (!Schema::hasTable('sc_teachers')) {
            Schema::create('sc_teachers', function (Blueprint $table) {
                $table->bigIncrements('id')->unsigned();
                $table->uuid('uuid')->nullable()->index();

                $table->string('name')->nullable()->index();
                $table->unsignedBigInteger('vh_taxonomy_subject_id')->nullable();
                $table->string('email')->nullable();
                $table->string('contact')->nullable();
                $table->unsignedBigInteger('vh_taxonomy_gender_id')->nullable();
                $table->string('slug')->nullable()->index();
                $table->boolean('is_active')->nullable()->index();

                // $table->foreign('vh_taxonomy_subject_id')->references('id')->on('vh_taxonomies');
                // $table->foreign('vh_taxonomy_gender_id')->references('id')->on('vh_taxonomies');

                //----common fields
                $table->text('meta')->nullable();
                $table->bigInteger('created_by')->nullable()->index();
                $table->bigInteger('updated_by')->nullable()->index();
                $table->bigInteger('deleted_by')->nullable()->index();
                $table->timestamps();
                $table->softDeletes();
                $table->index(['created_at', 'updated_at', 'deleted_at']);
                //----/common fields

            });
        }

    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('sc_teachers');
    }
}
