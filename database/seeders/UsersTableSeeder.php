<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    // Let's clear the users table first
	    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
	    DB::table('users')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');


	    // And now let's generate a few dozen users for our app:
	    for ($i = 0; $i < 10; $i++) {

		    DB::table('users')->insert([
			    'name' => Str::random(10),
			    'mobile' => '09'.mt_rand(111111111, 999999999),
			    'email' => Str::random(10).'@gmail.com',
			    'password' => Hash::make('password'),
		    ]);
	    }
    }
}
