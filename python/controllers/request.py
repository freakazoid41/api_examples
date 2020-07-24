
from flask import Flask, request,Response,jsonify
from flask_restful import Resource, Api
from json import dumps

class RequestController(Resource):
    def get(self,model,id=None):
        model = self.load(model)
        return model.get({'id':id})
        
    def patch(self, model,id=None):
        print(id)
        print('falan')
        print(request.form)
        #model.get(id)
        return {'falann':'boyle'} 
    def post(self, model):
        print(request.form)
        return {'falann':'boyle'} 

    def delete(self, model,id=None):
        return {'falann':'boyle'}    

    def load(self,model):
        module = __import__('models.'+model) 
        return getattr(module, model).module