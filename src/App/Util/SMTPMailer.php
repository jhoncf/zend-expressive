<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 21/09/16
 * Time: 18:01
 */

namespace App\Util;


/**
 * Class SMTPMailer
 * @package App\Util
 */
class SMTPMailer {

	/**
	 * @var
	 */
	private $config;

	/**
	 * @var mixed
	 */
	private $container;

	/**
	 * @var \PHPMailer
	 */
	private $mail;

	/**
	 * SMTPMailer constructor.
	 */
	public function __construct() {
		$this->container = require 'config/container.php';
		$this->config = $this->container->get("config")['SMTPMailConfig'];

		$this->mail = new \PHPMailer();

		$this->mail->Host = $this->config['host'];
		$this->mail->Username = $this->config['username'];
		$this->mail->Password = $this->config['password'];
		$this->mail->SMTPSecure = $this->config['SMTPSecure'];
		$this->mail->Port = $this->config['port'];
		$this->mail->CharSet = 'UTF-8';
	}

	/**
	 * @param $toEmail
	 * @param $toName
	 * @param $subject
	 * @param $body
	 * @return array|bool
	 */
	public function sendMail($toEmail, $toName, $subject, $body, $bcc = false) {
		$this->mail->setFrom($this->config['from']['email'], $this->config['from']['name']);
		$this->mail->addAddress($toEmail, $toName);

		if($bcc){
			$this->mail->addBCC($this->config['from']['email'], $this->config['from']['name']);
		}

		$this->mail->Subject = $subject;
		$this->mail->Body = $body;
		$this->mail->isHTML(true);

		if (!$this->mail->send()) {
			return [
				'error' => true,
				'message' => $this->mail->ErrorInfo
			];
		} else {
			return true;
		}
	}

}