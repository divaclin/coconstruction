var radian =radian || false;
document.addEventListener("touchstart", function(){}, true);
document.addEventListener("touchend", function(){}, true);

$(document).ready(function(){
	if(!radian){
	   effectAnimate();
    }
	Audio = (function(){
		var $audio;
		$audio = void 0;
		function Audio(){
			$audio=$("#clickAudio");
		}
		
		Audio.prototype.playClick = function(){
			return $audio[0].play();	
		};
		return Audio;
	})();
	window.audio = new Audio();
	
	getAllTag();
	pressEffect();
		
	$(document).on('hidden.bs.modal','.modalResidingAfter ', function (){
		//console.log($('.modalResidingAfter').data('ajax'));
		switchController.ajaxPage($('.modalResidingAfter').data('ajax'),false);	
	}); 	 		
});



function recordAll(){
	$.post("php/recordAll.php").done(function(data){
		alert('success');
	});
}
function resetAll(){
	if(confirm("Sure to reset")==true){
	   $.post("php/reset.php").done(function(data){
	    	alert(data);
	   });
    }
	else{
		alert('make sure befor you press this bomb');
	}
}

function projectUpdate(){	
	$.post("php/projectUpdate.php",{block:true}).done(function(data){
      var json = JSON.parse(data);
	  console.log(json);
	  $('.projectPop').html('<label class="TagNumber" data-num="'+json['total']+'">0</label>');
	  for(var i=0;i<json['rankCid'].length;i++){
		  console.log(buildingType(parseInt(json['rankCid'][i]['cid'])));
		  console.log("data-num="+json['rankCid'][i]['count']);
		  var tmp='<label style="font-size:18px;" class="TagName navToinfoA">';
		      tmp+=    '<a href="infoB.php?cid='+json['rankCid'][i]['cid']+'" data-ajax="page=infoB&cid='+json['rankCid'][i]['cid']+'" class="toLookUp type">'+buildingType(parseInt(json['rankCid'][i]['cid']))+'</a>';
		      tmp+= '</label>';
		      tmp+='<label style="font-size:18px;" class="TagNumber" data-num="'+json['rankCid'][i]['count']+'">0</label>';
		      tmp+='<div class="progress">';
	          tmp+=    '<div class="progress-bar progress-bar-core" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 20px;" value="'+(100*json['rankCid'][i]['count']/json['cidTotal'])+'"></div>';
		      tmp+='</div>';
		  $('#projectCidRank'+i).html(tmp);
	  }
	  for(var i=0;i<3;i++){
		  console.log(json['rankGid'][i]['gid']+" "+json['rankGid'][i]['gname']);
		  console.log("data-num="+json['rankGid'][i]['count']);
          var tmp='<label style="font-size:18px;" class="TagName navToinfoA">';
		      tmp+=    '<a href="infoB.php?cid='+json['rankGid'][i]['gid']+'" data-ajax="page=infoC&gid='+json['rankGid'][i]['gid']+'" class="toLookUp tag">'+json['rankGid'][i]['gname']+'</a>';
              tmp+='</label>';
              tmp+='<label style="font-size:18px;" class="TagNumber" data-num="'+json['rankGid'][i]['count']+'">0</label>';
              tmp+='<div class="progress">';
              tmp+=    '<div class="progress-bar progress-bar-core" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 20px;" value="'+(100*json['rankGid'][i]['count']/json['gidTotal'])+'"></div>';
              tmp+='</div>';
           $('#projectGidRank'+i).html(tmp);
	  }
	  effectAnimate();
	});
}

