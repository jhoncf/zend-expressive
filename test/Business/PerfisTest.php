<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 18/08/16
 * Time: 16:54
 */

namespace AppTest\Business;

use App\Util\CustomRequest;
use Business\Perfis\Action\PerfisAction;
use Business\Perfis\Action\PerfisCreate;
use Business\Perfis\Action\PerfisDelete;
use Business\Perfis\Action\PerfisRead;
use Business\Perfis\Action\PerfisUpdate;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use PHPUnit_Framework_TestCase as TestCase;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;

class PerfisTest extends TestCase {
	/** @var RouterInterface */
	protected $router;

	/**
	 * @var
	 */
	protected $container;

	/**
	 *
	 */
	protected function setUp() {
		$this->router = $this->prophesize(ContainerInterface::class);
		$this->container = require 'config/container.php';
	}

	/**
	 * @return mixed
	 */

	public function testCreatePerfis() {
		$action = new PerfisCreate($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'name' => 'Perfis Teste',
			'description' => 'Perfil description',
			'slug' => 'perfil_description',
			'order' => '1',
			'productOrder' => '1',
		    'dPermissions' => [
		    	['id' => 1],
		        ['id' => 2]
		    ]
		];

		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});

		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$postData['id'] = $responseArray['result']['id'];

		$this->assertArrayHasKey('result', $responseArray);
		$this->assertInternalType('int', $responseArray['result']['id']);
		$this->assertEquals('success', $responseArray['message']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);

		return $postData;
	}

	public function testListPerfis() {
		$action = new PerfisAction($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'GET', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('result', $responseArray);
		$this->assertArrayHasKey('id', $responseArray['result'][0]);
		$this->assertArrayHasKey('name', $responseArray['result'][0]);
		$this->assertArrayHasKey('description', $responseArray['result'][0]);
		$this->assertArrayHasKey('slug', $responseArray['result'][0]);
		$this->assertArrayHasKey('productOrder', $responseArray['result'][0]);
		$this->assertArrayHasKey('order', $responseArray['result'][0]);

		$this->assertInternalType('int', $responseArray['result'][0]['id']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreatePerfis
	 * @param $postData
	 *
	 */
	public function testGetIdPerfis($postData) {
		$action = new PerfisRead($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'GET', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request->withAttribute('resourceId', $postData['id']));

		$response = $action->__invoke($customRequest, new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('result', $responseArray);
		$this->assertInternalType('int', $responseArray['result']['id']);

		$this->assertEquals($postData['id'], $responseArray['result']['id']);
		$this->assertEquals($postData['name'], $responseArray['result']['name']);
		$this->assertEquals($postData['description'], $responseArray['result']['description']);
		$this->assertEquals($postData['slug'], $responseArray['result']['slug']);
		$this->assertEquals($postData['productOrder'], $responseArray['result']['productOrder']);
		$this->assertEquals($postData['order'], $responseArray['result']['order']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreatePerfis
	 */
	public function testEditPerfis($postData) {
		$action = new PerfisUpdate($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'PUT', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request->withAttribute('resourceId', $postData['id']));

		$putData = [
			'name' => 'Perfis Teste Edited',
			'description' => 'Perfil description Edited',
			'slug' => 'perfil_description_Edited',
			'order' => '1',
			'product_order' => '1',
			'dPermissions' => [
				['id' => 1]
			]
		];

		$customRequest->setContent($putData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('result', $responseArray);
		$this->assertEquals('success', $responseArray['message']);
		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreatePerfis
	 */
	public function testDeletePerfis($postData) {
		$action = new PerfisDelete($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'DELETE', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request->withAttribute('resourceId', $postData['id']));

		$response = $action->__invoke($customRequest, new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertEquals('success', $responseArray['message']);
		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}
}