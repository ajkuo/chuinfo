<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php

	$db_tool = new DB_Tool;
	$db = &$db_tool->get_DB();
	$PID = ( isset($_GET['p']) ) ? input_filter($_GET['p']) : '';

	if(empty($PID))
	{
		turn_to_url('/f/post_list.php');
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

		.no-comment{
			color: rgb(75, 75, 255);
			font-weight: bold;
			padding-top: 10px;
			margin-bottom: 30px;
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


		#divPostContent{
			word-wrap: break-word;
		}

		#divPostTitle{
			padding: 15px 0px;
		}

		#lbPostTitle, #lbPostType{
			font-weight: bold;
			padding: 0px;
			margin-top: 10px;
		}

		#divPostInfoBottom{
			margin-bottom: 30px;
		}

		#divPostAuthorFunc{
			float:right;
			margin-top:10px;
		}

		#divPostFunc{
			margin-top: 60px;
		}

		#divPostEditFunc{
			margin-top: 10px;
			text-align: right;
		}

		#divErrorMsg{
			margin:30px;
			text-align: center;
			font-size: large;
		}

		.fa-4{
			font-size: 2em;
		}

		img.postImg, img.resImg{
			max-width: 100%;
			padding: 5px;
		}

	</style>
    
    <?php echo $WEB_HJS; ?>
    <?php echo $WEB_AD; ?>s
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
					<div class="col-sm-7" id="divAllContent">
						<div id="divPostBase">
							<div id="divPostInfoTop">
								<div id="divPostTitle"></div>
							</div>
							<div id="divPostAuthorFunc"></div>
							<div id="divPostInfoBottom">
								<h4 id="lbPostAuthor"></h4>
								<h5 id="lbPostTime"></h5>
							</div>
							<div id="divPostContent">
								<p id="lbPostContent"></p>
							</div>
							<div id="divPostFunc"></div>
						</div>
						<hr>
						<div class="form-group">
							<h3>
								發表回應
							</h3>
							<textarea rows="6" id="resContent" class="form-control" placeholder="嗨！<?php echo strtoupper($_SESSION['SID']); ?> 你怎麼看？分享一下嘛！記得要注意禮貌唷。"></textarea>
						</div>
						<div id="lbSend"></div>
						<button class="btn btn-default" id="btnSend">
							送　出
						</button>&nbsp;&nbsp;
		                <label>
		                	<input type="checkbox" id="chkAnonymous">&nbsp;匿名回應
		                </label>
						<hr>
						<h3>文章回應 (<span id="lbTotalComment"></span>)</h3>
						<div id="std-comment"></div>
						<hr>
					</div>
					<div class="col-sm-3 hidden-xs">
						<div class="fb-page" data-href="https://www.facebook.com/chuinfo/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/chuinfo/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/chuinfo/">中華大學資訊網</a></blockquote></div>
						<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-6673389364850859" data-ad-slot="1278449324" data-ad-format="auto"></ins>
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
	
	<link rel='stylesheet' href='../css/jstarbox.css'>

    <?php echo $WEB_FJS; ?>
    <script type="text/javascript" src="../js/jquery.endless-scroll.js"></script>
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

	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>

	<script src="../js/jstarbox.js"></script>
    <script type="text/javascript">  
    	var PID = <?php echo $PID; ?>;
    	var SID = <?php echo $_SESSION['UserNo']; ?>;
    	var clickEdit_Post = false;
    	var clickEdit = false;
    	var CurrentPostId = 0;
    	var CurrentResId = 0;
    	var CurrentContent = "";
    	var CurrentContent_Post = "";
    	var Total_SN = 30;
    	var FinalRes = false;


	    function ShowPost(result){
			if(result.status == 1)
			{
				var Content = result.Content.replace(/\r?\n/g, "<br>").trim();
				$("#divPostTitle").html("<h5 id='lbPostType'></h5><h3 id='lbPostTitle'></h3>");
				$("#lbPostType").html("【" + result.Type + "】");
				<?php
					if($_SESSION['Permission'] >= 200)
					{	
				?>
						if(result.del_status == 1)
						{
							$("#lbPostType").append("<span style='color: red;'>(本文章已被刪除，此為歷史模式)</span>");
						}
				<?php
					}
				?>
				$("#lbPostTitle").html(result.Title);
			 
				$('#lbTotalComment').html(result.TotalComment);
				<?php
					if($_SESSION['Permission'] >= 100)
					{
				?>
					if(result.Author == 1)
					{
						$("#divPostAuthorFunc").html("<button class='comment-btn post-btn-edit' id='post-btn-edit-" + PID + "'>編輯</button><button class='comment-btn post-btn-del' id='post-btn-del-" + PID + "'>刪除</button>")
					}
					else
					{
						$("#divPostAuthorFunc").html("<button class='comment-btn manage-btn-del' id='manage-btn-del-" + PID + "'>刪除</button>");
					}


				<?php
					} else{
				?>
					if(result.Author == 1)
					{
						$("#divPostAuthorFunc").html("<button class='comment-btn post-btn-edit' id='post-btn-edit-" + PID + "'>編輯</button><button class='comment-btn post-btn-del' id='post-btn-del-" + PID + "'>刪除</button>");
					}
				<?php
					}
				?>

				if(result.Permission >= 100)
				{
					$("#lbPostAuthor").html(result.Gender);
				}
				else if(result.Anonymous == 0)
				{
					$("#lbPostAuthor").html(result.DeptName + " " + result.Gender);
				} 
				else
				{
					$("#lbPostAuthor").html("匿名");
				}
				<?php
					if($_SESSION['Permission'] >= 200)
					{	
				?>
					$("#lbPostAuthor").append(" (" + result.SID + ")");
				<?php
					}
				?>
				$("#lbPostTime").html(result.PostTime);
				$("#divPostContent").html("<p id='lbPostContent'></p>");
				$("#lbPostContent").html(Content);
		
		    	$.ajax({
		    		url: "post_like.php",
		    		type: "POST",
		    		dataType: "json",
		    		data: {PostId: PID, SID: SID},
		    		success: function(result){
						SetPostLike(result);
					}
				});
		    }
		    else
		    {
		    	$("#divAllContent").html("<div id='divErrorMsg'></div>");
		    	$("#divErrorMsg").html("<i class='fa fa-exclamation-triangle fa-4' aria-hidden='true'></i><br><br>" + result.msg);
		    }
		}


    function ShowComment(result){
		if(result.length == 0)
		{
    		if(Total_SN <= 30)
    		{
				$('#std-comment').html("<div class='no-comment'><i class='fa fa-commenting-o' style='font-size:30px;'></i>&nbsp;&nbsp;&nbsp;目前尚無任何回應，趕快來搶頭香吧！</div>");
			}

			FinalRes = true;
		}
		else
		{
    		if(Total_SN <= 30)
    		{
				$('#std-comment').html('');
			}

			for(var i=0; i < result.length; i++)
			{
				var postId = result[i].PostId;
				var Name = "comment-all-" + postId;
				var Content = result[i].Content.replace(/\r?\n/g, "<br>");
				Content = Content.trim();

				$('#std-comment').append("<div class='comment-all' id='" + Name + "'></div>");

				if(result[i].flag == 1)
				{
    				$('#std-comment > #' + Name).append("<div class='comment-info-top'><span class='comment-floor'>" + result[i].Floor + "F</span></span>&nbsp;-&nbsp;<span>某位同學</span></div>");
					$('#std-comment > #' + Name).append("<div class='comment-info-bottom text-muted'></div>");
					$('#std-comment > #' + Name + ' > .comment-info-bottom').html("<span class='comment-date'>" + result[i].PostTime + "</span>");
				}
				else
				{
				<?php
					if($_SESSION['Permission'] >= 100)
					{
				?>
					if(result[i].Author == 1)
					{
		    				$('#std-comment > #' + Name).html("<div class='comment-info-func'><button class='comment-btn comment-btn-edit' id='comment-btn-edit-" + postId + "'>編輯</button><button class='comment-btn comment-btn-del' id='comment-btn-del-" + postId + "'>刪除</button></div>");
					}
					else
					{
	    				$('#std-comment > #' + Name).html("<div class='comment-info-func'><button class='comment-btn manage-comment-btn-del' id='manage-comment-btn-del-" + postId + "'>刪除</button></div>");
					}
				<?php
					} else{
				?>
		    			if(result[i].Author == 1)
		    			{
		    				$('#std-comment > #' + Name).html("<div class='comment-info-func'><button class='comment-btn comment-btn-edit' id='comment-btn-edit-" + postId + "'>編輯</button><button class='comment-btn comment-btn-del' id='comment-btn-del-" + postId + "'>刪除</button></div>");
		    			}
		    			else
		    			{
		    				$('#std-comment > #' + Name).html("");
		    			}
				<?php
					}
				?>

    				$('#std-comment > #' + Name).append("<div class='comment-info-top'><span class='comment-floor'>" + result[i].Floor + "F</span>&nbsp;-&nbsp;<span class='comment-year'>" + result[i].Year + "</span><span class='comment-gender'>" + result[i].Gender + "</span></div>");
    				$('#std-comment > #' + Name).append("<div class='comment-info-bottom text-muted'></div>");

	    			if(result[i].Anonymous == 0)
	    			{
	    				$('#std-comment > #' + Name + ' > .comment-info-bottom').html("<span class='comment-date'>" + result[i].PostTime + "</span>&nbsp;<span class='comment-from'>from</span>&nbsp;<span class='comment-dept'>" + result[i].DeptName + "</span>");
	    			}
	    			else
	    			{
						$('#std-comment > #' + Name + ' > .comment-info-bottom').html("<span class='comment-date'>" + result[i].PostTime + "</span>");
	    			}
	    		}
    			$('#std-comment > #' + Name).append("<div class='comment-content' id='comment-content-" + postId + "'>" + Content + "</div>");
			}
		}
	}

    </script>
	<script src="../js/post.js"></script>
