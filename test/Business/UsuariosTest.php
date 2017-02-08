<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 22/08/16
 * Time: 13:19
 */

namespace AppTest\App;

use App\Util\CustomRequest;
use Business\Usuarios\Action\UsuariosAction;
use Business\Usuarios\Action\UsuariosChangePassword;
use Business\Usuarios\Action\UsuariosCreate;
use Business\Usuarios\Action\UsuariosDelete;
use Business\Usuarios\Action\UsuariosRead;
use Business\Usuarios\Action\UsuariosSearch;
use Business\Usuarios\Action\UsuariosSendMail;
use Business\Usuarios\Action\UsuariosUpdate;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use PHPUnit_Framework_TestCase as TestCase;

class UsuariosTest extends TestCase {
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

	public function testCreateUsuarios() {
		$action = new UsuariosCreate($this->container->get(EntityManager::class), $this->container);

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);

		$postData = [
			'name' => 'Usuario',
			'surname' => 'surname Usuario',
			'email' => 'usuario@usuario.com' . rand(),
			'dCompany' => [
				'id' => '8'
			],
			'dProfile' => [
				['id' => '1']
			],
			'dNewsletter' => [
				['id' => '1']
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

	public function testListUsuarios() {
		$action = new UsuariosAction($this->container->get(EntityManager::class));

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
		$this->assertArrayHasKey('email', $responseArray['result'][0]);
		$this->assertArrayHasKey('surname', $responseArray['result'][0]);

		$this->assertInternalType('int', $responseArray['result'][0]['id']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreateUsuarios
	 * @param $postData
	 */
	public function testGetIdUsuarios($postData) {
		$action = new UsuariosRead($this->container->get(EntityManager::class));

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
		$this->assertEquals($postData['email'], $responseArray['result']['email']);
		$this->assertEquals($postData['surname'], $responseArray['result']['surname']);

		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreateUsuarios
	 */
	public function testEditUsuarios($postData) {
		$action = new UsuariosUpdate($this->container->get(EntityManager::class), $this->container);

		$request = new ServerRequest(['/'], [], null, 'PUT', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request->withAttribute('resourceId', $postData['id']));

		$putData = [
			'name' => 'Usuario editado',
			'surname' => 'surname Usuarioeditado',
			'email' => 'usuario@usuario.comeditado',
			'dCompany' => [
				'id' => '7'
			],
			'dProfile' => [
				['id' => '2'],
				['id' => '3'],
			],
			'dNewsletter' => [
				['id' => '2']
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
	 * @depends testCreateUsuarios
	 */
	public function testDeleteUsuarios($postData) {
		$action = new UsuariosDelete($this->container->get(EntityManager::class));

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

	/**
	 * @depends testCreateUsuarios
	 * @param $postData
	 */
	public function testSendmailNewPassword($postData) {
		$action = new UsuariosSendMail($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'DELETE', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request->withAttribute('resourceId', []));

		$postData = [
			'id' => $postData['id']
		];

		$customRequest->setContent($postData);
		$response = $action->__invoke($customRequest, new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertEquals('Messagem enviada.', $responseArray['message']);
		$this->assertNotTrue($responseArray['error']);
		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * @depends testCreateUsuarios
	 * @param $postData
	 */
	public function testChangePassword($postData) {
		$action = new UsuariosChangePassword($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'DELETE', 'php://input', ['Content-type' => 'application/json'], [], []);

		$customRequest = new CustomRequest($request->withAttribute('resourceId', []));

		$postData = [
			'id' => $postData['id']
		];

		$customRequest->setContent($postData);

		$response = $action->__invoke($customRequest, new Response(), function () {
		});
		$responseJson = $response->getBody()
		                         ->getContents();

		$responseArray = json_decode($responseJson, true);

		$this->assertEquals('Alteração de senha enviada.', $responseArray['message']);
		$this->assertNotTrue($responseArray['error']);
		$this->assertJson($responseJson);
		$this->assertTrue($response instanceof Response);
	}

	/**
	 * testSearchUsuariosByCompany
	 */
	public function testSearchUsuariosByCompany() {
		$action = new UsuariosSearch($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);
		$customRequest->setContent([
			'companyId' => '7'
		]);

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
	 * testSearchUsuariosByCompany
	 */
	public function testSearchUsuariosByNewsletter() {
		$action = new UsuariosSearch($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);
		$customRequest->setContent([
			'newsletter' => [
				1,
				2
			],
		]);

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
	 * testSearchUsuariosByCompany
	 */
	public function testSearchUsuariosByNewsletterAndCompany() {
		$action = new UsuariosSearch($this->container->get(EntityManager::class));

		$request = new ServerRequest(['/'], [], null, 'POST', 'php://input', ['Content-type' => 'application/json'], [], []);
		$customRequest = new CustomRequest($request);
		$customRequest->setContent([
			'companyId' => '7',
			'newsletter' => [
				1,
				2
			],
		]);

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
}