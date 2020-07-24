import sqlite3
from sqlite3 import Error
from pathlib import Path
class main(object):
    def __init__(self,columns,table):
        #connect to db
        self.columns = columns
        self.table = table

    def connect(self):
        self.conn = sqlite3.connect(str(Path.cwd())+'/data/pickle.db')
        self.cur = self.conn.cursor()
        

    def get(self,obj):
        self.connect()
        rsp  = {'rsp':True,'data':[],'count':0}
        try:
            #create sql
            sql = f' select {",".join(self.columns)} from  {self.table}  '
            csql = f' select count(*) from  {self.table}  '
            if len(obj) != 0: 
                valid = True
                params = []
                where = 'where '
                for key in obj:
                    if obj[key] != None:
                        params.append(key+"='"+str(obj[key])+"'")
                    else:
                        valid = False
                        break    

                where+=' and '.join(params)

                if valid:
                    sql+= where
                    csql+= where
            #execute sql
            self.cur.execute(sql)
            rsp['data'] = self.cur.fetchall()
            
            self.cur.execute(csql)
            rsp['count'] = self.cur.fetchone()[0]
            rsp['rsp'] = False if rsp['count'] == 0 else True
        except sqlite3.Error as error:
            print(error)
        finally:
            if self.conn is not None:
                self.conn.close()    
            return rsp

    def insert(self,obj):
        self.connect()
        rsp = {'rsp':False,'data':''}
        try:
            sql =  f' insert into  {self.table} '
            if len(obj) != 0: 
                sql+= ' ('+",".join(obj.keys())+') values ('+",".join(list("?" * len(obj)))+')'

                self.cur.execute(sql,list(obj.values()))
                self.conn.commit()
                rsp['rsp'] = True
                rsp['data'] = self.cur.lastrowid
            else:
                self.conn.rollback()     
        except Error as e:
            print(e)
            self.conn.rollback()
        finally:
            if self.conn is not None:
                self.conn.close()    
            return rsp

    def update(self,obj):
        self.connect()
        rsp = {'rsp':False}
        try:
            sql = " update "+self.table+" set "+",".join([key+'=?' for key in obj.keys()])+" where id='"+str(obj['id'])+ "'"
            if len(obj) != 0: 
                
                self.cur.execute(sql,list(obj.values()))
                self.conn.commit()
                rsp['rsp'] = True
                rsp['data'] = self.cur.lastrowid
            else:
                self.conn.rollback() 

        except Error as e:
            print(e)
            self.conn.rollback()
        finally:
            if self.conn is not None:
                self.conn.close()    
            return rsp


    def delete(self,obj):
        self.connect()
        rsp = {'rsp':False}
        try:
            sql = f' delete from  {self.table}  where '
            if len(obj) != None: 
                params = []
                for key in obj:
                    if obj[key] != None:
                        params.append(key+"='"+str(obj[key])+"'")
                sql+=' and '.join(params)
                self.cur.execute(sql)
                self.conn.commit()
                rsp['rsp'] = True
            else:
                self.conn.rollback()     


            
        except sqlite3.Error as error:
            print(error)
            self.conn.rollback()
        finally:
            if self.conn is not None:
                self.conn.close()    
            return rsp