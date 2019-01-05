var pskcms =function(){ 
	return {
		//下拉选择	   
		pskselect:function(obj){
			$(obj).each(function(index, element) {
                $(obj).eq(index).find(".input").val($(obj).eq(index).find(".select-value dd[class=selected]").text());
            });			
			$(obj).click(function(){		
				$(this).find(".select-value").toggle();
				$(this).find(".icon-caret-down").toggleClass("on");		
			})	
			$(obj).find(".select-value dd").click(function(){
				var data = $(this).attr("data");
				var text = $(this).text();
				$(this).addClass("selected").siblings().removeClass("selected");
				$(this).parent().parent().find(".input").val(text);
				$(this).parent().parent().find(".input").blur();
				$(this).parent().parent().find(".inputval").val(data);
			})
		},
		//switch开关	
		pskswitch:function(obj){
			$(obj).click(function(){	
				var v  = $(this).prev().val();
				var em = $(this).find("em");
				var ontxt  = em.attr("ontxt");
				var offtxt = em.attr("offtxt");
				var emtxt  = em.text();
				emtxt = emtxt == ontxt ? offtxt : ontxt;
				v = v == 1 ? 0 : 1;
				em.text(emtxt);
				$(this).prev().val(v);
				$(this).toggleClass("on");
			})
		},
		//表单提交
		submitform:function(url){			
			$(".pskcmsform:eq(0)").Validform({
				tiptype:3,
				ajaxPost:true,
				beforeSubmit:function(curform){					
					layer.load(1);
				},
				callback:function(data){
					layer.closeAll();	
					if(data.msg=="没有权限"){
					  layer.alert(data.msg,{icon: 2});
					  return;
				 	}							
					if(data.status=="y"){
						layer.alert(data.info, {icon:1},function(){
							window.location.href = url;
						});
					}else{
						layer.alert(data.info, {icon: 2});
					}
				}
			});
		},
		//删除操作
		del:function(url){
			layer.confirm("您确定要删除吗？",{ btn: ['是','否'],icon:3},function(){
			  $.get(url,function(data){	
			  	  if(data.msg=="没有权限"){
					  layer.alert(data.msg,{icon: 2});
					  return;
				  }
				  if(data.status=="y"){
					  layer.alert(data.info,{icon: 1},function(){ window.location.reload();});	
				  }else{				
					  layer.alert(data.info,{icon: 2});			
				  }
			  },"JSON");
			  return false;
		   })
		},
		
		//显示上传图片
		showupload:function(obj){
			var id = '';
			var at = '';				
			var id = $(obj).attr("id");
			var at = $(obj).attr("at");			
			var url = "/index.php/pskcms/Common/uploadimg";
			$.get(url,{id:id,at:at},function(data){						
				layer.open({
					  title:'图片管理',
					  type: 1,				  
					  area: ['647px','510px'], //宽高
					  content: data,
				});		
			})
		},
		
		//显示上传文件
		showupfile:function(obj){
			var id = '';
			var at = '';
			var id = $(obj).attr("id");
			var at = $(obj).attr("at");							
			var url = "/index.php/pskcms/Common/uploadfile";
			$.get(url,{id:id,at:at},function(data){						
				layer.open({
					  title:'文件管理',
					  type: 1,				  
					  area: ['647px','510px'], //宽高
					  content: data,
				});		
			})
		},
		
		//全选和反选
		allselect:function(obj){
			if ($(obj).prop("checked") == true) {			
				$("input[name='id[]']").prop("checked", true);
			} else {			
				$("input[name='id[]']").prop("checked", false);
			}	
		},
		
		//批量删除
		delallSelect:function(url){
			var Checkbox=false;
			 $("input[name='id[]']").each(function(){
			  if (this.checked==true) {		
				Checkbox=true;	
			  }
			});
			if (Checkbox){
				layer.confirm("您确定要删除所选内容吗？",{ btn: ['是','否'], icon:3},function(){
				$("#listform").attr("action",url); 
				$("#listform").submit();
				});
			}
			else{
				layer.alert('请选择您要删除的内容', {icon: 2});
				return false;
			}
		},	
		
		//批量操作
		plattr:function(url,obj){
			var Checkbox=false;
			var i = 0;
			$("input[name='id[]']").each(function(){
			  if (this.checked==true) {		
				Checkbox=true;
				i++;	
			  }
			});
			if(Checkbox){	
				if($(obj).attr("name")=='copynum'){							
					if(i>1){
						layer.alert('只能选择一条数据', {icon: 2});
						$(obj).find("option:first").prop("selected","selected");
						return false;
					}
				}				
				$("#listform").attr("action",url); 
				$("#listform").submit();			   
			}
			else{
				layer.alert('请选择您要操作的内容', {icon: 2});
				$(obj).find("option:first").prop("selected","selected");
				return false;
			}
		},
		
			
		//单个排序
		sorts:function(url,id,num){
		  layer.load(2);
		  $.post(url,{id:id,num:num },function(res){
			  layer.closeAll();
			  if(res.status=='y'){ 
			  }else{
				layer.alert(res.info,{icon:2});	 
			  }
		  })
		},		
		
		//删除单个图片
		removeImg:function(obj){
			$(obj).parent().remove();
		},
		
		//文章列表搜索
		search:function(url){
			$("#listform").attr("action",url); 
			$("#listform").submit();
		}
		
	}    
}();

$(function(){
	pskcms.pskselect(".select"); //下拉框
	pskcms.pskswitch(".switch"); //Switch开关	
	$('.imgview').touchTouch(); //图片预览
})