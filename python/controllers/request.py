
from flask import Flask, request,Response,jsonify
from flask_restful import Resource, Api
from json import dumps

class RequestController(Resource):
    def get(self,model,id=None):
        model = self.load(model)
        model.get(id)
        return jsonify({'falann':'boyle'})
        
    def put(self, model):
        print('falan')
        print(request.form['data'])
        return {'falann':'boyle'} 
    def post(self, model):
        print(request.form['data'])
        return {'falann':'boyle'} 
    def delete(self, model):
        print(request.form['data'])
        return {'falann':'boyle'}    

    def load(self,model):
        module = __import__('models.'+model) 
        return getattr(module, model).module