import json
import os
import re
import time
from datetime import datetime
import requests
from bs4 import BeautifulSoup
import xlrd
from datetime import date
import re

def clean_element(element):
    # Remove all characters except digits and dots
    return re.sub(r'[^0-9.]', '', element)

def clean_array(arr):
    # Apply clean_element to each element in the array
    return [clean_element(item) for item in arr]

def only_english_letters(s):
    return bool(re.fullmatch(r'[a-zA-Z]+', s))

def find_long_space_substrings(s: str, min_spaces: int = 5) -> list:
    pattern = f' {" " * min_spaces}+' 
    return re.findall(pattern, s)

def replace_underscores(s):
    # Ensure there are at least 5 underscores
    if s.count('_') < 5:
        raise ValueError("The string must contain at least 5 underscores")
    
    # Replace the first 2 "_" with "-"
    s = s.replace('_', '-', 2)
    
    # Replace the third "_" with "  "
    s = s.replace('_', '  ', 1)
    
    # Replace the last 2 "_" with ":"
    # Reverse the string, perform replacement, and reverse back
    s = s[::-1].replace('_', ':', 2)[::-1]
    
    return s

class TencentDocument():

    def __init__(self, document_url, local_pad_id, cookie_value):
        # excel address
        self.document_url = document_url
        # manually fetch
        self.localPadId = local_pad_id
        self.headers = {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': cookie_value
        }

    def get_now_user_index(self):
        """
        
        :return:
            # nowUserIndex = '4883730fe8b94fbdb94da26a9a63b688'
            # uid = '144115225804776585'
            # utype = 'wx'
        """
        response_body = requests.get(url=self.document_url, headers=self.headers, verify=False)
        parser = BeautifulSoup(response_body.content, 'html.parser')
        global_multi_user_list = re.findall(re.compile('window.global_multi_user=(.*?);'), str(parser))
        if global_multi_user_list:
            user_dict = json.loads(global_multi_user_list[0])
            # print(user_dict)
            return user_dict['nowUserIndex']
        return 'cookie expired'

    def download_excel(self, check_progress_url, file_name):
        """
        
        :return:
        """
        # fetch url
        start_time = time.time()
        file_url = ''
        while True:
            res = requests.get(url=check_progress_url, headers=self.headers, verify=False)
            progress = res.json()['progress']
            if progress == 100:
                file_url = res.json()['file_url']
                break
            elif time.time() - start_time > 30:
                # print("no response")
                break
        if file_url:
            self.headers['content-type'] = 'application/octet-stream'
            res = requests.get(url=file_url, headers=self.headers, verify=False)
            with open(file_name, 'wb') as f:
                f.write(res.content)
            # print('Download Successful: ' + file_name)
        else:
            print("Download Failed")



