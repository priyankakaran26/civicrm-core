{*
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
*}

<div class="crm-block crm-form-block crm-core-form-recurringentity-block">
  {assign var=eventID value=$id}
    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="top"}
    </div>
    
  <table class="form-layout-compressed">
    <tr class="crm-core-form-recurringentity-block-repetition_frequency_unit">
      <td class="label">{$form.repetition_frequency_unit.label}</td>
      <td>{$form.repetition_frequency_unit.html}</td>
    </tr>
    <tr class="crm-core-form-recurringentity-block-repetition_frequency_interval">
      <td class="label">{$form.repetition_frequency_interval.label}</td>
      <td>{$form.repetition_frequency_interval.html} &nbsp;<span id="repeats-every-text">hour(s)</span>
      </td>
    </tr>
    <tr class="crm-core-form-recurringentity-block-start_action_condition">
      <td class="label">
          <label for="repeats_on">{$form.start_action_condition.label}:</label>
      </td>
      <td>
          {$form.start_action_condition.html}
      </td>
    </tr>
    <tr class="crm-core-form-recurringentity-block-repeats_by">
      <td class="label">{$form.repeats_by.label}</td>
      <td>{$form.repeats_by.1.html}&nbsp;&nbsp;{$form.limit_to.html}
      </td>
    </tr>
    <tr class="crm-core-form-recurringentity-block-repeats_by">
      <td class="label"></td>
      <td>{$form.repeats_by.2.html}&nbsp;&nbsp;{$form.start_action_date_1.html}&nbsp;&nbsp;{$form.start_action_date_2.html}
      </td>
    </tr>
    <tr class="crm-core-form-recurringentity-block-event_start_date">
      <td class="label">{$form.event_start_date.label}</td>
      <td>{include file="CRM/common/jcalendar.tpl" elementName=event_start_date}</td>
    </tr>
    <tr class="crm-core-form-recurringentity-block-ends">
      <td class="label">{$form.ends.label}</td>
      <td>{$form.ends.1.html}&nbsp;{$form.start_action_offset.html}</td>
    </tr>
    <tr class="crm-core-form-recurringentity-block-absolute_date">
        <td class="label"></td>
        <td>{$form.ends.2.html}{$form.event_absolute_date.label}&nbsp;{include file="CRM/common/jcalendar.tpl" elementName=absolute_date}
        </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}</div>
  </div>
</div>


