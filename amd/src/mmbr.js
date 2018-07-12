define(['jquery'], function ($) {
    return {
        call: function () {
            console.log('MMBR.IO Message event listener started!');

            window.addEventListener("message", receiveMessage, false);
            function receiveMessage(event) {
                console.log("Hello from " + event.data);
            // if (event.origin !== "http://example.org:8080")
            //     return;
            // } 
                $("form").submit();
            } 
        },
        payment: function () {
            console.log('MMBR.IO Submit event listener started!');
            console.log();
            window.addEventListener("submit", processPayment, false);
            function processPayment(event) {
              //  event.preventDefault();
                console.log("Submit catched");
             //   console.log(event);

            }
        }
    };
});



