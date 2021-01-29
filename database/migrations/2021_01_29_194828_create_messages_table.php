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
            $table->foreignId("user_id")
                ->constrained("users", "id");
            $table->string("sender_email");
            $table->string("recipient_email");
            $table->string("subject");
            $table->text("body_text")
                ->nullable();
            $table->text("body_html")
                ->nullable();
            $table->enum("status", [ "pending", "sent", "failed"])
                ->default("pending");
            $table->timestamps();
            $table->softDeletes();
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
