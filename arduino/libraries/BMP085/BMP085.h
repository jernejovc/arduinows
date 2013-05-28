#ifndef BMP085_h
#define BMP085_h

class BMP085 {
public:
  BMP085();
  void Calibration();
  float ReadTemperature();
  float ReadPressure();
private:
  int ReadInt(unsigned char address);
  unsigned char ReadRegister(unsigned char address);
  
  int SENSOR_ADDRESS;
  unsigned char OSS; 
  unsigned char OSS_DELAY; 
  int ac1;
  int ac2;
  int ac3;
  unsigned int ac4;
  unsigned int ac5;
  unsigned int ac6;
  int b1;
  int b2;
  int b5;
  int mb;
  int mc;
  int md;
};

#endif