<?php
	if($_SESSION['Permission'] >= 100)
	{
?>
    <script type="text/javascript">		
		$(document).on('click', '.manage-btn-del', function(e){
	    	var postId = this.id.replace("manage-btn-del-", "");
	    	if(postId == PID)
	    	{
		    	swal({   
		    		title: "確定要刪除此篇文章嗎？",   
		    		text: "※ 注意：刪除文章將會關閉此討論串的回應功能。",   
		    		type: "warning",   
		    		showCancelButton: true,   
		    		confirmButtonColor: "#DD6B55",   
		    		confirmButtonText: "確定刪除",   
		    		cancelButtonText: "取　　消",   
		    		closeOnConfirm: false,
				}, 
				function(){   
        			swal.disableButtons(); 
					closeOnConfirm: false; 
					$.ajax({
		                url: "../f/m_post_del_act.php",
		                type: "POST",
		                dataType: "json",
		                data: { PostId: postId, SID: SID },
		                success: function(result) {
		                    if(result.status)
		                    {
		                    	swal({   
						    		title: "成功刪除！",   
						    		text: "已刪除此篇文章。",   
						    		type: "success",   
						    		showCancelButton: false,   
						    		confirmButtonColor: "#DD6B55",   
						    		confirmButtonText: "確定", 
						    		closeOnConfirm: false,
								}, 
								function(){  
									window.open("../f/post_list.php", "_self");
								});
		                    }
		                    else
		                    {
		        				swal("出了一點錯誤...", result.msg, "error");
		                    }
		                },
		                error: function() {
		    				swal("出了一點錯誤...", "系統似乎有些小問題，請稍後再試。", "error"); 
		                },
		            });   
				});
		    }
	    });

	$(document).on('click', '.manage-comment-btn-del', function(e){
    	var ResId = this.id.replace("manage-comment-btn-del-", "");
    	swal({   
    		title: "確定要刪除此篇回應嗎？",   
    		text: "※ 請注意：刪除後即無法復原該回應。",   
    		type: "warning",   
    		showCancelButton: true,   
    		confirmButtonColor: "#DD6B55",   
    		confirmButtonText: "確定刪除",   
    		cancelButtonText: "取　　消",   
    		closeOnConfirm: false,
		}, 
		function(){
        	swal.disableButtons();    
			closeOnConfirm: false; 
			$.ajax({
                url: "../f/m_post_res_del_act.php",
                type: "POST",
                dataType: "json",
                data: { SID: SID, PostId: PID, ResId: ResId },
                success: function(result) {
                    if(result.status)
                    {
						swal("成功刪除！", "已經順利刪除該回應。", "success");
    					GetPost();
                    }
                    else
                    {
        				swal("出了一點錯誤...", result.msg, "error");
                    }
                },
                error: function() {
    				swal("出了一點錯誤...", "系統似乎有些小問題，請稍後再試。", "error"); 
                },
            });   
		});
    });
	</script>
<?php
	}
?>
</body>
</html>


