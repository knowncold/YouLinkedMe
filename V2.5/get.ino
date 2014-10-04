#include <OneWire.h>
#include <DallasTemperature.h>
#include <SPI.h>
#include <Ethernet.h>


char state = '0';
char c;
byte mac[] = { 
  0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED};
IPAddress ip(192,168,1,177);

IPAddress myDns(192,168,1,1);

EthernetClient client;

char server[] = "1.ulink42.sinaapp.com";
int sensrdata = 0;

unsigned long lastConnectionTime = 0;          
boolean lastConnected = false;                 
const unsigned long postingInterval = 200*1000;  
 
// 定义DS18B20数据口连接arduino的2号IO上
#define ONE_WIRE_BUS 2
 
// 初始连接在单总线上的单总线设备
OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);
 
void setup(){
  // 设置串口通信波特率
  Serial.begin(9600);
  delay(1000);
  Ethernet.begin(mac, ip, myDns);
  Serial.print("My IP address: ");
  Serial.println(Ethernet.localIP());
  pinMode(7, OUTPUT);   
  // 初始库
  sensors.begin();
}
 
void loop(void){ 
  sensors.requestTemperatures();
  sensrdata = sensors.getTempCByIndex(0); 

  if(state == '0'){
    digitalWrite(7, LOW);      
  }else if(state == '1'){
    digitalWrite(7, HIGH);
  }

  while(client.available()) {
    c = client.read();
    if (c == '{'){
      state = client.read();
    }
  }

  if (!client.connected() && lastConnected) {
    Serial.println("disconnecting.");
    client.stop();
  }

  if(!client.connected() && (millis() - lastConnectionTime > postingInterval)) {
    if (client.connect(server, 80)) {

      // send the HTTP PUT request:
      client.print("GET /downup.php?token=doubleq&data=");
      client.print(sensrdata);
      client.println(" HTTP/1.1");
      client.println("Host: 1.ulink42.sinaapp.com");
      client.println("User-Agent: arduino-ethernet");
      client.println("Connection: close");
      client.println();

      lastConnectionTime = millis();
    }else {
      Serial.println("connection failed");
      Serial.println("disconnecting.");
      client.stop();
    }
  }
  lastConnected = client.connected();
}