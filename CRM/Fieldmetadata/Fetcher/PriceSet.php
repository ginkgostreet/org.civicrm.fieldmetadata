<?php

/**
 * Class CRM_Fieldmetadata_Fetcher_PriceSet
 *
 * PriceSet Fetcher class to Fetch meta-data for a PriceSet
 *
 */
class CRM_Fieldmetadata_Fetcher_PriceSet extends CRM_Fieldmetadata_Fetcher {

  function fetch(&$params) {

    $id = CRM_Utils_Array::value("id", $params);

    $result = civicrm_api3("PriceSet", "get", array(
      'sequential' => 1,
      'id' => $id,
      'api.PriceField.get' => array(
        'options' => array('limit' => 0),
        'api.PriceFieldValue.get' => array(
          'options' => array('limit' => 0),
        ),
      ),
    ));

    return $result['values'][0];
  }

}