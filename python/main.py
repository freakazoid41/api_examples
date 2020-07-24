from controllers.request import *
from helpers.general import general
app = Flask(__name__)
api = Api(app)
gh = general()


@app.before_request
def enter():
    # request - flask.request
    if 'X-TOKEN' in request.headers or request.endpoint == 'login':
        if request.endpoint != 'login':
            #check token
            if gh.checkToken(request.headers)==False:
                return Response('Token Is Not Valid..', 401)
            else:    
                pass
        else:
            pass    
    else:
        return Response('Token Is Not Valid..', 401)
        #print(request.headers['x-token'])
       

'''@app.after_request
def exit(response):
    print(response)
    return jsonify(response)'''


@app.route('/login', methods = ['POST'])
def login():
    return jsonify(gh.login(request.form,request.headers))

        

api.add_resource(RequestController, '/request/<model>','/request/<model>/<id>') # Route_3


if __name__ == '__main__':
    app.run(port='5002')