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
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 * $Id$
 *
 */
require_once 'packages/When-master/When.php'; 
/**
 * This class generates form components for processing Event
 *
 */
class CRM_Core_Form_RecurringEntity {
  
  static function preProcess(){
    if (date_default_timezone_get()) {
      date_default_timezone_get();
    }
    date_default_timezone_set(date_default_timezone_get());
  }
  /**
   * action
   *
   * @var int
   */
  
  static function buildQuickForm(&$form) {
    //$attributes_schedule = CRM_Core_DAO::getAttribute('CRM_Core_DAO_ActionMapping');

    $form->_freqUnits = array('hour' => 'hour') + CRM_Core_OptionGroup::values('recur_frequency_units');
    foreach ($form->_freqUnits as $val => $label) {
      if($label == "day"){
          $label = "dai";
      }
      $freqUnitsDisplay[$val] = ts('%1ly', array(1 => $label));
    }
   // echo "<pre>";print_r($freqUnitsDisplay);
    $dayOfTheWeek = array('monday'   => 'Monday',
                          'tuesday'   => 'Tuesday',
                          'wednesday' => 'Wednesday',
                          'thursday'  => 'Thursday',
                          'friday'    => 'Friday',
                          'saturday'  => 'Saturday',
                          'sunday'    => 'Sunday'
                         );
    $form->add('select', 'repetition_frequency_unit', ts('Repeats:'), $freqUnitsDisplay, TRUE);
    $numericOptions = CRM_Core_SelectValues::getNumericOptions(1, 30);
    $form->add('select', 'repetition_frequency_interval', ts('Repeats every:'), $numericOptions, TRUE);
    foreach($dayOfTheWeek as $key => $val){
        $startActionCondition[] = $form->createElement('checkbox', $key, NULL, substr($val."&nbsp;", 0, 3));
    }
    $form->addGroup($startActionCondition, 'start_action_condition', ts('Repeats on'));
    $roptionTypes = array('1' => ts('day of the month'),
        '2' => ts('day of the week'),
      );
    $form->addRadio('repeats_by', ts("Repeats By:"), $roptionTypes, array(), NULL);
    $form->add('text', 'limit_to', '', array('maxlength' => 2, 'size' => 10));
    $dayOfTheWeekNo = array('first'  => 'First',
                            'second'=> 'Second',
                            'third' => 'Third',
                            'fourth'=> 'Fourth',
                            'last'  => 'Last'
                         );
    $form->add('select', 'start_action_date_1', ts(''), $dayOfTheWeekNo);
    $form->add('select', 'start_action_date_2', ts(''), $dayOfTheWeek);
    //$form->addDateTime('repeat_event_start_date', ts('Start Date:'), FALSE, array('formatType' => 'activityDateTime'));
    $eoptionTypes = array('1' => ts('After'),
        '2' => ts('On'),
      );
    $form->addRadio('ends', ts("Ends:"), $eoptionTypes, array(), NULL);
    $form->add('text', 'start_action_offset', ts('Occurrences'),
      array(
        'size' => 45,
        'maxlength' => 128
      )
    );
    $form->addFormRule(array('CRM_Core_Form_RecurringEntity', 'formRule'));
    $form->addDate('repeat_absolute_date', ts('On'), FALSE, array('formatType' => 'mailing'));
    $form->addDate('exclude_date', ts('Exclude Date(s)'), FALSE);
    $form->addElement('select', 'exclude_date_list', ts(''), array(), array('style' => 'width:200px;', 'multiple' => 'multiple'));
    $form->addElement('button','add_to_exclude_list','>>','onClick="addToExcludeList(document.getElementById(\'exclude_date\').value);"'); 
    $form->addElement('button','remove_from_exclude_list', '<<', 'onClick="removeFromExcludeList(\'exclude_date_list\')"'); 
    $form->addElement('hidden', 'isChangeInRepeatConfiguration', '', array('id' => 'isChangeInRepeatConfiguration'));
    $form->addButtons(array(
        array(
          'type' => 'submit',
          'name' => ts('Save'),
          'isDefault' => TRUE,
        ),
        array(
          'type' => 'cancel',
          'name' => ts('Cancel')
        ),
      )
    );
  }

