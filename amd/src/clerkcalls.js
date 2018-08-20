// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/** 
 * @package     enrol_mmbr
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright   Dmitry Nagorny
 */

/*
  This page is used to sync local data with Clerk
*/

define(['jquery','core/ajax'], function ($) {
    return {
      webhook: function () {
        $.ajax({
            url: "https://webhook.site/31efcf38-45ca-41fe-bcb6-d141b471eaa2",
            type: "GET",
           // data: {"value1":"HELLO"},
            complete: function(){
            }
          });
      },
      test: function() {
          alert("Working hopefully!!");
      }
    };
});