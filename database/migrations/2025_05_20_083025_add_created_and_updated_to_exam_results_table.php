<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedAndUpdatedToExamResultsTable extends Migration
{
    public function up()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dateTime('start_time')->nullable()->after('score');
            $table->dateTime('end_time')->nullable()->after('start_time');
        });
    }

    public function down()
    {
        Schema::table('exam_results', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
}