<?php
require_once 'NetSuiteService.php';

class NetSuite {
	public function create_lead($firstname, $lastname, $phone, $email, $landing_page = 'None', $gclid){
		$service = new NetSuiteService();

		$customer = new Customer();

		$lead_source = new SelectCustomFieldRef();
		$lead_source->value = new ListOrRecordRef();
		$lead_source->value->internalId = 1;
		$lead_source->scriptId = 'custentity_gb_lead_source';

		$lead_gclid = new StringCustomFieldRef();
		$lead_gclid->value = $gclid;
		$lead_gclid->scriptId = 'custentity_gb_lead_gclid';

		$lead_landing_page = new StringCustomFieldRef();
		$lead_landing_page->value = $landing_page;
		$lead_landing_page->scriptId = 'custentity_gb_lead_landing_page';

		$lead_ip_address = new StringCustomFieldRef();
		$lead_ip_address->value = $_SERVER['REMOTE_ADDR'];
		$lead_ip_address->scriptId = 'custentity3';

		$customerFields = array (
		    'firstName'			=> $firstname,
		    'lastName'			=> $lastname,
		    'phone'				=> $phone,
		    'email'				=> $email,
		    'entityStatus' 		=> array(
						    		'internalId' => "7", //Set to Lead-qualified
						    		),
		    'customForm'		=> array(
		    						'internalId' => "33", //Set to Geekbox lead form
		    						),
		);
		setFields($customer, $customerFields);
		$customer->customFieldList = new CustomFieldList();
		$customer->customFieldList->customField = array($lead_source, $lead_gclid, $lead_landing_page, $lead_ip_address);

		$request = new AddRequest();
		$request->record = $customer;

		$addResponse = $service->add($request);

		if (!$addResponse->writeResponse->status->isSuccess) {
			print_r($addResponse->writeResponse);
		    return false;
		} else {
		    return true;
		}
	}
}
?>
