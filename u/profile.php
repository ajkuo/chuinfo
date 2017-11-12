<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	$SID = $_SESSION['SID'];
	$UserNo = $_SESSION['UserNo'];


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
			$sql = "UPDATE accounts SET TotalPost = (SELECT count(*) FROM forum_post WHERE UserNo = ? AND del_status = 0), TotalResponse = (SELECT count(*) FROM post_response WHERE UserNo = ? AND del_status = 0), TotalComment = (SELECT count(*) FROM course_response WHERE UserNo = ? AND del_status = 0) WHERE No = ? AND del_status = 0;";
			$data_arr = array($UserNo, $UserNo, $UserNo, $UserNo);
			$std = $db->prepare($sql);
			$std->execute ($data_arr);

			$sql = "SELECT SID, a.Name as Name, Nickname, Gender, TotalPost, TotalResponse, TotalComment, dob, Email, a.add_on as add_on, d.Name as dname FROM accounts a, departments d WHERE a.DeptNo = d.No AND SID = ? AND a.del_status = 0;";
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
	                <?php
	                	switch($data['Gender'])
	                	{
	                		case "M":
	                			echo "<img src='../images/default_male.svg' width='150'>";
	                			break;
	                		case "F":
	                			echo "<img src='../images/default_female.svg' width='150'>";
	                			break;
	                		default:
	                			break;
	                	}
	                ?>	                	
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-12 text-left" style="word-break: break-all;">
	                	<div class="row control-group">
	                		<div class="col-lg-3 col-sm-3"></div>
	                		<div class="col-lg-6 col-sm-6 text-center">
				                <h2><?php echo $data['Nickname']; ?></h2>
				                <hr class="<?php if($data['Gender']=='M'){ ?>mars<?php } else if($data['Gender']=='F'){ ?>venus<?php }else{ ?>transgenderist<?php } ?>">
	                		</div>
	                		<div class="col-lg-3 col-sm-3"></div>
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
				                	<?php 
				                		if(empty($data['Email'])){
				                	?>
				                		<span><a href="profile_edit.php#Email">目前尚未填寫</a></span>
				                	<?php
				                		}
				                		else{
				                	?>
				                		<span id="Email">
				                			<?php echo $data['Email']; ?>
				                		</span>
				                	<?php
				                		}
				                	?>
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
				                		<label for="PostCnt">課程評論：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<span id="PostCnt">已發表 <?php echo $data['TotalComment']; ?> 篇評論</span>
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
				                		<label for="PostCnt">文章總數：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<span id="PostCnt">已發表 <?php echo $data['TotalPost']; ?> 篇 / 回應 <?php echo $data['TotalResponse']; ?> 篇</span>
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
				                		<label for="regDate">註冊日期：</label>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-2"></div>
				                	<div class="col-xs-8">
				                		<span id="regDate">
				                			<?php echo date("Y/m/d", strtotime($data['add_on'])); ?>
				                		</span>
				                	</div>
				                	<div class="col-xs-2"></div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>
				        
				        <hr class="line">

				        <div>
				        	<div class="row">
	                		<div class="col-lg-5 col-xs-4"></div>
	                		<div class="col-lg-2 col-xs-4">
	                			<a href="profile_edit.php"><input type="button" class="form-control btn btn-default" value="修改資料"></input></a>
	                		</div>
	                		<div class="col-lg-5 col-xs-4"></div>
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

</body>
</html>

