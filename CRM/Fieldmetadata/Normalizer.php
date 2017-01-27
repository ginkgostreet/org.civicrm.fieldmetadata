<?php

/**
 * Class CRM_Fieldmetadata_Normalizer
 *
 * This is the base class for all field-meta-data normalizer classes
 *
 */

abstract class CRM_Fieldmetadata_Normalizer {


  /**
   * Entry point for Normalization
   *
   * @param $data - The data returned by the Fetcher class
   * @param $params
   * @return mixed
   */
  function normalize($data, $params) {
    $metadata = $this->normalizeData($data, $params);
    $this->orderFields($metadata['fields']);
    return $metadata;
  }

  /**
   * Sort the fields by the order key.
   *
   * @param $fields
   */
  function orderFields(&$fields) {
    foreach($fields as $field) {
      if (sizeof($field['options'] > 1)) {
        uasort($field['options'], array($this, "compareOrder"));
      }
    }
    uasort($fields, array($this, "compareOrder"));
  }

  /**
   * Utility function for use inside uasort, called from orderFields()
   *
   * @param $a
   * @param $b
   * @return int
   */
  function compareOrder($a, $b) {
    if ($a['order'] == $b['order']) {
      return 0;
    }
    return ($a['order'] < $b['order']) ? -1 : 1;
  }



  /**
   * Returns an array with all the keys needed
   * for a field
   *
   * @return array
   */
  function getEmptyField() {
    return array(
      "entity" => null,
      "label" => "",
      "name" => "",
      "order" => 0,
      "required" => false,
      "default" => "",
      "options" => array(),
      "price" => array(),
      "displayPrice" => false,
      "quantity" => false,
      "preText" => "",
      "postText" => "",
    );
  }


  /**
   * Returns an array with all the needed keys for
   * a field option.
   *
   * @return array
   */
  function getEmptyOption() {
    return array(
      "label" => "",
      "name" => "",
      "value" => "",
      "order" => 0,
      "required" => false,
      "default" => false,
      "price" => false,
      "preText" => "",
      "postText" => "",
    );
  }

  /**
   * Where CiviCRM gives "", NULL, FALSE, "0", 0, etc. to represent FALSE, this
   * method saves the day by converting the value to a string.
   *
   * When output from normalizers is predictable and consistent, clients have
   * less type juggling to do.
   *
   * This method might be out of place here. It may be more appropriate to have
   * a Field class than to address this level of detail here.
   *
   * @param mixed $value
   * @return string "1" or "0"
   */
  function stringifyBooleanValue($value) {
    return $value ? "1" : "0";
  }

  /**
   * Updates the Widget type based on context
   *
   * @param $fields
   * @param $context
   * @throws CRM_Core_Exception
   */
  function setWidgetTypesByContext(&$fields, $context) {
    $getWidget = "get{$context}Widget";
    if (method_exists($this, $getWidget)) {
      foreach($fields as &$field) {
        $field['widget'] = $this->$getWidget($field['widget']);
        //todo: Run a hook so other extensions can update the widget type.
      }
    } else {
      //todo: Create a hook that registers context
      throw new CRM_Core_Exception("Cannot Set Context", 6);
    }
  }

  /**
   * Maps a field html_type to an angular widget
   *
   * @param $htmlType
   * @return bool|string|CRM_Case_Form_CustomData
   * @throws CRM_Core_Exception
   */
  function getAngularWidget($htmlType) {
    switch($htmlType) {
      //crm-ui-select
      case 'Select State/Province':
        //return "crm-render-state";
        return "crm-render-select";
      case 'Select Country':
        //return "crm-render-country";
        return "crm-render-select";
      case 'RichTextEditor':
        return "crm-ui-richtext";
      case 'advcheckbox':
        return "crm-render-checkbox";
      case 'ChainSelect':
        return "crm-render-chain-select";
      case 'Date':
      case 'DateTime':
        return "crm-ui-datepicker";
      case 'Autocomplete-Select':
        return "crm-entityref";
      default:
        return "crm-render-". strtolower($htmlType);
    }
  }

  /**
   * Function Specific to normalizing for a given entity
   *
   * @param $data
   * @param $params
   * @return mixed
   */
  abstract protected function normalizeData(&$data, $params);

  /**
   * Instantiation function to get an instance of a Normalizer
   * sub-class for a given entity
   *
   * @param $entity - The Name of the entity for which we are trying to normalize metadata
   * @return subclass of CRM_Fieldmetadata_Normalizer for given entity
   * @throws CRM_Core_Exception
   */
  public static function &getInstanceForEntity($entity) {
    // key: Entity => value: PHP class
    $normalizerClasses = array();
    CRM_Fieldmetadata_Hook::registerNormalizer($normalizerClasses);
    $class = CRM_Utils_Array::value($entity, $normalizerClasses);

    if (!$class) {
      // throw exception indicating no normalizer
      // has been registered for this entity
      throw new CRM_Core_Exception("No Normalizer class has been registered for '{$entity}'", 3);
    }

    $normalizer = new $class;

    if (!is_subclass_of($normalizer, "CRM_Fieldmetadata_Normalizer")) {
      // throw exception indicating the provided class
      // does not extend the required base class
      throw new CRM_Core_Exception("Fetcher class '{$class}' does not extend the 'CRM_Fieldmetadata_Normalizer' base class", 4);
    }

    return $normalizer;
  }
}