  /**
   * global validation rules for the form
   *
   * @param array $fields posted values of the form
   *
   * @return array list of errors to be posted back to the form
   * @static
   * @access public
   */
  static function formRule($values) {
    $errors = array();
//    $start = CRM_Utils_Date::processDate($values['repeat_event_start_date']);
//    $end = CRM_Utils_Date::processDate($values['repeat_absolute_date']);
//    if (($end < $start) && ($end != 0)) {
//      $errors['repeat_absolute_date'] = ts('End date should be after Start date');
//    }
    return $errors;
  }

  /**
   * Function to process the form
   *
   * @access public
   *
   * @return None
   */
  static function postProcess($params=array(), $type='') {
    $buildRule = ''; 
    if(!empty($type)){
      $params['used_for'] = $type;
    }
    $params['entity_value'] = $params['parent_event_id'];
    unset($params['repeat_event_start_date']);
    /**
     *  Build Recursion Rules and Set POST params
     */
    
    //For Repeats:
    if(CRM_Utils_Array::value('repetition_frequency_unit', $params)){
      $repetition_frequency_unit = $params['repetition_frequency_unit'];
      if($repetition_frequency_unit == 'day'){
        $repetition_frequency_unit = 'dai';
      }
      $buildRule = 'FREQ='.strtoupper($repetition_frequency_unit.'ly;');
    }
    
    //For Repeats every:
    if(CRM_Utils_Array::value('repetition_frequency_interval', $params)){
      $buildRule .= 'INTERVAL='.$params['repetition_frequency_interval'].';';
    }
    
    //For Repeats on:(weekly case)
    if($params['repetition_frequency_unit'] == 'week'){
      if(CRM_Utils_Array::value('start_action_condition', $params)){
        $repeats_on = CRM_Utils_Array::value('start_action_condition', $params);
        //echo "<pre>"; print_r($params);exit;
        $params['start_action_condition'] = implode(",", array_keys($repeats_on));
        $buildRuleArray = array();
        foreach($repeats_on as $key => $val){
          $buildRuleArray[] = substr($key, 0, 2);
        }
        $buildRule .= 'WKST=MO;BYDAY='.strtoupper(implode(',', $buildRuleArray)).';';
      }
    }else{
      unset($params['start_action_condition']);
    }
    
    //For Repeats By:(monthly case)
    if($params['repetition_frequency_unit'] == 'month'){
      if($params['repeats_by'] == 1){
        if(CRM_Utils_Array::value('limit_to', $params)){
          $buildRule .= 'BYMONTHDAY='.$params['limit_to'].';';
        }
      }else{
        unset($params['limit_to']);
      }
      if($params['repeats_by'] == 2){
        if(CRM_Utils_Array::value('start_action_date_1', $params) && CRM_Utils_Array::value('start_action_date_2', $params)){
          $startActionDate1 = '';
          switch ($params['start_action_date_1']) {
            case 'first':
                $startActionDate1 = 1;
                break;
            case 'second':
                $startActionDate1 = 2;
                break;
            case 'third':
                $startActionDate1 = 3;
                break;
            case 'fourth':
                $startActionDate1 = 4;
                break;
            case 'last':
                $startActionDate1 = -1;
                break;
          }
          $buildRule .= 'BYDAY='.$startActionDate1.strtoupper(substr($params['start_action_date_2'], 0, 2).';');
          $params['start_action_date'] = $params['start_action_date_1']." ".$params['start_action_date_2'];
          //Set value of "day of the month" to 0 since db has default value as 1
          $params['limit_to'] = 0;
        }
      }else{
        unset($params['limit_to'], $params['start_action_date']);
      }
    }else{
      unset($params['limit_to'], $params['start_action_date']);
    }
    
    //For "Ends" - After: 
    if($params['ends'] == 1){
      if(CRM_Utils_Array::value('start_action_offset', $params)){
          $buildRule .= 'COUNT='.$params['start_action_offset'].';';
      }
    }else{
      unset($params['start_action_offset']);
    }
    
    //For "Ends" - On: 
    if($params['ends'] == 2){
      if(CRM_Utils_Array::value('repeat_absolute_date', $params)){
        $buildRule .= 'UNTIL='.CRM_Utils_Date::processDate($params['repeat_absolute_date']).';';
        $params['absolute_date'] = CRM_Utils_Date::processDate($params['repeat_absolute_date']);
      }
    }else{
      unset($params['absolute_date']);
    }
    $buildRule = rtrim($buildRule, ';');
    if(CRM_Utils_Array::value('id', $params)){
      CRM_Core_BAO_ActionSchedule::del($params['id']);
      unset($params['id']);
    }
    CRM_Core_BAO_ActionSchedule::add($params);
    
    //TO DO - Exclude date functionality
    if(CRM_Utils_Array::value('exclude_date_list', $params)){
      //$groupParams = array('name' => 'event_repeat_exclude_dates_'.$params['parent_event_id']);
      //$optionValue = CRM_Core_OptionValue::addOptionValue($params['exclude_date_list'], $groupParams, $action);
    }

    //Check to avoid confusions with current date in the repeat list
    if($params['repetition_frequency_unit'] == "hour"){
      //Add as many number of hours in the criteria posted
      if(CRM_Utils_Array::value('repetition_frequency_interval', $params)){
        $newStartDate = date('Y-m-d H:i:s', strtotime($params['parent_event_start_date']. ' + '.$params['repetition_frequency_interval'].' hours'));
        $start = new DateTime($newStartDate);
      }
    }else if($params['repetition_frequency_unit'] == "day"){
      if(CRM_Utils_Array::value('repetition_frequency_interval', $params)){
        $newStartDate = date('Y-m-d H:i:s', strtotime($params['parent_event_start_date']. ' + '.$params['repetition_frequency_interval'].' days'));
        $start = new DateTime($newStartDate);
      }
    }else if($params['repetition_frequency_unit'] == "year"){
      if(CRM_Utils_Array::value('repetition_frequency_interval', $params)){
        $newStartDate = date('Y-m-d H:i:s', strtotime($params['parent_event_start_date']. ' + '.$params['repetition_frequency_interval'].' years'));
        $start = new DateTime($newStartDate);
      }
    }else{  
      $start = new DateTime($params['parent_event_start_date']);
    }
    
    //Give call to create recursions
    self::generateRecursions($start, $buildRule, $params);
  }
  //end of function

