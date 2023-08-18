<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function get_tax($tax_id = '')
{
    $CI =& get_instance();
    $CI->db->from('taxes');
    $CI->db->where(['saas_id'=>$CI->session->userdata('saas_id')]);
    if($tax_id){
        $CI->db->where(['id'=>$tax_id]);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return array();
    }
} 

function check_for_custom_domain(){
    
    $CI =& get_instance();
    
    $domain = filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING);
    
    $CI->db->from('cards');
    $CI->db->where(['custom_domain'=>$domain]);

    $query = $CI->db->get();
    $data = $query->row_array();
        
    if($data){
        
        if($data['custom_domain_status'] == 1) {

            $CI->data['current_user'] = $CI->ion_auth->user()->row();
            $card = $data;
            if($card){

                $CI->data['card_plan_details'] = $my_plan = get_current_plan($card['saas_id']);

                if($my_plan){
                    $CI->data['card_plan_modules'] = $card_plan_modules = json_decode($my_plan['modules'], true);
                }else{
                    $CI->data['card_plan_modules'] = $card_plan_modules = false;
                }

                if ($my_plan && !is_null($my_plan['end_date']) && $my_plan['end_date'] < date('Y-m-d') && $my_plan['expired'] == 1)
                {
                $users_plans_data = array(
                    'expired' => 0,			
                );
                $users_plans_id = $CI->plans_model->update_users_plans($card['user_id'], $users_plans_data);
                show_404();
                }
                if($my_plan && !is_null($my_plan['end_date']) && $my_plan['expired'] == 0){ 
                    show_404();
                }

                $CI->data['card'] = $card;
                $CI->data['page_title'] = $card['title'];
                $CI->data['meta_image'] = ($card['profile'] != '' && file_exists('assets/uploads/card-profile/'.$card['profile']))?base_url('assets/uploads/card-profile/'.$card['profile']):base_url('assets/uploads/logos/'.half_logo());
                $CI->data['banner'] = ($card['banner'] != '' && file_exists('assets/uploads/card-banner/'.$card['banner']))?base_url('assets/uploads/card-banner/'.$card['banner']):'';
                $CI->data['meta_description'] = $card['description'];
                $CI->data['google_analytics'] = $card['google_analytics'];

                $CI->data['products'] = $CI->cards_model->get_products('', $card['user_id'], $card['id']);

                $CI->data['portfolio'] = $CI->cards_model->get_portfolio('', $card['user_id'], $card['id']);

                $CI->data['gallery'] = $gallery = $CI->cards_model->get_gallery('', $card['user_id'], $card['id']);

                if($gallery){
                    foreach($gallery as $key => $gal){ 
                        if($gal['content_type'] == 'youtube' && $gal['url'] != ''){
                            $CI->data['gallery'][$key]['thumb'] = get_video_thumbnail('youtube', $gal['url']);
                        }elseif($gal['content_type'] == 'vimeo' && $gal['url'] != ''){
                            $CI->data['gallery'][$key]['thumb'] = get_video_thumbnail('vimeo', $gal['url']);
                        }else{
                            $CI->data['gallery'][$key]['thumb'] = $gal['url'];
                        }
                    }
                }

                $CI->data['testimonials'] = $CI->cards_model->get_testimonials('', $card['user_id'], $card['id']);

                $CI->data['custom_sections'] = $CI->cards_model->get_custom_sections('', $card['user_id'], $card['id']);

                $CI->data['custom_fields'] = $CI->cards_model->get_custom_fields('', $card['user_id'], $card['id']);
                
                if(isset($card_plan_modules) && isset($card_plan_modules['ads']) && $card_plan_modules['ads'] != 1){ 
                    $CI->data['ads_header_code'] = get_ads_data('header_code');
                    $CI->data['ads_footer_code'] = get_ads_data('footer_code');
                    $CI->data['ad_area'] = get_ads_data('ad_area');
                    $CI->data['ad_code'] = get_ads_data('ad_code');
                }else{
                    $CI->data['ads_header_code'] = '';
                    $CI->data['ads_footer_code'] = '';
                    $CI->data['ad_area'] = '';
                    $CI->data['ad_code'] = '';
                }
                
                
                $CI->data['card']['qr_code_options'] = !empty($card['qr_code_options'])?json_decode($card['qr_code_options'], true):'';

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
                    $CI->data['card']['reorder_sections'] = json_encode($reorder_sections);
                }else{
                    $CI->data['card']['reorder_sections'] = json_encode(array('main_card_section', 'products_services', 'portfolio', 'gallery', 'testimonials', 'qr_code', 'enquiry_form', 'custom_sections'));
                }
                
                if($CI->session->userdata('visited') == '' || $CI->session->userdata('visited') != $card['id']){
                    $CI->session->set_userdata('visited', $card['id']);
                    $data['views'] = 1 + $card['views'];
                    $CI->cards_model->save($card['id'], $card['user_id'], $data);
                }
                
                if($CI->uri->segment(2) && ($CI->uri->segment(2) == 'theme_one' || $CI->uri->segment(2) == 'theme_two' || $CI->uri->segment(2) == 'theme_three' || $CI->uri->segment(2) == 'theme_four' || $CI->uri->segment(2) == 'theme_five' || $CI->uri->segment(2) == 'theme_six' || $CI->uri->segment(2) == 'theme_seven' || $CI->uri->segment(2) == 'theme_eight')){
                    $CI->load->view('cards/'.$CI->uri->segment(2),$CI->data);
                }else{
                    
                    if(isset($card['theme_name']) && $card['theme_name'] != ''){
                        $CI->load->view('cards/'.$card['theme_name'],$CI->data);
                    }else{
                        $CI->load->view('cards/theme_one',$CI->data);
                    }
                }
            }else{
                show_404();
            }
        }
        return true;
    }      
}

