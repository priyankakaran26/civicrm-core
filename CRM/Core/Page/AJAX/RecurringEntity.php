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
      $finalResult['status'] = 'Done';
    }
    echo json_encode($finalResult);
    CRM_Utils_System::civiExit();
  }
  
  public static function generatePreview(){
    require_once 'packages/When-master/When.php'; 
    $params = array();
    $formValues = array();
    $formValues = $_REQUEST;
    if(!empty($formValues)){
      $dbParams = CRM_Core_BAO_RecurringEntity::mapFormValuesToDB($formValues);
      if(!empty($dbParams)){
        $recursionObject = CRM_Core_BAO_RecurringEntity::getRecursionFromReminderByDBParams($dbParams);
        if(CRM_Utils_Array::value('event_id', $formValues)){
          $parent_event_id = CRM_Core_BAO_RecurringEntity::getParentFor($formValues['event_id'], 'civicrm_event');
          if(!$parent_event_id){
            $parent_event_id = $formValues['event_id'];
          }
          $params['parent_event_id'] = $parent_event_id;
          $params['parent_event_start_date'] = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event', $params['parent_event_id'], 'start_date');
          $params['parent_event_end_date'] = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event', $params['parent_event_id'], 'end_date');
        }
        $recurResult = CRM_Core_Form_RecurringEntity::generateRecursions($recursionObject, $params); 
        $recurDates = array();
        $count = 1;
        foreach ($recurResult as $key => $value) {
          $recurDates[$count]['start_date'] = date('Y-m-d H:i:s', strtotime($value['start_date']));
          $recurDates[$count]['end_date'] = date('Y-m-d H:i:s', strtotime($value['end_date']));
          $count++;
        }
      }
    }
    echo json_encode($recurDates);
    CRM_Utils_System::civiExit();
  }
  
}

