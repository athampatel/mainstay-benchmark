console.log('help page.js');

$(document).on('click','#help-save-button',function(e){
    e.preventDefault();

    let message = $('#help_textarea').val();
    let user_name = $('#user_name').val();
    let email_address = $('#email_address').val();
    let ph_no = $('#phone_no').val();
  
    let validation_error = '';
    if(message == ''){
        validation_error += 'Message field is required <br>';
    }
    if(user_name == ''){
        validation_error += 'Name field is required <br>';
    }
    if(email_address == ''){
        validation_error += 'Email field is required <br>';
    }
    // if(ph_no ==''){
        // validation_error += 'Phone Number field is required <br>';
    // }
    console.log(validation_error,'__validation error');
    // return false;
    if(validation_error != ''){
        $('#help-message-alert').removeClass('alert-success').addClass('alert-danger').html(validation_error).removeClass('d-none');
        return false;
    }
    // ajax message
    $.ajax({
        type: 'POST',
        url: '/sendHelp',
        dataType: "JSON",
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { "message" : message,'name': user_name,'email':email_address,'phone_no':ph_no},
        beforeSend:function(){
            $('.backdrop').removeClass('d-none')
        },
        success: function (res) {  
            if(res.success){
                $('#help-message-alert').addClass('alert-success').removeClass('alert-danger').html(res.message).removeClass('d-none');

                setTimeout(() => {
                    $('#help-message-alert').addClass('d-none')
                }, 2000);
            }
        },
        complete:function(){
            $('.backdrop').addClass('d-none')
        }
    });
})