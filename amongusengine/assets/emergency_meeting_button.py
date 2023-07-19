# this is software that runs on a central game computer
# its purpose is to interface with the emergency meeting button and call a meeting when the button is pressed
# runs correctly on windows only

import serial
import pyaudio
import wave
import os
import requests
import time

class AudioFile:
    chunk = 1024

    def __init__(self, file):
        """ Init audio stream """ 
        self.wf = wave.open(file, 'rb')
        self.p = pyaudio.PyAudio()
        self.stream = self.p.open(
            format = self.p.get_format_from_width(self.wf.getsampwidth()),
            channels = self.wf.getnchannels(),
            rate = self.wf.getframerate(),
            output = True
        )

    def play(self):
        """ Play entire file """
        data = self.wf.readframes(self.chunk)
        while data != b'':
            self.stream.write(data)
            data = self.wf.readframes(self.chunk)

    def close(self):
        """ Graceful shutdown """ 
        self.stream.close()
        self.p.terminate()

import time

ser = serial.Serial(
    port='COM3',\
    baudrate=9600,\
    parity=serial.PARITY_NONE,\
    stopbits=serial.STOPBITS_ONE,\
    bytesize=serial.EIGHTBITS,\
        timeout=0.6)

print("Emergency meeting button connected over: " + ser.portstr)
count=0

while True:
        line = ser.readline()
        try:
            line = int(line)
            #print(line)
            if int(line) >= 1:
                os.system('cls')
                print(f"""
+----------------------------------+
|   EMERGENCY MEETING REQUESTED!   |
+----------------------------------+

Waiting for host...
""")
                count += 1
                #a = AudioFile("C:/Users/infof/Downloads/push.wav")
                #a.play()
                #a.close()
                r = requests.get("https://amongusirlcluster1.charleshu.repl.co/amongusengine/event.php?event=emergencymeeting&issuer=__emergencybutton&eventdata=&norefresh=1")
                if "OK" in r.text:
                        print("SUCCESS..... EMERGENCY MEETING ACTIVE")
                        #a = AudioFile("C:/Users/infof/Downloads/noise.wav")
                        #a.play()
                        #a.close()
                else:
                        print("ERROR! Unable to start emergency meeting. Error:",r.text)

                ser.reset_input_buffer()
                input("<ENTER> to clear this message.")
                os.system("cls")

                
                
        except ValueError as e:
            print(e)

ser.close()
