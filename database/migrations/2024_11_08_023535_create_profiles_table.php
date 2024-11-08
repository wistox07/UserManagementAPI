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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->string("name");
            $table->string("personal_email");
            $table->string("profile_picture");
            $table->date("birth");
            $table->enum('gender', ['m', 'f']); // 'm' para masculino, 'f' para femenino
            $table->foreignId("created_by")->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("updated_by")->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("status_record_id")->constrained("status_records")->onDelete("cascade")->onUpdate("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
};
