<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 * $Id$
 *
 */

class CRM_Core_BAO_RecurringEntity extends CRM_Core_DAO_RecurringEntity {

  static $_tableDAOMapper = 
    array('civicrm_event' => 'CRM_Event_DAO_Event');

  static public function getEntitiesForParent($parentId, $entityTable, $includeParent = TRUE) {
    $entities = array();

    $query = "SELECT *
      FROM civicrm_recurring_entity
      WHERE parent_id = %1 AND entity_table = %2";
    if (!$includeParent) {
      $query .= " AND entity_id != %1";
    }
    $dao = CRM_Core_DAO::executeQuery($query, 
      array(
        1 => array($parentId, 'Integer'),
        2 => array($entityTable, 'String'),
      )
    );

    while ($dao->fetch()) {
      $entities["{$dao->entity_table}_{$dao->entity_id}"]['table'] = $dao->entity_table;
      $entities["{$dao->entity_table}_{$dao->entity_id}"]['id'] = $dao->entity_id;
    }
    return $entities;
  }

  static public function getEntitiesFor($entityId, $entityTable, $includeParent = TRUE) {
    $parentId = self::getParentFor($entityId, $entityTable);
    if ($parentId) {
      return self::getEntitiesForParent($parentId, $entityTable, $includeParent);
    }
    return array();
  }

  static public function getParentFor($entityId, $entityTable, $includeParent = TRUE) {
    $query = "
      SELECT parent_id 
      FROM civicrm_recurring_entity
      WHERE entity_id = %1 AND entity_table = %2";
    if (!$includeParent) {
      $query .= " AND parent_id != %1";
    }
    $parentId = 
      CRM_Core_DAO::singleValueQuery($query,
        array(
          1 => array($entityId, 'Integer'),
          2 => array($entityTable, 'String'),
        )
      );
    return $parentId;
  }

  static public function triggerUpdate($obj) {
    static $processedEntities = array();
    if (empty($obj->id) || empty($obj->__table)) {
      return FALSE;
    }
    $key = "{$obj->__table}_{$obj->id}";

    if (array_key_exists($key, $processedEntities)) {
      // already processed
      return NULL;
    }

    // get related entities
    $repeatingEntities = self::getEntitiesFor($obj->id, $obj->__table, FALSE);
    if (empty($repeatingEntities)) {
      // return if its not a recurring entity parent
      return NULL;
    }
    // mark processed
    $processedEntities[$key] = 1;

    foreach ($repeatingEntities as $key => $val) {
      $entityID = $val['id'];
      $entityTable = $val['table'];

      $processedEntities[$key] = 1;

      if (array_key_exists($entityTable, self::$_tableDAOMapper)) {
        $daoName = self::$_tableDAOMapper[$entityTable];

        $fromId  = self::getParentFor($entityID, $entityTable);

        // FIXME: generalize me
        $skipData = array('start_date' => NULL, 
          'end_date' => NULL,
        );

        $updateDAO = CRM_Core_DAO::cascadeUpdate($daoName, $fromId, $entityID, $skipData);
      }
    }
    // done with processing. lets unset static var.
    unset($processedEntities);
  }

}

