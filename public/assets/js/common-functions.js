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


// $(document.body).ready(function(){
//     $(document.body).on('submit','#change-order-form',function(e){
//         e.preventDefault();
//         alert("HELLEE");
//         return false;
//     });
// });

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

// custom date format 1 Eg: Apr 10,2022

function CustomDateFormat1($date){
    let $_date = new Date($date);
    let date_number = $_date.getDate() < 9 ? '0'+$_date.getDate() : $_date.getDate();
    let date_month = getMonthNameShort($_date.getMonth() + 1);
    let date_year = $_date.getFullYear();
    return `${date_month} ${date_number}, ${date_year}`;
}


// search box work
$(document).on('click','#search-icon',function(e){
    e.preventDefault();
    $('#search-container').addClass('active')
})
// search close
$(document).on('click','#search-close',function(e){
    e.preventDefault();
    $('#search-container').removeClass('active')
})

$(document).on('click','#sidebar-icon',function(e){
    e.preventDefault();
    $('#sidebar').toggleClass('active1')
})