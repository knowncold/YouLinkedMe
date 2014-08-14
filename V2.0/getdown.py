import requests
import RPi.GPIO as GPIO
GPIO.setmode(GPIO.BCM)



while True:
	payload = {'sec':'woshipia'}
	r = requests.get('http://1.ulinkb.sinaapp.com/down.php',params=payload)
	if (r.content == '{1}'):
		GPIO.setup(25,GPIO.OUT)
		GPIO.output(25,GPIO.HIGH)

	if (r.content == '{0}'):
		GPIO.setup(25,GPIO.OUT)
		GPIO.output(25,GPIO.LOW)