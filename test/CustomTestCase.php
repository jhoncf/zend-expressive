<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 12/08/16
 * Time: 10:04
 */

namespace AppTest;

use PHPUnit_Framework_TestCase as TestCase;

class CustomTestCase extends TestCase {

	public function customAssert() {
		return true;
	}

}