<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
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

		.btnDetail{
			background-color: #ffffff;
			border: 0;
		}

		.btnActive{
			background-color: #ecf0f1;
		}

		.btnDetail:hover{
			text-decoration: underline;
		}

		#divNoResult{
			margin: 50px 0px;
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
	                    <h2>課程資訊</h2>
	                    <p>
	                    	<i class="fa fa-paper-plane"></i>
	                    	&nbsp;目前僅開放通識課程資訊查詢和評論&nbsp;
	                    	<i class="fa fa-paper-plane"></i>
	                    	<br>
	                    	（點選標題列可調整排序）
	                    </p>
		                	<div class="row control-group">
		                		<div class="col-lg-4 col-sm-3"></div>
		                		<div class="col-lg-4 col-sm-6">
		                		</div>
		                		<div class="col-lg-4 col-sm-3"></div>
		                	</div>
	                </div>
	            </div>
	            <br>
	            <div class="row">
	            	<div class="col-lg-12">
	            		<div class="row">
		                	<div class="row control-group">
		                		<div class="col-sm-3 col-xs-4">
		            				<label>開課系所</label>
				            		<select class="form-control" id="ddlstDept">
				            			<option value="33">通識教育中心</option>
				            		</select>
		                		</div>
		                		<div class="col-sm-3 col-xs-4">
		                			<label>詳細分類</label>
				            		<select class="form-control" id="ddlstType">
				            			<option value="all">全部通識</option>
				            			<option value="核">核心通識</option>
				            			<option value="通">一般通識</option>
				            		</select>
		                		</div>
		                		<div class="col-sm-3 col-xs-4">
		                			<label>星期 (105-1)</label>
				            		<select class="form-control" id="ddlstDay">
				            			<option value="all">全部顯示</option>
				            			<option value="(一)">星期一</option>
				            			<option value="(二)">星期二</option>
				            			<option value="(三)">星期三</option>
				            			<option value="(四)">星期四</option>
				            			<option value="(五)">星期五</option>
				            			<option value="(六)">星期六</option>
				            		</select>
		                		</div>
		                		<div class="col-sm-3 col-xs-12">
		                			<label>直接搜尋</label>
				                    <div class="input-group stylish-input-group">
					                    <input type="text" class="form-control" id="txtKeyword" placeholder="免選系所分類，可直接搜尋" >
					                    <span class="input-group-addon">
					                        <button type="submit" id="btnKeywordSearch">
					                            <span class="fa fa-search"></span>
					                        </button>  
					                    </span>
					                </div>
		                		</div>
		                		<div class="col-lg-3 col-xs-1"></div>
		                	</div>
		                </div>
		                <hr>
	            	</div>
	            </div>

	         	<div class="row">
	                <div class="col-lg-12 text-center" id="divAllContent"></div>
                	<form id="fmCourse" method="POST" action="detail_act.php">
                		<input type="hidden" name="SID" id="SID" value="<?php echo $_SESSION['SID']; ?>">
                		<input type="hidden" name="CID" id="CID">
                	</form>
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

    <style type="text/css">
table.tablesorter thead tr{
	cursor: pointer;
}
table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
	background-color: #d4d8d8;
}
table.tablesorter tbody td {
	background-color: #ffffff;
}
table.tablesorter tbody tr.odd td {
	background-color:#ecf0f1;
}
    </style>

    <script type="text/javascript">var SID = '<?php echo $_SESSION['SID']; ?>';</script>
    <?php echo $WEB_FJS; ?>
    <script src="../js/jquery.tablesorter.min.js"></script>
    <script src="../js/course_list.js?v=2"></script>


</body>
</html>

