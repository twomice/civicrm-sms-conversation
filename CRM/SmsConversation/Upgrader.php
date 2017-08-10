<?php

/**
 * Collection of upgrade steps.
 */
class CRM_SmsConversation_Upgrader extends CRM_SmsConversation_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  public function upgrade_4700() {
    // Set is_active to required, default 1
    $this->ctx->log->info('Applying update 4700');
    CRM_Core_DAO::executeQuery("UPDATE `civicrm_sms_conversation` SET is_active = 1 WHERE is_active IS NULL");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_sms_conversation` CHANGE `is_active` `is_active` TINYINT(4) NULL DEFAULT '1' COMMENT 'Is this conversation enabled?'");
    return TRUE;
  }

  /**
   * Add column civicrm_sms_conversation_contact.sms_provider_id
   */
  public function upgrade_4701() {
    // Set is_active to required, default 1
    $this->ctx->log->info('Applying update 4701');
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_sms_conversation_contact` ADD `sms_provider_id` INT( 10 ) UNSIGNED NULL COMMENT  'FK to civicrm_sms_provider.id' AFTER  `source_contact_id`");
    CRM_Core_DAO::executeQuery("SELECT @default_sms_provider_id := id FROM civicrm_sms_provider WHERE is_active AND is_default order by ID limit 1");
    CRM_Core_DAO::executeQuery("UPDATE civicrm_sms_conversation_contact SET sms_provider_id = @default_sms_provider_id");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_sms_conversation_contact` ADD FOREIGN KEY (`sms_provider_id`) REFERENCES `civicrm_sms_provider`(`id`) ON DELETE SET NULL ON UPDATE RESTRICT");
    return TRUE;
  }

}
