$(function(){	
	//导航切换
	$(".menuson .header").click(function(){
		var $parent = $(this).parent();
		$(".menuson>li.active").not($parent).removeClass("active open").find('.sub-menus').hide();
		
		$parent.addClass("active");
		if(!!$(this).next('.sub-menus').size()){
			if($parent.hasClass("open")){
				$parent.removeClass("open").find('.sub-menus').hide();
			}else{
				$parent.addClass("open").find('.sub-menus').show();	
			}
			
			
		}
	});
	
	// 三级菜单点击
	$('.sub-menus li').click(function(e) {
        $(".sub-menus li.active").removeClass("active")
		$(this).addClass("active");
    });
	
	$('.title').click(function(){
		var $ul = $(this).next('ul');
		$('dd').find('.menuson').slideUp();
		if($ul.is(':visible')){
			$(this).next('.menuson').slideUp();
		}else{
			$(this).next('.menuson').slideDown();
		}
	});

  	$(".addArea").click(function(){
  		addArea();
  	});

  	$(".addAddress").click(function(){
  		showAddAddress();
  	});

  	$(".delete").click(function(){
  		deleteItem();
  	});

	// layer.config({
	//     extend: 'extend/layer.ext.js'
	// });
})


function addArea(){
	layer.prompt({
  		title: '添加区域',
  		formType: 0 //prompt风格，支持0-2
	}, function(name){
		$.ajax({
			type:"POST",
			url : rootUrl+"/Config/addArea",
			data:{
			    name:name
			},
			dataType : "json",
			success:function(ret){
				if(ret == 1){
					layer.msg('添加成功', {
		                time : 700,
		                offset : '20%'
	            	});
				}			    
			},
			error:function(){
				layer.msg('连接失败', {
	                time : 700,
	                offset : '20%'
	            });
			}
		});
	});
}

function showAddAddress(){
	layer.open({
    type: 2,
    title: '编辑用户信息',
    area: ['350px', '350px'],
    fix: false, //不固定
    maxmin: true,
    content: getUrl+'/addAddress'
});
}

function deleteItem() {
	layer.confirm('确定删除 ？', {
  	btn: ['确定','取消'] //按钮
	}, function(){
  		
	}, function(){
		var index = parent.layer.getFrameIndex(window.name); //获取窗口索引
        parent.layer.close(index);
	});
}

function addAddress() {
	$.ajax({
		type:"POST",
		url : rootUrl+"/Config/addAddress",
		data:{
		    name:name
		},
		dataType : "json",
		success:function(ret){
			if(ret == 1){
				layer.msg('添加成功', {
	                time : 700,
	                offset : '20%'
            	});
			}			    
		},
		error:function(){
			layer.msg('连接失败', {
                time : 700,
                offset : '20%'
            });
		}
	});
}