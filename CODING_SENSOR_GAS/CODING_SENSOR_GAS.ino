#include <WiFi.h>
#include <HTTPClient.h>

#define GAS_SENSOR_PIN 34
#define BUZZER_PIN 13

String URL = "http://192.168.107.99/Kirimdata/kirimdata.php";

const char* ssid = "bismillahST";
const char* password = "iotsangatgampang";

int GasLevel = 0;            // Variabel untuk menyimpan nilai gas
const int gasThreshold = 1200; // Ambang batas gas untuk MQ5

// Variables for timing HTTP data transmission
unsigned long previousMillis = 0;
const long interval = 5000;

// Function prototypes
void connectWiFi();
void sendHTTPData();
void Read_Gas_Sensor();

void setup() {
  Serial.begin(115200);

  pinMode(GAS_SENSOR_PIN, INPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  digitalWrite(BUZZER_PIN, LOW);

  connectWiFi();
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    connectWiFi();
  }
  Read_Gas_Sensor();

  // Kirim data HTTP setiap interval waktu
  unsigned long currentMillis = millis();
  if (currentMillis - previousMillis >= interval) {
    previousMillis = currentMillis;
    sendHTTPData();
  }
}

void Read_Gas_Sensor() {
  GasLevel = analogRead(GAS_SENSOR_PIN);

  if (GasLevel > gasThreshold) { // Jika nilai gas melebihi ambang batas
    digitalWrite(BUZZER_PIN, HIGH); // Nyalakan buzzer
  } else {
    digitalWrite(BUZZER_PIN, LOW);  // Matikan buzzer
  }

  // Tampilkan nilai gas di Serial Monitor
  Serial.printf("Gas Level: %d\n", GasLevel);
}

void sendHTTPData() {
  String postData = "GasLevel=" + String(GasLevel);

  HTTPClient http;
  http.begin(URL);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");

  int httpCode = http.POST(postData);
  String payload = httpCode > 0 ? http.getString() : "[HTTP] POST failed, error: " + http.errorToString(httpCode);

  // Tampilkan hasil pengiriman data di Serial Monitor
  Serial.printf(
    "URL: %s\nData Sent: %s\nHTTP Code: %d\nPayload: %s\n-----------------------------------\n",
    URL.c_str(), postData.c_str(), httpCode, payload.c_str()
  );

  http.end(); // Akhiri HTTP request
}

void connectWiFi() {
  WiFi.mode(WIFI_OFF);
  delay(1000);
  WiFi.mode(WIFI_STA);

  WiFi.begin(ssid, password);
  Serial.println("Connecting to WiFi");

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.printf("\nConnected to WiFi: %s\n", ssid);
  Serial.printf("IP Address: %s\n", WiFi.localIP().toString().c_str());
}
