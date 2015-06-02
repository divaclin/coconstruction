String url = "140.119.134.100";
// Size of each cell in the grid, ratio of window size to video size
// 80 * 8 = 640
// 60 * 8 = 480
int objectsize = 113;
int videoScale = objectsize-25;

// Number of columns and rows in our system
int cols, rows;

// Values of each grid, represent the type of building
int[][] type;

// Values and number of colors
int paints = 4;
int draw = 0;
color[] colors;

//arguments for php 
final int reactTime=3000;
int currentTime;
int lastTime=0;
JSONArray json; 
boolean flag = true;


void setup() {
  size(1280,1024);
  background(0);
  frameRate(1);
  cols = width/videoScale;
  rows = height/videoScale;
  colors = new color[paints];
  colors[0] = color(0, 0, 0);
  colors[1] = color(244, 33, 139);
  colors[2] = color(159,233,50);
  colors[3] = color(36, 200, 248);
  type = new int[cols][rows];
  for (int i = 0; i < cols; i++) {
    for (int j = 0; j < rows; j++) {
      type[i][j] = 0;    
    }
  }
}

void draw() {    
  currentTime=millis();
  if(currentTime-lastTime>=reactTime){
    lastTime=currentTime;
    saveStrings("data/select.json",loadStrings("http://192.168.1.100/co-construction/api/status_select.php"));
    json=loadJSONArray("data/select.json");
    //println(json);
    if(json.toString().length()>=3){ //find not empty
      for(int k=0;k<json.size();k++){
        JSONObject tmp=this.json.getJSONObject(k);//error if find empty
        if(tmp!=null){
          //String done = tmp.getString("done");
          if(tmp.getString("done").indexOf("E")<0){
            println("###TMP"+tmp);
            println("done = "+ tmp.getString("done")+" / indexOfE = "+  tmp.getString("done").indexOf("E"));
            println("===========");
            String chosen = tmp.getString("object_type");
            String device = tmp.getString("device");
            if(device.equals("A")) draw = 1;
            else if(device.equals("B")) draw = 2;
            else draw = 3;
            //if same device, different action => recover color to black
            for (int i = 0; i < cols; i++) {
              for (int j = 0; j < rows; j++) {
                if(type[i][j] == draw)
                  type[i][j] = 0;    
              }
            }
            String oid; 
            if(chosen.equals("T")){ //SELECT TYPE
              oid = tmp.getString("oid");
              saveStrings("data/infoB.json",loadStrings("http://192.168.1.100/co-construction/api/select_type.php?cid="+oid));
              JSONArray j_array=loadJSONArray("data/infoB.json");
              println("size="+j_array.size());
              for(int i=0;i<j_array.size();i++){
                JSONObject data=j_array.getJSONObject(i);//error if find empty
                int x = (int)(data.getFloat("x")/objectsize);
                if(x>=cols) x = cols-1;
                int y = (int)(data.getFloat("y")/objectsize);  
                if(y>=rows) y = rows-1;
                type[x][y] = draw;
                println("x="+(data.getFloat("x"))+",y="+(data.getFloat("y")));
                println("x="+x+",y="+y);
              }
            }
            else if(chosen.equals("G")){ //SELECT TAG
              oid = tmp.getString("oid");
              saveStrings("data/infoC.json",loadStrings("http://192.168.1.100/co-construction/api/select_tag.php?gid="+oid));
              JSONArray j_array=loadJSONArray("data/infoC.json");
              println("size="+j_array.size());
              for(int i=0;i<j_array.size();i++){
                JSONObject data=j_array.getJSONObject(i);//error if find empty
                int x = (int)(data.getFloat("x")/objectsize);
                if(x>=cols) x = cols-1;
                int y = (int)(data.getFloat("y")/objectsize); 
                if(y>=rows) y = rows-1;
                println("x="+(data.getFloat("x"))+",y="+(data.getFloat("y")));
                println("x="+x+",y="+y); 
                type[x][y] = draw;
              }
            }
            else if(chosen.equals("B")){ //SELECT BUILDING
              oid = tmp.getString("oid");
              saveStrings("data/infoA.json",loadStrings("http://192.168.1.100/co-construction/api/select_building.php?bid="+oid));
              JSONArray j_array=loadJSONArray("data/infoA.json");
              JSONObject data=j_array.getJSONObject(0);//error if find empty
              int x = (int)(data.getFloat("x")/objectsize);
              if(x>=cols) x = cols-1;
              int y = (int)((data.getFloat("y"))/objectsize); 
              if(y>=rows) y = rows-1;
              println("x="+(data.getFloat("x"))+",y="+(data.getFloat("y")));
              println("x="+x+",y="+y); 
              type[x][y] = draw;
            }
            //UPDATE
            loadStrings("http://192.168.1.100/co-construction/api/table_update.php?statusid="+tmp.getString("statusid"));
            println("@@@@@@@done");
          }
        }
      }  
    }
    // Begin loop for columns
    for (int i = 0; i < cols; i++) {
      // Begin loop for rows
      for (int j = 0; j < rows; j++) {      
        // Scaling up to draw a rectangle at (x,y)
        float pos_x = i*videoScale+2.0*videoScale;
        float pos_y = j*videoScale+1.3*videoScale;//+1.5*videoScale; 
        //float dis = pos_x-7*videoScale;
        //if(abs(dis)>0){
        //  pos_x = pos_x-dis*0.05;
        //}
        if(flag)
          fill (color(colors[type[i][j]]));
        else{
          fill(255);
          stroke(0);
        }
        // For every column and row, a rectangle is drawn at an (x,y) location scaled and sized by videoScale.
        rect (pos_x,pos_y,videoScale,videoScale);
      }
    }
  }
}