function get_video_thumbnail($type, $url){

    switch ($type) {
        case 'youtube':
            $video_id = false;
            $regex = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i';
            if (preg_match($regex, $url, $match)) {
                $video_id = $match[1];
            }

            if($video_id){
                $image_url = "https://img.youtube.com/vi/" . $video_id . "/0.jpg";
            }else{
                $image_url = base_url('assets/img/video-thumbnail.png');
            }

            break;
        
        case 'vimeo':
            $video_id = false;
            $regex = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/';
            if (preg_match($regex, $url, $match)) {
                $video_id = $match[5];
            }

            if($video_id){            
                $video_data = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . $video_id . ".php"));
                $image_url = $video_data[0]['thumbnail_medium'];
            }else{
                $image_url = base_url('assets/img/video-thumbnail.png');
            }
            
            break;
        
        default:
            $image_url = base_url('assets/img/video-thumbnail.png');
            break;
    }
    return $image_url;
}

function get_ads_data($type = '')
{
    $CI =& get_instance();
    
    if($type == 'ad_area'){
        $return_type = array();
    }else{
        $return_type = false;
    }

    $where_type = 'ads';

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return $return_type;
    }
    
    $data = json_decode($data[0]['value']);

    if($type == ''){
        return $data;
    }

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        return $return_type;
    }
}

function get_google_client_id()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'logins']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return '';
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->google_client_id)){
        return $data->google_client_id;
    }else{
        return '';
    }
}

function get_google_client_secret()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'logins']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return '';
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->google_client_secret)){
        return $data->google_client_secret;
    }else{
        return '';
    }
}

function get_mata_data($type = '')
{
    $CI =& get_instance();
    
    $where_type = 'seo';

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if($type == ''){
        return $data;
    }

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        return false;
    }
}

function get_unread_support_msg_count()
{
    $CI =& get_instance();
    return $CI->support_model->get_unread_support_msg_count($CI->session->userdata('user_id'));				
}

function from_email()
{
    $CI =& get_instance();
    $CI->load->library('session');
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return 'admin@vcard.com';
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->from_email)){
        return $data->from_email;
    }else{
        return 'admin@vcard.com';
    }
}

