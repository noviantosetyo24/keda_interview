<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recipient_id')->nullable()->index('users_id_foreign_recipient');
            $table->enum('message_type', ['chat', 'feedback']);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('status');
            $table->unsignedBigInteger('created_by')->index('users_id_foreign_sender');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('recipient_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
