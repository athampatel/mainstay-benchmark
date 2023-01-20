// return month name in short format
function getMonthNameShort(monthNumber) {
    const date = new Date();
    date.setMonth(monthNumber - 1);
    return date.toLocaleString('en-US', { month: 'short' });
}

// return month name in longer format
function getMonthNameLong(monthNumber) {
    const date = new Date();
    date.setMonth(monthNumber - 1);
    return date.toLocaleString('en-US', { month: 'long' });
}

// common ajax request
function AjaxRequestCom($url,$method,$data,$callback){
    $.ajax({
        type: $method,
        url: $url,
        dataType: "JSON",
        data: $data,
        success: function (res) {  
            window[$callback](res);
        }
    });
}

$(document.body).ready(function(){
    $(document.body).on('submit','#change-order-form',function(e){
        e.preventDefault();
        alert("HELLEE");
        return false;
    });
});

function ajaxAction($data,$container,$method,$url){
    $.ajax({
        type: $method,
        url: $url,
        dataType: "JSON",
        data: $data,
        beforeSend:function(){
        },
        success: function (res){  

        },
        complete:function(){

        }
    });
}