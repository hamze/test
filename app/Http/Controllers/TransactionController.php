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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{


	public function getMostTransactions()
	{
		$data = [];

		$users = DB::table('users')->leftjoin('accounts', 'accounts.user_id', '=', 'users.id')
			->leftjoin('cards', 'cards.account_id', '=', 'accounts.id')
			->leftjoin('transactions', 'transactions.card_id_source', '=', 'cards.id')
			->selectRaw('users.id, COUNT(*) as trs')
			->where('transactions.created_at', '>', Carbon::now()->subMinute(10)->toDateTimeString() )
			->groupBy('users.id')
			->orderBy('trs', 'DESC')
			->limit(3)
			->get();

		foreach ($users as $user) {

			$transactions = DB::table('transactions')
				->leftjoin('cards', 'cards.id', '=', 'transactions.card_id_source')
				->leftjoin('accounts', 'accounts.id', '=', 'cards.account_id')
				->leftjoin('users', 'users.id', '=', 'accounts.user_id')
				->where('users.id', '=', $user->id)
				->orderBy('transactions.created_at', 'DESC')
				->limit(10)
				->get();

			foreach ($transactions as $transaction)
				$data[ $transaction->mobile ][] = [
					'source' => Card::find( $transaction->card_id_source )->number,
					'destination' => Card::find( $transaction->card_id_destination )->number,
					'amount' => $transaction->amount,
				];
		}

		$response = array('response' => $data, 'success' => true);
		return response()->json($response, 201);
	}


	public function store(TransactionRequest $request, SmsServiceInterface $sms)
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

			$sms->send( $card_source->account->user->mobile, $card_destination->account->user->mobile, $card_source->account->id, $card_destination->account->id, $amount); /// Send Sms to both accounts

			$response = array('response' => 'Transaction Done.', 'success'=>true);
		}

		return response()->json($response, 201);
	}

}
