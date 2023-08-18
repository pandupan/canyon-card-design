<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v2.4
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$this->db->query("ALTER TABLE `languages` ADD `status` INT NOT NULL DEFAULT '1'");

		$this->db->query("ALTER TABLE `products` ADD `order_by_id` INT NOT NULL DEFAULT '0' AFTER `url`");

		$this->db->query("ALTER TABLE `portfolio` ADD `order_by_id` INT NOT NULL DEFAULT '0' AFTER `url`");
		
		$this->db->query("ALTER TABLE `gallery` ADD `order_by_id` INT NOT NULL DEFAULT '0' AFTER `url`");

		$this->db->query("ALTER TABLE `testimonials` ADD `order_by_id` INT NOT NULL DEFAULT '0' AFTER `rating`");
		
		$this->db->query("ALTER TABLE `cards` ADD `make_setions_conetnt_carousel` INT NOT NULL DEFAULT '0' AFTER `search_engine_indexing`");
		$this->db->query("ALTER TABLE `cards` ADD `qr_code_options` TEXT NOT NULL AFTER `custom_js`");
		$this->db->query("ALTER TABLE `cards` ADD `reorder_sections` TEXT NOT NULL AFTER `custom_js`");
		$this->db->query("ALTER TABLE `cards` ADD `custom_domain` TEXT NOT NULL AFTER `custom_js`");
		$this->db->query("ALTER TABLE `cards` ADD `custom_domain_status` INT NOT NULL DEFAULT '0' AFTER `custom_js`");
		$this->db->query("ALTER TABLE `cards` ADD `custom_domain_redirect` INT NOT NULL DEFAULT '0' AFTER `custom_js`");
		$this->db->query("ALTER TABLE `cards` ADD `show_card_view_count_on_a_card` INT NOT NULL DEFAULT '0' AFTER `show_qr_on_card`");
		$this->db->query("ALTER TABLE `cards` ADD `show_change_language_option_on_a_card` INT NOT NULL DEFAULT '0' AFTER `show_qr_on_card`");
		
		$this->db->query("INSERT INTO `email_templates` (`name`, `subject`, `message`, `variables`) VALUES ('front_enquiry_form', 'Contact Form submitted', '<p>Name:&nbsp;<span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">{NAME} </span></p>\r\n<p><span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">Email: {EMAIL}</span></p>\r\n<p><span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">{MESSAGE}</span></p>', '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {NAME}, {EMAIL}, {MESSAGE}')");

		$this->db->query("INSERT INTO `email_templates` (`name`, `subject`, `message`, `variables`) VALUES ('card_enquiry_form', 'Enquiry form submitted from your vCard', '<p>Name:&nbsp;<span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">{NAME}</span></p>\r\n<p><span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">Email: {EMAIL}</span></p>\r\n<p><span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">Mobile: {MOBILE}</span></p>\r\n<p><span style=\"background-color: #ffffff; color: #0d1137; font-family: Nunito, \'Segoe UI\', arial;\">{MESSAGE}</span></p>', '{COMPANY_NAME}, {DASHBOARD_URL}, {LOGO_URL}, {NAME}, {EMAIL}, {MOBILE}, {MESSAGE}, {CARD_EMAIL}, {CARD_NAME}, {CARD_URL}')");

		$this->db->set('value', '2.4');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');

	}

	public function down() {
	}
}