if __name__ == '__main__':
   
    document_url = 'https://docs.qq.com/sheet/DUWJvd1NxVnFpWWJQ'
    
    local_pad_id = 'QbowSqVqiYbP'
    
    cookie_str = "RK=qRXQi9C38M; ptcz=af3b973943901c39ed15030d0e560f85e105be9b7ab7de32921cb53d31a7bec1; qq_domain_video_guid_verify=3698335e93bc3986; _qimei_uuid42=1810c0a213310018259d922f8430b067bedddcc294; pgv_pvid=5769469809; _qimei_q36=; _qimei_h38=98e5eea9259d922f8430b0670200000191810d; fqm_pvqid=80ae6259-63f2-45a8-a754-77217ec8e5b1; fingerprint=d4de3714dbf04954bdaadfc3d43b000910; low_login_enable=1; pac_uid=0_AmwzZ21mM0r98; suid=user_0_AmwzZ21mM0r98; eas_sid=Y1I7M2d7y8a3U8h4Y4V8G6A7j5; optimal_cdn_domain=docs.gtimg.com; _qimei_fingerprint=f5619be8ff61a2a1904a9a80d7a08fff; traceid=2f41497f79; TOK=2f41497f79a4bce6; hashkey=2f41497f; ES2=549ffeca1afbe657; uid=144115237698804633; uid_key=EOP1mMQHGiwwb0JvNHBLVDU5cnlkNThnMExtRHY4ckVwYUdTcXFoMllUWm1TaWM2S0ZBPSKBAmV5SmhiR2NpT2lKQlEwTkJURWNpTENKMGVYQWlPaUpLVjFRaWZRLmV5SlVhVzU1U1VRaU9pSXhORFF4TVRVeU16YzJPVGc0TURRMk16TWlMQ0pXWlhJaU9pSXhJaXdpUkc5dFlXbHVJam9pYzJGaGMxOTBiMk1pTENKU1ppSTZJbWxMV2tGd2F5SXNJbVY0Y0NJNk1UY3pOakl5TVRjMU1Td2lhV0YwSWpveE56TXpOakk1TnpVeExDSnBjM01pT2lKVVpXNWpaVzUwSUVSdlkzTWlmUS5yQXp6MC16b3k1ajlYTlFWMnVIY1U4VS1DSFFPNUFZYzFpaU1Bd25aZVo0KLfI8rsG; utype=wx; wx_appid=wx02b8ff0031cec148; openid=oy6SixKlLEVdTUMPNjrX_jO8fSAE; access_token=87_MWQwfzgGf98n9o4rhUiwcbXsOALiowVqvBY4XMd17RFDPaEhRZn4YG_VnplOBvHkMuGm3NImFrRr4tSRj8_2LpfMecJrZz5nPkcBCmeiHyg; refresh_token=87_XBg-ItIMHpuXqIwQt0K6uTvihfTfbIcAoK15kzFy2O8eDvc8nTHQYArVYfdb8LhaBjs3n3MLkkQ71srj5cMkKTo6g8i4-9p-S4LhKGiN3uo; env_id=gray-pct50; gray_user=true; DOC_SID=659ed4ce53244a879cb61c8659d2c08163d3d10217e148f398793ad122015d36; SID=659ed4ce53244a879cb61c8659d2c08163d3d10217e148f398793ad122015d36; loginTime=1733629772618"
    # cookie_str = "RK=qRXQi9C38M; ptcz=af3b973943901c39ed15030d0e560f85e105be9b7ab7de32921cb53d31a7bec1; qq_domain_video_guid_verify=3698335e93bc3986; _qimei_uuid42=1810c0a213310018259d922f8430b067bedddcc294; pgv_pvid=5769469809; _qimei_q36=; _qimei_h38=98e5eea9259d922f8430b0670200000191810d; fqm_pvqid=80ae6259-63f2-45a8-a754-77217ec8e5b1; fingerprint=d4de3714dbf04954bdaadfc3d43b000910; low_login_enable=1; pac_uid=0_AmwzZ21mM0r98; suid=user_0_AmwzZ21mM0r98; eas_sid=Y1I7M2d7y8a3U8h4Y4V8G6A7j5; optimal_cdn_domain=docs.gtimg.com; backup_cdn_domain=docs.gtimg.com; current-city-name=bj; _qimei_fingerprint=f5619be8ff61a2a1904a9a80d7a08fff; traceid=f8f6a68bb3; TOK=f8f6a68bb3ad3c2d; hashkey=f8f6a68b; ES2=6b71757f7cbb1275; uid=144115237698804633; uid_key=EOP1mMQHGixxYWluWGlMV0FINnNUcERka1pJVGJndm9NUE5BcUJHWDlSZWRIK3A5SERzPSKBAmV5SmhiR2NpT2lKQlEwTkJURWNpTENKMGVYQWlPaUpLVjFRaWZRLmV5SlVhVzU1U1VRaU9pSXhORFF4TVRVeU16YzJPVGc0TURRMk16TWlMQ0pXWlhJaU9pSXhJaXdpUkc5dFlXbHVJam9pYzJGaGMxOTBiMk1pTENKU1ppSTZJbVJtVldKaFVTSXNJbVY0Y0NJNk1UY3pNell3TVRZMU15d2lhV0YwSWpveE56TXhNREE1TmpVekxDSnBjM01pT2lKVVpXNWpaVzUwSUVSdlkzTWlmUS5PZFBQMzZsenB0c0pLdWNra3ltWDVud3Z6V0kzUWhtak9mOGdCTEJKTGxVKPXS0roG; utype=wx; wx_appid=wx02b8ff0031cec148; openid=oy6SixKlLEVdTUMPNjrX_jO8fSAE; access_token=86_64ZuirPC8gUfM5_cd5jVG_sizYq5WUORiE-RtnBfDF0wseq-MxvAi9XWMlbtE7qPgv7Uf3bFEgQXzNWOpkwWE1AbbnMdl_7PKk5m5AYyaKM; refresh_token=86_my8D63Vn7oKaSIvnG8ECD_9BKIp_6qRJoSt-7SIvXOgGEdEy07tI06V_MoHOqSE6sSm8Uy0N-dCvIeT10gCnUmNvwaRusI0YLKl1ofa2e9Q; env_id=gray-pct50; gray_user=true; DOC_SID=1cc20d3d2ed248f8aaee4c72f9a08821a1b77d1215b6421b984a226a35e191c8; SID=1cc20d3d2ed248f8aaee4c72f9a08821a1b77d1215b6421b984a226a35e191c8; loginTime=1731009667528"

    tx = TencentDocument(document_url, local_pad_id, cookie_str)
    now_user_index = tx.get_now_user_index()
    
    export_excel_url = f'https://docs.qq.com/v1/export/export_office?u=a2367cabf8134c6493298e7eff0816ef'
    post_url = 'https://docs.qq.com/v1/export/export_office'
    data = {
        'exportType': 0,
        'switches': '{"embedFonts":false}',
        'exportSource': 'client',
        'docId': '300000000$QbowSqVqiYbP'
    }
    headers = {
        'Cookie':
            cookie_str
    }
    export_office_res = requests.post(url=post_url, data=data, headers=headers).json()
    operationId = export_office_res['operationId']
    operation_id = operationId
    now_user_index = "a2367cabf8134c6493298e7eff0816ef"
    check_progress_url = "https://docs.qq.com/v1/export/query_progress?u="+now_user_index+"&operationId="+operation_id
    current_datetime = datetime.strftime(datetime.now(), '%Y_%m_%d_%H_%M_%S')
    file_name = f'{current_datetime}.xlsx'
    
    tx.download_excel(check_progress_url, "test.xlsx")

    today = date.today()
    t_date = today.strftime("%m/%d")
    t_date = t_date.replace("/",".")
    if t_date.startswith("0"):
        t_date = t_date[1:]
    t_list = t_date.split(".")
    if t_list[1].startswith("0"):
        tdatetemp = t_list[1][1:]
    else:
        tdatetemp = t_list[1]
    final_date = t_list[0]+"."+tdatetemp


    book = xlrd.open_workbook("test.xlsx")
    sheet_array = book.sheet_names()
    for ele in sheet_array:
        if len(ele) > 5 or len(ele) == 5:
            ele = ele[:5]
    cleaned_array = clean_array(sheet_array)
    sheet_ind = cleaned_array.index(final_date)
    sh = book.sheet_by_index(sheet_ind)
        
    
    filename = "/www/wwwroot/jessechatgpt.com/Output.txt"
    text_file = open(filename, "w")
    text_file.write("\n\n"+"Updated at "+replace_underscores(current_datetime)+"\nData will be fetched in the server every 15 seconds, remember to manually refresh the page in order to check the latest status."+"\n姓名, 自习时间, 自习内容, 助教反馈 should exist as column names in ROW1"+"\n\n\n"+"-------------------------------------------------"+"\n\n")

    sni = 0
    try:
        while(True):
            if "姓名" not in str(sh.cell_value(rowx=0, colx=sni)):
                sni = sni + 1
            else:
                break
    except:
        text_file.write("Updated at "+replace_underscores(current_datetime)+"\n\n")

    fbi = 0
    try:
        while(True):
            if "助教反馈" not in str(sh.cell_value(rowx=0, colx=fbi)):
                fbi = fbi + 1
            else:
                break
    except:
        text_file.write("Updated at "+replace_underscores(current_datetime)+"\n\n")

    sci = 0
    try:
        while(True):
            if "自习内容" not in str(sh.cell_value(rowx=0, colx=sci)):
                sci = sci + 1
            else:
                break
    except:
        text_file.write("Updated at "+replace_underscores(current_datetime)+"\n\n")

    di = 0
    try:
        while(True):
            if "自习时间" not in str(sh.cell_value(rowx=0, colx=di)):
                di = di + 1
            else:
                break
    except:
        text_file.write("Updated at "+replace_underscores(current_datetime)+"\n\n")

    iter = 0
    try:
        while(True):
            student_name = str(sh.cell_value(rowx=iter, colx=sni))
            student_name = student_name.replace("\n","")
            student_name = student_name.replace("("," ")
            student_name = student_name.replace(")","")
            if " " in student_name:
                stemp = student_name.split()
                student_name = stemp[0]
            if student_name != "" and "姓名" not in student_name:
                feedback = str(sh.cell_value(rowx=iter, colx=fbi))
                study_content = str(sh.cell_value(rowx=iter, colx=sci))
                if study_content != "":
                    duration = str(sh.cell_value(rowx=iter, colx=di))
                    sclist = find_long_space_substrings(study_content)
                    sclength = len(sclist) 
                    if sclength!= 0:
                        i = 0
                        while i < sclength:
                            study_content = study_content.replace(sclist[i],"\n")
                            i = i + 1
                    if "\n\n" in study_content:
                        study_content = study_content.replace("\n\n","\n")
                    if "加油" not in feedback:
                        if only_english_letters(student_name) == False:
                            if len(student_name) > 2:
                                numpattern = r'[0-9]'
                                new_student_name1 = re.sub(numpattern,"",student_name)
                                letterpattern = r'[a-zA-Z]'
                                new_student_name = re.sub(letterpattern, '', new_student_name1)
                                new_student_name = new_student_name.replace(".","")
                                new_name = new_student_name[1:]
                                if len(new_name) == 3:
                                    new_name = new_name[1:]
                            else:
                                new_name = student_name
                        else:
                            new_name = student_name
                        if len(new_name)>2 and only_english_letters(new_name)==False:
                            tt = new_name[:2]
                            new_name = tt
                        temp_str = student_name+":\n\n"+new_name+"家长您好，今日反馈如下：\n\n"+"一、 学习内容\n"+study_content+"\n\n"+"二、 自习反馈\n"+feedback+" 继续加油哦～\n\n"+"自习时间: "+str(duration)+"\n\n\n"+"-------------------------------------------------"+"\n\n"
                        text_file.write(temp_str)
                        iter = iter + 1
                    else:
                        if only_english_letters(student_name) == False:
                            if len(student_name) > 2:
                                numpattern = r'[0-9]'
                                new_student_name1 = re.sub(numpattern,"",student_name)
                                letterpattern = r'[a-zA-Z]'
                                new_student_name = re.sub(letterpattern, '', new_student_name1)
                                new_student_name = new_student_name.replace(".","")
                                new_name = new_student_name[1:]
                                if len(new_name) == 3:
                                    new_name = new_name[1:]
                            else:
                                new_name = student_name
                        else:
                            new_name = student_name
                        if len(new_name)>2 and only_english_letters(new_name)==False:
                            tt = new_name[:2]
                            new_name = tt
                        text_file.write(student_name+":\n\n"+new_name+"家长您好，今日反馈如下：\n\n"+"一、 学习内容\n"+study_content+"\n\n"+"二、 自习反馈\n"+feedback+"\n\n"+"自习时间: "+str(duration)+"\n\n\n"+"-------------------------------------------------"+"\n\n")
                        iter = iter + 1
                else:
                    iter = iter + 1
            else:
                iter = iter + 1
    except:
        text_file.write("Updated at "+replace_underscores(current_datetime)+"\n\n")
        # print("Output.txt has been generated.")
            
    text_file.close()  
    os.remove("test.xlsx")
    time.sleep(120)