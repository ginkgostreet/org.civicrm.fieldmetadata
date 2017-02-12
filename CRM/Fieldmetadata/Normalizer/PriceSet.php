<?php

/**
 * Class CRM_Fieldmetadata_Normalizer_PriceSet
 *
 * Normalizer class for normalizing PriceSet and PriceField
 * field metadata
 */
class CRM_Fieldmetadata_Normalizer_PriceSet extends CRM_Fieldmetadata_Normalizer {

  function normalizeData(&$data, $params) {

    $return = array(
      "collectionType" => "PriceSet",
      "title" => $data['title'],
      "name" => $data['name'],
      "preText" => CRM_Utils_Array::value("help_pre", $data, ""),
      "postText" => CRM_Utils_Array::value("help_post", $data, ""),
    );

    $fields = array();

    if (array_key_exists("api.PriceField.get", $data)) {
      $priceFields = CRM_Utils_Array::value("values", $data['api.PriceField.get'], array());
      foreach($priceFields as $priceField) {

        if ($this->priceFieldIsActive($priceField)) {

          $field = $this->getEmptyField();
          $field["collectionType"] = "PriceSet";
          $field["entity"] = "Contribution";
          $field["label"] = $priceField['label'];
          $field["name"] = "price_". $priceField['id'];
          $field["order"] = $priceField['weight'];
          $field["required"] = $priceField['is_required'];
          $field["defaultValue"] = "";
          $field["preText"] = CRM_Utils_Array::value("help_pre", $priceField, "");
          $field["postText"] = CRM_Utils_Array::value("help_post", $priceField, "");
          $field["displayPrice"] = $priceField['is_display_amounts'];
          $field["quantity"] = ($priceField['is_enter_qty'] == 1);


          $field['widget'] = $priceField['html_type'];

          //Handle Options (These exist even for text boxes
          $priceOptions = CRM_Utils_Array::value("values", $priceField['api.PriceFieldValue.get'], array());
          if($priceField['is_enter_qty'] == 1) {
            $field['price'] = $priceOptions[0]['amount'];
          } else {
            foreach ($priceOptions as $priceOption) {
              if ($priceOption['is_active'] == 1) {
                $option = $this->getEmptyOption();
                $option['label'] = $priceOption['label'];
                $option['order'] = $priceOption['weight'];
                $option['price'] = $priceOption['amount'];
                $option["preText"] = CRM_Utils_Array::value("help_pre", $priceOption, "");
                $option["postText"] = CRM_Utils_Array::value("help_post", $priceOption, "");
                $option["default"] = ($priceOption['is_default'] == 1) ? true : false;

                if ($priceField['html_type'] == "CheckBox") {
                  $option['name'] = "price_". $priceField['id'] ."[". $priceOption['id'] ."]";
                  $option['value'] = $priceOption['id'];
                } else {
                  $option['name'] = "price_". $priceField['id'];
                  $option['value'] = $priceOption['id'];
                }

                $field['options'][] = $option;
              }
            }
          }
          $fields[$field['name']] = $field;
        }
      }
    }

    $return['fields'] = $fields;
    return $return;
  }

  /**
   * Determines whether or not a pricefield is active.
   *
   * This method considers criteria in addition to the is_active flag to
   * determine whether or not the field is active.
   *
   * @param array $field
   *   Formatted like the values array from api.PriceField.get.
   * @return boolean
   */
  protected function priceFieldIsActive(array $field) {
    if (!CRM_Utils_Array::value('is_active', $field)) {
      return FALSE;
    }

    $now = time();
    $expireTime = strtotime(CRM_Utils_Array::value('expire_on', $field));
    // Consider the pricefield active if the time string is not parseable
    if ($expireTime !== FALSE && $expireTime < $now) {
      return FALSE;
    }

    $activeTime = strtotime(CRM_Utils_Array::value('active_on', $field));
    // Consider the pricefield active if the time string is not parseable
    if ($activeTime !== FALSE && $activeTime > $now) {
      return FALSE;
    }

    return TRUE;
  }

}