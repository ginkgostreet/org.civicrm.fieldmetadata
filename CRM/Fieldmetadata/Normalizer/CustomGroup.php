<?php

/**
 * Class CRM_Fieldmetadata_NormalizerCustomGroup
 *
 * Normalizer class for normalizing Custom Group and Custom Field metadata
 */
class CRM_Fieldmetadata_Normalizer_CustomGroup extends CRM_Fieldmetadata_Normalizer {

  function normalizeData(&$data, $params) {
    $return = array(
      "collectionType" => "CustomGroup",
      "title" => $data['title'],
      "name" => $data['name'],
      "preText" => CRM_Utils_Array::value("help_pre", $data, ""),
      "postText" => CRM_Utils_Array::value("help_post", $data, ""),
    );

    $fields = array();

    $customFields = CRM_Utils_Array::value("values", $data['api.CustomField.get'], array());
    foreach ($customFields as $customField) {

      if (!empty($customField['is_active'])) {
        $field = $this->getEmptyField();
        $field["collectionType"] = "CustomGroup";
        $field["entity"] = $data['extends'];
        $field["is_active"] = $this->normalizeBoolean($customField['is_active']);
        $field["label"] = $customField['label'];
        $field["name"] = 'custom_' . $customField['id'];
        $field["order"] = $customField['weight'];
        $field["required"] = $this->normalizeBoolean($customField['is_required']);
        $field["defaultValue"] = "";
        $field["preText"] = CRM_Utils_Array::value("help_pre", $customField, "");
        $field["postText"] = CRM_Utils_Array::value("help_post", $customField, "");

        $field['widget'] = $customField['html_type'];

        // This check is made because API chaining is not reliable in cases where
        // the value that is chained off of is NULL. See CRM-17327, for example.
        // TODO: Possibly the better fix for this is not to chain at all and to
        // use the API's join capabilities instead.
        if (!empty($customField['option_group_id'])) {
          $field['options'] = $this->normalizeFieldOptions($customField);
        }

        if ($customField['data_type'] === 'Boolean') {
          $field['options'] = $this->mockBooleanOptions($fields);
        }

        $fields[$field['name']] = $field;
      }
    }

    $return['fields'] = $fields;
    return $return;
  }

  /**
   * @param array $customField
   *   Looks like the result of api.CustomField.getsingle.
   * @return array
   */
  private function normalizeFieldOptions(array $customField) {
    $result = array();
    $fieldOptions = CRM_Utils_Array::value("values", $customField['api.OptionValue.get'], array());
    foreach ($fieldOptions as $fieldOption) {
      if ($fieldOption['is_active'] == 1) {
        $option = $this->getEmptyOption();
        $option['is_active'] = $this->normalizeBoolean($fieldOption['is_active']);
        $option['label'] = $fieldOption['label'];
        $option['order'] = $fieldOption['weight'];
        $option['default'] = $this->normalizeBoolean($fieldOption['is_default']);
        $option['value'] = $fieldOption['value'];

        $option['name'] = $fieldOption['name'];
        if ($customField['html_type'] == "CheckBox") {
          $option['name'] = 'custom_' . $customField['id'] . '[' . $fieldOption['value'] . ']';
        }

        $result[] = $option;
      }
    }

    return $result;
  }

}
