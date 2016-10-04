<?php

require_once 'fieldmetadata.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function fieldmetadata_civicrm_config(&$config) {
  _fieldmetadata_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function fieldmetadata_civicrm_xmlMenu(&$files) {
  _fieldmetadata_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function fieldmetadata_civicrm_install() {
  _fieldmetadata_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function fieldmetadata_civicrm_uninstall() {
  _fieldmetadata_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function fieldmetadata_civicrm_enable() {
  _fieldmetadata_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function fieldmetadata_civicrm_disable() {
  _fieldmetadata_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function fieldmetadata_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _fieldmetadata_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function fieldmetadata_civicrm_managed(&$entities) {
  _fieldmetadata_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function fieldmetadata_civicrm_caseTypes(&$caseTypes) {
  _fieldmetadata_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function fieldmetadata_civicrm_angularModules(&$angularModules) {
  //Run the Civix included function for modules defined in php
  _fieldmetadata_civix_civicrm_angularModules($angularModules);

  $angularModules['crmFieldMetadata'] = array(
    'ext' => 'org.civicrm.fieldmetadata',
    'js' =>
      array (
        0 => 'ang/crmFieldMetadata.js',
        1 => 'ang/crmFieldMetadata/*.js',
        2 => 'ang/crmFieldMetadata/*/*.js'
      ),
    'css' => array (0 => 'ang/crmFieldMetadata.css'),
    'partials' => array (0 => 'ang/crmFieldMetadata'),
    'settings' => array ()
  );
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function fieldmetadata_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _fieldmetadata_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Functions below this ship commented out. Uncomment as required.
 *

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function fieldmetadata_civicrm_preProcess($formName, &$form) {

}

*/


/**
 * implementation of hook_civicrm_registerNormalizer
 * used to register our two built in Normalizers
 *
 * @param $classes
 */
function fieldmetadata_civicrm_fieldmetadata_registerNormalizer(&$classes) {
  $classes['UFGroup'] = "CRM_Fieldmetadata_Normalizer_UFGroup";
  $classes['PriceSet'] = "CRM_Fieldmetadata_Normalizer_PriceSet";
  $classes['PaymentBlock'] = "CRM_Fieldmetadata_Normalizer_PaymentBlock";
  $classes['BillingBlock'] = "CRM_Fieldmetadata_Normalizer_BillingBlock";
}

/**
 * implementation of hook_civicrm_registerFetcher
 * used to register our two built in Fetchers
 *
 * @param $classes
 */
function fieldmetadata_civicrm_fieldmetadata_registerFetcher(&$classes) {
  $classes['UFGroup'] = "CRM_Fieldmetadata_Fetcher_UFGroup";
  $classes['PriceSet'] = "CRM_Fieldmetadata_Fetcher_PriceSet";
  $classes['PaymentBlock'] = "CRM_Fieldmetadata_Fetcher_PaymentBlock";
  $classes['BillingBlock'] = "CRM_Fieldmetadata_Fetcher_BillingBlock";
}