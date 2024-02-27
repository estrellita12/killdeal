from selenium import webdriver 
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
from bs4 import BeautifulSoup
from datetime import datetime, timedelta
import time 
import sys
import os.path   
import json

def getChrome():
    options = webdriver.ChromeOptions()     # 브라우저 창 안 띄우겠다
    options.add_argument('headless')        # 보통의 FHD화면을 가정
    options.add_argument('window-size=1920x1080')   # gpu를 사용하지 않도록 설정
    options.add_argument("disable-gpu")             # headless탐지 방지를 위해 UA를 임의로 설정
    options.add_argument("disable-infobars")
    options.add_argument("--disable-extensions")
    options.add_argument('--no-sandbox') 
    options.add_argument("Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36")
    path='/home/mwdevelop/killdeal/crawling/chromedriver121/chromedriver'
    driver = webdriver.Chrome(path,options=options)
    return driver

driver = getChrome()
driver.implicitly_wait(20)
url = "http://172.20.100.100:8060/crawling/google_sheet.html"
driver.get(url=url)

html = driver.page_source
soup = BeautifulSoup(html, 'html.parser')
print(soup)
