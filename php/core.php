<?php require_once('app.php');?>
<?php
function buildingType($type){
	switch($type){
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

class HTML{
	static function Head($page){
		echo '<head>
                <meta charset="UTF-8">
                <title>'.App::TITLE.'</title>
                <link rel="stylesheet" href="css/reset.css">				
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
				<link rel="stylesheet" href="css/animate.css">
                <link rel="stylesheet" href="css/core.css">				
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
				<script src="js/core.js"></script>'.
				
				($page!='0'?'<link rel="stylesheet" href="css/'.$page.'.css">
					         <script type="text/javascript" src="js/'.$page.'.js"></script>':'')
             .'</head>';
	}
	static function Header(){
		echo '<header>
			      <ul>
		             <li><a>nav1</a></li>
		             <li><a>nav2</a></li>
		             <li><a>nav3</a></li>
		             <li><a>nav4</a></li>
		             <li><a>nav5</a></li>
		          </ul>
			  </header>';
	}
	static function Footer(){
		echo '<footer>
			 </footer>';
	}
}

class PAD{
   static function Background($background){
	   self::WhichBuilding();
	   echo'<article class="background" style="background-image:url(\'img/'.($background=='infoA'||$background=='build'||$background=='project'?'infoB':$background).'Background.png\');">'.
		     self::Content($background)
		   .'</article>';
   }
   static function Content($text){
	   switch ($text){
		   case 'email':
		         return '<form class="emailForm" style="background-image:url(\'img/'.$text.'Box.png\');">
				           <input class="emailText" type="text" name="emailUsr"     placeholder="&nbsp;&nbsp;請輸入姓名" style="margin-top:110px;">
				           <input class="emailText" type="text" name="emailAddress" placeholder="&nbsp;&nbsp;請輸入email">
				           <input class="emailBtn" type="submit" value="">
						 </form>';
		         break;
		   case 'infoA':
		         $tmp='';
				 $tmp=($_GET['type']==1?1:2);//1=facility 2=house 
		         $outcome=self::WhichBuilding($_GET['bid']);
				 return self::skewText(1,$outcome['bname']).
					    self::rightTopBtn($tmp).
						'<div class="infoABox">'.
                           self::box('infoA',$outcome['bname'],buildingType($outcome['cid']),$outcome['content'],$outcome['tag'],$tmp).
						  '<div class="infoBBuilding"></div>
						   <div class="infoABoxBottom">
				              <div class="infoABuildingName" style="width:430px;">周遭分析 Analyze</div>
				              <div class="infoABuildingContext" style="width:430px">
							    <div class="infoABoxBottomBackground">
							    '.self::infoARank().'
								</div>
							  </div>
						   </div>   
						</div>';
		         break;
		   case 'infoB':
		         return  self::skewText(1,'Building Type').
					     self::rightTopBtn(1).
				         '<div class="infoBBox">
				             <div class="infoBTagRank">'.self::InfoBTagRank().'</div>
							 <div class="infoBBuilding"></div>
							 <div class="infoBBuildingRank">'.self::InfoBBuildingRank().'</div>'.
                             self::box('infoB','Build Name','Building Type','Building Context','Building Tag',0).
						 '</div>';
		         break;
		   case 'infoC':
		         $tmp='';
                 for($i=0;$i<100;$i++){$tmp.=self::InfoC();} 
                 return self::skewText(1,'#TAG').
					    self::skewText(0,'Number').
					    self::rightTopBtn(1).
					     '<div class="infoCBox">
				            <div class="infoCContainer">'.$tmp.'</div>  
					     </div>';		   
 		         break;
		   case 'build':
		         return self::skewText(1,'建造建築物').
					    self::rightTopBtn(0).
						'<div class="buildBox">
						    <div class="infoBBuilding"></div>							
							<div class="buildContainer">
							     <form class="buildForm">
								     <input class="buildFormName" type="text" placeholder="請輸入建築物名稱（限七字）..." maxlength="7" >
									 <select class="buildFormSelect">
									   <option value="none">請選擇建物類型</option>
									   <option value="活動類">活動類</option>
									   <option value="服務及餐飲類">服務及餐飲類</option>
									   <option value="工業類">工業類</option>
									   <option value="休閒娛樂類">休閒娛樂類</option>
									   <option value="宗教類">宗教類</option>
									   <option value="運動類">運動類</option>
									   <option value="法律及安全類">法律及安全類</option>
									   <option value="自然類">自然類</option>
									   <option value="交通類">交通類</option>
									   <option value="教育類">教育類</option>
									 </select>
									 <textarea class="buildFormTextarea" placeholder="介紹一下你的建築物吧"></textarea>
									 <input class="buildFormTag" type="text" placeholder="為你的建築物下標籤吧 如：#好高高 ＃巨X建築">
								 </form>
				            </div>
						</div>';
		         break;
		   case 'scan':
		         break;
		   case 'project':
		         return self::skewText(1,'BuildingType').
					    '<div class="projectBox">
	                       <div class="infoBTagRank">'.self::InfoBTagRank().'</div>
			               <div class="projectPop"><label>365</label></div>
				           <div class="infoBBuilding"></div>
				           <div class="infoBBuildingRank">'.self::InfoBBuildingRank().'</div>'.
                           self::box('infoB','Build Name','Building Type','Building Context','Building Tag',0).   
						'</div>';
		         break;	 	 	 	  	 
		   default:
		         break;
	   }
   }
   static function rightTopBtn($btnType){
	   //0=完成 1=建造 2=入住
	   switch($btnType){
		   case 0:
             return '<input class="finishBtn" type="button">';		     		   
		     break;
		   case 1:
	         return '<input class="buildBtn" type="button">';		     
		     break;
		   case 2:
             return '<input class="houseBtn" type="button">';		     
		     break;
		   default:
		     break;
	   }
   	
   }
   static function skewText($textSize,$textStr){
	   //1=large 0=small
	    return '<div class="textR '.($textSize==1?'textL':'textS').'">'.$textStr.($textSize==1?'':'次').'</div>';  	
   }
   static function box($infoType,$buildingName,$buildingType,$buildingContext,$buildingTag,$addition){
	    return '<div class="'.$infoType.'Container">
					<div class="'.$infoType.'BuildingName">'.$buildingName.'</div>
					<div class="'.$infoType.'BuildingT">'.$buildingType.($addition==2?'<div style="float:right; color:#fff; margin-right:10px;">已入住<label class="coYellow">44</label>戶 尚餘<label class="coYellow">6666</label>戶</div>':'').'</div>
					<div class="'.$infoType.'BuildingContext">'.$buildingContext.'</div>
				    <div class="'.$infoType.'BuildingT">'.$buildingTag.'</div>
				</div>';
   }
   static function infoARank(){
	    $tmp='<div class="infoABoxBottomLeft">';
		for($i=0;$i<6;$i++){
			if($i==3){
				$tmp.='</div><div class="infoABoxBottomRight">';
			}
			$tmp.='<div style="margin-top:16px;"><a>TAG'.$i.'</a></div>';
		}
		return $tmp.'</div>';
   }
   static function infoBRank($name,$number,$type,$index,$percent){
	    $RankMargin='';
		//0=tag 1=building
		if($type==0){
			$RankMargin='margin:'.($index==0?'40':'-20').'px 0 0 '.(50+$index*10).'px;';
		}
		else{
			$RankMargin='margin:'.($index==0||$index==5?'50':'-15').'px 0 0 '.(50+($index%5)*8).'px;';
		}
	    return'<div style="'.$RankMargin.'">
		         <label class="TagName">'.$name.'</label>
	             <label class="TagNumber"> '.$number.'</label>
		         <div class="progress">
                   <div class="progress-bar progress-bar-core" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%;"></div>
                 </div>
			   </div>';
			  
   }
   static function InfoBTagRank(){
	   $tmp='';
	   for($i=0;$i<3;$i++){
		   $tmp.=self::infoBRank('BuildingTag'.$i,$i,0,$i,$i*10+50);
	   }
	   return $tmp;
   	  
   }
   static function InfoBBuildingRank(){
	   $tmp='<div style="float:left; width:305px; height:200px;">';
	   for($i=0;$i<10;$i++){
		   if($i==5){
			   $tmp.='</div><div style="float:left; width:315px; height:200px;">';
		   }
		   $tmp.=self::infoBRank('BuildingRank'.$i,$i,1,$i,$i*10+10);
	   }
	   return $tmp.'</div>';
   	
   }
   static function InfoC(){
	   return'<div class="infoCContent">
		         <div class="infoCText">Zoo</div>
	             <a href=""><img class="infoCImg" src="img/infoCtest.png"></a>
				 <div class="infoCTag"><a href="">#cute</a><a href="">#cats</a><a href="">#animal</a><a href="">#orgasm</a><a href="">#sweety</a></div>
		      </div>';
   }
   static function WhichBuilding($bid){
	   App::db_connect();
	   $sql='SELECT * FROM user INNER JOIN building ON user.bid=building.bid WHERE user.bid=:bid';
	   $stmt=App::$dbn->prepare($sql);
	   $stmt->execute(array(
		   ":bid"=>$bid
	   ));
	   $result=$stmt->fetch(PDO::FETCH_ASSOC);
	   return $result;
   }
}
   



?>