function set_expire_all_expired_plans(){
    $CI =& get_instance();
    if($CI->db->query("UPDATE users_plans SET expired=0 WHERE expired=1 AND end_date < CURDATE() ")){
        return true;
    }else{
        return false;
    }
}

function get_google_recaptcha_site_key()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'recaptcha']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->site_key)){
        return $data->site_key;
    }else{
        return '';
    }
} 
function get_google_recaptcha_secret_key()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'recaptcha']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->secret_key)){
        return $data->secret_key;
    }else{
        return '';
    }
} 

function slug_unique($string, $id = ''){
    $CI =& get_instance();
    $where = "";
    if($id != '' && is_numeric($id)){
        $where .= " and id != $id ";
    }
    $query = $CI->db->query("SELECT id FROM cards WHERE slug = '$string' $where ");
    $data = $query->result_array();
    if(!empty($data)){
        return true;
    }else{
        return false;
    }
}

function create_slug($string){
    $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
    return $slug;
}

function render_email_template($template_name, $template_data){
    $ci = get_instance();

    if(!$template_name && !$template_data){
        return false;
    }
    
    $pre_template_data = array();
    $pre_template_data['COMPANY_NAME'] = company_name();
    $pre_template_data['DASHBOARD_URL'] = base_url();
    $pre_template_data['LOGO_URL'] = full_logo();
    
    $template_data = array_merge($pre_template_data,$template_data);

    $template_code = $ci->settings_model->get_email_templates($template_name);
    if($template_code){
        if(isset($template_code[0]['message']) && $template_code[0]['message'] != ''){
            $template = $template_code[0]['message'];
            foreach($template_data as $key => $value)
            {
                $template = str_replace('{'.$key.'}', $value, $template);
            }
            $template_code[0]['message'] = $template;
            return $template_code;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function send_mail($to, $subject, $message) {
    $CI = get_instance();
    
    $email_library = get_email_library();

    if($email_library == 'codeigniter'){
        $email_config = Array();
        $email_config["protocol"] = "smtp";
        $email_config["charset"] = "utf-8";
        $email_config["mailtype"] = "html";
        $email_config["smtp_host"] = smtp_host();
        $email_config["smtp_port"] = smtp_port();
        $email_config["smtp_user"] = smtp_email();
        $email_config["smtp_pass"] = smtp_password();
        $email_config["smtp_crypto"] = smtp_encryption();
        if($email_config["smtp_crypto"] == 'none'){
            $email_config["smtp_crypto"] = "";
        }
        $CI->load->library('email', $email_config);
        $CI->email->clear(true);
        $CI->email->set_newline("\r\n");
        $CI->email->set_crlf("\r\n");
        $CI->email->from(from_email(), company_name());
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);
        if($CI->email->send()){
            return true;
        }else{
            return false;
        }
    }else{
        require_once('vendor/phpmailer/class.phpmailer.php');
        $CI = new PHPMailer(); 
        $CI->CharSet = 'UTF-8';
        $CI->IsSMTP(); 
        $CI->SMTPDebug = 1; 
        $CI->SMTPAuth = true;
        $CI->SMTPSecure = smtp_encryption();
        $CI->Host = smtp_host();
        $CI->Port = smtp_port();
        $CI->IsHTML(true);
        $CI->Username = smtp_email();
        $CI->Password = smtp_password();
        $CI->SetFrom(from_email(), company_name());
        $CI->Subject = $subject;
        $CI->Body = $message;
        $CI->AddAddress($to);
        if($CI->Send()){
            return true;
        }else{
            return false;
        }
    }
}

function turn_off_custom_domain_system(){    
    
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return 0;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->turn_off_custom_domain_system) && !empty($data->turn_off_custom_domain_system)){
        return $data->turn_off_custom_domain_system;
    }else{
        return 0;
    }

}

function turn_off_new_user_registration(){    
    
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return 0;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->turn_off_new_user_registration) && !empty($data->turn_off_new_user_registration)){
        return $data->turn_off_new_user_registration;
    }else{
        return 0;
    }

}

