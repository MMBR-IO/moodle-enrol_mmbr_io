define(['jquery'], function ($) {
    return {
        call: function () {
            console.log('MMBR js started!');

            window.addEventListener("message", receiveMessage, false);
            function receiveMessage(event) {
                console.log("Hello from " + event.data);
            // if (event.origin !== "http://example.org:8080")
            //     return;
            // } 
                $("form").submit();
            } 
        }
    };
});



