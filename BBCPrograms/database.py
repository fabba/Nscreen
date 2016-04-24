import MySQLdb, time

db = MySQLdb.connect(host="localhost",port=3306,user="root",passwd="",db="bbc_subs",charset='utf8')
cursor = db.cursor()

def execute_query(query,vital=True,level=0):
    ''' Executes any given query. Vital queries will be retried upon failure until they succeed. '''
    if level >= 5:
        print 'Too many query attempts made. Forfeiting this query.'
        return False

    try:
        cursor.execute(query)

        if query.split(' ')[0] == 'SELECT':
            results = cursor.fetchall()
            return results

        elif query.split(' ')[0] == 'DELETE' or query.split(' ')[0] == 'UPDATE' or query.split(' ')[0] == 'INSERT':
            db.commit()
            return True

    except Exception:
        print u'Got a MySQL error on the following query: {0}'.format(query)

        if vital:
            time.sleep(5)
            return execute_query(query,True,level=level+1)
        else:
            return False
