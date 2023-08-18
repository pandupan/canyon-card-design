<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Timezone_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
        $this->db->from('settings');
        $this->db->where(['type'=>'general']);
        $query = $this->db->get();
        $confi = $query->result_array();
        $confi = json_decode($confi[0]['value']);
        if(!empty($confi->mysql_timezone)){
            $this->db->query("SET time_zone='".$confi->mysql_timezone."'");
        }else{
            $this->db->query("SET time_zone='-11:00'");
        }
        if(!empty($confi->php_timezone)){
            date_default_timezone_set($confi->php_timezone);
        }else{
            date_default_timezone_set('Pacific/Midway');
        }

    }

}