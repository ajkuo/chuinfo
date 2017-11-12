
    $('input#SID').keypress(function(e) {
         code = e.keyCode ? e.keyCode : e.which;
         if(code == 13){
            $('#btnLogin').click();
         }
    });
    
    $('input#Password').keypress(function(e) {
         code = e.keyCode ? e.keyCode : e.which;
         if(code == 13){
            $('#btnLogin').click();
         }
    });

    $(document).ready(function(){
        if(localStorage.getItem('login_SID'))
        {
            $("input#SID").val(localStorage.getItem('login_SID'));
            $("#RemberMe").prop('checked', true);
        }

        $("#btnLogin").click(function(e){
            
            if($("#RemberMe").is(':checked'))
            {
                localStorage.setItem('login_SID', $("input#SID").val());
            }
            else
            {
                localStorage.removeItem('login_SID');
            }

            var loginData = {
                    SID: $("input#SID").val(),
                    Password: $("input#Password").val()
                };
            $.ajax({
                url: "../u/login_act.php",
                type: "POST",
                dataType: "json",
                data: loginData,
                success: function(result) {
                    if(result.status)
                    {
                        // Enable button & show success message
                        $("#btnSubmit").attr("disabled", false);
                        $('#result').html("<div class='alert alert-success'>");
                        $('#result > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                            .append("</button>");
                        $('#result > .alert-success')
                            .append("<strong>" + result.msg + "</strong>");
                        $('#result > .alert-success')
                            .append('</div>');
                        //clear all fields
                        $('#loginForm').trigger("reset");
                        window.open('../f/post_list.php', '_self');
                    }
                    else
                    {
                        $('#result').html("<div class='alert alert-danger'>");
                        $('#result > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                            .append("</button>");
                        $('#result > .alert-danger').append("<strong>" + result.msg + "</strong>");
                        $('#result > .alert-danger').append('</div>'); 
                    }
                },
                error: function() {
                    $('#result').html("<div class='alert alert-danger'>");
                    $('#result > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                        .append("</button>");
                    $('#result > .alert-danger').append("<strong>似乎發生了一些錯誤，請稍後再試。</strong>");
                    $('#result > .alert-danger').append('</div>');
                },
            });
        });
    });
