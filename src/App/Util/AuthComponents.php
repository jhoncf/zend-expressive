<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 14/07/16
 * Time: 11:12
 */

namespace App\Util;


use Interop\Container\ContainerInterface;
use Opis\Session\Session;

/**
 * Class AuthComponents
 * @package App\Util
 */
class AuthComponents {

	/**
	 * @var $session Session
	 */
	private $session;

	/**
	 * AuthComponents constructor.
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container) {
		$this->config = $container->get("config")['AuthComponents'];
	}

	/**
	 * @param $password
	 * @return string
	 */
	public function hashPassword($password) {
		return $this->hash($password, null, false);
	}

	/**
	 * @param $string
	 * @param null $type
	 * @param bool $salt
	 * @return string
	 */
	public function hash($string, $type = null, $salt = false) {
		if ($salt) {
			if (is_string($salt)) {
				$string = $salt . $string;
			}
		} else {
			$salt = $this->config['salt'];
			$string = $salt . $string;
		}

		$type = strtolower($type);

		if ($type == 'sha1' || $type == null) {
			if (function_exists('sha1')) {
				$return = sha1($string);
				return $return;
			}
			$type = 'sha256';
		}

		if ($type == 'sha256' && function_exists('mhash')) {
			return bin2hex(mhash(MHASH_SHA256, $string));
		}

		if (function_exists('hash')) {
			return hash($type, $string);
		}
		return md5($string);
	}

	/**
	 * @param $password
	 * @param $hashedPassword
	 * @return bool
	 * @throws \Exception
	 */
	public function validPassword($password, $hashedPassword) {

		$hashedNewPasswd = $this->hashPassword($password);
		if ($hashedNewPasswd === $hashedPassword) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Inicia os dados a sessão do usuário
	 * @return $this
	 */
	public function sessionStart() {
		if(session_status()){
			session_write_close();
		}
		
		$this->session = new Session();
		return $this;
	}

	/**
	 * @param $username
	 * @return $this
	 */
	public function sessionWrite($username) {
		if(isset($username['username'])){
			$this->session->set('userName', $username['username']);
		}
		$this->session->set('name', $username['name']);
		$this->session->set('email', $username['email']);

		$this->session->set('sessionKey', $this->session->id());
		return $this;
	}

	/**
	 * Retorna os dados da sessão do usuário
	 * @return array
	 */
	public function sessionRead() {
		return [
			'userName' => $this->session->get('userName'),
			'name' => $this->session->get('name'),
			'email' => $this->session->get('email'),
			'sessionKey' => $this->session->get('sessionKey')
		];
	}

	/**
	 * @return bool
	 */
	public function sessionDestroy() {
		$this->session->clear();
		$this->session->regenerate();
		return $this->session->destroy();
	}

	/**
	 * @param $sessionData
	 * @return bool
	 */
	public function sessionStatus($sessionData) {
		if (isset($sessionData['userName']) && $this->session->get('userName') != $sessionData['userName']) {
			return false;
		}

		if ($this->session->get('sessionKey') != $sessionData['sessionKey']) {
			return false;
		}

		return true;
	}

	/**
	 * @param $sessionKey
	 * @return bool
	 */
	public function isLogged($sessionKey){
		if ($this->session->get('sessionKey') != $sessionKey) {
			return false;
		}
		return true;
	}

}