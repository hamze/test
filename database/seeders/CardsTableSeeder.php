<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardsTableSeeder extends Seeder
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
	    DB::table('cards')->truncate();
	    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

	    // Get all Accounts for user_id relation
	    $accounts = Account::all();

	    // Creating Multiple accounts for each user
	    foreach( $accounts as $account )
	    {

		    for ($i = 0; $i < 2; $i++) {

			    DB::table('cards')->insert([
				    'account_id' => $account->id,
				    'number' => $this->generateRandomCardNumber(),
			    ]);
		    }

	    }
    }

	function generateRandomCardNumber() {

		$first = [4,5,6];

		$card = $first [ mt_rand(0, 2) ];

		$card = $card . mt_rand(11111111111111, 99999999999999);



		for($i=0; $i<15; $i++)
		{
			$res[$i] = $card[$i];
			if( !($i%2) )
			{
				$res[$i] *= 2;
				if( $res[$i] > 9 )
					$res[$i] -= 9;
			}
		}
		$sum = array_sum($res);

		if( !($sum % 10) )
			return $card . 0;
		return $card . ((ceil($sum/10) * 10) - $sum);
	}

}
