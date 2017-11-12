
	    var clickEdit = false;
	    var CurrentPostId = 0;
	    var CurrentContent = "";

        jQuery(function() {
            jQuery('.starbox').each(function() {
                var starbox = jQuery(this);
                starbox.starbox({
                    average: starbox.attr('data-start-value'),
                    changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
                    ghosting: starbox.hasClass('ghosting'),
                    autoUpdateAverage: starbox.hasClass('autoupdate'),
                    buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
                    stars: starbox.attr('data-star-count') || 5
                }).bind('starbox-value-changed', function(event, value) {
                    if(starbox.hasClass('random')) {
                        var val = Math.random();
                        starbox.next().text('Random: '+val);
                        return val;
                    } else {
                    	$.ajax({
				            url: "../c/star_act.php",
				            type: "POST",
				            data: {SID: UserNo, CID: CID, Type: $(this).attr("id"), Value: value}
				        });
                    }
                }).bind('starbox-value-moved', function(event, value) {
                });
            });
        });



    $(document).ready(function(){
		GetCommnet();
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

	$(document).on('keydown keyup change', 'textarea.AutoHeight', function(e){
		txtHeigthChange(this);
    });

    function txtHeigthChange(txtContent){	    	
    	$("textarea.AutoHeight").css("overflow", "hidden");
    	$(txtContent).height('0px').height($(txtContent).prop("scrollHeight") + "px"); 
    }

    $("#btnSend").click(function(e){
    	if($("#resContent").val().trim().length > 0)
    	{
	        swal({   
	        	title: "確定送出？",   
	        	text: "您的評論會影響他人選課意願，請確保內容正確無誤。",   
	        	type: "warning",   
	        	showCancelButton: true,   
	        	cancelButtonText: "取　　消",    
	        	confirmButtonText: "確定發表",   
	        	closeOnConfirm: false 
	        }, 
	        function(){
	        	swal.disableButtons();
	        	PostContent = $("#resContent").val();

	            var resData = {
	        		SID: UserNo,
	        		CID: CID,
	                Content: PostContent,
	                Anonymous: $("#chkAnonymous").is(':checked')
	            };

	            $("#btnSend").attr('disabled', 'disabled');

	            $.ajax({
	                url: "../c/res_act.php",
	                type: "POST",
	                dataType: "json",
	                data: resData,
	                success: function(result) {
	                    if(result.status)
	                    {
	        				swal("成功發表！", "我們在這裡替各位同學感謝您的無私分享。" + result.msg, "success"); 
		    				GetCommnet();
	                        $('#resContent').val("");
							txtHeigthChange($('#resContent'));
	                    }
	                    else
	                    {
	        				swal("出了一點錯誤...", result.msg, "error"); 
	                        $('#resContent').val("");
	                    }
			            $("#btnSend").removeAttr('disabled');
	                },
	                error: function() {
	    				swal("出了一點錯誤...", "系統似乎有些小問題，請稍後再試。", "error"); 
			            $("#btnSend").removeAttr('disabled');
	                },
	            });
	        });
	    }
	    else
	    {
			swal("oh!", "您還沒有輸入內容呢。", "error"); 
	    }
    });

	$(document).on('keydown keyup', '#resContent', function(e){
		CheckResponse();
    });

    function CheckResponse(){
		var txtLength = 0;

		if($("#resContent").val().length == undefined || $("#resContent").val().length == 0)
		{
			txtLength = 0;
		}
		else
		{
			txtLength = $("#resContent").val().length;
		}

    	if(txtLength > 2000){
            $('#lbSend').html("<div class='alert alert-danger'>");
            $('#lbSend > .alert-danger').append("<strong>您的評論字數太多囉... (您需要刪除 " + (txtLength - 2000) + " 個字)</strong>");
            $('#lbSend > .alert-danger').append('</div>');
            $("#btnSend").attr('disabled', 'disabled');  
    	}
    	else
    	{
    		$('#lbSend').html("");
            $("#btnSend").removeAttr('disabled');        
    	}
    }

    $("#btnLike").click(function(){
    	if($("#btnLike").hasClass("btn-info")){
			$.ajax({
                url: "../c/course_like.php",
                type: "POST",
                dataType: "json",
                data:{
                	SID: SID,
                	CID: CID,
                	Liked: 1
                }
            });
    		$("#btnLike").removeClass("btn-info");
    		$("#btnLike").addClass("btn-default");
    		$("#btnLike").html("<i class='fa fa-check-square-o text-center'></i>&nbsp;已推薦");
    	}
    	else{
    		swal({   
    			title: "您確定要收回推薦？",   
    			text: "您的推薦是很重要的參考依據！",   
    			type: "warning",   
    			showCancelButton: true,   
    			confirmButtonColor: "#DD6B55",   
    			cancelButtonText: "取　　消",   
    			confirmButtonText: "收回推薦",   
    			closeOnConfirm: true 
    		}, function(){   
	        	swal.disableButtons();
    			$.ajax({
	                url: "../c/course_like.php",
	                type: "POST",
	                dataType: "json",
	                data:{
	                	SID: SID,
	                	CID: CID,
	                	Liked: 0
	                }
	            });
	    		$("#btnLike").removeClass("btn-default");
	    		$("#btnLike").addClass("btn-info");
	    		$("#btnLike").addClass("btn-liked");
	    		$("#btnLike").html("<i class='fa fa-thumbs-o-up text-center'></i>&nbsp;值得推薦");
    		});
    	}
    });

	$(document).on('click', '.comment-btn-edit', function(e){
    	var postId = this.id.replace("comment-btn-edit-", "");
    	var txtName = 'comment-txt-edit-' + postId;
    	
    	if((CurrentPostId != postId) && clickEdit)
    	{
    		CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
            $("#std-comment > #comment-all-" + CurrentPostId +" > #comment-content-" + CurrentPostId).html(CurrentContent);
            clickEdit = false;
    	}

    	CurrentPostId = postId;

    	if(!clickEdit){
    		CurrentContent = $('#comment-content-' + postId).html().replace(/(<br>)/g, "\n");
    		$("#std-comment > #comment-all-" + postId +"> #comment-content-" + postId).html("<div class='comment-edit-text'><form><textarea class='form-control AutoHeight' id='" + txtName + "'>"+ CurrentContent.trim() +"</textarea></form></div>");
			$("#std-comment > #comment-all-" + postId +"> #comment-content-" + postId).append("<div class='comment-edit-func'><button class='comment-btn comment-btn-edit-cancel' id='comment-btn-edit-cancel-" + postId + "'>取 消</button><button class='comment-btn comment-btn-edit-submit' id='comment-btn-edit-submit-" + postId + "'>完 成</button></div>");
	    			
			txtHeigthChange($("#" + txtName));
            clickEdit = true;
    	}
    	else{
    		CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
            $("#std-comment > #comment-all-" + postId +" > #comment-content-" + postId).html(CurrentContent);
            clickEdit = false;
    	}
    });

	$(document).on('click', '.comment-btn-edit-cancel', function(e){
    	var postId = this.id.replace("comment-btn-edit-cancel-", "");

    	if(clickEdit && CurrentPostId == postId)
    	{
			CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
	        $("#std-comment > #comment-all-" + postId +" > #comment-content-" + postId).html(CurrentContent);
	        clickEdit = false;
	    }
	});

	$(document).on('click', '.comment-btn-edit-submit', function(e){
    	var postId = this.id.replace("comment-btn-edit-submit-", "");
		
		if(clickEdit && CurrentPostId == postId)
		{
            $("#comment-btn-edit-submit-" + postId).attr('disabled', 'disabled');
            $("#comment-btn-edit-cancel-" + postId).attr('disabled', 'disabled');

			if(CurrentContent != $("#comment-txt-edit-" + postId).val())
			{
				var resData = {
	        		SID: UserNo,
	        		CID: CID,
	                Content: $("#comment-txt-edit-" + postId).val(),
	                Edit: true,
	                PostId: postId
	            };

	            $.ajax({
	                url: "../c/res_act.php",
	                type: "POST",
	                dataType: "json",
	                data: resData,
		            success: function(res){
				    	$.ajax({
				    		url: "comment.php",
				    		type: "GET",
				    		dataType: "json",
				    		data: {c: CID, PostId: postId},
				    		success: function(res){
								CurrentContent = $("#comment-txt-edit-" + postId).val().replace(/\r?\n/g, "<br>");
						        $("#std-comment > #comment-all-" + postId +" > #comment-content-" + postId).html(CurrentContent);
						        clickEdit = false;
				    		}
						});
				        clickEdit = false;
				    }
	    		});
			}
			else
			{
				CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
		        $("#std-comment > #comment-all-" + CurrentPostId +" > #comment-content-" + CurrentPostId).html(CurrentContent);
		        clickEdit = false;
			}

			$("#comment-btn-edit-submit-" + postId).removeAttr('disabled'); 
			$("#comment-btn-edit-cancel-" + postId).removeAttr('disabled'); 
		}
		else
		{
			CurrentContent = CurrentContent.replace(/\r?\n/g, "<br>");
	        $("#std-comment > #comment-all-" + CurrentPostId +" > #comment-content-" + CurrentPostId).html(CurrentContent);
	        clickEdit = false;
		}
	});


	$(document).on('click', '.comment-btn-del', function(e){
    	var postId = this.id.replace("comment-btn-del-", "");
    	swal({   
    		title: "確定要刪除評論嗎？",   
    		text: "其他同學很需要您的寶貴建議，這樣真的好嗎？",   
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
                url: "../c/res_del_act.php",
                type: "POST",
                dataType: "json",
                data: { SID: UserNo, CID: CID, PID: postId },
                success: function(result) {
                    if(result.status)
                    {
						swal("成功刪除！", "希望您仍能持續發表更多評論哦！", "success");
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

    function GetCommnet(SN){    
    	SN = SN || 30;
    	Total_SN = SN;

    	$.ajax({
    		url: "comment.php",
    		type: "GET",
    		dataType: "json",
    		data: {c: CID, SN: SN },
    		success: function(result){
    			ShowComment(result, SN);
    		}
		});
    }