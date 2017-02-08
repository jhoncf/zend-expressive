<?php
/**
 * Created by PhpStorm.
 * User: jhonatas
 * Date: 14/07/16
 * Time: 11:27
 */

return [
	'AuthComponents' => [
		'salt' => 'Dinafon - grumpsy daisy 976'
	],
	'SMTPMailConfig' => [
		'host' => 'mail.dcide.com.br',
		'username' => 'denergia@dcide.com.br',
		'password' => 'iG3&xd84',
		'SMTPSecure' => 'tls',
		'port' => 587,
		'from' => [
			'email' => 'denergia@dcide.com.br',
			'name' => 'Dcide',
		]
	],
	'Apps' => [
		'PoolDenergiaUrl' => 'http://jhonatas.pool.vagrant/'
	]
];
