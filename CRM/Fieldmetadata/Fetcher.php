<?php

/**
 * Class CRM_Fieldmetadata_Fetcher
 *
 * This is the base class for all field-meta-data normalizer classes
 *
 */

class CRM_Fieldmetadata_Fetcher {

  /**
   * Instantiation function to get an instance of a Fetcher
   * sub-class for a given entity
   *
   * @param $entity
   * @return CRM_Case_Form_CustomData
   * @throws CRM_Core_Exception
   */
  public static function &getInstanceForEntity($entity) {
    // key: Entity => value: PHP class
    $fetcherClasses = array();
    CRM_Fieldmetadata_Hook::registerFetcher($fetcherClasses);
    $class = CRM_Utils_Array::value($entity, $fetcherClasses);

    if (!$class) {
      // throw exception indicating no fetcher
      // has been registered for this entity
      throw new CRM_Core_Exception("No Fetcher class has been registered for '{$entity}'", 1);
    }

    $fetcher = new $class;

    if (!is_subclass_of($fetcher, "CRM_Fieldmetadata_Fetcher")) {
      // throw exception indicating the provided class
      // does not extend the required base class
      throw new CRM_Core_Exception("Fetcher class '{$class}' does not extend the 'CRM_Fieldmetadata_Fetcher' base class", 2);
    }

    return $fetcher;
  }
}