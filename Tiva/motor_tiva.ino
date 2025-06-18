// --- CONFIGURATION ---
// IMPORTANT: Change this to the physical pin connected to your motor driver
const int MOTOR_PIN = 2;
const long BAUD_RATE = 9600;

// --- GLOBAL VARIABLES ---
// The delay between motor pulses in milliseconds, received from serial
// volatile makes sure the compiler doesn't optimize it away in unexpected ways
volatile int motorPulseDelay = 0;
volatile bool motorIsRunning = false;

// Variables for non-blocking motor pulsing
unsigned long previousMillis = 0;
int pinState = LOW;

void setup() {
  // Initialize the motor pin as an output
  pinMode(MOTOR_PIN, OUTPUT);
  digitalWrite(MOTOR_PIN, LOW); // Ensure motor is off at start

  // Initialize Serial communication
  Serial.begin(BAUD_RATE);
  while (!Serial) {
    ; // Wait for serial port to connect.
  }

  Serial.println("TIVA Motor Controller Initialized.");
  Serial.println("Waiting for commands (e.g., '100' or 'OFF')...");
}

void loop() {
  // These two functions run continuously and are non-blocking
  handleSerialCommands();
  runMotor();
}

/**
 * Checks for and processes incoming serial commands.
 * It reads a line, trims it, and updates the motor state.
 */
void handleSerialCommands() {
  if (Serial.available() > 0) {
    // Read the incoming command string until a newline character
    String command = Serial.readStringUntil('\n');
    command.trim(); // Remove any whitespace

    Serial.print("Received command: '");
    Serial.print(command);
    Serial.println("'");

    // Check if the command is to turn the motor off
    if (command.equalsIgnoreCase("OFF")) {
      motorIsRunning = false;
      Serial.println("State: Motor OFF");
    } else {
      // Otherwise, try to parse it as an integer (the delay)
      int newDelay = command.toInt();

      // Check if parsing was successful and the value is positive
      if (newDelay > 0) {
        motorPulseDelay = newDelay;
        motorIsRunning = true;
        Serial.print("State: Motor RUNNING with delay: ");
        Serial.println(motorPulseDelay);
      } else {
        Serial.println("Error: Invalid command. Ignoring.");
      }
    }
  }
}

/**
 * Pulses the motor pin if the motor is supposed to be running.
 * Uses a non-blocking delay with millis() to avoid halting the program.
 */
void runMotor() {
  // If the motor should be off, ensure the pin is LOW and exit the function.
  if (!motorIsRunning) {
    digitalWrite(MOTOR_PIN, LOW);
    return;
  }

  // Get the current time
  unsigned long currentMillis = millis();

  // Check if enough time has passed since the last pulse
  if (currentMillis - previousMillis >= motorPulseDelay) {
    // Save the last time you changed the pin state
    previousMillis = currentMillis;

    // If the pin is LOW, turn it HIGH and vice-versa
    if (pinState == LOW) {
      pinState = HIGH;
    } else {
      pinState = LOW;
    }

    // Set the motor pin to the new state
    digitalWrite(MOTOR_PIN, pinState);
  }
}