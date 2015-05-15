var radian =radian || false;

document.addEventListener("touchstart", function(){}, true);
document.addEventListener("touchend", function(){}, true);

$(window).load(function(){
	
});

$(document).ready(function(){
	if(!radian){
	   effectAnimate();
    }
	getAllTag();
	pressEffect();
	$('.projectInfoBox').css({"height":"415px","background-size":"580px 415px"});
   		
});

$(window).resize(function(){
	$('footer').css("margin-top",$(window).height());
});
function projectHandler(){
   $.post("php/projectInfo.php",{block:true}).done(function (data){
	   console.log(JSON.parse(data));
       var t = setTimeout(function(){projectHandler();},3000);	
   });
}
function pressEffect(){
	$('.emailBtn').on("mousedown touchstart",function(){
	     $('.emailBtnPress').addClass('emailPressImg');
	});
	$('.emailBtn').on("mouseup touchend",function(){
	     $('.emailBtnPress').removeClass('emailPressImg');
	}); 
	
	$('.infoBArrowF').on("mousedown touchstart",function(){
	     $('.infoBArrowF').css("background-image","url('img/infoBArrowFPressed.png')");
	});
	$('.infoBArrowF').on("mouseup touchend",function(){
	     $('.infoBArrowF').css("background-image","url('img/infoBArrowF.png')");
	});
	 
	$('.infoBArrow').on("mousedown touchstart",function(){
	     $('.infoBArrow').css("background-image","url('img/infoBArrowPressed.png')");
	});
	$('.infoBArrow').on("mouseup touchend",function(){
	     $('.infoBArrow').css("background-image","url('img/infoBArrow.png')");
	}); 
}

function removeCustom(custom){
	$(custom).parents("li").remove();
}
function navBox1(){
	$('#box2').css({"display":"none"});
	$('#box1').css({"display":"block"});
	$('#navBox2').removeClass('navTabActive');
	if(!$('#navBox1').hasClass('navTabActive')){
	  $('#navBox1').addClass('navTabActive');
    }
} 
function navBox2(){
	$('#box1').css({"display":"none"});
	$('#box2').css({"display":"block"});
	$('#navBox1').removeClass('navTabActive');
	if(!$('#navBox2').hasClass('navTabActive')){
	   $('#navBox2').addClass('navTabActive');
   }
   $( ".TagNumber" ).each(function(){
    	var numAnim = new countUp(this, 0, $(this).attr('data-num'));
	    numAnim.start();
   });
}

function getAllTag(){
   $.post("php/getTag.php",{block:true}).done(function (data){
   	   var json=$.parseJSON($.parseJSON(JSON.stringify($.parseJSON(data))));
	   $('#input-facebook-theme').tokenInput(json,{theme: "facebook"});
   });
}
function effectAnimate(){
	$( ".progress-bar-core" ).each(function(){
		$(this).width($(this).attr('value'));
	});
	
	$( ".TagNumber" ).each(function(){
		var numAnim = new countUp(this, 0, $(this).attr('data-num'));
		numAnim.start();
		//$(this).html($(this).attr('data-num'));
	});
}

