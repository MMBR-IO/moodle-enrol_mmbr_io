define(['jquery'], function ($) {
    return {
        call: function () {
            console.log('MMBR js started!');

            window.addEventListener("message", function(event) {
                console.log("Hello from " + event.data);
                $("form").submit();
            }); 
        }
    };
});



