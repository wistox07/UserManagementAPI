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
            $table->foreignId("user_system_id")->constrained("user_systems")->onDelete("cascade")->onUpdate("cascade");
            $table->string("ip_adress");
            $table->string("mac_adress");
            $table->string("user_agent");
            $table->string("auth_token");
            $table->boolean("is_deleted");
            $table->integer("expires_at");
            $table->timestamp("login_at");
            $table->timestamp("logout_at");
            $table->timestamp("last_activity");
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
