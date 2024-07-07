#include <Arduino.h>
#include <WiFi.h>
#include <HTTPClient.h>

#define SENSOR 36

const char* ssid = "IITRPR";//"CHENAB";
const char* password =

const char* url = "http://simpop.org/ecg/savedata.php";


unsigned long sessnum;

String httpResponseData;

void setup() {
  Serial.begin(115200);

  WiFi.begin(ssid, password);
  pinMode(SENSOR, INPUT);
  pinMode(10, INPUT);
  pinMode(9, INPUT);

  randomSeed(millis());
  sessnum = random(1000,9999);
  httpResponseData = "sessnum=" + String(sessnum) + "&sessdata=";
}

int POSTcnt = 0;

void loop() {
  if(POSTcnt < 10){
    if (WiFi.status() == WL_CONNECTED) {
      Serial.println("WiFi Connected");
      if((digitalRead(10) == 1)||(digitalRead(9) == 1)){
        Serial.println("ECG Disconnected");
      } else {
        int datapoint = analogRead(SENSOR);
        if(datapoint < 1000 && datapoint != 0) httpResponseData += String(datapoint)+",";
      }

      if (httpResponseData.length() > 1000) {
        Serial.println(httpResponseData);


        HTTPClient http;
        WiFiClient client;

        http.begin(client, url);

        http.addHeader("Content-Type", "application/x-www-form-urlencoded");

        int httpResponseCode = http.POST(httpResponseData);

        String payload = http.getString();
        if (payload.indexOf("SUCCESS") == -1) {
          Serial.println(payload);
          httpResponseData = "sessnum=" + String(sessnum) + "&sessdata=";
        } else {
          POSTcnt++;
        }
        http.end();
        httpResponseData = "sessnum=" + String(sessnum) + "&sessdata=";
      }
    } else {
      Serial.println("WiFi Disconnected");
    }
  } else {
    Serial.println("Test Done");
  }
  delay(1);
}
