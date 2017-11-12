<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	$db_tool = new DB_Tool;
	$db = &$db_tool->get_DB();
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

		.pmenu > li{
			 padding-top: 10px;
		}

		.ptitle{
			text-decoration: none !important;
		}

		.ptitle:hover{
			text-decoration: underline !important;
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
	<?php
		$sql = "SELECT No, Name, Code, PublicPermission FROM forum_type WHERE del_status = 0 ORDER BY Sort ASC;";
		$std = $db->prepare($sql);
		$std->execute ();
		$type = $std -> fetchAll(PDO::FETCH_ASSOC);
	?>
	<div class="wrapper">
		<section id="all_content">
			<div class="container">
				<div class="row">
					<div class="col-sm-2 hidden-xs">
						<ul class="pmenu" style="font-size: large; margin-top: 15px;">
						<?php
							foreach($type as $data): 
						?>
							<li><a href="post_list.php?t=<?php echo $data['Code']; ?>"><?php echo $data['Name']; ?></a></li>
						<?php
							endforeach;
						?>
						</ul>
					</div>
					<div class="col-sm-7 col-xs-12">
						<h1>發表新文章</h1>
						<div class="row">
							<div class="col-xs-4 col-md-3">
								<label for="txtTitle">文章分類</label>
								<select class="form-control" id="ddlstType">
									<?php
										foreach($type as $data): 
											if($data['PublicPermission'] <= $_SESSION['Permission'])
											{
									?>
										<option value="<?php echo $data['No']; ?>"><?php echo $data['Name']; ?></option>
									<?php
											}
										endforeach;
									?>
								</select>
							</div>
							<div class="col-xs-8 col-md-9">
								<label for="txtTitle">文章標題</label>
								<input class="form-control" placeholder="請記得輸入標題哦。" type="text" id="txtTitle" maxlength="25">
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<label for="txtContent">文章內容</label>
								<textarea class="form-control" placeholder="嗨，今天想聊些什麼呢？" id="txtContent" rows="15"></textarea>
							</div>
						</div><br>
						<div class="row">
							<div class="col-xs-12 text-center">
								<label><input type="checkbox" id="chkAnonymous"><span id="lbAnonymous">&nbsp;匿名發表（隱藏系級和性別）</span></label><br>
								<label><i class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;提醒您：發表文章即表示您已同意本站<a href="../terms/#privacy" target="_blank">著作權聲明</a>，並願遵守<a href="../terms/post_rule.php" target="_blank">文章規範</a>。</label>
							</div>
						</div><br>
						<div class="row">
							<div id="lbSubmit"></div>
						</div>
						<div class="row">
							<div class="col-xs-1 col-sm-3"></div>
							<div class="col-xs-5 col-sm-3">
								<button class="btn btn-default form-control" id="btnCancel"> 取　消 </button>
							</div>
							<div class="col-xs-5 col-sm-3">
								<button class="btn btn-info form-control" id="btnSubmit"> 送　出 </button>
							</div>
							<div class="col-xs-1 col-sm-3"></div>
						</div>
					</div>
					<div class="col-sm-3 hidden-xs">
						<div class="fb-page" data-href="https://www.facebook.com/chuinfo/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/chuinfo/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/chuinfo/">中華大學資訊網</a></blockquote></div>
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
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : '1767143666862817',
	      xfbml      : true,
	      version    : 'v2.7'
	    });
	  };

	  (function(d, s, id){
	     var js, fjs = d.getElementsByTagName(s)[0];
	     if (d.getElementById(id)) {return;}
	     js = d.createElement(s); js.id = id;
	     js.src = "//connect.facebook.net/en_US/sdk.js";
	     fjs.parentNode.insertBefore(js, fjs);
	   }(document, 'script', 'facebook-jssdk'));
	</script>

    <script type="text/javascript">
    	var UserNo = <?php echo $_SESSION['UserNo']; ?>;
    	var edited = false;


	    $(document).ready(function(){
	    	if(localStorage.getItem('forum_TypeCode'))
	    	{
	    		var type = localStorage.getItem('forum_TypeCode');

	    		switch(type){
	    			case "complex":
	    				$("#ddlstType").val(2);
	    				break;
	    			case "hate":
	    				$("#ddlstType").val(3);
	    				break;
	    			case "funny":
	    				$("#ddlstType").val(4);
	    				break;
	    			case "love":
	    				$("#ddlstType").val(5);
	    				break;
	    			case "chat":
	    				$("#ddlstType").val(6);
	    				break;
	    			case "group":
	    				$("#ddlstType").val(7);
	    				break;
	    			default:
	    				$("#ddlstType").val(2);
	    				break;
	    		}
	    	}
	    });

		$(document).on('keydown keyup change', '#txtTitle', function(e){
    		if($("#txtTitle").val().trim().length <= 0 && $("#txtContent").val().trim().length <= 0 )
    		{
    			edited = false;
    		}
    		else
    		{
				edited = true;
			}
	    });

		$(document).on('keydown keyup change', '#txtContent', function(e){
    		if($("#txtTitle").val().trim().length <= 0 && $("#txtContent").val().trim().length <= 0 )
    		{
    			edited = false;
    		}
    		else
    		{
				edited = true;
			}
	    });
	    
		$(window).on('beforeunload', function(){
			if(edited)
		      return 'Are you sure you want to leave?';
		});



    	$("#btnCancel").click(function(e){
    		if(edited)
    		{
		        swal({   
		        	title: "確定放棄？",   
		        	text: "若按下確定，您已編輯的文章將會消失哦。",   
		        	type: "warning",   
		        	showCancelButton: true,   
		        	cancelButtonText: "取　　消",    
		        	confirmButtonText: "確定放棄",   
		        	closeOnConfirm: false 
		        }, 
		        function(){
        			swal.disableButtons(); 
    				window.open("../f/post_list.php", "_self");
		        });
    		}
    		else
    		{
				window.open("../f/post_list.php", "_self");
    		}
    	});

    $("#btnSubmit").click(function(e){
    	if($("#txtTitle").val().trim().length > 0 && $("#txtContent").val().trim().length > 0 )
    	{
			$("#btnSubmit").prop('disabled', true);
			$("#btnCancel").prop('disabled', true);

    		if($('#ddlstType').val() == 3)
    		{
				$("#chkAnonymous").prop('checked', true);
    		}

            var postData = {
        		SID: UserNo,
        		Type: $("#ddlstType").val(),
        		Title: $("#txtTitle").val(),
                Content: $("#txtContent").val(),
                Anonymous: $("#chkAnonymous").is(':checked')
            };

            $.ajax({
                url: "../f/post_act.php",
                type: "POST",
                dataType: "json",
                data: postData,
                success: function(result) {
                    if(result.status)
                    {
				        swal({   
				        	title: "發表完成！",   
				        	text: "",   
				        	type: "success",   
				        	showCancelButton: false,      
				        	confirmButtonText: "確定",   
				        	closeOnConfirm: false 
				        }, 
				        function(){
    						edited = false;
		    				window.open("../f/post_list.php", "_self");
				        });
						//txtHeigthChange($('#resContent'));
                    }
                    else
                    {
        				swal("出了一點錯誤...", result.msg, "error"); 
                    }
	        		$("#ddlstType").val(2);
	        		$("#txtTitle").val("");
	                $("#txtContent").val("");
					$("#btnSubmit").prop('disabled', false);
					$("#btnCancel").prop('disabled', false);
                },
                error: function() {
    				swal("出了一點錯誤...", "系統似乎有些小問題，請稍後再試。", "error"); 
                },
            });
	    }
	    else
	    {
			swal("oh!", "您尚未輸入標題或內容哦。", "error"); 
	    }
    });

	$(document).on('keydown keyup', '#txtContent', function(e){
		CheckPost();
    });

	$(document).on('change', '#ddlstType', function(e){
		if($("#ddlstType").val() == 3)
		{
			$("#chkAnonymous").prop('checked', true);
			$("#chkAnonymous").prop('disabled', true);
			$("#lbAnonymous").html("&nbsp;分類若為「靠北」則強制匿名發表");
		}
		else
		{
			$("#chkAnonymous").prop('checked', false);
			$("#chkAnonymous").prop('disabled', false);
			$("#lbAnonymous").html("&nbsp;匿名發表（隱藏系級和性別）");
		}
    });

    function CheckPost(){
		var txtLength = 0;

		if($("#txtContent").val().length == undefined || $("#txtContent").val().length == 0)
		{
			txtLength = 0;
		}
		else
		{
			txtLength = $("#txtContent").val().length;
		}

    	if(txtLength > 12000){
            $('#lbSubmit').html("<div class='alert alert-danger'>");
            $('#lbSubmit > .alert-danger').append("&nbsp;<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>&nbsp;&nbsp;<strong>您的評論字數太多囉... (您需要刪除 " + (txtLength - 12000) + " 個字)</strong>");
            $('#lbSubmit > .alert-danger').append('</div>');
            $("#btnSubmit").attr('disabled', 'disabled');  
    	}
    	else
    	{
    		$('#lbSubmit').html("");
            $("#btnSubmit").removeAttr('disabled');        
    	}
    }

    </script>

</body>
</html>