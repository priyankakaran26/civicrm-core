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
  <div id="dialog" style="display:none">
      Would you like to change this event only, or this and following events in series?<br/><br/>
      <div style="display: inline-block">
          <div style="display:inline-block;width:100%;">
              <div style="width:30%;float:left;">
                  <button class="dialog-button only-this-event">Only this Event</button>
              </div>
              <div style="width:70%;float:left;">All other events in the series will remain same</div></div>
          <div style="display:inline-block;width:100%;">
              <div style="width:30%;float:left;">
                  <button class="dialog-button all-following-event">This and Following Events</button>
              </div>
              <div style="width:70%;float:left;">This and all the following events will be changed</div>
          </div>
      </div>
  </div>
  <input type="hidden" value="" name="isRepeatingEvent" id="is-repeating-event"/>
{literal}
    <style type="text/css">
        .dialog-button{
          background: #f5f5f5;
          background-image: -webkit-linear-gradient(top,#f5f5f5,#f1f1f1);
          border: 1px solid rgba(0,0,0,0.1);
          padding: 5px 8px;
          text-align: center;
          border-radius: 2px;
          cursor: pointer;  
          font-size: 11px !important;
        }
    </style>
{/literal}
{*{foreach from=$form.buttons item=button key=key name=btns}
    {if $key|substring:0:4 EQ '_qf_'}
    {/if}
{/foreach}*}
{if $isRepeat eq 'repeat'}
    {literal}
        <script type="text/javascript">
        cj(document).ready(function() {
            //alert("hieeeee");
           /* cj("#dialog").dialog({ autoOpen: false });
            cj("#_qf_EventInfo_upload-top, #_qf_EventInfo_upload_done-top, #_qf_EventInfo_upload-bottom, #_qf_EventInfo_upload_done-bottom, #_qf_Location_upload-top, _qf_Location_upload_done-top, #_qf_Location_upload-bottom, #_qf_Location_upload_done-bottom, #_qf_Fee_upload-top, #_qf_Fee_upload_done-top, #_qf_Fee_upload-bottom, #_qf_Fee_upload_done-bottom, #_qf_Registration_upload-top, #_qf_Registration_upload_done-top, #_qf_Registration_upload-bottom, #_qf_Registration_upload_done-bottom, #_qf_ScheduleReminders_upload-top, #_qf_ScheduleReminders_upload_done-top, #_qf_ScheduleReminders_upload-bottom, #_qf_ScheduleReminders_upload_done-bottom, #_qf_Event_upload-top, #_qf_Event_upload_done-top, #_qf_Event_upload-bottom, #_qf_Event_upload_done-bottom, #_qf_Repeat_submit-top, #_qf_Repeat_submit-bottom").click(
                function () {
                    cj("#dialog").dialog('open');
                    cj("#dialog").dialog({
                        title: 'Save recurring event',
                        width: '650',
                        position: 'center',
                        //draggable: false,
                        buttons: {
                            Cancel: function() { //cancel
                                cj( this ).dialog( "close" );
                            }
                        }
                    });
                    return false;
                }
            );*/
            cj(".all-following-event").click(function(){
                cj("#dialog").dialog('close');
                cj("form").submit();
            });
            cj(".only-this-event").click(function(){
                cj("#is-repeating-event").val({/literal}{$eventID}{literal});
                cj("#dialog").dialog('close');
                cj("form").submit();
    /*            var ajaxurl = CRM.url("civicrm/event/manage/settings");
                cj.ajax({
                  data: "reset=1&action=update&id="+ {/literal}{$eventID}{literal} +"&isrepeat=1",
                  url:  ajaxurl,
                  success: function (data) {
                      cj("#dialog").dialog('close');
                      alert("Changes Saved");
                  }
                });*/
            });
        });
        </script>
    {/literal}
{/if}
