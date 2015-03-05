document.addEventListener("touchstart", function(){}, true);

$(window).load(function(){
	
});

$(document).ready(function(){
	$('footer').css("margin-top",$(window).height());//html
});

$(window).resize(function(){
	$('footer').css("margin-top",$(window).height());//html
});

function uploadEmail(){
	var usr=document.getElementsByName("emailUsr")[0].value;
	var address=document.getElementsByName("emailAddress")[0].value;

    $.post("php/createEmail.php",{usr:usr,address:address,block:true}).done(function (data){
		alert('login success!! your ID is '+$.parseJSON(data));
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
					sessionStorage.setItem("USERID", data);
					window.location.href ='build.php';
				}
			});
	    }
		else{
			alert('invalid input');
		}
}
function finishBuilding(){
	var buildingName=document.getElementsByName("buildingName")[0].value;
	var buildingType=document.getElementById("typeSelector").value;
	var buildingContent=document.getElementsByName("buildingContent")[0].value;
	var buildingTag=document.getElementsByName("buildingTag")[0].value;
    
	$.post('php/createBuilding.php',{bname:buildingName,cid:buildingType,content:buildingContent,tag:buildingTag,block:true}).done(function(bid){
		sessionStorage.setItem("BID",bid);
		$.post('php/updateUser.php',{uid:sessionStorage.getItem("USERID"),bid:sessionStorage.getItem("BID"),block:true}).done(function(data){
	          localStorage.clear();
			  window.location.href = '';
		});
	});	

	
}