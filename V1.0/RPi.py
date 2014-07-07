######################
#	RPi to WeChat
#	V1.0
######################

# 导入GPIO和requests库
import requests
import RPi.GPIO as GPIO

# 设置GPIO输出方式
GPIO.setmode(GPIO.BCM)
GPIO.setup(18,GPIO.OUT) 		#green
GPIO.setup(23,GPIO.OUT)			#white
GPIO.setup(24,GPIO.OUT)			#red
GPIO.setup(25,GPIO.OUT)			#blue

# 利用requests不断读取服务器上的几个文件的内容
# 如果符合就作出某个动作
while True:	
	r = requests.get('http://example.net/ulink/green.txt')
	if r.text == "11":
		GPIO.output(18,GPIO.HIGH)
	if r.text == "00":
		GPIO.output(18,GPIO.LOW)

	r = requests.get('http://example.net/ulink/white.txt')
	if r.text == "11":
		GPIO.output(23,GPIO.HIGH)
	if r.text == "00":
		GPIO.output(23,GPIO.LOW)
		
	r = requests.get('http://example.net/ulink/red.txt')
	if r.text == "11":
		GPIO.output(24,GPIO.HIGH)
	if r.text == "00":
		GPIO.output(24,GPIO.LOW)		
		
	r = requests.get('http://example.net/ulink/blue.txt')
	if r.text == "11":
		GPIO.output(25,GPIO.HIGH)
	if r.text == "00":
		GPIO.output(25,GPIO.LOW)		
'''
	r = requests.get('http://example.net/ulink/example.txt')
	if r.text == "value1":
		code
	if r.text == "value2":
		code
'''
