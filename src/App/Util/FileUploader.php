<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 26/07/16
 * Time: 13:53
 */

namespace App\Util;


/**
 * Class FileUploader
 * @package App\Util
 */
class FileUploader {

	/**
	 * @param $base64String
	 * @return string
	 */
	public function base64toImage($base64String, $type) {
		if ($type != "image/jpeg" && $type != "image/png") {
			return false;
		}
		$img = $base64String;
		$img = str_replace('data:' . $type . ';base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);

		if ($this->getFileSize($img)['size'] > 500) {
			return false;
		}

		$file = UPLOAD_DIR . uniqid() . '.jpeg';
		$fullDirFileName = PUBLIC_DIR . $file;
		if (!file_put_contents($fullDirFileName, $data)) {
			return false;
		}

		return $file;
	}

	public function deleteFile($fileName) {
		return unlink(PUBLIC_DIR . $fileName);
	}

	private function getFileSize($base64string) {
		return $this->formatBytes((strlen($base64string) * 3 / 4) - substr_count(substr($base64string, -2), '='));
	}

	private function formatBytes($bytes, $precision = 2) {
		$base = log($bytes, 1024);
		$suffixes = array(
			'',
			'K',
			'M',
			'G',
			'T'
		);

		return [
			'size' => round(pow(1024, $base - floor($base)), $precision),
			'unity' => $suffixes[floor($base)]
		];
	}
}