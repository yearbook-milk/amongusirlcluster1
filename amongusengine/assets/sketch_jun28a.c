// this is firmware that is loaded onto the emergency meeting button's internal microcontroller
const int pinButton = 8;

void setup() {
  pinMode(pinButton, INPUT);
  Serial.begin(9600);
}

void loop() {
  int stateButton = digitalRead(pinButton);
  Serial.println(stateButton);
  delay(25);
}