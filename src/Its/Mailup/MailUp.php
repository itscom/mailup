<?php
/**
 * Created by PhpStorm.
 * User: ngi
 * Date: 06/06/2018
 * Time: 10:11
 */
namespace Its\Mailup;

use GuzzleHttp\Client;

class MailUp {

	protected $client;
	protected $parameters = [];
	protected $lists = [];
	protected $groups = [];


	const SUBSCRIBE_RELATIVE_URI = "/frontend/XmlSubscribe.aspx";
	const UNSUBSCRIBE_RELATIVE_URI = "/frontend/XmlUnsubscribe.aspx";


	public function __construct($consoleuri, $guzzleconfig = [])
	{
		$guzzleconfig["base_uri"] = rtrim($consoleuri,'/');;
		$this->client = new Client($guzzleconfig);
	}

	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
	}

	public function addList($list)
	{
		$this->lists[] = $list;
	}
	public function addGroup($group)
	{
		$this->groups[] = $group;
	}

	public function subscribeEmail($email)
	{
		$s = $this->parameters;
		$s["email"] = $email;
		$s["list"] = implode($this->lists, ",");
		if (count($this->groups) > 0)
			$s["group"] = implode($this->groups, ",");

		$response = $this->client->post(self::SUBSCRIBE_RELATIVE_URI, [
			'form_params' => $s
		]);
		$body = $response->getBody();
		$remainingBytes = trim($body->getContents());

		switch ($remainingBytes) {
			case "1":
				throw new MailUpException('Generic error', 1);
				break;
			case "2":
				throw new MailUpException('Invalid email address or mobile number', 2);
				break;
			case "3":
				throw new MailUpException('Recipient already subscribed', 3);
				break;
			case "-1011":
				throw new MailUpException('IP not registered', -1011);
				break;
			default:
				return true;
		}
	}


}
