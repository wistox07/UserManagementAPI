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
            $table->text("auth_token");
            $table->boolean("is_active");
            $table->boolean("is_deleted");
            $table->date("expires_at");
            $table->date("authenticated_at");
            $table->date("accessed_at")->nullable();
            $table->date("logout_at")->nullable();
            $table->date("last_activity");
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
