<?php

namespace Database\Seeders;

use App\Models\StatusRecord;
use App\Models\User;
use App\Models\Profile;
use App\Models\Session;
use App\Models\System;
use App\Models\UserSystem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;



class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        


        // \App\Models\User::factory(10)->create();
        /*$userSystem = User::create([
            "name" => "system",
            "email" => "system@gmail.com",
            "password" => Str::random(10),
            "is_deleted" => false,

        ]);
        */
        $system = System::create([
            "name" => "Sistema de Gestion de Tickets",
            "url" => "test.tickets",
            "is_deleted" => false,
            "description" => "Sistema Tickets"
        ]);

        User::factory(10)->create()->each(function ($user) use ($system) {

            Profile::factory()->create([
                "user_id" => $user->id
            ]);
            $userSystem = UserSystem::factory()->create([
                "user_id" => $user->id,
                "system_id" => $system->id
            ]);
            Session::factory()->create([
                "user_system_id" =>  $userSystem->id
            ]);



        });
    }
}
