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
 *
 * Generated from xml/schema/CRM/Core/ActionMapping.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 */

namespace Civi\Core;

require_once 'Civi/Core/Entity.php';

use Doctrine\ORM\Mapping as ORM;
use Civi\API\Annotation as CiviAPI;
use JMS\Serializer\Annotation as JMS;

/**
 * ActionMapping
 *
 * @CiviAPI\Entity("ActionMapping")
 * @CiviAPI\Permission()
 * @ORM\Table(name="civicrm_action_mapping")
 * @ORM\Entity
 *
 */
class ActionMapping extends \Civi\Core\Entity {

  /**
   * @var integer
   *
   * @JMS\Type("integer")
   * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned":true} )
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;
    
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="entity", type="string", length=64, nullable=true)
   * 
   */
  private $entity;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="entity_value", type="string", length=64, nullable=true)
   * 
   */
  private $entityValue;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="entity_value_label", type="string", length=64, nullable=true)
   * 
   */
  private $entityValueLabel;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="entity_status", type="string", length=64, nullable=true)
   * 
   */
  private $entityStatus;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="entity_status_label", type="string", length=64, nullable=true)
   * 
   */
  private $entityStatusLabel;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="entity_date_start", type="string", length=64, nullable=true)
   * 
   */
  private $entityDateStart;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="entity_date_end", type="string", length=64, nullable=true)
   * 
   */
  private $entityDateEnd;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="entity_recipient", type="string", length=64, nullable=true)
   * 
   */
  private $entityRecipient;

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }
    
  /**
   * Set entity
   *
   * @param string $entity
   * @return ActionMapping
   */
  public function setEntity($entity) {
    $this->entity = $entity;
    return $this;
  }

  /**
   * Get entity
   *
   * @return string
   */
  public function getEntity() {
    return $this->entity;
  }
  
  /**
   * Set entityValue
   *
   * @param string $entityValue
   * @return ActionMapping
   */
  public function setEntityValue($entityValue) {
    $this->entityValue = $entityValue;
    return $this;
  }

  /**
   * Get entityValue
   *
   * @return string
   */
  public function getEntityValue() {
    return $this->entityValue;
  }
  
  /**
   * Set entityValueLabel
   *
   * @param string $entityValueLabel
   * @return ActionMapping
   */
  public function setEntityValueLabel($entityValueLabel) {
    $this->entityValueLabel = $entityValueLabel;
    return $this;
  }

  /**
   * Get entityValueLabel
   *
   * @return string
   */
  public function getEntityValueLabel() {
    return $this->entityValueLabel;
  }
  
  /**
   * Set entityStatus
   *
   * @param string $entityStatus
   * @return ActionMapping
   */
  public function setEntityStatus($entityStatus) {
    $this->entityStatus = $entityStatus;
    return $this;
  }

  /**
   * Get entityStatus
   *
   * @return string
   */
  public function getEntityStatus() {
    return $this->entityStatus;
  }
  
  /**
   * Set entityStatusLabel
   *
   * @param string $entityStatusLabel
   * @return ActionMapping
   */
  public function setEntityStatusLabel($entityStatusLabel) {
    $this->entityStatusLabel = $entityStatusLabel;
    return $this;
  }

  /**
   * Get entityStatusLabel
   *
   * @return string
   */
  public function getEntityStatusLabel() {
    return $this->entityStatusLabel;
  }
  
  /**
   * Set entityDateStart
   *
   * @param string $entityDateStart
   * @return ActionMapping
   */
  public function setEntityDateStart($entityDateStart) {
    $this->entityDateStart = $entityDateStart;
    return $this;
  }

  /**
   * Get entityDateStart
   *
   * @return string
   */
  public function getEntityDateStart() {
    return $this->entityDateStart;
  }
  
  /**
   * Set entityDateEnd
   *
   * @param string $entityDateEnd
   * @return ActionMapping
   */
  public function setEntityDateEnd($entityDateEnd) {
    $this->entityDateEnd = $entityDateEnd;
    return $this;
  }

  /**
   * Get entityDateEnd
   *
   * @return string
   */
  public function getEntityDateEnd() {
    return $this->entityDateEnd;
  }
  
  /**
   * Set entityRecipient
   *
   * @param string $entityRecipient
   * @return ActionMapping
   */
  public function setEntityRecipient($entityRecipient) {
    $this->entityRecipient = $entityRecipient;
    return $this;
  }

  /**
   * Get entityRecipient
   *
   * @return string
   */
  public function getEntityRecipient() {
    return $this->entityRecipient;
  }

  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  public static $_fields = NULL;

  static function &fields( ) {
    if ( !self::$_fields) {
      self::$_fields = array (
      
              'id' => array(
      
        'name' => 'id',
        'propertyName' => 'id',
        'type' => \CRM_Utils_Type::T_INT,
                        'required' => true,
                                                     
                                    
                          ),
      
              'entity' => array(
      
        'name' => 'entity',
        'propertyName' => 'entity',
        'type' => \CRM_Utils_Type::T_STRING,
                'title' => ts('Entity'),
                                 'maxlength' => 64,
                                 'size' => \CRM_Utils_Type::BIG,
                           
                                    
                          ),
      
              'entity_value' => array(
      
        'name' => 'entity_value',
        'propertyName' => 'entityValue',
        'type' => \CRM_Utils_Type::T_STRING,
                'title' => ts('Entity Value'),
                                 'maxlength' => 64,
                                 'size' => \CRM_Utils_Type::BIG,
                           
                                    
                          ),
      
              'entity_value_label' => array(
      
        'name' => 'entity_value_label',
        'propertyName' => 'entityValueLabel',
        'type' => \CRM_Utils_Type::T_STRING,
                'title' => ts('Entity Value Label'),
                                 'maxlength' => 64,
                                 'size' => \CRM_Utils_Type::BIG,
                           
                                    
                          ),
      
              'entity_status' => array(
      
        'name' => 'entity_status',
        'propertyName' => 'entityStatus',
        'type' => \CRM_Utils_Type::T_STRING,
                'title' => ts('Entity Status'),
                                 'maxlength' => 64,
                                 'size' => \CRM_Utils_Type::BIG,
                           
                                    
                          ),
      
              'entity_status_label' => array(
      
        'name' => 'entity_status_label',
        'propertyName' => 'entityStatusLabel',
        'type' => \CRM_Utils_Type::T_STRING,
                'title' => ts('Entity Status Label'),
                                 'maxlength' => 64,
                                 'size' => \CRM_Utils_Type::BIG,
                           
                                    
                          ),
      
              'entity_date_start' => array(
      
        'name' => 'entity_date_start',
        'propertyName' => 'entityDateStart',
        'type' => \CRM_Utils_Type::T_STRING,
                'title' => ts('Entity Date Start'),
                                 'maxlength' => 64,
                                 'size' => \CRM_Utils_Type::BIG,
                           
                                    
                          ),
      
              'entity_date_end' => array(
      
        'name' => 'entity_date_end',
        'propertyName' => 'entityDateEnd',
        'type' => \CRM_Utils_Type::T_STRING,
                'title' => ts('Entity Date End'),
                                 'maxlength' => 64,
                                 'size' => \CRM_Utils_Type::BIG,
                           
                                    
                          ),
      
              'entity_recipient' => array(
      
        'name' => 'entity_recipient',
        'propertyName' => 'entityRecipient',
        'type' => \CRM_Utils_Type::T_STRING,
                'title' => ts('Entity Recipient'),
                                 'maxlength' => 64,
                                 'size' => \CRM_Utils_Type::BIG,
                           
                                    
                          ),
             );
    }
    return self::$_fields;
  }

}
