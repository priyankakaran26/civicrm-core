<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Repeat
 *
 * @author Priyanka
 */
class CRM_Event_Form_ManageEvent_Repeat extends CRM_Event_Form_ManageEvent {
    
    function preProcess() {
        parent::preProcess();
        if($this->_id){
          $eventStartDate = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_Event',
            $this->_id,
            'start_date'
          );
          $this->assign('eventStartDate', $eventStartDate);
        }
    }
  
  public function buildQuickForm() {
    CRM_Core_Form_RecurringEntity::buildQuickForm($this);
  }
   
  public function postProcess() {
    if($this->_id){
        $params = $this->controller->exportValues($this->_name);       
        CRM_Core_Form_RecurringEntity::postProcess($params);
    }else{
        CRM_Core_Error::fatal("Could not find Event ID");
    }  
  }
  
}
