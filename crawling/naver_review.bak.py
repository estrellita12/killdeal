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

def naverSmartStore(idx,preTot=0):
    date_chk_flag = True
    limit = 20

    now = now = datetime.now()
    chk_date = now - timedelta(days=3)

    review_list = list()
    selectorList = dict()
    selectorList["star"]= "div._2V6vMO_iLm > em._15NU42F3kT"
    selectorList["writer"]="div.iWGqB6S4Lq > strong._2L3vDiadT9"
    selectorList["reg_date"]="div.iWGqB6S4Lq > span._2L3vDiadT9"
    selectorList["contents"]="div._1kMfD5ErZ6 > span._2L3vDiadT9"
 
    try:
        driver = getChrome()
        driver.implicitly_wait(10)
        url = "https://smartstore.naver.com/majorgolf/products/"+idx
        driver.get(url=url)
        tot = 0
        try:
            tot = driver.find_element(By.CSS_SELECTOR,'#content > div > div._2-I30XS1lA > div._3rXou9cfw2 > div.NFNlCQC2mv > div:nth-child(1) > a > strong').text
            tot = int(tot)
        except Exception as e:
            #print(e,"리뷰의 총 갯수를 가져오지 못했습니다.")       
            return []

        if tot <= 0:
            return []

        if preTot >= tot:
            return []

        if preTot == 0:
            date_chk_flag = False

        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(1)
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(1)
        driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
        time.sleep(1)

        element = WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.CSS_SELECTOR,'#_productFloatingTab > div > div > ul > li:nth-child(2) > a')))
        driver.find_element(By.CSS_SELECTOR,'#_productFloatingTab > div > div > ul > li:nth-child(2) > a').click()
        time.sleep(1)

        #response=driver.find_element(By.ID,'REVIEW').get_attribute('innerHTML')
        #soup = BeautifulSoup(response, 'html.parser')
        #html = driver.page_source
        #soup = BeautifulSoup(html, 'html.parser')
        #time.sleep(1)
        for i in range(limit):
            html = driver.page_source
            soup = BeautifulSoup(html, 'html.parser')
            ul_list = soup.select(".BnwL_cs1av")
            for r in ul_list:
                review_data = {"star":"","writer":"","reg_date":"","contents":"","reference":"smartstore","image_url":""}
                for k in selectorList:
                    try:
                        review_data[k] = r.select_one(selectorList[k]).text
                    except Exception as e:
                        #print(k,selectorList[k],e)
                        pass
                try:
                    review_data["image_url"] = r.select_one("span._1DOkWFrX74 > img")['src']
                except Exception as e:
                    #print("image_url",e)
                    pass
                review_data["reg_date"] = "20"+review_data["reg_date"][:2]+"-"+review_data["reg_date"][3:5]+"-"+review_data["reg_date"][6:8]
                review_list.append(review_data)
                rv_year = review_data["reg_date"][:4]
                rv_month = review_data["reg_date"][5:7]
                rv_day = review_data["reg_date"][8:10]
                rv_date = datetime(int(rv_year),int(rv_month),int(rv_day))
                if date_chk_flag and rv_date < chk_date :
                    return review_list

            if len(review_list) >= tot :
                break

            try:
                driver.find_element(By.CSS_SELECTOR,'._2UJrM31-Ry > .fAUKm1ewwo:last-child').click()
                time.sleep(1)
            except Exception as e:
                break;

    except Exception as e:
        #print(e)       
        pass
    finally:
        driver.quit()
        return review_list

def convJson(datalist):
    return json.dumps(datalist)

if len(sys.argv) > 1:
    code = sys.argv[1]
    cnt = 0
    if len(sys.argv) > 2:
        cnt = sys.argv[2]
        cnt = int(cnt)
    res = naverSmartStore(code,cnt)
    #print("res",res)
    if len(res) >= 1:
        print(1)
        print(json.dumps(res))
    else:
        print(0)
else:
    print(-1)
print("====END====")

