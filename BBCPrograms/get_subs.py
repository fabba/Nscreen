import json
from alchemyapi import AlchemyAPI
from database import execute_query
from database_nscreen import execute_query_nscreen
import datetime

def get_enrichment(ids,text,bbc_id):
    

    response = alchemyapi.combined('text', text,{'linkedData':1,'showSourceText':1})
   
    
    if response['status'] == 'OK':
        
        for keyword in response['keywords']:
            execute_query_nscreen('INSERT INTO keywords(bbc_id,keywords,relevance) values( \'{0}\',\'{1}\',\'{2}\')'.format(bbc_id,keyword['text'].replace("'",""),float(keyword['relevance'])))

        
    else:
        print('Error in combined call: ', response['statusInfo'])
    
alchemyapi = AlchemyAPI()
CHUNK_SIZE = 500 #words
def get_subs(bbc_id):
    subtitles = execute_query('SELECT ID, content, alchemyapi FROM subtitles WHERE episode = \'{0}\''.format(bbc_id))
    chunks = ['']
    ids = [[]]
    chunk_index = 0
    for line in subtitles:
        chunks[chunk_index] += u' {0} '.format(line[1]) 
        ids[chunk_index].append(str(line[0]))
    for chunk_ids, chunk in zip(ids,chunks):
        get_enrichment(chunk_ids,chunk,bbc_id)
    

