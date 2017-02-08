<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 30/06/16
 * Time: 10:34
 */

namespace Exceptions;
use Exception as BaseException;
use Whoops\Run;
use Whoops\Handler\JsonResponseHandler;

/**
 * Class DUsersExceptions
 * @package Exceptions
 */
class DUsersExceptions extends BaseException {

	/**
	 * DUsersExceptions constructor.
	 * @param string $message
	 * @param null $code
	 */
	public function __construct($message, $code = null) {
		parent::__construct($message);
		if(DEBUG){
			$run = new Run();
			$jsonHandler = new JsonResponseHandler();
			$jsonHandler->addTraceToOutput(true);
			$run->pushHandler($jsonHandler);
			$run->register();

			$code = $code != null ? $code : $this->getCode();
			return new BaseException(
				$run->handleException($this),
				$code,
				$this->getHeaders()
			);
		}
		return $message;
	}

}