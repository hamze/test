<?php
namespace App\Services;




use App\Models\Transaction;

class KavenegarService implements SmsServiceInterface
{

	protected $uri;
	protected $token;
	protected $message_source;
	protected $message_destination;

	public function __construct( $uri, $token, $source, $destination) {
		$this->uri = $uri;
		$this->token = $token;
		$this->message_source = $source;
		$this->message_destination = $destination;
	}

	public function send($mob_source, $mob_destination, $acc_source, $acc_destination, $amount) {
		try {
			$url = "https://api.kavenegar.com/v1/" . $this->token . "/sms/send.json?receptor=". $mob_source ."&message=" . urlencode( sprintf($this->message_source, $acc_source, ($amount + Transaction::WAGE)) );
			$res = json_decode(file_get_contents($url));

			$url = "https://api.kavenegar.com/v1/" . $this->token . "/sms/send.json?receptor=". $mob_destination ."&message=" . urlencode( sprintf($this->message_destination, $acc_destination, $amount) );
			$res = json_decode(file_get_contents($url));
		}
		catch (\Exception $e) {
			return false;
		}
	}

}