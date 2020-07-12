


import 'package:angel_container/mirrors.dart';
import 'package:angel_framework/angel_framework.dart';
import 'package:angel_framework/http.dart';
import 'controllers/requestController.dart';




void main() async {

  // Create our server.
  var app = Angel(reflector: MirrorsReflector());
  
  //#region routes
  app.get('/request/:model', (req, res) async=>  RequestController(req: req,res: res).handleGet());
  app.get('/request/:model/:id', (req, res) async=>  RequestController(req: req,res: res).handleGet());

  app.post('/request/:model', (req, res)async=>  RequestController(req: req,res: res).handlePost());
  app.patch('/request/:model/:id', (req, res)async=>  RequestController(req: req,res: res).handlePatch());
  app.delete('/request/:model/:id', (req, res)async=>  RequestController(req: req,res: res).handleDelete());
  //#endregion
 
  app.fallback((req, res) {
    throw AngelHttpException.notFound(
      message: 'Unknown path: "${req.uri.path}"',
    );
  });





  var http = AngelHttp(app);
  var server = await http.startServer('127.0.0.1', 8080);
  var url = 'http://${server.address.address}:${server.port}';
  print('Listening at $url');
 
}