<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	$SID = $_SESSION['SID'];

	$db_tool = new DB_Tool;
	$db = &$db_tool->get_DB();

	if(!isset($SID) || empty($SID))
	{
		turn_to_url('/c/course_list.php');
	}
	else
	{
		$count = $db_tool->get_value('accounts', 'count(*)', 'SID = ? AND del_status = 0', array($SID));
		
		if($count == 1)
		{
			$sql = "SELECT SID, a.Name as Name, Nickname, Gender, dob, Email, a.add_on as add_on, d.Name as dname FROM accounts a, departments d WHERE a.DeptNo = d.No AND SID = ? AND a.del_status = 0";
			$data_arr = array($SID);

			$std = $db->prepare($sql);
			$std->execute ($data_arr);
			$data = $std -> fetch(PDO::FETCH_ASSOC);
			
		}
		else
		{
			turn_to_url('/c/course_list.php');
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
        .remind{
            text-align: justify;
            text-justify: inter-ideograph;
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
                	<?php echo $WEB_NAV; ?>
                </ul>
            </div>
        </div>
    </nav>

	<div class="wrapper">
		<section id="all_content">
			<div class="container">
	            <div class="row">
	                <div class="col-lg-12 text-center">
	                    <img class="img circle" width="150" src="">
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-12 text-left" style="word-break: break-all;">
	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6 text-center">
				                <h2>編輯個人資料</h2>
				                <label class="remind">
				                	如有更改姓名、性別、系所、生日......等資料的需求，請與我們連絡，謝謝。
				                </label>
				                <hr class="star-primary">
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="SID">學　　號：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<span id="SID"><?php echo strtoupper($data['SID']); ?></span>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="Name">姓　　名：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<span id="Name"><?php echo $data['Name']; ?></span>&nbsp;
				                		<span>
				                			
				                		</span>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="Nickname">暱　　稱：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<input type="text" class="form-control" id="Nickname" maxlength="20" value="<?php echo $data['Nickname']; ?>"></input>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="Gender">性　　別：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
										<span id="Gender">
											<?php
												switch($data['Gender']){
													case "M":
														echo "男";
														break;
													case "F":
														echo "女";
														break;
													case "O":
														echo "其他";
														break;
												}
											?>
										</span> 
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>


	                	<div class="row	control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="Department">隸屬系所：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<span id="Department"><?php echo $data['dname']; ?></span>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row	control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="DoB">生　　日：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<span id="DoB"><?php echo $data['dob']; ?></span>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row	control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="Email">電子信箱：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                	<input type="text" class="form-control" id="Email" value="<?php if(!empty($data['Email'])) echo $data['Email']; ?>" placeholder="選填，將可以藉此接收通知。" ></input>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

				        <hr class="line">

	                	<div class="row	control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="CurPassword">目前密碼：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                	<input type="password" class="form-control" id="CurPassword" placeholder="欲修改密碼才需要填寫。" ></input>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row	control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="NewPassword">新密碼：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                	<input type="password" class="form-control" id="NewPassword" placeholder="欲修改密碼才需要填寫。" ></input>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row	control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<label for="ReNewPassword">重複新密碼：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                	<input type="password" class="form-control" id="ReNewPassword" placeholder="欲修改密碼才需要填寫。" ></input>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

				        <hr class="line">
				        
				        <div>
				        	<div class="row">
	                		<div class="col-lg-4 col-xs-3"></div>
	                		<div class="col-lg-4 col-xs-6">
	                			<div class="row">
	                				<div class="col-xs-6">	                					
	                					<button class="form-control btn btn-info" id="btnSave">儲存</button>
	                				</div>
	                				<div class="col-xs-6">	                					
	                					<a href="profile.php"><input type="button" class="form-control btn btn-default" value="取消"></input></a>
	                				</div>
	                			</div>
	                		</div>
	                		<div class="col-lg-4 col-xs-3"></div>
				        </div>
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
	    var Nickname = "";
	    var Email = "";

	    $(document).ready(function(){
	    	Nickname = $("#Nickname").val();
	    	Email = $("#Email").val();
	    });

    	$("#btnSave").click(function(e){
	    	var check = true;
	    	var edited = false;
	    	var ErrorMsg = "";
            var regEmail = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z]+$/; 
            var regPassword = /^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/; 

	    	if($("#Nickname").val() != Nickname || $("#Email").val() != Email)
	    	{
	    		edited = true;
	    	}

	    	if($("#CurPassword").val().length != 0 || $("#NewPassword").val().length != 0 || $("#ReNewPassword").val().length != 0)
	    	{

	    		edited = true;

	    		if($("#CurPassword").val().length == 0)
	    		{
	    			check = false;
	    			ErrorMsg = "請輸入目前的密碼！";
	    			$("#CurPassword").focus();
	    		}
	    		else if($("#NewPassword").val().length == 0)
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
	    		else if($("#CurPassword").val() == $("#NewPassword").val())
	    		{
	    			check = false;
	    			ErrorMsg = "新密碼不得與原密碼相同！";
	    			$("#NewPassword").focus();
	    		}
	    		else if(regPassword.test($("#NewPassword").val()) == false)
	    		{
	    			check = false;
	    			ErrorMsg = "新密碼強度不足，需要至少8碼並含英數！";
	    			$("#NewPassword").focus();
	    		}
	    		else if($("#NewPassword").val() != $("#ReNewPassword").val())
	    		{
	    			check = false;
	    			ErrorMsg = "新密碼兩次輸入不同！";
	    			$("#ReNewPassword").focus();
	    		}
	    	}

	    	if($("#Nickname").val().trim().length == 0)
	    	{
	    		check = false;
	    		ErrorMsg = "暱稱不得為空！";
	    		$("#Nickname").focus();
	    	}

	    	if($("#Email").val().length > 0 && regEmail.test($("#Email").val()) == false)
	    	{
	    		check = false;
	    		ErrorMsg = "信箱格式有誤！";
	    		$("#Email").focus();
	    	}


    		if(check == false)
    		{
    			swal(ErrorMsg, "", "warning");
    		}
    		else
    		{
    			if(edited == true)
	    		{
					$("#btnSave").prop('disabled', true);

		    		var userData = {
		    			SID: $("#SID").html().toLowerCase(),
		    			Nickname: $("#Nickname").val(),
		    			Email: $("#Email").val(),
		    			CurPassword: $("#CurPassword").val(),
		    			NewPassword: $("#NewPassword").val()
		    		};

		    		$.ajax({
		                url: "../u/profile_edit_act.php",
		                type: "POST",
		                dataType: "json",
		                data: userData,
		                success: function(result){
		                	if(result.status == 1)
		                	{
				    			$("#Nickname").val("");
				    			$("#Email").val("");
				    			$("#CurPassword").val("");
				    			$("#NewPassword").val("");
				    			$("#ReNewPassword").val("");
						        swal({   
						        	title: "成功！",   
						        	text: result.msg,   
						        	type: "success",   
						        	showCancelButton: false,   
						        	confirmButtonText: "完　成",   
						        	closeOnConfirm: true 
						        },
						        function(){
						        	window.open("profile.php", "_self");
						        });
		    				}
		    				else
		    				{
		    					swal("Oh!", result.msg, "warning");
		    				}
		                }
		    		});

					$("#btnSave").prop('disabled', false);
		    	}
		    	else
		    	{
			        swal({   
			        	title: "完成。",   
			        	text: "沒有更新任何內容。",   
			        	type: "success",   
			        	showCancelButton: false,   
			        	confirmButtonText: "完　成",   
			        	closeOnConfirm: true 
			        },
			        function(){
			        	window.open("profile.php", "_self");
			        });
		    	}
    		}
    	});

    </script>


</body>
</html>

