<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Transaction extends Model
{
    use HasFactory;

    const WAGE = 5000;

	protected $fillable = [
		'card_id_source',
		'card_id_destination',
		'amount',
	];

	public function source()
	{
		return $this->belongsTo(Card::class, 'id', 'card_id_source');
	}

	public function destination()
	{
		return $this->belongsTo(Card::class, 'id', 'card_id_destination');
	}

	public function wage()
	{
		return $this->hasOne(Wage::class);
	}
}
