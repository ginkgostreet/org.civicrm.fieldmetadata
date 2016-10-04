<?php

/**
 * Class CRM_Fieldmetadata_Fetcher_BillingBlock
 *
 * Fetch the Address Block for Billing
 *
 */
class CRM_Fieldmetadata_Fetcher_BillingBlock extends CRM_Fieldmetadata_Fetcher {

  function fetch(&$params) {
    $data = array();
    $id = CRM_Utils_Array::value("id", $params);
    $bltId = CRM_Core_BAO_LocationType::getBilling();

    //fetch the payment processor
    $processor = CRM_Financial_BAO_PaymentProcessor::getPaymentProcessors(array('LiveMode'), array($id));
    $processor = $processor[$id];

    //Fetch the metadata
    $data['fields'] = CRM_Core_Payment_Form::getBillingAddressMetadata($processor, $bltId);

    //Set the billing block name and title.
    $data['name'] = "billing_name_address";
    $data['title'] = ts("Billing Name and Address");

    return $data;
  }

}