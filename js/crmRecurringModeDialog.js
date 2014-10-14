/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function ($, _, undefined) {
  $.fn.crmRecurringModeDialog = function ( options ) {
    var form = '';
    if (!options.entityID || !options.entityTable || !options.mapper) {
      CRM.console('error', 'Error: describe error');
      return false;
    }
    // fixme: throw error if this type is not button
    $(this).click(function() {
      form = $(this).parents('form:first').attr('class');
      if( form != "" && options.mapper.hasOwnProperty(form) ){
        $("#recurring-dialog").dialog({
          title: 'How does this change affect other repeating entities in the set?',
          modal: true,
          width: '650',
          buttons: {
            Cancel: function() { //cancel
              $( this ).dialog( "close" );
            }
          }
        }).dialog('open');
        return false;
      }
    });
    
    $(".only-this-event").click(function() {
      updateMode(1);
    });

    $(".this-and-all-following-event").click(function() {
      updateMode(2);
    });

    $(".all-events").click(function() {
      updateMode(3);
    });
    
    function updateMode(mode) {
      var entityID = parseInt(options.entityID);
      var entityTable = options.entityTable;
      if (entityID != "" && mode && options.mapper.hasOwnProperty(form) && entityTable !="") {
        var ajaxurl = CRM.url("civicrm/ajax/recurringentity/update-mode");
        var data    = {mode: mode, entityId: entityID, entityTable: entityTable, linkedEntityTable: options.mapper[form]};
        $.ajax({
          dataType: "json",
          data: data,
          url:  ajaxurl,
          success: function (result) {
            if (result.status == 'Done') {
              $("#recurring-dialog").dialog( "close" );
              $('#mainTabContainer div:visible Form, form.'+form).submit();
            } else if (result.status == 'Error') {
              var errorBox = confirm(ts("Mode could not be updated, save only this event?"));
              if (errorBox == true) {
                $("#recurring-dialog").dialog( "close" );
                $('#mainTabContainer div:visible Form, form.'+form).submit();
              } else {
                $("#recurring-dialog").dialog( "close" );
                return false;
              }
            }
          }
        });
      }
    } 
  };
  
})(jQuery, _);

