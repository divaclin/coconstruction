ArrayList<Effect> effect = new ArrayList<Effect>();

//super class
class Effect{
      int ARnum;
      float objPos;
      float objSize;
      
      Effect(int _num,float _pos, float _size){
         this.ARnum=_num;
         this.objPos=_pos;
         this.objSize=_size;
      }
      void show(){
      }
}
//child belows
class Twinkle extends Effect{
    float twinkleLine;
    
    Twinkle(int ARnum,float objPos,float objSize){
      super(ARnum,objPos,objSize);  
      this.twinkleLine=0;
    }
    
    void show(){
       smooth();
       fill(0,50);
       strokeWeight(1);
       stroke(random(255),random(255),random(255),40);    
       for(int r=0;r<360;r++){
              rotate(r);
              line(0,0,2*this.objSize*noise(r,this.twinkleLine+=.1)/2,0);
          }
    }
}


class Particle extends Effect{
    int totalDots;
    Dot[] dots;
    float diameter;
          
    Particle(int ARnum,float objPos,float objSize){
     super(ARnum,objPos,objSize); 
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
    
    Ripples(int ARnum ,float objPos,float objSize){
     super(ARnum,objPos,objSize);
     this.totalRipple = 5;
     this.diaIncreaseRate = 1;
     this.strokeDecreaseRate = 2;
     this.alphaS = 60;
     this.ripple = new Ripple[this.totalRipple];
         
       for(int i=0; i<this.totalRipple; i++){
         this.ripple[i] = new Ripple(this.objPos,this.objPos,i*20,(i+1)*10-9,int(random(0,255)),int(random(0,255)),int(random(0,255)),alphaS);
       }
    }
    void show(){
      for(int i=0; i<this.totalRipple; i++){
        if(this.ripple[i].sw==0){
          this.ripple[i].x = this.objPos;
          this.ripple[i].y = this.objPos;
          this.ripple[i].d = i*20;
          this.ripple[i].sa = alphaS;
          this.ripple[i].sw = 100;
        }
        this.ripple[i].d += diaIncreaseRate;
        if(this.ripple[i].d%strokeDecreaseRate==0) this.ripple[i].sw--;
        strokeWeight(this.ripple[i].sw);
        stroke(this.ripple[i].sr, this.ripple[i].sg, this.ripple[i].sb, this.ripple[i].sa);
        polygon(this.ripple[i].x, this.ripple[i].y, this.ripple[i].d/2, 5);//d/2
        //ellipse(this.ripple[i].x, this.ripple[i].y, this.ripple[i].d/1.5, this.ripple[i].d/1.5);   
      }
      strokeWeight(1);
    }    
}



//unit class , don't care this
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




