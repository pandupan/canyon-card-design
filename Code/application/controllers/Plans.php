<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Plans extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
	}

	public function invoice(){
		if($this->uri->segment(3) && is_numeric($this->uri->segment(3)))
		{
			$orders = $this->plans_model->get_orders($this->uri->segment(3));
			if($orders){

				$this->data['page_title'] = $orders[0]['invoice_id'];
				$this->data['orders'] = $orders;
			
				$this->load->view('invoices-pdf.php', $this->data);
	
				$html = $this->output->get_output();
	
				$this->load->library('pdf');
	
				$options = $this->dompdf->getOptions();
				$options->setisPhpEnabled(true);
				$options->setisRemoteEnabled(true);
				$options->setisJavascriptEnabled(true);
				$options->setisHtml5ParserEnabled(true);
	
				$this->dompdf->setOptions($options);
				$this->dompdf->loadHtml($html);
				$this->dompdf->render();
				$this->dompdf->stream("invoice.pdf", array("Attachment"=>0));

			}else{
				redirect('auth', 'refresh');
			}

		}else{
			redirect('auth', 'refresh');
		}
	}


	public function pay($plan_id = '')
	{
		if(empty($plan_id)){
			$plan_id = $this->uri->segment(3)?$this->uri->segment(3):'';
		}

		if(empty($plan_id)){
			redirect('plans', 'refresh');
		}

		if(!is_numeric($plan_id)){
			redirect('plans', 'refresh');
		}

		$this->data['page_title'] = 'Payment - '.company_name();
		$this->data['current_user'] = $this->ion_auth->user()->row();
		$this->data['plan'] = $plan = $this->plans_model->get_plans($plan_id);
		if(empty($plan)){
			redirect('plans', 'refresh');
		}elseif(!isset($plan[0]['title'])){
			redirect('plans', 'refresh');
		}elseif($plan[0]['billing_type'] != 'One Time' && $plan[0]['billing_type'] != 'Monthly' && $plan[0]['billing_type'] != 'Yearly'){
			redirect('plans', 'refresh');
		}
		$this->data['taxes'] = $this->settings_model->get_taxes();
		$this->load->view('plans-pay',$this->data);

	}

	public function create_session($plan_id = '')
	{	
		$stripeSecret = get_stripe_secret_key();
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && $stripeSecret)
		{
			if(empty($plan_id)){
				$plan_id = $this->uri->segment(3)?$this->uri->segment(3):'';
			}
			if(!empty($plan_id) || is_numeric($plan_id)){
				$plan = $this->plans_model->get_plans($plan_id);
				if($plan){
					require_once('vendor/stripe/stripe-php/init.php');
					
					\Stripe\Stripe::setApiKey($stripeSecret);
					$session = \Stripe\Checkout\Session::create([
						'payment_method_types' => ['card'],
						'line_items' => [[
						'price_data' => [
							'currency' => get_saas_currency('currency_code'),
							'product_data' => [
							'name' => $plan[0]['title'],
							],
							'unit_amount' => $plan[0]['price']*100,
						],
						'quantity' => 1,
						]],
						'metadata' => [
							'plan_id' => $plan_id,
						],
						'mode' => 'payment',
						'success_url' => base_url().'plans/soc?sid={CHECKOUT_SESSION_ID}',
						'cancel_url' => base_url().'plans/soc?sid={CHECKOUT_SESSION_ID}',
					]);
					$data = array('id' => $session->id, 'data' => $session);
					echo json_encode($data);
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
			}else{
				$this->data['error'] = true;
				$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
			echo json_encode($this->data);
		}
	}

	public function index()
	{	
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			if ($this->ion_auth->is_admin()){
				$this->notifications_model->edit('', 'offline_request', '', '', '');
			}
			$this->data['page_title'] = 'Subscription Plans - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->data['plans'] = $this->plans_model->get_plans();
			$this->load->view('plans',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function soc()
	{	
		$stripeSecret = get_stripe_secret_key();
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin() && $stripeSecret)
		{
			if(isset($_GET['sid']) && $_GET['sid'] != ''){
				require_once('vendor/stripe/stripe-php/init.php');
				$stripe = new \Stripe\StripeClient($stripeSecret);
				try{
					$payment_details = $stripe->checkout->sessions->retrieve($_GET['sid']);
					if($payment_details->payment_status == 'paid'){
						$plan = $this->plans_model->get_plans($payment_details->metadata->plan_id);
						if($plan){
							if($plan[0]['price'] > 0){
								$transaction_data = array(
									'saas_id' => $this->session->userdata('saas_id'),			
									'amount' => $plan[0]['price'],		
									'status' => 1,		
								);

								$transaction_id = $this->plans_model->create_transaction($transaction_data);
								
								$taxes = $this->settings_model->get_taxes();
								if($taxes){
									$tax_pec = 0;
									$tax_arry = array();
									foreach($taxes as $key => $tax){ 
									  $tax_pec = $tax_pec+$tax['tax']; 
									  $tax_arry[$key]['tax_name'] = $tax['title'];
									  $tax_arry[$key]['tax_per'] = $tax['tax'];
									  $tax_arry[$key]['tax_amount'] = $plan[0]['price']*$tax['tax']/100;
									} 
								}
								$tax_amount = $plan[0]['price']*$tax_pec/100;
								$total_amount_with_tax = $plan[0]['price']+$tax_amount;

								$order_data = array(
									'saas_id' => $this->session->userdata('saas_id'),		
									'plan_id' => $payment_details->metadata->plan_id,		
									'amount' => $plan[0]['price'],		
									'amount_with_tax' => $total_amount_with_tax,		
									'tax' => json_encode($tax_arry),	
									'transaction_id' => $transaction_id,			
								);

								$order_id = $this->plans_model->create_order($order_data);
							}
							
							$dt = strtotime(date("Y-m-d"));
							if($plan[0]['billing_type'] == "One Time"){
								$date = NULL;
							}elseif($plan[0]['billing_type'] == "Monthly"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}elseif($plan[0]['billing_type'] == "Yearly"){
								$date = date("Y-m-d", strtotime("+1 year", $dt));
							}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+3 days", $dt));
							}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+7 days", $dt));
							}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+15 days", $dt));
							}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}else{
								$date = date("Y-m-d", strtotime("+3 days", $dt));
							}

							$my_plan = get_current_plan();
							if($my_plan){
								if($my_plan['expired'] == 1){
									if($my_plan['plan_id'] == 1 && $my_plan['plan_id'] == $payment_details->metadata->plan_id){
										$date = date("Y-m-d", strtotime("+3 days", $dt));
										if($plan[0]['billing_type'] == "One Time"){
											$date = NULL;
										}
									}else{

										if(empty($my_plan['end_date'])){
											$dt = strtotime(date("Y-m-d"));
										}else{
											$dt = strtotime($my_plan['end_date']);
										}

										if($plan[0]['billing_type'] == "One Time"){
											$date = NULL;
										}elseif($plan[0]['billing_type'] == "Monthly"){
											$date = date("Y-m-d", strtotime("+1 month", $dt));
										}elseif($plan[0]['billing_type'] == "Yearly"){
											$date = date("Y-m-d", strtotime("+1 year", $dt));
										}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
											$date = date("Y-m-d", strtotime("+3 days", $dt));
										}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
											$date = date("Y-m-d", strtotime("+7 days", $dt));
										}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
											$date = date("Y-m-d", strtotime("+15 days", $dt));
										}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
											$date = date("Y-m-d", strtotime("+1 month", $dt));
										}else{
											$date = date("Y-m-d", strtotime("+3 days", $dt));
										}
									}
								}
								$users_plans_data = array(
									'plan_id' => $payment_details->metadata->plan_id,		
									'expired' => 1,		
									'start_date' => date("Y-m-d"),			
									'end_date' => $date,			
								);
								$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
							}else{
								$users_plans_data = array(	
									'expired' => 1,				
									'plan_id' => $payment_details->metadata->plan_id,		
									'start_date' => date("Y-m-d"),			
									'end_date' => $date,			
								);
								$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
							}
							
							if($users_plans_id){

								// notification to the saas admins
								$saas_admins = $this->ion_auth->users(array(3))->result();
								foreach($saas_admins as $saas_admin){
									$data = array(
										'notification' => '<span class="text-info">'.$plan[0]['title'].'</span>',
										'type' => 'new_plan',	
										'type_id' => $payment_details->metadata->plan_id,	
										'from_id' => $this->session->userdata('saas_id'),
										'to_id' => $saas_admin->user_id,	
									);
									$notification_id = $this->notifications_model->create($data);
								}

								$this->session->set_flashdata('message', $this->lang->line('plan_subscribed_successfully')?$this->lang->line('plan_subscribed_successfully'):"Plan subscribed successfully.");
								$this->session->set_flashdata('message_type', 'success');
							}else{
								$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
								$this->session->set_flashdata('message_type', 'success');
							}
						}else{
							$this->session->set_flashdata('message', $this->lang->line('choose_valid_subscription_plan')?$this->lang->line('choose_valid_subscription_plan'):"Choose valid subscription plan.");
							$this->session->set_flashdata('message_type', 'success');
						}
					}else{
						$this->session->set_flashdata('message', $this->lang->line('payment_unsuccessful_please_try_again_later')?$this->lang->line('payment_unsuccessful_please_try_again_later'):"Payment unsuccessful. Please Try again later.");
						$this->session->set_flashdata('message_type', 'success');
					}
				}catch(Exception $e){
					$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
					$this->session->set_flashdata('message_type', 'success');
				}
			}else{
				$this->session->set_flashdata('message', $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.");
				$this->session->set_flashdata('message_type', 'success');
			}
			redirect('plans', 'refresh');
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function orders()
	{	
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->notifications_model->edit('', 'new_plan', '', '', '');
			$this->data['page_title'] = 'Subscription Orders - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('orders',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}

	public function transactions()
	{	
		if ($this->ion_auth->logged_in())
		{
			$this->data['page_title'] = 'Transactions - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('transactions',$this->data);
		}else{
			redirect('auth', 'refresh');
		}
	}
	public function get_transactions($transaction_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$transactions = $this->plans_model->get_transactions($transaction_id);
			if($transactions){
				foreach($transactions as $key => $transaction){
					$temp[$key] = $transaction;
					$temp[$key]['user'] = $transaction['first_name']." ".$transaction['last_name'];
					$temp[$key]['created'] = format_date($transaction['created'],system_date_format());

					if($transaction['status']==1){
						$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('completed')?$this->lang->line('completed'):'Completed').'</div>';
					}else{
						$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
					}
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_orders($order_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$orders = $this->plans_model->get_orders($order_id);
			if($orders){
				foreach($orders as $key => $order){
					$temp[$key] = $order;
					$temp[$key]['user'] = $order['first_name']." ".$order['last_name'];

					if($order["billing_type"] == 'One Time'){
						$temp[$key]['billing_type'] = $this->lang->line('one_time')?$this->lang->line('one_time'):'One Time';
					}elseif($order["billing_type"] == 'Monthly'){
						$temp[$key]['billing_type'] = $this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly';
					}elseif($order["billing_type"] == 'three_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan';
					}elseif($order["billing_type"] == 'seven_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan';
					}elseif($order["billing_type"] == 'fifteen_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan';
					}elseif($order["billing_type"] == 'thirty_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan';
					}else{
						$temp[$key]['billing_type'] = $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
					}

					if($order['status']==1){
						$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('completed')?$this->lang->line('completed'):'Completed').'</div>';
					}else{
						$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
					}

					$temp[$key]['created'] = format_date($order['created'],system_date_format());

					$temp[$key]['invoice'] = '<a href="'.(base_url('plans/invoice/'.$order['id'])).'" target="_blank"><strong>'.$order['invoice_id'].'</strong></a>';

				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function get_offline_requests($id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$offline_requests = $this->plans_model->get_offline_requests($id);
			if($offline_requests){
				foreach($offline_requests as $key => $offline_request){
					$temp[$key] = $offline_request;
					$temp[$key]['user'] = $offline_request['first_name']." ".$offline_request['last_name'];
					$temp[$key]['created'] = format_date($offline_request['created'],system_date_format());
					
					if($offline_request["billing_type"] == 'One Time'){
						$billing_type = $this->lang->line('one_time')?$this->lang->line('one_time'):'One Time';
					}elseif($offline_request["billing_type"] == 'Monthly'){
						$billing_type = $this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly';
					}elseif($offline_request["billing_type"] == 'three_days_trial_plan'){
						$billing_type = $this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan';
					}elseif($offline_request["billing_type"] == 'seven_days_trial_plan'){
						$billing_type = $this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan';
					}elseif($offline_request["billing_type"] == 'fifteen_days_trial_plan'){
						$billing_type = $this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan';
					}elseif($offline_request["billing_type"] == 'thirty_days_trial_plan'){
						$billing_type = $this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan';
					}else{
						$billing_type = $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
					}

					$temp[$key]['title'] = '<b>'.$offline_request['title']."</b><br><b>".($this->lang->line('billing_type')?htmlspecialchars($this->lang->line('billing_type')):'Billing Type').':</b> '.$billing_type."<br><b>".($this->lang->line('price_usd')?$this->lang->line('price_usd'):'Price').':</b> '.get_saas_currency('currency_sy').' '.$offline_request['price'];

					if($offline_request['receipt']){
						$file_upload_path = '';
						if(file_exists('assets/uploads/receipt/'.$offline_request['receipt'])){
							$file_upload_path = 'assets/uploads/receipt/'.$offline_request['receipt'];
						}
						$temp[$key]['receipt'] = '<span class="d-flex"><a target="_blank" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View').'"><i class="fas fa-eye"></i></a><a download="'.$offline_request['receipt'].'" href="'.base_url($file_upload_path).'" class="btn btn-icon btn-sm btn-primary mr-1" data-toggle="tooltip" title="'.($this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download').'"><i class="fas fa-download"></i></a></span>';
					}

					if($offline_request['status']==0){
						$temp[$key]['status'] = '<div class="badge badge-info">'.($this->lang->line('pending')?$this->lang->line('pending'):'Pending').'</div>';
						$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-success mr-1 accept_request" data-id="'.$offline_request["id"].'" data-plan_id="'.$offline_request["plan_id"].'" data-saas_id="'.$offline_request["saas_id"].'" data-toggle="tooltip" title="'.($this->lang->line('accept')?htmlspecialchars($this->lang->line('accept')):'Accept').'"><i class="fas fa-check"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger reject_request" data-id="'.$offline_request["id"].'" data-plan_id="'.$offline_request["plan_id"].'" data-toggle="tooltip" title="'.($this->lang->line('reject')?htmlspecialchars($this->lang->line('reject')):'Reject').'"><i class="fas fa-times"></i></a></span>';
					}elseif($offline_request['status']==1){
						$temp[$key]['status'] = '<div class="badge badge-success">'.($this->lang->line('accepted')?$this->lang->line('accepted'):'Accepted').'</div>';
						$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="disabled btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('accept')?htmlspecialchars($this->lang->line('accept')):'Accept').'"><i class="fas fa-check"></i></a><a href="#" class="disabled btn btn-icon btn-sm btn-danger" data-toggle="tooltip" title="'.($this->lang->line('reject')?htmlspecialchars($this->lang->line('reject')):'Reject').'"><i class="fas fa-times"></i></a></span>';
					}else{
						$temp[$key]['status'] = '<div class="badge badge-danger">'.($this->lang->line('rejected')?$this->lang->line('rejected'):'Rejected').'</div>';
						$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="disabled btn btn-icon btn-sm btn-success mr-1" data-toggle="tooltip" title="'.($this->lang->line('accept')?htmlspecialchars($this->lang->line('accept')):'Accept').'"><i class="fas fa-check"></i></a><a href="#" class="disabled btn btn-icon btn-sm btn-danger" data-toggle="tooltip" title="'.($this->lang->line('reject')?htmlspecialchars($this->lang->line('reject')):'Reject').'"><i class="fas fa-times"></i></a></span>';
					}
				}
				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function offline_requests()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->notifications_model->edit('', 'offline_request', '', '', '');
			$this->data['page_title'] = 'Offline Requests - '.company_name();
			$this->data['current_user'] = $this->ion_auth->user()->row();
			$this->load->view('offline_requests',$this->data);

		}else{
			redirect('auth', 'refresh'); 
		}
		
	}

	public function reject_request()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('id', 'Request ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$data = array(
					'status' => 2,			
				);
				if($this->plans_model->accept_reject_request($this->input->post('id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('offline_request_rejected_successfully')?$this->lang->line('offline_request_rejected_successfully'):"Offline request rejected successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('offline_request_rejected_successfully')?$this->lang->line('offline_request_rejected_successfully'):"Offline request rejected successfully.";
					echo json_encode($this->data); 
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
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

	public function accept_request()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('id', 'Request ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('saas_id', 'SaaS ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			if($this->form_validation->run() == TRUE){
				$data = array(
					'status' => 1,			
				);
				if($this->plans_model->accept_reject_request($this->input->post('id'), $data)){

					$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
					if($plan[0]['price'] > 0){
						$transaction_data = array(
							'saas_id' => $this->input->post('saas_id'),			
							'amount' => $plan[0]['price'],		
							'status' => 1,		
						);
	
						$transaction_id = $this->plans_model->create_transaction($transaction_data);
	
						$taxes = $this->settings_model->get_taxes();
						if($taxes){
							$tax_pec = 0;
							$tax_arry = array();
							foreach($taxes as $key => $tax){ 
							  $tax_pec = $tax_pec+$tax['tax']; 
							  $tax_arry[$key]['tax_name'] = $tax['title'];
							  $tax_arry[$key]['tax_per'] = $tax['tax'];
							  $tax_arry[$key]['tax_amount'] = $plan[0]['price']*$tax['tax']/100;
							} 
						}
						$tax_amount = $plan[0]['price']*$tax_pec/100;
						$total_amount_with_tax = $plan[0]['price']+$tax_amount;

						$order_data = array(
							'saas_id' => $this->input->post('saas_id'),	
							'plan_id' => $this->input->post('plan_id'),		
							'amount' => $plan[0]['price'],		
							'amount_with_tax' => $total_amount_with_tax,		
							'tax' => json_encode($tax_arry),	
							'transaction_id' => $transaction_id,			
						);
						$order_id = $this->plans_model->create_order($order_data);
					}
					
					$dt = strtotime(date("Y-m-d"));
					if($plan[0]['billing_type'] == "One Time"){
						$date = NULL;
					}elseif($plan[0]['billing_type'] == "Monthly"){
						$date = date("Y-m-d", strtotime("+1 month", $dt));
					}elseif($plan[0]['billing_type'] == "Yearly"){
						$date = date("Y-m-d", strtotime("+1 year", $dt));
					}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
						$date = date("Y-m-d", strtotime("+3 days", $dt));
					}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
						$date = date("Y-m-d", strtotime("+7 days", $dt));
					}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
						$date = date("Y-m-d", strtotime("+15 days", $dt));
					}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
						$date = date("Y-m-d", strtotime("+1 month", $dt));
					}else{
						$date = date("Y-m-d", strtotime("+3 days", $dt));
					}
	
					$my_plan = get_current_plan();
					if($my_plan){
						if($my_plan['expired'] == 1 && $my_plan['plan_id'] == $this->input->post('plan_id')){
							if($my_plan['plan_id'] == 1){
								$date = date("Y-m-d", strtotime("+3 days", $dt));
								if($plan[0]['billing_type'] == "One Time"){
									$date = NULL;
								}
							}else{

								if(empty($my_plan['end_date'])){
									$dt = strtotime(date("Y-m-d"));
								}else{
									$dt = strtotime($my_plan['end_date']);
								}

								if($plan[0]['billing_type'] == "One Time"){
									$date = NULL;
								}elseif($plan[0]['billing_type'] == "Monthly"){
									$date = date("Y-m-d", strtotime("+1 month", $dt));
								}elseif($plan[0]['billing_type'] == "Yearly"){
									$date = date("Y-m-d", strtotime("+1 year", $dt));
								}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
									$date = date("Y-m-d", strtotime("+3 days", $dt));
								}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
									$date = date("Y-m-d", strtotime("+7 days", $dt));
								}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
									$date = date("Y-m-d", strtotime("+15 days", $dt));
								}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
									$date = date("Y-m-d", strtotime("+1 month", $dt));
								}else{
									$date = date("Y-m-d", strtotime("+3 days", $dt));
								}
							}
						}
						$users_plans_data = array(
							'plan_id' => $this->input->post('plan_id'),		
							'expired' => 1,		
							'start_date' => date("Y-m-d"),			
							'end_date' => $date,			
						);
						$users_plans_id = $this->plans_model->update_users_plans($this->input->post('saas_id'), $users_plans_data);
					}else{
						$users_plans_data = array(		
							'expired' => 1,				
							'plan_id' => $this->input->post('plan_id'),		
							'start_date' => date("Y-m-d"),			
							'end_date' => $date,			
						);

						$users_plans_id = $this->plans_model->update_users_plans($this->input->post('saas_id'), $users_plans_data);
					}

					if($users_plans_id){

						// notification to the creator admin

						$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
						$plan_name = '';
						if($plan){
							$plan_name = $plan[0]['title'];
						}
						$notification_data = array(
							'notification' => '<span class="text-info">'.$plan_name.'</span>',
							'type' => 'offline_request',	
							'type_id' => $this->input->post('plan_id'),	
							'from_id' => $this->session->userdata('user_id'),
							'to_id' => $this->input->post('saas_id'),	
						);
						$this->notifications_model->create($notification_data);

						$this->session->set_flashdata('message', $this->lang->line('offline_request_accepted_successfully')?$this->lang->line('offline_request_accepted_successfully'):"Offline request accepted successfully.");
						$this->session->set_flashdata('message_type', 'success');
						$this->data['error'] = false;
						$this->data['message'] = $this->lang->line('offline_request_accepted_successfully')?$this->lang->line('offline_request_accepted_successfully'):"Offline request accepted successfully.";
						echo json_encode($this->data); 
					}else{
						$this->data['error'] = true;
						$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
						echo json_encode($this->data);
					}
				}else{
					$this->data['error'] = true;
					$this->data['message'] = $this->lang->line('something_wrong_try_again')?$this->lang->line('something_wrong_try_again'):"Something wrong! Try again.";
					echo json_encode($this->data);
				}
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

	public function create_offline_request()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
		{
			$this->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			
			if(empty($_FILES['receipt']['name'])){
				$this->form_validation->set_rules('receipt', 'Upload Receipt', 'trim|required|strip_tags|xss_clean');
			}

			if($this->form_validation->run() == TRUE){

				$upload_path = 'assets/uploads/receipt';
				if(!is_dir($upload_path)){
					mkdir($upload_path,0775,true);
				}
				$image = time().'-'.str_replace(' ', '-', $_FILES["receipt"]['name']);
				$config['file_name'] = $image;
				$config['upload_path']          = $upload_path;
				$config['allowed_types']        = 'jpg|png|jpeg';
				$config['overwrite']             = false;
				$config['max_size']             = 0;
				$config['max_width']            = 0;
				$config['max_height']           = 0;
				$this->load->library('upload', $config);
				if (!$this->upload->do_upload('receipt')){
					$this->data['error'] = true;
					$this->data['message'] = $this->upload->display_errors();
					echo json_encode($this->data); 
					return false;
				}

				$data = array(
					'saas_id' => $this->session->userdata('saas_id'),			
					'plan_id' => $this->input->post('plan_id'),				
					'receipt' => $image,		
				);
				$offline_request_id = $this->plans_model->create_offline_request($data);
				if($offline_request_id){

					// notification to the saas admins
					$saas_admins = $this->ion_auth->users(array(3))->result();
					$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
					$plan_name = '';
					if($plan){
						$plan_name = $plan[0]['title'];
					}
					foreach($saas_admins as $saas_admin){
						$notification_data = array(
							'notification' => '<span class="text-info">'.$plan_name.'</span>',
							'type' => 'offline_request',	
							'type_id' => $this->input->post('plan_id'),	
							'from_id' => $this->session->userdata('saas_id'),
							'to_id' => $saas_admin->user_id,	
						);
						$this->notifications_model->create($notification_data);
					}

					$this->session->set_flashdata('message', $this->lang->line('offline_bank_transfer_request_sent_successfully')?$this->lang->line('offline_bank_transfer_request_sent_successfully'):"Offline / Bank Transfer request sent successfully.");
					$this->session->set_flashdata('message_type', 'success');

					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('offline_bank_transfer_request_sent_successfully')?htmlspecialchars($this->lang->line('offline_bank_transfer_request_sent_successfully')):"Offline / Bank Transfer request sent successfully.";
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


	public function order_completed()
	{
		if ($this->ion_auth->logged_in() && ($this->ion_auth->is_admin() || $this->ion_auth->in_group(3)))
		{
			$this->form_validation->set_rules('status', 'Status', 'trim|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('plan_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');

			$plan = $this->plans_model->get_plans($this->input->post('plan_id'));
			if($this->form_validation->run() == TRUE && $plan){
				if($plan[0]['price'] > 0){
					$transaction_data = array(
						'saas_id' => $this->session->userdata('saas_id'),			
						'amount' => $plan[0]['price'],		
						'status' => $this->input->post('status')?$this->input->post('status'):0,		
					);

					$transaction_id = $this->plans_model->create_transaction($transaction_data);

					$taxes = $this->settings_model->get_taxes();
					if($taxes){
						$tax_pec = 0;
						$tax_arry = array();
						foreach($taxes as $key => $tax){ 
						  $tax_pec = $tax_pec+$tax['tax']; 
						  $tax_arry[$key]['tax_name'] = $tax['title'];
						  $tax_arry[$key]['tax_per'] = $tax['tax'];
						  $tax_arry[$key]['tax_amount'] = $plan[0]['price']*$tax['tax']/100;
						} 
					}
					$tax_amount = $plan[0]['price']*$tax_pec/100;
					$total_amount_with_tax = $plan[0]['price']+$tax_amount;

					$order_data = array(
						'saas_id' => $this->session->userdata('saas_id'),		
						'plan_id' => $this->input->post('plan_id'),		
						'amount' => $plan[0]['price'],		
						'amount_with_tax' => $total_amount_with_tax,		
						'tax' => json_encode($tax_arry),	
						'transaction_id' => $transaction_id,			
					);
					$order_id = $this->plans_model->create_order($order_data);
				}
				
				$dt = strtotime(date("Y-m-d"));
				if($plan[0]['billing_type'] == "One Time"){
					$date = NULL;
				}elseif($plan[0]['billing_type'] == "Monthly"){
					$date = date("Y-m-d", strtotime("+1 month", $dt));
				}elseif($plan[0]['billing_type'] == "Yearly"){
					$date = date("Y-m-d", strtotime("+1 year", $dt));
				}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
					$date = date("Y-m-d", strtotime("+3 days", $dt));
				}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
					$date = date("Y-m-d", strtotime("+7 days", $dt));
				}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
					$date = date("Y-m-d", strtotime("+15 days", $dt));
				}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
					$date = date("Y-m-d", strtotime("+1 month", $dt));
				}else{
					$date = date("Y-m-d", strtotime("+3 days", $dt));
				}

				$my_plan = get_current_plan();
				if($my_plan){
					if($my_plan['expired'] == 1 && $my_plan['plan_id'] == $this->input->post('plan_id')){
						if($my_plan['plan_id'] == 1){
							$date = date("Y-m-d", strtotime("+3 days", $dt));
							if($plan[0]['billing_type'] == "One Time"){
								$date = NULL;
							}
						}else{
							
							if(empty($my_plan['end_date'])){
								$dt = strtotime(date("Y-m-d"));
							}else{
								$dt = strtotime($my_plan['end_date']);
							}

							if($plan[0]['billing_type'] == "One Time"){
								$date = NULL;
							}elseif($plan[0]['billing_type'] == "Monthly"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}elseif($plan[0]['billing_type'] == "Yearly"){
								$date = date("Y-m-d", strtotime("+1 year", $dt));
							}elseif($plan[0]['billing_type'] == "three_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+3 days", $dt));
							}elseif($plan[0]['billing_type'] == "seven_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+7 days", $dt));
							}elseif($plan[0]['billing_type'] == "fifteen_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+15 days", $dt));
							}elseif($plan[0]['billing_type'] == "thirty_days_trial_plan"){
								$date = date("Y-m-d", strtotime("+1 month", $dt));
							}else{
								$date = date("Y-m-d", strtotime("+3 days", $dt));
							}
						}
					}
					$users_plans_data = array(
						'plan_id' => $this->input->post('plan_id'),		
						'expired' => 1,		
						'start_date' => date("Y-m-d"),			
						'end_date' => $date,			
					);
					$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
				}else{
					$users_plans_data = array(	
						'expired' => 1,				
						'plan_id' => $this->input->post('plan_id'),		
						'start_date' => date("Y-m-d"),			
						'end_date' => $date,			
					);

					$users_plans_id = $this->plans_model->update_users_plans($this->session->userdata('saas_id'), $users_plans_data);
				}
				
				if($users_plans_id){
					
					// notification to the saas admins
					$saas_admins = $this->ion_auth->users(array(3))->result();
					foreach($saas_admins as $saas_admin){
						$data = array(
							'notification' => '<span class="text-info">'.$plan[0]['title'].'</span>',
							'type' => 'new_plan',	
							'type_id' => $this->input->post('plan_id'),	
							'from_id' => $this->session->userdata('saas_id'),
							'to_id' => $saas_admin->user_id,	
						);
						$notification_id = $this->notifications_model->create($data);
					}

					$this->session->set_flashdata('message', $this->lang->line('plan_subscribed_successfully')?$this->lang->line('plan_subscribed_successfully'):"Plan subscribed successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('plan_subscribed_successfully')?$this->lang->line('plan_subscribed_successfully'):"Plan subscribed successfully.";
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

	public function delete($id='')
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{

			if(empty($id)){
				$id = $this->uri->segment(4)?$this->uri->segment(4):'';
			}
			
			if(!empty($id) && is_numeric($id) && $this->plans_model->delete($id)){
				$this->plans_model->delete_plan_update_users_plan($id);
				$this->notifications_model->delete('', 'new_plan', $id);
				$this->notifications_model->delete('', 'offline_request', $id);
				$this->session->set_flashdata('message', $this->lang->line('plan_deleted_successfully')?$this->lang->line('plan_deleted_successfully'):"Plan deleted successfully.");
				$this->session->set_flashdata('message_type', 'success');

				$this->data['error'] = false;
				$this->data['message'] = $this->lang->line('plan_deleted_successfully')?$this->lang->line('plan_deleted_successfully'):"Plan deleted successfully.";
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

	public function validate($plan_id = '')
	{	
		if(empty($plan_id)){
			$plan_id = $this->uri->segment(3)?$this->uri->segment(3):'';
		}
		
		$plan = $this->plans_model->get_plans($plan_id);

		if(!empty($plan_id) && is_numeric($plan_id) && $plan){
			$this->data['validationError'] = false;
			$this->data['plan'] = $plan;
			$this->data['message'] = "Successfully.";
			echo json_encode($this->data);
		}else{
			$this->data['validationError'] = true;
			$this->data['message'] = "Unsuccessfully.";
			echo json_encode($this->data);
		}
		
	}

	public function edit()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('update_id', 'Plan ID', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('title', 'Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('price', 'Price', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('cards_count', 'vCards', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('custom_fields_count', 'custom fields', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('products_services_count', 'products services', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('portfolio_count', 'portfolio', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('testimonials_count', 'testimonials', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('gallery_count', 'gallery', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('custom_sections_count', 'custom sections', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('team_member_count', 'team member', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('billing_type', 'Billing Type', 'trim|required|strip_tags|xss_clean');
			
			if($this->form_validation->run() == TRUE){

				$modules['select_all'] = $this->input->post('select_all')?1:0;
				$modules['custom_fields'] = $this->input->post('custom_fields')?1:0;
				$modules['products_services'] = $this->input->post('products_services')?1:0;
				$modules['portfolio'] = $this->input->post('portfolio')?1:0;
				$modules['testimonials'] = $this->input->post('testimonials')?1:0;
				$modules['gallery'] = $this->input->post('gallery')?1:0;
				$modules['custom_sections'] = $this->input->post('custom_sections')?1:0;
				$modules['team_member'] = $this->input->post('team_member')?1:0;
				$modules['qr_code'] = $this->input->post('qr_code')?1:0;
				$modules['hide_branding'] = $this->input->post('hide_branding')?1:0;
				$modules['enquiry_form'] = $this->input->post('enquiry_form')?1:0;
				$modules['support'] = $this->input->post('support')?1:0;
				$modules['ads'] = $this->input->post('ads')?1:0;
				$modules['custom_js_css'] = $this->input->post('custom_js_css')?1:0;
				$modules['search_engine_indexing'] = $this->input->post('search_engine_indexing')?1:0;
				$modules['multiple_themes'] = $this->input->post('multiple_themes')?1:0;
				$modules['custom_domain'] = $this->input->post('custom_domain')?1:0;
				$modules['custom_card_url'] = $this->input->post('custom_card_url')?1:0;

				$data = array(
					'title' => $this->input->post('title'),		
					'price' => $this->input->post('price')<0?0:$this->input->post('price'),		
					'billing_type' => $this->input->post('billing_type'),		
					'cards' => $this->input->post('cards_count'),		
					'custom_fields' => $this->input->post('custom_fields_count'),		
					'products_services' => $this->input->post('products_services_count'),		
					'portfolio' => $this->input->post('portfolio_count'),		
					'testimonials' => $this->input->post('testimonials_count'),		
					'gallery' => $this->input->post('gallery_count'),		
					'custom_sections' => $this->input->post('custom_sections_count'),	
					'team_member' => $this->input->post('team_member_count'),	
					'modules' => json_encode($modules),		
				);

				if($this->plans_model->edit($this->input->post('update_id'), $data)){
					$this->session->set_flashdata('message', $this->lang->line('plan_updated_successfully')?$this->lang->line('plan_updated_successfully'):"Plan updated successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('plan_updated_successfully')?$this->lang->line('plan_updated_successfully'):"Plan updated successfully.";
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

	public function create()
	{
		if ($this->ion_auth->logged_in() && $this->ion_auth->in_group(3))
		{
			$this->form_validation->set_rules('title', 'Title', 'trim|required|strip_tags|xss_clean');
			$this->form_validation->set_rules('price', 'Price', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('cards_count', 'vCards', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('custom_fields_count', 'custom fields', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('products_services_count', 'products services', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('portfolio_count', 'portfolio', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('testimonials_count', 'testimonials', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('gallery_count', 'gallery', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('custom_sections_count', 'custom sections', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('team_member_count', 'team member', 'trim|required|strip_tags|xss_clean|is_numeric');
			$this->form_validation->set_rules('billing_type', 'Billing Type', 'trim|required|strip_tags|xss_clean');

			if($this->form_validation->run() == TRUE){
				
				
				$modules['select_all'] = $this->input->post('select_all')?1:0;
				$modules['custom_fields'] = $this->input->post('custom_fields')?1:0;
				$modules['products_services'] = $this->input->post('products_services')?1:0;
				$modules['portfolio'] = $this->input->post('portfolio')?1:0;
				$modules['testimonials'] = $this->input->post('testimonials')?1:0;
				$modules['gallery'] = $this->input->post('gallery')?1:0;
				$modules['custom_sections'] = $this->input->post('custom_sections')?1:0;
				$modules['team_member'] = $this->input->post('team_member')?1:0;
				$modules['qr_code'] = $this->input->post('qr_code')?1:0;
				$modules['hide_branding'] = $this->input->post('hide_branding')?1:0;
				$modules['enquiry_form'] = $this->input->post('enquiry_form')?1:0;
				$modules['support'] = $this->input->post('support')?1:0;
				$modules['ads'] = $this->input->post('ads')?1:0;
				$modules['custom_js_css'] = $this->input->post('custom_js_css')?1:0;
				$modules['search_engine_indexing'] = $this->input->post('search_engine_indexing')?1:0;
				$modules['multiple_themes'] = $this->input->post('multiple_themes')?1:0;
				$modules['custom_domain'] = $this->input->post('custom_domain')?1:0;
				$modules['custom_card_url'] = $this->input->post('custom_card_url')?1:0;

				$data = array(
					'title' => $this->input->post('title'),		
					'price' => $this->input->post('price')<0?0:$this->input->post('price'),		
					'billing_type' => $this->input->post('billing_type'),		
					'cards' => $this->input->post('cards_count'),		
					'custom_fields' => $this->input->post('custom_fields_count'),		
					'products_services' => $this->input->post('products_services_count'),		
					'portfolio' => $this->input->post('portfolio_count'),		
					'testimonials' => $this->input->post('testimonials_count'),		
					'gallery' => $this->input->post('gallery_count'),		
					'custom_sections' => $this->input->post('custom_sections_count'),		
					'team_member' => $this->input->post('team_member_count'),		
					'modules' => json_encode($modules),		
				);

				$plan_id = $this->plans_model->create($data);
				
				if($plan_id){
					$this->session->set_flashdata('message', $this->lang->line('plan_created_successfully')?$this->lang->line('plan_created_successfully'):"Plan created successfully.");
					$this->session->set_flashdata('message_type', 'success');
					$this->data['error'] = false;
					$this->data['message'] = $this->lang->line('plan_created_successfully')?$this->lang->line('plan_created_successfully'):"Plan created successfully.";
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

	public function get_plans($plan_id = '')
	{
		if ($this->ion_auth->logged_in())
		{
			$plans = $this->plans_model->get_plans($plan_id);
			if($plans){
				foreach($plans as $key => $plan){
					$temp[$key] = $plan;

					if($plan["billing_type"] == 'One Time'){
						$temp[$key]['billing_type'] = $this->lang->line('one_time')?$this->lang->line('one_time'):'One Time';
					}elseif($plan["billing_type"] == 'Monthly'){
						$temp[$key]['billing_type'] = $this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly';
					}elseif($plan["billing_type"] == 'three_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan';
					}elseif($plan["billing_type"] == 'seven_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan';
					}elseif($plan["billing_type"] == 'fifteen_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan';
					}elseif($plan["billing_type"] == 'thirty_days_trial_plan'){
						$temp[$key]['billing_type'] = $this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan';
					}else{
						$temp[$key]['billing_type'] = $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
					}

					if($plan["cards"] > 0){
						$cards_count = $plan["cards"];
					}else{
						$cards_count = $this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited';
					}

					$modules = '';
					if($plan["modules"] != ''){
						foreach(json_decode($plan["modules"]) as $mod_key => $mod){
							$mod_name = '';

							if($mod_key == 'products_services'){
								$mod_name = (($mod == 1)?(($plan["products_services"] > 0)?$plan["products_services"]:($this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited')):'').' '.($this->lang->line('products_services')?$this->lang->line('products_services'):'Products and Services');
							}elseif($mod_key == 'portfolio'){
								$mod_name = (($mod == 1)?(($plan["portfolio"] > 0)?$plan["portfolio"]:($this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited')):'').' '.($this->lang->line('portfolio')?$this->lang->line('portfolio'):'Portfolio');
							}elseif($mod_key == 'testimonials'){
								$mod_name = (($mod == 1)?(($plan["testimonials"] > 0)?$plan["testimonials"]:($this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited')):'').' '.($this->lang->line('testimonials')?$this->lang->line('testimonials'):'Testimonials');
							}elseif($mod_key == 'gallery'){
								$mod_name = (($mod == 1)?(($plan["gallery"] > 0)?$plan["gallery"]:($this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited')):'').' '.($this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery');
							}elseif($mod_key == 'custom_sections'){
								$mod_name = (($mod == 1)?(($plan["custom_sections"] > 0)?$plan["custom_sections"]:($this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited')):'').' '.($this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections');
							}elseif($mod_key == 'custom_fields'){
								$mod_name = (($mod == 1)?(($plan["custom_fields"] > 0)?$plan["custom_fields"]:($this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited')):'').' '.($this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact').'/'.($this->lang->line('custom_fields')?$this->lang->line('custom_fields'):'Custom Fields');
							}elseif($mod_key == 'team_member'){
								$mod_name = (($mod == 1)?(($plan["team_member"] > 0)?$plan["team_member"]:($this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited')):'').' '.($this->lang->line('team_member')?$this->lang->line('team_member'):'Team Member');
							}elseif($mod_key == 'qr_code'){
								$mod_name = $this->lang->line('qr_code')?$this->lang->line('qr_code'):'QR Code';
							}elseif($mod_key == 'hide_branding'){
								$mod_name = $this->lang->line('hide_branding')?$this->lang->line('hide_branding'):'Hide Branding';
							}elseif($mod_key == 'enquiry_form'){
								$mod_name = $this->lang->line('enquiry_form')?htmlspecialchars($this->lang->line('enquiry_form')):'Enquiry Form';
							}elseif($mod_key == 'support'){
								$mod_name = $this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support';
							}elseif($mod_key == 'ads'){
								$mod_name = $this->lang->line('no_ads')?htmlspecialchars($this->lang->line('no_ads')):'No Ads';
							}elseif($mod_key == 'custom_js_css'){
								$mod_name = $this->lang->line('custom_js_css')?htmlspecialchars($this->lang->line('custom_js_css')):'Custom JS, CSS';
							}elseif($mod_key == 'search_engine_indexing'){
								$mod_name = $this->lang->line('search_engine_indexing')?htmlspecialchars($this->lang->line('search_engine_indexing')):'Search Engine Indexing';
							}elseif($mod_key == 'multiple_themes'){
								$mod_name = $this->lang->line('multiple_themes')?$this->lang->line('multiple_themes'):'Multiple Themes';
							}elseif($mod_key == 'custom_domain' && !turn_off_custom_domain_system()){
								$mod_name = $this->lang->line('custom_domain')?$this->lang->line('custom_domain'):'Custom Domain';
							}elseif($mod_key == 'custom_card_url'){
								$mod_name = $this->lang->line('custom_card_url')?$this->lang->line('custom_card_url'):'Custom Card URL';
							}

							if($mod_name && $mod == 1){
								$modules .= '<div class="pricing-item d-inline-flex mb-1 mr-2">
												<div class="pricing-item-icon mr-1"><i class="fas fa-check"></i></div>
												<div class="pricing-item-label">'.$mod_name.'</div>
											</div>';
							}elseif($mod_name){
								$modules .= '<div class="pricing-item d-inline-flex mb-1 mr-2">
												<div class="pricing-item-icon bg-danger text-white mr-1"><i class="fas fa-times"></i></div>
												<div class="pricing-item-label">'.$mod_name.'</div>
											</div>';
							}
						}
					}
					$temp[$key]['modules'] = '<div class="pricing bg-transparent shadow-none m-1">
												<div class="pricing-details">
													<div class="pricing-item d-inline-flex mb-1 mr-2">
														<div class="pricing-item-icon mr-1"><i class="fas fa-check"></i></div>
														<div class="pricing-item-label">'.$cards_count.' '.($this->lang->line('vcard')?htmlspecialchars($this->lang->line('vcard')):'vCard').'</div>
													</div>
													'.$modules.'
												</div>
											</div>';

					$temp[$key]['action'] = '<span class="d-flex"><a href="#" class="btn btn-icon btn-sm btn-success modal-edit-plan mr-1" data-id="'.$plan["id"].'" data-toggle="tooltip" title="'.($this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit').'"><i class="fas fa-pen"></i></a><a href="#" class="btn btn-icon btn-sm btn-danger delete_plan" data-id="'.$plan["id"].'" data-toggle="tooltip" title="'.($this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete').'"><i class="fas fa-trash"></i></a></span>';
				}

				return print_r(json_encode($temp));
			}else{
				return '';
			}
		}else{
			return '';
		}
	}

	public function ajax_get_plan_by_id($id='')
	{	
		$id = !empty($id)?$id:$this->input->post('id');
		if ($this->ion_auth->logged_in() && !empty($id) && is_numeric($id))
		{
			$plans = $this->plans_model->get_plans($id);
			if(!empty($plans)){
				$this->data['error'] = false;
				$this->data['data'] = $plans;
				$this->data['message'] = 'Successful';
				echo json_encode($this->data);
			}else{
				$this->data['error'] = true;
				$this->data['message'] = 'No user found.';
				echo json_encode($this->data);
			}
		}else{
			$this->data['error'] = true;
			$this->data['message'] = $this->lang->line('access_denied')?$this->lang->line('access_denied'):"Access Denied";
			echo json_encode($this->data);
		}
	}

}







