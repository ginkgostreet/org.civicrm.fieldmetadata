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
  abstract function normalize($data, $params);

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