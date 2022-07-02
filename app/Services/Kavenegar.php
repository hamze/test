<?php
namespace App\Services\Kavenegar;


class Client
{

	protected $uri;
	protected $token;

	public function __construct( $uri, $token) {
		$this->uri = $uri;
		$this->token = $token;
	}

	public function send() {
		return $this->uri;
	}
}