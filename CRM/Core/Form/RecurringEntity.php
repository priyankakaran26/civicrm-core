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

/**
 * This class generates form components for processing Event
 *
 */
class CRM_Core_Form_RecurringEntity extends CRM_Core_Form {

 
  function preProcess() {
    
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
   
  }

  /**
   * Function to build the form
   *
   * @return None
   * @access public
   */
  public function buildQuickForm() {
    
    //need to assign custom data type and subtype to the template
//    $this->assign('entityId', $this->_id);
    $attributes_schedule = CRM_Core_DAO::getAttribute('CRM_Core_DAO_ActionMapping');

    $this->_freqUnits = array('hour' => 'hour') + CRM_Core_OptionGroup::values('recur_frequency_units');
    foreach ($this->_freqUnits as $val => $label) {
      $freqUnitsDisplay[$val] = ts('%1(s)', array(1 => $label));
    }
    //echo "<pre>";print_r($freqUnitsDisplay);
    $this->add('select', 'repetition_frequency_unit', ts('Repeats:'), $freqUnitsDisplay, TRUE);
    $numericOptions = CRM_Core_SelectValues::getNumericOptions(0, 30);
    $this->add('select', 'repetition_frequency_interval', ts('Repeats every:'), $numericOptions, TRUE);
    $this->add('checkbox', 'start_action_condition_mon', ts('Mon'));
    $this->add('checkbox', 'start_action_condition_tue', ts('Tue'));
    $this->add('checkbox', 'start_action_condition_wed', ts('Wed'));
    $this->add('checkbox', 'start_action_condition_thu', ts('Thu'));
    $this->add('checkbox', 'start_action_condition_fri', ts('Fri'));
    $this->add('checkbox', 'start_action_condition_sat', ts('Sat'));
    $this->add('checkbox', 'start_action_condition_sun', ts('Sun'));
    $roptionTypes = array('1' => ts('day of the month'),
        '2' => ts('day of the week'),
      );
    $this->addRadio('repeats_by', ts("Repeats By:"), $roptionTypes, array(), NULL);
    $this->add('text', 'limit_to', '', array('maxlength' => 2, 'size' => 10));
    $day_of_the_week_1 = array('first'  => 'First',
                                'second'=> 'Second',
                                'third' => 'Third',
                                'fourth'=> 'Fourth',
                                'last'  => 'Last'
                         );
    $this->add('select', 'start_action_date_1', ts(''), $day_of_the_week_1);
    $day_of_the_week_2 = array('monday'     => 'Monday',
                                'tuesday'   => 'Tuesday',
                                'wednesday' => 'Wednesday',
                                'thursday'  => 'Thursday',
                                'friday'    => 'Friday',
                                'saturday'  => 'Saturday',
                                'sunday'    => 'Sunday',
                         );
    $this->add('select', 'start_action_date_2', ts(''), $day_of_the_week_2);
    $this->addDateTime('event_start_date', ts('Start Date:'), TRUE, array('formatType' => 'activityDateTime'));
    $eoptionTypes = array('1' => ts('After'),
        '2' => ts('On'),
      );
    $this->addRadio('ends', ts("Ends:"), $eoptionTypes, array(), NULL, TRUE);
    $this->add('text', 'start_action_offset', ts('Occurrences'),
      array(
        'size' => 45,
        'maxlength' => 128
      ), TRUE
    );
    $this->addFormRule(array('CRM_Core_Form_RecurringEntity', 'formRule'));
    $this->addDate('absolute_date', ts('On'), FALSE);
    $this->addButtons(array(
        array(
          'type' => 'submit',
          'name' => ts('Save'),
          'isDefault' => TRUE,
        ),
        array(
          'type' => 'cancel',
          'name' => ts('Cancel'),
        ),
      )
    );

    parent::buildQuickForm();
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
  public function postProcess() {
      $params = $this->controller->exportValues($this->_name);
      $params['used_for'] = 'event';
      echo "<pre>"; print_r($params);
      CRM_Core_BAO_ActionSchedule::add($params);
      
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