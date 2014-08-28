<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EntityApplyChangesTo
 *
 * @author Priyanka
 */
class CRM_Core_Page_Ajax_RecurringEntity {
  
  public static function updateCascadeType(){
    if(CRM_Utils_Array::value('cascadeType', $_REQUEST) && CRM_Utils_Array::value('entityId', $_REQUEST)){
      $finalResult = array();
      $cascadeType = CRM_Utils_Type::escape($_REQUEST['cascadeType'], 'Integer');
      $entityId = CRM_Utils_Type::escape($_REQUEST['entityId'], 'Integer');
      
      $sql = "UPDATE
          civicrm_recurring_entity
          SET cascade_type = (%1)
          WHERE entity_id = (%2) AND entity_table = 'civicrm_event'";
      $params = array(
                  1 => array($cascadeType, 'Integer'),          
                  2 => array($entityId, 'Integer')
                );
      CRM_Core_DAO::executeQuery($sql, $params);
      $finalResult['status'] = 'Priyanka';
    }
    echo json_encode($finalResult);
  }
}

