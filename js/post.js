

	    $(document).ready(function(){
	    	GetPost();
		});

		$(document).endlessScroll({
			  fireOnce: false,
       		inflowPixels: 100,
       		ceaseFireOnEmpty: false,
			callback: function(i){
          		i = (i + 1) * 30;
          		GetCommnet(i);
			},
			ceaseFire: function(){
				if(FinalRes)
				{
					return true;
				}
			}
		});

	    function SetPostLike(result){
	    	var LikeText = "";
	    	var DislikeText = "";

			$("#divPostFunc").html("<button type='button' class='btn btn-info btn-sm' id='btnLike'></button>&nbsp;<button type='button' class='btn btn-danger btn-sm ' id='btnDislike'></button>&nbsp;<button type='button' class='btn btn-default btn-sm' id='btnReport'>&nbsp;檢&nbsp;舉&nbsp;<br><i class='fa fa-exclamation-triangle'></i></button>");

	    	if(result.TotalLike < 1000)
	    	{
	    		LikeText = "<i class='fa fa-thumbs-o-up'></i>&nbsp;" + result.TotalLike;
	    	}
	    	else
	    	{
	    		LikeText = "<i class='fa fa-rocket'></i>&nbsp;爆";
	    	}

	    	if(result.TotalDislike < 1000)
	    	{
	    		DislikeText = "<i class='fa fa-thumbs-o-up'></i>&nbsp;" + result.TotalDislike;
	    	}
	    	else
	    	{
	    		DislikeText = "<i class='fa fa-bomb'></i>&nbsp;爆</strong>"; 
	    	}

	    	switch(result.PostLike){
	    		case "0":
		    		$("#btnLike").html(" u 質好文<br>" + LikeText);
					$("#btnDislike").html("廢到有剩<br>" + DislikeText);
		            $("#btnLike").removeAttr('disabled');  
		            $("#btnDislike").removeAttr('disabled');  
					break;
				case "1":
		    		$("#btnLike").html("&nbsp;&nbsp;已說讚&nbsp;&nbsp;<br>" + LikeText);
					$("#btnDislike").html("廢到有剩<br>" + DislikeText);
					$("#btnDislike").attr('disabled', 'disabled');
					break;
				case "2":
		    		$("#btnLike").html(" u 質好文<br>" + LikeText);
					$("#btnDislike").html("&nbsp;&nbsp;已說廢&nbsp;&nbsp;<br>" + DislikeText);
					$("#btnLike").attr('disabled', 'disabled');
					break;
				default:
		    		$("#btnLike").html(" u 質好文<br>" + LikeText);
					$("#btnDislike").html("廢到有剩<br>" + DislikeText);
		            $("#btnLike").removeAttr('disabled');  
		            $("#btnDislike").removeAttr('disabled');  
					break;
	    	}
	    }


		$(document).on('click', '#btnLike', function(e){
	    	$.ajax({
	    		url: "post_like.php",
	    		type: "POST",
	    		dataType: "json",
	    		data: {PostId: PID, SID: SID, PostLike: 1},
	    		success: function(result){
					SetPostLike(result);
				}
			});
		});

		$(document).on('click', '#btnDislike', function(e){
	    	$.ajax({
	    		url: "post_like.php",
	    		type: "POST",
	    		dataType: "json",
	    		data: {PostId: PID, SID: SID, PostLike: 2},
	    		success: function(result){
					SetPostLike(result);
				}
			});
		});

		function GetPost(){
	    	$.ajax({
	    		url: "post_content.php",
	    		type: "GET",
	    		dataType: "json",
	    		data: {p: PID},
	    		success: function(result){
	    			ShowPost(result);
    				GetCommnet();
	    		}
			});
	    }


		$(document).on('click', '.post-btn-edit', function(e){
	    	var postId = this.id.replace("post-btn-edit-", "");

	    	if(postId == PID)
			{
		    	if(!clickEdit_Post){
			    	var postTitle = $("#lbPostTitle").html();
			    	var postType = $("#lbPostType").html().replace("【", "");
			    	postType = postType.replace("】", "");

			    	var len = $('#lbPostContent > .postImg').length;
			    	for(var i = 0; i < len; i++)
			    	{
			    		CurrentContent_Post = $('#lbPostContent > .postImg').eq(0).replaceWith($('#lbPostContent > .postImg').eq(0).attr("src"));
			    	}
		    		CurrentContent_Post = $('#lbPostContent').html().replace(/(<br>)/g, "\n");
		    		$("#divPostTitle").html("");
		    		$("#divPostContent").html("<label for='txtTitle'>文章分類</label><select class='form-control' id='ddlstType'></select><br><label for='txtTitle'>文章標題</label><input class='form-control' placeholder='請記得輸入標題哦。' type='text' id='txtTitle' maxlength='25' value='" + postTitle + "'><br>");
		    		$("#divPostContent").append("<form><label for='txtContent'>文章內容</label><textarea class='form-control AutoHeight' id='txtPostEdit'>"+ CurrentContent_Post.trim() +"</textarea></form>");
					$("#divPostContent").append("<div id='divPostEditFunc'><button class='comment-btn post-btn-edit-cancel' id='post-btn-edit-cancel'>取 消</button><button class='comment-btn post-btn-edit-submit' id='post-btn-edit-submit'>完 成</button></div>");
			    	$("#divPostFunc").html("");
					txtHeigthChange($("#txtPostEdit"));
		            clickEdit_Post = true;
			    	$.ajax({
			    		url: "get_type.php",
			    		type: "GET",
			    		dataType: "json",
			    		success: function(result){			    			
							for(var i=0; i < result.length; i++)
							{
								var str = "<option value='" + result[i].TypeNo + "'>" + result[i].Name + "</option>";
								if(postType == result[i].Name)
								{
									str = "<option value='" + result[i].TypeNo + "' selected>" + result[i].Name + "</option>";
								}
								$("#ddlstType").append(str);
							}
			    		}
					});
		    	}
		    	else{		    		
			        swal({   
			        	title: "確定放棄？",   
			        	text: "若按下確定，您已編輯的文章將會消失哦。",   
			        	type: "warning",   
			        	showCancelButton: true,   
			        	cancelButtonText: "取　　消",    
			        	confirmButtonText: "確定放棄",   
			        	closeOnConfirm: true 
			        }, 
			        function(){
        				swal.disableButtons(); 
		    			GetPost();
			            clickEdit_Post = false;
			            CurrentContent_Post = "";
			        });
		    	}
		    }
	    });

		$(document).on('click', '.post-btn-edit-cancel', function(e){
	    	var postId = PID;

	    	if(clickEdit_Post)
	    	{	
		        swal({   
		        	title: "確定放棄？",   
		        	text: "若按下確定，您已編輯的文章將會消失哦。",   
		        	type: "warning",   
		        	showCancelButton: true,   
		        	cancelButtonText: "取　　消",    
		        	confirmButtonText: "確定放棄",   
		        	closeOnConfirm: true 
		        }, 
		        function(){
        			swal.disableButtons(); 
	    			GetPost();
		            clickEdit_Post = false;
		            CurrentContent_Post = "";
		        });
		    }
		});

		$(document).on('click', '.post-btn-edit-submit', function(e){			
			if(clickEdit_Post)
			{
				var postData = {
	                PostId: PID,
	                SID: SID,
	                Type: $("#ddlstType").val(),
	                Title: $("#txtTitle").val(),
	                Content: $("#txtPostEdit").val(),
	                Edit: true
	            };

	            $("#post-btn-edit-submit").attr('disabled', 'disabled');
	            $("#post-btn-edit-cancel").attr('disabled', 'disabled');

	            $.ajax({
	                url: "../f/post_act.php",
	                type: "POST",
	                dataType: "json",
	                data: postData,
		            success: function(res){
		    			GetPost();
			            clickEdit_Post = false;
			            CurrentContent_Post = "";
			            $("#post-btn-edit-submit").removeAttr('disabled');
			            $("#post-btn-edit-cancel").removeAttr('disabled');
				    }
	    		});
			}
			else
			{
				CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
		        $("#std-comment > #comment-all-" + CurrentPostId +" > #comment-content-" + CurrentPostId).html(CurrentContent);
		        clickEdit = false;
			}
		});


		$(document).on('click', '.post-btn-del', function(e){
	    	var postId = this.id.replace("post-btn-del-", "");
	    	if(postId == PID)
	    	{
		    	swal({   
		    		title: "確定要刪除文章嗎？",   
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
		                url: "../f/post_del_act.php",
		                type: "POST",
		                dataType: "json",
		                data: { PostId: postId, SID: SID },
		                success: function(result) {
		                    if(result.status)
		                    {
		                    	swal({   
						    		title: "成功刪除！",   
						    		text: "希望您仍能持續參與討論哦！",   
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

	$(document).on('keydown keyup change', 'textarea.AutoHeight', function(e){
		txtHeigthChange(this);
    });

    function txtHeigthChange(txtContent){	    	
    	$("textarea.AutoHeight").css("overflow","hidden");
    	$(txtContent).height('0px').height($(txtContent).prop("scrollHeight")+"px"); 
    }

	$(document).on('keydown keyup', '#resContent', function(e){
		CheckResponse(2000, "lbSend", "btnSend");
    });

    function CheckResponse(MaxLength, TextId, BtnId){
		var txtLength = 0;

		if($("#resContent").val().length == undefined || $("#resContent").val().length == 0)
		{
			txtLength = 0;
		}
		else
		{
			txtLength = $("#resContent").val().length;
		}

		var TextId = "#" + TextId;
		var BtnId = "#" + BtnId;

    	if(txtLength > MaxLength){
            $(TextId).html("<div class='alert alert-danger'>");
            $(TextId + ' > .alert-danger').append("<strong>您的字數太多囉... (您需要刪除 " + (txtLength - 2000) + " 個字)</strong>");
            $(TextId + ' > .alert-danger').append('</div>');
            $(BtnId).attr('disabled', 'disabled');  
    	}
    	else
    	{
    		$(TextId).html("");
            $(BtnId).removeAttr('disabled');        
    	}
    }

    $("#btnSend").click(function(e){
    	if($("#resContent").val().trim().length > 0)
    	{
	        swal({   
	        	title: "確定送出？",   
	        	text: "按下確定發表即表示您已同意並遵守<a href='../terms/post_rule.php' target='_blank'>《文章規範》</a>。",   
	        	type: "warning",
	        	html: true,   
	        	showCancelButton: true,   
	        	cancelButtonText: "取　　消",    
	        	confirmButtonText: "確定發表",   
	        	closeOnConfirm: false 
	        }, 
	        function(){
	        	swal.disableButtons();
	        	PostContent = $("#resContent").val();

	            var resData = {
	        		SID: SID,
	        		PostId: PID,
	                Content: PostContent,
	                Anonymous: $("#chkAnonymous").is(':checked')
	            };

	            $.ajax({
	                url: "../f/post_res_act.php",
	                type: "POST",
	                dataType: "json",
	                data: resData,
	                success: function(result) {
	                    if(result.status)
	                    {
	        				swal("成功發表！", "", "success");
	    					GetPost();
	                        $('#resContent').val("");
	                    }
	                    else
	                    {
	        				swal("出了一點錯誤...", result.msg, "error"); 
	                        $('#resContent').val("");
	                    }
	                },
	                error: function() {
	    				swal("出了一點錯誤...", "系統似乎有些小問題，請稍後再試。", "error"); 
	                },
	            });
	        });
	    }
	    else
	    {
			swal("oh!", "您還沒有輸入內容呢。", "error"); 
	    }
    });

    function GetCommnet(SN){    
    	SN = SN || 30;
    	Total_SN = SN;	 

    	$.ajax({
    		url: "post_responses.php",
    		type: "GET",
    		dataType: "json",
    		data: {
				p: PID,
    			SN: SN
    		},
    		success: function(res){
    			ShowComment(res);
    		}
		});
    }

	$(document).on('click', '.comment-btn-del', function(e){
    	var ResId = this.id.replace("comment-btn-del-", "");
    	swal({   
    		title: "確定要刪除回應嗎？",   
    		text: "※ 請注意：刪除後即無法復原您的回應。",   
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
                url: "../f/post_res_del_act.php",
                type: "POST",
                dataType: "json",
                data: { SID: SID, PostId: PID, ResId: ResId },
                success: function(result) {
                    if(result.status)
                    {
						swal("成功刪除！", "歡迎繼續參與討論哦！", "success");
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

	$(document).on('click', '.comment-btn-edit', function(e){
    	var postId = this.id
    	postId = postId.replace("comment-btn-edit-", "");
    	var txtName = 'comment-txt-edit-' + postId;

    	var len = $('#comment-content-' + postId + ' > .resImg').length;
    	for(var i = 0; i < len; i++)
    	{
    		CurrentContent_Post = $('#comment-content-' + postId + ' > .resImg').eq(0).replaceWith($('#comment-content-' + postId + ' > .resImg').eq(0).attr("src"));
    	}
    	
    	if((CurrentResId != postId) && clickEdit)
    	{
    		if(CurrentContent == $("#comment-txt-edit-" + CurrentResId).val())
    		{
	    		CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
	            $("#std-comment > #comment-all-" + CurrentResId +" > #comment-content-" + CurrentResId).html(CurrentContent);
	            clickEdit = false;
    		}

    		else
    		{
		        swal({   
		        	title: "確定放棄？",   
		        	text: "若按下確定，您已編輯的文章將會消失哦。",   
		        	type: "warning",   
		        	showCancelButton: true,   
		        	cancelButtonText: "取　　消",    
		        	confirmButtonText: "確定放棄",   
		        	closeOnConfirm: true 
		        }, 
		        function(isConfirm){
        			swal.disableButtons(); 
	        	   if (isConfirm) {
			    		CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
			            $("#std-comment > #comment-all-" + CurrentResId +" > #comment-content-" + CurrentResId).html(CurrentContent);
			            clickEdit = false;

			    		CurrentResId = postId;
			    		CurrentContent = $('#comment-content-' + postId).html().replace(/(<br>)/g, "\n");
			    		$("#std-comment > #comment-all-" + postId +"> #comment-content-" + postId).html("<div class='comment-edit-text'><form><textarea class='form-control AutoHeight' id='" + txtName + "'>"+ CurrentContent.trim() +"</textarea></form></div>");
						$("#std-comment > #comment-all-" + postId +"> #comment-content-" + postId).append("<div class='comment-edit-func'><button class='comment-btn comment-btn-edit-cancel' id='comment-btn-edit-cancel-" + postId + "'>取 消</button><button class='comment-btn comment-btn-edit-submit' id='comment-btn-edit-submit-" + postId + "'>完 成</button></div>");
				    			
						txtHeigthChange($("#" + txtName));
			            clickEdit = true;
		        	}
		        });
		    }
    	}

    	if(!clickEdit){
    		CurrentResId = postId;
    		CurrentContent = $('#comment-content-' + postId).html().replace(/(<br>)/g, "\n");
    		$("#std-comment > #comment-all-" + postId +"> #comment-content-" + postId).html("<div class='comment-edit-text'><form><textarea class='form-control AutoHeight' id='" + txtName + "'>"+ CurrentContent.trim() +"</textarea></form></div>");
			$("#std-comment > #comment-all-" + postId +"> #comment-content-" + postId).append("<div class='comment-edit-func'><button class='comment-btn comment-btn-edit-cancel' id='comment-btn-edit-cancel-" + postId + "'>取 消</button><button class='comment-btn comment-btn-edit-submit' id='comment-btn-edit-submit-" + postId + "'>完 成</button></div>");
	    			
			txtHeigthChange($("#" + txtName));
            clickEdit = true;
    	}
    	else if((CurrentResId == postId) && clickEdit){
    		if(CurrentContent == $("#" + txtName).val())
    		{
				GetCommnet();
	            clickEdit = false;
    		}
    		else
    		{
		        swal({   
		        	title: "確定放棄？",   
		        	text: "若按下確定，您已編輯的文章將會消失哦。",   
		        	type: "warning",   
		        	showCancelButton: true,   
		        	cancelButtonText: "取　　消",    
		        	confirmButtonText: "確定放棄",   
		        	closeOnConfirm: true 
		        }, 
		        function(isConfirm){
    				swal.disableButtons(); 
	        	   if (isConfirm) {
			    		CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
			            $("#std-comment > #comment-all-" + CurrentResId +" > #comment-content-" + CurrentResId).html(CurrentContent);
			            clickEdit = false;
		        	}
		        });
    		}    	
    	}
    });

	$(document).on('click', '.comment-btn-edit-cancel', function(e){
    	var postId = this.id.replace("comment-btn-edit-cancel-", "");
    	var txtName = 'comment-txt-edit-' + postId;

    	if(clickEdit && CurrentResId == postId)
    	{
    		if(CurrentContent == $("#" + txtName).val())
    		{
				GetCommnet();
	            clickEdit = false;
    		}
    		else
    		{
		        swal({   
		        	title: "確定放棄？",   
		        	text: "若按下確定，您已編輯的文章將會消失哦。",   
		        	type: "warning",   
		        	showCancelButton: true,   
		        	cancelButtonText: "取　　消",    
		        	confirmButtonText: "確定放棄",   
		        	closeOnConfirm: true 
		        }, 
		        function(){
        			swal.disableButtons(); 
					GetCommnet();
			        clickEdit = false;
		        });
    		}
	    }
	});

	$(document).on('click', '.comment-btn-edit-submit', function(e){
    	var postId = this.id.replace("comment-btn-edit-submit-", "");
		
		if(clickEdit && CurrentResId == postId)
		{
            $("#comment-btn-edit-submit-" + postId).attr('disabled', 'disabled');
            $("#comment-btn-edit-cancel-" + postId).attr('disabled', 'disabled');

			if(CurrentContent != $("#comment-txt-edit-" + postId).val())
			{
				var resData = {
	        		SID: SID,
	                PostId: PID,
	                ResId: postId,
	                Content: $("#comment-txt-edit-" + postId).val(),
	                Edit: true
	            };

	            $.ajax({
	                url: "../f/post_res_act.php",
	                type: "POST",
	                dataType: "json",
	                data: resData,
		            success: function(res){
				        clickEdit = false;

				    	$.ajax({
				    		url: "post_responses.php",
				    		type: "GET",
				    		dataType: "json",
				    		data: {
								p: PID,
				    			ResId: postId
				    		},
				    		success: function(res){
								var Content = res[0].Content.replace(/\r?\n/g, "<br>");
								Content = Content.trim();
				    			$('#std-comment > #comment-all-' + postId + ' > #comment-content-' + postId).html(Content);
				    		}
						});
				    }
	    		});
			}
			else
			{
				GetCommnet();
		        clickEdit = false;
			}

			$("#comment-btn-edit-submit-" + postId).removeAttr('disabled'); 
			$("#comment-btn-edit-cancel-" + postId).removeAttr('disabled'); 
		}
		else
		{
			CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
	        $("#std-comment > #comment-all-" + CurrentResId +" > #comment-content-" + CurrentResId).html(CurrentContent);
	        clickEdit = false;
		}
	});


	$(document).on('click', '#btnReport', function(e){
		swal({
		   title: "<i class='fa fa-exclamation-triangle' aria-hidden='true'></i><br>檢舉回報",
		   text: "檢舉範圍限本文章（含回應）<br>請勿濫用本功能，違者將視情況懲處<br><br>",
		   type: "input",   
		   showCancelButton: true, 
		   html: true,   
		   closeOnConfirm: false,
		   cancelButtonText: "取　消",
		   confirmButtonText: "送　出",
		   animation: "slide-from-top",   
		   inputPlaceholder: "請簡述違規樓層和原因。" 
		}, 
	   function(inputValue){ 
        	swal.disableButtons();  
	        if (inputValue === false) 
	           return false;      
	       if (inputValue === "") {     
	           swal.showInputError("請至少輸入違規樓層。");     
	           return false;
	        }      
	       if (inputValue.length > 200) {     
	           swal.showInputError("您輸入的內容太長了。");     
	           return false;   
	        }       

            $.ajax({
                url: "../f/report.php",
                type: "POST",
                dataType: "json",
                data: {
                	SID: SID,
                	PostId: PID,
                	Content: inputValue
                },
	            success: function(res){
					if(res.status == 1)
					{
	       				swal("感謝您的回報！", "非常感謝，我們將會盡速處理。", "success"); 
	           			return true;   
					}
					else
					{
	       				swal("oh..", res.msg, "warning"); 
	           			return false;   
					}
			    }
    		});
		});
	});
