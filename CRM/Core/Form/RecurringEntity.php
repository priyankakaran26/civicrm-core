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
   * Function to build the form
   *
   * @return None
   * @access public
   */
  static function buildQuickForm($form) {
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
    $numericOptions = CRM_Core_SelectValues::getNumericOptions(0, 30);
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
    $form->addDateTime('repeat_event_start_date', ts('Start Date:'), FALSE, array('formatType' => 'activityDateTime'));
    $eoptionTypes = array('1' => ts('After'),
        '2' => ts('On'),
      );
    $form->addRadio('ends', ts("Ends:"), $eoptionTypes, array(), NULL, FALSE);
    $form->add('text', 'start_action_offset', ts('Occurrences'),
      array(
        'size' => 45,
        'maxlength' => 128
      )
    );
    $form->addFormRule(array('CRM_Core_Form_RecurringEntity', 'formRule'));
    $form->addDate('repeat_absolute_date', ts('On'), FALSE);
    $form->addDate('exclude_date', ts('Exclude Date(s)'), FALSE);
    $form->addElement('select', 'exclude_date_list', ts(''), array(), array('style' => 'width:200px;', 'multiple' => 'multiple'));
    $form->addElement('button','add_to_exclude_list','>>','onClick="addToExcludeList(document.getElementById(\'exclude_date\').value);"'); 
    $form->addElement('button','remove_from_exclude_list', '<<', 'onClick="removeFromExcludeList(\'exclude_date_list\')"'); 
    $form->addButtons(array(
        array(
          'type' => 'submit',
          'name' => ts('Save'),
          'isDefault' => TRUE
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
      return $errors = array();
  }

  /**
   * Function to process the form
   *
   * @access public
   *
   * @return None
   */
  static function postProcess($params, $type='') {
    $build_rule = '';
    $params['used_for'] = 'event';
    
    //Building recursion rules
    if(CRM_Utils_Array::value('repetition_frequency_unit', $params)){
      $repetition_frequency_unit = $params['repetition_frequency_unit'];
      if($repetition_frequency_unit == 'day'){
        $repetition_frequency_unit = 'dai';
      }
      $build_rule = 'FREQ='.strtoupper($repetition_frequency_unit.'ly;');
    }
    if(CRM_Utils_Array::value('repetition_frequency_interval', $params)){
      $build_rule .= 'INTERVAL='.$params['repetition_frequency_interval'].';';
    }
    if(CRM_Utils_Array::value('limit_to', $params)){
      $build_rule .= 'BYMONTHDAY='.$params['limit_to'];
    }
    $repeats_on = CRM_Utils_Array::value('start_action_condition', $params);
    if(!empty($repeats_on)){
        $params['start_action_condition'] = implode(",", array_keys($repeats_on));
    }
    $repeatsByDayOfWeek_1 = CRM_Utils_Array::value('start_action_date_1', $params);
    $repeatsByDayOfWeek_2 = CRM_Utils_Array::value('start_action_date_2', $params);
    if($repeatsByDayOfWeek_1 && $repeatsByDayOfWeek_2){
        $params['start_action_date'] = $repeatsByDayOfWeek_1 + " " + $repeatsByDayOfWeek_2;
    }
//    if($params['ends'] == 1){
//      $params['start_action_offset'] = $params['start_action_offset'];
//    }else{
//      $params['start_action_offset'] = '';
//    }
//    if($params['ends'] == 2){
//      $params['absolute_date'] = $params['repeat_absolute_date'];
//    }else{
//      $params['repeat_absolute_date'] = '';
//    }
    if(CRM_Utils_Array::value('exclude_date_list', $params)){
      //$groupParams = array('name' => 'event_repeat_exclude_dates_'.$params['id']);
     // $optionValue = CRM_Core_OptionValue::addOptionValue($params['exclude_date_list'], $groupParams, $action);
    }
    unset($params['id']);
    
    CRM_Core_BAO_ActionSchedule::add($params);
    //$start = new DateTime('2015-03-08');
    $start = new DateTime('2015-03-08 07:00:00.000000');
    $r = new When();
    $r->recur($start)->rrule('FREQ=MONTHLY;INTERVAL=1;COUNT=4;BYMONTHDAY=1');

    while($result = $r->next()){
      //echo $result->format('YmdHis'). '<br />';
      //TO DO: Get parent event start date and end date
      $eparams['start_date'] = CRM_Utils_Date::processDate($result->format('YmdHis'));
      $parentStartDate = strtotime("2015-03-08 07:00:00.000000");
      $parentEndDate = strtotime("2015-03-11 17:00:00.000000");
      $subTime = $parentEndDate - $parentStartDate;
      $difference = ($subTime/(60*60*24))%365;
      $end_date = CRM_Utils_Date::processDate(date('YmdHis', strtotime($eparams['start_date']. ' + '.$difference.' days')));
      $eparams['end_date'] = $end_date;
      //echo "<pre>";print_r($eparams);
      $daoEvent = new CRM_Event_DAO_Event();
      $daoEvent->id = 3;
      $daoEvent->find(TRUE);
      $copyEvent = clone($daoEvent);
      unset($copyEvent->id);
      $copyEvent->start_date = $eparams['start_date'];
      $copyEvent->end_date = $eparams['end_date'];
      $copyEvent->save();
      echo "<pre>"; print_r($copy_dao);
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

}