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
      //  data: $(date: '02/2012').serialize(),

        data: $('#ajax_form').serialize(),
        //data: {date: '1'},
        success: function(data){
            result = $.parseJSON(data);
            $('#result_form').html(result.date);
        }
    });
}

