<?php

namespace AppTest\Action;

use App\Action\HomePageAction;
use App\Util\CustomRequest;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Expressive\Router\RouterInterface;
use PHPUnit_Framework_TestCase as TestCase;

class HomePageActionTest extends TestCase {
	/** @var RouterInterface */
	protected $router;

	protected function setUp() {
		$this->router = $this->prophesize(RouterInterface::class);
	}

	public function testResponse() {
		$homePage = new HomePageAction($this->router->reveal(), null);

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'userName' => 'teste@test',
			'password' => 'teste2@'
		];
		$customRequest->setContent($postData);

		$response = $homePage->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();
		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('userName', $responseArray[0]);
		$this->assertArrayHasKey('SessionData', $responseArray[0]);

		$this->assertEquals($postData, $responseArray[0]);
		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}
}