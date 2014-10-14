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

<div id="recurring-dialog" class="hide-block">
    {ts}How would you like this change to affect other entities in the repetition set?{/ts}<br/><br/>
    <div class="show-block">
        <div class="recurring-dialog-inner-wrapper">
            <div class="recurring-dialog-inner-left">
                <button class="recurring-dialog-button only-this-event">{ts}Only this entity{/ts}</button>
            </div>
          <div class="recurring-dialog-inner-right">{ts}All other entities in the series will remain same.{/ts}</div>
        </div>
        <div class="recurring-dialog-inner-wrapper">
            <div class="recurring-dialog-inner-left">
                <button class="recurring-dialog-button this-and-all-following-event">{ts}This and Following entities{/ts}</button>
            </div>
            <div class="recurring-dialog-inner-right">{ts}Change applies to this and all the following entities.{/ts}</div>
        </div>
        <div class="recurring-dialog-inner-wrapper">
            <div class="recurring-dialog-inner-left">
                <button class="recurring-dialog-button all-events">{ts}All the entities{/ts}</button>
            </div>
            <div class="recurring-dialog-inner-right">{ts}Change applies to all the entities in the series.{/ts}</div>
        </div>
    </div>
</div>