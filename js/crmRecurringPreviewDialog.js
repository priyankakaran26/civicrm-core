/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function ($, _, undefined) {
  $.fn.crmRecurringPreviewDialog = function ( options ) {
    if (!options.entityID || !options.entityTable) {
      CRM.console('error', 'Error: describe error');
      return false;
    }
      $(this).click( function (e) {
      e.preventDefault();
      $('#exclude_date_list option').attr('selected',true);
      //Copy exclude dates
      var dateTxt=[];
      $('#exclude_date_list option:selected').each(function() {
          dateTxt.push($(this).text());
      });
      var completeDateText = dateTxt.join(',');
      $('#copyExcludeDates').val(completeDateText);

      $('#generated_dates').html('').html('<div class="crm-loading-element"><span class="loading-text">Just a moment, generating dates...</span></div>');
      //$('#preview-dialog').dialog('open');
      $('#preview-dialog').dialog({
        title: 'Confirm event dates',
        width: '650',
        position: 'center',
        //draggable: false,
        buttons: {
          Ok: function() {
              $(this).dialog( "close" );
              $('form#Repeat, form#Activity').submit();
          },
          Cancel: function() { //cancel
              $(this).dialog( "close" );
          }
        }
      });
      var ajaxurl = CRM.url("civicrm/ajax/recurringentity/generate-preview");
      var entityID = parseInt(options.entityID);
      var entityTable = options.entityTable;
      if (entityID != "" && entityTable != "") {
          ajaxurl += "?entity_id="+entityID+"&entity_table="+entityTable;
      }
      var formData = $('form').serializeArray();
      $.ajax({
        dataType: "json",
        type: "POST",
        data: formData,
        url:  ajaxurl,
        success: function (result) {
          if (Object.keys(result).length > 0) {
            var errors = [];
            var participantData = [];
            var html = 'Based on your repeat configuration here is the list of event dates, Do you wish to proceed creating events for these dates?<br/><table id="options" class="display"><thead><tr><th>Sr No</th><th>Start date</th><th id="th-end-date">End date</th></tr><thead>';
            var count = 1;
            for(var i in result) {
              if (i != 'errors') {
                if (i == 'participantData') {
                  participantData = result.participantData;
                  break;
                }
                var start_date = result[i].start_date;
                var end_date = result[i].end_date;

                var end_date_text = '';
                if (end_date !== undefined) {
                  end_date_text = '<td>'+end_date+'</td>';
                }
                html += '<tr><td>'+count+'</td><td>'+start_date+'</td>'+end_date_text+'</tr>';
                count = count + 1;
              } else {
                errors = result.errors;
              }
            }
            html += '</table>';
            var warningHtml = '';
            if (Object.keys(participantData).length > 0) {               
              warningHtml += '<div class="messages status no-popup"><div class="icon inform-icon"></div>&nbsp;There are registrations for the repeating events already present in the set, continuing with the process would unlink them and repeating events without registration would be trashed. </div><table id="options" class="display"><thead><tr><th>Event ID</th><th>Event</th><th>Participant Count</th></tr><thead>';
              for (var id in participantData) {
                for(var data in participantData[id]) {
//                warningHtml += '<tr><td>'+id+'</td><td> <a href="{/literal}{crmURL p="civicrm/event/manage/settings" q="reset=1&action=update&id="}{literal}'+id+'{/literal}{literal}">'+data+'</a></td><td><a href="{/literal}{crmURL p='civicrm/event/search' q="reset=1&force=1&status=true&event="}{literal}'+id+'{/literal}{literal}">'+participantData[id][data]+'</a></td></tr>';
                }
              }
              warningHtml += '</table><br/>';
            }
            if (errors.length > 0) {
              html = '';
              for (var j = 0; j < errors.length; j++) {
                html += '<span class="crm-error">*&nbsp;' + errors[j] + '</span><br/>';
              }
            }
            if (warningHtml != "") {
              $('#generated_dates').append(warningHtml).append(html);
            } else {
              $('#generated_dates').html(html);
            }
            if (end_date_text == "") {
              $('#th-end-date').hide();
            }
            if ($("#preview-dialog").height() >= 300) {
              $('#preview-dialog').css('height', '300');
              $('#preview-dialog').css('overflow-y', 'auto');
            }
          } else {
            $('div.ui-dialog-buttonset button span:contains(Ok)').hide();
            $('#generated_dates').append("<span class='crm-error'>Sorry, no dates could be generated for the given criteria!</span>");
          }
        },
        complete: function() {
          $('div.crm-loading-element').hide();
        }
      });
      return false;
      }
      );
  };
  
})(jQuery, _);

