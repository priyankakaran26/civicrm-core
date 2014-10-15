/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function ($, _, undefined) {
  $.fn.crmRecurringModeDialog = function ( options ) {
    console.log('crmRecurringModeDialog invoked.');
    var form = '';
    if (!options.entityID || !options.entityTable || !options.mapper) {
      CRM.console('error', 'Error: describe error');
      return false;
    }
    console.log(this);

    $(".only-this-event").click(function() {
      console.log('update-mode-1');
      updateMode(1, options);
    });

    $(".this-and-all-following-event").click(function() {
      console.log('update-mode-2');
      updateMode(2, options);
    });

    $(".all-events").click(function() {
      console.log('update-mode-3');
      updateMode(3, options);
    });

    // fixme: throw error if this type is not button
    var r = true;
    if (options.isOnClick) {
      // if already in click mode / late binding
      console.log("already clicked");
      r = openRecurringDialog(this, options);
      if (r == false) return false;
    } else {
      $(this).click(function() {
        console.log("real click happening");
        r = openRecurringDialog(this, options);
        if (r == false) return false;
      });
    }

  }
    function openRecurringDialog(buttonObj, options) {
      form = $(buttonObj).parents('form:first').attr('class');
      console.log("form=" + form);
      console.log(options.mapper.hasOwnProperty(form));
      if( form != "" && options.mapper.hasOwnProperty(form) ){
        $("#recurring-dialog").dialog({
          title: 'How does this change affect other repeating entities in the set?',
          modal: true,
          width: '650',
          buttons: {
            Cancel: function() { //cancel
              $(this).dialog( "close" );
            }
          }
        }).dialog('open');
        return false;
      }
    }
    
    function updateMode(mode, options) {
      var entityID    = parseInt(options.entityID);
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
})(jQuery, _);

