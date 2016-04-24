import json
from alchemyapi import AlchemyAPI
from database import execute_query
import datetime

def get_enrichment(ids,text):
    

    response = alchemyapi.combined('text', text,{'linkedData':1,'showSourceText':1})
    if response['status'] == 'OK':
	    for keyword in response['keywords'][:3]:
                print keyword['text']
    else:
        print('Error in combined call: ', response['statusInfo'])
    
alchemyapi = AlchemyAPI()  
def get_subs(id,scene_ filename):
    with open(scene_filename, "r") as ins:
        array = []
        for line in ins:
            array.append(line)
    
    CHUNK_SIZE = 500 #words
    subtitles = execute_query('SELECT ID, content, alchemyapi FROM subtitles WHERE episode = \'{0}\' and start_time > \'{1}\' and end_time < \'{2}\''.format(id,datetime.timedelta(seconds=starttime),datetime.timedelta(seconds=endtime)))
    print subtitles

    chunks = ['']
    ids = [[]]
    chunk_index = 0
    get_enrichment(id,subtitles)
