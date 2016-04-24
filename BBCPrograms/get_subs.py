import json
from alchemyapi import AlchemyAPI
from database import execute_query
import datetime

def get_enrichment(ids,text,target_file):
    

    response = alchemyapi.combined('text', text,{'linkedData':1,'showSourceText':1})
   
    
    if response['status'] == 'OK':
        
        for keyword in response['keywords'][:10]:
            target_file.write(keyword['text'])
            target_file.write(',')
        
    else:
        print('Error in combined call: ', response['statusInfo'])
    
alchemyapi = AlchemyAPI()
CHUNK_SIZE = 500 #words
def get_subs(id):
    target_file = open(id+'_keywords.srt', 'w')
    target_file.truncate()
    subtitles = execute_query('SELECT ID, content, alchemyapi FROM subtitles WHERE episode = \'{0}\''.format(id))
    chunks = ['']
    ids = [[]]
    chunk_index = 0
    for line in subtitles:
        chunks[chunk_index] += u' {0} '.format(line[1]) 
        ids[chunk_index].append(str(line[0]))
    for chunk_ids, chunk in zip(ids,chunks):
        get_enrichment(chunk_ids,chunk,target_file)
    target_file.close()
    

