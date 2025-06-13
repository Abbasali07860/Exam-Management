<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_result_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->text('answer')->nullable(); // For MCQ (option value) or subjective (text)
            $table->integer('marks_obtained')->nullable(); // Marks awarded for this question
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_answers');
    }
}