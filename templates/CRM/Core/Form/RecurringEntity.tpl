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

<div class="crm-block crm-form-block crm-core-form-recurringentity-block crm-accordion-wrapper">
    <div class="crm-accordion-header">Repeat Configuration</div>
    <div class="crm-accordion-body">
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
          {*<tr class="crm-core-form-recurringentity-block-event_start_date">
            <td class="label">{$form.repeat_event_start_date.label}</td>
            <td>{include file="CRM/common/jcalendar.tpl" elementName=repeat_event_start_date}</td>
          </tr>*}
          <tr class="crm-core-form-recurringentity-block-ends">
            <td class="label">{$form.ends.label}</td>
            <td>{$form.ends.1.html}&nbsp;{$form.start_action_offset.html}&nbsp;Occurrences</td>
          </tr>
          <tr class="crm-core-form-recurringentity-block-absolute_date">
              <td class="label"></td>
              <td>{$form.ends.2.html}&nbsp;{include file="CRM/common/jcalendar.tpl" elementName=repeat_absolute_date}
              </td>
          </tr>
          <tr class="crm-core-form-recurringentity-block-exclude_date">
              <td class="label">{$form.exclude_date.label}</td>
              <td>&nbsp;{include file="CRM/common/jcalendar.tpl" elementName=exclude_date}
                  &nbsp;{$form.add_to_exclude_list.html}&nbsp;{$form.remove_from_exclude_list.html}
                  {$form.exclude_date_list.html}
              </td>
          </tr>

          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table>
      <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
      </div>
    </div>
</div>
{literal}
<style type="text/css">
    .highlight-record{
        font-weight:  bold !important;
        background-color: #FFFFCC !important;
    }
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
  cj(document).ready(function() {
    /****** On load "Repeats By" and "Repeats On" blocks should be hidden if dropdown value is not week or month****** (Edit Mode)***/
    if(cj('#repetition_frequency_unit').val() == "week"){
        cj('.crm-core-form-recurringentity-block-start_action_condition').show();
        cj('.crm-core-form-recurringentity-block-repeats_by td').hide();
    }else if(cj('#repetition_frequency_unit').val() == "month"){
        cj('.crm-core-form-recurringentity-block-repeats_by td').show();
        cj('.crm-core-form-recurringentity-block-start_action_condition').hide();
    }else{
        cj('.crm-core-form-recurringentity-block-start_action_condition').hide();
        cj('.crm-core-form-recurringentity-block-repeats_by td').hide();
    }
    cj("#repeats-every-text").html(cj('#repetition_frequency_unit').val()+'(s)');
    
    /***********On Load Set Ends Value (Edit Mode) **********/
    if(cj('input:radio[name=ends]:checked').val() == 1){
        cj('#start_action_offset').removeAttr('disabled').attr('enabled','enabled');
        cj('#repeat_absolute_date_display').val('');
        //alert("1");
    }else if(cj('input:radio[name=ends]:checked').val() == 2){
        cj('#repeat_absolute_date_display').removeAttr("disabled").attr('enabled','enabled');
        cj('#start_action_offset').val('');
        //alert("2s");
    }else{
        cj('#start_action_offset').removeAttr('enabled').attr('disabled','disabled');
        cj('#repeat_absolute_date_display').removeAttr('enabled').attr('disabled','disabled');
    }
        
    
    cj('#repetition_frequency_unit').change(function () {
        if(cj(this).val()==='hour'){
            cj('#repeats-every-text').html(cj(this).val()+'(s)');
            cj('.crm-core-form-recurringentity-block-start_action_condition').hide();
            cj('.crm-core-form-recurringentity-block-repeats_by td').hide();
        }else if(cj(this).val()==='day'){
            cj('#repeats-every-text').html(cj(this).val()+'(s)');
            cj('.crm-core-form-recurringentity-block-start_action_condition').hide();
            cj('.crm-core-form-recurringentity-block-repeats_by td').hide();
        }else if(cj(this).val()==='week'){
            cj('#repeats-every-text').html(cj(this).val()+'(s)');
            //Show "Repeats On" block when week is selected 
            cj('.crm-core-form-recurringentity-block-start_action_condition').show();
            cj('.crm-core-form-recurringentity-block-repeats_by td').hide();
        }else if(cj(this).val()==='month'){
            cj('#repeats-every-text').html(cj(this).val()+'(s)');
            cj('.crm-core-form-recurringentity-block-start_action_condition').hide();
            //Show "Repeats By" block when month is selected 
            cj('.crm-core-form-recurringentity-block-repeats_by td').show();
        }else if(cj(this).val()==='year'){
            cj('#repeats-every-text').html(cj(this).val()+'(s)');
            cj('.crm-core-form-recurringentity-block-start_action_condition').hide();
            cj('.crm-core-form-recurringentity-block-repeats_by td').hide();
        }
    });
    
    // For "Ends" block
    cj('input:radio[name=ends]').click(function() {
        if(cj(this).val() == 1){
            cj('#start_action_offset').removeAttr('disabled').attr('enabled','enabled');
            cj('#repeat_absolute_date_display').val('');
        }else{
            cj('#start_action_offset').removeAttr('enabled').attr('disabled','disabled');
        }
        if(cj(this).val() == 2){
            cj('#repeat_absolute_date_display').removeAttr('disabled').attr('enabled','enabled');
            cj('#start_action_offset').val('');
        }else{
            cj('#repeat_absolute_date_display').removeAttr('enabled').attr('disabled','disabled');
        }
    });
    //cj('#start_action_offset').attr('disabled','disabled');
    //cj('#repeat_absolute_date_display').attr('disabled','disabled');
    
    //For "Repeats By" block
    cj('input:radio[name=repeats_by]').click(function() {
        if(cj(this).val() == 1){
            cj('#limit_to').removeAttr('disabled').attr('enabled','enabled');
        }else{
            cj('#limit_to').removeAttr('enabled').attr('disabled','disabled');
        }
        if(cj(this).val() == 2){
            cj('#start_action_date_1').removeAttr('disabled').attr('enabled','enabled');
            cj('#start_action_date_2').removeAttr('disabled').attr('enabled','enabled');
        }else{
            cj('#start_action_date_1').removeAttr('enabled').attr('disabled','disabled');
            cj('#start_action_date_2').removeAttr('enabled').attr('disabled','disabled');
        }
    });
    cj('#limit_to').attr('disabled','disabled');
    cj('#start_action_date_1').attr('disabled','disabled');
    cj('#start_action_date_2').attr('disabled','disabled');
        
    //Select all options in listbox before submitting
    cj(this).submit(function() {
        cj("#exclude_date_list option").attr("selected",true);
        });
    });
    
    //Exclude list function
    function addToExcludeList(val) {
        if(val !== ""){
            cj('#exclude_date_list').append('<option>'+val+'</option>');
        }
    }
    
    function removeFromExcludeList(sourceID) {
        var src = document.getElementById(sourceID);
        for(var count= src.options.length-1; count >= 0; count--) {
            if(src.options[count].selected == true) {
                    try{
                        src.remove(count, null);
                    }catch(error){
                        src.remove(count);
                    }
            }
        }
    }
</script>
{/literal}  