function get_header_code()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'custom_code']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->header_code)){
        return $data->header_code;
    }else{
        return '';
    }
} 

function get_footer_code()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'custom_code']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(!empty($data->footer_code)){
        return $data->footer_code;
    }else{
        return '';
    }
} 

function theme_color(){    
    
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->theme_color) && !empty($data->theme_color)){
        return $data->theme_color;
    }else{
        return '#e52165';
    }

}

function email_activation(){    
    
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->email_activation) && !empty($data->email_activation)){
        return $data->email_activation;
    }else{
        return false;
    }

}

function default_language()
{
    $CI =& get_instance();

    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->default_language)){
        return $data->default_language;
    }else{
        return 'english';
    }
}

function company_details($type = '', $user_id = '')
{
    $CI =& get_instance();
    if(empty($user_id)){
        if($CI->ion_auth->in_group(4)){
            $where_type = 'company_'.$CI->session->userdata('user_id');
        }else{
            $where_type = 'company_'.$CI->session->userdata('saas_id');
        }
    }else{
        $where_type = 'company_'.$user_id;
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return '';
    }
    
    $data = json_decode($data[0]['value']);

    if($type == ''){
        return $data;
    }

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        return '';
    }
} 

function get_currency($type)
{
    $CI =& get_instance();

    $where_type = 'general_'.$CI->session->userdata('saas_id');

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        if($type == 'currency_code'){
            return 'USD';
        }else{
            return '$';
        }
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        if($type == 'currency_code'){
            return 'USD';
        }else{
            return '$';
        }
    }
} 

function get_saas_currency($type)
{
    $CI =& get_instance();
    
    $where_type = 'general';

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        if($type == 'currency_code'){
            return 'USD';
        }else{
            return '$';
        }
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->{$type})){
        return $data->{$type};
    }else{
        if($type == 'currency_code'){
            return 'USD';
        }else{
            return '$';
        }
    }
} 

function get_home($lang = '')
{
    $CI =& get_instance();
    $CI->db->where(['type'=>'home']);
    $query = $CI->db->get('settings');
    $data = $query->result_array();
    if(!$data){
        return false;
    }
    $data = json_decode($data[0]['value']);
    if(empty($lang)){
        return $data;
    }else{
        if(isset($data->$lang)){
            return $data->$lang;
        }else{
            return true;
        }
    }
}

function get_languages($language_id = '', $language_name = '', $status = ''){
    $CI =& get_instance();
    $languages = $CI->languages_model->get_languages($language_id, $language_name, $status);
    if(empty($languages)){
        return false;
    }else{
        return $languages;
    }
}

