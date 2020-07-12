part of models;

class Todo extends Main{
  Todo(){
    print('todo loaded');
  }

  Map<String,String> handleAdd(Map data) {
    print(data);
    return {'rsp':'true','data':'1'};
  }

  Map<String,String> handleUpdate(Map data) {
    return {'rsp':'true','data':'1'};
  }

  Map<String,String> handleDelete(int id) {
    return {'rsp':'true','data':'1'};
  }

  List<Map<String, String>> handleGet({int id}) {
    return [{'data':'1'}];
  }
}