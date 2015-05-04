var radian =radian || false;

document.addEventListener("touchstart", function(){
	$('.emailBtnPress').addClass('emailPressImg');
}, true);
document.addEventListener("touchend", function(){
	$('.emailBtnPress').removeClass('emailPressImg');
}, true);


$(window).load(function(){
	
});

$(document).ready(function(){
	if(!radian){
	   effectAnimate();
    }
	getAllTag();
	$('.emailBtn').mousedown(function(){
	     $('.emailBtnPress').addClass('emailPressImg');
	})
	$('.emailBtn').mouseup(function(){
	     $('.emailBtnPress').removeClass('emailPressImg');
	})
});

$(window).resize(function(){
	$('footer').css("margin-top",$(window).height());
});

function getAllTag(){
   $.post("php/getTag.php",{block:true}).done(function (data){});
   $('#input-facebook-theme').tokenInput('php/tag.json',{theme: "facebook"});
   console.log($('.buildFormTag').text());
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
		//history.back();	
	}
}
function infoBUpdate(cid,updateTime){
	$.post("php/getInfoB.php",{cid:cid,block:true}).done(function (data){
		 sessionStorage.setItem('infoB',data);
	 	 var typeList=$.parseJSON(sessionStorage.getItem('infoB')); 
	 	 var currentIndex=updateTime%typeList.length;
	     
		 $('.infoBContainer').removeClass('bounceInRight');
		 $('.infoBContainer').addClass('bounceOutRight');
	

		 
		 var t2=setTimeout(function(){
 		                   $('.infoBBuildingName').html('');
		                   $('.infoBBuildingContext').html('');
		                   $('.alterTag').html('');
		 	 	 		   $('.infoBBuildingName').html('<a style="color:#fff;" href="infoA.php?bid='+typeList[currentIndex]['bid']+'" data-ajax="page=infoA&bid='+typeList[currentIndex]['bid']+'">'+typeList[currentIndex]['bname']+'</a>');
		 				   $('.infoBBuildingContext').html(typeList[currentIndex]['content']);
		 				   $('.alterTag').html(typeList[currentIndex]['tag']);
			               $('.infoBContainer').removeClass('bounceOutRight');
		                   $('.infoBContainer').addClass('bounceInRight');
			               },1000);


		 
	 	 var t =setTimeout(function(){infoBUpdate(cid,updateTime+1);},10000);		 	
	});
}

function uploadEmail(){
	var usr=document.getElementsByName("emailUsr")[0].value;
	var address=document.getElementsByName("emailAddress")[0].value;

    $.post("php/createEmail.php",{usr:usr,address:address,block:true}).done(function (data){
		$('.returnId').empty()
		$('.returnId').append('<label>'+$.parseJSON(data)+'</label>');
		$('.modal').modal('toggle');
    });
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
	var buildingTag=document.getElementsByName("buildingTag")[0].value.split(" ");
    
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