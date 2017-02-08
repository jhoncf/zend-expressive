<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 23/09/16
 * Time: 16:00
 */

namespace AppTest\Business;

use App\Util\CustomRequest;
use Business\Auth\Action\Change_passwordAction;
use Business\Auth\Action\LoggedAction;
use Business\Auth\Action\LoginAction;
use Business\Auth\Action\LogoutAction;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use PHPUnit_Framework_TestCase as TestCase;

class AuthTest extends TestCase {

	/** @var RouterInterface */
	protected $router;

	/**
	 * @var
	 */
	protected $container;

	/**
	 * @var
	 */
	private $sessionKey;

	/**
	 *
	 */
	protected function setUp() {
		$this->router = $this->prophesize(ContainerInterface::class);
		$this->container = require 'config/container.php';
	}

	public function testWrongLogin() {
		$action = new LoginAction($this->container, $this->container->get(EntityManager::class));
		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request);

		$postData = [
			'email' => 'ti.register@dcide.com.b',
			'password' => 'Dcide123@',
			'SessionData' => [
				'application' => 'denergia_web'
			]
		];
		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('errors', $responseArray);
		$this->assertArrayHasKey('error', $responseArray);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);

		return $responseArray;
	}

	public function testWrongPasswordLogin() {
		$action = new LoginAction($this->container, $this->container->get(EntityManager::class));
		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request);

		$postData = [
			'email' => 'ti.register@dcide.com.br',
			'password' => 'WrongPassword',
			'SessionData' => [
				'application' => 'denergia_web'
			]
		];
		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('errors', $responseArray);
		$this->assertArrayHasKey('error', $responseArray);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);

		return $responseArray;
	}

	/**
	 * @return mixed
	 */
	public function testLogin() {
		$action = new LoginAction($this->container, $this->container->get(EntityManager::class));
		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request);

		$postData = [
			'email' => 'ti.register@dcide.com.br',
			'password' => 'Dcide123@',
			'SessionData' => [
				'application' => 'denergia_web'
			]
		];
		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('error', $responseArray);
		$this->assertArrayHasKey('loginStatus', $responseArray);
		$this->assertArrayHasKey('sessionKey', $responseArray);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);

		return $responseArray;
	}

	/**
	 * @depends testLogin
	 * @param $postSent
	 */
	public function testLogged($postSent) {
		$action = new LoggedAction($this->container, $this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'SessionData' => [
				'email' => $postSent['User']['email'],
				'sessionKey' => $postSent['sessionKey'],
				'application' => 'denergia_web',
				'applicationLocalSessionKey' => "U*I000ADS9ALIKAOLAKOAOK"
			]
		];

		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);
		$this->assertArrayHasKey('error', $responseArray);
		$this->assertArrayHasKey('loginStatus', $responseArray);
		$this->assertArrayHasKey('sessionKey', $responseArray);
		$this->assertEquals('logged', $responseArray['loginStatus']);
		$this->assertNotTrue($responseArray['error']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testLogin
	 * @param $postSent
	 */
	public function testLogout($postSent) {
		$action = new LogoutAction($this->container, $this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'SessionData' => [
				'email' => $postSent['User']['email'],
				'sessionKey' => $postSent['sessionKey'],
				'application' => 'denergia_web',
				'applicationLocalSessionKey' => "U*I000ADS9ALIKAOLAKOAOK"
			]
		];

		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);
		$this->assertArrayHasKey('error', $responseArray);
		$this->assertNotTrue($responseArray['error']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testLogin
	 * @param $postSent
	 */
	public function testChangePassword($postSent) {
		$action = new Change_passwordAction($this->container, $this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'SessionData' => array (
				'email' => $postSent['User']['email'],
				'oldPassword' => 'Dcide123@',
				'newPassword' => '1234',
				'sessionKey' => $postSent['sessionKey'],
				'application' => 'denergia_web',
				'applicationLocalSessionKey' => "U*I000ADS9ALIKAOLAKOAOK",
			)
		];

		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);
		$this->assertArrayHasKey('error', $responseArray);
		$this->assertNotTrue($responseArray['error']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testLogin
	 * @param $postSent
	 */
	public function testChangeOriginalPassword($postSent) {
		$action = new Change_passwordAction($this->container, $this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'SessionData' => array (
				'email' => $postSent['User']['email'],
				'oldPassword' => '1234',
				'newPassword' => 'Dcide123@',
				'sessionKey' => $postSent['sessionKey'],
				'application' => 'denergia_web',
				'applicationLocalSessionKey' => "U*I000ADS9ALIKAOLAKOAOK",
			)
		];

		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);
		$this->assertArrayHasKey('error', $responseArray);
		$this->assertNotTrue($responseArray['error']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}
}