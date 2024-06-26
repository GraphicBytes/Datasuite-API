<?php

namespace Adestra;

use \PhpXmlRpc\Value;

class Get extends Core {

	function __construct (){}


	/**
     * Private search for a contact by email.
     *
     * @param String $email 				- Email address to search for.
     * @param Int $tableID 					- The ID of the core table we are searching through.
	 * @param Int $listID 					- The ID of the list that we are searching through.
     *
	 * @return Multidimensional Array
	 *
     **/
	private static function searchByEmail($email, $tableID, $listID){
		$queryData = array(
			"email" => new Value($email, 'string'),																		// User Email (XMLRPC String)
			"_list_id" => new Value($listID, 'int'),																	// List ID (XMLRPC Int)
		);

		$params = array(
			new Value($tableID,'int'),																					// Core Table ID (XMLRPC Int)
			new Value($queryData, 'struct')																				// Query Data (XMLRPC Struct)
		);

		return parent::callFunction('contact.search', $params);															// TO DO - only return the first result - email should be unique anyway.

	}


	/**
     * Private search for a contact by ID.
     *
     * @param Int $id 					- The ID of the contact we are searching for.
	 *
	 * @return Array
	 *
     **/
	private static function getById($id){

		$params = array(
			new Value($id,'int')																						// User ID (XMLRPC Int)
		);

		return parent::callFunction('contact.get', $params);
	}


	/**
     * Public function used to get a contact
     *
     * @param Mixed $search 				- Should be either a String or Int, if a String, search by email otherwise search by ID.
     * @param Int $tableID 					- The ID of the core table we are searching through.
	 * @param Int $listID 					- The ID of the list that we are searching through.
     *
	 * @return Array  || Multidimensional Array
	 *
     **/

	public static function contact($search, $tableID, $listID ) {														// Default values

		if(gettype($search) == 'string'){																				// TO DO check for valid email?
			if(filter_var($search, FILTER_VALIDATE_EMAIL)) {
				return self::searchByEmail($search, $tableID, $listID);
			} else {
				return 'error';
			}

		} else {
			return self::getById($search);
		}
	}


	/**
     * Public function used to get a contact's lists
     *
     * @param Int $id 					- The ID of the contact we are searching for.
     *
	 * @return Array
	 *
     **/
	public static function lists($id) {

		$params = array(
			new Value($id,'int')																						// User ID (XMLRPC Int)
		);

		return parent::callFunction('contact.lists', $params);

	}


	/**
     * Public function used to get a list's details
     *
     * @param Int $id 					- The ID of the list we are searching for.
     *
	 * @return Array
	 *
     **/
	public static function listDetails($list_id) {

		$params = array(
			new Value($list_id,'int')																						// User ID (XMLRPC Int)
		);

		return parent::callFunction('list.get', $params);

	}


	/**
     * Public function used to get USERS by search term, not CONTACTS
     *
     * @param String $search_term 				- The string to search for an exact match to
     * @param String search_term_type 			- Either 'id', 'email', 'username' or 'name'
     *
	 * @return Array
	 *
     **/
	public static function usersBySearch($search_term, $search_term_type) {

		$id = ($search_term_type == 'id') ? $search_term : '';
		$name = ($search_term_type == 'name') ? $search_term : '';
		$username = ($search_term_type == 'username') ? $search_term : '';
		$email = ($search_term_type == 'email') ? $search_term : '';

		$search = array(
			"id" => new Value($email, 'int'),
			"name" => new Value($name, 'string'),
			"username" => new Value($username, 'string'),
			"email" => new Value($email, 'string')
		);

		$params = array(
			new Value($search,'struct')																						// User ID (XMLRPC Int)
		);

		pp($params);

		return parent::callFunction('user.search', $params);

	}


	/**
     * Public function to get contacts by a search term
	 *
	 * @param Int $listID 					- The ID of the list that we are searching through.
     * @param Int $tableID 					- The ID of the core table we are searching through.
     *
	 * @return StructArray
	 *
     **/
	public static function contactsByList($listID, $tableID) {

		$queryData = array(
			"_list_id" => new Value($listID, 'int')		// Only submit a list ID (XMLRPC Int), so the result is all contacts within that list
		);

		$dataTableIds = [$tableID];						// Just pass the single core table ID through

		$params = array(
			new Value($tableID, 'int'),					// Core Table ID (XMLRPC Int)
			new Value($queryData, 'struct')
		);

		/*
		 *	contact.search params: table_id, search, data_table_ids (optional), paging (optional)
		 */
		return parent::callFunction('contact.search', $params);

	}

}


?>
