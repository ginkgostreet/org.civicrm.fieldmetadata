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
    $fieldOrder = 2;
    foreach($data['fields'] as $pField) {
      $field = $this->getEmptyField();

      $field["collectionType"] = "PaymentBlock";
      $field["entity"] = "Transaction";

      $field['name'] = $pField['name'];
      $field['order'] = $fieldOrder;
      $field['required'] = $pField['is_required'];
      $field['label'] = $pField['title'];
      $field['label'] = $pField['title'];
      $field['widget'] = $pField['htmlType'];
      $field['rules'] = $pField['rules'];

      if($field['name'] == "credit_card_exp_date") {
        $field['widget'] = "Expiration";
      } else {
        $field['widget'] = $pField['htmlType'];
      }

      if($field['name'] == "credit_card_type") {
        $field['order'] = 1;
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
      $fieldOrder++;
    }

    $return['fields'] = $fields;

    return $return;
  }

}