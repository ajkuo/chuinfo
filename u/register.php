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
                        <a href="../u/login.php">登入</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


	<style type="text/css">
		.control-group{
			margin-top: 10px;
		}

		select:invalid{
			color: gray;
		}

        .remind{
            text-align: justify;
            text-justify: inter-ideograph;
        }
	</style>
	<section>
		<div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2>讓我們準備開始吧！</h2>
                    <p>除信箱外皆為必填欄位，請輸入真實資料</p>
                    <hr class="star-primary">
                </div>
            </div>
            <form id="registerForm">
	            <div class="row">
	                <div class="col-lg-12">
	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label for="SID">學　　號&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://webmail.chu.edu.tw/cgi-bin/openwebmail/openwebmail.pl" target="_blank" title="溫馨提示：帳號需輸入小寫學號，密碼為身份證或與您學生資訊系統密碼相同。">Q: 	我該去哪收信？</a></label>
				                <input type="text" class="form-control" placeholder="系統將會寄發認證信件至您的校內信箱" id="SID" required data-validation-regex-regex="^[a-zA-Z]{1}[0-9]{8}$"></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label for="Password">密　　碼</label>
				                <input type="password" class="form-control" placeholder="請輸入至少 8 碼英數混合的密碼" id="Password" required data-validation-regex-regex="^(?=.*\d)(?=.*[a-zA-Z]).{8,30}$"></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label for="PasswordConfirm">確認密碼</label>
				                <input type="password" class="form-control" placeholder="請再輸入一次密碼" id="PasswordConfirm" required data-validation-matches-match="Password"></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label for="Name">姓　　名</label>
				                <input type="text" class="form-control" placeholder="請務必輸入真實姓名，不會公開顯示" id="Name" required maxlength="15"></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label for="Nickname">暱　　稱</label>
				                <input type="text" class="form-control" placeholder="限 15 字內，暱稱將會公開顯示" id="Nickname" required maxlength="15"></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label for="Gender">性　　別</label>
				                <select class="form-control" id="Gender" name="Gender">
									<option value="M">男</option>
									<option value="F">女</option>
									<option value="O">其他</option>
								</select>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label for="Department">系　　所</label>
				                <select class="form-control" id="Department" name="Department">		
						             <option value="" disabled selected hidden>請選擇您的系所</option>
						             <?php 
						             		$db_tool = new DB_Tool;
						             		$db = &$db_tool->get_DB();
											$sql = "SELECT No, Name FROM departments WHERE Type = 'S';";
											$std = $db->prepare($sql);
											$std->execute();
											$rc = $std -> rowCount();
											$rs = $std -> fetchAll();
											foreach($rs as $data):
						             ?>
						             <option value="<?php echo $data['No']; ?>"><?php echo $data['Name']; ?></option>
						         <?php endforeach; ?>
								</select>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label>生　　日</label>
				                <br>
				                <div class="row">
					                <div class="col-xs-4">
						                <select class="form-control" id="Year" name="Year">
						                <?php $start = getdate()["year"]; ?>
						                	<option value="-1" disabled selected hidden>年</option>
						                	<?php
						                		for($i = $start; $i >=($start-65); $i--)
						                		{
						                	?>
						                	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						                	<?php }; ?>
						                </select>
					                </div>
					                <div class="col-xs-4">
						                <select class="form-control" id="Month" name="Month">
						                	<option value="-1" disabled selected hidden>月</option>
						                	<option value="1">1</option>
						                	<option value="2">2</option>
						                	<option value="3">3</option>
						                	<option value="4">4</option>
						                	<option value="5">5</option>
						                	<option value="6">6</option>
						                	<option value="7">7</option>
						                	<option value="8">8</option>
						                	<option value="9">9</option>
						                	<option value="10">10</option>
						                	<option value="11">11</option>
						                	<option value="12">12</option>
						                </select>
					                </div>
					                <div class="col-xs-4">
						                <select class="form-control" id="Day" name="Day">
						                	<option value="-1" disabled selected hidden>日</option>
						                	<?php 
						                	//cal_days_in_month(CAL_GREGORIAN, 2, 2016);

						                		for($i=1; $i <=31; $i++){
						                	?>
						                	<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
						                	<?php } ?>

						                </select>
					                </div>
				                </div>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<div class="row control-group">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <label>校外備用信箱（選填）</label>
				                <input type="email" class="form-control" placeholder="通過認證後，將可以此信箱接收通知" id="Email"></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<br>
	                	
	                	<hr class="star-primary">

	                	<div class="row control-group text-center">
	                		<div class="col-lg-4 col-sm-3"></div>
	                		<div class="col-lg-4 col-sm-6">
				                <h2>啟程前再檢查一下...</h2><br>
				                <label class="remind">請再次確認資料正確無誤，一旦按下送出便無法再直接更改您的學號、姓名、系所及生日。此外，<u>若您並非學號所有人，將無法順利通過認證</u>，因此請務必確保資料真實性，以免造成困擾。
				                </label>
				                <br><br>
				                <label class="remind">而當您完成註冊後，即代表您已閱讀並且同意<?php echo $WEB_TITLE; ?>《<a href="../terms/" target="_blank">服務條款</a>》之內容。</label>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>

	                	<hr class="line">
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
				                <input type="button" class="form-control btn btn-info" name="btnSubmit" id="btnSubmit" value="完成註冊"></input>
	                		</div>
	                		<div class="col-lg-4 col-sm-3"></div>
	                	</div>
	                </div>
	            </div><br><br>
            </form>
		</div>
	</section>

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

    <script type="text/javascript" src="../js/signup.js"></script>

    
</body>
</html>
