<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wage extends Model
{
    use HasFactory;

	protected $fillable = [
	];

	public function transaction()
	{
		return $this->hasOne(Transaction::class);
	}
}
