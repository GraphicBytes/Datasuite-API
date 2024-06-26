<?php

namespace Adestra;

use \PhpXmlRpc\Value;

class Create extends Core
{

	function __construct()
	{
	}

	/**
	 * Public function used to Create a campaign contact
	 *
	 * @param String $email 				- Email address to search for.
	 * @param Int $tableID 					- The ID of the core table we are searching through.
	 * @param Int $listID 					- The ID of the list that we are searching through.
	 *
	 * @return Bool
	 *
	 * contact-create docs: https://app.adestra.com/doc/page/current/index/api/contact#contact-create
	 **/

	public static function contact($email, $tableID, $listID, $first_name, $last_name, $full_name, $api_username, $api_password, $api_qr_path_target, $api_qr_path, $extraData)
	{

		$queryData = array(
			"email" => new Value($email, 'string'),
			"_list_id" => new Value($listID, 'int'),
			"first_name" => new Value($first_name, 'string'),
			"last_name" => new Value($last_name, 'string'),
			"full_name" => new Value($full_name, 'string'),
			"user_accepts_marketing_1" => new Value("yes", 'string'),
		);

		$queryData[$api_qr_path_target] = new Value($api_qr_path, 'string');


		foreach ($extraData as $key => $value) {
			
			// stringify arrays passed to Adestra (like from multi-selects)
			$cleanValue = $value;
			if(gettype($cleanValue) == 'array') {
				$cleanValue = implode(', ', $cleanValue);
			}
        
			$queryData[$key] = new Value($cleanValue, 'string');
		}

		$params = array(
			new Value($tableID, 'int'),
			new Value($queryData, 'struct')
		);

		return parent::callFunction('contact.create', $params, $api_username, $api_password);															// TO DO - only return the first result - email should be unique anyway.

	}
}