function startTime(second) {
    document.getElementById('tmpClock').innerHTML = '<p id="ClockP">'+parseInt(second)+'</p>';
	if(second>0){
		$.post("php/selectStatus.php",{block:true}).done(function (data){
			var json=$.parseJSON(data);
			for(var i=0; i<json.length;i++){
				if(json[i]['device']=='D' && json[i]['behavior']=='BUILD_UP_return' && json[i]['done'].indexOf(localStorage.getItem("deviceid"))==-1){
				   var bid=	$.parseJSON(json[i]['object']);
		   		   $.post("php/updateUser.php",{uid:sessionStorage.getItem('USERID'),bid:bid['bid'],block:true,device:localStorage.getItem("deviceid")}).done(function (data){
					      sessionStorage.setItem('BUILDINGID',bid['bid']);
						  if(!radian){
						     window.location.href ='build.php';
					      }
						  else{
							  switchController.ajaxPage($(this).data('ajax'),false);
						  }
				   });
				}
			}
		});	
       var t = setTimeout(function(){startTime(second-.2)},200);
    }
	else{
		$('.modalCount').modal('hide');
		$('.modalBuilding').modal('show');
		//history.back();	
	}
}
function infoBUpdate(cid){
	$.post("php/getInfoB.php",{cid:cid,block:true}).done(function (data){
		 sessionStorage.setItem('infoB',data);
		 sessionStorage.setItm('infoBIndex',0); 	     
		 console.log(data);
	});
}
function nextBox(next){
	     var typeList = $.parseJSON(sessionStorage.getItem('infoB')); 	     
         var current  = sessionStorage.getItem('infoBIndex');
	     var index=curret+next;
		 if(index>typeList.length){
			 sessionStorage.setItm('infoBIndex',0);
			 index=0; 	     
		 }
		 else if(index<0){
			 sessionStorage.setItm('infoBIndex',typeList.length-1);
		 	 index=typeList.length-1;
		 }
		 
		 if(next>0){
	 		$('.infoABBox').removeClass('bounceInRight bounceInLeft');
	 		$('.infoABBox').addClass('bounceOutRight');
			$('.infoABBox').removeClass('bounceOutRight');
	 		$('.infoABBox').addClass('bounceInLeft');
			
		 }
		 else if(next<0){
 	 		$('.infoABBox').removeClass('bounceInRight bounceInLeft');
 	 		$('.infoABBox').addClass('bounceOutLeft');
 			$('.infoABBox').removeClass('bounceOutLeft');
 	 		$('.infoABBox').addClass('bounceInRight');
		 }
		 
		  $('.infoBBuildingName').html('');
		  $('.infoBBuildingContext').html('');
		  $('.alterTag').html('');
		  $('.infoBBuildingName').html('<a style="color:#fff;" href="infoA.php?bid='+typeList[index]['bid']+'" data-ajax="page=infoA&bid='+typeList[index]['bid']+'">'+typeList[index]['bname']+'</a>');
		  $('.infoBBuildingContext').html(typeList[index]['content']);
		  $('.alterTag').html(typeList[index]['tag']);
		 
		  $('.infoABBox').removeClass('bounceOutRight');
		  $('.infoABBox').addClass('bounceInRight');
		 			               		 
}
function uploadEmail(){
	var usr=document.getElementsByName("emailUsr")[0].value;
	var address=document.getElementsByName("emailAddress")[0].value;
    if(usr!=null || usr!=undefined){
      $.post("php/createEmail.php",{usr:usr,address:address,block:true}).done(function (data){
	    	$('.returnId').empty()
		    $('.returnId').append('<label>'+$.parseJSON(data)+'</label>');
		    $('.modal').modal('toggle');
      });
    }
	document.getElementsByName("emailUsr")[0].value='';
	document.getElementsByName("emailAddress")[0].value='';	
}
function buildBuilding(){
	   $('.modalBuildTypeId').modal('hide');	    
		var userId = document.getElementsByName("inputIdBuild")[0].value;
	    if (userId != null){
			$.post('php/checkId.php',{uid:userId,block:true}).done(function(data){
				if($.parseJSON(data)==0){
					$('.modalBuilding').modal('show');
					//alert('User do not exist OR had built');
				}
				else{
					$.post('php/statusBuildup.php',{uuid:userId,block:true,device:localStorage.getItem("deviceid")}).done(function(data){});
					sessionStorage.setItem("USERID", data);
					$('.modalCount').modal('show');
					startTime(15);
					//window.location.href ='count.php';
				}
			});
	    }
		else{
			$('.modalBuilding').modal('show');
		}
		document.getElementsByName("inputIdBuild")[0].value='';
}
function resideHouse(){
   $('.modalResideTypeId').modal('hide');	    
	var bid = getParameterByName('bid');
	var userId = document.getElementsByName("inputIdReside")[0].value;
       if (userId != null){
	     	$.post('php/checkHouse.php',{bid:bid,uid:userId,block:true}).done(function(data){
		    	if($.parseJSON(data)==0){
					$('.modalResiding').modal('show');
			    }
			    else{
				    $.post('php/statusHouse.php',{uuid:userId,bid:bid,block:true,device:localStorage.getItem("deviceid")}).done(function(data){
						$('.modalResidingAfter').modal('show');
				    });
			   }
	     	});
       }
	   else{
		$('.modalResiding').modal('show');
	   }
	   document.getElementsByName("inputIdReside")[0].value='';
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function finishBuilding(){
	var buildingName=document.getElementsByName("buildingName")[0].value;
	var buildingType=document.getElementById("typeSelector").value;
	var buildingContent=document.getElementsByName("buildingContent")[0].value;
	var buildingTag=[];
	var index=0;
	$('li.token-input-token-facebook p').each(function(){
		buildingTag[index++]=$(this).text();
	});
    
	for(var i=0;i<buildingTag.length;i++){
		if(buildingTag[i][0]!='#'){
			buildingTag[i]='#'+buildingTag[i];
		}
		if(buildingTag[i].length>7){
		    buildingTag.splice(i,1);
		}
	}	
	
	$.post('php/updateBuilding.php',{bname:buildingName,cid:buildingType,content:buildingContent,tag:JSON.stringify(buildingTag),bid:sessionStorage.getItem('BUILDINGID')
,block:true,device:localStorage.getItem("deviceid")}).done(function(data){
	    if(!radian){
		    window.location.href = 'infoA.php?bid='+sessionStorage.getItem('BUILDINGID');
		 }
	     sessionStorage.clear();
	});
}	

function createImage(){   
	var ctx = document.getElementById('canvas').getContext('2d'); //宣告變數找到頁面的canvas標籤的2d內容
	ctx.clearRect(0,0,1769,652);
	var img = new Image()
	img.src ='img/customizeImg.jpg';
	img.onload = function(){
	    ctx.drawImage(img,0,0);
	}
	var user=document.getElementById("customizeUser").value;
	if(user!=null && user!=undefined){
		$.post('php/generateInfo.php',{uid:user,block:true}).done(function(data){
			var tmp=$.parseJSON(data);
			ctx.font='30px "sans-serif"';
			ctx.fillStyle='#F5BD2F';
			console.log(tmp);
			ctx.fillText(buildingType(parseInt(tmp['cid'])), 150, 100);
			ctx.fillText('('+tmp['x']+','+tmp['y']+')',150,345);
			ctx.fillText(tmp['tag'],150,580);
			ctx.fillText(tmp['content'],1380,79);
			
			ctx.fillStyle='#70BAC0';
			ctx.fillText(tmp['name']+' 為共構城市建造了一座',600,80);
			
			ctx.font='40px "sans-serif"';
			ctx.fillStyle='#FFF';
			ctx.fillText(tmp['bname'],700,130);
		    
			var buildingImg = new Image();
			buildingImg.src='img/infoBtest.gif';
			ctx.drawImage(buildingImg,700,150);
		});
	}
}

function buildingType(data){
	switch(data){
	    case 0:
		  return '住宅類';
		  break;
		case 1:
		  return '活動類';
		  break;
		case 2:
		  return '服務及餐飲類';
		  break;
		case 3:
		  return '工業類';
		  break;
		case 4:
		  return '休閒娛樂類';
		  break;
		case 5:
		  return '宗教類';
		  break;
		case 6:
		  return '運動類';
		  break;
		case 7:
		  return '法律安全類';
		  break;
		case 8:
		  return '自然類';
		  break;
		case 9:
		  return '交通類';
		  break;
		case 10:
		  return '教育類';
		  break;
		default:
		  break;
	}
}