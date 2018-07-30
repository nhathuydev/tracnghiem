<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCollectionAndAnswersheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->unsignedInteger('random_question_count')->default(0);
            $table->unsignedInteger('point_ladder')->default(10);
        });

        Schema::table('answer_sheet', function (Blueprint $table) {
            $table->unsignedInteger('point_ladder')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
