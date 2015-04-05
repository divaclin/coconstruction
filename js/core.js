document.addEventListener("touchstart", function(){}, true);

$(window).load(function(){
	
});

$(document).ready(function(){
	$('footer').css("margin-top",$(window).height());
});

$(window).resize(function(){
	$('footer').css("margin-top",$(window).height());
});

function startTime(second) {
    document.getElementById('tmpClock').innerHTML = parseInt(second);
	if(second>0){
		$.post("php/selectStatus.php",{block:true}).done(function (data){
			var json=$.parseJSON(data);
			for(var i=0; i<json.length;i++){
				if(json[i]['device']=='D' && json[i]['behavior']=='BUILD_UP_return' && json[i]['done'].indexOf('Z')==-1){
				   var bid=	$.parseJSON(json[i]['object']);
		   		   $.post("php/updateUser.php",{uid:sessionStorage.getItem('USERID'),bid:bid['bid'],block:true}).done(function (data){
					      sessionStorage.setItem('BUILDINGID',bid['bid']);
						  window.location.href ='build.php';
				   });
				}
			}
		});	
       var t = setTimeout(function(){startTime(second-.2)},200);
    }
	else{
		history.back();	
	}
}
function infoBUpdate(cid,updateTime){
	$.post("php/getInfoB.php",{cid:cid,block:true}).done(function (data){
		 sessionStorage.setItem('infoB',data);
	
	 	 var typeList=$.parseJSON(sessionStorage.getItem('infoB')); 
	 	 var currentIndex=updateTime%typeList.length;
	
	
	
	 	 $('.infoBBuildingName').html(typeList[currentIndex]['bname']);
	 	 $('.infoBBuildingContext').html(typeList[currentIndex]['content']);
	 	 $('.tag').html(typeList[currentIndex]['tag']);
		 
		 $('.infoBBuildingName').removeClass('animated fadeIn');
		 $('.infoBBuildingContext').removeClass('animated fadeIn');
		 $('.tag').removeClass('animated fadeIn');
		 
		 $('.infoBBuildingName').addClass('animated fadeIn');
		 $('.infoBBuildingContext').addClass('animated fadeIn');
		 $('.tag').addClass('animated fadeIn');
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
}
function buildBuilding(){
	var userId = prompt("Please enter your ID", "");
	    if (userId != null){
			$.post('php/checkId.php',{uid:userId,block:true}).done(function(data){
				if($.parseJSON(data)==0){
					alert('User do not exist OR had built');
				}
				else{
					$.post('php/statusBuildup.php',{uuid:userId,block:true}).done(function(data){});
					sessionStorage.setItem("USERID", data);
					window.location.href ='count.php';
				}
			});
	    }
		else{
			alert('invalid input');
		}
}
function resideHouse(){
	var bid = getParameterByName('bid');
	var userId = prompt("Please enter your ID", "");
       if (userId != null){
	     	$.post('php/checkHouse.php',{bid:bid,uid:userId,block:true}).done(function(data){
		    	if($.parseJSON(data)==0){
			    	alert('User do not exist OR had settled down OR no space');
			    }
			    else{
				    $.post('php/statusHouse.php',{uuid:userId,bid:bid,block:true}).done(function(data){
				    	alert('you are our citizen now');
				    });
			   }
	     	});
       }
	   else{
	    	alert('invalid input');
	   }
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
	var buildingTag=document.getElementsByName("buildingTag")[0].value;
    
	$.post('php/updateBuilding.php',{bname:buildingName,cid:buildingType,content:buildingContent,tag:buildingTag,bid:sessionStorage.getItem('BUILDINGID')
,block:true}).done(function(bid){
	          localStorage.clear();
			  window.location.href = 'infoA.php?bid='+sessionStorage.getItem('BUILDINGID');
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