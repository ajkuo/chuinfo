
    $(document).ready(function(){
	    $("#btnSubmit").click(function(e){

	        var SID = $("input#SID").val();
	        var Password = $("input#Password").val();
	        var rePassword = $("input#PasswordConfirm").val();
	        var Name = $("input#Name").val();
	        var Gender = $("#Gender").val();
	        var Department = $("#Department").val();
	        var Year = $("#Year").val();
	        var Month = $("#Month").val();
	        var Day = $("#Day").val();
	        var Nickname = $("input#Nickname").val();
	        var Email = $("input#Email").val();

	        var check = true;
	        var msg = "";
            var regEmail = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/; 
            var regPassword = /^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/; 
            var regSID = /^[a-zA-Z]{1}[0-9]{8}$/; 

	        if(Email.length != 0 && regEmail.test(Email) == false)
	        {
	        	check = false;
	        	msg = "信箱格式有誤，請重新輸入。";
	        }

	        if(Email.length != 0 && Email.length >= 300)
	        {
	        	check = false;
	        	msg = "信箱過長，請換一個試試看。";
	        }

	        if(Nickname.length > 20)
	        {
	        	check = false;
	        	msg = "暱稱過長，請重新輸入。";
	        }

	        if(Name.length > 20)
	        {
	        	check = false;
	        	msg = "姓名過長，如有特殊需求請來信。";
	        }

	        if(Password != rePassword)
	        {
	        	check = false;
	        	msg = "兩次密碼輸入不同，請再試一次。";
	        }

	        if(regPassword.test(Password) == false)
	        {
	        	check = false;
	        	msg = "密碼至少需要有一個英文字母或數字。";
	        }

	        if(Password.length < 8)
	        {
	        	check = false;
	        	msg = "密碼長度不足，請重新輸入！";
	        }

	        if(regSID.test(SID) == false)
	        {
	        	check = false;
	        	msg = "學號格式有誤！";
	        }

	        if(SID.length == 0 || Password.length == 0 || rePassword.length == 0 || Name.length == 0 || Nickname.length == 0)
	        {
	        	check = false;
	        	msg = "您還有資料尚未輸入，請重新檢查。";
	        }

	        if(check == true)
	        {
		        var regData = {
		                SID: SID,
		                Password: Password,
		                rePassword: rePassword,
		                Name: Name,
		                Gender: Gender,
		                Department: Department,
		                Year: Year,
		                Month: Month,
		                Day: Day,
		                Nickname: Nickname,
		                Email: Email
	            };

		        $.ajax({
		            url: "../u/register_act.php",
		            type: "POST",
		            dataType: "json",
		            data: regData,
		            success: function(result) {
		                if(result.status == 1)
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
		                    $('#result').append("<script>swal({title: '歡迎加入！', text: \"提醒您：請先至 <a target='_blank' href='https://webmail.chu.edu.tw/cgi-bin/openwebmail/openwebmail.pl'><strong>校內信箱</strong></a> 收取認證信（帳號為小寫學號、預設密碼為大寫身分證字號）以開通權限。\", type: 'success', html: true});</script>");
			                //clear all fields
			                $('#registerForm').trigger("reset");
		                }
		                else
		                {
		                    $('#result').append("<script>swal('哎呀！', '" + result.msg + "', 'error');</script>");
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
		    }
		    else
		    {
		    	swal('哎呀！', msg, 'error');
		    }
        });
    });
