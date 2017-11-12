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
		turn_to_url('/');
	}
	else
	{
		$SID = input_filter(strtolower($_GET['sid']));
		$token = input_filter($_GET['token']);

		$db_tool = new DB_Tool;
		$db = &$db_tool->get_DB();
		$exist = $db_tool->get_value('accounts', 'count(*)', 'SID = ? AND del_status = 0 AND Permission = 0', array($SID));

		if($exist != 1)
		{
			turn_to_url('/');
		}
		else
		{
			$user_exist = $db_tool->get_value('accounts', 'count(*)', 'SID = ? AND mail_token = ? AND del_status = 0', array($SID, $token));

			if($user_exist == 1)
			{
				$sql = "UPDATE accounts SET Permission = 1 WHERE SID = ? AND del_status = 0;";
				$std = $db->prepare($sql);
				$std->execute (array($SID));

				$success_msg = "認證完成，請重新登入！";
			}
			else
			{
				turn_to_url('/');
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

	<link rel='stylesheet' href='../css/comment.css'>

	<style type="text/css">
		.control-group{
			margin-top: 10px;
		}

		html, body{
			height: 100%;
		}

		.wrapper {
		    min-height: 100%;
		    width: 100%;
		    position: relative;
		}

		section .container{
			padding-top: 30px;
			padding-bottom: 177px;
		}

		@media(max-width:767px) {
			section .container{
				padding-top: 10px;
				margin: 0 !important;
			}
			.dropdown-menu li:hover .dropdown-menu {
				position:static;
			}
		}

		@media (min-width: 767px){
			.container{
				margin-left: auto !important;
			}

			ul{
				padding: 0px;
			}
		}

		@media (min-width: 992px){
			.container {
			    width: 970px;
			}
		}
		@media (min-width: 1200px){
			.container {
			    width: 1170px;
				margin-left: auto !important;
			}
		}
		@media (min-width: 1600px){
			.container {
			    width: 1370px;	
				margin-left: 10% !important;
			}
		}


		footer{
			height: 157px;
			margin-top: -157px;
		    position: relative;
		}

		.stylish-input-group{
			border:1;
		}

		.stylish-input-group .input-group-addon{
		    background: white !important; 
		    border: 1px solid #ccc;
		    border-left: 0;
		}
		.stylish-input-group .form-control{
		    border: 1px solid #ccc;
			border-right:0; 
			box-shadow:0 0 0; 
			border-color:#ccc;
		}
		.stylish-input-group button{
		    border:0;
		    background:transparent;
		}

		.course_list>tbody>tr>td>a{
			text-decoration: none !important;
			color: #2c3e50 !important;
		}
		.course_list>tbody>tr>td>a:hover{
			text-decoration: underline !important;
		}

		.pmenu > li{
			 padding-top: 15px;
		}

		.ptitle{
			text-decoration: none !important;
		}

		.ptitle:hover{
			text-decoration: underline !important;
		}
		ul {
		    list-style-position: inside;
			padding-right: 15px;
		}
		a:hover{
			cursor: pointer;
		}

		#divEmptyMsg{
			text-align: center;
			padding: 30px;
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
				</div>
			</div>
		</section>
	</div>

	</script>

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
		<script type='text/javascript'>
			swal({
	    		title: '恭喜！',
	    		text: '<?php echo $success_msg; ?>',
	    		type: 'success',
	    		showCancelButton: false,
	    		confirmButtonText: '確　 定',
	    		closeOnConfirm: false,
			},
			function(){
				window.open('../u/login.php', '_self');
			});
		</script>
	</body>
</html>