  /**
   * Return a descriptive name for the page, used in wizard header
   *
   * @return string
   * @access public
   */
  public function getTitle() {
    return ts('Repeat Event');
  }

  static public function generateRecursions($startDate='', $buildRule='', $params=array()){
    //echo "<pre>"; print_r($params);exit;
    $r = new When();
    $newParams = array();
    if(!empty($startDate) && !empty($buildRule) && !empty($params)){
      //Proceed only if these keys are found in array
      if(CRM_Utils_Array::value('parent_event_start_date', $params) && CRM_Utils_Array::value('parent_event_end_date', $params) && CRM_Utils_Array::value('parent_event_id', $params))
//      echo $buildRule;
      $r->recur($startDate)->rrule("$buildRule");
      while($result = $r->next()){
        //$result->format('YmdHis'). '<br />';

        $newParams['start_date'] = CRM_Utils_Date::processDate($result->format('YmdHis'));
        $parentStartDate = strtotime($params['parent_event_start_date']);
        $parentEndDate = strtotime($params['parent_event_end_date']);
        $diff = abs($parentEndDate - $parentStartDate);
        $years   = floor($diff / (365*60*60*24)); 
        $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
        $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); 
        $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
        $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
        $end_date = CRM_Utils_Date::processDate(date('YmdHis', strtotime($newParams['start_date']. ' + '.$years.' years + '.$months.' months + '.$days.' days + '.$hours.' hours + '.$minutes.' minutes + '.$seconds.' seconds')));
        $newParams['end_date'] = $end_date;
        
        $daoObject = new CRM_Event_DAO_Event();
        $daoObject->id = $params['parent_event_id'];
        if($daoObject->find(TRUE)){
          $newEventObject = clone($daoObject);
          unset($newEventObject->id);
          $newEventObject->start_date = $newParams['start_date'];
          $newEventObject->end_date = $newParams['end_date'];
          $newEventObject->created_date = date('YmdHis');
          $newEventObject->save();
          CRM_Core_BAO_RecurringEntity::quickAdd($daoObject->id, $newEventObject->id, 'civicrm_event');
        }
        
        //Copy Priceset
        $daoPriceSet = new CRM_Price_DAO_PriceSetEntity();
        $daoPriceSet->entity_id = $params['parent_event_id'];
        $daoPriceSet->entity_table = 'civicrm_event';
        if($daoPriceSet->find(TRUE)){
          $copyPriceSet = clone($daoPriceSet);
          $copyPriceSet->entity_id = $newEventObject->id;
          unset($copyPriceSet->id);
          $copyPriceSet->save();
        }
        
        //copy UF
        $daoUF = new CRM_Core_DAO_UFJoin();
        $daoUF->entity_id = $params['parent_event_id'];
        $daoUF->entity_table = 'civicrm_event';
        if($daoUF->find(TRUE)){
          $copyUF = clone($daoUF);
          $copyUF->entity_id = $newEventObject->id;
          unset($copyUF->id);
          $copyUF->save();
        }
        
        //copy Friend
        $daoFriend = new CRM_Friend_DAO_Friend();
        $daoFriend->entity_id = $params['parent_event_id'];
        $daoFriend->entity_table = 'civicrm_event';
        if($daoFriend->find(TRUE)){
          $copyFriend = clone($daoFriend);
          $copyFriend->entity_id = $newEventObject->id;
          unset($copyFriend->id);
          $copyFriend->save();
          CRM_Core_BAO_RecurringEntity::quickAdd($daoFriend->id, $copyFriend->id, 'civicrm_tell_friend');
        }

        //copy PCP
        $daoPCP = new CRM_PCP_DAO_PCPBlock();
        $daoPCP->entity_id = $params['parent_event_id'];
        $daoPCP->entity_table = 'civicrm_event';
        if($daoPCP->find(TRUE)){
          $copyPCP = clone($daoPCP);
          $copyPCP->entity_id = $newEventObject->id;
          unset($copyPCP->id);
          $copyPCP->save();
          CRM_Core_BAO_RecurringEntity::quickAdd($daoPCP->id, $copyPCP->id, 'civicrm_pcp_block');
        }
      }
      
      CRM_Core_BAO_RecurringEntity::quickAdd($params['parent_event_id'], $params['parent_event_id'], 'civicrm_event');
    }
    return;
  }
  
  /*
   * Get Reminder id based on event id
   */
  static public function getReminderDetailsByEventId($eventId=''){
    if(!empty($eventId)){
      $query = "
        SELECT *
        FROM   civicrm_action_schedule 
        WHERE  entity_value = '".$eventId."'";
      $dao = CRM_Core_DAO::executeQuery($query);
      $dao->fetch();
    }
    return $dao;
  }
  
  //It is a repeating event if, there exists a parent for an event
  static public function checkParentExistsForThisId($currentEventId){
    if(!empty($currentEventId)){
      $query = "
        SELECT parent_id 
        FROM civicrm_recurring_entity
        WHERE entity_id = ".$currentEventId;
      $dao = CRM_Core_DAO::executeQuery($query);
      $dao->fetch();
    }
    return $dao;
  }
  
  static public function getAllConnectedEvents($parentId=''){
    if(!empty($parentId)){
      $query = "
        SELECT GROUP_CONCAT(entity_id) as entity_id
        FROM civicrm_recurring_entity
        WHERE parent_id = ".$parentId;
      $dao = CRM_Core_DAO::executeQuery($query);
      $dao->fetch();
    }
    return $dao;
  }
  
  static public function genericSave($obj){
//    echo get_class($obj);exit;
//    echo "hieeeeeeeeeeeeeeeeeeee";
//    echo "<pre>"; print_r($obj);
    static $getConnectionId = NULL;
    if($getConnectionId == $obj->id){
      return;
    }
    if(!empty($obj->id)){
      $isRepeatingEvent = CRM_Core_Form_RecurringEntity::checkParentExistsForThisId($obj->id);
        if($isRepeatingEvent->parent_id){
          //Get all connection of this event
          $allEventIds = CRM_Core_Form_RecurringEntity::getAllConnectedEvents($isRepeatingEvent->parent_id);
          if($allEventIds->entity_id){
//            $allConnectedIds = explode(',', $allEventIds->entity_id);
//            $key = array_search($obj->id, $allConnectedIds);
//            unset($allConnectedIds[$key]);
            //For this and all events in series
//              if(!in_array($obj->id, $allConnectedIds)){
//                $allConnectedIds[] = $params['id'];
//              }
              $daoObject = new CRM_Event_DAO_Event();
                //Set Connection to avoid going in infinite loop
                echo "ConnectionId".$getConnectionId = $obj->id;
                $daoObject->id = $obj->id;
                if($daoObject->find(TRUE)){
                  unset($params['start_date']);
                  unset($params['end_date']);
                  $daoObject->save();
                }
                $allConnectedIds = array(4012, 4013);
              foreach($allConnectedIds as $key => $val){
                $daoObject = new CRM_Event_DAO_Event();
                //Set Connection to avoid going in infinite loop
                $getConnectionId = $val;
                $daoObject->id = $val;
                if($daoObject->find(TRUE)){
                  unset($params['start_date']);
                  unset($params['end_date']);
                  $daoObject->save();
                  //echo "thisid".$daoObject->id;exit;
                }
              }
          }
        }
    }
  }
  
  static function updateRecurCriterias($currentId=''){
    if(isset($currentId) && !empty($currentId)){
      $checkParentExistsForThisId = self::checkParentExistsForThisId($currentId);
      if($checkParentExistsForThisId->parent_id){
        $getAllConnections = self::getAllConnectedEvents($checkParentExistsForThisId->parent_id);
        //If there are any connections
        if($getAllConnections->entity_id){
          $listOfCurrentAndFutureEvents = self::getListOfCurrentAndFutureEvents($getAllConnections->entity_id);
        }
        //Lets delete relations for events which already happened
        if($listOfCurrentAndFutureEvents->ids){
          echo "All Connections".$getAllConnections->entity_id."<br>";
          echo "All Future Events".$listOfCurrentAndFutureEvents->ids."<br>";
          self::deleleRelationsForEventsInPast($listOfCurrentAndFutureEvents->ids);
          //Now that we have deleted past relations, we need to make the current id as parent 
          //and insert the record 
//          foreach($listOfCurrentAndFutureEvents->ids as $val){
//            $daoRecurringEntity = new CRM_Core_DAO_RecurringEntity();
//            $daoRecurringEntity->parent_id = $currentId;
//            $daoRecurringEntity->entity_id = $val;
//            $daoRecurringEntity->entity_table = 'civicrm_event';
//            $daoRecurringEntity->save();
//          }
//          if($daoRecurringEntity->id){
//            $daoRecurringEntity = new CRM_Core_DAO_RecurringEntity();
//            $daoRecurringEntity->parent_id = $currentId;
//            $daoRecurringEntity->entity_id = $currentId;
//            $daoRecurringEntity->entity_table = 'civicrm_event';
//            $daoRecurringEntity->save();
//          }
          // After creating relations lets call getRecursions to build new event list
        }
      }
    }
    return;
  }
  
  static function getListOfCurrentAndFutureEvents($ids=''){
    if(isset($ids) and !empty($ids)){
      $curDate = date('YmdHis');
      $query = "SELECT group_concat(id) as ids FROM civicrm_event 
                WHERE id IN ({$ids}) 
                AND ( end_date >= {$curDate} OR
                (
                  ( end_date IS NULL OR end_date = '' ) AND start_date >= {$curDate}
                ))";
      $dao = CRM_Core_DAO::executeQuery($query);
      $dao->fetch();
    }
    return $dao;
  }
  
  static function deleleRelationsForEventsInPast($ids=''){
    if(isset($ids) and !empty($ids)){
      $query = "DELETE FROM civicrm_recurring_entity
                WHERE entity_id IN ({$ids})";
      $dao = CRM_Core_DAO::executeQuery($query);
    }
    return; 
  }
  
}
