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
        Schema::create('user_systems', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("system_id")->constrained("systems")->onDelete("cascade")->onUpdate("cascade");
            $table->boolean("is_deleted");
            $table->foreignId("created_by")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("updated_by")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
            $table->foreignId("deleted_by")->nullable()->constrained("users")->onDelete("cascade")->onUpdate("cascade");
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
        Schema::dropIfExists('user_systems');
    }
};
