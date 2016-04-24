import json 
from alchemyapi import AlchemyAPI
from database import execute_query

alchemyapi = AlchemyAPI()  
CHUNK_SIZE = 500 #words
programmes = execute_query('SELECT * FROM programmes')

for programme in programmes:   
    subtitles = execute_query('SELECT ID, content, alchemyapi FROM subtitles WHERE episode = \'{0}\''.format(programme[0]))
  
    chunks = ['']
    ids = [[]]
    chunk_index = 0
    for line in subtitles:
        if line[2] == None: # ignores the line if it has already been annotated by AlchemyAPI (Dit kan je weghalen fab)
            chunks[chunk_index] += u' {0} '.format(line[1]) 
            ids[chunk_index].append(str(line[0]))
            if len(chunks[chunk_index].split(' ')) >= CHUNK_SIZE:
                chunk_index += 1
                chunks.append('')
                ids.append([])
    print chunks
    if chunks[0]:
        print 'Going to annotate programme {0}, made {1} chunks of {2} words each.'.format(programme[0],len(chunks),CHUNK_SIZE)
        for chunk_ids, chunk in zip(ids,chunks):
            print chunk
            get_enrichment(chunk_ids,chunk)
    
