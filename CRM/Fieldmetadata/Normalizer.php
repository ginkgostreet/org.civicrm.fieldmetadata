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
      case "Select":
        return "crmRenderSelect";
      default:
        return "crmRender{$htmlType}";
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