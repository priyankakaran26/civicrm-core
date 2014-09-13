{* file to handle db changes in 4.6.alpha1 during upgrade *}
-- CRM-15256
ALTER TABLE civicrm_action_schedule ADD used_for VARCHAR(64) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Used for repeating entity' AFTER sms_provider_id;

CREATE TABLE IF NOT EXISTS `civicrm_recurring_entity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary key',
  `parent_id` int(10) unsigned NOT NULL COMMENT 'recurring entity parent id',
  `entity_id` int(10) unsigned DEFAULT NULL COMMENT 'recurring entity child id',
  `entity_table` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'physical tablename for entity, e.g. civicrm_event',
  `mode` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-this entity, 2-this and the following entities, 3-all the entities',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=87 ;