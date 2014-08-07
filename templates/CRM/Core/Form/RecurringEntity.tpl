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
{literal}
<style type="text/css">
    #start_action_offset{
        width: 40px;
    }
    #limit_to{
        width:40px;
    }
    /*input[disabled="disabled"], select[disabled="disabled"]{
        background-color: #EBEBE4 !important;
    }*/
</style>
<script type="text/javascript">
  CRM.$(function($) {
    $('#repetition_frequency_unit').change(function () {
        if($(this).val()==='hour'){
            $('#repeats-every-text').html($(this).val()+'(s)');
            $('.crm-core-form-recurringentity-block-start_action_condition').hide();
            $('.crm-core-form-recurringentity-block-repeats_by td').hide();
        }else if($(this).val()==='day'){
            $('#repeats-every-text').html($(this).val()+'(s)');
            $('.crm-core-form-recurringentity-block-start_action_condition').hide();
            $('.crm-core-form-recurringentity-block-repeats_by td').hide();
        }else if($(this).val()==='week'){
            $('#repeats-every-text').html($(this).val()+'(s)');
            //Show "Repeats On" block when week is selected 
            $('.crm-core-form-recurringentity-block-start_action_condition').show();
            $('.crm-core-form-recurringentity-block-repeats_by td').hide();
        }else if($(this).val()==='month'){
            $('#repeats-every-text').html($(this).val()+'(s)');
            $('.crm-core-form-recurringentity-block-start_action_condition').hide();
            //Show "Repeats By" block when month is selected 
            $('.crm-core-form-recurringentity-block-repeats_by td').show();
        }else if($(this).val()==='year'){
            $('#repeats-every-text').html($(this).val()+'(s)');
            $('.crm-core-form-recurringentity-block-start_action_condition').hide();
            $('.crm-core-form-recurringentity-block-repeats_by td').hide();
        }
    });
    //var test = $("input:radio[name=repeats_by]:checked").val();
    //alert(test);
    /*$('input:radio[name=repeats_by]').click(function() {
        if($(this).val() == 1){
            $('#limit_to').show();
        }
        if($(this).val() == 2){
            $('#start_action_date_1').show();
            $('#start_action_date_2').show();
        }
    });*/
     /*$('#limit_to').hide();
     $('#start_action_date_1').hide();
     $('#start_action_date_2').hide();*/
        
    // For "Ends" block
    $('input:radio[name=ends]').click(function() {
        if($(this).val() == 1){
            $('#start_action_offset').removeAttr('disabled').attr('enabled','enabled');
        }else{
            $('#start_action_offset').removeAttr('enabled').attr('disabled','disabled');
        }
        if($(this).val() == 2){
            $('#absolute_date_display').removeAttr('disabled').attr('enabled','enabled');
        }else{
            $('#absolute_date_display').removeAttr('enabled').attr('disabled','disabled');
        }
    });
    $('#start_action_offset').attr('disabled','disabled');
    $('#absolute_date_display').attr('disabled','disabled');
    
    //For "Repeats By" block
    $('input:radio[name=repeats_by]').click(function() {
        if($(this).val() == 1){
            $('#limit_to').removeAttr('disabled').attr('enabled','enabled');
        }else{
            $('#limit_to').removeAttr('enabled').attr('disabled','disabled');
        }
        if($(this).val() == 2){
            $('#start_action_date_1').removeAttr('disabled').attr('enabled','enabled');
            $('#start_action_date_2').removeAttr('disabled').attr('enabled','enabled');
        }else{
            $('#start_action_date_1').removeAttr('enabled').attr('disabled','disabled');
            $('#start_action_date_2').removeAttr('enabled').attr('disabled','disabled');
        }
    });
    $('#limit_to').attr('disabled','disabled');
    $('#start_action_date_1').attr('disabled','disabled');
    $('#start_action_date_2').attr('disabled','disabled');
    
    /****** On load "Repeats By" and "Repeats On" blocks should be hidden ******/
    $('.crm-core-form-recurringentity-block-start_action_condition').hide();
    $('.crm-core-form-recurringentity-block-repeats_by td').hide();
    
  });
</script>
{/literal}


