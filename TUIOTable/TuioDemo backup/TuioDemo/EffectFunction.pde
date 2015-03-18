int myEffect(int ARnum,float objPos,float objSize){
    for(int i = 0;i<effect.size();i++){
       if(effect.get(i).ARnum==ARnum){
          effect.get(i).show();
          return 1;
       }
    }
    switch (ARnum%10){
      case 0:
      case 1:
      case 4:
      case 5:
          effect.add(new Twinkle(ARnum,objPos+objSize/2,objSize));
          break; 
      case 8:    
      case 3:    
      case 6:
          effect.add(new Particle(ARnum,objPos+objSize/2,objSize));
          break;
      case 9:    
      case 2:   
      case 7:
          effect.add(new Ripples(ARnum,objPos+objSize/2,objSize));
          break;
      default:
           break;
    }
    return 0;
}

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


