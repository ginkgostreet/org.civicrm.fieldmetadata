<?php

/**
 * Class CRM_Fieldmetadata_Normalizer_PaymentBlock
 *
 * Normalizer class for normalizing Payment Block Field metadata
 *
 */
class CRM_Fieldmetadata_Normalizer_PaymentBlock extends CRM_Fieldmetadata_Normalizer {

  function normalizeData(&$data, $params) {
    $return = array(
      "collectionType" => "PaymentBlock",
      "title" => $data['title'],
      "name" => $data['class_name'],
      "preText" => CRM_Utils_Array::value("help_pre", $data, ""),
      "postText" => CRM_Utils_Array::value("help_post", $data, ""),
    );

    $fields = array();
    foreach($data['fields'] as $pField) {
      $field = $this->getEmptyField();

      $field["collectionType"] = "PaymentBlock";
      $field["entity"] = "Transaction";

      $field['name'] = $pField['name'];
      $field['order'] = $this->getFieldOrder($pField['name']);
      $field['required'] = $pField['is_required'];
      $field['label'] = $pField['title'];
      $field['rules'] = $pField['rules'];

      if($field['name'] == "credit_card_exp_date") {
        $field['widget'] = "Expiration";
      } else {
        $field['widget'] = $pField['htmlType'];
      }

      if($field['name'] == "credit_card_type") {
        $optionOrder = 1;
        foreach($pField['attributes'] as $id => $label) {
          $option = $this->getEmptyOption();
          $option['label'] = $label;
          $option['value'] = $id;
          $option['order'] = $optionOrder;
          $field['options'][] = $option;
          $optionOrder++;
        }

      } else {
        $field['attributes'] = $pField['attributes'];
      }


      $fields[$field['name']] = $field;
    }

    $return['fields'] = $fields;

    return $return;
  }

  function getFieldOrder($name) {
    switch($name) {
      case "credit_card_type": return 0;
      case "credit_card_number": return 1;
      case "credit_card_exp_date": return 2;
      case "cvv2": return 3;
      default: return 6;
    }
  }

}