<?php

namespace App\Services;

use Exception;
use App\Exceptions\SignatureException;
use App\Models\Payment;

class IdeapayService
{
	private $verified = true;

	public function __construct($_verified) {
		$this->verified = $_verified;
	}

	public static function create(Payment $payment) {
		require_once(__DIR__ . '/ideapay-sdk/load.php');

		$client_credentials = array(
			'client_id'     =>  config('ideapay.client_id'),
			'client_secret' =>  config('ideapay.client_secret')
		);

		$payment->load(['patient.user']);

		$currency = 'PHP';

		$client = new \Pay($client_credentials);
		$client->set_origin(config('app.url') . '/api/book/appointment');

		if(config('ideapay.live')) {
			$client->live();
		}
		else {
			$client->sandbox();
		}

		$client->set_customer_info(array(
			'first_name'    =>  $payment->patient->user->fname,
			'last_name'     =>  $payment->patient->user->lname,
			'email_address' =>  $payment->patient->user->email,
			// 'phone_number'  =>  $data['phone_number'] ?? '',
			// 'mobile_number' =>  $data['mobile_number'] ?? '',
			// 'date_of_birth' =>  $payment->patient->user->getOriginal('birthdate') ?? ''
		));

		$billing_info = array(
			// 'address_line1' =>  $data['billing']['address_line1'] ?? '',
			// 'address_line2' =>  $data['billing']['address_line2'] ?? '',
			// 'city'          =>  $data['billing']['city'] ?? '',
			// 'state'         =>  $data['billing']['state'] ?? '',
			// 'country'       =>  $data['billing']['country'] ?? '',
			// 'postal_code'   =>  $data['billing']['postal_code'] ?? '',
			'currency'      =>  $currency,
			'amount' => $payment->amount
		);

		$client->set_billing_info($billing_info);

		$url = $client->send();
		$payment_ref = $client->get_payment_id();
		return [
			'url' => $url,
			'payment_ref' => $payment_ref
		];
	}

	public function getStatus($data)
	{
		if($this->verified) {
			self::verifySignature($data);
		}

		$status = null;
		if(array_key_exists('response_code', $data)) {
			$status = $this->getResponseCodeStatus($data['response_code']);
			if($status == 'cancelled') {
				if(!array_key_exists('cancel', $data)) {
					$status = null;
				}
			}
		}
		return $status;
	}

	private function getResponseCodeStatus($code)
	{
		$status = null;
		switch($code) {
			case 'P001':
				$status = 'success';
				break;
			case 'P002':
				$status = 'cancelled';
				break;
			case 'P003':
				$status = 'pending';
		}
		return $status;
	}

	public function isSuccess($data)
	{
		if($this->verified) {
			self::verifySignature($data);
		}
		if(array_key_exists('response_code', $data)) {
			if($data['response_code'] == 'P001') {
				return true;
			}
		}

		return false;
	}

	public function isCancelled($data)
	{
		if(array_key_exists('response_code', $data) && array_key_exists('cancel', $data)) {
			if($data['response_code'] == 'P002') {
				return true;
			}
		}

		return false;
	}

	public static function verifySignature($data)
	{
		$sign_keys = ['response_code', 'response_message', 'payment_id'];
		$data_sign = array_intersect_key($data, array_flip(array_merge($sign_keys, ['signature'])));
		if(sizeof($data_sign) == 4) {
			$concat_sign = '';
			foreach($sign_keys as $value) {
				$concat_sign .= $data_sign[$value];
			}
			$concat_sign .= config('ideapay.client_secret');
			$hash_sign = hash('sha512', $concat_sign);
			if($hash_sign != $data_sign['signature']) {
				throw new SignatureException('Invalid signature');
			}
		}
		else {
			throw new SignatureException('Missing required parameter', 400);
		}
	}
}
