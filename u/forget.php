<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_content('/c/course_list.php'); ?>
<?php require_once "../templates/base.php"; ?>
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
	                    <h2>忘記密碼？</h2>
	                    <p>請輸入您的學號，我們將會寄密碼通知信至您的校內信箱。</p>
	                    <hr class="star-primary">
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-12">
	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label>學　　號</label>
				                <input type="text" class="form-control" id="SID" required></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>
	                	<br><br>
	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label class="remind">※ 請注意：務必確保學號正確無誤，並切勿任意輸入他人學號，以免造成困擾，如有任何問題請<a href="mailto:<?php echo $LINK_MAIL; ?>">與我們聯絡</a>。
				                </label>
				                <br><br>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <div id="result"></div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <input type="button" class="form-control btn btn-info" id="btnSubmit" value="送　　出"></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
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
    	$("#btnSubmit").click(function(){
    		if($("#SID").val().length == 0)
    		{
    			swal("oh..", "您尚未輸入學號呢。", "warning");
    		}
    		else
    		{
		    	$.ajax({
		    		url: "forget_act.php",
		    		type: "POST",
		    		dataType: "json",
		    		data: {
		    			SID: $("#SID").val()
		    		},
		    		success: function(result){
		    			if(result.status == 1)
		    			{
					        swal({   
					        	title: "成功！",   
					        	text: result.msg,   
					        	type: "success",   
					        	showCancelButton: false,   
					        	confirmButtonText: "完　成",   
					        	closeOnConfirm: true 
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
	    				$("#SID").val("");
		    		}
				});	 
    		}
    	});
    </script>
    
</body>
</html>


