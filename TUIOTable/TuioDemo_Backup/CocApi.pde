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
     final int selectTime =200;
     final int buildingTime =3000;
     
     HashMap<Integer,String> TuioObjStatus = new HashMap<Integer,String>(); //building stat  
  
     JSONArray json;
     
     int stat;//table stat
     
     //time control
     int currentTime;
     int lastTime;
     
     ArrayList<Effect> effect = new ArrayList<Effect>();

     
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
       //this.tableShow();
       
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
     void tableShow(){
          for(Map.Entry me : TuioObjStatus.entrySet()){
              if(me.getValue().equals("SELECT")||me.getValue().equals("INIT")){
                if(this.effect.indexOf((Integer)me.getKey())==-1){
                   saveStrings("data/building_select.json",loadStrings(this.path+"tableFindBuilding.php?bid="+this.effect.indexOf((Integer)me.getKey())));
                   this.json=loadJSONArray("data/building_select.json");
                   JSONObject tmp=this.json.getJSONObject(i);
                   switch(tmp.getInt("eid")){
                         case 1:
                               this.effect.add(new Twinkle((Integer)me.getKey(),objPos+objSize/2,objSize,tmp.getString("color")));
                               break;
                         case 2:
                               this.effect.add(new Particle((Integer)me.getKey(),objPos+objSize/2,objSize,tmp.getString("color")));
                               break;
                         case 3:
                               this.effect.add(new Ripples((Integer)me.getKey(),objPos+objSize/2,objSize,tmp.getString("color")));
                               break;
                         default:
                             break;
                   }
                }  
                else{
                  this.effect.get(this.effect.indexOf((Integer)me.getKey())).show();
                }
              }
          }
     }
}
