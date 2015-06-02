//super class
class Effect{
      int ARnum;
      float objPos;
      float objSize;
      String colorStr;
      int[] colorRGB = new int[3] ;
      
      Effect(int _num,float _pos, float _size,String _colorStr){
         this.ARnum=_num;
         this.objPos=_pos;
         this.objSize=_size;
         this.colorStr=_colorStr;
         this.colorRGB = colorCode2RGB(this.colorStr);
      }
      void show(){
      }
      
}
//child belows
class Twinkle extends Effect{
    float twinkleLine;
    
    Twinkle(int ARnum,float objPos,float objSize,String colorStr){
      super(ARnum,objPos,objSize,colorStr);  
      this.twinkleLine=0;
    }
    
    void show(){
       smooth();
       fill(0,50);
       strokeWeight(5);
       stroke(random(255),random(255),random(255),40);    
       for(int r=0;r<360;r++){
              rotate(r);
              line(0,0,5*this.objSize*noise(r,this.twinkleLine+=.1)/2,0);
          }
    }
}


class Particle extends Effect{
    int totalDots;
    Dot[] dots;
    float diameter;
          
    Particle(int ARnum,float objPos,float objSize,String colorStr){
     super(ARnum,objPos,objSize,colorStr); 
     this.totalDots = 100;
     this.dots = new Dot[totalDots];
     this.diameter = 12.0;
     
       for (int i = 0; i < totalDots; i++) {
           Dot d = new Dot();
           d.x = random(0,this.objPos);
           d.y = random(0,this.objPos);
           d.vx = random(2.0) - 1.0;
           d.vy = random(2.0) - 1.0;
           dots[i] = d;
       }
    }
    void show(){
          float r = 255;
          float g = 255;
          float b = 255;
          strokeWeight(1);
          for (int i = 0; i < totalDots; i++) {
             r = map(dots[i].x,this.objPos-this.objSize/2, this.objPos+this.objSize/2, 0, 255);
             if(random(0,100)>=50){
                g = map(dots[i].y,this.objPos-this.objSize/2, this.objPos+this.objSize/2, 0, 255);
             }
             else{
                b = map(dots[i].y,this.objPos-this.objSize/2, this.objPos+this.objSize/2, 0, 255);
             }
             noStroke();
             fill(r, g, b);
             dots[i].update(this.objPos,this.objSize);
             ellipse(dots[i].x, dots[i].y, this.diameter, this.diameter);
         }
    }    
}


class Ripples extends Effect{
    int totalRipple;
    int diaIncreaseRate;
    int strokeDecreaseRate;
    int alphaS;
    Ripple[] ripple;
    
    Ripples(int ARnum ,float objPos,float objSize,String colorStr){
     super(ARnum,objPos,objSize,colorStr);
     this.totalRipple = 5;
     this.diaIncreaseRate = 1;
     this.strokeDecreaseRate = 2;
     this.alphaS = 90;
     this.ripple = new Ripple[this.totalRipple];
         
       for(int i=0; i<this.totalRipple; i++){
         int ran=int(random(80,alphaS));
         this.ripple[i] = new Ripple(this.objPos,this.objPos,i,7,this.colorRGB[0],this.colorRGB[1],this.colorRGB[2],ran);
       }
    }
    void show(){
      for(int i=0; i<this.totalRipple; i++){
        if(this.ripple[i].sw==0){
          this.ripple[i].x = this.objPos;
          this.ripple[i].y = this.objPos;
          this.ripple[i].d = i*10;
          this.ripple[i].sa = int(random(80,alphaS));
          this.ripple[i].sw = 50;
        }
        this.ripple[i].d += diaIncreaseRate;
        if(this.ripple[i].d%strokeDecreaseRate==0) this.ripple[i].sw--;
        strokeWeight(0);
        fill(this.ripple[i].sr, this.ripple[i].sg, this.ripple[i].sb, this.ripple[i].sa);
        polygon(this.ripple[i].x, this.ripple[i].y, this.ripple[i].d*3/5, 5);//d/2
        ellipse(this.ripple[i].x, this.ripple[i].y, this.ripple[i].d/1.5, this.ripple[i].d/1.5);   
      }
      strokeWeight(1);
    }    
}



