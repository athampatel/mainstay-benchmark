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