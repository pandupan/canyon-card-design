<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cards extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function ajax_get_custom_domain_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$custom_domain = $this->cards_model->get_card_by_ids($id);
			if(!empty($custom_domain)){
				$this->data['error'] = false;
				$this->data['data'] = $custom_domain;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'Nothing found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function custom_domain()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('custom_domain') && !turn_off_custom_domain_system())
		{

			if ($this->ion_auth->is_admin()){
				$this->notifications_model->edit('', 'new_domain_status', '', '', '');
			}

			$this->data['page_title'] = 'Custom Domain - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-custom-domain',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function domain_request()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3) && !turn_off_custom_domain_system())
		{
			$this->notifications_model->edit('', 'new_domain', '', '', '');

			$this->data['page_title'] = 'Domain Request - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-custom-domain-edit',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function clone($id='')
	{
		if ($this->ion_auth->logged_in() && $this->session->userdata('saas_id'))
		{
			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			if(empty($id) || !is_numeric($id)){
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
				return false;
			}

			$query = $this->db->query("INSERT INTO cards (saas_id, user_id, slug, theme_name, card_theme_bg_type, card_theme_bg, card_bg_type, card_bg, card_font_color, card_font, profile, title, sub_title, description, google_analytics, banner, hide_branding, views, scans, enquery_email, qr_code_options, custom_domain, custom_domain_status, custom_domain_redirect, show_add_to_phone_book, show_share, show_qr_on_card, show_qr_on_share_popup, show_change_language_option_on_a_card, reorder_sections, show_card_view_count_on_a_card, search_engine_indexing, make_setions_conetnt_carousel, custom_css, custom_js) SELECT saas_id, user_id, ".time().", theme_name, card_theme_bg_type, card_theme_bg, card_bg_type, card_bg, card_font_color, card_font, profile, title, sub_title, description, google_analytics, banner, hide_branding, views, scans, enquery_email, qr_code_options, '', custom_domain_status, custom_domain_redirect, show_add_to_phone_book, show_share, show_qr_on_card, show_qr_on_share_popup, show_change_language_option_on_a_card, reorder_sections, show_card_view_count_on_a_card, search_engine_indexing, make_setions_conetnt_carousel, custom_css, custom_js FROM cards WHERE id=$id AND saas_id=".$this->session->userdata('saas_id'));

			$card_id = $this->db->insert_id();

			if($card_id){

				$this->db->query("INSERT INTO card_fields (saas_id, user_id, card_id, type, icon, title, url, order_by_id) SELECT saas_id, user_id, $card_id, type, icon, title, url, order_by_id FROM card_fields WHERE card_id=$id");

				$this->db->query("INSERT INTO card_sections (saas_id, user_id, card_id, title, content) SELECT saas_id, user_id, $card_id, title, content FROM card_sections WHERE card_id=$id");
				
				$this->db->query("INSERT INTO gallery (saas_id, user_id, card_id, content_type, title, url, order_by_id) SELECT saas_id, user_id, $card_id, content_type, title, url, order_by_id FROM gallery WHERE card_id=$id");
				
				$this->db->query("INSERT INTO portfolio (saas_id, user_id, card_id, title, description, image, url, order_by_id) SELECT saas_id, user_id, $card_id, title, description, image, url, order_by_id FROM portfolio WHERE card_id=$id");

				$this->db->query("INSERT INTO products (saas_id, user_id, card_id, title, price, description, image, url, order_by_id) SELECT saas_id, user_id, $card_id, title, price, description, image, url, order_by_id FROM products WHERE card_id=$id");

				$this->db->query("INSERT INTO testimonials (saas_id, user_id, card_id, title, description, image, rating, order_by_id) SELECT saas_id, user_id, $card_id, title, description, image, rating, order_by_id FROM testimonials WHERE card_id=$id");

				$this->session->set_flashdata('message', $this->lang->line('card_cloned_successfully')?htmlspecialchars($this->lang->line('card_cloned_successfully')):'Card cloned successfully.');
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('card_cloned_successfully')?htmlspecialchars($this->lang->line('card_cloned_successfully')):'Card cloned successfully.';
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function scan($slug = NULL)
	{
		if($slug == NULL){
			show_404();
			return false;
		}

		$card = $this->cards_model->get_card_by_slug($slug);
		
		if($card){
			$data['scans'] = 1 + $card['scans'];
			$this->cards_model->save($card['id'], $card['user_id'], $data);
			redirect(base_url().''.$slug, 'refresh');
		}else{
			show_404();
		}
	}

	public function info($slug = NULL)
	{
		$this->data['current_user'] = $this->ion_auth->user()->row();
		$card = $this->cards_model->get_card_by_slug($slug);
		
		if($card){

			$this->data['card_plan_details'] = $my_plan = get_current_plan($card['saas_id']);

			if($my_plan){
				$this->data['card_plan_modules'] = $card_plan_modules = json_decode($my_plan['modules'], true);
			}else{
				$this->data['card_plan_modules'] = $card_plan_modules = false;
			}

			if ($my_plan && !is_null($my_plan['end_date']) && $my_plan['end_date'] < date('Y-m-d') && $my_plan['expired'] == 1)
			{
			  $users_plans_data = array(
				'expired' => 0,			
			  );
			  $users_plans_id = $this->plans_model->update_users_plans($card['user_id'], $users_plans_data);
			  show_404();
			}
			if($my_plan && !is_null($my_plan['end_date']) && $my_plan['expired'] == 0){ 
				show_404();
			}

			$this->data['card'] = $card;
			$this->data['page_title'] = $card['title'];
			$this->data['meta_image'] = ($card['profile'] != '' && file_exists('assets/uploads/card-profile/'.$card['profile']))?base_url('assets/uploads/card-profile/'.$card['profile']):base_url('assets/uploads/logos/'.half_logo());
			$this->data['banner'] = ($card['banner'] != '' && file_exists('assets/uploads/card-banner/'.$card['banner']))?base_url('assets/uploads/card-banner/'.$card['banner']):'';
			$this->data['meta_description'] = $card['description'];
			$this->data['google_analytics'] = $card['google_analytics'];

			$this->data['products'] = $this->cards_model->get_products('', $card['user_id'], $card['id']);

			$this->data['portfolio'] = $this->cards_model->get_portfolio('', $card['user_id'], $card['id']);

			$this->data['gallery'] = $gallery = $this->cards_model->get_gallery('', $card['user_id'], $card['id']);

			if($gallery){
				foreach($gallery as $key => $gal){ 
					if($gal['content_type'] == 'youtube' && $gal['url'] != ''){
						$this->data['gallery'][$key]['thumb'] = get_video_thumbnail('youtube', $gal['url']);
					}elseif($gal['content_type'] == 'vimeo' && $gal['url'] != ''){
						$this->data['gallery'][$key]['thumb'] = get_video_thumbnail('vimeo', $gal['url']);
					}else{
						$this->data['gallery'][$key]['thumb'] = $gal['url'];
					}
				}
			}

			$this->data['testimonials'] = $this->cards_model->get_testimonials('', $card['user_id'], $card['id']);

			$this->data['custom_sections'] = $this->cards_model->get_custom_sections('', $card['user_id'], $card['id']);

			$this->data['custom_fields'] = $this->cards_model->get_custom_fields('', $card['user_id'], $card['id']);
			
			if(isset($card_plan_modules) && isset($card_plan_modules['ads']) && $card_plan_modules['ads'] != 1){ 
				$this->data['ads_header_code'] = get_ads_data('header_code');
				$this->data['ads_footer_code'] = get_ads_data('footer_code');
				$this->data['ad_area'] = get_ads_data('ad_area');
				$this->data['ad_code'] = get_ads_data('ad_code');
			}else{
				$this->data['ads_header_code'] = '';
				$this->data['ads_footer_code'] = '';
				$this->data['ad_area'] = '';
				$this->data['ad_code'] = '';
			}
			
			
			$this->data['card']['qr_code_options'] = !empty($card['qr_code_options'])?json_decode($card['qr_code_options'], true):'';

			if(isset($card['reorder_sections'])){
				$reorder_sections = empty($card['reorder_sections'])?array():json_decode($card['reorder_sections']);
				if(!in_array('main_card_section', $reorder_sections)){
					array_push($reorder_sections, 'main_card_section');
				}
				if(!in_array('products_services', $reorder_sections)){
					array_push($reorder_sections, 'products_services');
				}
				if(!in_array('portfolio', $reorder_sections)){
					array_push($reorder_sections, 'portfolio');
				}
				if(!in_array('gallery', $reorder_sections)){
					array_push($reorder_sections, 'gallery');
				}
				if(!in_array('testimonials', $reorder_sections)){
					array_push($reorder_sections, 'testimonials');
				}
				if(!in_array('qr_code', $reorder_sections)){
					array_push($reorder_sections, 'qr_code');
				}
				if(!in_array('enquiry_form', $reorder_sections)){
					array_push($reorder_sections, 'enquiry_form');
				}
				if(!in_array('custom_sections', $reorder_sections)){
					array_push($reorder_sections, 'custom_sections');
				}
				$this->data['card']['reorder_sections'] = json_encode($reorder_sections);
			}else{
				$this->data['card']['reorder_sections'] = json_encode(array('main_card_section', 'products_services', 'portfolio', 'gallery', 'testimonials', 'qr_code', 'enquiry_form', 'custom_sections'));
			}

			if($this->session->userdata('visited') == '' || $this->session->userdata('visited') != $card['id']){
				$this->session->set_userdata('visited', $card['id']);
				$data['views'] = 1 + $card['views'];
				$this->cards_model->save($card['id'], $card['user_id'], $data);
			}

			if($this->uri->segment(2) && ($this->uri->segment(2) == 'theme_one' || $this->uri->segment(2) == 'theme_two' || $this->uri->segment(2) == 'theme_three' || $this->uri->segment(2) == 'theme_four' || $this->uri->segment(2) == 'theme_five' || $this->uri->segment(2) == 'theme_six' || $this->uri->segment(2) == 'theme_seven' || $this->uri->segment(2) == 'theme_eight')){
				$this->load->view('cards/'.$this->uri->segment(2),$this->data);
			}else{

				if(!turn_off_custom_domain_system() && $card['custom_domain'] != '' && $card['custom_domain_status'] == 1 && $card['custom_domain_redirect'] == 1){
					header('Location: http://'.$card['custom_domain']);
					exit();
				}
				
				if(isset($card['theme_name']) && $card['theme_name'] != ''){
					$this->load->view('cards/'.$card['theme_name'],$this->data);
				}else{
					$this->load->view('cards/theme_one',$this->data);
				}
			}
			
		}else{
			show_404();
		}
	}

	public function index()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Cards - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
            $this->load->view('cards',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function delete_card($id='')
	{
		

		if ($this->ion_auth->logged_in())
		{
			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			$this->session->set_userdata('current_card_id', '');

			if(!empty($id) && is_numeric($id) && $this->cards_model->delete_card($id)){

				
				$this->notifications_model->delete('', '', $id);

				$this->cards_model->delete_product('', $id);
				$this->cards_model->delete_portfolio('', $id);
				$this->cards_model->delete_gallery('', $id);
				$this->cards_model->delete_testimonials('', $id);

				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function get_cards()
	{	
		if ($this->ion_auth->logged_in())
		{
			return $this->cards_model->get_cards();
		}else{
			return '';
		}
	}

	public function get_domain_request()
	{	
		if ($this->ion_auth->logged_in())
		{
			return $this->cards_model->get_domain_request();
		}else{
			return '';
		}
	}

	public function qr()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('qr_code'))
		{
			$this->data['page_title'] = 'QR Code - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['card']['qr_code_options'] = !empty($card_data['qr_code_options'])?json_decode($card_data['qr_code_options'], true):'';

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();

            $this->load->view('card-qr',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

    public function theme()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Theme - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();	

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			if($card_data['card_theme_bg_type'] == 'Gradient'){
				preg_match_all("/#([\w\d]*)/",$card_data['card_theme_bg'],$query);
				$this->data['card']['color_1'] = isset($query[0][0])?$query[0][0]:'';
				$this->data['card']['color_2'] = isset($query[0][0])?$query[0][1]:'';
			}else{
				$this->data['card']['color_1'] = '';
				$this->data['card']['color_2'] = '';
			}

			if($card_data['card_bg_type'] == 'Gradient'){
				preg_match_all("/#([\w\d]*)/",$card_data['card_bg'],$query);
				$this->data['card']['card_color_1'] = isset($query[0][0])?$query[0][0]:'';
				$this->data['card']['card_color_2'] = isset($query[0][0])?$query[0][1]:'';
			}else{
				$this->data['card']['card_color_1'] = '';
				$this->data['card']['card_color_2'] = '';
			}
			
			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
			$this->data['demo'] = $this->cards_model->get_card_by_ids('', 1);
			
            $this->load->view('card-theme',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function advanced()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Advanced - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}
			
			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-advanced',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function profile()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Profile - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}
			
			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-profile',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

    public function details()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Contact Details - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}
			
			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-contact-details',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}


	
	public function save()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|is_numeric|strip_tags|xss_clean');

			$this->form_validation->set_rules('changes_type', 'changes type', 'trim|required|strip_tags|xss_clean');
			
			if($this->input->post('changes_type') == 'theme'){
				$theme_color = '';
				$card_color = '';
				$this->form_validation->set_rules('theme_name', 'Theme', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('card_theme_bg_type', 'Theme Background Type', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('card_font_color', 'Card Font Color', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('card_font', 'Card Font', 'trim|strip_tags|xss_clean');
	
				if($this->input->post('card_theme_bg_type') == 'Color'){
					$this->form_validation->set_rules('theme_color', 'Theme Color', 'trim|required|strip_tags|xss_clean');
					$theme_color = $this->input->post('theme_color');
				}
				if($this->input->post('card_theme_bg_type') == 'Gradient'){
					$this->form_validation->set_rules('color_1', 'Color', 'trim|required|strip_tags|xss_clean');
					$this->form_validation->set_rules('color_2', 'Color', 'trim|required|strip_tags|xss_clean');
					$theme_color = "linear-gradient(to right, ".$this->input->post('color_1').", ".$this->input->post('color_2').")";
				}
				if($this->input->post('card_theme_bg_type') == 'Transparent'){
					$theme_color = 'transparent';
				}

				
				if($this->input->post('card_bg_type') == 'Color'){
					$this->form_validation->set_rules('card_color', 'Card Color', 'trim|required|strip_tags|xss_clean');
					$card_color = $this->input->post('card_color');
				}
				if($this->input->post('card_bg_type') == 'Gradient'){
					$this->form_validation->set_rules('card_color_1', 'Color', 'trim|required|strip_tags|xss_clean');
					$this->form_validation->set_rules('card_color_2', 'Color', 'trim|required|strip_tags|xss_clean');
					$card_color = "linear-gradient(to right, ".$this->input->post('card_color_1').", ".$this->input->post('card_color_2').")";
				}
				if($this->input->post('card_bg_type') == 'Transparent'){
					$card_color = 'transparent';
				}
				

			}

			if($this->input->post('changes_type') == 'profile'){
				$this->form_validation->set_rules('slug', 'slug', 'trim|required|strip_tags|xss_clean|create_slug');
				$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('sub_title', 'sub title', 'trim|required|strip_tags|xss_clean');
				$this->form_validation->set_rules('short_description', 'short description', 'trim|required|strip_tags|xss_clean');
			}

			if($this->input->post('changes_type') == 'custom_domain'){
				$this->form_validation->set_rules('custom_domain', 'custom domain', 'trim|strip_tags|xss_clean');
			}

			if($this->input->post('changes_type') == 'advanced'){
				$this->form_validation->set_rules('enquery_email', 'enquery email', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('google_analytics', 'google analytics', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('hide_branding', '', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('show_add_to_phone_book', '', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('show_share', '', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('show_qr_on_card', '', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('show_qr_on_share_popup', '', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('show_change_language_option_on_a_card', '', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('show_card_view_count_on_a_card', '', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('search_engine_indexing', '', 'trim|xss_clean|strip_tags');
				$this->form_validation->set_rules('make_setions_conetnt_carousel', '', 'trim|xss_clean|strip_tags');
			}

			if($this->input->post('changes_type') == 'qr_code'){
				$this->form_validation->set_rules('foreground_color', 'foreground color', 'trim|xss_clean|required');
				$this->form_validation->set_rules('background_color', 'background color', 'trim|xss_clean|required');
				$this->form_validation->set_rules('corner_radius', 'corner radius', 'trim|xss_clean|required');
				$this->form_validation->set_rules('qr_type', 'QR type', 'trim|xss_clean|required');
				if($this->input->post('qr_type') != 0){

					$this->form_validation->set_rules('size', 'size', 'trim|xss_clean|required');

					if($this->input->post('qr_type') == 2){
						$this->form_validation->set_rules('text', 'text', 'trim|xss_clean|required|strip_tags');
						$this->form_validation->set_rules('text_color', 'text_color', 'trim|xss_clean|required');
					}

					if($this->input->post('qr_type') == 4 && empty($_FILES['image']['name']) && empty($this->input->post('old_image'))){
						$this->form_validation->set_rules('image', 'image', 'trim|xss_clean|required');
					}

				}
			}

			if($this->input->post('changes_type') == 'reorder_sections'){
				$this->form_validation->set_rules('reorder_sections[]', 'reorder sections', 'trim|xss_clean|required|strip_tags');
			}

			if($this->form_validation->run() == TRUE){
				$data = array();

				if($this->input->post('changes_type') == 'reorder_sections'){
					$reorder_sections = array();
					foreach($this->input->post('reorder_sections') as $key => $reorder_section){
						$reorder_sections[$key] = $reorder_section;
					}
					$data['reorder_sections'] = json_encode($reorder_sections);
				}

				if($this->input->post('changes_type') == 'qr_code'){
					$qr_code_options = array();
					$qr_code_options['foreground_color'] = $this->input->post('foreground_color');
					$qr_code_options['background_color'] = $this->input->post('background_color');
					$qr_code_options['corner_radius'] = $this->input->post('corner_radius');
					$qr_code_options['qr_type'] = $this->input->post('qr_type');
					$qr_code_options['size'] = $this->input->post('size');
					$qr_code_options['text'] = $this->input->post('text');
					$qr_code_options['text_color'] = $this->input->post('text_color');

					if(!empty($_FILES['image']['name']) && $this->input->post('qr_type') == 4){
						$upload_path = 'assets/uploads/qr-img/';
						if(!is_dir($upload_path)){
							mkdir($upload_path,0775,true);
						}
						$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
						$config['upload_path']          = $upload_path;
						$config['allowed_types']        = "jpg|png|jpeg";
						$config['overwrite']             = false;
						$config['max_size']             = 0;
						$config['max_width']            = 0;
						$config['max_height']           = 0;
						$config['file_name']           = $image;
						$this->load->library('upload', $config);
						if($this->upload->do_upload('image')){
							$qr_code_options['image'] = $image;
							if($this->input->post('old_image') != ''){
								$unlink_path = $upload_path.''.$this->input->post('old_image');
								if(file_exists($unlink_path)){
									unlink($unlink_path);
								}
							}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data); 
							return false;
						}
					}else{
					    if($this->input->post('qr_type') == 4){
    					    if($this->input->post('old_image') != ""){
    							$qr_code_options['image'] = $this->input->post('old_image');
    						} 
					    }
					}
					
					$data['qr_code_options'] = json_encode($qr_code_options);
				}


				if($this->input->post('changes_type') == 'theme'){
					$data['theme_name'] = $this->input->post('theme_name');
					$data['card_theme_bg_type'] = $this->input->post('card_theme_bg_type');
					$data['card_theme_bg'] = $theme_color;
					$data['card_bg_type'] = $this->input->post('card_bg_type');
					$data['card_bg'] = $card_color;
					$data['card_font_color'] = $this->input->post('card_font_color');
					$data['card_font'] = $this->input->post('card_font');

					$upload_path = 'assets/uploads/card-bg/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}

					if($this->input->post('card_theme_bg_type') == 'Image'){
						if (!empty($_FILES['theme_image']['name'])){

							$image = time().'-'.str_replace(' ', '-', $_FILES["theme_image"]['name']);
							$config['upload_path']          = $upload_path;
							$config['allowed_types']        = "gif|jpg|png|jpeg";
							$config['overwrite']             = false;
							$config['max_size']             = 0;
							$config['max_width']            = 0;
							$config['max_height']           = 0;
							$config['file_name']           = $image;
							$this->load->library('upload', $config);
							if($this->upload->do_upload('theme_image')){
								$data['card_theme_bg'] = $image;
								if($this->input->post('old_theme_image') != ''){
									$unlink_path = $upload_path.''.$this->input->post('old_theme_image');
									if(file_exists($unlink_path)){
										unlink($unlink_path);
									}
								}
							}else{
								$this->data['error'] = true;
								$this->data['message'] = $this->upload->display_errors();
								echo json_encode($this->data); 
								return false;
							}
						}else{
							$data['card_theme_bg'] = $this->input->post('old_theme_image') != ""?$this->input->post('old_theme_image'):$this->input->post('theme_color');
						}
					}else{
						if($this->input->post('old_theme_image') != ''){
							$unlink_path = $upload_path.''.$this->input->post('old_theme_image');
							if(file_exists($unlink_path)){
								unlink($unlink_path);
							}
						}
					}

					if($this->input->post('card_bg_type') == 'Image'){
						if (!empty($_FILES['card_image']['name'])){

							$image = time().'-'.str_replace(' ', '-', $_FILES["card_image"]['name']);
							$config['upload_path']          = $upload_path;
							$config['allowed_types']        = "gif|jpg|png|jpeg";
							$config['overwrite']             = false;
							$config['max_size']             = 0;
							$config['max_width']            = 0;
							$config['max_height']           = 0;
							$config['file_name']           = $image;
							$this->load->library('upload', $config);
							if($this->upload->do_upload('card_image')){
								$data['card_bg'] = $image;
								if($this->input->post('old_card_image') != ''){
									$unlink_path = $upload_path.''.$this->input->post('old_card_image');
									if(file_exists($unlink_path)){
										unlink($unlink_path);
									}
								}
							}else{
								$this->data['error'] = true;
								$this->data['message'] = $this->upload->display_errors();
								echo json_encode($this->data); 
								return false;
							}
						}else{
							$data['card_bg'] = $this->input->post('old_card_image') != ""?$this->input->post('old_card_image'):$this->input->post('card_image');
						}
					}else{
						if($this->input->post('old_card_image') != ''){
							$unlink_path = $upload_path.''.$this->input->post('old_card_image');
							if(file_exists($unlink_path)){
								unlink($unlink_path);
							}
						}
					}

				}
				
				if($this->input->post('changes_type') == 'profile'){
					
					if(slug_unique($this->input->post('slug'), $this->input->post('card_id'))){
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('slug_already_exists')?htmlspecialchars($this->lang->line('slug_already_exists')):'Slug already exists. Try another one.';
						echo json_encode($this->data); 
						return false;
					}

					if (!empty($_FILES['profile']['name'])){
						$upload_path = 'assets/uploads/card-profile/';
						if(!is_dir($upload_path)){
							mkdir($upload_path,0775,true);
						}
						$image = time().'-'.str_replace(' ', '-', $_FILES["profile"]['name']);
						$config['upload_path']          = $upload_path;
						$config['allowed_types']        = "gif|jpg|png|jpeg";
						$config['overwrite']             = false;
						$config['max_size']             = 0;
						$config['max_width']            = 0;
						$config['max_height']           = 0;
						$config['file_name']           = $image;
						$this->load->library('upload', $config);
						if($this->upload->do_upload('profile')){
							$data['profile'] = $image;
							if($this->input->post('old_profile') != ''){
								$unlink_path = $upload_path.''.$this->input->post('old_profile');
								if(file_exists($unlink_path)){
									unlink($unlink_path);
								}
							}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data); 
							return false;
						}
					}else{
						$data['profile'] = $this->input->post('old_profile') != ""?$this->input->post('old_profile'):'';
					}

					if (!empty($_FILES['banner']['name'])){
						$upload_path = 'assets/uploads/card-banner/';
						if(!is_dir($upload_path)){
							mkdir($upload_path,0775,true);
						}
						$image = time().'-'.str_replace(' ', '-', $_FILES["banner"]['name']);
						$config['upload_path']          = $upload_path;
						$config['allowed_types']        = "gif|jpg|png|jpeg";
						$config['overwrite']             = false;
						$config['max_size']             = 0;
						$config['max_width']            = 0;
						$config['max_height']           = 0;
						$config['file_name']           = $image;
						$this->load->library('upload', $config);
						if($this->upload->do_upload('banner')){
							$data['banner'] = $image;
							if($this->input->post('old_banner') != ''){
								$unlink_path = $upload_path.''.$this->input->post('old_banner');
								if(file_exists($unlink_path)){
									unlink($unlink_path);
								}
							}
						}else{
							$this->data['error'] = true;
							$this->data['message'] = $this->upload->display_errors();
							echo json_encode($this->data); 
							return false;
						}
					}else{
						$data['banner'] = $this->input->post('old_banner') != ""?$this->input->post('old_banner'):'';
					}

					if($this->input->post('create') == 'yes'){
						$data['user_id'] = $this->session->userdata('user_id');
						$data['saas_id'] = $this->session->userdata('saas_id');
					}

					$data['slug'] = $this->input->post('slug');
					$data['title'] = $this->input->post('title');
					$data['sub_title'] = $this->input->post('sub_title');
					$data['description'] = $this->input->post('short_description');
				}
				
				if($this->input->post('changes_type') == 'advanced'){
					$data['enquery_email'] = $this->input->post('enquery_email');
					$data['google_analytics'] = $this->input->post('google_analytics');
					$data['custom_css'] = $this->input->post('custom_css');
					$data['custom_js'] = $this->input->post('custom_js');
					$data['custom_js'] = $this->input->post('custom_js');
					$data['hide_branding'] = $this->input->post('hide_branding')?1:0;
					$data['show_add_to_phone_book'] = $this->input->post('show_add_to_phone_book')?1:0;
					$data['show_share'] = $this->input->post('show_share')?1:0;
					$data['show_qr_on_card'] = $this->input->post('show_qr_on_card')?1:0;
					$data['show_qr_on_share_popup'] = $this->input->post('show_qr_on_share_popup')?1:0;
					$data['show_change_language_option_on_a_card'] = $this->input->post('show_change_language_option_on_a_card')?1:0;
					$data['show_card_view_count_on_a_card'] = $this->input->post('show_card_view_count_on_a_card')?1:0;
					$data['search_engine_indexing'] = $this->input->post('search_engine_indexing')?1:0;
					$data['make_setions_conetnt_carousel'] = $this->input->post('make_setions_conetnt_carousel')?1:0;
				}
				
				
				if($this->input->post('changes_type') == 'custom_domain'){

					if($this->input->post('custom_domain') == filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING)){
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('invalid_domain_name')?htmlspecialchars($this->lang->line('invalid_domain_name')):"Invalid domain name.";
						echo json_encode($this->data);
						return false;
					}

					$data_custom_domain = false;
					$card_id = $this->input->post('card_id');
					$custom_domain = $this->input->post('custom_domain');
					if($custom_domain != ''){
						$query = $this->db->query("SELECT id FROM cards WHERE id != $card_id AND custom_domain = '$custom_domain' ");
						$data_custom_domain = $query->result_array();
					}

					if($data_custom_domain){
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('the_domain_is_already_associated_with_another_card')?htmlspecialchars($this->lang->line('the_domain_is_already_associated_with_another_card')):"The domain is already associated with another card.";
						echo json_encode($this->data);
						return false;
					}

					$data['custom_domain'] = $this->input->post('custom_domain');

					if($this->input->post('custom_domain_old') != '' && $this->input->post('custom_domain') != $this->input->post('custom_domain_old')){
						$data['custom_domain_status'] = 0;
					}

					if($this->ion_auth->in_group(3)){
						$data['custom_domain_status'] = $this->input->post('custom_domain_status');

						$query = $this->db->query("SELECT id,user_id,custom_domain_status FROM cards WHERE id = $card_id");
						$cdata = $query->result_array();
						if($cdata){
							if($this->input->post('custom_domain_status') != $cdata[0]['custom_domain_status']){
								$ndata = array(
									'notification' => '<span class="text-info">'.$this->input->post('custom_domain').'</span>',
									'type' => 'new_domain_status',	
									'type_id' => $this->input->post('card_id'),	
									'from_id' => $this->session->userdata('saas_id'),
									'to_id' => $cdata[0]['user_id'],	
								);
								$notification_id = $this->notifications_model->create($ndata);
							}
						}
					}

					$data['custom_domain_redirect'] = $this->input->post('custom_domain_redirect')?1:0;

					if(!$this->ion_auth->in_group(3)){
					// notification to the saas admins
						$saas_admins = $this->ion_auth->users(array(3))->result();
						foreach($saas_admins as $saas_admin){
							$ndata = array(
								'notification' => '<span class="text-info">'.$this->input->post('custom_domain').'</span>',
								'type' => 'new_domain',	
								'type_id' => $this->input->post('card_id'),	
								'from_id' => $this->session->userdata('saas_id'),
								'to_id' => $saas_admin->user_id,	
							);
							$notification_id = $this->notifications_model->create($ndata);
						}
					}

				}

				if(isset($cdata) && isset($cdata[0]['user_id'])){
					$update_user_id = $cdata[0]['user_id'];
				}else{
					if($this->ion_auth->is_admin()){
						$query = $this->db->query("SELECT id,user_id,custom_domain_status FROM cards WHERE id = ".$this->input->post('card_id'));
						$card_user = $query->result_array();
						if(isset($card_user) && isset($card_user[0]['user_id'])){
							$update_user_id = $card_user[0]['user_id'];
						}else{
							$update_user_id = $this->session->userdata('user_id');
						}
					}else{
						$update_user_id = $this->session->userdata('user_id');
					}
				}

				if($this->cards_model->save($this->input->post('card_id'), $update_user_id, $data)){
					$this->session->set_flashdata('message', $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function products()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('products_services'))
		{
			$this->data['page_title'] = 'Products and Services - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-products',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function create_product()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('price', 'price', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('url', 'url', 'trim|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				if (!empty($_FILES['image']['name'])){
					$upload_path = 'assets/uploads/product-image/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = "gif|jpg|png|jpeg";
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$config['file_name']           = $image;
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image')){
						$data['image'] = $image;
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = $this->input->post('title');
				$data['price'] = $this->input->post('price');
				$data['description'] = $this->input->post('description');
				
				if($this->input->post('url') == 'custom'){
					$data['url'] = $this->input->post('custom_url');
				}else{
					$data['url'] = $this->input->post('url');
				}
				
				if($this->cards_model->create_product($data)){
					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}


	public function edit_product()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('update_id', 'id', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('price', 'price', 'trim|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('url', 'url', 'trim|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				if (!empty($_FILES['image']['name'])){
					$upload_path = 'assets/uploads/product-image/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = "gif|jpg|png|jpeg";
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$config['file_name']           = $image;
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image')){
						$data['image'] = $image;
						if($this->input->post('old_image') != ''){
							$unlink_path = $upload_path.''.$this->input->post('old_image');
							if(file_exists($unlink_path)){
								unlink($unlink_path);
							}
						}
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}else{
					$data['image'] = $this->input->post('old_image') != ""?$this->input->post('old_image'):'';
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = $this->input->post('title');
				$data['price'] = $this->input->post('price');
				$data['description'] = $this->input->post('description');

				if($this->input->post('url') == 'custom'){
					$data['url'] = $this->input->post('custom_url');
				}else{
					$data['url'] = $this->input->post('url');
				}
				
				if($this->cards_model->edit_product($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	
	public function get_products($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
            $products = $this->cards_model->get_products($id);
			$temp = array();
			if($products){
				foreach($products as $key => $product){
					$temp[$key] = $product;

					if($product['url'] != ''){
						$temp[$key]['url'] = '<a href="'.$product['url'].'" target="_blank">'.$product['url'].'</a>';
					}

					if($product['image'] != ''){
						$temp[$key]['image'] = '<a href="'.base_url('assets/uploads/product-image/'.$product['image']).'" target="_blank"><img style="width: 49px;" alt="image" src="'.base_url('assets/uploads/product-image/'.$product['image']).'"></a>';
					}

					$temp[$key]['action'] = '<span class="d-flex">
						<a href="#" class="btn btn-icon btn-sm btn-success modal-edit-product mr-1" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>
						
						<a href="#" class="btn btn-icon btn-sm btn-danger delete_product" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';	
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function delete_product($id='')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			if(!empty($id) && is_numeric($id) && $this->cards_model->delete_product($id)){

				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function ajax_get_product_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$products = $this->cards_model->get_products($id);
			if(!empty($products)){
				$this->data['error'] = false;
				$this->data['data'] = $products;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'Nothing found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function gallery()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('gallery'))
		{
			$this->data['page_title'] = 'Gallery - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-gallery',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	
	
	public function create_gallery()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			// $this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('content_type', 'content type', 'trim|required|strip_tags|xss_clean');

			if($this->input->post('content_type') != 'upload'){
				$this->form_validation->set_rules('url', 'url', 'trim|required|strip_tags|xss_clean');
			}

			if($this->input->post('content_type') == 'upload' && empty($_FILES['image']['name'])){
				$this->form_validation->set_rules('image', 'image', 'trim|required|strip_tags|xss_clean');
			}
			
			if($this->form_validation->run() == TRUE){
				$data = array();
				
				if ($this->input->post('content_type') == 'upload' && !empty($_FILES['image']['name'])){
					$upload_path = 'assets/uploads/product-image/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = "gif|jpg|png|jpeg";
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$config['file_name']           = $image;
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image')){
						$data['url'] = $image;
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = 'gallery';
				$data['content_type'] = $this->input->post('content_type');
				
				if(!isset($data['url'])){
					$data['url'] = $this->input->post('url');
				}
				
				if($this->input->post('content_type') == 'vimeo' && $data['url'] != ''){
					if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $this->input->post('url'), $output_array)) {
						$data['url'] = 'https://player.vimeo.com/video/'.$output_array[5];
					}
				}

				if($this->cards_model->create_gallery($data)){
					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function edit_gallery()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('update_id', 'id', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			// $this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('content_type', 'content type', 'trim|required|strip_tags|xss_clean');

			if($this->input->post('content_type') != 'upload'){
				$this->form_validation->set_rules('url', 'url', 'trim|required|strip_tags|xss_clean');
			}

			if($this->input->post('content_type') == 'upload' && $this->input->post('old_image') == "" && empty($_FILES['image']['name'])){
				$this->form_validation->set_rules('image', 'image', 'trim|required|strip_tags|xss_clean');
			}

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				if ($this->input->post('content_type') == 'upload' && !empty($_FILES['image']['name'])){
					$upload_path = 'assets/uploads/product-image/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = "gif|jpg|png|jpeg";
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$config['file_name']           = $image;
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image')){
						$data['url'] = $image;
						if($this->input->post('old_image') != ''){
							$unlink_path = $upload_path.''.$this->input->post('old_image');
							if(file_exists($unlink_path)){
								unlink($unlink_path);
							}
						}
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}else{
					$data['url'] = $this->input->post('old_image') != ""?$this->input->post('old_image'):'';
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = 'gallery';
				$data['content_type'] = $this->input->post('content_type');
				
				if(isset($data['url']) && $data['url'] == ''){
					$data['url'] = $this->input->post('url');
				}
				
				if($this->input->post('content_type') == 'vimeo' && $data['url'] != ''){
					if(preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $this->input->post('url'), $output_array)) {
						$data['url'] = 'https://player.vimeo.com/video/'.$output_array[5];
					}
				}

				if($this->cards_model->edit_gallery($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function get_gallery($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
            $products = $this->cards_model->get_gallery($id);
			$temp = array();
			if($products){
				foreach($products as $key => $product){
					$temp[$key] = $product;

					$temp[$key]['url'] = '<a href="'.$product['url'].'" target="_blank">'.$product['url'].'</a>';

					if($product['url'] != '' && $product['content_type'] == 'upload'){

						$temp[$key]['preview'] = '<a href="'.base_url('assets/uploads/product-image/'.$product['url']).'" target="_blank"><img style="width: 49px;" alt="image" src="'.base_url('assets/uploads/product-image/'.$product['url']).'"></a>';

						$temp[$key]['content_type'] = $this->lang->line('upload_image')?htmlspecialchars($this->lang->line('upload_image')):'Upload Image';

						$temp[$key]['url'] = '<a href="'.base_url('assets/uploads/product-image/'.$product['url']).'" target="_blank">'.base_url('assets/uploads/product-image/'.$product['url']).'</a>';

					}elseif($product['content_type'] == 'youtube'){
						$temp[$key]['preview'] = '<a href="'.$product['url'].'" target="_blank"><img style="width: 49px;" alt="image" src="'.get_video_thumbnail('youtube', $product['url']).'"></a>';
						$temp[$key]['content_type'] = $this->lang->line('youtube')?htmlspecialchars($this->lang->line('youtube')):'YouTube';
					}elseif($product['content_type'] == 'vimeo'){
						$temp[$key]['preview'] = '<a href="'.$product['url'].'" target="_blank"><img style="width: 49px;" alt="image" src="'.get_video_thumbnail('vimeo', $product['url']).'"></a>';
						$temp[$key]['content_type'] = $this->lang->line('vimeo')?htmlspecialchars($this->lang->line('vimeo')):'Vimeo';
					}else{
						$temp[$key]['preview'] = '<a href="'.$product['url'].'" target="_blank"><img style="width: 49px;" alt="image" src="'.$product['url'].'"></a>';
						$temp[$key]['content_type'] = $this->lang->line('custom_image_url')?htmlspecialchars($this->lang->line('custom_image_url')):'Custom Image URL';
					}

					$temp[$key]['action'] = '<span class="d-flex">
						<a href="#" class="btn btn-icon btn-sm btn-success modal-edit-gallery mr-1" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>
						
						<a href="#" class="btn btn-icon btn-sm btn-danger delete_gallery" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';	
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function delete_gallery($id='')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			if(!empty($id) && is_numeric($id) && $this->cards_model->delete_gallery($id)){

				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}	
	
	public function ajax_get_gallery_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$gallery = $this->cards_model->get_gallery($id);
			if(!empty($gallery)){
				$this->data['error'] = false;
				$this->data['data'] = $gallery;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'Nothing found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function custom_sections()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('custom_sections'))
		{
			$this->data['page_title'] = 'Custom Sections - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();			
						
			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-custom-sections',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	
	public function edit_custom_section()
	{
		if ($this->ion_auth->logged_in() && $this->uri->segment(3) && is_numeric($this->uri->segment(3)))
		{
			
			$this->data['page_title'] = 'Edit Custom Section - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
				
			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$custom_section = $this->cards_model->get_custom_sections($this->uri->segment(3),$this->session->userdata('user_id'), $this->session->userdata('current_card_id'));

			if($custom_section){
				$this->data['custom_section'] = $custom_section;
			}else{
				redirect('cards/custom-sections', 'refresh');
			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();

			$this->load->view('edit-custom-section',$this->data);
		}else{
            redirect('cards/custom-sections', 'refresh');
		}
	}

	public function create_custom_section()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('custom_sections'))
		{
			$this->data['page_title'] = 'Create Custom Section - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
									
			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();

			$this->load->view('create-custom-section',$this->data);
		}else{
            redirect('auth', 'refresh');
		}
	}

	public function create_custom_sections()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('content', 'content', 'required');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = $this->input->post('title');
				$data['content'] = $this->input->post('content');

				if($this->cards_model->create_custom_sections($data)){
					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}


	public function edit_custom_sections()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('update_id', 'id', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('content', 'content', 'required');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = $this->input->post('title');
				$data['content'] = $this->input->post('content');
				
				if($this->cards_model->edit_custom_sections($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	
	public function get_custom_sections($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
            $products = $this->cards_model->get_custom_sections($id);
			$temp = array();
			if($products){
				foreach($products as $key => $product){
					$temp[$key] = $product;

					$temp[$key]['action'] = '<span class="d-flex">
						<a href="'.base_url('cards/edit-custom-section/'.$product['id']).'" class="btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>
						
						<a href="#" class="btn btn-icon btn-sm btn-danger delete_custom_sections" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';	
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function delete_custom_sections($id='')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			if(!empty($id) && is_numeric($id) && $this->cards_model->delete_custom_sections($id)){

				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function ajax_get_custom_sections_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$custom_sections = $this->cards_model->get_custom_sections($id);
			if(!empty($custom_sections)){
				$this->data['error'] = false;
				$this->data['data'] = $custom_sections;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'Nothing found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function portfolio()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('portfolio'))
		{
			$this->data['page_title'] = 'Portfolio - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();			
						
			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-portfolio',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function create_portfolio()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('url', 'url', 'trim|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				if (!empty($_FILES['image']['name'])){
					$upload_path = 'assets/uploads/product-image/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = "gif|jpg|png|jpeg";
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$config['file_name']           = $image;
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image')){
						$data['image'] = $image;
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = $this->input->post('title');
				$data['description'] = $this->input->post('description');
				
				if($this->input->post('url') == 'custom'){
					$data['url'] = $this->input->post('custom_url');
				}else{
					$data['url'] = $this->input->post('url');
				}
				
				if($this->cards_model->create_portfolio($data)){
					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}


	public function edit_portfolio()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('update_id', 'id', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('url', 'url', 'trim|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				if (!empty($_FILES['image']['name'])){
					$upload_path = 'assets/uploads/product-image/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = "gif|jpg|png|jpeg";
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$config['file_name']           = $image;
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image')){
						$data['image'] = $image;
						if($this->input->post('old_image') != ''){
							$unlink_path = $upload_path.''.$this->input->post('old_image');
							if(file_exists($unlink_path)){
								unlink($unlink_path);
							}
						}
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}else{
					$data['image'] = $this->input->post('old_image') != ""?$this->input->post('old_image'):'';
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = $this->input->post('title');
				$data['description'] = $this->input->post('description');

				if($this->input->post('url') == 'custom'){
					$data['url'] = $this->input->post('custom_url');
				}else{
					$data['url'] = $this->input->post('url');
				}
				
				if($this->cards_model->edit_portfolio($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	
	public function get_portfolio($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
            $products = $this->cards_model->get_portfolio($id);
			$temp = array();
			if($products){
				foreach($products as $key => $product){
					$temp[$key] = $product;

					if($product['url'] != ''){
						$temp[$key]['url'] = '<a href="'.$product['url'].'" target="_blank">'.$product['url'].'</a>';
					}

					if($product['image'] != ''){
						$temp[$key]['image'] = '<a href="'.base_url('assets/uploads/product-image/'.$product['image']).'" target="_blank"><img style="width: 49px;" alt="image" src="'.base_url('assets/uploads/product-image/'.$product['image']).'"></a>';
					}

					$temp[$key]['action'] = '<span class="d-flex">
						<a href="#" class="btn btn-icon btn-sm btn-success modal-edit-portfolio mr-1" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>
						
						<a href="#" class="btn btn-icon btn-sm btn-danger delete_portfolio" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';	
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function delete_portfolio($id='')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			if(!empty($id) && is_numeric($id) && $this->cards_model->delete_portfolio($id)){

				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function ajax_get_portfolio_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$portfolio = $this->cards_model->get_portfolio($id);
			if(!empty($portfolio)){
				$this->data['error'] = false;
				$this->data['data'] = $portfolio;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'Nothing found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	
	public function testimonials()
	{
		if ($this->ion_auth->logged_in() && is_module_allowed('testimonials'))
		{
			$this->data['page_title'] = 'Testimonials - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();			
			
			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-testimonials',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function create_testimonials()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('rating', 'rating', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				if (!empty($_FILES['image']['name'])){
					$upload_path = 'assets/uploads/product-image/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = "gif|jpg|png|jpeg";
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$config['file_name']           = $image;
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image')){
						$data['image'] = $image;
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = $this->input->post('title');
				$data['description'] = $this->input->post('description');
				$data['rating'] = $this->input->post('rating');
				
				
				if($this->cards_model->create_testimonials($data)){
					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}


	public function edit_testimonials()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('update_id', 'id', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('description', 'description', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('rating', 'rating', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				if (!empty($_FILES['image']['name'])){
					$upload_path = 'assets/uploads/product-image/';
					if(!is_dir($upload_path)){
						mkdir($upload_path,0775,true);
					}
					$image = time().'-'.str_replace(' ', '-', $_FILES["image"]['name']);
					$config['upload_path']          = $upload_path;
					$config['allowed_types']        = "gif|jpg|png|jpeg";
					$config['overwrite']             = false;
					$config['max_size']             = 0;
					$config['max_width']            = 0;
					$config['max_height']           = 0;
					$config['file_name']           = $image;
					$this->load->library('upload', $config);
					if($this->upload->do_upload('image')){
						$data['image'] = $image;
						if($this->input->post('old_image') != ''){
							$unlink_path = $upload_path.''.$this->input->post('old_image');
							if(file_exists($unlink_path)){
								unlink($unlink_path);
							}
						}
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->upload->display_errors();
						echo json_encode($this->data); 
						return false;
					}
				}else{
					$data['image'] = $this->input->post('old_image') != ""?$this->input->post('old_image'):'';
				}

				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['title'] = $this->input->post('title');
				$data['description'] = $this->input->post('description');
				$data['rating'] = $this->input->post('rating');

				if($this->cards_model->edit_testimonials($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function get_testimonials($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
            $products = $this->cards_model->get_testimonials($id);
			$temp = array();
			if($products){
				foreach($products as $key => $product){
					$temp[$key] = $product;

					if($product['image'] != ''){
						$temp[$key]['image'] = '<a href="'.base_url('assets/uploads/product-image/'.$product['image']).'" target="_blank"><img style="width: 49px;" alt="image" src="'.base_url('assets/uploads/product-image/'.$product['image']).'"></a>';
					}

					$temp[$key]['rating'] = '<i class="'.($product['rating']>=1?'fas':'far').' fa-star"></i>
					<i class="'.($product['rating']>=2?'fas':'far').' fa-star"></i>
					<i class="'.($product['rating']>=3?'fas':'far').' fa-star"></i>
					<i class="'.($product['rating']>=4?'fas':'far').' fa-star"></i>
					<i class="'.($product['rating']>=5?'fas':'far').' fa-star"></i>';

					$temp[$key]['action'] = '<span class="d-flex">
						<a href="#" class="btn btn-icon btn-sm btn-success modal-edit-testimonials mr-1" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>
						
						<a href="#" class="btn btn-icon btn-sm btn-danger delete_testimonials" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';	
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function ajax_get_testimonials_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$testimonials = $this->cards_model->get_testimonials($id);
			if(!empty($testimonials)){
				$this->data['error'] = false;
				$this->data['data'] = $testimonials;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'Nothing found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function delete_testimonials($id='')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			if(!empty($id) && is_numeric($id) && $this->cards_model->delete_testimonials($id)){
				
				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	public function send_mail()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|strip_tags|xss_clean|valid_email');
		$this->form_validation->set_rules('mobile', 'Mobile', 'trim|strip_tags|xss_clean');
		$this->form_validation->set_rules('msg', 'Message', 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('user_email', 'card email', 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('user_email', 'card email', 'trim|required|strip_tags|xss_clean');
		$this->form_validation->set_rules('card_name', '', 'trim|xss_clean');
		$this->form_validation->set_rules('card_url', '', 'trim|xss_clean');

		if($this->form_validation->run() == TRUE){
			try{

				
				$template_data = array();
				$template_data['NAME'] = $this->input->post('name');
				$template_data['EMAIL'] = $this->input->post('email');
				$template_data['MOBILE'] = $this->input->post('mobile');
				$template_data['MESSAGE'] = $this->input->post('msg');
				$template_data['CARD_EMAIL'] = $this->input->post('user_email');
				$template_data['CARD_NAME'] = $this->input->post('card_name');
				$template_data['CARD_URL'] = $this->input->post('card_url');
				$email_template = render_email_template('card_enquiry_form', $template_data);
				send_mail($this->input->post('user_email'), $email_template[0]['subject'], $email_template[0]['message']);

			}catch(Exception $e){

			}
			
			$this->data['error'] = false;
			$this->data['message'] = $this->lang->line('we_will_get_back_to_you_soon')?$this->lang->line('we_will_get_back_to_you_soon'):"We will get back to you soon.";
			echo json_encode($this->data); 
			return false;
		}else{
			$this->data['error'] = true;
			$this->data['message'] = validation_errors();
			echo json_encode($this->data); 
			return false;
		}
	}




		
	public function create_custom_fields()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('type', 'field type', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('icon', 'all', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'all', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('url', 'all', 'trim|required|strip_tags|xss_clean');
			
			if($this->form_validation->run() == TRUE){
				$data = array();
				
				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['type'] = $this->input->post('type');
				$data['icon'] = $this->input->post('icon');
				$data['title'] = $this->input->post('title');
				$data['url'] = $this->input->post('url');
				
				if($this->cards_model->create_custom_fields($data)){
					$this->session->set_flashdata('message', $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('created_successfully')?$this->lang->line('created_successfully'):"Created successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function edit_custom_fields()
	{
		if ($this->ion_auth->logged_in())
		{
			
			$this->form_validation->set_rules('update_id', 'ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('card_id', 'card ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('type', 'field type', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('icon', 'all', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('title', 'all', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('url', 'all', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				$data = array();
				
				$data['saas_id'] = $this->session->userdata('saas_id');
				$data['user_id'] = $this->session->userdata('user_id');
				$data['card_id'] = $this->input->post('card_id');
				$data['type'] = $this->input->post('type');
				$data['icon'] = $this->input->post('icon');
				$data['title'] = $this->input->post('title');
				$data['url'] = $this->input->post('url');

				if($this->cards_model->edit_custom_fields($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('changes_successfully_saved')?$this->lang->line('changes_successfully_saved'):"Changes successfully saved.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = validation_errors();
				echo json_encode($this->data); 
			}

		}else{
			
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
		
	}

	public function get_custom_fields($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
            $products = $this->cards_model->get_custom_fields($id);
			
			$temp = array();

			if($products){
				foreach($products as $key => $product){
					$temp[$key] = $product;
					$temp[$key]['icon'] = (isset($product['icon']) && !empty($product['icon']))?'<i class="'.$product['icon'].'"></i>':'';

					$type = $product['type'];

					if($type == 'mobile'){
					$temp[$key]['type'] = ($this->lang->line('mobile')?htmlspecialchars($this->lang->line('mobile')):'Mobile').' / '.($this->lang->line('phone')?htmlspecialchars($this->lang->line('phone')):'Phone');
					}elseif($type == 'email'){
					$temp[$key]['type'] = $this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email';
					}elseif($type == 'address'){
					$temp[$key]['type'] = $this->lang->line('address')?htmlspecialchars($this->lang->line('address')):'Address';
					}elseif($type == 'whatsapp'){
					$temp[$key]['type'] = 'WhatsApp';
					}elseif($type == 'linkedin'){
					$temp[$key]['type'] = 'LinkedIn';
					}elseif($type == 'website'){
					$temp[$key]['type'] = $this->lang->line('website')?htmlspecialchars($this->lang->line('website')):'Website';
					}elseif($type == 'facebook'){
					$temp[$key]['type'] = 'Facebook';
					}elseif($type == 'twitter'){
					$temp[$key]['type'] = 'Twitter';
					}elseif($type == 'instagram'){
					$temp[$key]['type'] = 'Instagram';
					}elseif($type == 'telegram'){
					$temp[$key]['type'] = 'Telegram';
					}elseif($type == 'skype'){
					$temp[$key]['type'] = 'Skype';
					}elseif($type == 'youtube'){
					$temp[$key]['type'] = 'YouTube';
					}elseif($type == 'tiktok'){
					$temp[$key]['type'] = 'TikTok';
					}elseif($type == 'snapchat'){
					$temp[$key]['type'] = 'Snapchat';
					}elseif($type == 'paypal'){
					$temp[$key]['type'] = 'Paypal';
					}elseif($type == 'github'){
					$temp[$key]['type'] = 'Github';
					}elseif($type == 'pinterest'){
					$temp[$key]['type'] = 'Pinterest';
					}elseif($type == 'wechat'){
					$temp[$key]['type'] = 'WeChat';
					}elseif($type == 'signal'){
					$temp[$key]['type'] = 'Signal';
					}elseif($type == 'discord'){
					$temp[$key]['type'] = 'Discord';
					}elseif($type == 'reddit'){
					$temp[$key]['type'] = 'Reddit';
					}elseif($type == 'spotify'){
					$temp[$key]['type'] = 'Spotify';
					}elseif($type == 'vimeo'){
					$temp[$key]['type'] = 'Vimeo';
					}elseif($type == 'soundcloud'){
					$temp[$key]['type'] = 'Soundcloud';
					}elseif($type == 'dribbble'){
					$temp[$key]['type'] = 'Dribbble';
					}elseif($type == 'behance'){
					$temp[$key]['type'] = 'Behance';
					}elseif($type == 'flickr'){
					$temp[$key]['type'] = 'Flickr';
					}elseif($type == 'twitch'){
					$temp[$key]['type'] = 'Twitch';
					}else{
					$temp[$key]['type'] = $this->lang->line('custom_url')?htmlspecialchars($this->lang->line('custom_url')):'Custom URL';
					}

					$temp[$key]['action'] = '<span class="d-flex">
						<a href="#" class="btn btn-icon btn-sm btn-success modal-edit-contact_details mr-1" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a>
						
						<a href="#" class="btn btn-icon btn-sm btn-danger delete_contact_details" data-id="'.$product["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';	
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function delete_custom_fields($id='')
	{
		if ($this->ion_auth->logged_in())
		{
			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}

			if(!empty($id) && is_numeric($id) && $this->cards_model->delete_custom_fields($id)){

				$this->session->set_flashdata('message', $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('deleted_successfully')?$this->lang->line('deleted_successfully'):"Deleted successfully.";
				echo json_encode($this->data);
			}else{
				
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}

		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}	
	
	public function ajax_get_custom_fields_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$custom_fields = $this->cards_model->get_custom_fields($id);
			if(!empty($custom_fields)){
				$this->data['error'] = false;
				$this->data['data'] = $custom_fields;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'Nothing found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

	
	public function reorder_sections()
	{
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Reorder sections - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();

			if($this->uri->segment(3) && is_numeric($this->uri->segment(3)) && !$this->ion_auth->in_group(3)){
				$this->session->set_userdata('current_card_id', $this->uri->segment(3));
			}

			$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			if(!$card_data){
				$this->session->set_userdata('current_card_id', '');
				$this->data['card'] = $card_data = $this->cards_model->get_card_by_ids($this->session->userdata('current_card_id'), $this->session->userdata('user_id'));

			}
			

			if(isset($card_data['reorder_sections']) && empty($card_data['reorder_sections'])){ 
				$this->data['card']['reorder_sections'] = json_encode(array('main_card_section', 'products_services', 'portfolio', 'gallery', 'testimonials', 'qr_code', 'enquiry_form', 'custom_sections'));
			}else{
				$reorder_sections = json_decode($card_data['reorder_sections']);
				if(!in_array('main_card_section', $reorder_sections)){
					array_push($reorder_sections, 'main_card_section');
				}elseif(!in_array('products_services', $reorder_sections)){
					array_push($reorder_sections, 'products_services');
				}elseif(!in_array('portfolio', $reorder_sections)){
					array_push($reorder_sections, 'portfolio');
				}elseif(!in_array('gallery', $reorder_sections)){
					array_push($reorder_sections, 'gallery');
				}elseif(!in_array('testimonials', $reorder_sections)){
					array_push($reorder_sections, 'testimonials');
				}elseif(!in_array('qr_code', $reorder_sections)){
					array_push($reorder_sections, 'qr_code');
				}elseif(!in_array('enquiry_form', $reorder_sections)){
					array_push($reorder_sections, 'enquiry_form');
				}elseif(!in_array('custom_sections', $reorder_sections)){
					array_push($reorder_sections, 'custom_sections');
				}
				$this->data['card']['reorder_sections'] = json_encode($reorder_sections);
			}

			$this->data['my_all_cards'] = $this->cards_model->get_my_all_cards();
			
            $this->load->view('card-reorder-sections',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function order_gallery()
	{
		
		if ($this->ion_auth->logged_in())
		{
			foreach(json_decode($_POST['data']) as $key => $fields){
				$data = array(
					'order_by_id' => $key
				);
				$this->cards_model->edit_gallery($fields,$data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function order_testimonials()
	{
		
		if ($this->ion_auth->logged_in())
		{
			foreach(json_decode($_POST['data']) as $key => $fields){
				$data = array(
					'order_by_id' => $key
				);
				$this->cards_model->edit_testimonials($fields,$data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function order_portfolio()
	{
		
		if ($this->ion_auth->logged_in())
		{
			foreach(json_decode($_POST['data']) as $key => $fields){
				$data = array(
					'order_by_id' => $key
				);
				$this->cards_model->edit_portfolio($fields,$data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function order_products()
	{
		
		if ($this->ion_auth->logged_in())
		{
			foreach(json_decode($_POST['data']) as $key => $fields){
				$data = array(
					'order_by_id' => $key
				);
				$this->cards_model->edit_product($fields,$data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function order_custom_fields()
	{
		if ($this->ion_auth->logged_in())
		{
			foreach(json_decode($_POST['data']) as $key => $fields){
				$data = array(
					'order_by_id' => $key
				);
				$this->cards_model->edit_custom_fields($fields->id,$data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}

	public function order_custom_section()
	{
		if ($this->ion_auth->logged_in())
		{
			foreach(json_decode($_POST['data']) as $key => $fields){
				$data = array(
					'order_by_id' => $key
				);
				$this->cards_model->edit_custom_sections($fields,$data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data); 
		}
	}


}
