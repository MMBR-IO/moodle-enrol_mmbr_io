define(['jquery'], function($) {
 
    return {
        init: function() {
            alert("Start");
            console.log("Reached js file");
 
           $( "#dima" ).html("Worked");
           $( "#btntest").click(function(){
                alert("Helo");
           });
        }
    };
});