function get_notifications($user_id = ''){

    $CI =& get_instance();
    $left_join = " LEFT JOIN users u ON n.from_id=u.id ";
    $query = $CI->db->query("SELECT n.*,u.first_name,u.last_name,u.profile FROM notifications n $left_join WHERE is_read=0 AND to_id=".$CI->session->userdata('user_id')." ORDER BY n.created DESC LIMIT 10");
    $notifications = $query->result_array();
    if($notifications){
        foreach($notifications as $key => $notification){
            $temp[$key] = $notification;

            $extra = '';
            $notification_url = base_url('notifications');
            $notification_txt = $notification['notification'];
            if($notification['type'] == 'offline_request' && $CI->ion_auth->in_group(3)){
                $notification_txt = $CI->lang->line('offline_bank_transfer_request_created_for_subscription_plan')?$CI->lang->line('offline_bank_transfer_request_created_for_subscription_plan')." ".$notification['notification']:"Offline / Bank Transfer request created for subscription plan ".$notification['notification'];
                $notification_url = base_url('plans/offline-requests');
                $plan = $CI->plans_model->get_plans($notification['type_id']);
                if($plan){
                    $extra = '<div class="text-small">
                        '.($CI->lang->line('plan')?$CI->lang->line('plan'):'Plan').': <span class="text-info">'.$plan[0]['title'].'</span> 
                    </div>'; 
                }
            }elseif($notification['type'] == 'new_plan' && $CI->ion_auth->in_group(3)){
                $notification_txt = $CI->lang->line('ordered_subscription_plan')?$CI->lang->line('ordered_subscription_plan')." ".$notification['notification']:"Ordered subscription plan ".$notification['notification'];
                $notification_url = base_url('plans/orders');
                $plan = $CI->plans_model->get_plans($notification['type_id']);
                if($plan){
                    $user = $CI->ion_auth->user($notification['from_id'])->row();
                    if($user){
                        $ADD = 'User: <span class="text-info">'.$user->first_name.' '.$user->last_name.'</span>';
                    }else{
                        $ADD = '';
                    }
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('plan')?$CI->lang->line('plan'):'Plan').': <span class="text-info">'.$plan[0]['title'].'</span> 
                        '.($CI->lang->line('transaction')?$CI->lang->line('transaction'):'Transaction').': <span class="text-info">'.$plan[0]['price'].'</span> 
                        '.$ADD.'
                    </div>';
                }
            }elseif($notification['type'] == 'new_user' && $CI->ion_auth->in_group(3)){
                $notification_txt = $CI->lang->line('new_user_registered')?$CI->lang->line('new_user_registered'):"New user registered.";
                $notification_url = base_url('users/saas');
                $user = $CI->ion_auth->user($notification['type_id'])->row();
                if($user){
                    $extra = '<div class="text-small">
                        '.($CI->lang->line('user')?$CI->lang->line('user'):'User').': <span class="text-info">'.$user->first_name.' '.$user->last_name.'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'offline_request' && $CI->ion_auth->is_admin()){
                
                $notification_txt = $CI->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')?$CI->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')." ".$notification['notification']:"Your Offline / Bank Transfer request accepted for subscription plan ".$notification['notification'];
                $notification_url = base_url('plans');
                $plan = $CI->plans_model->get_plans($notification['type_id']);
                if($plan){
                    $extra = '<div class="text-small">
                    '.($CI->lang->line('plan')?$CI->lang->line('plan'):'Plan').': <span class="text-info">'.$plan[0]['title'].'</span> 
                    </div>';
                }
            }elseif($notification['type'] == 'new_domain_status' && $CI->ion_auth->is_admin()){
                
                $notification_txt = $notification['notification']." ".($CI->lang->line('custom_domain_status_updated')?$CI->lang->line('custom_domain_status_updated'):" custom domain status updated.");
                $notification_url = base_url('cards/custom-domain/'.$notification['type_id']);

            }elseif($notification['type'] == 'new_domain' && $CI->ion_auth->in_group(3)){
                
                $notification_txt = $notification['notification']." ".($CI->lang->line('a_new_custom_domain_submitted')?$CI->lang->line('a_new_custom_domain_submitted'):" a new custom domain submitted.");
                $notification_url = base_url('cards/domain-request');
                $user = $CI->ion_auth->user($notification['from_id'])->row();
                if($user){
                    $extra = '<div class="text-small">
                        '.($CI->lang->line('user')?$CI->lang->line('user'):'User').': <span class="text-info">'.$user->first_name.' '.$user->last_name.'</span> 
                    </div>';
                }
            }

            $temp[$key]['notification_url'] = $notification_url;

            $temp[$key]['notification'] = $notification_txt.' '.$extra;
            
            $temp[$key]['first_name'] = $notification['first_name'];
            $temp[$key]['last_name'] = $notification['last_name'];
            $temp[$key]['profile'] = $notification['profile'];

        }
    }else{
        $temp = array();
    }

    if(!empty($temp)){
        return $temp;
    }else{
        return false;
    }
}

