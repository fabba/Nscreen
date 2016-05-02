import json
from alchemyapi import AlchemyAPI
from database import execute_query
from database_nscreen import execute_query_nscreen
import datetime

def get_enrichment(ids,text,val,starttime,endtime,bbc_id):
    

    response = alchemyapi.combined('text', text,{'linkedData':1,'showSourceText':1})
   
    
    if response['status'] == 'OK':
        
        for keyword in response['keywords']:
             execute_query_nscreen('INSERT INTO keyword_scenes (bbc_id,keyword,relevance,start_scene,end_scene) values( \'{0}\',\'{1}\',\'{2}\',\'{3}\',\'{4}\')'.format(bbc_id,keyword['text'].replace("'",""),float(keyword['relevance']),starttime,endtime))

        
    else:
        print('Error in combined call: ', response['statusInfo'])
    
alchemyapi = AlchemyAPI()
CHUNK_SIZE = 500 #words
def get_subs(bbc_id,scene_filename):
    with open(scene_filename, "r") as ins:
        array = []
        for line in ins:
            array.append(line)
    vals = []
    for idx, val in enumerate(array):
        if "-->" in val:
            line = val.split(" --> ")
            time_start_shot = line[0].split(":")
            starttime = int(float(time_start_shot[0]))*3600 + int(float(time_start_shot[1]))*60 + int(float(time_start_shot[2]))
   
            time_stop_shot = line[1].split(":")
            
            endtime = int(float(time_stop_shot[0]))*3600 + int(float(time_stop_shot[1]))*60 + int(float(time_stop_shot[2]))

            subtitles = execute_query('SELECT ID, content, alchemyapi FROM subtitles WHERE episode = \'{0}\' and start_time > \'{1}\' and end_time < \'{2}\''.format(bbc_id,datetime.timedelta(seconds=starttime),datetime.timedelta(seconds=endtime)))

            chunks = ['']
            ids = [[]]
            chunk_index = 0
            for line in subtitles:
                chunks[chunk_index] += u' {0} '.format(line[1]) 
                ids[chunk_index].append(str(line[0]))
            for chunk_ids, chunk in zip(ids,chunks):
                get_enrichment(chunk_ids,chunk,array[idx+1],starttime,endtime,bbc_id)
         
    

