#include <BMP085.h>
#include <Arduino.h>
#include <Wire.h>


BMP085::BMP085() {
  SENSOR_ADDRESS = 0x77;
  OSS = 2; //Oversampling, high resolution
  OSS_DELAY = 20; // Oversampling delay, >14.5ms
}

int BMP085::ReadInt(unsigned char address) {
  unsigned char MSB, LSB;

  Wire.beginTransmission(SENSOR_ADDRESS);
  Wire.write(address);
  Wire.endTransmission();

  Wire.requestFrom(SENSOR_ADDRESS, 2);
  
  //Wait until we have two values available
  while(Wire.available() < 2);
  
  
  MSB = Wire.read();
  LSB = Wire.read();

  return (int) ((MSB<<8) + LSB);
}

unsigned char BMP085::ReadRegister(unsigned char address) {
  unsigned char value;
  
  Wire.beginTransmission(SENSOR_ADDRESS);
  Wire.write(address);
  Wire.endTransmission();

  Wire.requestFrom(SENSOR_ADDRESS, 1);
  
  //Wait until we have value available
  while(Wire.available() < 1);
  
  value = Wire.read();
  return value;
}


void BMP085::Calibration() {
  ac1 = ReadInt(0xAA);
  ac2 = ReadInt(0xAC);
  ac3 = ReadInt(0xAE);
  ac4 = ReadInt(0xB0);
  ac5 = ReadInt(0xB2);
  ac6 = ReadInt(0xB4);
  
  b1 = ReadInt(0xB6);
  b2 = ReadInt(0xB8);
  mb = ReadInt(0xBA);
  mc = ReadInt(0xBC);
  md = ReadInt(0xBE);
}

float BMP085::ReadTemperature() {
  unsigned int uncompensated;

  // Request temperature reading
  Wire.beginTransmission(SENSOR_ADDRESS);
  Wire.write(0xF4);
  Wire.write(0x2E);
  Wire.endTransmission();

  // Wait at least 4.5ms
  delay(5);
  
  // Read uncompensated temperature
  uncompensated = ReadInt(0xF6);
  
  // Calculate temperature as per datasheet
  long x1 = (((long)uncompensated - (long)ac6)*(long)ac5) >> 15;
  long x2 = ((long)mc << 11)/(x1 + md);
  b5 = x1 + x2;
  long temperature = (b5 + 8) >> 4;
  
  return temperature / 10.0;
}

float BMP085::ReadPressure() {
  unsigned char MSB, LSB, XLSB;
  unsigned long uncompensated = 0;

  // Read pressure, set Oversampling
  Wire.beginTransmission(SENSOR_ADDRESS);
  Wire.write(0xF4);
  Wire.write(0x34 + (OSS<<6));
  Wire.endTransmission();

  // Wait for conversion, delay time dependent on OSS
  delay(OSS_DELAY);

  // Read register 0xF6 (MSB), 0xF7 (LSB), and 0xF8 (XLSB)
  MSB = ReadRegister(0xF6);
  LSB = ReadRegister(0xF7);
  XLSB = ReadRegister(0xF8);

  uncompensated = (((unsigned long) MSB << 16) | ((unsigned long) LSB << 8) | (unsigned long) XLSB) >> (8-OSS);
  
  // Calculate pressure as per datasheet
  long x1, x2, x3, b3, b6, pressure;
  unsigned long b4, b7;

  b6 = b5 - 4000;
  
  x1 = (b2 * (b6 * b6)>>12)>>11;
  x2 = (ac2 * b6)>>11;
  x3 = x1 + x2;
  b3 = (((((long)ac1)*4 + x3)<<OSS) + 2)>>2;

  x1 = (ac3 * b6)>>13;
  x2 = (b1 * ((b6 * b6)>>12))>>16;
  x3 = ((x1 + x2) + 2)>>2;
  b4 = (ac4 * (unsigned long)(x3 + 32768))>>15;

  b7 = ((unsigned long)(uncompensated - b3) * (50000>>OSS));
  if (b7 < 0x80000000)
    pressure = (b7<<1)/b4;
  else
    pressure = (b7/b4)<<1;

  x1 = (pressure>>8) * (pressure>>8);
  x1 = (x1 * 3038)>>16;
  x2 = (-7357 * pressure)>>16;
  pressure += (x1 + x2 + 3791)>>4;

  // Return value in hPa
  return pressure / 100.0;
}