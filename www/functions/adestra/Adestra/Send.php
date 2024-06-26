<?php 

namespace Adestra;

use \PhpXmlRpc\Value;

class Send extends Core {
	
	function __construct (){}	
	 	
	/**
     * Public function used to send a campaign to a contact
     *
     * @param Int $contactID 							- The ID of the contact
     * @param Int $campaignID 							- The ID of the campaign
     *           
	 * @return Bool
	 *
	 * sendSingle docs: https://app.adestra.com/doc/page/current/index/api/campaign#campaign-sendSingle
    **/
	
	public static function campaign($campaign_id, $contact_id) {
		
		$send_single_options = array(								// a set of optional options
			//new Value(false, 'bool'),									// suppression_info - unlikely to be needed
			//new Value('', 'string'),									// launch_reference - might be useful for tracking/reporting
			//new Value(0, 'struct')									// brand - won't be needed
		);
		
		$transaction_data = array();								// optional stuff made available to the campaign template (XMLRPC Struct)
		
		$params = array(			
			new Value($campaign_id, 'int'),								// Campaign ID (XMLRPC Int)
			new Value($contact_id, 'int'),								// User ID (XMLRPC Int)
			new Value($transaction_data, 'struct'),						// Campaign template data
			new Value($send_single_options, 'struct')					// Options (XMLRPC Struct)
		);
		
		return self::callFunction('campaign.sendSingle', $params);			
		
	}
	
}


?>