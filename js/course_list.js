
		$('#txtKeyword').keypress(function(e) {
			 code = e.keyCode ? e.keyCode : e.which;
			 if(code == 13){
			 	$('#btnKeywordSearch').click();
			 }
		});

	    $(document).ready(function(){
	    	$("#ddlstDept").val("33");
	    	$("#ddlstType").val("all");
	    	$("#ddlstDay").val("all");
	    	$.ajax({
	            url: "../c/course_search.php",
	            type: "POST",
	            dataType: "json",
	            data: {
		    		SID: SID,
		    		DeptNo: 33,
		    		Type: 'all',
		    		Day: 'all',
		    		SearchType: 99
		    	},
	            success: function(result){
	            	ShowCourses(result);
	            }
	        });
	    });

	    $("#ddlstDept").change(function(){
	    	$.ajax({
	            url: "../c/course_search.php",
	            type: "POST",
	            dataType: "json",
	            data: {
		    		SID: SID,
		    		DeptNo: $("#ddlstDept").val(),
		    		Type: $("#ddlstType").val(),
		    		Day: $("#ddlstDay").val(),
		    		SearchType: 1,
		    		Default: 1
		    	},
	            success: function(result){
	            	ShowCourses(result);
	            }
	        });
	    });

	    $("#ddlstType").change(function(){
	    	$.ajax({
	            url: "../c/course_search.php",
	            type: "POST",
	            dataType: "json",
	            data: {
		    		SID: SID,
		    		DeptNo: $("#ddlstDept").val(),
		    		Type: $("#ddlstType").val(),
		    		Day: $("#ddlstDay").val(),
		    		SearchType: 1
		    	},
	            success: function(result){
	            	ShowCourses(result);
	            }
	        });
	    });

	    $("#ddlstDay").change(function(){
	    	$.ajax({
	            url: "../c/course_search.php",
	            type: "POST",
	            dataType: "json",
	            data: {
		    		SID: SID,
		    		DeptNo: $("#ddlstDept").val(),
		    		Type: $("#ddlstType").val(),
		    		Day: $("#ddlstDay").val(),
		    		SearchType: 1
		    	},
	            success: function(result){
	            	ShowCourses(result);
	            }
	        });
	    });

	    $("#btnKeywordSearch").click(function(e){
	    	$("#ddlstDept").val("33");
	    	$("#ddlstType").val("all");
	    	$("#ddlstDay").val("all");
	    	if($('#txtKeyword').val().trim().length == 0)
	    	{
	    		var deptNo = $('#ddlstDept').val();
		    	$.ajax({
		            url: "../c/course_search.php",
		            type: "POST",
		            dataType: "json",
		            data: {
			    		SID: SID,
			    		DeptNo: $("#ddlstDept").val(),
			    		Type: $("#ddlstType").val(),
			    		Day: $("#ddlstDay").val(),
			    		SearchType: 1
			    	},
		            success: function(result){
		            	ShowCourses(result);
		            }
		        });
	    	}
	    	else
	    	{
		    	searchData = {
		    		SID: SID,
		    		Keyword: $('#txtKeyword').val(),
	    			SearchType: 2
		    	}
		    	$.ajax({
	                url: "../c/course_search.php",
	                type: "POST",
	                dataType: "json",
	                data: searchData,
	                success: function(result){
		            	ShowCourses(result);
	                }
		        });
            }
	    });

	    function ShowCourses(result) {
        	if(result.length == 0)
        	{
        		$('#divAllContent').html('<div id="divNoResult">查無符合條件之課程。</div>')
        	}
        	else
        	{
        		$('#divAllContent').html("<table class='table course_list text-center tablesorter'><thead><tr><th class='text-center hidden-xs'>開課系所</th><th class='text-center'>課程名稱</th><th class='text-center'>授課教師</th><th class='text-center hidden-xs'>評價</th><th class='text-center'><i class='fa fa-thumbs-o-up'></i></th><th class='text-center'><i class='fa fa-comment-o'></i></th></tr></thead><tbody id='tbCourses'></tbody></table>");
        		//$('#tbCourses > #tbCourses').html('')
    			for(var i=0; i < result.length; i++)
    			{
					var Name = "tr-course-" + (i + 1);
					var star = '';

					switch(Math.round(result[i].AvgScore))
					{
						case 0:
							star = '☆☆☆☆☆';
							break;
						case 1:
							star = '★☆☆☆☆';
							break;
						case 2:
							star = '★★☆☆☆';
							break;
						case 3:
							star = '★★★☆☆';
							break;
						case 4:
							star = '★★★★☆';
							break;
						case 5:
							star = '★★★★★';
							break;
						default:
							star = '☆☆☆☆☆';
							break;
					}

					$("#tbCourses").append("<tr id='" + Name + "'></tr>");

    				$('#tbCourses > #' + Name).html("<td class='hidden-xs'>" + result[i].DeptName + "</td>");

					$('#tbCourses > #' + Name).append("<td><a href='/c/course_detail.php?id=" + result[i].CID + "'>" + result[i].Name + "</button></td>");

    				$('#tbCourses > #' + Name).append("<td>" + result[i].Teacher + "</td><td class='hidden-xs'>" + star + "</td><td>" + result[i].TotalLike + "</td><td>" + result[i].TotalComment + "</td></td>");
    			}
        			$("table").tablesorter({
        				widgets: ['zebra']
        			});
        	}
        };