<?php

namespace App\Services;

interface SmsServiceInterface
{

	public function send($mob_source, $mob_destination, $acc_source, $acc_destination, $amount);

}