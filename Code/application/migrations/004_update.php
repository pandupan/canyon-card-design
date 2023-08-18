<?php defined('BASEPATH') OR exit('No direct script access allowed');
// v2.9
class Migration_update extends CI_Migration {

	public function __construct() {
		parent::__construct();
		$this->load->dbforge();
	}

	public function up() {  

		$this->db->query("INSERT INTO `groups` (`id`, `name`, `description`) VALUES ('2', 'members', 'General User')");

		$this->db->query("ALTER TABLE `plans` ADD `team_member` INT NOT NULL DEFAULT '-1' AFTER `custom_sections`");

		$this->db->query("ALTER TABLE `orders` ADD `amount` TEXT NOT NULL AFTER `plan_id`, ADD `amount_with_tax` TEXT NOT NULL AFTER `amount`, ADD `tax` TEXT NOT NULL AFTER `amount_with_tax`");

		$this->db->query("ALTER TABLE `transactions` ADD `tax` TEXT NOT NULL AFTER `amount`");

		$this->db->query("CREATE TABLE `taxes` ( `id` INT NOT NULL AUTO_INCREMENT , `saas_id` INT NOT NULL , `title` TEXT NOT NULL , `tax` TEXT NOT NULL , `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`))");

		$this->db->query("UPDATE orders o 
		INNER JOIN transactions t
		ON o.transaction_id = t.id
		SET o.amount = t.amount, o.amount_with_tax = t.amount");

		$this->db->set('value', '2.9');
        $this->db->where('type', 'system_version');
        $this->db->update('settings');

	}

	public function down() {
	}
}
