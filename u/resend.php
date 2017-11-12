<?php require_once "../backend/chu_tool.php"; ?>
<?php require_once "../templates/base.php"; ?>
<?php
		Session_start();
		
		$root = 'http://' . $_SERVER['HTTP_HOST'];

		if(!isset($_SESSION['SID']) || empty($_SESSION['SID']) || !isset($_SESSION['UserNo']) || empty($_SESSION['UserNo']) || !isset($_SESSION['Permission']))
		{
			$url = $root . '/u/login.php';
			header("Location: $url");
			exit();
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
                        <a href="../u/logout.php">登出</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

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

        .fa-4{
        	font-size: 2em;
        }

        #chuMailLink{
        	font-size: large;
        	color: #217dbb;
        }
	</style>
	
	<div class="wrapper">
		<section id="all_content">
			<div class="container">
	            <div class="row">
	                <div class="col-lg-12 text-center">
	                    <h2>仍未收到認證信？</h2>
                		<div class="col-lg-4 col-xs-1"></div>
                		<div class="col-lg-4 col-xs-10">
	                    	<p>若超過十分鐘仍未在<strong><a href="https://webmail.chu.edu.tw/cgi-bin/openwebmail/openwebmail.pl" target="_blank" title="溫馨提示：帳號需輸入小寫學號，密碼為身分證或與您學生資訊系統密碼相同。" id="chuMailLink"> 校內信箱 </a></strong>（帳號為小寫學號、密碼預設為大寫身分證字號）收到認證信，請在下方點選【重寄認證信】按鈕。</p>
                		</div>
                		<div class="col-lg-4 col-xs-1"></div>
                		<div class="col-xs-12">
                    		<hr class="star-primary">
                		</div>
	                </div>	                   
	            </div> 
            	<br>
	            <div class="row">
	                <div class="col-lg-12">
			            <div class="row">
		                	<div class="row control-group">
		                		<div class="col-lg-4 col-xs-3"></div>
		                		<div class="col-lg-4 col-xs-6">
					                <input type="button" class="form-control btn btn-info" id="btnResend" value="重寄認證信"></input>
		                		</div>
		                		<div class="col-lg-4 col-xs-3"></div>
		                	</div>
		                	<br>
		                	<hr class="line">
		                	<div class="row control-group text-center">
		                		<div class="col-lg-4 col-xs-2"></div>
		                		<div class="col-lg-4 col-xs-8">			                			
					                <label class="remind">※ 若您已補寄認證信，並超過半小時仍未收到，請透過<a href="https://www.facebook.com/chuinfo/"> Facebook粉絲頁面 </a>傳送訊息告訴我們，並附上學生證正面照以確認身分，我們會在最短時間內為您處理，如有不便之處，敬請見諒。
					                <br><br>※ 若您已收到認證信並完成認證，請重新登入，謝謝。</label>
		                		</div>
		                		<div class="col-lg-4 col-xs-2"></div>
		                	</div>
			            </div>
	                </div>
	            </div>
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
    	$("#btnResend").click(function(){
	    	var SID = '<?php echo $_SESSION['SID']; ?>';
	    	$.ajax({
	    		url: "resend_act.php",
	    		type: "POST",
	    		dataType: "json",
	    		data: {
	    			SID: SID
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
				        },
				        function(){
				        	window.open("logout.php", "_self");
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
    	});
    </script>
</body>
</html>
