<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 04/07/16
 * Time: 14:24
 */

namespace App\Helper;


class EntityHelper {

	/**
	 * @param array $options
	 * @param $obj
	 * @return mixed
	 */
	static public function setOptions(array $options, $obj) {
		$methods = get_class_methods($obj);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if ((in_array($method, $methods) && $method != 'setCreated' && $method != 'setModified') && !is_array($value)) {
				$obj->$method($value);
			}
		}
		return $obj;
	}
}