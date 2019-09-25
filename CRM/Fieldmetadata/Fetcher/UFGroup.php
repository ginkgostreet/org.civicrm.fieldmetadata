<?php

/**
 * Class CRM_Fieldmetadata_Fetcher_UFGroup
 *
 * Fetcher class to Fetch field metadata for a Profile/UFGroup
 *
 */
class CRM_Fieldmetadata_Fetcher_UFGroup extends CRM_Fieldmetadata_Fetcher {


  function fetch(&$params) {

    $id = CRM_Utils_Array::value("id", $params);

    $result = civicrm_api3("UFGroup", "get", array(
      'sequential' => 1,
      'id' => $id,
      'api.UFField.get' => array('options' => array(
        'limit' => 0,
        'sort' => 'weight',
      )),
    ));

    $this->group = $result['values'][0];
    unset($this->group['api.UFField.get']);

    $defaults = array("is_required" => false, "is_view" => false, "help_pre" => null, "help_post" => null, "is_multi_summary" => false);

    $this->fields = $result['values'][0]['api.UFField.get']['values'];

    //Merge the fields with the defaults array so it doesn't throw errors if
    //those keys don't exist.
    foreach($this->fields as &$field) {
      $field = array_merge($defaults, $field);
    }

    //Prep the fields for buildProfile
    $this->fields = CRM_Core_BAO_UFGroup::formatUFFields($this->group, $this->fields);

    foreach($this->fields as &$field) {
      $this->activeField = $field['name'];
      if (substr($field['name'], 0, 6) == "custom") {

        //Fetch the options if we need them.
        try {
          $field['id'] = str_replace("custom_", "", $field['name']);
          $optionGroupId = civicrm_api3('CustomField', 'getvalue', array("id" => $field['id'], "return" => "option_group_id"));

          $result = civicrm_api3('OptionValue', 'get', array(
            'sequential' => 1,
            'option_group_id' => $optionGroupId,
            'options' => array('limit' => 0),
            'is_active' => 1,
          ));

          if ($result['count'] > 0) {
            $field['options'] = $result['values'];
          }
        } catch (Exception $e) {}

        //Populate Country Select
        if ($field['data_type'] == "Country" ) {
          if (!array_key_exists("options", $field) || empty($field['options'])) {

            $field['options'] = CRM_Core_PseudoConstant::country();
          }
        }

        //Populate State Select
        if ($field['data_type'] == "StateProvince" && !array_key_exists("options", $field)) {
          $field['options'] = CRM_Core_PseudoConstant::stateProvince();
        }

      } else {

        //Trigger buildProfile function which sets most of the options we
        //need for each field.
        CRM_Core_BAO_UFGroup::buildProfile($this, $field, CRM_Profile_Form::MODE_CREATE);

        //If we need to fetch the options for this field.
        if (array_key_exists("pseudoconstant", $field) && !empty($field['pseudoconstant']) && !array_key_exists("options", $field)) {
          $result = civicrm_api3('OptionValue', 'get', array(
            'sequential' => 1,
            'is_active' => 1,
            'option_group_id' => $field['pseudoconstant']['optionGroupName'],
            'options' => array('limit' => 0),
          ));
          if ($result['count'] > 0) {
            $field['options'] = $result['values'];
          }
        }
      }
    }
    $this->activeField = null;
    $this->group['fields'] = $this->fields;
    return $this->group;
  }


  /**
   * The following functions are designed to mimic the fields
   * of CRM_Core_Form so that we can hijack the buildProfile function
   * as it is the simplest way to get core field options and types.
   *
   **/


  public function add($type, $name, $label = '', $attributes = '', $required = FALSE, $extra = NULL) {
    if (array_key_exists($name, $this->fields)) {
      //Set the HTML type for those fields that don't have it.
      if (!array_key_exists('html_type', $this->fields[$name])) {
        $this->fields[$name]['html_type'] = $type;
      }

      //Set options for those select fields that don't have them.
      if($type == "select" && !array_key_exists('options', $this->fields[$name]) && is_array($attributes)) {
        $this->fields[$name]['options'] = $attributes;
      }
    }
  }

  public function setDefaults($defaultValues, $filter = null) {
    error_log("setDefaults");
  }

  public function createElement($type, $value, $title, $var, $key) {

    if (!array_key_exists('html_type', $this->fields[$this->activeField])) {
      $this->fields[$this->activeField]['html_type'] = $type;
    }

    if ($type == "checkbox") {
      return array($value => $var);
    } else {
      return $var;
    }
  }

  public function addGroup($options, $name, $title) {
    if (array_key_exists($name, $this->fields)) {
      if (!array_key_exists('options', $this->fields[$name]) && (!array_key_exists("pseudoconstant", $this->fields[$name]) || empty($this->fields[$name]['pseudoconstant']))) {
        $this->fields[$name]['options'] = $options;
      }
    }
    return $this;
  }

  public function assign($name, $value) {
    $this->fields[$this->activeField][$name] = $value;
  }
  public function setAttribute($name, $value) {
    $this->fields[$this->activeField]['attributes'][$name] = $value;
  }

  public function __call($method, $args) {
    if (substr($method, 0, 3) == "add") {
      if (!array_key_exists('html_type', $this->fields[$this->activeField])) {
        $this->fields[$this->activeField]['html_type'] = substr($method, 3);
      }
    } else {
      error_log("$method is not defined");
      return $this;
    }
  }

}
