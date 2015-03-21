class API{ 
     //table stat  
     final int BUILD_INIT=0;
     final int BUILD_PROCESSING=1;
     final int BUILD_BUILDING=2;
     
     final String path = "http://140.119.134.100/co-construction/api/";
     final String attribute = "?bid=";
     
     //time control 
     final int initTime = 10000;
     final int requestTime = 5000;
     final int selectTime = 200;
     final int buildingTime =3000;
     final int showTime = 200;
     
     HashMap<Integer,String> TuioObjStatus = new HashMap<Integer,String>(); //building stat  
     ArrayList<Effect> effect = new ArrayList<Effect>();
  
     JSONArray json;
     
     int stat;//table stat
     
     //time control
     int currentTime;
     int lastTime;
     

     
     API(){
       this.stat=this.BUILD_INIT;
       this.lastTime=0;
     }
     void currentTableStat(){
       this.currentTime=millis();
       switch(this.stat){
         case BUILD_INIT:
              this.tableInit();
              break;
         case BUILD_PROCESSING:
              this.dataFromDB();
              this.tableSelect();
              break;
         case BUILD_BUILDING:
              this.tableBuild();
              break;
         default:
              break;
              
       }
       this.tableShow();
     }
     void tableInit(){
          if(this.currentTime-this.lastTime<=initTime){
             for(int i=0;i<tuioObjectList.size();i++){
                 TuioObject tobj = tuioObjectList.get(i);
                 if(this.TuioObjStatus.get(tobj.getSymbolID())==null){
                    this.TuioObjStatus.put(tobj.getSymbolID(),"INIT");
                 }
              }
              println("initializing...");
          }
          else{
             this.lastTime=this.currentTime;
             this.stat=BUILD_PROCESSING;
             println(this.TuioObjStatus);
             println("initialize completely");
          }
     }
     void dataFromDB(){
           if(this.currentTime-this.lastTime>=requestTime){
              saveStrings("data/db_stat.json",loadStrings(this.path+"tableConnect.php?block=true"));
              this.json=loadJSONArray("data/db_stat.json");
              this.lastTime=this.currentTime;
              for(int i=0; i< this.json.size();i++){
                  JSONObject tmp=this.json.getJSONObject(i);
                  if(tmp.getString("behavior").equals("BUILD_UP")){
                    this.stat=BUILD_BUILDING;
                    break;
                 }
              }
           }
           println("PROCESSING");
     }
     void tableSelect(){
          if(this.currentTime-this.lastTime>=selectTime){
            println("SELECT");
            if(tuioObjectList.size()<TuioObjStatus.size()){
               this.lastTime=this.currentTime;
               ArrayList<Integer> List   = new ArrayList<Integer>();
               ArrayList<Integer> Status = new ArrayList<Integer>();
               
               for(int i=0;i<tuioObjectList.size();i++){
                   List.add(tuioObjectList.get(i).getSymbolID());
               }
               for(Map.Entry me : TuioObjStatus.entrySet()){
                   Status.add((Integer)me.getKey());
               }
               Status.removeAll(List);
               this.clearSelect();
               TuioObjStatus.put(Status.get(0),"SELECT");

            }
           println(TuioObjStatus);
          }
     }
     void tableBuild(){
          if(this.currentTime-this.lastTime<=buildingTime){
             for(int i=0;i<tuioObjectList.size();i++){
                 TuioObject tobj = tuioObjectList.get(i);
                 if(TuioObjStatus.get(tobj.getSymbolID())==null){
                    println(tobj.getSymbolID());
                    this.clearSelect();
                    TuioObjStatus.put(tobj.getSymbolID(),"SELECT");
                    this.stat=BUILD_PROCESSING;
                    break;
                }
            }
            println("WAIT FOR BUILDING...");
         }
         else{
            println("TIME EXCESSED");
            this.lastTime=this.currentTime;
            this.stat=BUILD_PROCESSING;
         }
     }
     void clearSelect(){
          for(Map.Entry me : TuioObjStatus.entrySet()){
             TuioObjStatus.put((Integer)me.getKey(),"CHECKED");
          }
     }
     int tableShow(){
         for (int j=0;j<tuioObjectList.size();j++) {
              TuioObject tobj = tuioObjectList.get(j);
              if(TuioObjStatus.get(tobj.getSymbolID())!=null){
                 if(TuioObjStatus.get(tobj.getSymbolID()).equals("SELECT") || TuioObjStatus.get(tobj.getSymbolID()).equals("INIT")){
                   float currentX=(width-tobj.getScreenX(width))*fixedX;
                   float currentY=tobj.getScreenY(height)*fixedY;
                   for(int i = 0;i<this.effect.size();i++){
                       println("show effect");
                       if(this.effect.get(i).ARnum==tobj.getSymbolID()){
                          
                          if(this.currentTime-this.lastTime>=showTime){
                            saveStrings("data/building_select.json",loadStrings(this.path+"tableFindBuilding.php?bid="+tobj.getSymbolID()+"&x="+currentX+"&y="+currentY));
                            this.json=loadJSONArray("data/building_select.json");
                            this.lastTime=this.currentTime;
                          }
                          
                          stroke(0);
                          fill(255);
                          pushMatrix();
                          translate(currentX,currentY);
                          rotate(tobj.getAngle()); 
                          this.effect.get(i).show();
                          popMatrix();
                          fill(255);
                  
                          text(""+tobj.getSymbolID(), width-tobj.getScreenX(width), tobj.getScreenY(height));
                          return 1;
                       }
                   }
                   
                   if(this.currentTime-this.lastTime>=showTime){
                      saveStrings("data/building_select.json",loadStrings(this.path+"tableFindBuilding.php?bid="+tobj.getSymbolID()+"&x="+currentX+"&y="+currentY));
                      this.json=loadJSONArray("data/building_select.json");
                      this.lastTime=this.currentTime;
                   } 
                   
                   JSONObject tmp=this.json.getJSONObject(0);//error if not find
                   println(tmp.getString("color"));
                   if(tmp!=null){
                      switch(tmp.getInt("eid")){
                          case 1:
                               this.effect.add(new Twinkle(tobj.getSymbolID(),0,obj_size,tmp.getString("color")));
                               break;
                          case 2:
                               this.effect.add(new Particle(tobj.getSymbolID(),0,obj_size,tmp.getString("color")));
                               break;
                          case 3:
                               this.effect.add(new Ripples(tobj.getSymbolID(),0,obj_size,tmp.getString("color")));
                               break;
                          default:
                             println("default");
                             break;
                     }
                     return 0;
                   }
                 }
              }          
         }
         return -1;
     }
}
