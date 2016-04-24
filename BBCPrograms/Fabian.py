import json
from alchemyapi import AlchemyAPI
from database import execute_query
import datetime

def get_enrichment(ids,text,val,target_file):
    

    response = alchemyapi.combined('text', text,{'linkedData':1,'showSourceText':1})
   
    
    if response['status'] == 'OK':
        
        for keyword in response['keywords'][:5]:
            target_file.write(keyword['text'])
            target_file.write(',')
        
    else:
        print('Error in combined call: ', response['statusInfo'])
    
alchemyapi = AlchemyAPI()
CHUNK_SIZE = 500 #words
def get_subs(id,scene_filename):
    with open(scene_filename, "r") as ins:
        array = []
        for line in ins:
            array.append(line)
    vals = []
    target_file = open(id+'_scenes_subs.srt', 'w')
    target_file.truncate()
    for idx, val in enumerate(array):
        if "-->" in val:
            line = val.split(" --> ")
            time_start_shot = line[0].split(":")
            starttime = int(float(time_start_shot[0]))*3600 + int(float(time_start_shot[1]))*60 + int(float(time_start_shot[2]))
            time_stop_shot = line[1].split(":")
            endtime = int(float(time_stop_shot[0]))*3600 + int(float(time_stop_shot[1]))*60 + int(float(time_stop_shot[2]))
            subtitles = execute_query('SELECT ID, content, alchemyapi FROM subtitles WHERE episode = \'{0}\' and start_time > \'{1}\' and end_time < \'{2}\''.format(id,datetime.timedelta(seconds=starttime),datetime.timedelta(seconds=endtime)))
            target_file.write(array[idx+1].split("\n")[0])
            target_file.write(' --> ')
            chunks = ['']
            ids = [[]]
            chunk_index = 0
            for line in subtitles:
                chunks[chunk_index] += u' {0} '.format(line[1]) 
                ids[chunk_index].append(str(line[0]))
            for chunk_ids, chunk in zip(ids,chunks):
                get_enrichment(chunk_ids,chunk,array[idx+1],target_file)
            target_file.write('\n')
    target_file.close()
    

