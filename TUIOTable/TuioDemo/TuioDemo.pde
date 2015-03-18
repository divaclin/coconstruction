import java.util.Map;
import processing.opengl.*;
import TUIO.*;

TuioProcessing tuioClient;
float cursor_size = 15;
float object_size = 75; //default 60 140
float table_size = 760;
float scale_factor = 1;
PFont font;
float fixedX=1.1;
float fixedY=1.3;

float obj_size;
float cur_size;

boolean verbose = false; // print console debug messages
boolean callback = false; // updates only after callbacks

ArrayList<TuioObject> tuioObjectList; 

API api=new API();

void setup(){ 
  // GUI setup
  noCursor();
  size(displayWidth,displayHeight,OPENGL);
  noStroke();
  fill(0);
  
  // periodic updates
  if (!callback) {
    frameRate(60); //<>//
    loop();
  } else noLoop(); // or callback updates 
  
  font = createFont("Arial", 18);
  scale_factor = height/table_size;
  tuioClient  = new TuioProcessing(this);
}


void draw(){
   background(0);
   textFont(font,18*scale_factor);
   obj_size = object_size*scale_factor; 
   cur_size = cursor_size*scale_factor; 
   drawGrid();
   ShowTuioObject();
 //  ShowTuioCursor();
 //  ShowTuioBlob();

}


void ShowTuioObject(){
  tuioObjectList = tuioClient.getTuioObjectList();
  api.currentTableStat();
  for (int i=0;i<tuioObjectList.size();i++) {
     TuioObject tobj = tuioObjectList.get(i);
     
     stroke(0);
     fill(255);
     pushMatrix();
     translate((width-tobj.getScreenX(width))*fixedX,tobj.getScreenY(height)*fixedY);
     rotate(tobj.getAngle());
    // rect(-obj_size/2,-obj_size/2,obj_size,obj_size);
     myEffect(tobj.getSymbolID(),-obj_size/2,obj_size);     
     popMatrix();
     fill(255);
     
     text(""+tobj.getSymbolID(), width-tobj.getScreenX(width), tobj.getScreenY(height));
   }  
}
void ShowTuioCursor(){
   ArrayList<TuioCursor> tuioCursorList = tuioClient.getTuioCursorList();
   for (int i=0;i<tuioCursorList.size();i++) {
      TuioCursor tcur = tuioCursorList.get(i);
      ArrayList<TuioPoint> pointList = tcur.getPath();
      
      if (pointList.size()>0) {
        stroke(0,0,255);
        TuioPoint start_point = pointList.get(0);
        for (int j=0;j<pointList.size();j++) {
           TuioPoint end_point = pointList.get(j);
           line(start_point.getScreenX(width),start_point.getScreenY(height),end_point.getScreenX(width),end_point.getScreenY(height));
           start_point = end_point;
        }
        
        stroke(192,192,192);
        fill(192,192,192);
        ellipse( tcur.getScreenX(width), tcur.getScreenY(height),cur_size,cur_size);
        fill(0);
        text(""+ tcur.getCursorID(), tcur.getScreenX(width)-5,  tcur.getScreenY(height)+5);
      }
   }  
}
void ShowTuioBlob(){
    ArrayList<TuioBlob> tuioBlobList = tuioClient.getTuioBlobList();
  for (int i=0;i<tuioBlobList.size();i++) {
     TuioBlob tblb = tuioBlobList.get(i);
     stroke(0);
     fill(0);
     pushMatrix();
     translate(tblb.getScreenX(width),tblb.getScreenY(height));
     rotate(tblb.getAngle());
     ellipse(-1*tblb.getScreenWidth(width)/2,-1*tblb.getScreenHeight(height)/2, tblb.getScreenWidth(width), tblb.getScreenWidth(width));
     popMatrix();
     fill(255);
     text(""+tblb.getBlobID(), tblb.getScreenX(width), tblb.getScreenX(width));
   }
}


