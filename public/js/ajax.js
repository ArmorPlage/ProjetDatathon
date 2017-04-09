$( document ).ready(function() {
    $.ajax({
        type: "GET",
        url: "les-plages-json/",
        contentType: "application/json; charset=utf-8",
        data: "{}",
        dataType: "json",
        success: function (result) 
        {
            $.each(result, function(index, element) {
                    console.log(element.UMID+" "+element.TITRE);
            });
        },
        error: function (result) 
        {
            var response = result.responseText;
            console.log('Error loading: ' + response);
        }
    });
});