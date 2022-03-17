/* Article FructCode.com */
$( document ).ready(function() {
    $("#btn").click(
        function(){

            sendAjaxForm1();

            return false;
        }
    );

});

function sendAjaxForm1()
{
    $.ajax({
        url: '/php/action_ajax_form.php',
        method: 'post',
        dataType: 'html',
        data: $('#ajax_form').serialize(),
        success: function(data){
            result = $.parseJSON(data);
            $('#result_form').html(result.date);
        }
    });
}

