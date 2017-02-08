<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 12/08/16
 * Time: 10:15
 */

namespace AppTest\App;

use App\Util\CustomRequest;
use App\Auth\Action\LoginAction;
use Doctrine\ORM\EntityManager;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;

class LoginTest extends TestCase {

	/** @var RouterInterface */
	protected $router;

	/**
	 * @var
	 */
	protected $container;

	/**
	 * @var
	 */
	protected $request;

	/**
	 * @var $sessionKey
	 */
	protected $sessionKey;

	/**
	 *
	 */
	protected function setUp() {
		$this->router = $this->prophesize(ContainerInterface::class);
		$this->container = require 'config/container.php';

		$this->request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testLoginResponse() {
		$action = new LoginAction($this->container, $this->container->get(EntityManager::class));

		$customRequest = new CustomRequest($this->request);

		$postData = [
			'userName' => 'ti.register@dcide.com.br',
			'password' => '123',
			'SessionData' => [
				'application' => 'dusers_admin'
			]
		];
		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->sessionKey = $responseArray['result']['sessionKey'];

		$this->assertArrayHasKey('userName', $responseArray['result']);
		$this->assertArrayHasKey('name', $responseArray['result']);
		$this->assertArrayHasKey('email', $responseArray['result']);
		$this->assertArrayHasKey('sessionKey', $responseArray['result']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @runInSeparateProcess
	 */
	public function testWrongLoginResponse() {
		$action = new LoginAction($this->container, $this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'userName' => 'ti.register@dcide.com.br',
			'password' => '1234',
			'SessionData' => [
				'application' => 'dusers_admin'
			]
		];

		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('message', $responseArray);
		$this->assertArrayHasKey('success', $responseArray);

		$this->assertEquals(true, $responseArray['error']);
		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

}