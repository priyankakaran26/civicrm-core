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
  
  /**
   * Schedule Reminder Id
   */
  protected $_scheduleReminderId = NULL;
  
  /**
   *  Parent Event ID
   */
  protected $_parentEventId = NULL;
  
  /**
   * Parent Event Start Date
   */
  protected $_parentEventStartDate = NULL;
  
  /**
   * Parent Event End Date
   */
  protected $_parentEventEndDate = NULL;
  protected $_pager = NULL;
  
  
  
  function preProcess() {
    parent::preProcess();
    $this->assign('currentEventId', $this->_id);
    
    $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this);
    $parentEventParams = array('id' => $this->_id);
    $parentEventValues = array();
    $parentEventReturnProperties = array('start_date', 'end_date');
    $parentEventAttributes = CRM_Core_DAO::commonRetrieve('CRM_Event_DAO_Event', $parentEventParams, $parentEventValues, $parentEventReturnProperties);
    $this->_parentEventStartDate = $parentEventAttributes->start_date;
    $this->_parentEventEndDate = $parentEventAttributes->end_date;
    
    /**
     * Get connected event information
     */
    //Duplicate code line remove
    $checkParentExistsForThisId = CRM_Core_Form_RecurringEntity::checkParentExistsForThisId($this->_id);
    if($checkParentExistsForThisId->parent_id){
      //Get all connected event ids
      $allEventIds = CRM_Core_Form_RecurringEntity::getAllConnectedEvents($checkParentExistsForThisId->parent_id);
      if($allEventIds->entity_id){
        //echo "<pre>"; print_r($eventIds);
        //list($offset, $rowCount) = $this->_pager->getOffsetAndRowCount();
        $params = array();
        $query = "
          SELECT *
          FROM civicrm_event
          WHERE id IN (".$allEventIds->entity_id.")
          ORDER BY start_date desc
           ";

        $dao = CRM_Core_DAO::executeQuery($query, $params, TRUE, 'CRM_Event_DAO_Event');
        $permissions = CRM_Event_BAO_Event::checkPermission();
        while ($dao->fetch()) {
          if (in_array($dao->id, $permissions[CRM_Core_Permission::VIEW])) {
            $manageEvent[$dao->id] = array();
            CRM_Core_DAO::storeValues($dao, $manageEvent[$dao->id]);
          }
        }
        $this->assign('rows', $manageEvent);
      }
    }
  }
  
  /**
   * This function sets the default values for the form. For edit/view mode
   * the default values are retrieved from the database
   *
   * @access public
   *
   * @return None
   */
  function setDefaultValues() {
    $defaults = array();
    $checkParentExistsForThisId = CRM_Core_Form_RecurringEntity::checkParentExistsForThisId($this->_id);
    //If this ID has parent, send parent id
    if($checkParentExistsForThisId->parent_id){
      $scheduleReminderDetails = CRM_Core_Form_RecurringEntity::getReminderDetailsByEventId($checkParentExistsForThisId->parent_id);
      $this->_parentEventId = $checkParentExistsForThisId->parent_id;
    }else{
      //ELse send this id as parent
      $scheduleReminderDetails = CRM_Core_Form_RecurringEntity::getReminderDetailsByEventId($this->_id);
      $this->_parentEventId = $this->_id;
    }
    
    //Set Schedule Reminder Id
    $this->_scheduleReminderId = $scheduleReminderDetails->id;

    // Check if there is id for this event in Reminder table
    if($this->_scheduleReminderId){
      $defaults['repetition_frequency_unit'] = $scheduleReminderDetails->repetition_frequency_unit;
      $defaults['repetition_frequency_interval'] = $scheduleReminderDetails->repetition_frequency_interval;
      $defaults['start_action_condition'] = array_flip(explode(",",$scheduleReminderDetails->start_action_condition));
      foreach($defaults['start_action_condition'] as $key => $val){
        $val = 1;
        $defaults['start_action_condition'][$key] = $val;
      }
      list($defaults['repeat_event_start_date'], $defaults['repeat_event_start_date_time']) = CRM_Utils_Date::setDateDefaults($this->_parentEventStartDate, 'activityDateTime');
      $defaults['start_action_offset'] = $scheduleReminderDetails->start_action_offset;
      if($scheduleReminderDetails->start_action_offset){
        $defaults['ends'] = 1;
      }
      list($defaults['repeat_absolute_date']) = CRM_Utils_Date::setDateDefaults($scheduleReminderDetails->absolute_date);
      if($scheduleReminderDetails->absolute_date){
        $defaults['ends'] = 2;
      }
      $defaults['limit_to'] = $scheduleReminderDetails->limit_to;
      if($scheduleReminderDetails->limit_to){
        $defaults['repeats_by'] = 1;
      }
      $explodeStartActionCondition = array();
      $explodeStartActionCondition = explode(" ", $scheduleReminderDetails->start_action_date);
      $defaults['start_action_date_1'] = $explodeStartActionCondition[0];
      $defaults['start_action_date_2'] = $explodeStartActionCondition[1];
      if($scheduleReminderDetails->start_action_date){
        $defaults['repeats_by'] = 2;
      }
    } 
    //CRM_Core_Error::debug($defaults);
    return $defaults;
  }
  
  public function buildQuickForm() {
    CRM_Core_Form_RecurringEntity::buildQuickForm($this);
  }
   
  public function postProcess() {
    if($this->_id){
      $params = $this->controller->exportValues($this->_name); 
      $params['parent_event_id'] = $this->_parentEventId;
      $params['parent_event_start_date'] = $this->_parentEventStartDate;
      $params['parent_event_end_date'] = $this->_parentEventEndDate;
      //Unset event id
      unset($params['id']);
      
      //Set Schedule Reminder id
      $params['id'] = $this->_scheduleReminderId;
//      echo "<pre>"; print_r($params);
      $url = 'civicrm/event/manage/repeat';
      $urlParams = "action=update&reset=1&id={$this->_id}";
      CRM_Core_Form_RecurringEntity::postProcess($params, 'event');
      CRM_Utils_System::redirect(CRM_Utils_System::url($url, $urlParams));
      //CRM_Core_Error::debug_var('Event Recursion');
    }else{
        CRM_Core_Error::fatal("Could not find Event ID");
    }  
  }
  
}