function projectHandler(){ 
   $.post("php/projectInfo.php",{block:true}).done(function (data){
	   sessionStorage.setItem("project",data);
	   if(sessionStorage.getItem("ProjectIndex")==null){
		   sessionStorage.setItem("ProjectIndex",0);
	   }
   });
   	   
   var index = parseInt(sessionStorage.getItem("ProjectIndex"));
   var json  = JSON.parse(sessionStorage.getItem("project"));
	   
       if($('.textR').text()!=buildingType(parseInt(json[index]['cid']))){
		   $('.textR').removeClass('bounceInLeftSkew');
		   $('.textR').addClass('bounceOutLeft');
		   
		   var t4 = setTimeout(function(){
 		       $('.textR').html(buildingType(parseInt(json[index]['cid'])));
   		       $('.textR').removeClass('bounceOutLeft');
   		       $('.textR').addClass('bounceInLeftSkew');
		   },3000);
       }
       $('.projectInfoBox').css({"height":"415px","background-size":"580px 415px"});
   	   $('.infoBBuildingContent').css("background-image","url('img/building/"+json[index]['iid']+".png')");
  	   $('.infoBBuildingContent').fadeIn(3000).delay(10000).fadeOut(3000);
       
	   var t = setTimeout(function(){
	       $('.projectBid').html(json[index]['bname']).fadeIn(3000);
   	       $('.projectCid').html(buildingType(parseInt(json[index]['cid']))).fadeIn(3000);						
  	       $('.projectContent').html(json[index]['content']).fadeIn(3000);						
  	       $('.projectGid').html(json[index]['tag']).fadeIn(3000);
		   var t2 = setTimeout(function(){
		       $('.projectInfoBox').css({"height":"0px","background-size":"580px 0px"});
		       $('.projectBid').html('').fadeOut(2000);
	   	       $('.projectCid').html('').fadeOut(2000);						
	  	       $('.projectContent').html('').fadeOut(2000);						
	  	       $('.projectGid').html('').fadeOut(2000);
		   },9000);
	   },3000);						
	   
  	   sessionStorage.setItem("ProjectIndex",(index+1>=json.length?0:index+1));
	   var t3 = setTimeout(projectHandler,18000);				
}
function pressEffect(){
	$('.emailBtn').on("mousedown touchstart",function(){
	     $('.emailBtnPress').addClass('emailPressImg');
	 	 audio.playClick();
	});
	$('.emailBtn').on("mouseup touchend",function(){
	     $('.emailBtnPress').removeClass('emailPressImg');
	}); 
	
	$('.infoBArrowF').on("mousedown touchstart",function(){
	     $('.infoBArrowF').css("background-image","url('img/infoBArrowFPressed.png')");
	 	 audio.playClick(); 
	});
	$('.infoBArrowF').on("mouseup touchend",function(){
	     $('.infoBArrowF').css("background-image","url('img/infoBArrowF.png')");
	});
	 
	$('.infoBArrow').on("mousedown touchstart",function(){
	     $('.infoBArrow').css("background-image","url('img/infoBArrowPressed.png')");
	 	 audio.playClick();	 
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
	audio.playClick();
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
   audio.playClick();
   
}

function getAllTag(){
   $.post("php/getTag.php",{block:true}).done(function (data){
   	   sessionStorage.setItem("token",data);
	   var json= JSON.parse(JSON.parse(sessionStorage.getItem("token")));
	   $('#input-facebook-theme').tokenInput(json,{theme: "facebook"});
	   //console.log(typeof(json));
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
	var device=localStorage.getItem("deviceid");
	if(second>0){
	    document.getElementById('tmpClock').innerHTML = '<p id="ClockP">'+parseInt(second)+'</p>';
		$.post("php/selectStatus.php",{block:true}).done(function (data){
			var json=$.parseJSON(data);
			for(var i=0; i<json.length;i++){
				if(json[i]['device']=='D' && json[i]['behavior']=='BUILD_UP_return' && json[i]['done'].indexOf(device)>0){
					 second=-1;
					 break;
				}
				if(json[i]['device']=='D' && json[i]['behavior']=='BUILD_UP_return' && json[i]['done'].indexOf(device)<0){
				   var bid=	$.parseJSON(json[i]['object']);
		   		   $.post("php/updateUser.php",{uid:sessionStorage.getItem('USERID'),bid:bid['bid'],block:true,device:localStorage.getItem("deviceid")}).done(function (data){
					      sessionStorage.setItem('BUILDINGID',bid['bid']);
						  if(!radian){
						     window.location.href ='build.php';
					      }
						  else{
							 //clearTimeout(startTime);
							 second=-1;
					  		 $('.modalCount').modal('hide');
							 switchController.closeNotification();
							 switchController.ajaxPage('page=build',false,getAllTag());
							 var t = setTimeout(getAllTag,3000);
						  }
				   });
				   break;
				}
			}
		});	
        var t = setTimeout(function(){startTime(second-.2)},200);
    }
	else if(second==0){
		$('.modalCount').modal('hide');
		$('.modalBuilding').modal('show');
		//history.back();	
	}
	else{
 		 $('.modalCount').modal('hide');
	}
}
function infoBUpdate(cid){
	$.post("php/getInfoB.php",{cid:cid,block:true}).done(function (data){
		 sessionStorage.setItem('infoB',data);
		 sessionStorage.setItem('infoBIndex',0); 	     
		 //console.log(data);
	});
}
function nextBox(next){
	     var typeList = $.parseJSON(sessionStorage.getItem('infoB')); 	     
         var current  = sessionStorage.getItem('infoBIndex');
	     var index=parseInt(current)+next;
		// console.log(typeList);
	 	 audio.playClick();
		 if(index>=typeList.length){
			 sessionStorage.setItem('infoBIndex',0);
			 index=0; 	     
		 }
		 else if(index<0){
			 sessionStorage.setItem('infoBIndex',typeList.length-1);
		 	 index=typeList.length-1;
		 }
		 else{
			 sessionStorage.setItem('infoBIndex',index);
		 }
		 
		 
		 $('.infoBBuildingContent').removeClass('fadeIn');
 		 $('.infoABBox').removeClass('bounceInRight bounceInLeft');	 		
		 if(next>0){
			$('.infoABBox').addClass('bounceOutRight');
			$('.infoBBuildingContent').addClass('fadeOut');
			var a=setTimeout(function(){
			                    $('.infoABBox').removeClass('bounceOutRight'); 
							    $('.infoABBox').addClass('bounceInLeft');
					  		    if(!(typeList[0]==null || undefined)){
								   $('.infoBBuildingName').html('');
					  		       $('.infoBBuildingContext').html('');
					  		       $('.alterTag').html('');
					  		       $('.infoBBuildingName').html('<a class="toLookUp building" style="color:#fff;" href="infoA.php?bid='+typeList[index]['bid']+'" data-ajax="page=infoA&bid='+typeList[index]['bid']+'">'+typeList[index]['bname']+'</a>');
					  		       $('.infoBBuildingContext').html(typeList[index]['content']);
					  		       $('.alterTag').html(typeList[index]['tag']);
								   $('.infoBBuildingContent').css("background-image","url(img/building/"+typeList[index]['iid']+".png)");
								   
								   $('#newCidRank1').attr("data-num",typeList[index]['analyze'][0]['num']);
								   $('#newCidRank2').attr("data-num",typeList[index]['analyze'][1]['num']);
								   $('#newCidRank3').attr("data-num",typeList[index]['analyze'][2]['num']);
								   
								   $('#newCidRankCid1').html('<a class="toLookUp type" href="infoB.php?cid='+typeList[index]['analyze'][0]['id']+'" data-ajax="page=infoB&cid='+typeList[index]['analyze'][0]['id']+'">'+buildingType(typeList[index]['analyze'][0]['id'])+'</a>');
								   $('#newCidRankCid2').html('<a class="toLookUp type" href="infoB.php?cid='+typeList[index]['analyze'][1]['id']+'" data-ajax="page=infoB&cid='+typeList[index]['analyze'][1]['id']+'">'+buildingType(typeList[index]['analyze'][1]['id'])+'</a>');
								   $('#newCidRankCid3').html('<a class="toLookUp type" href="infoB.php?cid='+typeList[index]['analyze'][2]['id']+'" data-ajax="page=infoB&cid='+typeList[index]['analyze'][2]['id']+'">'+buildingType(typeList[index]['analyze'][2]['id'])+'</a>');
								   
								   $('#newGidRank1').attr("data-num",typeList[index]['analyze'][3]['num']);
								   $('#newGidRank2').attr("data-num",typeList[index]['analyze'][4]['num']);
								   $('#newGidRank3').attr("data-num",typeList[index]['analyze'][5]['num']);
								   
								   $('#newGidRankCid1').html('<a class="toLookUp tag" href="infoC.php?gid='+typeList[index]['analyze'][3]['id']+'" data-ajax="page=infoC&gid='+typeList[index]['analyze'][3]['id']+'">'+typeList[index]['analyze'][3]['gname']+'</a>');
								   $('#newGidRankCid2').html('<a class="toLookUp tag" href="infoC.php?gid='+typeList[index]['analyze'][4]['id']+'" data-ajax="page=infoC&gid='+typeList[index]['analyze'][4]['id']+'">'+typeList[index]['analyze'][4]['gname']+'</a>');
								   $('#newGidRankCid3').html('<a class="toLookUp tag" href="infoC.php?gid='+typeList[index]['analyze'][5]['id']+'" data-ajax="page=infoC&gid='+typeList[index]['analyze'][5]['id']+'">'+typeList[index]['analyze'][5]['gname']+'</a>');
								   
						  	       $( ".TagNumber" ).each(function(){
						  	         	var numAnim = new countUp(this, 0, $(this).attr('data-num'));
						  	  	       numAnim.start();
						  	       });
							    }
								$('.infoBBuildingContent').removeClass('fadeOut');
								$('.infoBBuildingContent').addClass('fadeIn');
						    },1000);
			
		 }
		 else if(next<0){
 	 		$('.infoABBox').addClass('bounceOutLeft'); 
			$('.infoBBuildingContent').addClass('fadeOut');			
			var a=setTimeout(function(){
				                $('.infoABBox').removeClass('bounceOutLeft');
 	 		                    $('.infoABBox').addClass('bounceInRight');
					  		    if(!(typeList[0]==null || undefined)){
					  		       $('.infoBBuildingName').html('');
					  		       $('.infoBBuildingContext').html('');
					  		       $('.alterTag').html('');
					  		       $('.infoBBuildingName').html('<a style="color:#fff;" href="infoA.php?bid='+typeList[index]['bid']+'" data-ajax="page=infoA&bid='+typeList[index]['bid']+'">'+typeList[index]['bname']+'</a>');
					  		       $('.infoBBuildingContext').html(typeList[index]['content']);
					  		       $('.alterTag').html(typeList[index]['tag']);
								   $('.infoBBuildingContent').css("background-image","url(img/building/"+typeList[index]['iid']+".png)");
								   
								   $('#newCidRank1').attr("data-num",typeList[index]['analyze'][0]['num']);
								   $('#newCidRank2').attr("data-num",typeList[index]['analyze'][1]['num']);
								   $('#newCidRank3').attr("data-num",typeList[index]['analyze'][2]['num']);
								   
								   $('#newCidRankCid1').html('<a class="toLookUp type" href="infoB.php?cid='+typeList[index]['analyze'][0]['id']+'" data-ajax="page=infoB&cid='+typeList[index]['analyze'][0]['id']+'">'+buildingType(typeList[index]['analyze'][0]['id'])+'</a>');
								   $('#newCidRankCid2').html('<a class="toLookUp type" href="infoB.php?cid='+typeList[index]['analyze'][1]['id']+'" data-ajax="page=infoB&cid='+typeList[index]['analyze'][1]['id']+'">'+buildingType(typeList[index]['analyze'][1]['id'])+'</a>');
								   $('#newCidRankCid3').html('<a class="toLookUp type" href="infoB.php?cid='+typeList[index]['analyze'][2]['id']+'" data-ajax="page=infoB&cid='+typeList[index]['analyze'][2]['id']+'">'+buildingType(typeList[index]['analyze'][2]['id'])+'</a>');
								   
								   $('#newGidRank1').attr("data-num",typeList[index]['analyze'][3]['num']);
								   $('#newGidRank2').attr("data-num",typeList[index]['analyze'][4]['num']);
								   $('#newGidRank3').attr("data-num",typeList[index]['analyze'][5]['num']);
								   
								   $('#newGidRankCid1').html('<a class="toLookUp tag" href="infoC.php?gid='+typeList[index]['analyze'][3]['id']+'" data-ajax="page=infoC&gid='+typeList[index]['analyze'][3]['id']+'">'+typeList[index]['analyze'][3]['gname']+'</a>');
								   $('#newGidRankCid2').html('<a class="toLookUp tag" href="infoC.php?gid='+typeList[index]['analyze'][4]['id']+'" data-ajax="page=infoC&gid='+typeList[index]['analyze'][4]['id']+'">'+typeList[index]['analyze'][4]['gname']+'</a>');
								   $('#newGidRankCid3').html('<a class="toLookUp tag" href="infoC.php?gid='+typeList[index]['analyze'][5]['id']+'" data-ajax="page=infoC&gid='+typeList[index]['analyze'][5]['id']+'">'+typeList[index]['analyze'][5]['gname']+'</a>');
								   
						  	       $( ".TagNumber" ).each(function(){
						  	        	var numAnim = new countUp(this, 0, $(this).attr('data-num'));
						  	  	        numAnim.start();
						  	       });
							    }
								$('.infoBBuildingContent').removeClass('fadeOut');
								$('.infoBBuildingContent').addClass('fadeIn');
		                   },1000);
		 }		 			               		 
}
function uploadEmail(){
	var usr=document.getElementsByName("emailUsr")[0].value;
	var address=document.getElementsByName("emailAddress")[0].value;
	var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    
	audio.playClick();
	address=(address=='0'?'nccucoconstruction@gmail.com':address);
	//console.log(address);
    if(usr!='' && re.test(address)){
      $.post("php/createEmail.php",{usr:usr,address:address,block:true}).done(function (data){
	    	$('.returnId').empty();
		    $('.returnId').append('<label>'+$.parseJSON(data)+'</label>');
		    $('.modal').modal('toggle');
		    document.getElementsByName("emailUsr")[0].value='';
	        document.getElementsByName("emailAddress")[0].value='';	
      });
    }
}
function buildBuilding(){
	   $('.modalBuildTypeId').modal('hide');	    
   	   audio.playClick(); 
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
	var bid = $.parseJSON(localStorage.getItem("status"))['bid'];
	var bname = $.parseJSON(localStorage.getItem("status"))['bname']; 
	var userId = document.getElementsByName("inputIdReside")[0].value;
	//console.log(bid);
       if (userId != null){
	     	$.post('php/checkHouse.php',{bid:bid,uid:userId,block:true}).done(function(data){		    	
				if($.parseJSON(data)==0){
					$('.modalResiding').modal('show');
			 	    document.getElementsByName("inputIdReside")[0].value='';					
			    }
			    else{
				    $.post('php/statusHouse.php',{uuid:userId,bid:bid,block:true,device:localStorage.getItem("deviceid"),bname:bname}).done(function(data){
						$('.modalResidingAfter').modal('show');
				 	    document.getElementsByName("inputIdReside")[0].value='';
				    });
			   }
	     	});
       }
	   else{
		    $('.modalResiding').modal('show');
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
	var buildingTag=[];
	var index=0;
	var uuid=sessionStorage.getItem('USERID');
	//console.log(uuid);
	audio.playClick();
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
	
 	$.post('php/updateBuilding.php',{uuid:uuid,bname:buildingName,cid:buildingType,content:buildingContent,tag:JSON.stringify(buildingTag),bid:sessionStorage.getItem('BUILDINGID')
,block:true,device:localStorage.getItem("deviceid")}).done(function(data){
	    if(!radian){
		    window.location.href = 'infoA.php?bid='+sessionStorage.getItem('BUILDINGID');
		 }
		// var stat = switchController.prepareStatus("LOOK_UP","B");
		 switchController.openNotification();
 		 switchController.ajaxPage('page=infoA&bid='+sessionStorage.getItem('BUILDINGID'),false);	
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
	//console.log(user);
	if(user!=null && user!=undefined){
		$.post('php/generateInfo.php',{uid:user,block:true}).done(function(data){
			//console.log(data);
			var tmp=$.parseJSON(data);
			$("#customizeUserEmail").text(tmp['email']);
			
			ctx.font='30px "sans-serif"';
			//ctx.fillStyle='#F5BD2F';
			ctx.fillStyle='#FFF';
			//console.log(tmp);
			ctx.fillText(buildingType(parseInt(tmp['cid'])), 150, 100);
			ctx.fillText('('+tmp['x']+','+tmp['y']+')',150,345);
			ctx.fillText(tmp['tag'],150,580);
			
			for(var i=0; i<Math.ceil(tmp['content'].length/11);i++){
			   ctx.fillText(tmp['content'].substr(i*11,((i+1)*11>tmp['content'].length?tmp['content'].length:11)),1380,79+i*32);
		    }
			
			ctx.fillStyle='#70BAC0';
			ctx.fillText(tmp['name']+' 為共構城市建造了一座',600,80);
			
			ctx.font='40px "sans-serif"';
			ctx.fillStyle='#FFF';
			ctx.fillText(tmp['bname'],700,130);
		    
			var buildingImg = new Image();
			buildingImg.src='img/building/'+tmp['iid']+'.png';
			ctx.drawImage(buildingImg,700,40);
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
		  return '暫無類別';
		  break;
	}
}