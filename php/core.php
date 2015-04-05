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
				<script src="js/core.js"></script>
			</head>';
	}
	static function CustomizeImg(){
		echo '<canvas id="canvas" width="1769" height="652"></canvas>
			  <div style="margin:0 auto; text-align:center;">
			    <label>userID: <input id="customizeUser" type="text"></label>
		        <button class="btn btn-primary margin-left:10px;" onclick="createImage()">generate</button>
			  </div>';
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
	  // self::WhichBuilding();
	   echo'<article class="background" style="background-image:url(\'img/'.($background=='infoA'||$background=='build'||$background=='project'?'infoB':$background).'Background.png\');">'.
		     self::Content($background)
		   .'</article>';
   }
   static function Content($text){
	   switch ($text){
		   case 'email':
		         return '<form class="emailForm" style="background-image:url(\'img/'.$text.'Box.png\');">
				           <input required class="emailText" type="text" name="emailUsr"     placeholder="&nbsp;&nbsp;請輸入姓名" style="margin-top:110px;">
				           <input required class="emailText" type="text" name="emailAddress" placeholder="&nbsp;&nbsp;請輸入email">
				           <input class="emailBtn" type="button"  onclick="uploadEmail()">
						 </form>
						 <div class="modal fade">
						   <div class="modal-dialog">
						     <div class="modal-content">
						       <p class="returnId" ></p>
						     </div>
						   </div>
						 </div>';
		         break;
		   case 'infoA':
		         if(isset($_GET['bid'])){
		         $outcome=self::WhichBuilding($_GET['bid']);
				 return self::skewText(1,$outcome['bname']).
					    self::rightTopBtn($outcome['type']).
						'<input id="phpData" type="hidden" value="'.json_encode($outcome).'">
						 <div class="infoABox">
 						   <div class="infoABoxBottom">
 							    '.self::infoARank().'
 						   </div>   
		  				   <div class="infoBBuilding"></div>'.                          self::box('infoA',$outcome['bname'],$outcome['cid'],$outcome['content'],$outcome['bid'],$outcome['type'],$outcome['reside'],$outcome['total']).	   
						'</div>';
				 }
		         break;
		   case 'infoB':
		         if(isset($_GET['cid'])){
				 $outcome=self::WhichType($_GET['cid']);	 
		         return  self::skewText(1,buildingType($_GET['cid'])).
					     self::rightTopBtn(1).
				         '<div class="infoBBox">
				             <div class="infoBTagRank">'.self::InfoBTagRank().'</div>
							 <div class="infoBBuilding"></div>
							 <div class="infoBBuildingRank">'.self::InfoBBuildingRank().'</div>'.
                             self::box('infoB',$outcome['bname'],$_GET['cid'],$outcome['content'],$outcome['bid'],0,0,0).
						 '</div>';
				 }
		         break;
		   case 'infoC':
                 if(isset($_GET['gid'])){
			     App::db_connect();
			     $sqlC='SELECT * FROM tag WHERE gid=:gid ';
			     $stmtC=App::$dbn->prepare($sqlC);
			     $stmtC->execute(array(
			        ':gid'=>$_GET['gid']
			     ));
			     $resultC=$stmtC->fetch(PDO::FETCH_ASSOC);	 
		         $tmp='';
                 $tmp.=self::WhichTag($_GET['gid']); 
                 return self::skewText(1,$resultC['gname']).
					    self::skewText(0,$resultC['count']).
					    self::rightTopBtn(1).
					     '<div class="infoCBox">
				            <div class="infoCContainer">'.$tmp.'</div>  
					     </div>';
				 }		   
 		         break;
		   case 'build':
		         return self::skewText(1,'建造建築物').
					    self::rightTopBtn(0).
						'<div class="buildBox">
						    <div class="infoBBuilding"></div>							
							<div class="buildContainer">
							     <form class="buildForm">
								     <input class="buildFormName" name="buildingName" type="text" placeholder="請輸入建築物名稱（限七字）..." maxlength="7" >
									 <select class="buildFormSelect" id="typeSelector">
									   <option value="none">請選擇建物類型</option>
									   <option value="1">活動類</option>
									   <option value="2">服務及餐飲類</option>
									   <option value="3">工業類</option>
									   <option value="4">休閒娛樂類</option>
									   <option value="5">宗教類</option>
									   <option value="6">運動類</option>
									   <option value="7">法律及安全類</option>
									   <option value="8">自然類</option>
									   <option value="9">交通類</option>
									   <option value="10">教育類</option>
									 </select>
									 <textarea class="buildFormTextarea" name="buildingContent" placeholder="介紹一下你的建築物吧"></textarea>
									 <input class="buildFormTag" name="buildingTag" type="text" placeholder="為你的建築物下標籤吧 如：#水岸怒神 ＃大根君">
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
                           self::box('infoB','Build Name','Building Type','Building Context','Building Tag',0,0,0).   
						'</div>';
		         break;	 	 	 	  	 
		   case 'count':
		         return '<div id="tmpClock"></div>';
				 break;
		   default:
		         break;
	   }
   }
   static function rightTopBtn($btnType){
	   //0=完成 1=建造 2=入住
	   switch($btnType){
		   case 0:
             return '<input class="finishBtn" type="button" onclick="finishBuilding()">';		     		   
		     break;
		   case 1:
	         return '<input class="buildBtn" type="button" onclick="buildBuilding()">';		     
		     break;
		   case 2:
             return '<input class="houseBtn" type="button" onclick="resideHouse()">';		     
		     break;
		   default:
		     break;
	   }
   	
   }
   static function skewText($textSize,$textStr){
	   //1=large 0=small
	    return '<div class="textR '.($textSize==1?'textL textTypingL':'textS').'">'.$textStr.($textSize==1?'':'次').'</div>';  	
   }
   static function box($infoType,$buildingName,$buildingType,$buildingContext,$buildingTag,$addition,$addReside,$addTotal){
        App::db_connect();
        $sql='SELECT * FROM gidtobid INNER JOIN tag ON gidtobid.gid=tag.gid WHERE bid=:bid ORDER BY count DESC';
        $stmt=App::$dbn->prepare($sql);
        $stmt->execute(array(
        	':bid'=>$buildingTag
        ));
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		$tagList='';
		for($i=0;$i<count($result);$i++){
			$tagList.='<a href="infoC.php?gid='.$result[$i]['gid'].'">'.$result[$i]['gname'].'  </a>';
		}
		
	    return '<div class="'.$infoType.'Container animated  bounceInRight">
					<div class="'.$infoType.'BuildingName">'.$buildingName.'</div>
					<div class="'.$infoType.'BuildingT toLookUp type"  data-href="infoB.php?cid=1"><a href="infoB.php?cid='.$buildingType.'">'.buildingType($buildingType).'</a>'.($addition==2?'<div style="float:right; color:#fff; margin-right:10px;">已入住<label class="coYellow">'.$addReside.'</label>戶 尚餘<label class="coYellow">'.($addTotal-$addReside).'</label>戶</div>':'').'</div>
					<div class="'.$infoType.'BuildingContext">'.$buildingContext.'</div>
				    <div class="'.$infoType.'BuildingT toLookUp tag"><div class="'.$infoType.'innerBox">'.$tagList.'</div></div>
				</div>';
   }
   static function infoARank(){
        App::db_connect();
        $sql='SELECT cid,count FROM color WHERE 1 ORDER BY count DESC';
        $stmt=App::$dbn->prepare($sql);
        $stmt->execute(array());
        $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	   
	    $totalCount=0;
	    for($i=0;$i<count($result);$i++){
		   $totalCount+=$result[$i]['count'];
	    }
		
        $sql2='SELECT * FROM tag WHERE 1 ORDER BY count DESC';
        $stmt2=App::$dbn->prepare($sql2);
        $stmt2->execute(array());
        $result2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
	   
	    $totalCount2=0;
	    for($i=0;$i<count($result2);$i++){
		   $totalCount2+=$result2[$i]['count'];
	    }		
		
	    $tmp='<div class="infoABoxBottomLeft">';
		for($i=0;$i<6;$i++){
			if($i==3){
				$tmp.='</div><div class="infoABoxBottomRight">';
			}
			$tmp.='<div style="margin-top:3px; margin-left:'.($i*7).'px;"><a class="toLookUp tag" href="'.($i<3?'infoB.php?cid='.$result[$i]['cid']:'infoC.php?gid='.$result2[$i]['gid']).'">'.($i<3?buildingType($result[$i]['cid']):$result2[$i%3]['gname']).'</a><label style="margin-left:10px; color:#C4E4E8;">'.($i<3?$result[$i]['count']:$result2[$i%3]['count']).' 次</label><div class="progress" style="margin:-5px 0 0 0;">
                   <div class="progress-bar progress-bar-core" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:'.($i<3?floatval($result[$i]['count']/$totalCount)*100:floatval($result2[$i%3]['count']/$totalCount2)*100).'%;" value="'.($i<3?floatval($result[$i]['count']/$totalCount)*100:floatval($result2[$i%3]['count']/$totalCount2)*100).'"></div>
                 </div></div>';
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
		         <label class="TagName navToinfoA">'.($type==1?'<a href="infoB.php?cid='.$name.'">'.buildingType($name).'</a>':'<a href="infoC.php?gid='.$name['gid'].'">'.$name['gname'].'</a>').'</label>
	             <label class="TagNumber"> '.$number.'</label>
		         <div class="progress">
                   <div class="progress-bar progress-bar-core" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:'.$percent.'%;" value="'.$percent.'"></div>
                 </div>
			   </div>';
			  
   }
   static function InfoBTagRank(){
	   $tmp='';
       $sql2='SELECT * FROM tag WHERE 1 ORDER BY count DESC';
       $stmt2=App::$dbn->prepare($sql2);
       $stmt2->execute(array());
       $result2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
   
       $totalCount2=0;
       for($i=0;$i<count($result2);$i++){
	      $totalCount2+=$result2[$i]['count'];
       }
	   for($i=0;$i<3;$i++){
		   $tmp.=self::infoBRank($result2[$i],$result2[$i]['count'].' 次',0,$i,floatval($result2[$i]['count']/$totalCount2)*100);
	   }
	   return $tmp;
   	  
   }
   static function InfoBBuildingRank(){
       App::db_connect();
       $sql='SELECT cid,count FROM color WHERE 1 ORDER BY count DESC';
       $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array());
       $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	   
	   $totalCount=0;
	   for($i=0;$i<count($result);$i++){
		   $totalCount+=$result[$i]['count'];
	   }
	   $tmp='<div style="float:left; width:305px; height:200px;">';
	   for($i=0;$i<10;$i++){
		   if($i==5){
			   $tmp.='</div><div style="float:left; width:315px; height:200px;">';
		   }
		   $tmp.=self::infoBRank($result[$i]['cid'],$result[$i]['count'],1,$i,floatval($result[$i]['count']/$totalCount)*100);
	   }
	   return $tmp.'</div>';
   	
   }
   static function WhichTag($gid){
	   $tmp='';
	   if(isset($gid)){	
  	      App::db_connect();
  	      $sql='SELECT bid FROM gidtobid WHERE gid=:gid';
  	      $stmt=App::$dbn->prepare($sql);
  	      $stmt->execute(array(
  		    ":gid"=>$gid
  	      ));
  	      $result=$stmt->fetchALL(PDO::FETCH_ASSOC);
		  
		  for($i=0;$i<count($result);$i++){
	  	      $sql2='SELECT cid,bid,bname FROM building WHERE bid=:bid';
	  	      $stmt2=App::$dbn->prepare($sql2);
	  	      $stmt2->execute(array(
	  		    ":bid"=>$result[$i]['bid']
	  	      ));
	  	      $result2=$stmt2->fetch(PDO::FETCH_ASSOC);
			  $tmp.='<div class="infoCContent">
		               <div class="infoCText"><a href="infoA.php?bid='.$result2['bid'].'">'.$result2['bname'].'</a></div>
	                   <a href=""><img class="infoCImg" src="img/infoCtest.png"></a>
				       <div class="infoCTag"><div class="infoCinnerBox">';
			  
			  $sql3='SELECT * FROM gidtobid  INNER JOIN tag ON tag.gid=gidtobid.gid WHERE bid=:bid';
			  $stmt3=App::$dbn->prepare($sql3);
			  $stmt3->execute(array(
			   		  ":bid"=>$result2['bid']
			  ));
			  $result3=$stmt3->fetchALL(PDO::FETCH_ASSOC);
			  
			  for($j=0;$j<count($result3);$j++){
				  $tmp.='<a href="infoC.php?gid='.$result3[$j]['gid'].'">'.$result3[$j]['gname'].' </a>';
			  }
			  		   
		      $tmp.='</div></div></div>';
		  }  
	   return $tmp;
	  }
   }
   static function WhichBuilding($bid){
	   if(isset($bid)){
	     App::db_connect();
	     $sql='SELECT bid,bname,content,cid,type,reside,total FROM building WHERE bid=:bid';
	     $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
		    ":bid"=>$bid
	     ));
	     $result=$stmt->fetch(PDO::FETCH_ASSOC);
	     return $result;
       }
   }
   static function WhichType($cid){
	   if(isset($cid)){
	     App::db_connect();
	     $sql='SELECT  bid,bname,content,type,reside,total FROM building WHERE cid=:cid';
	     $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
		    ":cid"=>$cid
	     ));
	     $result=$stmt->fetch(PDO::FETCH_ASSOC);
	     return $result;
       }
   }
}
   



?>