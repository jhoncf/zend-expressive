<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 11:03
 */

namespace App\Util;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Stratigility\Http\Request;

/**
 * Class CustomRequest
 * @package App\Util
 */
class CustomRequest extends Request {

	/**
	 * @var
	 */
	var $customParseBody;

	/**
	 * CustomRequest constructor.
	 * @param ServerRequestInterface $decoratedRequest
	 * @param null $originalRequest
	 */
	public function __construct(ServerRequestInterface $decoratedRequest, $originalRequest = null) {
		parent::__construct($decoratedRequest, $originalRequest);
	}

	/**
	 * @param $array
	 */
	public function setContent($array) {
		$this->customParseBody = $array;

	}

	/**
	 * @return array
	 * @throws \Exception
	 */
	public function getContent() {
		if (!empty($this->customParseBody) && !is_array($this->customParseBody)) {
			throw new \Exception("Formato recebido é inválido. Formato JSON requerido.");
		}
		return $this->customParseBody;
	}
}