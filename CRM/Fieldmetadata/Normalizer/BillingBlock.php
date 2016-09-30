<?php

/**
 * Class CRM_Fieldmetadata_Normalizer_BillingBlock
 *
 * Normalizer class for normalizing Billing Address Fields
 *
 */
class CRM_Fieldmetadata_Normalizer_BillingBlock extends CRM_Fieldmetadata_Normalizer {

  function normalizeData(&$data, $params) {
    $return = array(
      "collectionType" => "BillingBlock",
      "title" => $data['title'],
      "name" => $data['name'],
      "preText" => CRM_Utils_Array::value("help_pre", $data, ""),
      "postText" => CRM_Utils_Array::value("help_post", $data, ""),
    );

    $fields = array();

    foreach($data['fields'] as $dField) {
      $field = $this->getEmptyField();

      $field["collectionType"] = "BillingBlock";
      $field["entity"] = "Contact";

      $field['name'] = $dField['name'];
      $field['order'] = $this->getFieldOrder($dField['name']);
      $field['required'] = $dField['is_required'];
      $field['label'] = $dField['title'];
      $field['widget'] = $dField['htmlType'];
      $field['rules'] = CRM_Utils_Array::value("rules", $dField);

      if(strtolower($field['widget']) == "select") {
        $optionOrder = 1;
        foreach($dField['attributes'] as $id => $label) {
          $option = $this->getEmptyOption();
          $option['label'] = $label;
          $option['value'] = $id;
          $option['order'] = $optionOrder;
          $field['options'][] = $option;
          $optionOrder++;
        }
      }

      $fields[$field['name']] = $field;
    }



    $return['fields'] = $fields;
    return $return;
  }

  function getFieldOrder($name) {
    switch($name) {
      case "billing_first_name": return 0;
      case "billing_middle_name": return 1;
      case "billing_last_name": return 2;
      case "billing_street_address-5": return 3;
      case "billing_city-5": return 4;
      case "billing_country_id-5": return 5;
      case "billing_state_province_id-5": return 6;
      case "billing_postal_code-5": return 7;
      default: return 9;
    }
  }

}