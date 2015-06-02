<?php require_once('app.php');?>
<?php
function buildingType($type){
	switch($type){
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

class HTML{
	static function Head($page){
		echo '<head>
                <meta charset="UTF-8">
	            <meta http-equiv="cache-control" content="max-age=0" />
	            <meta http-equiv="cache-control" content="no-cache" />
	    	    <meta http-equiv="expires" content="0" />
	    	    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	    	    <meta http-equiv="pragma" content="no-cache" />
                <title>'.App::TITLE.'</title>
                <link rel="stylesheet" href="css/reset.css">				
                <link rel="stylesheet" href="css/bootstrap.min.css">
                <link rel="stylesheet" href="css/bootstrap-theme.min.css">
                <link rel="stylesheet" href="css/token-input-facebook.css">				
				<link rel="stylesheet" href="css/animate.css">
                <link rel="stylesheet" href="css/core.css">				
                <script src="js/jquery.min.js"></script>
                <script src="js/bootstrap.min.js"></script>
                <script src="js/jquery.tokeninput.js"></script>
                <script src="js/countUp/countUp.min.js"></script>
				<script src="js/core.js"></script>
			</head>';
	}
	static function CustomizeImg(){
		echo '<canvas style="overflow:scroll;" id="canvas" width="1769" height="652" onload="createImage()"></canvas>
			  <div style="margin:0 auto; text-align:center;">
			    <label>userID: <input id="customizeUser" type="text"></label>
			    <label>email : </label><label id="customizeUserEmail"></label>
				</br>
		        <button class="btn btn-primary" style="margin-left:10px;" onclick="createImage()">generate</button>
				<button class="btn btn-success" onclick="recordAll()">recordAll</button>
				<button class="btn btn-danger" onclick="resetAll()">resetAll</button>
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
	   switch ($background){
		   case 'email':
		        echo'<audio style="display:none;" id="clickAudio" controls><source src="audio/click.mp3" type="audio/mpeg">Your browser does not support the audio tag.</audio><article class="background" style="background-image:url(\'img/emailBackground.gif\');" >'.self::Content($background).'</article>';
		        break;
		   case 'project':
                echo'<audio style="display:none;" id="clickAudio" controls><source src="audio/click.mp3" type="audio/mpeg">Your browser does not support the audio tag.</audio><script src="js/radian.js"></script><div id="radian"></div><article class="projectBackground" style="background:#000; background-image:url(\'img/projectBackground.gif\');" >'.self::Content($background).'</article>';   
		        break;
		   default:
 		        echo /*'<article class="background">'.self::Content($background).'</article>';*/'<video width="1024" height="768" autoplay="autoplay" loop>
 		                <source src="img/'.$background.'Background.mp4" type="video/mp4">
 		                 Your browser does not support the video tag.
 		              </video>
 		             <audio style="display:none;" id="clickAudio" controls><source src="audio/click.mp3" type="audio/mpeg">Your browser does not support the audio tag.</audio><article class="background">'.self::Content($background).'</article>';
		        break;
	   }
		 
   }
   static function Content($text){
	   switch ($text){
		   case 'email':
		         return '<form class="emailForm" style="background-image:url(\'img/'.$text.'Box.png\');">
				           <input required class="emailText" type="text" name="emailUsr"     placeholder="&nbsp;&nbsp;請輸入姓名" style="margin-top:110px;">
				           <input required class="emailText" type="email" name="emailAddress" placeholder="&nbsp;&nbsp;請輸入email" autofocus>
				           <div class="emailBtnPress">
						     <input class="emailBtn" type="button"  onclick="uploadEmail()">
						   </div>
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
				 return '<div  style="font-size:20px; margin:60px 0 0 30px;" class="animated bounceInLeftSkew textR">建物名稱</div>'.
					    self::skewText(1,$outcome['bname']).
					    self::rightTopBtn($outcome['type']).
						'<script> localStorage.setItem("status",JSON.stringify('.json_encode($outcome).'));</script>
						 <div class="infoABox">
 						   '.self::buildingImgGenerator($outcome['iid']).                          self::box('infoA',$outcome['bname'],$outcome['cid'],$outcome['content'],$outcome['bid'],$outcome['type'],$outcome['reside'],$outcome['total']).	   
						'</div>'.self::Content('modal');
				 }
		         break;
		   case 'infoB':
		         $noCid='<p class="coYellow toLookUp building" href="infoA.php?bid=1" data-ajax="page=infoA&bid=1">目前無此類別的建築物，請按右上角的「建造」按鈕進行建造或是拿起「桌上的建築物」查詢資訊。</p>';
		         if(isset($_GET['cid'])){
				 $outcome=self::WhichType($_GET['cid']);
                 $statusObj=array(
					 "cid"=>$_GET['cid'],
					 "tname"=>buildingType($_GET['cid'])
                 );	 
		         return '<script>infoBUpdate('.$_GET['cid'].');</script>
					     <div class="infoBArrowF" onclick="nextBox(-1)"></div>
					     <div class="infoBArrow"  onclick="nextBox(1)"></div>
					     <div  style="font-size:20px; margin:60px 0 0 30px;" class="animated bounceInLeftSkew textR">建物類型</div>'.
					     self::skewText(1,buildingType($_GET['cid'])).
					     self::rightTopBtn(1).
	 					'<script> localStorage.setItem("status",JSON.stringify('.json_encode($statusObj).'));</script>
				         <div class="infoBBox">'
				             .self::buildingImgGenerator($outcome['iid'])
                             .self::box('infoB',$outcome['bname'],$_GET['cid'],($outcome['content']==null?$noCid:$outcome['content']),$outcome['bid'],0,0,0).
						 '</div>'.self::Content('modal');
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
                 return '<div  style="font-size:20px; margin:60px 0 0 30px;" class="animated bounceInLeftSkew textR">標籤名稱</div>'.
					    self::skewText(1,$resultC['gname']).
					    self::skewText(0,$resultC['count']).
					    self::rightTopBtn(1).
						'<script> localStorage.setItem("status",JSON.stringify('.json_encode($resultC).'));</script>
					     <div class="infoCBox">
				            <div class="infoCContainer">'.$tmp.'</div>  
					     </div>'.self::Content('modal');
				 }		   
 		         break;
		   case 'build':
		         return '<div  style="font-size:20px; margin:60px 0 0 30px;" class="animated bounceInLeftSkew textR">改變城市</div>'.
					    self::skewText(1,'建造建築物').
					    self::rightTopBtn(0).
						'<div class="buildBox">
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
									 <ul id="input-facebook-theme" class="buildFormTag" name="buildingTag" type="hidden" placeholder="只要用空格隔開就可以下tag摟，每個tag要小於六個字">
									 </ul>
								 </form>
				            </div>
						</div>';
		         break;
		   case 'project':
		         if(isset($_GET['bid'])){
			     $outcome=self::WhichBuilding($_GET['bid']);
		         App::db_connect();
		         $sql='SELECT * FROM building WHERE total > 1';
		         $stmt=App::$dbn->prepare($sql);
		         $stmt->execute(array());
		         $result=$stmt->fetchAll(PDO::FETCH_ASSOC);	 
				 $population=0;
				 for($i=0;$i<count($result);$i++){
					 $population+=$result[$i]['total'];
				 }
				 return '<script>projectUpdate();</script>'. 
					 self::skewText(1,buildingType($outcome['type'])).
				    '<div class="projectBox">
						 <div class="projectInfoBox">
				            <div class="projectBid"></div>
						    <div class="projectCid"></div>
						    <div class="projectContent"></div>
						    <div class="projectGid"></div>
						 </div>
			             <div class="infoBBuildingRankP" style="margin:60px 205px 5px 100px;">'.self::InfoBBuildingRank().'</div>'
			             .self::buildingImgGenerator($outcome['iid']).
						 '<div class="infoBTagRankP" style="margin:15px 205px 5px 100px; padding:35px 0 0 20px;">'.self::InfoBTagRank().'</div>
  			              <div class="projectPop"><label class="TagNumber" data-num="'.$population.'">'.$population.'</label></div>	 
					 </div>';
				 }
		         break;	 
				 	 	 	  	 
		   case 'modal':
		         return '<div style="padding-top:120px; position:absolute; height:200vh;" class="modal modalCount fade" data-backdrop="static" data-keyboard="false">
						   <div class="modal-dialog">
						     <div class="modal-content2">
                                <p id="tmpClock"></p>
						     </div>
						   </div>
						 </div>
					     <div style="margin-top:149px; position:absolute; height:200vh;" class="modal modalBuildTypeId fade">
						   <div class="modal-dialog">
						     <div class="modal-content4">
  				                 <input required style="margin-top:100px; margin-left:120px;" class="emailText" type="text" name="inputIdBuild" placeholder="&nbsp;&nbsp;請輸入id">
	  				             <div class="emailBtnPress">
							       <input class="emailBtn" type="button"  onclick="setTimeout(function(){buildBuilding();},1000);">
							     </div>
						     </div>
						   </div>
						 </div>
						 <div style="margin-top:149px; position:absolute; height:200vh;" class="modal modalResideTypeId fade">
						 	<div class="modal-dialog">
						 		<div class="modal-content4">
						   			  <input required style="margin-top:100px; margin-left:120px;" class="emailText" type="text" name="inputIdReside" placeholder="&nbsp;&nbsp;請輸入id">   
		   				              <div class="emailBtnPress">
	 							        <input class="emailBtn" type="button"  onclick="setTimeout(function(){resideHouse();},1000);">
									  </div>
						 		</div>
						 	</div>
						 </div>
					     <div style="padding-top:120px; position:absolute; height:200vh;" class="modal modalBuilding fade">
						   <div class="modal-dialog">
						     <div class="modal-content3">
                                <p id="buildFail">輸入失敗/無此id/此id已建置建築物</p>
						     </div>
						   </div>
						 </div>
					     <div style="padding-top:120px; position:absolute; height:200vh;" class="modal modalResiding fade">
						   <div class="modal-dialog">
						     <div class="modal-content3">
                                <p id="buildFail" style="font-size:15px;">輸入失敗/無此id/此id已入住/此建築已額滿</p>
						     </div>
						   </div>
						 </div>
					     <div style="padding-top:120px; position:absolute; height:200vh;" class="modal modalResidingAfter fade" data-ajax="page=infoA&bid='.(isset($_GET['bid'])?$_GET['bid']:'1').'">
						   <div class="modal-dialog">
						     <div class="modal-content3">
                                <p id="buildFail">入住成功，歡迎成為共構的一份子</br>頁面即將更新</p>
						     </div>
						   </div>
						 </div>';
				 break;
		   default:
		         break;
	   }
   }
   static function rightTopBtn($btnType){
	   //0=完成 1=建造 2=入住
	   switch($btnType){
		   case 0:
             return '<input class="finishBtn toFinish" type="button" onclick="finishBuilding()">';		     		   
		     break;
		   case 1:
	         return '<input class="buildBtn toBuildUp" type="button" onclick="audio.playClick(); $(\'.modalBuildTypeId\').modal(\'show\');
" data-ajax="page=build">';		     
		     break;
		   case 2:
             return '<input class="houseBtn toHouse" type="button" onclick="audio.playClick(); $(\'.modalResideTypeId\').modal(\'show\');">';		     
		     break;
		   default:
		     break;
	   }
   	
   }
   static function skewText($textSize,$textStr){
	   //1=large 0=small
	    return '<div '.(isset($_GET['project'])?'style="margin-left:250px;"':'').' class="animated bounceInLeftSkew textR '.($textSize==1?'textL':'textS').'">'.$textStr.($textSize==1?'':'次').'</div>';  	
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
			$tagList.='<a href="infoC.php?gid='.$result[$i]['gid'].'" data-ajax="page=infoC&gid='.$result[$i]['gid'].'" class="toLookUp tag">'.$result[$i]['gname'].'  </a>';
		}
		
		$tabNav='';
		if($infoType=='infoA'){
		   	$tabNav='<div id="navBox1" class="navTab navTabActive" onclick="navBox1()">建物介紹</div><div id="navBox2" class="navTab" style="top:195px;" onclick="navBox2()">周遭分析</div>';
		}
		else{
		    $tabNav='<div id="navBox1" class="navTab navTabActive" onclick="navBox1()">建物介紹</div><div id="navBox2" class="navTab" style="top:195px;" onclick="navBox2()">類型排行</div>';
		}
		$strTmp='<div class="infoABBox animated  bounceInRight">'.$tabNav;
		
	    $strTmp.='<div id="box1" class="'.$infoType.'Container ">
				    <div class="'.$infoType.'BuildingName"><a style="color:#fff;" class="toLookUp building" href="infoA.php?bid='.$buildingTag.'" data-ajax="page=infoA&bid='.$buildingTag.'">'.$buildingName.'</a></div>
					<div class="'.$infoType.'BuildingT"><a href="infoB.php?cid='.$buildingType.'" data-ajax="page=infoB&cid='.$buildingType.'" class="toLookUp type">'.buildingType($buildingType).'</a>'.($addition==2?'<div style="float:right; color:#fff; margin-right:10px;">已入住<label class="coYellow">'.$addReside.'</label>戶 尚餘<label class="coYellow">'.($addTotal-$addReside).'</label>戶</div>':'').'</div>
					<div class="'.$infoType.'BuildingContext">'.$buildingContext.'</div>
				    <div class="'.$infoType.'BuildingT alterTag"><div class="'.$infoType.'innerBox">'.$tagList.'</div></div>
				</div>';
		
		$strTmp.='<div id="box2" class="'.$infoType.'SecondBox">
			        <div '.(isset($_GET['cid'])?'style="width:250px; left:85px;"':'').' class="bidRank">
		              <div class="box2Ranking">類型排行榜 Ranking</div>'
                      .self::newCidRank($buildingTag).
				   '</div>
		            <div '.(isset($_GET['cid'])?'style="width:250px; left:340px;"':'').' class="tagRank">
	                  <div class="box2Ranking">標籤排行榜 Ranking</div>'
					  .self::newGidRank($buildingTag).
					 '</div>
			      </div>';
	    			  
		return $strTmp.'</div>';
   }
   static function infoARank(){
        App::db_connect();
        $sql='SELECT cid,count FROM color WHERE cid!=0 ORDER BY count DESC';
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
			$tmp.='<div style="margin-top:5px; margin-left:'.($i*7).'px;"><a class="toLookUp '.($i<3?'type':'tag').'" href="'.($i<3?'infoB.php?cid='.$result[$i]['cid']:'infoC.php?gid='.$result2[$i]['gid']).'" data-ajax="'.($i<3?'page=infoB&cid='.$result[$i]['cid']:'page=infoC&gid='.$result2[$i%3]['gid']).'">'.($i<3?buildingType($result[$i]['cid']):$result2[$i%3]['gname']).'</a><label style="margin-left:10px;" class="TagNumber" data-num="'.($i<3?$result[$i]['count']:$result2[$i%3]['count']).'">0</label><div class="progress" style="margin:0 0;">
                   <div class="progress-bar progress-bar-core" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%;" value="'.($i<3?floatval($result[$i]['count']/$totalCount)*100+20:floatval($result2[$i%3]['count']/$totalCount2)*100+20).'"></div>
                 </div></div>';
		}
		return $tmp.'</div>';
   }
   static function infoBRank($name,$number,$type,$index,$percent){
	    $RankMargin='';
		//0=tag 1=building
		if($type==0){
			$RankMargin='margin:'.($index==0?'20':'-20').'px 0 20px '.(50+$index*10).'px;';
		}
		else{
			$RankMargin='margin:'.($index==0||$index==5?'50':'-15').'px 0 0 '.(50+($index%5)*8).'px;';
		}
        
		
	    return'<div '.($type==1?'id="projectCidRank'.$index.'"':'id="projectGidRank'.$index.'"').' style="'.$RankMargin.'">
		         <label '.(isset($_GET['project'])?'style="font-size:18px;"':'').' class="TagName navToinfoA">'.($type==1?'<a href="infoB.php?cid='.$name.'" data-ajax="page=infoB&cid='.$name.'" class="toLookUp type">'.buildingType($name).'</a>':'<a href="infoC.php?gid='.$name['gid'].'" class="toLookUp tag" data-ajax="page=infoC&gid='.$name['gid'].'">'.$name['gname'].'</a>').'</label>
	             <label '.(isset($_GET['project'])?'style="font-size:18px;"':'').' class="TagNumber" data-num="'.$number.'">0</label>
		         <div class="progress">
                   <div class="progress-bar progress-bar-core" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:0%;" value="'.($percent+20).'"></div>
                 </div>
			   </div>';
			  
   }
   static function InfoBTagRank(){
	   $tmp='';
       App::db_connect();
       $sql2='SELECT * FROM tag WHERE 1 ORDER BY count DESC';
       $stmt2=App::$dbn->prepare($sql2);
       $stmt2->execute(array());
       $result2=$stmt2->fetchAll(PDO::FETCH_ASSOC);
   
       $totalCount2=0;
       for($i=0;$i<count($result2);$i++){
	      $totalCount2+=$result2[$i]['count'];
       }
	   for($i=0;$i<3;$i++){
		   $tmp.=self::infoBRank($result2[$i],$result2[$i]['count'],0,$i,floatval($result2[$i]['count']/$totalCount2)*100);
	   }
       
	   return $tmp;
   	  
   }
   static function InfoBBuildingRank(){
       App::db_connect();
       $sql='SELECT cid,count FROM color WHERE cid!=0 ORDER BY count DESC';
       $stmt=App::$dbn->prepare($sql);
       $stmt->execute(array());
       $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
	   
	   $totalCount=0;
	   for($i=0;$i<count($result);$i++){
		   $totalCount+=$result[$i]['count'];
	   }
	   $tmp='<div '.(isset($_GET['project'])?'id="PRankL"':'style="float:left; width:305px; height:200px;"').'>';
	   for($i=0;$i<10;$i++){
		   if($i==5){
			   $tmp.='</div><div '.(isset($_GET['project'])?'id="PRankR"':'style="float:left; width:315px; height:200px;"').'>';
		   }
		   $tmp.=self::infoBRank($result[$i]['cid'],$result[$i]['count'],1,$i,floatval($result[$i]['count']/$totalCount)*100);
	   }
       
	   return $tmp.'</div>';
   	
   }
   static function newCidRank($bid){
	   $rankArr=self::analyze($bid);
	   $tmp='';
	   for($i=0;$i<3;$i++){
		   $tmp.='<div '.(isset($_GET['cid'])?'style="width:80px;"':'').' class="box2RankingContainer">
					<p id="newCidRank'.($i+1).'" class="box2RankingNum'.($i+1).' TagNumber" data-num="'.$rankArr[$i]['num'].'">0</p>
					<div class="box2RankingImg'.($i+1).'"></div>
					<p id="newCidRankCid'.($i+1).'" class="coBlue box2RankingCid"><a class="toLookUp type" href="infoB.php?cid='.$rankArr[$i]['id'].'" data-ajax="page=infoB&cid='.$rankArr[$i]['id'].'">'.buildingType($rankArr[$i]['id']).'</a></p>
				 </div>';
	   }
       
	   return $tmp;
   }
   static function newGidRank($bid){
	   $rankArr=self::analyze($bid);
	   $tmp='';
	   for($i=0;$i<3;$i++){
		   $tmp.='<div '.(isset($_GET['cid'])?'style="width:80px;"':'').' class="box2RankingContainer">
					<p id="newGidRank'.($i+1).'" class="box2RankingGidNum'.($i+1).' TagNumber" data-num="'.$rankArr[$i+3]['num'].'">0</p>
					<div class="box2RankingGidImg'.($i+1).'"></div>
					<p id="newGidRankCid'.($i+1).'" class="coBlue box2RankingGid"><a class="toLookUp tag" href="infoC.php?gid='.$rankArr[$i+3]['id'].'" data-ajax="page=infoC&gid='.$rankArr[$i+3]['id'].'">'.$rankArr[$i+3]['gname'].'</a></p>
				 </div>';
	   }
       
	   return $tmp;
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
	  	      $sql2='SELECT * FROM building WHERE bid=:bid';
	  	      $stmt2=App::$dbn->prepare($sql2);
	  	      $stmt2->execute(array(
	  		    ":bid"=>$result[$i]['bid']
	  	      ));
	  	      $result2=$stmt2->fetch(PDO::FETCH_ASSOC);
			  $tmp.='<div class="infoCContent">
		               <div class="infoCText toLookUp building"><a href="infoA.php?bid='.$result2['bid'].'" data-ajax="page=infoA&bid='.$result2['bid'].'" class="toLookUp building">'.$result2['bname'].'</a></div>
	                   <a class="toLookUp building" href="infoA.php?bid='.$result2['bid'].'" data-ajax="page=infoA&bid='.$result2['bid'].'"><div class="infoCImg" style="background-image:url(\'img/building/'.$result2['iid'].'.png\')"></div></a>
				       <div class="infoCTag"><div class="infoCinnerBox">';
			  
			  $sql3='SELECT * FROM gidtobid  INNER JOIN tag ON tag.gid=gidtobid.gid WHERE bid=:bid';
			  $stmt3=App::$dbn->prepare($sql3);
			  $stmt3->execute(array(
			   		  ":bid"=>$result2['bid']
			  ));
			  $result3=$stmt3->fetchALL(PDO::FETCH_ASSOC);
			  
			  for($j=0;$j<count($result3);$j++){
				  $tmp.='<a href="infoC.php?gid='.$result3[$j]['gid'].'" data-ajax="page=infoC&gid='.$result3[$j]['gid'].'" class="toLookUp tag">'.$result3[$j]['gname'].' </a>';
			  }
			  		   
		      $tmp.='</div></div></div>';
		  }
            
	   return $tmp;
	  }
   }
   static function WhichBuilding($bid){
	   if(isset($bid)){
	     App::db_connect();
	     $sql='SELECT * FROM building WHERE bid=:bid';
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
	     $sql='SELECT * FROM building WHERE cid=:cid';
	     $stmt=App::$dbn->prepare($sql);
	     $stmt->execute(array(
		    ":cid"=>$cid
	     ));
	     $result=$stmt->fetch(PDO::FETCH_ASSOC);
         
		 return $result;
       }
   }
   static function buildingImgGenerator($iid){
	   return '<div class="infoBBuilding">
		           <div class="infoBBuildingBottom">
	                  <div class="infoBBuilgingBottomDecoration"></div>
				   </div>
				   <div style="background-image:url(\'img/building/'.$iid.'.png\');
" class="infoBBuildingContent '.(!isset($_GET['project'])?'animated fadeIn':'').'"></div>
			   </div>';		   
   }
   static function analyze($bid){
	      $analyzeCid=[];
		  $analyzeGid=[];
		  $finalAnaly=[];
          App::db_connect();
	      $sql='SELECT * FROM building WHERE bid=:bid';
	      $stmt=App::$dbn->prepare($sql);
          $stmt->execute(array(
			  ':bid'=>$bid
          ));
	      $currentBuilding=$stmt->fetch(PDO::FETCH_ASSOC);
		  
	      $sql='SELECT * FROM building WHERE 1';
	      $stmt=App::$dbn->prepare($sql);
          $stmt->execute(array());
	      $result=$stmt->fetchAll(PDO::FETCH_ASSOC);
		  		  
		  for($i=0;$i<count($result);$i++){
			  if(sqrt(pow((intval($currentBuilding['x']) - intval($result[$i]['x'])), 2) + pow((intval($currentBuilding['y']) - intval($result[$i]['y'])),2))<=250 && $result[$i]['bid']!=$bid){
				  if(isset($analyzeCid[$result[$i]['cid']])){
				  	$analyzeCid[$result[$i]['cid']]+=1;
				  }
				  else{
				  	$analyzeCid[$result[$i]['cid']]=1;
				  }
				  
			      $sql='SELECT * FROM gidtobid WHERE bid=:bid';
			      $stmt=App::$dbn->prepare($sql);
		          $stmt->execute(array(
					  ":bid"=>$result[$i]['bid']
		          ));
			      $result2=$stmt->fetchAll(PDO::FETCH_ASSOC);
				  for($j=0;$j<count($result2);$j++){
				  	  if(isset($analyzeGid[$result2[$j]['gid']])){
				  	  	$analyzeGid[$result2[$j]['gid']]+=1;
				  	  }
					  else{
  				  	  	$analyzeGid[$result2[$j]['gid']]=1;
					  }
				  }
			  }
		  }
		  arsort($analyzeCid);
		  arsort($analyzeGid);
		  $analyzeIndex = 0;
		  foreach($analyzeCid as $key => $val){
			 if($analyzeIndex<3){
			 	$finalAnaly[] = array('id' => $key,'num' => $val);
				$analyzeIndex++;
			 } 
		  }
		  if($analyzeIndex<3){
			  for(;$analyzeIndex<3;$analyzeIndex++){
  			 	$finalAnaly[] = array('id' => -1,'num' => 0);
			  }
		  }
		  foreach($analyzeGid as $key => $val){
			  if($analyzeIndex<6){
			    $sql='SELECT * FROM tag WHERE gid=:gid';
			    $stmt=App::$dbn->prepare($sql);
		        $stmt->execute(array(
					  ':gid'=>$key
		        ));
			    $tag=$stmt->fetch(PDO::FETCH_ASSOC);
  			 	$finalAnaly[] = array('id' => $key,'num' => $val, 'gname'=>$tag['gname']);
  				$analyzeIndex++;
			  }
		  }
		  if($analyzeIndex<6){
			  for(;$analyzeIndex<6;$analyzeIndex++){
  			 	$finalAnaly[] = array('id' =>-1,'num' => 0,'gname'=>'暫無標籤');
			  }
		  }
		  return $finalAnaly;	
   }
}
   



?>