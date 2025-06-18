import serial
import mysql.connector
import time
from datetime import datetime

# --- CONFIGURATION ---
DB_CONFIG = {
    'host': "mysql-appg1d.alwaysdata.net",
    'user': "appg1d_groupeb",
    'password': "Dev$G11B",
    'database': "appg1d_projetcommun"
}
# IMPORTANT: Change this to the COM port your TIVA board is using!
SERIAL_PORT = 'COM10'  # Example for Windows. Use '/dev/ttyACM0' or similar for Linux/Mac
BAUD_RATE = 9600
MOTOR_ID_TO_CONTROL = 1
POLLING_INTERVAL_SECONDS = 2 # How often to check the database for speed changes

# --- Speed to Delay Mapping ---
# This function converts the DB speed (0-10) to a motor delay (0-100 ms)
# DB Speed 0 -> Motor Off
# DB Speed 1 -> 100ms delay (slowest)
# DB Speed 10 -> 10ms delay (fastest)
def map_speed_to_delay(speed: int) -> int:
    """Converts a speed value from 1-10 to an inverse delay from 10-100."""
    if not 1 <= speed <= 10:
        raise ValueError("Speed must be between 1 and 10.")
    # This is an inverse linear mapping: speed 1 maps to 100, speed 10 maps to 10.
    # Formula: 110 - (speed * 10)
    return 110 - (speed * 10)

def run_controller():
    """Main function to run the motor controller loop."""
    db_connection = None
    serial_connection = None
    last_known_speed = -1 # Use -1 to force an update on the first run

    try:
        # Establish serial connection
        print(f"Connecting to TIVA board on {SERIAL_PORT} at {BAUD_RATE} baud...")
        serial_connection = serial.Serial(SERIAL_PORT, BAUD_RATE, timeout=1)
        time.sleep(2) # Give the TIVA board time to reset after opening serial
        print("✔ Serial connection successful.")

        while True:
            try:
                # Connect to the database
                db_connection = mysql.connector.connect(**DB_CONFIG)
                cursor = db_connection.cursor()

                # Fetch the current speed for our motor
                cursor.execute(
                    "SELECT motorSpeed FROM motors WHERE motorId = %s",
                    (MOTOR_ID_TO_CONTROL,)
                )
                result = cursor.fetchone()

                if result is None:
                    print(f"⚠ Motor with ID {MOTOR_ID_TO_CONTROL} not found in database. Skipping.")
                    time.sleep(POLLING_INTERVAL_SECONDS)
                    continue

                current_speed = result[0]

                # --- Core Logic: Check if speed has changed ---
                if current_speed != last_known_speed:
                    print(f"Change detected! New speed: {current_speed}. Old speed: {last_known_speed}")

                    if current_speed == 0:
                        # Send the "OFF" command
                        command_to_send = "OFF\n"
                        print("Motor is stopped. Sending 'OFF' command.")
                    elif 1 <= current_speed <= 10:
                        # Calculate delay and send it
                        delay = map_speed_to_delay(current_speed)
                        command_to_send = f"{delay}\n"
                        print(f"Calculated delay: {delay}ms. Sending command.")
                    else:
                        # Invalid speed, treat as OFF for safety
                        print(f"⚠ Invalid speed '{current_speed}' in DB. Stopping motor for safety.")
                        command_to_send = "OFF\n"
                        current_speed = 0 # Ensure we log 0

                    # Send the command to the TIVA board
                    serial_connection.write(command_to_send.encode('utf-8'))

                    # Log the update in the speed_updates table
                    cursor.execute(
                        "INSERT INTO speed_updates (motorId, newSpeed) VALUES (%s, %s)",
                        (MOTOR_ID_TO_CONTROL, current_speed)
                    )
                    db_connection.commit()
                    print(f"✔ Logged speed change to {current_speed} in database.")
                    
                    # Update the last known speed
                    last_known_speed = current_speed

                else:
                    # No change, just print a status
                    print(f"No speed change for motor {MOTOR_ID_TO_CONTROL}. Current speed is {last_known_speed}.")


            except mysql.connector.Error as db_err:
                print(f"❌ Database Error: {db_err}")
                # Wait longer before retrying a failed DB connection
                time.sleep(10)
            finally:
                # Close DB connection for this iteration
                if db_connection and db_connection.is_connected():
                    cursor.close()
                    db_connection.close()
            
            # Wait for the next polling interval
            time.sleep(POLLING_INTERVAL_SECONDS)

    except serial.SerialException as ser_err:
        print(f"❌ Serial Port Error: {ser_err}")
        print("Please check the port name and ensure the TIVA board is connected.")
    except KeyboardInterrupt:
        print("\nProgram terminated by user.")
    finally:
        # Clean up connections on exit
        if serial_connection and serial_connection.is_open:
            # Send one final "OFF" command for safety
            serial_connection.write("OFF\n".encode('utf-8'))
            serial_connection.close()
            print("Serial port closed.")
        if db_connection and db_connection.is_connected():
            db_connection.close()
            print("Database connection closed.")


if __name__ == '__main__':
    run_controller()