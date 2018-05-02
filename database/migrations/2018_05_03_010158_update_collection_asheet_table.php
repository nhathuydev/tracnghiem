<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCollectionAsheetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->unsignedInteger('point')->default(0);
        });
        Schema::table('answer_sheet', function (Blueprint $table) {
            $table->unsignedInteger('point')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('point');
        });
        Schema::table('answer_sheet', function (Blueprint $table) {
            $table->dropColumn('point');
        });
    }
}
