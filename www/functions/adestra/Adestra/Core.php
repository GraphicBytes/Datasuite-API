<?php

namespace Adestra;

use \PhpXmlRpc\Client;
use \PhpXmlRpc\Request;

class Core
{
	/**
	*
	* Authentication credentials.
	*
	**/

	function __construct (){}

	 /**
     * Protected method to get the client connection
     *
	 * @return Client
	 *
     **/
	protected static function getConnection($username, $password){
		$client = new Client('https://'.$username.':'.$password.'@app.adestra.com/api/xmlrpc');
		$client->return_type = 'phpvals';
		return $client;
	}

	 /**
     * Adestra API function call
     *
     * @param String $method 								- The method name
	 * @param Array $params 								- Array of XMLRPC Values
	 *
	 * @return Array
	 *
     **/
	protected static function callFunction($method, $params, $username, $password) {

		$response = self::getConnection($username, $password)->send(new Request($method, $params));

		if ($response->faultCode()) {
			self::error($response->faultCode());
			die();
		}

		flush();

		return $response->value();

	}

	 /**
     * Handles an error
     *
     * @param String $error 								- Error code returned from unsuccessful request to Adestra API
	 * 														- Full list of error codes can be found here: https://app.adestra.com/doc/page/current/index/api/error-handling
     **/
	protected static function error($error) {
		echo "<p>Server methods list could not be retrieved: error '{$error}'</p>\n";

		die();
	}

}
