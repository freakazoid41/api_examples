<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('_login'))
{
    function _login($data)
    {
        $CI = & get_instance();  
        $CI->session->set_userdata('UserReferance',$data->id);
        return array('rsp'=>true,'msg'=>'Hoşgeldiniz ..');
    }   
}


if ( ! function_exists('_get_token'))
{
    function _get_token()
    {
        $token = 'pickle_key'.str_replace('.','',microtime(true));
        return array(
            'token'=>base64_encode($token),
            'end' => date("Y-m-d H:i:s", strtotime("+30 minutes"))
        );
    }   
}

if ( ! function_exists('_check_token'))
{
    function _check_token($obj)
    {
       
        if(isset($obj['X-TOKEN'])){
            $CI =& get_instance();
            //get user from database
            $CI->load->model('users_keys');
            $token = $CI->users_keys->_get(null,array(
                'user_key'=>$obj['X-TOKEN'],
                'user_label'=>$obj['User-Agent']
            ));
            if(!empty($token) && isset($token['data'][0])){
                if(strtotime("now")<strtotime(explode('+',$token['data'][0]->end_at)[0])){
                    return array('rsp'=>true);
                }else{
                    //give more time if you want or just reject
                    //hour diff
                    $diff =  abs(strtotime("now") - strtotime(explode('+',$token['data'][0]->end_at)[0]))/(60*60);
                    if($diff<1){
                        //give another 30 minute
                        $CI->users_keys->_update(array(
                            'id' => $token['data'][0]->id,
                            'end_at' =>date("Y-m-d H:i:s", strtotime("+30 minutes"))
                        ));
                        return array('rsp'=>true);
                    }else{
                        //clear keys
                        $CI->users_keys->_delete($token['data'][0]->id);
                        return array('rsp'=>false,'msg' => 'old key ..');
                    }
                }
            }
        }else{
            return array('rsp'=>false,'msg'=>'Invalid Key..');
        }
    }   
}


if ( ! function_exists('_get_seo'))
{
    function _get_seo($value)
    {
        $value = strtolower($value);
        $value = str_replace(" ", "_", $value);
        $value = str_replace("ı", "i", $value);
        $value = str_replace("ö", "o", $value);
        $value = str_replace("ü", "u", $value);
        $value = str_replace("ş", "s", $value);
        $value = str_replace("ç", "c", $value);
        return array('rsp'=>true,'data'=>$value);
    }   
}

if ( ! function_exists('_log'))
{
    function _log($obj)
    {
       $CI =& get_instance();
       $CI->load->model('sys_log');
       return $CI->sys_log->_add($obj)['rsp'];
    }   
}



