<?php

namespace Adestra;

use \PhpXmlRpc\Value;

class Update extends Core {

	function __construct (){}

	 /**
     * Update a contact's data fields
     *
     * @param Int $id 							- The ID of the contact we are updating.
	 * @param Array $data 						- Key value pairs of data we want to update, the key should be either (*1) or (*2) and value should be the new value of the field.
	 *
     * (*1) - a core-table field name (e.g. 'email').
	 * (*2) - a data-table field name, using the format "<table_id>.<field_name>", e.g. "3.survey_response".
	 *
	 * @return Bool
	 *
     **/
	public static function contact($id, $data = array()) {																// Default values

		$queryData = array();
		foreach($data as $key => $value){																				// See function description for further explination
			$queryData[$key] = new Value($value, (gettype($value) == 'integer') ? 'int' : 'string');						// fieldName => (XMLRPC Int) || (XMLRPC String)
		}

		$params = array(
			new Value($id,'int'),																						// Contact ID (XMLRPC Int)
			new Value($queryData, 'struct')																				// Query Data (XMLRPC Struct)
		);

		return parent::callFunction('contact.update', $params);

	}

	/**
     * Update a contact's subscriptions
     *
     * @param Int $id 							- The ID of the contact we are updating.
	 * @param Array $actionOn 					- Array of list ID's that you want to do the subscription action on.
	 * @param Bool $subscribe (Optional) 		- true by default which subscribes. Change the flag to false to unsubscribe the user from the provided lists.
	 *
	 * @return Bool
	 *
     **/
	public static function subscription($id, $actionOn, $subscribe = true, $api_username, $api_password) {									// Default values

		if ($actionOn === null) {
			$actionOn = array();
		}


		$method = ($subscribe) ? 'subscribe':'unsubscribe';																// To subscribe or to unsubscribe - that is the question.

		$listIDs = array();
		foreach($actionOn as $listID){
			array_push($listIDs, new Value($listID, 'int'));															// List ID (XMLRPC Int)
		}

		$params = array(
			new Value($id,'int'),																						// User ID (XMLRPC Int)
			new Value($listIDs,'array')																					// List ID's (XMLRPC Array)
		);

		return parent::callFunction('contact.'.$method, $params, $api_username, $api_password);

	}


}