function count_days_btw_two_dates($today , $sec_date){
    $CI =& get_instance();
    $today=date_create($today);
    $sec_date=date_create($sec_date);
    $diff=date_diff($today,$sec_date);
    $data['days'] = $diff->format("%a");
    if($today < $sec_date || $today == $sec_date){
        $data['days_status'] = $CI->lang->line('left')?$CI->lang->line('left'):'Left';
    }else{
        $data['days_status'] = $CI->lang->line('overdue')?$CI->lang->line('overdue'):'Overdue';
    }
    return $data;
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    if(!is_dir($dst)){
        mkdir($dst,0775,true);
    }
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}


function get_bank_details($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->bank_details)){
            return $data->bank_details;
        }else{
            return '';
        }
    }else{
        return false;
    }
}


function get_features($feature_type = '')
{
    $CI =& get_instance();
    $CI->db->where(['type'=>'features']);
    $query = $CI->db->get('settings');
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($feature_type)){
        return $data;
    }else{
        if(isset($data->$feature_type)){
            return $data->$feature_type;
        }else{
            return true;
        }
    }
}

function frontend_permissions($permissions_type = '')
{
    $CI =& get_instance();

    $CI->db->where(['type'=>'frontend']);
    $query = $CI->db->get('settings');
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(empty($permissions_type)){
        return $data;
    }else{
        if(isset($data->$permissions_type)){
            return $data->$permissions_type;
        }else{
            return true;
        }
    }
} 

function is_module_allowed($module_type = '')
{
    $CI =& get_instance();

    if($CI->session->userdata('saas_id') == ''){
        return true;
    }
    if($CI->ion_auth->in_group(3)){
        return true;
    }
    $count_query = $CI->db->query("SELECT * FROM users_plans WHERE saas_id=".$CI->session->userdata('saas_id')." AND expired=1 AND (end_date >= CURDATE() || end_date IS NULL)");
    $count = $count_query->row_array();

    if($count){
            $current_plan = get_current_plan();
    }else{
        return false;
    }

    if($current_plan['modules'] != ''){
        $data = json_decode($current_plan['modules']);
        if(isset($data->{$module_type}) && $data->{$module_type} == 1){
            return true;
        }else{
            return false;
        } 
    }else{
        return true;
    }
} 

function get_current_plan($saas_id = ''){
    $CI =& get_instance();

    if(empty($saas_id)){
        $saas_id = $CI->session->userdata('saas_id');
    }

    if(empty($saas_id)){
        return false;
    }

    $left_join = " LEFT JOIN plans p ON up.plan_id=p.id ";

    $query = $CI->db->query("SELECT up.*,p.title,p.price,p.billing_type,p.cards,p.modules,p.custom_fields,p.products_services,p.portfolio,p.testimonials,p.gallery,p.custom_sections,p.team_member FROM users_plans up $left_join WHERE up.saas_id=$saas_id ORDER BY up.created DESC LIMIT 1");
    $data = $query->row_array();

    if(!empty($data) && $saas_id){
        return $data;
    }else{
        return false;
    }
}

function my_plan_features($feature_type = '')
{
    $CI =& get_instance();

    if($CI->session->userdata('saas_id') == ''){
        return false;
    }

    $count_query = $CI->db->query("SELECT * FROM users_plans WHERE saas_id=".$CI->session->userdata('saas_id')." AND (end_date >= CURDATE() || end_date IS NULL)");
    $count = $count_query->row_array();
    if($count){
        $current_plan = get_current_plan();
    }else{
        return false;
    }

    if($feature_type == 'card_fields'){
        $field_name = 'custom_fields';
    }elseif($feature_type == 'products'){
        $field_name = 'products_services';
    }elseif($feature_type == 'card_sections'){
        $field_name = 'custom_sections';
    }elseif($feature_type == 'users'){
        $field_name = 'team_member';
    }else{
        $field_name = $feature_type;
    }

    if($current_plan[$field_name] < 0){
        return true;
    }elseif($current_plan[$field_name] == get_count('id',$feature_type,'saas_id='.$CI->session->userdata('saas_id'))){
        if($feature_type == 'users'){
            return true;
        }
        return false;
    }else{
        if($current_plan[$field_name] < get_count('id',$feature_type,'saas_id='.$CI->session->userdata('saas_id'))){
            return false;
        }
        return true;
    }
    
} 

