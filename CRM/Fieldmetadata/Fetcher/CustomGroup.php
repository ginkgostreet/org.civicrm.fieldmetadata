<?php

/**
 * Class CRM_Fieldmetadata_Fetcher_CustomGroup
 *
 * Fetcher class to Fetch metadata for a Custom Group and its fields
 */
class CRM_Fieldmetadata_Fetcher_CustomGroup extends CRM_Fieldmetadata_Fetcher {

  function fetch(&$params) {
    $params['sequential'] = 1;
    $params['api.CustomField.get'] = array(
      'options' => array('limit' => 0),
      'api.OptionValue.get' => array(
        'limit' => 0,
        'option_group_id' => '$value.option_group_id',
        'options' => array('sort' => "weight"),
        'is_active' => 1
      )
    );

    return civicrm_api3("CustomGroup", "getsingle", $params);
  }

}