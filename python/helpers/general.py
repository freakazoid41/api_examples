from datetime import datetime,timedelta
import time
class general():
    def __init__(self):
        pass

    def login(self,data,headers):
        module = __import__('models.users') 
        users = getattr(module, 'users').module

        module = __import__('models.users_keys') 
        users_keys = getattr(module, 'users_keys').module

        u_obj  = users.get({
            'username':data['username'],
            'password':data['password']
        })

        if u_obj['rsp'] == True:
            #get token and person info
            token = self.getToken()
            label = headers['User-Agent']
            #clean all old dated tokens
            users_keys.delete({
                'user_id': str(u_obj['data'][0][0]),
                'user_label':label
            })
            #add new token to database
            users_keys.insert({
                'user_id': str(u_obj['data'][0][0]),
                'user_label':label,
                'user_key':token['token'],
                'end_at':token['end_at']
            })

            return {
                'rsp':True,
                'msg':'Logged In !!',
                'data':{
                    'id':str(u_obj['data'][0][0]),
                    'token':token['token'],
                }
            }

        else:
            return {
                'rsp':False,
                'msg':'User Not Valid !!',
            } 


    def getToken(self):
        now = datetime.now()
        return {
            'token':'pickle_key_'+str(now.strftime('%Y%m%d%H%M%S%f')),
            'end_at':str(now + timedelta(minutes = 30))
        }


    def checkToken(self,headers):
        #get user from database
        module = __import__('models.users_keys') 
        users_keys = getattr(module, 'users_keys').module
        #get user from database
        token = users_keys.get({
            'user_key':headers['X-TOKEN'],
            'user_label':headers['User-Agent']
        })
        if token['rsp'] == True:
            #check date first
            key_date = datetime.strptime(token['data'][0][4].split('.')[0], '%Y-%m-%d %H:%M:%S')
            duration = datetime.now() - key_date 
            diff = divmod(duration.total_seconds(), 60)[0]
            if  diff < 5:
                return True
            else:
                #give more time if you want or just reject
                if diff < 10:
                    print('giving more time')
                    #give another 30 minute
                    users_keys.update({
                        'id':token['data'][0][0],
                        'end_at':str(datetime.now() + timedelta(minutes = 30))
                    })
                    return True
                else:
                    return False    
            pass
        else:
            return False