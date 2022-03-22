<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // add admin user
        $user = new \App\Models\User();
        $user->name = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->password = bcrypt('12341234');
        $user->save();
    }
}
