<?php

/**
 * Class CRM_Fieldmetadata_Normalizer_UFGroup
 *
 * Normalizer class for normalizing profile field metadata
 *
 */
class CRM_Fieldmetadata_Normalizer_UFGroup extends CRM_Fieldmetadata_Normalizer {

  function normalizeData(&$data, $params) {

    $return = array(
      "collectionType" => "UFGroup",
      "title" => $data['title'],
      "name" => $data['name'],
      "preText" => CRM_Utils_Array::value("help_pre", $data, ""),
      "postText" => CRM_Utils_Array::value("help_post", $data, ""),
    );

    $fields = array();

    $fieldOrder = 1;
    foreach($data['fields'] as $fieldData) {

      $field = $this->getEmptyField();
      $field["collectionType"] = "UFGroup";
      $field["entity"] = $fieldData['field_type'];
      $field["label"] = $fieldData['title'];
      $field["name"] = $fieldData['name'];
      $field["order"] = $fieldOrder;
      $field["required"] = $this->stringifyBooleanValue($fieldData['is_required']);
      $field["attributes"] = $fieldData['attributes'];
      $field["defaultValue"] = "";
      $field["preText"] = CRM_Utils_Array::value("help_pre", $fieldData, "");
      $field["postText"] = CRM_Utils_Array::value("help_post", $fieldData, "");

      //todo: Use either the html_type or the data type
      $field['widget'] = $fieldData['html_type'];

      if($field['widget'] == "ChainSelect" && strpos($field['name'], "state_province") !== false) {
        $field['attributes']['watcher'] = str_replace("state_province", "country", $field['name']);
        $field['attributes']['data-callback'] = CRM_Utils_System::url("civicrm/ajax/jqState");
      }

      if(array_key_exists("options", $fieldData)) {
        $index = 1;
        foreach($fieldData['options'] as $key => $optionData) {

          $option = $this->getEmptyOption();
          $option["preText"] = CRM_Utils_Array::value("help_pre", $optionData, "");
          $option["postText"] = CRM_Utils_Array::value("help_post", $optionData, "");


          if(!(substr($field['name'], 0, 6) == "custom") && empty($fieldData['pseudoconstant'])) {
            $option['order'] = $index;
            if (is_array($optionData)) {
              $option['label'] = reset($optionData);
              $option['name'] = key($optionData);
              $option['value'] = key($optionData);
            } else {
              $option['label'] = $optionData;
              $option['name'] = $optionData;
              $option['value'] = $key;
            }
          } else {
            if (is_array($optionData)) {
              $option['label'] = $optionData['label'];
              $option["default"] = CRM_Utils_Array::value("is_default", $optionData, false);
              $option['order'] = $optionData['weight'];
              $option['name'] = CRM_Utils_Array::value("name", $optionData, "");
              $option['value'] = $optionData['value'];
            } else {
              $option['order'] = $index;
              $option['label'] = $optionData;
              $option['name'] = $optionData;
              $option['value'] = $key;
            }
          }

          $field['options'][] = $option;
          $index++;
        }
      }

      $fields[] = $field;
      $fieldOrder++;
    }

    $return['fields'] = $fields;
    //$return['fields'] = array();
    return $return;
  }

}