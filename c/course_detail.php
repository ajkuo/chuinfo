<?php require_once "../backend/chu_tool.php"; ?>
<?php check_to_login(); ?>
<?php require_once "../templates/base.php"; ?>
<?php

	$db_tool = new DB_Tool;
	$db = &$db_tool->get_DB();
	$CID = ( isset($_GET['id']) ) ? input_filter($_GET['id']) : '';

	if(empty($CID))
	{
		turn_to_url('/c/course_list.php');
	}
	else
	{				
		$count = $db_tool->get_value('course_group', 'count(*)', 'No = ?', array($CID));
		
		if($count == 1)
		{
			course_detail_log($_SESSION['UserNo'], $CID);

			$sql = "SELECT g.No as GroupNo, g.Name as Name, g.Teacher as Teacher, g.Credit as Credit, g.CourseTime as Hours, c.Content as Content, c.Classroom as Classroom, c.StdOfScore as StdOfScore FROM course_group g, course_history c WHERE g.No = c.GroupNo AND g.No = ? ;";
			$std = $db->prepare($sql);
			$std->execute (array($CID));
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
                <a class="navbar-brand" href="../index.php"><?php echo  $WEB_TITLE; ?></a>
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
					<div class="col-md-8">
						<h3>
							<?php echo $data['Name']; ?>
						</h3>
						<h4>授課教師：<?php echo $data['Teacher']; ?></h4>
						<h4>學分數：<?php echo $data['Credit']; ?>&nbsp;學分</h4>
						<h4>上課地點：<?php echo $data['Classroom']; ?></h4>
						<?php
							$liked = $db_tool->get_value('course_like', 'count(*)', "sid = ? AND GroupNo = ? AND del_status = 0;", array($_SESSION['SID'], $CID));
						?>
						<button type="button" class="btn <?php if($liked == 0) echo "btn-info"; else echo "btn-default"; ?> btn-sm" id="btnLike"><i class="fa <?php if($liked == 0) echo "fa-thumbs-o-up"; else echo "fa-check-square-o"; ?> text-center"></i>&nbsp;<?php if($liked == 0) echo "值得推薦"; else echo "已推薦"; ?></button>
						<hr>
						<h4>課程概述：</h4>
						<p><?php echo $data['Content']; ?></p>
						<h4>評分標準：</h4>
						<p><?php echo $data['StdOfScore']; ?></p>
						<ins class="adsbygoogle"
						     style="display:block"
						     data-ad-client="ca-pub-6673389364850859"
						     data-ad-slot="9022042128"
						     data-ad-format="auto"></ins>
						 <div class="hidden-md hidden-lg">
							<hr>
						 	<h3>開課紀錄</h3>
							<table class="table">
								<thead>
									<tr>
										<th>
											開課學年
										</th>
										<th>
											學期
										</th>
										<th>
											課號
										</th>
										<th>
											上課時間
										</th>
										<th>
											修課人數
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = "SELECT CID, Year, Term, ClassTime, Member, MaxMember FROM course_history WHERE GroupNo = " . $data['GroupNo'] . " ORDER BY Year DESC, Term DESC, CID ASC;";
										$std = $db->prepare($sql);
										$std->execute();
										$rc = $std -> rowCount();
										$rs = $std -> fetchAll();
										$count = 0;
										foreach($rs as $ch):
											$count += 1;
									?>
									<tr <?php if($count % 2 == 1){ echo "class='active'"; } ?>>
										<td>
											<?php echo $ch['Year']; ?>
										</td>
										<td>
											<?php echo $ch['Term']; ?>
										</td>
										<td>
											<?php echo $ch['CID']; ?>
										</td>
										<td>
											<?php echo $ch['ClassTime']; ?>
										</td>
										<td>
											<?php echo $ch['Member'] . '/' . $ch['MaxMember']; ?>
										</td>
									</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						 </div>
						<hr>
						<h3>
							評價課程
						</h3>
						<div class="row gvie-score">
						<?php 
							$sql = "SELECT TeacherScore, CourseScore, GradeScore, RecommendScore FROM course_score WHERE GroupNo = ? AND UserNo =  ? AND del_status = 0;";
							$std = $db->prepare($sql);
							$std->execute (array($CID, $_SESSION['UserNo']));
							$rc = $std -> rowCount();
							$rs = $std -> fetchAll();
							$st = 0;
							$sc = 0;
							$ss = 0;
							$sr = 0;
							if($rc > 0)
							{
								foreach($rs as $score):
									if($score['TeacherScore'] != 0)
										$st = $score['TeacherScore'] / 5;
									if($score['CourseScore'] != 0)
										$sc = $score['CourseScore'] / 5;
									if($score['GradeScore'] != 0)
										$ss = $score['GradeScore'] / 5;
									if($score['RecommendScore'] != 0)
										$sr = $score['RecommendScore'] / 5;
								endforeach;
							}
							else
							{
								$st = 0;
								$sc = 0;
								$ss = 0;
								$sr = 0;
							}
						?>
							<div class="col-sm-6 col-xs-12" style="margin-top: 10px;">
									<h4 style="display: inline;">教師向度</h4><span> - 教師表現、個性如何？</span>
									<div class="starbox starbox-t autoupdate" id="st" data-start-value ="<?php echo $st; ?>"></div>
							</div>
							<div class="col-sm-6 col-xs-12" style="margin-top: 10px;">
									<h4 style="display: inline;">課程向度</h4><span> - 課程是否紮實、充實？</span>
									<div class="starbox starbox-c autoupdate" id="sc" data-start-value="<?php echo $sc; ?>"></div>
							</div>
							<div class="col-sm-6 col-xs-12" style="margin-top: 10px;">
									<h4 style="display: inline;">分數向度</h4><span> - 點名狀況？好不好過？</span>
									<div class="starbox starbox-s autoupdate" id="ss" data-start-value="<?php echo $ss; ?>"></div>
							</div>
							<div class="col-sm-6 col-xs-12" style="margin-top: 10px;">
									<h4 style="display: inline;">推薦向度</h4><span> - 整體而言，您的推薦程度？</span>
									<div class="starbox starbox-r autoupdate" id="sr" data-start-value="<?php echo $sr; ?>"></div>
							</div>
						</div>
						<hr>
							<div class="form-group">
								<h3>
									發表評論
								</h3>
								<textarea rows="5" id="resContent" class="form-control AutoHeight" placeholder="請注意：評論內容應盡量客觀、冷靜地陳述事實，且不得散播不實言論及攻擊性字眼。（您的內容將以匿名發表，但仍須對自身言論負責）"></textarea>
							</div>
							<div id="lbSend"></div>
							<button class="btn btn-default" id="btnSend">
								送　出
							</button>&nbsp;&nbsp;
					                <label>
					                	<input type="checkbox" id="chkAnonymous">&nbsp;隱藏我的系所名稱
					                </label>
					 <div class="hidden-md hidden-lg">
						<hr>
						<?php
							$sql = "SELECT TeacherScore, CourseScore, GradeScore, RecommendScore, AverageScore, (SELECT CASE WHEN TeacherCount > CourseCount THEN (CASE WHEN TeacherCount > GradeCount THEN (CASE WHEN TeacherCount > RecommendCount THEN TeacherCount ELSE RecommendCount END) ELSE (CASE WHEN GradeCount > RecommendCount THEN GradeCount ELSE RecommendCount END) END) ELSE (CASE WHEN CourseCount > GradeCount THEN (CASE WHEN CourseCount > RecommendCount THEN CourseCount ELSE RecommendCount END) ELSE (CASE WHEN GradeCount > RecommendCount THEN GradeCount ELSE RecommendCount END) END) END as TotalPerson FROM course_group WHERE No = ?) as TotalPerson FROM course_group WHERE No = ?;";
							$std = $db->prepare($sql);
							$std->execute (array($CID, $CID));
							$DataCount = $std -> rowCount();
							$ScoreData = $std -> fetchAll();

							if($DataCount > 0)
							{
								$sc_t = $ScoreData[0]['TeacherScore'];
								$sc_c = $ScoreData[0]['CourseScore'];
								$sc_g = $ScoreData[0]['GradeScore'];
								$sc_r = $ScoreData[0]['RecommendScore'];
								$avg_sc = $ScoreData[0]['AverageScore'];
								$total_person = $ScoreData[0]['TotalPerson'];
							}
							else
							{
								$sc_t = 0;
								$sc_c = 0;
								$sc_g = 0;
								$sc_r = 0;
								$total_person = 0;
								$avg_sc = 0;
							}

							function get_star($score){
								if(!is_numeric($score)) $score = 0;
								$score = round($score, 0);
								switch ($score) {
									case 0:
										return "☆☆☆☆☆";
										break;
									case 1:
										return "★☆☆☆☆";
										break;
									case 2:
										return "★★☆☆☆";
										break;
									case 3:
										return "★★★☆☆";
										break;
									case 4:
										return "★★★★☆";
										break;
									case 5:
										return "★★★★★";
										break;									
									default:
										return "☆☆☆☆☆";
										break;
								}
							}
						?>
						<h3>課程得分</h3>
						<h4>目前有 <?php echo $total_person; ?> 人評分</h4>
						<table class="table">
								<tr>
									<th>評分向度</th>
									<th>項目得分</th>
								</tr>
								<tr>
									<td>
										教師向度
									</td>
									<td>
										<?php echo get_star($sc_t); ?>
									</td>
								</tr>
								<tr class="active">
									<td>
										課程向度
									</td>
									<td>
										<?php echo get_star($sc_c); ?>
									</td>
								</tr>
								<tr>
									<td>
										分數向度
									</td>
									<td>
										<?php echo get_star($sc_g); ?>
									</td>
								</tr>
								<tr class="active">
									<td>
										推薦向度
									</td>
									<td>
										<?php echo get_star($sc_r); ?>
									</td>
								</tr>
								<tr>
									<td>課程總評</td>
									<td>
										<?php 
											$course_comment = "";
											if($avg_sc == 0){
												$course_comment = "尚無評分";
											}else if($avg_sc <= 1.5){
												$course_comment = "風評極差（" . $avg_sc ."分）";
											}else if($avg_sc > 1.5 && $avg_sc <= 2.4){
												$course_comment = "不算太好（" . $avg_sc ."分）";
											}else if($avg_sc > 2.4 && $avg_sc <= 3.3){
												$course_comment = "普普通通（" . $avg_sc ."分）";
											}else if($avg_sc > 3.3 && $avg_sc <= 3.8){
												$course_comment = "還算不錯（" . $avg_sc ."分）";
											}else if($avg_sc > 3.8 && $avg_sc <= 4.3){
												$course_comment = "評價很高（" . $avg_sc ."分）";
											}else if($avg_sc > 4.3){
												$course_comment = "絕對必選（" . $avg_sc ."分）";
											}

											echo $course_comment;
										?>
									</td>
								</tr>
						</table>
						<hr>					 	
					 </div>
						<h3>同學評論</h3>
						<div id="std-comment"></div>
					</div>
					<div class="col-md-4 hidden-xs hidden-sm">
						<hr>
						<h3>課程得分</h3>
						<h4>目前有 <?php echo $total_person; ?> 人評分</h4>
						<table class="table">
								<tr>
									<th>評分向度</th>
									<th>項目得分</th>
								</tr>
								<tr>
									<td>
										教師向度
									</td>
									<td>
										<?php echo get_star($sc_t); ?>
									</td>
								</tr>
								<tr class="active">
									<td>
										課程向度
									</td>
									<td>
										<?php echo get_star($sc_c); ?>
									</td>
								</tr>
								<tr>
									<td>
										分數向度
									</td>
									<td>
										<?php echo get_star($sc_g); ?>
									</td>
								</tr>
								<tr class="active">
									<td>
										推薦向度
									</td>
									<td>
										<?php echo get_star($sc_r); ?>
									</td>
								</tr>
								<tr>
									<td>課程總評</td>
									<td>
										<?php echo $course_comment; ?>
									</td>
								</tr>
						</table>
						<hr>
						<h3>開課紀錄</h3>
						<table class="table">
							<thead>
								<tr>
									<th>
										開課學年
									</th>
									<th>
										學期
									</th>
									<th>
										課號
									</th>
									<th>
										上課時間
									</th>
									<th>
										修課人數
									</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$sql = "SELECT CID, Year, Term, ClassTime, Member, MaxMember FROM course_history WHERE GroupNo = " . $data['GroupNo'] . " ORDER BY Year DESC, Term DESC, CID ASC;";
									$std = $db->prepare($sql);
									$std->execute();
									$rc = $std -> rowCount();
									$rs = $std -> fetchAll();
									$count = 0;
									foreach($rs as $ch):
										$count += 1;
								?>
								<tr <?php if($count % 2 == 1){ echo "class='active'"; } ?>>
									<td>
										<?php echo $ch['Year']; ?>
									</td>
									<td>
										<?php echo $ch['Term']; ?>
									</td>
									<td>
										<?php echo $ch['CID']; ?>
									</td>
									<td>
										<?php echo $ch['ClassTime']; ?>
									</td>
									<td>
										<?php echo $ch['Member'] . '/' . $ch['MaxMember']; ?>
									</td>
								</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
						<hr>
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
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>

	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>
    <script type="text/javascript" src="../js/jquery.endless-scroll.js"></script>
	<script src="../js/jstarbox.js"></script>

    <script type="text/javascript">
    	var CID = '<?php echo $CID; ?>';
    	var SID = '<?php echo $_SESSION['SID']; ?>';
    	var UserNo = '<?php echo $_SESSION['UserNo']; ?>';
    	var Total_SN = 30;
    	var FinalRes = false;

	    function ShowComment(result, SN){
			if(result.length == 0)
			{
	    		if(Total_SN <= 30)
	    		{
					$('#std-comment').html("<div class='no-comment'><i class='fa fa-commenting-o' style='font-size:30px;'></i>&nbsp;&nbsp;&nbsp;目前尚無任何評論，趕快來搶頭香吧！</div>");
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
	<script src="../js/course_detail.js"></script>
<?php
	if($_SESSION['Permission'] >= 100)
	{
?>
    <script type="text/javascript">    	
		$(document).on('click', '.manage-comment-btn-del', function(e){
	    	var postId = this.id.replace("manage-comment-btn-del-", "");
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
	                url: "../c/m_post_res_del_act.php",
	                type: "POST",
	                dataType: "json",
	                data: { SID: UserNo, CID: CID, PostId: postId },
	                success: function(result) {
	                    if(result.status)
	                    {
							swal("成功刪除！", "已經順利刪除該回應。", "success");
	    					
					    	$.ajax({
					    		url: "comment.php",
					    		type: "GET",
					    		dataType: "json",
					    		data: {c: CID, PostId: postId },
					    		success: function(res){
									CurrentContent = res[0].Content.replace(/\r?\n/g, "<br>");
							        $("#std-comment > #comment-all-" + postId +" > #comment-content-" + postId).html(CurrentContent);
							        clickEdit = false;
							        $('#std-comment > #comment-all-' + postId + ' > .comment-info-func').html("");
					    		}
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
	    });
    </script>
<?php
	}
?>
</body>
</html>


