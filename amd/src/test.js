define(['jquery'], function($) { 
    return {
        init: function() {
           
            
        },
        setStripe: function() {
            $( document ).ready(function() {
                var stripe = Stripe('pk_test_g6do5S237ekq10r65BnxO6S0');
                var elements = stripe.elements();
                // Custom styling can be passed to options when creating an Element.
                var style = {
                    // base: {
                    //   color: '#32325d',
                    //   lineHeight: '24px',
                    //   fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    //   fontSmoothing: 'antialiased',
                    //   fontSize: '16px',
                    //   '::placeholder': {
                    //     color: '#aab7c4'
                    //   }
                    // },
                    // invalid: {
                    //   color: '#fa755a',
                    //   iconColor: '#fa755a'
                    // }
                    };
                    
                    // Create an instance of the card Element
                    var card = elements.create('card', {style: style});
                // Add an instance of the card Element into the `card-element` <div>.
                card.mount('#card-element');
                card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });
            });
        }
    };
});