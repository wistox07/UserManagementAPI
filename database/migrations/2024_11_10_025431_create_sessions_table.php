<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_system_id")->nullable()->constrained("user_systems")->onDelete("cascade")->onUpdate("cascade");
            $table->string("ip_adress");
            $table->string("user_agent")->nullable();
            $table->text("auth_token")->nullable();
            $table->boolean("is_active");
            $table->boolean("is_deleted");
            $table->dateTime("expires_at")->nullable();
            $table->dateTime("authenticated_at");
            $table->dateTime("accessed_at")->nullable();
            $table->dateTime("logout_at")->nullable();
            $table->dateTime("last_activity");
            $table->integer("login_attempts");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sessions');
    }
};
