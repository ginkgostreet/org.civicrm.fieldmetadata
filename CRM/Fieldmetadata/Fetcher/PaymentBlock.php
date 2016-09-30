<?php

/**
 * Class CRM_Fieldmetadata_Fetcher_PaymentBlock
 *
 * Fetch the CC, or other payment field details needed to create a payment block.
 *
 */
class CRM_Fieldmetadata_Fetcher_PaymentBlock extends CRM_Fieldmetadata_Fetcher {

  function fetch(&$params) {
    $id = CRM_Utils_Array::value("id", $params);

    //todo: fetch the payment processor metadata
    $processor = CRM_Financial_BAO_PaymentProcessor::getPaymentProcessors(array('LiveMode'), array($id));
    $return = $processor[$id];

    //Fetch the field metadata
    $return['fields'] = CRM_Core_Payment_Form::getPaymentFieldMetadata($processor[$id]);

    return $return;
  }

}