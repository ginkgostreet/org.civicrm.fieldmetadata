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
  $params['entity']['description'] = "The entity type for which field metadata is being fetched";
  $params['entity']['type'] = CRM_Utils_Type::T_STRING;

  $params['entity_params']['api.required'] = 1;
  $params['entity_params']['description'] = "Parameters that will be passed to the Fetcher and Normalizer to give those classes enough information to return the metadata required. This will usually include the entity id or name, or sub-action etc";
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
  //Wrap our code in a try/catch so that we can translate the
  //CRM_Core_Exception into an API_Exception because
  //that play nicer with the core api wrapper
  try {
    $fetcher = CRM_Fieldmetadata_Fetcher::getInstanceForEntity($params['entity']);
    $data = $fetcher->fetch($params['entity_params']);

    $normalizer = CRM_Fieldmetadata_Normalizer::getInstanceForEntity($params['entity']);
    $return = $normalizer->normalize($data, $params['entity_params']);

    return civicrm_api3_create_success($return, $params, 'Fieldmetadata', 'Get');

  } catch (Exception $e) {
    throw new API_Exception($e->getMessage(), $e->getCode());
  }
}