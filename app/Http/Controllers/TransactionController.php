<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\Kavenegar\Client;
use Illuminate\Http\Request;

class TransactionController extends Controller
{


	public function getMostTransactions(Client $service)
	{
		return $service->send();
		return Transaction::all();
	}


	public function store(Request $request)
	{
		$article = Transaction::create($request->all());

		return response()->json($article, 201);
	}
}
