#define MOTOR_PIN 2 // (PA2)

void setup() {
  pinMode(MOTOR_PIN, OUTPUT);
  pinMode(38, OUTPUT);
  pinMode(36, OUTPUT);
}

void loop() {

//analogWrite(36,200);
 
// digitalWrite(38, HIGH);
//  pinMode(36, OUTPUT);
   digitalWrite(36, LOW);
  delay(20);                   
//  digitalWrite(38, LOW); 
//  pinMode(37, INPUT);
   digitalWrite(36, HIGH);
  delay(3);
  
} 