function get_razorpay_key_id($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->razorpay_key_id)){
            return $data->razorpay_key_id;
        }else{
            return '';
        }
    }else{
        return false;
    }
}
function get_razorpay_key_secret($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->razorpay_key_secret)){
            return $data->razorpay_key_secret;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_paystack_public_key($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paystack_public_key)){
            return $data->paystack_public_key;
        }else{
            return '';
        }
    }else{
        return false;
    }
}
function get_paystack_secret_key($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paystack_secret_key)){
            return $data->paystack_secret_key;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_offline_bank_transfer($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->offline_bank_transfer)){
            return $data->offline_bank_transfer;
        }else{
            return '';
        }
    }else{
        return false;
    }
}
function get_stripe_publishable_key($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->stripe_publishable_key)){
            return $data->stripe_publishable_key;
        }else{
            return '';
        }
    }else{
        return false;
    }
}
function get_stripe_secret_key($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->stripe_secret_key)){
            return $data->stripe_secret_key;
        }else{
            return '';
        }
    }else{
        return false;
    }
}

function get_payment_paypal($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paypal_client_id)){
            return $data->paypal_client_id;
        }else{
            return true;
        }
    }else{
        return false;
    }
}
function get_paypal_secret($is_non_saas = false){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    if($is_non_saas){
        $CI->db->where(['type'=>'payment_'.$CI->session->userdata('saas_id')]);
    }else{
        $CI->db->where(['type'=>'payment']);
    }
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        $data = json_decode($data[0]['value']);
        if(isset($data->paypal_secret)){
            return $data->paypal_secret;
        }else{
            return true;
        }
    }else{
        return false;
    }
}

function get_system_version(){
    $CI =& get_instance();
    $CI->db->select('value');
    $CI->db->from('settings');
    $CI->db->where(['type'=>'system_version']);
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data[0]['value'];
    }else{
        return false;
    }
}

function get_earnings(){ 
    
    $CI =& get_instance();
    $query = $CI->db->query("SELECT sum(amount) AS amount FROM transactions WHERE status=1");
    $res = $query->result_array();
    if(!empty($res)){
        return $res[0]['amount']?$res[0]['amount']:0;
    }else{
        return false;
    }
    
}

function get_count($field,$table,$where = ''){ 
    if(!empty($where)){
        $where = "where ".$where;
    }
    $CI =& get_instance();
    $query = $CI->db->query("SELECT $field FROM ".$table." ".$where." ");
    $res = $query->result_array();
    if(!empty($res)){
        return count($res);
    }else{
        return 0;
    }
}

function get_email_library()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }

    $data = json_decode($data[0]['value']);

    if(!empty($data->email_library)){
        return $data->email_library;
    }else{
        return "codeigniter";
    }
}

function smtp_host()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }

    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_host)){
        return $data->smtp_host;
    }else{
        return false;
    }
} 

function smtp_port()
{
    $CI =& get_instance();

    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_port)){
        return $data->smtp_port;
    }else{
        return false;
    }
} 

function smtp_email()
{
    $CI =& get_instance();
    $CI->load->library('session');
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_username)){
        return $data->smtp_username;
    }else{
        return false;
    }
}

function smtp_password()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_password)){
        return $data->smtp_password;
    }else{
        return false;
    }
}

function smtp_encryption()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'email_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'email_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'email';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);
    
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->smtp_encryption)){
        return $data->smtp_encryption;
    }else{
        return false;
    }
}

function company_name()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->company_name)){
        return $data->company_name;
    }else{
        return 'Your';
    }
} 

function company_email()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->company_email)){
        return $data->company_email;
    }else{
        return 'admin@admin.com';
    }
} 

function footer_text()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->footer_text)){
        return $data->footer_text;
    }else{
        return company_name().' '.date('Y').' All Rights Reserved';
    }
} 

function google_analytics()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->google_analytics)){
        return $data->google_analytics;
    }else{
        return false;
    }
} 

