import '../models/models.dart' show Todo;

class RequestController {
    var req;
    var res;
    var model;
    var body;
    int id;
    Map<String,dynamic> models = {
      'todo':Todo(),
    };
    
    RequestController({this.req,this.res}){
      model = models[req.params['model']];
      id = int.parse(req.params['id']);
      
      //req.headers
    }

    Future<Map<String, String>> handlePost() async{
      await req.parseBody();
      body = req.bodyAsMap;
      return model.handleAdd(body);
    }


    Future<Map<String, String>> handlePatch() async{
      await req.parseBody();
      print(req.bodyAsMap);
      body = req.bodyAsMap;
      return model.handleUpdate(body);
    }


    Map<String, String> handleDelete() {
      return model.handleDelete(id);
    }


    List<Map<String, String>> handleGet(){
      return model.handleGet(id:id);
    }
}