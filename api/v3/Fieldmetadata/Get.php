<?php

/**
 * Fieldmetadata.Get API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRM/API+Architecture+Standards
 */
function _civicrm_api3_fieldmetadata_get_spec(&$params) {
  $params['entity']['api.required'] = 1;
  $params['params']['api.required'] = 1;
}


/**
 * Fieldmetadata.Get API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_fieldmetadata_get($params) {

  return civicrm_api3_create_success(array(), $params, 'Fieldmetadata', 'Get');
}