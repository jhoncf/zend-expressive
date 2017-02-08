<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 12/08/16
 * Time: 13:43
 */

namespace AppTest\Business;

use App\Util\CustomRequest;
use Business\Empresas\Action\EmpresasAction;
use Business\Empresas\Action\EmpresasCreate;
use Business\Empresas\Action\EmpresasDelete;
use Business\Empresas\Action\EmpresasRead;
use Business\Empresas\Action\EmpresasUpdate;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use PHPUnit_Framework_TestCase as TestCase;

class EmpresasTest extends TestCase {
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
	 * Expected data example:
	 * {
	 *  "result": {
	 *      "id": 11275
	 * },
	 * "message": "success"
	 * }
	 */

	/**
	 * @return mixed
	 */

	public function testCreateEmpresa() {
		$action = new EmpresasCreate($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'name' => 'Empresa Teste',
			'shortName' => '123',
			'imageUrl' => 'null',
			'domain' => 'teste.com'
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

	public function testListEmpresa() {
		$action = new EmpresasAction($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'GET', 'php://input', ['Content-type' => 'application/json'], [], []);

		$response = $action->__invoke($request, new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('result', $responseArray);
		$this->assertArrayHasKey('id', $responseArray['result'][0]);
		$this->assertArrayHasKey('name', $responseArray['result'][0]);
		$this->assertArrayHasKey('imageUrl', $responseArray['result'][0]);
		$this->assertArrayHasKey('domain', $responseArray['result'][0]);

		$this->assertInternalType('int', $responseArray['result'][0]['id']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreateEmpresa
	 */
	public function testGetIdEmpresa($postData) {
		$action = new EmpresasRead($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'GET', 'php://input', ['Content-type' => 'application/json'], [], []);

		$response = $action->__invoke($request->withAttribute('resourceId', $postData['id']), new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('result', $responseArray);
		$this->assertInternalType('int', $responseArray['result']['id']);

		$this->assertEquals($postData['id'], $responseArray['result']['id']);
		$this->assertEquals($postData['name'], $responseArray['result']['name']);
		$this->assertEquals($postData['shortName'], $responseArray['result']['shortName']);
		$this->assertEquals($postData['imageUrl'], $responseArray['result']['imageUrl']);
		$this->assertEquals($postData['domain'], $responseArray['result']['domain']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreateEmpresa
	 */
	public function testEditEmpresa($postData) {
		$action = new EmpresasUpdate($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'PUT', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request->withAttribute('resourceId', $postData['id']));

		$putData = [
			'name' => 'Empresa Teste Editado',
			'shortName' => 'Teste Editado',
			'imageUrl' => 'teste',
			'domain' => 'teste.com'
		];

		$customRequest->setContent($putData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertArrayHasKey('result', $responseArray);
		$this->assertInternalType('int', $responseArray['result']['id']);

		$this->assertEquals($postData['id'], $responseArray['result']['id']);
		$this->assertEquals($putData['name'], $responseArray['result']['name']);
		$this->assertEquals($putData['shortName'], $responseArray['result']['shortName']);
		$this->assertEquals($putData['imageUrl'], $responseArray['result']['imageUrl']);
		$this->assertEquals($putData['domain'], $responseArray['result']['domain']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreateEmpresa
	 */
	public function testDeleteEmpresa($postData) {
		$action = new EmpresasDelete($this->container->get(EntityManager::class));

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