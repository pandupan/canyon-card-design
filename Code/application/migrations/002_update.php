<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v2
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$this->db->query("CREATE TABLE card_sections
		(
		   id INT AUTO_INCREMENT PRIMARY KEY,
		   saas_id INT NOT NULL,
		   user_id INT NOT NULL,
		   card_id INT NOT NULL,
		   title TEXT NOT NULL,
		   content TEXT NOT NULL,
		   order_by_id INT NOT NULL DEFAULT '0',
		   created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)");

		$this->db->query("CREATE TABLE card_fields
		(
		   id INT AUTO_INCREMENT PRIMARY KEY,
		   saas_id INT NOT NULL,
		   user_id INT NOT NULL,
		   card_id INT NOT NULL,
		   type TEXT NOT NULL,
		   icon TEXT NOT NULL,
		   title TEXT NOT NULL,
		   url TEXT NOT NULL,
		   order_by_id INT NOT NULL DEFAULT '0',
		   created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		)");

		$fields = array(
			'receipt' => array(
				'type' => 'TEXT',
			),
		);
		$this->dbforge->add_column('offline_requests', $fields);

		$this->dbforge->add_field(array(
				'id' => array(
						'type' => 'INT',
						'auto_increment' => TRUE
				),
				'user_id' => array(
					'type' => 'INT',
				),
				'subject' => array(
					'type' => 'TEXT',
				),
				'status' => array(
					'type' => 'INT',
					'default' => 1,
				),
				'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('support');

		$this->dbforge->add_field(array(
			'id' => array(
					'type' => 'INT',
					'auto_increment' => TRUE
			),
			'from_id' => array(
				'type' => 'INT',
			),
			'to_id' => array(
				'type' => 'INT',
			),
			'message' => array(
				'type' => 'TEXT',
			),
			'is_read' => array(
				'type' => 'INT',
				'default' => 0,
			),
			'created timestamp default current_timestamp',
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('support_messages');

		$this->db->query("ALTER TABLE `cards` CHANGE `card_bg_type` `card_theme_bg_type` VARCHAR(256) NOT NULL DEFAULT 'Color'");

		$this->db->query("ALTER TABLE `cards` CHANGE `card_bg` `card_theme_bg` TEXT NOT NULL");

		$this->db->query("ALTER TABLE `cards` ADD `card_bg_type` VARCHAR(256) NOT NULL DEFAULT 'Color' AFTER `views`, ADD `card_bg` TEXT NOT NULL AFTER `card_bg_type`, ADD `card_font_color` VARCHAR(256) NOT NULL DEFAULT '#000000' AFTER `card_bg`, ADD `card_font` TEXT NOT NULL AFTER `card_font_color`, ADD `scans` INT NOT NULL AFTER `card_font`, ADD `enquery_email` TEXT NOT NULL AFTER `scans`, ADD `show_add_to_phone_book` INT NOT NULL DEFAULT '1' AFTER `enquery_email`, ADD `show_share` INT NOT NULL DEFAULT '1' AFTER `show_add_to_phone_book`, ADD `show_qr_on_card` INT NOT NULL DEFAULT '1' AFTER `show_share`, ADD `show_qr_on_share_popup` INT NOT NULL DEFAULT '1' AFTER `show_qr_on_card`, ADD `search_engine_indexing` INT NOT NULL DEFAULT '0' AFTER `show_qr_on_share_popup`, ADD `custom_css` TEXT NOT NULL AFTER `search_engine_indexing`, ADD `custom_js` TEXT NOT NULL AFTER `custom_css`");
		
		$this->db->query("ALTER TABLE `plans` ADD `custom_fields` INT NOT NULL DEFAULT '-1' AFTER `cards`, ADD `products_services` INT NOT NULL DEFAULT '-1' AFTER `custom_fields`, ADD `portfolio` INT NOT NULL DEFAULT '-1' AFTER `products_services`, ADD `testimonials` INT NOT NULL DEFAULT '-1' AFTER `portfolio`, ADD `gallery` INT NOT NULL DEFAULT '-1' AFTER `testimonials`, ADD `custom_sections` INT NOT NULL DEFAULT '-1' AFTER `gallery`");

		$query = $this->db->query("SELECT id,saas_id,user_id,social_options FROM cards WHERE social_options != '' ");
		$res = $query->result_array();
		if($res){
			foreach($res as $row){
				
				$card_fields_data['saas_id'] = $row['saas_id'];
				$card_fields_data['user_id'] = $row['user_id'];
				$card_fields_data['card_id'] = $row['id'];

				$social_options = (isset($row['social_options']) && $row['social_options'] != '')?json_decode($row['social_options'],true):'';

				if(isset($social_options['mandatory']) && isset($social_options['mandatory']['mobile']) && $social_options['mandatory']['mobile'] != ''){
					$card_fields_data['type'] = 'mobile';
					$card_fields_data['icon'] = 'fas fa-mobile-alt';
					$card_fields_data['title'] = $social_options['mandatory']['mobile'];
					$card_fields_data['url'] = $social_options['mandatory']['mobile'];
					$this->db->insert('card_fields', $card_fields_data);
				}else{
					$card_fields_data['type'] = 'mobile';
					$card_fields_data['icon'] = 'fas fa-mobile-alt';
					$card_fields_data['title'] = '';
					$card_fields_data['url'] = '';
					$this->db->insert('card_fields', $card_fields_data);
				}
				if(isset($social_options['mandatory']) && isset($social_options['mandatory']['email']) && $social_options['mandatory']['email'] != ''){
					$card_fields_data['type'] = 'email';
					$card_fields_data['icon'] = 'far fa-envelope';
					$card_fields_data['title'] = $social_options['mandatory']['email'];
					$card_fields_data['url'] = $social_options['mandatory']['email'];
					$this->db->insert('card_fields', $card_fields_data);
				}else{
					$card_fields_data['type'] = 'email';
					$card_fields_data['icon'] = 'far fa-envelope';
					$card_fields_data['title'] = '';
					$card_fields_data['url'] = '';
					$this->db->insert('card_fields', $card_fields_data);
				}
				if(isset($social_options['mandatory']) && isset($social_options['mandatory']['whatsapp']) && $social_options['mandatory']['whatsapp'] != ''){
					$card_fields_data['type'] = 'whatsapp';
					$card_fields_data['icon'] = 'fab fa-whatsapp';
					$card_fields_data['title'] = $social_options['mandatory']['whatsapp'];
					$card_fields_data['url'] = $social_options['mandatory']['whatsapp'];
					$this->db->insert('card_fields', $card_fields_data);
				}else{
					$card_fields_data['type'] = 'whatsapp';
					$card_fields_data['icon'] = 'fab fa-whatsapp';
					$card_fields_data['title'] = '';
					$card_fields_data['url'] = '';
					$this->db->insert('card_fields', $card_fields_data);
				}
				if(isset($social_options['mandatory']) && isset($social_options['mandatory']['address']) && $social_options['mandatory']['address'] != ''){
					$card_fields_data['type'] = 'address';
					$card_fields_data['icon'] = 'fas fa-map-marker-alt';
					$card_fields_data['title'] = $social_options['mandatory']['address'];
					$card_fields_data['url'] = isset($social_options['mandatory']['address_url']) && $social_options['mandatory']['address_url'] != ''?$social_options['mandatory']['address_url']:'';
					$this->db->insert('card_fields', $card_fields_data);
				}else{
					$card_fields_data['type'] = 'address';
					$card_fields_data['icon'] = 'fas fa-map-marker-alt';
					$card_fields_data['title'] = '';
					$card_fields_data['url'] = '';
					$this->db->insert('card_fields', $card_fields_data);
				}
				
				if(isset($social_options['optional']) && $social_options['optional'] != '' && $social_options['optional']['icon'] && $social_options['optional']['text'] && $social_options['optional']['url']){ 
					foreach($social_options['optional']['icon'] as $key_icon => $icon){
						foreach($social_options['optional']['text'] as $key_text => $text){ 
							if($key_icon == $key_text){ 
								foreach($social_options['optional']['url'] as $key_url => $url){  
									if($key_icon == $key_url){
										$card_fields_data['type'] = 'custom';
										$card_fields_data['icon'] = $icon;
										$card_fields_data['title'] = $text;
										$card_fields_data['url'] = $url;
										$this->db->insert('card_fields', $card_fields_data);
									} 
								}	
							} 
						} 
					} 
				}
			}
		}

		$this->db->set('value', '2');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');

	}

	public function down() {
	}
}
