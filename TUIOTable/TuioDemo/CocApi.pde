class API{     
     final int BUILD_INIT=0;
     final int BUILD_PROCESSING=1;
     final int BUILD_BUILDING=2;
     final String path = "http://140.119.134.100/co-construction/api/";
     final String attribute = "?bid=";
     
     final int initTime = 10000;
     final int requestTime = 5000;
     final int buildingTime =3000;
     
     HashMap<Integer,String> TuioObjStatus = new HashMap<Integer,String>();
     JSONArray json;
     
     int stat;
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
          if(tuioObjectList.size()<TuioObjStatus.size()){
             for(int i=0;i<tuioObjectList.size();i++){
                 TuioObject tobj = tuioObjectList.get(i);
                 for(Map.Entry me : TuioObjStatus.entrySet()){
                    if((Integer)me.getKey()==tobj.getSymbolID() && me.getValue().equals("SELECT")){
                       TuioObjStatus.put(tobj.getSymbolID(),"CHECKED"+this.currentTime); 
                    }
                  }
             }
            for(Map.Entry me : TuioObjStatus.entrySet()){
              if(!me.getValue().equals("CHECKED"+this.currentTime)){
                 TuioObjStatus.put(tobj.getSymbolID(),"SELECT"); 
                 break;
              }
            } 
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
             TuioObjStatus.put((Integer)me.getKey(),"CHECKED"+this.currentTime);
          }
     }
}
