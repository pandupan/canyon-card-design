<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{ 
    public function __construct()
	{
		parent::__construct();
    }
    
    function create_taxes($data){
        if($this->db->insert('taxes', $data)){
            return true;
        }else{
            return false;
        }
    }
    function update_taxes($id,$data){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->update('taxes', $data)){
            return true;
        }else{
            return false;
        }
    }

    function delete_taxes($id){
        $this->db->where('id', $id);
        $this->db->where('saas_id', $this->session->userdata('saas_id'));
        if($this->db->delete('taxes'))
            return true;
        else
            return false;
    }

    function get_taxes($tax_id = ''){
        $where = "";
        $where .= (!empty($tax_id) && is_numeric($tax_id))?" WHERE id=$tax_id":"";
        $query = $this->db->query("SELECT * FROM taxes $where ");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }
    
    function update_email_templates($name, $data){
        $this->db->where('name', $name);
        if($this->db->update('email_templates', $data)){
            return true;
        }else{
            return false;
        }
    }

    function get_email_templates($name = ''){
        $where = "";
        $where .= (!empty($name))?" WHERE name='$name'":"";
        $query = $this->db->query("SELECT * FROM email_templates $where ");
        $data = $query->result_array();
        if($data){
            return $data;
        }else{
            return false;
        }
    }

    function save_settings($setting_type,$data){
        $this->db->where('type', $setting_type);
        $query = $this->db->get('settings');
        if($query->num_rows() > 0){
            $this->db->where('type', $setting_type);
            $this->db->update('settings', $data);
            return true;
        }else{
            $data["type"] = $setting_type;
            if($this->db->insert('settings', $data)){
                return true;
            }else{
                return false;
            }
        }
    }
}