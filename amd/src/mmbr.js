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

define(['jquery'], function ($) {
    return {
        call: function () {
            console.log('MMBR.IO Message event listener started!');
            window.addEventListener("message", receiveMessage, false);
            function receiveMessage(event) {
                // Origin validation MUSTHAVE in production
                // if (event.origin !== "http://example.org:8080")
                //     return;
                // } 
                var paymentKey = event.data;
                console.log("Received key: " + event.data);
                // Apply receiveed data to the form and submit it
                $('<input>', {
                    type: 'hidden',
                    id: 'paymentkey',
                    name: 'paymentkey',
                    value: paymentKey
                }).appendTo('form');
                $("form").submit();
            } 
        }, 
        payment: function () {
            console.log('MMBR.IO Submit event listener started!');
            window.addEventListener("submit", processPayment, false);
            function processPayment(event) {
                event.preventDefault();
                console.log("Submit catched");
                console.log(event);
            }
        },
    };
});



