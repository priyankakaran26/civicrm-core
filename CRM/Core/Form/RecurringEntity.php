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
  static function postProcess($params, $type='') {
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
        $buildRule .= 'BYDAY='.strtoupper(implode(',', $buildRuleArray)).';';
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

    //unset($params['id']);
//    echo "<pre>"; print_r($params);exit;
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
    //$start = new DateTime('2015-03-08');
    $start = new DateTime('2014-08-24 07:00:00.000000');
    $r = new When();
    
    //Recursion rule in action
    $eparams = array();
    if(!empty($buildRule)){
      //echo $buildRule;
      $r->recur($start)->rrule("'".$buildRule."'");
      while($result = $r->next()){
        //echo $result->format('YmdHis'). '<br />';
        //TO DO: Get parent event start date and end date
        $eparams['start_date'] = CRM_Utils_Date::processDate($result->format('YmdHis'));
        $parentStartDate = strtotime($params['parent_event_start_date']);
        $parentEndDate = strtotime($params['parent_event_end_date']);
        $diff = abs($parentEndDate - $parentStartDate);
        //$difference = ($subTime/(60*60*24))%365;exit;
        $years   = floor($diff / (365*60*60*24)); 
        $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
        $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); 
        $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60));
        $minutes  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
        $end_date = CRM_Utils_Date::processDate(date('YmdHis', strtotime($eparams['start_date']. ' + '.$years.' years + '.$months.' months + '.$days.' days + '.$hours.' hours + '.$minutes.' minutes + '.$seconds.' seconds')));
        $eparams['end_date'] = $end_date;
        //echo "<pre>";print_r($eparams);
        $daoObject = new CRM_Event_DAO_Event();
        $daoObject->id = $params['parent_event_id'];
        if($daoObject->find(TRUE)){
          $newEventObject = clone($daoObject);
          unset($newEventObject->id);
          $newEventObject->start_date = $eparams['start_date'];
          $newEventObject->end_date = $eparams['end_date'];
          $newEventObject->save();
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
        }
        
        //echo "<pre>"; print_r($copyObject);
        // Insert in civicrm_recurring_entity table to maintain relation
        $daoRecurringEntity = new CRM_Core_DAO_RecurringEntity();
        $daoRecurringEntity->parent_id = $params['parent_event_id'];
        $daoRecurringEntity->entity_id = $newEventObject->id;
        $daoRecurringEntity->entity_table = 'civicrm_event';
        $daoRecurringEntity->save();
        //CRM_Core_Error::debug_log_message("My event recursion");
      }
      //Check if there were any connections made
      if($daoRecurringEntity->id){
        $daoRecurringEntity = new CRM_Core_DAO_RecurringEntity();
        $daoRecurringEntity->parent_id = $params['parent_event_id'];
        $daoRecurringEntity->entity_id = $params['parent_event_id'];
        $daoRecurringEntity->entity_table = 'civicrm_event';
        $daoRecurringEntity->save();
      }
    }
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
}