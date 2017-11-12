<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php
	$db_tool = new DB_Tool;
	$db = &$db_tool->get_DB();

	$Permission = $_SESSION['Permission'];

	$typeCode = "all";
	if(isset($_GET['t']) && !empty($_GET['t']))
	{
		$typeCode = input_filter($_GET['t']);
		$exist = $db_tool -> get_value('forum_type', 'count(*)', "del_status = 0 AND Code = ?", array($typeCode));
		if($exist == 0)
		{
			$typeCode = "all";
			$typeName = "全部";
		}
		else
		{
			$typeName = $db_tool -> get_value('forum_type', 'Name', "del_status = 0 AND Code = ?", array($typeCode));
		}
	}

	$latest = 'false';
	if(isset($_GET['latest']) && !empty($_GET['latest']) && $_GET['latest'] == 'true')
	{
		$latest = 'true';
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
	<?php
		$sql = "SELECT Name, Code FROM forum_type WHERE del_status = 0 ORDER BY Sort ASC;";
		$std = $db->prepare($sql);
		$std->execute ();
		$type = $std -> fetchAll(PDO::FETCH_ASSOC);
	?>
	<div class="wrapper">
		<section id="all_content">
			<div class="container">
				<div class="row">
					<div class="col-sm-2 col-md-2 col-lg-2 hidden-xs text-right">
						<ul class="pmenu" style="font-size: large; margin-top: 15px;">
						<?php
							foreach($type as $data): 
						?>
							<li><a class="btnMenu" id="pmenu-<?php echo $data['Code']; ?>"><?php echo $data['Name']; ?></a></li>
						<?php
							endforeach;
						?>
						</ul>
					</div>
					<div class="col-sm-7 col-md-7 col-lg-7 col-xs-12">
						<div class="row">
							<div class="col-xs-4 col-ms-12">
								<h1 class="hidden-xs" id="txtTypeTitle">全部</h1>
								<div class="btn-group hidden-sm hidden-md hidden-lg">
									<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">&nbsp;<span id="txtTypeMenuTitle">全部</span>&nbsp;&nbsp;&nbsp;<span class="caret"></span>
									</button>
									<ul class="dropdown-menu" role="menu">
										<?php
											foreach($type as $data): 
										?>
											<li><a href="#" class="btnMenu" id="btnmenu-<?php echo $data['Code']; ?>"><?php echo $data['Name']; ?></a></li>
										<?php
											endforeach;
										?>	
									</ul>
								</div>
							</div>
							<div class="col-xs-8 text-right hidden-sm hidden-md hidden-lg" style="text-align: right; float: right;">
							<?php if($Permission >= 100){ ?>
								<a href="#">
									<button type="button" class="btn btn-default" id="btnSticky_xs">頂</button>
								</a>
								<a href="#">
									<button type="button" class="btn btn-default" id="btnMark_xs">標</button>
								</a>
								<a href="#">
									<button type="button" class="btn btn-danger" id="btnDel_xs">刪</button>
								</a>
								<a href="new_post.php">
									<button type="button" class="btn btn-info">新文</button>
								</a>
							<?php } else { ?>
								<a href="new_post.php">
									<button type="button" class="btn btn-info">發表新文章</button>
								</a>
							<?php } ?>
							</div>
						</div>
						<div class="row div-TopBar">
							<div class="col-lg-12">
								<div class="div-btnNewPost-sm" style="float: right;">
									<?php if($Permission >= 100){ ?>
										<a href="#">
											<button type="button" class="btn btn-default hidden-xs" id="btnSticky">置頂</button>
										</a>
										<a href="#">
											<button type="button" class="btn btn-default hidden-xs" id="btnMark">標記</button>
										</a>
										<a href="#">
											<button type="button" class="btn btn-danger hidden-xs" id="btnDel">刪除</button>
										</a>
									<?php } ?>
									<a href="new_post.php">
										<button type="button" class="btn btn-info hidden-xs">發表新文章</button>
									</a>
								</div>
								<div class="div-SortBy" style="padding:15px;">
									<ul class="nav nav-tabs">
										<li id="vwPopular" class="active">
											<a href="#" class="linkContetView" id="linkPopular">熱門內容</a>
										</li>
										<li id="vwLatest">
											<a href="#"  class="linkContetView" id="linkLatest">最新內容</a>
										</li>
									<?php if($Permission >= 100){ ?>
										<li id="vwMark">
											<a href="#"  class="linkContetView" id="linkLatest">已標記</a>
										</li>
									<?php } ?>
									</ul>
								</div>
							</div>
						</div>
						<div class="row" id="divAllPost"></div>
			            <div id="navigation" align="center">  
				            <a href="user/list?page=1"></a>
				        </div>  
					</div>
					<div class="col-sm-3 col-md-3 col-lg-3 hidden-xs">
						<div class="fb-page" data-href="https://www.facebook.com/chuinfo/" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/chuinfo/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/chuinfo/">中華大學資訊網</a></blockquote></div>
						<ins class="adsbygoogle" style="display:block" data-ad-client="ca-pub-6673389364850859" data-ad-slot="1278449324" data-ad-format="auto"></ins>
					</div>
				</div>
			</div>
		</section>
	</div>

    <?php echo $WEB_FCSS; ?>
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({
	      appId      : 'APP_ID',
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
    <?php echo $WEB_FJS; ?>
    <script type="text/javascript" src="../js/jquery.endless-scroll.js"></script>
    <script type="text/javascript">    	
    	var TypeCode = '<?php echo $typeCode; ?>';
    	var Latest = '<?php echo $latest; ?>';
    	var Total_SN = 30;
		var Total_Post = 0;
    	var FirstScroll = true;

	    $(document).ready(function(){
	    	if(localStorage.getItem('forum_TypeCode') && localStorage.getItem('forum_TypeTime'))
	    	{
	    		if(((new Date() - new Date(localStorage.getItem('forum_TypeTime'))) / 3600000) < 1)
	    		{
	    			TypeCode = localStorage.getItem('forum_TypeCode');
	    		}
	    		else
	    		{
                	localStorage.removeItem('forum_TypeCode');
                	localStorage.removeItem('forum_LinkType');
                	localStorage.removeItem('forum_TypeTime');
	    		}
	    	}
	    	if(localStorage.getItem('forum_LinkType'))
	    	{
	    		ChangeView(localStorage.getItem('forum_LinkType'));
	    	}
			else if(Latest == 'true')
			{
				ChangeView('latest');
			}
			else
			{	    				
				ChangeView('popular');
			}
		});

		$(document).endlessScroll({
			  fireOnce: false,
       		inflowPixels: 100,
       		ceaseFireOnEmpty: false,
			callback: function(i){
          		i = (i + 1) * 30;
		    	
		    	if(localStorage.getItem('forum_LinkType') == 'latest')
		    	{
					GetPosts(TypeCode, 'true', i);
		    	}
		    	else
		    	{
	    			GetPosts(TypeCode, 'false', i);
		    	}

			},
			resetCounter: function(){
				if(FirstScroll)
				{
					FirstScroll = false;
					return true;
				}
			}
		});

	    function GetPosts(type, latest, SN){
	    	type = type || 'all';
	    	latest = latest || 'false';
	    	SN = SN || 30;
	    	Total_SN = SN;

	    	$.ajax({
	    		url: "posts.php",
	    		type: "GET",
	    		dataType: "json",
	    		data: {
	    			t: type,
	    			latest: latest,
	    			SN: SN
	    		},
	    		success: function(result){
	    			ShowPosts(result);
	    		}
			});	    	
	    }

	    function ShowPosts(result) {
        	if(result.length == 0)
        	{
        		if(Total_SN <= 30)
        		{
        			$('#divAllPost').html('<div id="divEmptyMsg"><i class="fa fa-paper-plane"></i>&nbsp;&nbsp;目前沒有任何文章，趕快搶個頭香吧！</div>');
        		}
        	}
        	else
        	{
        		if(Total_SN <= 30)
        		{
        			$('#divAllPost').html("<table class='table' id='tbPost'><thead><tr><?php if($Permission >= 100){ echo "<th><i class='fa fa-check-square-o'></i></th>"; } ?><th class='text-left'><i class='fa fa-thumbs-o-up text-center'></i></th><th class='text-left'><i class='fa fa-comment-o text-center'></i></th><th class='text-left'>標題</th><th class='text-left'>作者</th><th class='text-left hidden-xs hidden-sm'>日期</th></tr></thead><tbody id='tbPosts'></tbody>");
				}

    			for(var i = 0; i < result.length; i++)
    			{
					var Name = "tr-posts-" + result[i].PostId;
					var TotalLike = "";

					if(result[i].TotalLike >= 1000)
					{
						TotalLike = "<i class='fa fa-rocket'></i>";
					}
					else
					{ 
						TotalLike = result[i].TotalLike;
					}

    				if(i % 2 == 0)
    				{
    					$('#tbPosts').append("<tr id='" + Name + "' class='active tr-act'></tr>");
    				}
    				else
    				{
    					$("#tbPosts").append("<tr id='" + Name + "'></tr>");
    				}

    				if(result[i].Sticky > 0)
    				{
    					$('#tbPosts > #' + Name).removeClass("active");
						$('#tbPosts > #' + Name).css("background-color", "#e2f5cd");
    					$('#tbPosts > #' + Name).addClass("tr-sticky");
    				}

    				$('#tbPosts > #' + Name).html("");

    				<?php
    					if($Permission >= 100)
    					{
    				?>
    					var chkPost = "<td><input name='chkPost' class='chkPost' id='chkPost-" + result[i].PostId + "' type='checkbox'></input></td>";
    					$('#tbPosts > #' + Name).html(chkPost);
    				<?php } ?>

    				$('#tbPosts > #' + Name).append("<td>" + TotalLike + "</td>" + "<td>" + result[i].TotalComment + "</td>");

					$('#tbPosts > #' + Name).append("<td><a class='ptitle' href='../f/post.php?p=" + result[i].PostId + "'>" + result[i].Title + "</a></td>");

					var PostTime = new Date(result[i].PostTime);
					PostTime = PostTime.getFullYear() + "-" + (PostTime.getMonth() + 1) +  "-" + PostTime.getDate() + " " + ("0" + PostTime.getHours()).slice(-2) + ":" + ("0" + PostTime.getMinutes()).slice(-2);

    				$('#tbPosts > #' + Name).append("<td>" + result[i].Author + "</td><td class='hidden-xs hidden-sm'>" + PostTime + "</td>");

    				Total_Post = Total_Post + 1;
    			}
        	}
        };

		$(document).on('click', '.btnMenu', function(e){
			TypeCode = this.id.replace("pmenu-", "");
			TypeCode = TypeCode.replace("btnmenu-", "");
            localStorage.setItem('forum_TypeCode', TypeCode);
            localStorage.setItem('forum_TypeTime', new Date());
            localStorage.setItem('forum_LinkType', "popular");
			ChangeView('popular');
		});

		$(document).on('click', '.linkContetView', function(e){
			var linkType = this.id;
            localStorage.setItem('forum_LinkType', linkType.replace("link", "").toLowerCase());
			if(linkType == 'linkPopular')
			{
				if(!$("#vwPopular").hasClass("active"))
				{
					ChangeView('popular');
				}
			}
			else if(linkType == 'linkLatest')
			{
				if(!$("#vwLatest").hasClass("active"))
				{
					ChangeView('latest');
				}
			}
	    });

	    function ChangeView(linkType){
			
			Total_Post = 0;
	    	FirstScroll = true;

	    	linkType = linkType || 'popular';

	    	if(linkType == 'latest')
	    	{
				$("#vwLatest").addClass("active");
				$("#vwPopular").removeClass("active");
				GetPosts(TypeCode, 'true');
	    	}
	    	else
	    	{
    			$("#vwPopular").addClass("active");
    			$("#vwLatest").removeClass("active");
    			GetPosts(TypeCode);
	    	}

			$("#txtTypeTitle").html($("#pmenu-" + TypeCode).text());
			$("#txtTypeMenuTitle").html("&nbsp;" + $("#pmenu-" + TypeCode).text() + "&nbsp;&nbsp;&nbsp;");
	    }
    </script>
<?php
	if($Permission >= 100)
	{
?>
	<script type="text/javascript">
		var SID = <?php echo $_SESSION['UserNo']; ?>;
	</script>

	<script type="text/javascript">
		$(document).on('click', '.chkPost', function(e){
			var PostId = this.id.replace("chkPost-", "");

			if($("#" + this.id).prop('checked'))
			{
				if($("#tr-posts-" + PostId).hasClass("tr-act"))
				{
    				$("#tr-posts-" + PostId).removeClass("active");
				}

				$("#tr-posts-" + PostId).css("background-color", "#ffb4b4");
			}
			else
			{
				if($("#tr-posts-" + PostId).hasClass("tr-sticky"))
				{
					$("#tr-posts-" + PostId).css("background-color", "#e2f5cd");
				}
				else if($("#tr-posts-" + PostId).hasClass("tr-act"))
				{
    				$("#tr-posts-" + PostId).addClass("active");
				}
				else
				{
					$("#tr-posts-" + PostId).css("background-color", "white");
				}
			}
		});

		$(document).on('click', '#btnDel, #btnDel_xs', function(e){
			var checkedPostId = new Array();
			$("input[name='chkPost']:checked").each(function(i) {
		    	checkedPostId[i] = this.id.replace("chkPost-", "");
		    });

			if(checkedPostId.length == 0)
			{
				alert("\n\n尚未選取任何文章。\n\n");
			}
			else
			{
		        swal({   
		        	title: "確定刪除？",   
		        	text: "若按下確定，將會進行刪除動作。",   
		        	type: "warning",   
		        	showCancelButton: true,   
		        	cancelButtonText: "取　　消",    
		        	confirmButtonText: "確　　定",   
		        	closeOnConfirm: false 
		        }, 
		        function(){
			    	$.ajax({
			    		url: "m_post_del_act.php",
			    		type: "POST",
			    		dataType: "json",
			    		data: {
			    			SID: SID,
			    			PostId: checkedPostId
			    		},
			    		success: function(result){
			    			if(result.status == 1)
			    			{
			    				swal("完成", "您所選取的文章已刪除。", "success");
						    	if(localStorage.getItem('forum_LinkType') == 'latest')
						    	{
									GetPosts(TypeCode, 'true');
						    	}
						    	else
						    	{
					    			GetPosts(TypeCode, 'false');
						    	}
			    			}
			    		}
					});	
		        });  
			}
		});

		$(document).on('click', '#btnSticky, #btnSticky_xs', function(e){
			var checkedPostId = new Array();
			$("input[name='chkPost']:checked").each(function(i) {
		    	checkedPostId[i] = this.id.replace("chkPost-", "");
		    });

			if(checkedPostId.length == 0)
			{
				alert("\n\n尚未選取任何文章。\n\n");
			}
			else
			{
				swal({
				   title: "<i class='fa fa-thumb-tack'></i><br>文章置頂",
				   text: "請輸入置頂模式(1:取消置頂、2:分類置頂、3:全區置頂)<br><br>",
				   type: "input",   
				   showCancelButton: true, 
				   html: true,   
				   closeOnConfirm: false,
				   cancelButtonText: "取　消",
				   confirmButtonText: "送　出",
				   animation: "slide-from-top",   
				   inputPlaceholder: "1-取消置頂、2-分類置頂、3-全區置頂" 
					}, 
				   function(inputValue){   
				        if (inputValue === false) 
				           return false;      
				       	if (inputValue === "") 
				       	{     
				           swal.showInputError("請輸入置頂模式。");     
				           return false;
				        }      
				       	if (inputValue === "1" || inputValue === "2" || inputValue === "3") 
				       	{
				           swal({   
					        	title: "確定置頂？",   
					        	text: "確定要將這幾篇文章置頂嗎？",   
					        	type: "warning",   
					        	showCancelButton: true,   
					        	cancelButtonText: "取　　消",    
					        	confirmButtonText: "確　　定",   
					        	closeOnConfirm: false 
					        }, 
					        function(){
						    	$.ajax({
						    		url: "../f/m_post_sticky_act.php",
						    		type: "POST",
						    		dataType: "json",
						    		data: {
						    			SID: SID,
						    			PostId: checkedPostId,
						    			Sticky: inputValue
						    		},
						    		success: function(result){
						    			if(result.status == 1)
						    			{
						    				swal("完成", "您所選取的文章置頂模式已變更。", "success");
									    	if(localStorage.getItem('forum_LinkType') == 'latest')
									    	{
												GetPosts(TypeCode, 'true');
									    	}
									    	else
									    	{
								    			GetPosts(TypeCode, 'false');
									    	}
						    			}
						    		}
								});	
					        });   
				        }
				        else
				        {     
				           swal.showInputError("您輸入的內容有誤。");     
				           return false;   
				        }       
				});
			}
		});
	</script>
<?php 
	}
?>

</body>
</html>