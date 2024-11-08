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
        Schema::create('status_records', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("description");
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
        Schema::dropIfExists('status_records');
    }
};