//unit class , ignore this
class Ripple{
  float x,y;
  int d,sw,sr,sg,sb,sa;
  Ripple(float x_in,float y_in,int d_in,int sw_in,int sr_in,int sg_in,int sb_in,int sa_in){ 
         x=x_in; y=y_in; 
         d=d_in; sw=sw_in; 
         sr=sr_in; sg=sg_in; sb=sb_in; sa=sa_in;
       }
}

class Dot {
    float x = 0.0;
    float y = 0.0;
    float vx = 0.0;
    float vy = 0.0;
    
    void update(float objPos,float objSize){
      // update the velocity
      this.vx += random(2.0) - 1.0;
      this.vx *= .96;
      this.vy += random(2.0) - 1.0;
      this.vy *= .96;
      // update the position
      this.x += this.vx;
      this.y += this.vy;
      // handle boundary collision
      if (this.x > objPos+objSize/2) { this.x = objPos+objSize/2; this.vx *= -1.0; }
      if (this.x < objPos-objSize/2) { this.x = objPos-objSize/2; this.vx *= -1.0; }
      if (this.y > objPos+objSize/2) { this.y = objPos+objSize/2; this.vy *= -1.0; }
      if (this.y < objPos-objSize/2) { this.y = objPos-objSize/2; this.vy *= -1.0; }
    }
}

//some shape function
void polygon(float x, float y, float radius, int npoints) {
  float angle = TWO_PI / npoints;
  beginShape();
  for (float a = 0; a < TWO_PI; a += angle) {
    float sx = x + cos(a) * radius;
    float sy = y + sin(a) * radius;
    vertex(sx, sy);
  }
  endShape(CLOSE);
}

void drawGrid(){
     if(!fixStat){
       strokeWeight(1);
       float recSize=object_size+15;
       fill(22,22,22);
       stroke(255);
       line(width-1,0,width-1,height);
       line(0,height,width,height);
       for(int i=0;i<width/recSize;i++){
         line(i*recSize,0,i*recSize,height);
       }
       for(int i=0;i<height/recSize;i++){
         line(0,i*recSize,width,i*recSize);
       }
       noStroke();
       fill(120,120,120);
       rect(0,0,recSize,height);
       rect(recSize,0,recSize,3*recSize);
       rect(recSize,8*recSize,recSize,2*recSize);
       rect(2*recSize,0,11*recSize,recSize);
       rect(2*recSize,9*recSize,11*recSize,recSize);
       rect(10*recSize,recSize,3*recSize,recSize);
       rect(11*recSize,2*recSize,2*recSize,recSize);
       rect(11*recSize,8*recSize,2*recSize,recSize);
       rect(12*recSize,2*recSize,recSize,6*recSize);
     }
     if(fixStat){
       stroke(0,255,0);
       line(width/2,0,width/2,height);
       line(0,height/2,width,height/2);
     }
}

int[] colorCode2RGB(String colorStr){
      int[] tmp = new int[3];
      for(int i=1;i<=3;i++){
         tmp[i-1]=Character.getNumericValue(colorStr.charAt(2*i-1))*16+Character.getNumericValue(colorStr.charAt(2*i));
      }
      return tmp;
}
void drawZone(float x, float y){
     int zoneSize=int(object_size+15);
     int X=int(x)/zoneSize;
     int Y=int(y)/zoneSize;
     fill(255,0,0);
     rect(X*zoneSize,Y*zoneSize,zoneSize,zoneSize);
}
int fixCoordinate(float pre){
    int zoneSize=int(object_size+15);
     return int(pre/zoneSize)*zoneSize;
}