function mysql_timezone()
{
    $CI =& get_instance();

    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->mysql_timezone)){
        return $data->mysql_timezone;
    }else{
        return '-11:00';
    }
} 

function alert_days()
{
    $CI =& get_instance();
    

    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(isset($data->alert_days) && !empty($data->alert_days)){
        return $data->alert_days;
    }else{
        return 1;
    }
} 

function php_timezone()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->php_timezone)){
        return $data->php_timezone;
    }else{
        return 'Pacific/Midway';
    }
} 

function system_date_format_js()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->date_format_js)){
        return $data->date_format_js;
    }else{
        return 'd-m-yyyy';
    }
} 

function system_time_format_js()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->time_format_js)){
        return $data->time_format_js;
    }else{
        return 'hh:MM A';
    }
} 

function format_date($date, $date_format){
    // $date = str_replace('/', '-', $date);  
    $date = date_create($date);
    return date_format($date,$date_format);
}

function system_date_format()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->date_format)){
        return $data->date_format;
    }else{
        return 'd-m-Y';
    }
} 

function system_time_format()
{
    $CI =& get_instance();
    
    $CI->db->where('type', 'general_'.$CI->session->userdata('saas_id'));
    $count = $CI->db->get('settings');
    if($count->num_rows() > 0){
        $where_type = 'general_'.$CI->session->userdata('saas_id');
    }else{
        $where_type = 'general';
    }

    $CI->db->from('settings');
    $CI->db->where(['type'=>$where_type]);

    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->time_format)){
        return $data->time_format;
    }else{
        return 'h:i A';
    }
} 

function full_logo()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->full_logo)){
        return $data->full_logo;
    }else{
        return 'logo.png';
    }
} 

function half_logo()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();

    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->half_logo)){
        return $data->half_logo;
    }else{
        return 'logo-half.png';
    }
} 

function favicon()
{
    $CI =& get_instance();
    $CI->db->from('settings');
    $CI->db->where(['type'=>'general']);
    $query = $CI->db->get();
    $data = $query->result_array();
    
    if(!$data){
        return false;
    }
    
    $data = json_decode($data[0]['value']);

    if(!empty($data->favicon)){
        return $data->favicon;
    }else{
        return 'favicon.png';
    }
} 

function time_formats(){
    $CI =& get_instance();
    $CI->db->from('time_formats');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function date_formats(){
    $CI =& get_instance();
    $CI->db->from('date_formats');
    $query = $CI->db->get();
    $data = $query->result_array();
    if(!empty($data)){
        return $data;
    }else{
        return false;
    }
}

function timezones(){
    $list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();
    
        $data = $offset = $added = array();
        foreach ($list as $abbr => $info) {
            foreach ($info as $zone) {
                if ( ! empty($zone['timezone_id'])
                    AND
                    ! in_array($zone['timezone_id'], $added)
                    AND 
                      in_array($zone['timezone_id'], $idents)) {
                    $z = new DateTimeZone($zone['timezone_id']);
                    $c = new DateTime(null, $z);
                    $zone['time'] = $c->format('H:i a');
                    $offset[] = $zone['offset'] = $z->getOffset($c);
                    $data[] = $zone;
                    $added[] = $zone['timezone_id'];
                }
            }
        }
    
        array_multisort($offset, SORT_ASC, $data);
        
        $i = 0;$temp = array();
        foreach ($data as $key => $row) {
            $temp[0] = $row['time'];
            $temp[1] = formatOffset($row['offset']);
            $temp[2] = $row['timezone_id'];
            $options[$i++] = $temp;
        }
        
        if(!empty($options)){
            return $options;
        }
}

function formatOffset($offset) {
    $hours = $offset / 3600;
    $remainder = $offset % 3600;
    $sign = $hours > 0 ? '+' : '-';
    $hour = (int) abs($hours);
    $minutes = (int) abs($remainder / 60);

    if ($hour == 0 AND $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT).':'. str_pad($minutes,2, '0');
}

?>