<?php


class CRM_Fieldmetadata_Hook {

  /**
   * Hook to query all extensions that supply fetcher classes
   *
   * @param $fetcherClasses
   * @return mixed
   */
  public static function registerFetcher(&$fetcherClasses) {
    return CRM_Utils_Hook::singleton()->invoke(1, $fetcherClasses, CRM_Utils_Hook::$_nullObject,
      CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject,
      'civicrm_fieldmetadata_registerFetcher'
    );
  }

  /**
   * Hook to query for Normalizer classes
   *
   * @param $normalizerClasses
   * @return mixed
   */
  public static function registerNormalizer(&$normalizerClasses) {
    return CRM_Utils_Hook::singleton()->invoke(1, $normalizerClasses, CRM_Utils_Hook::$_nullObject,
      CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject, CRM_Utils_Hook::$_nullObject,
      'civicrm_fieldmetadata_registerNormalizer'
    );
  }

}