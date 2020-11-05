<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Mshm\Course\Models\Season;

class CreateSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('course_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('title');
            $table->tinyInteger('number')->unsigned();
            $table->enum('status', Season::$statuses)
                ->default(Season::STATUS_OPENED);
            $table->enum('confirmation_status', Season::$confirmationStatuses)
                ->default(Season::CONFIRMATION_STATUS_PENDING);
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')
                ->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
}
