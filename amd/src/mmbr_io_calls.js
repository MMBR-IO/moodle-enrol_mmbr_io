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

 /**
  * Submits form after approved payment, to enrol user in the course
  * @param $ -
  * @returns functions
  */
define(['jquery', 'core/ajax'], function($, ajax) {
    return {
        call: function() {
            window.addEventListener("message", receiveMessage, false);
            /**
             * 
             * @param {Object} event 
             */
            function receiveMessage(event) {
                if (typeof event !== 'undefined' && event.data === 'success' && (
                        event.origin === "http://localhost:4141" ||
                        event.origin === "https://staging.mmbr.io" ||
                        event.origin === "https://api.mmbr.io"
                )) {
                    window.console.log('We received success! Submitting form!');
                    $.ajax({
                        type: "POST",
                        url: "enrol.php",
                        data: {paymentStatus: "success", validate: {id: 15}},
                        dataType: 'json',
                        success: function (response) {
                            window.console.log('We submitted form and ', response);
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            window.console.log('During ajax call error happened ', errorThrown);
                         }
                    });
                    // $("form").submit();
                } else {
                    sendErrorToTheFrame('Connection Error');
                }
            }

            /**
             * Creates postMessage for contentWindow
             * Sends error message to iFrame
             * Our iFrame
             * 
             * @param {string} err 
             */
            function sendErrorToTheFrame(err) {
                var obj = {
                    success: false,
                    origin: 'moodle',
                    error: err,
                };
                var paymentFrame = document.getElementById('paymentFrame');
                paymentFrame.contentWindow.postMessage(JSON.stringify(obj), '*');
            }
        },
    };
});
