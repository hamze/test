<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Card;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wage;
use App\Rules\CardNumber;
use App\Rules\TransactionRange;
use App\Services\SmsServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{


	public function getMostTransactions(SmsServiceInterface $tese)
	{
//		$transactions = Transaction::with(['source.account.user'])->where('created_at', '>', Carbon::now()->subMinute(600)->toDateTimeString() )->get()->toArray();

//		$users = User::has(['accounts' => function ($query){
//			$query->has(['cards' => function ($query) {
//				$query->has(['transactions' => function ($query) {
//					$query->max('id');
//				}]);
//			}]);
//		}])->limit(3)->get();

//		$users = User::whereHas('accounts.cards.transactions', function ($query) {
//			$query->has('transactions')->withCount('transactions')->orderBy('transactions_count', 'DESC');
//		})->limit(3)->get();

		$users = User::with(['accounts.cards' => function($q) {
			$q->withCount('transactions')->orderBy('transactions_count', 'DESC');
		}])->get();

		foreach ($users as $user)
			var_dump( $user->id);die;

//		Transaction::where(['created_at', '>', Carbon::now()->subMinute(10)->toDateTimeString() ])->get()->groupBy('');
		var_dump( $users ); die;
		return Transaction::all();
	}


	public function store(TransactionRequest $request)
	{
		$card_source = Card::where( 'number', $request->post('card_source') )->first();
		$card_destination = Card::where( 'number', $request->post('card_destination') )->first();
		$amount = $request->post('amount');

		if( !$card_source || !$card_destination ) { // If card numbers is NOT Available in DB
			$response = array('response' => 'Card Number not available.', 'success' => false);
		}
		elseif( $card_source->account->balance < $amount + Transaction::WAGE  ){ // If source account has NOT enough balance
			$response = array('response' => 'Source account hasn`t enough balance.', 'success' => false);
		}
		else {

			$transaction = new Transaction(); // Save Transaction
			$transaction->card_id_source = $card_source->id;
			$transaction->card_id_destination = $card_destination->id;
			$transaction->amount = $amount;
			$transaction->save();

			$wage = new Wage(); // Save wage
			$wage->transaction_id = $transaction->id;
			$wage->cost = Transaction::WAGE;
			$wage->save();

			$card_source->account()->update(['balance' => ($card_source->account->balance - $amount - Transaction::WAGE) ]); // update source account balance
			$card_destination->account()->update(['balance' => ($card_destination->account->balance + $amount) ]);  // update destination account balance

			$response = array('response' => 'Transaction Done.', 'success'=>true);
		}

		return response()->json($response, 201);
	}

}
