
from database import execute_query
import datetime

def insert_subs(id,subfile):
    with open(subfile, "r") as ins:
        array = []
        for line in ins:
            array.append(line)
    for idx, val in enumerate(array):
        if "-->" in val:
            line = val.split(" --> ")
            time_start_shot = line[0].split(":")
            starttime = int(float(time_start_shot[0]))*3600 + int(float(time_start_shot[1]))*60 + int(float(time_start_shot[2].split(",")[0]))
            time_stop_shot = line[1].split(":")
            endtime = int(float(time_stop_shot[0]))*3600 + int(float(time_stop_shot[1]))*60 + int(float(time_stop_shot[2].split(",")[0]))
            execute_query('INSERT INTO subtitles ( episode, start_time, end_time, content) values ( \'{0}\',\'{1}\',\'{2}\',\"{3}\")'.format(id,datetime.timedelta(seconds=starttime),datetime.timedelta(seconds=endtime),array[idx+1].replace('"','')))
        
