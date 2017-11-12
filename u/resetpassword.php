<?php require_once "../backend/chu_tool.php"; ?>
<?php require_once "../templates/base.php"; ?>
<?php
	Session_start();

	if(isset($_SESSION['SID']))
	{
		Session_destroy();
	}

	if(!isset($_GET['sid']) || empty($_GET['sid']) || !isset($_GET['token']) || empty($_GET['token']))
	{
		turn_to_url('/u/login.php');
	}
	else
	{
		$SID = input_filter(strtolower($_GET['sid']));
		$token = input_filter($_GET['token']);

		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		$exist = $db_tool->get_value('accounts', 'count(*)', 'SID = ?', array($SID));

		if($exist != 1)
		{
			turn_to_url('/u/login.php');
		}
		else
		{
			$mail_info = $db_tool->get_value('mail_forget_password', 'mail_token, mail_sendtime', 'SID = ? AND status = 0 ORDER BY mail_sendtime DESC LIMIT 1', array($SID));

			$date_prev = new DateTime($mail_info['mail_sendtime'], new DateTimeZone('Asia/Taipei'));
			$date_now = new DateTime(null, new DateTimeZone('Asia/Taipei'));
			$time_sub = $date_prev -> diff($date_now);

			if(empty($mail_info['mail_sendtime']))
			{
				turn_to_url('/u/login.php');
			}
			else if($token != $mail_info['mail_token'])
			{
				turn_to_url('/u/login.php');
			}
			else if($time_sub->y >= 0 && $time_sub->m >= 0 && $time_sub->d >= 3)
			{
				turn_to_url('/u/login.php');
			}
		}
	}
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $WEB_TITLE; ?></title>

    <?php echo $WEB_HCSS; ?>

	<style type="text/css">
		.control-group{
			margin-top: 10px;
		}

		html, body{
			height: 100%;
		}

		.wrapper {
		    min-height: 100%;
		    position: relative;
		}

		section .container{
			padding-top: 30px;
			padding-bottom: 177px;
		}

		@media(max-width:767px) {
			section .container{
				padding-top: 10px;
			}
		}

		footer{
			height: 157px;
			margin-top: -157px;
		    position: relative;
		}
	</style>

    <?php echo $WEB_HJS; ?>

</head>

<body id="page-top" class="index">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="../index.php"><?php echo $WEB_TITLE; ?></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="page-scroll">
                        <a href="../u/register.php">立即註冊</a>
                    </li>
                    <li class="page-scroll">
                        <a href="../u/login.php">登入</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

	<div class="wrapper">
		<section id="all_content">
			<div class="container">
	            <div class="row">
	                <div class="col-lg-12 text-center">
	                    <h2>密碼重置</h2>
	                    <hr class="star-primary">
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-12">
	                    <form id="loginForm">
		                	<div class="row control-group">
		                		<div class="col-lg-4 col-sm-3"></div>
		                		<div class="col-lg-4 col-sm-6">
					                <label>新密碼</label>
					                <input type="password" class="form-control" id="NewPassword"></input>
		                		</div>
		                		<div class="col-lg-4 col-sm-3"></div>
		                	</div>

		                	<div class="row control-group">
		                		<div class="col-lg-4 col-sm-3"></div>
		                		<div class="col-lg-4 col-sm-6">
					                <label>重覆新密碼</label>
					                <input type="password" class="form-control" id="ReNewPassword"></input>
		                		</div>
		                		<div class="col-lg-4 col-sm-3"></div>
		                	</div>
		                	<br>
		                	<div class="row control-group">
		                		<div class="col-lg-4 col-sm-3"></div>
		                		<div class="col-lg-4 col-sm-6">
					                <input type="button" class="form-control btn btn-info" id="btnSubmit" value="完　　成"></input>
		                		</div>
		                		<div class="col-lg-4 col-sm-3"></div>
		                	</div>
	                	</form>
	                </div>
	            </div>
	            <br>
			</div>
		</section>
	</div>

    <footer class="text-center">
        <div class="footer-above">
            <div class="container">
                <div class="row">
                    <div class="footer-col col-md-12">
                        <ul class="list-inline">
                            <li>
                                <a href="<?php echo $LINK_FB; ?>" class="btn-social btn-outline" target="_blank"><i class="fa fa-fw fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="mailto:<?php echo $LINK_MAIL; ?>" class="btn-social btn-outline"><i class="fa fa-fw fa-envelope-o"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>  
        <div class="footer-below">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; <?php echo $WEB_TITLE; ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <?php echo $WEB_FCSS; ?>
    <?php echo $WEB_FJS; ?>
    <script type="text/javascript">
	    $('input#NewPassword').keypress(function(e) {
	         code = e.keyCode ? e.keyCode : e.which;
	         if(code == 13){
	            $('#btnSubmit').click();
	         }
	    });
	    $('input#ReNewPassword').keypress(function(e) {
	         code = e.keyCode ? e.keyCode : e.which;
	         if(code == 13){
	            $('#btnSubmit').click();
	         }
	    });
    	$("#btnSubmit").click(function(){
    		var check = true;

    		if($("#NewPassword").val().length == 0)
    		{
    			check = false;
    			ErrorMsg = "請輸入欲修改之新密碼！";
    			$("#NewPassword").focus();
    		}
    		else if($("#ReNewPassword").val().length == 0)
    		{
    			check = false;
    			ErrorMsg = "請再次輸入欲修改之新密碼！";
    			$("#ReNewPassword").focus();
    		}
    		else if($("#NewPassword").val().length < 8)
    		{
    			check = false;
    			ErrorMsg = "新密碼強度不足，需要至少8碼！";
    			$("#NewPassword").focus();
    		}
    		else if($("#NewPassword").val() != $("#ReNewPassword").val())
    		{
    			check = false;
    			ErrorMsg = "新密碼兩次輸入不同！";
    			$("#ReNewPassword").focus();
    		}


    		if(check == false)
    		{
    			swal(ErrorMsg, "", "warning");
    		}
    		else
    		{
		    	$.ajax({
		    		url: "resetpassword_act.php",
		    		type: "POST",
		    		dataType: "json",
		    		data: {
		    			SID: '<?php echo $SID; ?>',
		    			token: '<?php echo $token; ?>',
		    			NewPassword: $("#NewPassword").val()
		    		},
		    		success: function(result){
	    				$("#NewPassword").val("");
	    				$("#ReNewPassword").val("");

		    			if(result.status == 1)
		    			{
					        swal({   
					        	title: "成功！",   
					        	text: result.msg,   
					        	type: "success",   
					        	showCancelButton: false,   
					        	confirmButtonText: "完　成",   
					        	closeOnConfirm: true 
					        },
					        function(){
					        	window.open('login.php', '_self');
					        });
		    			}
		    			else
		    			{
					        swal({   
					        	title: "oh..",   
					        	text: result.msg,   
					        	type: "warning",   
					        	showCancelButton: false,   
					        	confirmButtonText: "確　定",   
					        	closeOnConfirm: true 
					        });
		    			}
		    		}
				});	 
    		}
    	});
    </script>
    
</body>
</html>


