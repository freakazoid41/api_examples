<?php 

class Currencies extends CI_Model {

    public $id;
    public $symbol;
    public $key;
    public $created_at;

    function __construct() {
        $this->table = strtolower(get_class($this));
        $this->columns = array_keys(get_class_vars(get_class($this)));
    }

    public function _get($obj = null,$query=null){
        $sql = "select  ".implode(',',$this->columns)." from ".$this->table." as i";
        if($query != null){
            $values = array();
            foreach($query as $k=>$z){
                array_push($values,trim($k)."='".trim($z)."'");
            }
            $sql .= ' where '.implode(' and ',$values);
        }else{
            if($obj!= null){
                $sql .= " where i.id='".$obj['id']."' ";
            }
        }
        $query = $this->db->query($sql); 
        $result = $query->result();
        if(count($result)==0){
            return array('data' =>array(),'rsp'=>false);
        }
        return array('data' =>$result,'rsp'=>true);
    }

    public function _add($obj=null){
        $rsp = array('data' =>array(),'rsp'=>false,'msg'=>'Empty form..');
        if($obj != null){
            unset($obj['id']);
            $query = $this->db->insert($this->table, (object)$obj);
            if($query == 1){
                $rsp = array('data' =>$this->db->insert_id(),'rsp'=>true);
            }
        }
        return $rsp;
    }

    public function _update($obj=null){
        
        $rsp = array('data' =>array(),'rsp'=>false,'msg'=>'Empty form..');
        if($obj != null){
            if(isset($obj['id'])){
                $query = $this->db->update($this->table, (object)$obj, array('id' => $obj['id']));
                if($query == 1){
                    $rsp = array('data' =>$obj['id'],'rsp'=>true);
                }
            }else{
                $rsp = array('data' =>array(),'rsp'=>false,'msg'=>'No id..');
            }
            
        }
        return $rsp;
    }


    public function _delete($obj = null)
    {
        $rsp = array('data' =>array(),'rsp'=>false);
        if($obj != null){
            if(isset($obj['id'])){
                $valid = $this->db->delete($this->table,array('id'=>$obj['id'])) == 1;
                if($valid)$rsp = array('data' =>$obj['id'],'rsp'=>true);
               
            }else{
                $rsp = array('data' =>array(),'rsp'=>false,'msg'=>'No id..');
            }
            
        }
        return $rsp;
    }
    
}
