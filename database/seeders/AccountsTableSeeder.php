<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountsTableSeeder extends Seeder
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
	    DB::table('accounts')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all users for user_id relation
	    $users = User::all();

	    // Creating Multiple accounts for each user
	    foreach( $users as $user)
	    {

		    for ($i = 0; $i < 2; $i++) {

			    DB::table('accounts')->insert([
				    'user_id' => $user->id,
				    'balance' => mt_rand(100, 99999999),
			    ]);
		    }

	    }

    }
}
