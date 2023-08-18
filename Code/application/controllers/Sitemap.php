<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemap extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function index(){
		$this->data['cards'] = $this->cards_model->get_xml_card_url();
		$this->load->view('sitemap', $this->data);
	}

}







