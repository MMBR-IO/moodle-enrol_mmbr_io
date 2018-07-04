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

/*
  This page is used to set Stripe account and submit payment
*/

/** 
 * @package    enrol_mmbr
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Dmitry
 */



define(['jquery'], function ($) {
  return {
    setStripe: function (userid, courseid, instanceid) {
    // Might be problem with using 'document' in JQuery
    //  $(document).ready(function () {
        // Create a Stripe client.
        var stripe = Stripe('pk_test_g6do5S237ekq10r65BnxO6S0');
        // Create an instance of Elements.
        var elements = stripe.elements();
        var style = {
          base: {
            color: '#32325d',
            lineHeight: '18px',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
              color: '#aab7c4'
            }
          },
          invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
          }
        };
        // Create an instance of the card Element.
        var card = elements.create('card', {
          style: style
        });
        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');
        // Handle real-time validation errors from the card Element.
        card.addEventListener('change', function (event) {
          var displayError = document.getElementById('card-errors');
          if (event.error) {
            displayError.textContent = event.error.message;
          } else {
            displayError.textContent = '';
          }
        });
        // Handle form submission.
        $( "#btnSubmit" ).click(function( event ) {
          event.preventDefault();

          stripe.createToken(card).then(function (result) {
            if (result.error) {
              // Inform the user if there was an error.
              var errorElement = document.getElementById('card-errors');
              errorElement.textContent = result.error.message;
            } else {
              // Send the token to your server.
              stripeTokenHandler(result.token, userid, courseid, instanceid);              
            }
          });
       // });
      });
      function stripeTokenHandler(token, userid, courseid, instanceid) {
      if(token) {
        callClerk(token, userid, courseid, instanceid);
      //  $( "form" ).submit();
      } 
      }
    }
  };
});

function callClerk(token, userid, courseid, instanceid){
  $.ajax({
    url: "https://webhook.site/31efcf38-45ca-41fe-bcb6-d141b471eaa2",
    type: "POST",
    data: { "userid": userid, "courseid": courseid, 
            "instanceid": instanceid, "token": token},
    complete: function(){
      console.log('Data successfully sent to Clerk');
    }
  });
}