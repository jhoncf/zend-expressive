<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 26/09/16
 * Time: 15:00
 */

namespace AppTest\App;

use App\Util\SMTPMailer;
use PHPUnit_Framework_TestCase as TestCase;

class SMTPMailerTest extends TestCase {

	protected function setUp() {
	}

	public function testSendMail() {
		$mail = new SMTPMailer();

		$twig = new \Twig_Environment(new \Twig_Loader_Filesystem('templates/mail'), array(
			'cache' => 'data/cache/compilation_cache'
		));

		$body = $twig->render('new_password.html', array('activationKey' => '12312312313'));
		$result = $mail->sendMail('jhonatas@dcide.com.br', 'Jhonatas', 'Novo usuário - Dcide', $body);

		$this->assertTrue($result);
	}

	public function testSendMailBlocked() {
		$mail = new SMTPMailer();

		$twig = new \Twig_Environment(new \Twig_Loader_Filesystem('templates/mail'), array(
			'cache' => 'data/cache/compilation_cache'
		));

		$body = $twig->render('blocked_user.html', array('toName' => 'Jhonatas'));
		$result = $mail->sendMail('jhonatas@dcide.com.br', 'Jhonatas', 'Bloqueio de usuário - Dcide', $body);

		$this->assertTrue($result);
	}

}