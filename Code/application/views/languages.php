<?php $this->load->view('includes/head'); ?>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
        <div class="main-content">
          <section class="section">
            <div class="section-header">
              <div class="section-header-back">
                <a href="javascript:history.go(-1)" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
              </div>
              <h1><?=$this->lang->line('edit_language')?htmlspecialchars($this->lang->line('edit_language')):'Edit Language'?></h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?htmlspecialchars($this->lang->line('dashboard')):'Dashboard'?></a></div>
                <div class="breadcrumb-item active"><a href="<?=base_url('languages')?>"><?=$this->lang->line('languages')?htmlspecialchars($this->lang->line('languages')):'Languages'?></a></div>
                <div class="breadcrumb-item"><?=$this->lang->line('edit_language')?htmlspecialchars($this->lang->line('edit_language')):'Edit Language'?></div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">


                <div class="col-md-3">
                    <div class="card card-primary">
                        <div class="card-body">
                            <ul class="nav nav-pills flex-column">
                                <?php 
                                $current_lang = get_languages('', $this->uri->segment(3));
                                foreach($languages as $kay => $lan){ ?>
                                <li class="nav-item">
                                    <a class="nav-link <?=$lan['language']==$this->uri->segment(3)?'active':''?>" href="<?=base_url('languages/editing/'.$lan['language'])?>"><?=ucfirst(htmlspecialchars($lan['language']))?></a>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>



                <div class="col-md-9">
                    <div class="card card-primary" id="language-card">
                        <form action="<?=base_url('languages/edit')?>" method="POST" id="language-form">
                            <div class="card-header">
                              <h4><?=$this->lang->line('editing')?htmlspecialchars($this->lang->line('editing')):'Editing'?> <?=ucfirst(htmlspecialchars($this->uri->segment(3)))?></h4>
                            </div>
                            <div class="card-body row">

                                <div class="form-group col-md-4">
                                <label><?=$this->lang->line('language_name')?$this->lang->line('language_name'):'Language name'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')?htmlspecialchars($this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')):"Don't edit it if you don't want to edit language name."?>"></i></label>
                                    <input type="text" name="language_lang" value="<?=ucfirst(htmlspecialchars($this->uri->segment(3)))?>" class="form-control">
                                    <input type="hidden" name="update_lang" value="<?=htmlspecialchars($this->uri->segment(3))?>" class="form-control">
                                </div>
                                <div class="form-group col-md-2">
                                    <label><?=$this->lang->line('short_code')?$this->lang->line('short_code'):'Short Code'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')?htmlspecialchars($this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')):"Don't edit it if you don't want to edit language name."?>"></i></label>
                                    <input type="text" name="short_code_lang" value="<?=isset($current_lang[0]['short_code'])?htmlspecialchars($current_lang[0]['short_code']):''?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-3">
                                    <label><?=$this->lang->line('is_rtl')?htmlspecialchars($this->lang->line('is_rtl')):'Is RTL'?></label>
                                    <select name="active_lang" class="form-control">
                                        <option value="0" <?=(isset($current_lang[0]['active']) && $current_lang[0]['active']==0)?'selected':''?>>NO RTL</option>
                                        <option value="1" <?=(isset($current_lang[0]['active']) && $current_lang[0]['active']==1)?'selected':''?>>RTL</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-3">
                                    <label><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></label>
                                    <select name="status_lang" class="form-control">
                                        <option value="0" <?=(isset($current_lang[0]['status']) && $current_lang[0]['status']==0)?'selected':''?>><?=$this->lang->line('deactive')?htmlspecialchars($this->lang->line('deactive')):'Deactive'?></option>
                                        <option value="1" <?=(isset($current_lang[0]['status']) && $current_lang[0]['status']==1)?'selected':''?>><?=$this->lang->line('active')?htmlspecialchars($this->lang->line('active')):'Active'?></option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Dashboard</label>
                                    <input type="text" name="dashboard" value="<?=$this->lang->line('dashboard')?htmlspecialchars($this->lang->line('dashboard')):'Dashboard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Editing</label>
                                    <input type="text" name="editing" value="<?=$this->lang->line('editing')?htmlspecialchars($this->lang->line('editing')):'Editing'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pages</label>
                                    <input type="text" name="pages" value="<?=$this->lang->line('pages')?htmlspecialchars($this->lang->line('pages')):'Pages'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Page Title</label>
                                    <input type="text" name="pages_title" value="<?=$this->lang->line('pages_title')?htmlspecialchars($this->lang->line('pages_title')):'Page Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Page Content</label>
                                    <input type="text" name="pages_content" value="<?=$this->lang->line('pages_content')?htmlspecialchars($this->lang->line('pages_content')):'Page Content'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Currency Symbol</label>
                                    <input type="text" name="currency_symbol" value="<?=$this->lang->line('currency_symbol')?htmlspecialchars($this->lang->line('currency_symbol')):'Currency Symbol'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Currency Code</label>
                                    <input type="text" name="currency_code" value="<?=$this->lang->line('currency_code')?htmlspecialchars($this->lang->line('currency_code')):'Currency Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit</label>
                                    <input type="text" name="edit" value="<?=$this->lang->line('edit')?htmlspecialchars($this->lang->line('edit')):'Edit'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Enable OR Disable Sections</label>
                                    <input type="text" name="enable_or_disable_sections" value="<?=$this->lang->line('enable_or_disable_sections')?htmlspecialchars($this->lang->line('enable_or_disable_sections')):'Enable OR Disable Sections'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Frontend Customization</label>
                                    <input type="text" name="frontend_customization" value="<?=$this->lang->line('frontend_customization')?htmlspecialchars($this->lang->line('frontend_customization')):'Frontend Customization'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Contact Form</label>
                                    <input type="text" name="contact_form" value="<?=$this->lang->line('contact_form')?htmlspecialchars($this->lang->line('contact_form')):'Contact Form'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Plan</label>
                                    <input type="text" name="plan" value="<?=$this->lang->line('plan')?htmlspecialchars($this->lang->line('plan')):'Plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>All</label>
                                    <input type="text" name="all" value="<?=$this->lang->line('all')?htmlspecialchars($this->lang->line('all')):'All'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Total</label>
                                    <input type="text" name="total" value="<?=$this->lang->line('total')?htmlspecialchars($this->lang->line('total')):'Total'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Completed</label>
                                    <input type="text" name="completed" value="<?=$this->lang->line('completed')?htmlspecialchars($this->lang->line('completed')):'Completed'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Rejected</label>
                                    <input type="text" name="rejected" value="<?=$this->lang->line('rejected')?htmlspecialchars($this->lang->line('rejected')):'Rejected'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pending</label>
                                    <input type="text" name="pending" value="<?=$this->lang->line('pending')?htmlspecialchars($this->lang->line('pending')):'Pending'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Accepted</label>
                                    <input type="text" name="accepted" value="<?=$this->lang->line('accepted')?htmlspecialchars($this->lang->line('accepted')):'Accepted'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Get Start</label>
                                    <input type="text" name="get_start" value="<?=$this->lang->line('get_start')?htmlspecialchars($this->lang->line('get_start')):'Get Start'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Monthly</label>
                                    <input type="text" name="monthly" value="<?=$this->lang->line('monthly')?htmlspecialchars($this->lang->line('monthly')):'Monthly'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Yearly</label>
                                    <input type="text" name="yearly" value="<?=$this->lang->line('yearly')?htmlspecialchars($this->lang->line('yearly')):'Yearly'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User</label>
                                    <input type="text" name="user" value="<?=$this->lang->line('user')?htmlspecialchars($this->lang->line('user')):'User'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Name</label>
                                    <input type="text" name="name" value="<?=$this->lang->line('name')?htmlspecialchars($this->lang->line('name')):'Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="text" name="email" value="<?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Payment Gateway</label>
                                    <input type="text" name="payment_gateway" value="<?=$this->lang->line('payment_gateway')?htmlspecialchars($this->lang->line('payment_gateway')):'Payment Gateway'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Type your message</label>
                                    <input type="text" name="type_your_message" value="<?=$this->lang->line('type_your_message')?htmlspecialchars($this->lang->line('type_your_message')):'Type your message'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Send Message</label>
                                    <input type="text" name="send_message" value="<?=$this->lang->line('send_message')?htmlspecialchars($this->lang->line('send_message')):'Send Message'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Login</label>
                                    <input type="text" name="login" value="<?=$this->lang->line('login')?htmlspecialchars($this->lang->line('login')):'Login'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Send</label>
                                    <input type="text" name="send" value="<?=$this->lang->line('send')?htmlspecialchars($this->lang->line('send')):'Send'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Profile</label>
                                    <input type="text" name="profile" value="<?=$this->lang->line('profile')?htmlspecialchars($this->lang->line('profile')):'Profile'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password</label>
                                    <input type="text" name="password" value="<?=$this->lang->line('password')?htmlspecialchars($this->lang->line('password')):'Password'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Encryption</label>
                                    <input type="text" name="encryption" value="<?=$this->lang->line('encryption')?htmlspecialchars($this->lang->line('encryption')):'Encryption'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Send test mail to</label>
                                    <input type="text" name="send_test_mail_to" value="<?=$this->lang->line('send_test_mail_to')?htmlspecialchars($this->lang->line('send_test_mail_to')):'Send test mail to'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirm Password</label>
                                    <input type="text" name="confirm_password" value="<?=$this->lang->line('confirm_password')?htmlspecialchars($this->lang->line('confirm_password')):'Confirm Password'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Forgot Password</label>
                                    <input type="text" name="forgot_password" value="<?=$this->lang->line('forgot_password')?htmlspecialchars($this->lang->line('forgot_password')):'Forgot Password'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Remember Me</label>
                                    <input type="text" name="remember_me" value="<?=$this->lang->line('remember_me')?htmlspecialchars($this->lang->line('remember_me')):'Remember Me'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Don't have an account?</label>
                                    <input type="text" name="dont_have_an_account" value="<?=$this->lang->line('dont_have_an_account')?htmlspecialchars($this->lang->line('dont_have_an_account')):"Don't have an account?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create One</label>
                                    <input type="text" name="create_one" value="<?=$this->lang->line('create_one')?htmlspecialchars($this->lang->line('create_one')):'Create One'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Register</label>
                                    <input type="text" name="register" value="<?=$this->lang->line('register')?htmlspecialchars($this->lang->line('register')):'Register'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>First Name</label>
                                    <input type="text" name="first_name" value="<?=$this->lang->line('first_name')?htmlspecialchars($this->lang->line('first_name')):'First Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" value="<?=$this->lang->line('last_name')?htmlspecialchars($this->lang->line('last_name')):'Last Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Password Confirmation</label>
                                    <input type="text" name="password_confirmation" value="<?=$this->lang->line('password_confirmation')?htmlspecialchars($this->lang->line('password_confirmation')):'Password Confirmation'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Already have an account?</label>
                                    <input type="text" name="already_have_an_account" value="<?=$this->lang->line('already_have_an_account')?htmlspecialchars($this->lang->line('already_have_an_account')):'Already have an account?'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Login Here</label>
                                    <input type="text" name="login_here" value="<?=$this->lang->line('login_here')?htmlspecialchars($this->lang->line('login_here')):'Login Here'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Notifications</label>
                                    <input type="text" name="notifications" value="<?=$this->lang->line('notifications')?htmlspecialchars($this->lang->line('notifications')):'Notifications'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User Statistics</label>
                                    <input type="text" name="user_statistics" value="<?=$this->lang->line('user_statistics')?htmlspecialchars($this->lang->line('user_statistics')):'User Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No new notifications</label>
                                    <input type="text" name="no_new_notifications" value="<?=$this->lang->line('no_new_notifications')?htmlspecialchars($this->lang->line('no_new_notifications')):'No new notifications.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>View All</label>
                                    <input type="text" name="view_all" value="<?=$this->lang->line('view_all')?htmlspecialchars($this->lang->line('view_all')):'View All'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Orders</label>
                                    <input type="text" name="orders" value="<?=$this->lang->line('orders')?htmlspecialchars($this->lang->line('orders')):'Orders'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>offline / bank transfer requests</label>
                                    <input type="text" name="offline_requests" value="<?=$this->lang->line('offline_requests')?htmlspecialchars($this->lang->line('offline_requests')):'offline / bank transfer requests'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Transactions</label>
                                    <input type="text" name="transactions" value="<?=$this->lang->line('transactions')?htmlspecialchars($this->lang->line('transactions')):'Transactions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Transaction</label>
                                    <input type="text" name="transaction" value="<?=$this->lang->line('transaction')?htmlspecialchars($this->lang->line('transaction')):'Transaction'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SaaS Admins</label>
                                    <input type="text" name="saas_admins" value="<?=$this->lang->line('saas_admins')?htmlspecialchars($this->lang->line('saas_admins')):'SaaS Admins'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Frontend</label>
                                    <input type="text" name="frontend" value="<?=$this->lang->line('frontend')?htmlspecialchars($this->lang->line('frontend')):'Frontend'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Settings</label>
                                    <input type="text" name="settings" value="<?=$this->lang->line('settings')?htmlspecialchars($this->lang->line('settings')):'Settings'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>General</label>
                                    <input type="text" name="general" value="<?=$this->lang->line('general')?htmlspecialchars($this->lang->line('general')):'General'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Languages</label>
                                    <input type="text" name="languages" value="<?=$this->lang->line('languages')?htmlspecialchars($this->lang->line('languages')):'Languages'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit Language</label>
                                    <input type="text" name="edit_language" value="<?=$this->lang->line('edit_language')?htmlspecialchars($this->lang->line('edit_language')):'Edit Language'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Update</label>
                                    <input type="text" name="update" value="<?=$this->lang->line('update')?htmlspecialchars($this->lang->line('update')):'Update'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Delete</label>
                                    <input type="text" name="delete" value="<?=$this->lang->line('delete')?htmlspecialchars($this->lang->line('delete')):'Delete'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google Analytics</label>
                                    <input type="text" name="google_analytics" value="<?=$this->lang->line('google_analytics')?htmlspecialchars($this->lang->line('google_analytics')):'Google Analytics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Footer Text</label>
                                    <input type="text" name="footer_text" value="<?=$this->lang->line('footer_text')?htmlspecialchars($this->lang->line('footer_text')):'Footer Text'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Company Name</label>
                                    <input type="text" name="company_name" value="<?=$this->lang->line('company_name')?htmlspecialchars($this->lang->line('company_name')):'Company Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Earnings</label>
                                    <input type="text" name="earnings" value="<?=$this->lang->line('earnings')?htmlspecialchars($this->lang->line('earnings')):'Earnings'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Last days earning</label>
                                    <input type="text" name="last_days_earning" value="<?=$this->lang->line('last_days_earning')?htmlspecialchars($this->lang->line('last_days_earning')):'Last 30 days earning'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Active</label>
                                    <input type="text" name="active" value="<?=$this->lang->line('active')?htmlspecialchars($this->lang->line('active')):'Active'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Deactive</label>
                                    <input type="text" name="deactive" value="<?=$this->lang->line('deactive')?htmlspecialchars($this->lang->line('deactive')):'Deactive'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit User</label>
                                    <input type="text" name="edit_user" value="<?=$this->lang->line('edit_user')?htmlspecialchars($this->lang->line('edit_user')):'Edit User'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User Profile</label>
                                    <input type="text" name="user_profile" value="<?=$this->lang->line('user_profile')?htmlspecialchars($this->lang->line('user_profile')):'User Profile'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Free</label>
                                    <input type="text" name="free" value="<?=$this->lang->line('free')?htmlspecialchars($this->lang->line('free')):'Free'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paid</label>
                                    <input type="text" name="paid" value="<?=$this->lang->line('paid')?htmlspecialchars($this->lang->line('paid')):'Paid'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Expired</label>
                                    <input type="text" name="expired" value="<?=$this->lang->line('expired')?htmlspecialchars($this->lang->line('expired')):'Expired'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Logout</label>
                                    <input type="text" name="logout" value="<?=$this->lang->line('logout')?htmlspecialchars($this->lang->line('logout')):'Logout'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Language name</label>
                                    <input type="text" name="language_name" value="<?=$this->lang->line('language_name')?htmlspecialchars($this->lang->line('language_name')):'Language name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create New User</label>
                                    <input type="text" name="create_new_user" value="<?=$this->lang->line('create_new_user')?htmlspecialchars($this->lang->line('create_new_user')):'Create New User'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create Feature</label>
                                    <input type="text" name="create_feature" value="<?=$this->lang->line('create_feature')?htmlspecialchars($this->lang->line('create_feature')):'Create Feature'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Billing Type</label>
                                    <input type="text" name="billing_type" value="<?=$this->lang->line('billing_type')?htmlspecialchars($this->lang->line('billing_type')):'Billing Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Date</label>
                                    <input type="text" name="date" value="<?=$this->lang->line('date')?htmlspecialchars($this->lang->line('date')):'Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Home</label>
                                    <input type="text" name="home" value="<?=$this->lang->line('home')?htmlspecialchars($this->lang->line('home')):'Home'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Expiring</label>
                                    <input type="text" name="expiring" value="<?=$this->lang->line('expiring')?htmlspecialchars($this->lang->line('expiring')):'Expiring'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Status</label>
                                    <input type="text" name="status" value="<?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Action</label>
                                    <input type="text" name="action" value="<?=$this->lang->line('action')?htmlspecialchars($this->lang->line('action')):'Action'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="<?=$this->lang->line('phone')?htmlspecialchars($this->lang->line('phone')):'Phone'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ending Date</label>
                                    <input type="text" name="ending_date" value="<?=$this->lang->line('ending_date')?htmlspecialchars($this->lang->line('ending_date')):'Ending Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Request Date</label>
                                    <input type="text" name="request_date" value="<?=$this->lang->line('request_date')?htmlspecialchars($this->lang->line('request_date')):'Request Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Alert...</label>
                                    <input type="text" name="alert" value="<?=$this->lang->line('alert')?htmlspecialchars($this->lang->line('alert')):'Alert...'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Renew it now.</label>
                                    <input type="text" name="renew_it_now" value="<?=$this->lang->line('renew_it_now')?htmlspecialchars($this->lang->line('renew_it_now')):'Renew it now.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Renew Plan.</label>
                                    <input type="text" name="renew_plan" value="<?=$this->lang->line('renew_plan')?htmlspecialchars($this->lang->line('renew_plan')):'Renew Plan.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upgrade</label>
                                    <input type="text" name="subscribe" value="<?=$this->lang->line('subscribe')?htmlspecialchars($this->lang->line('subscribe')):'Upgrade'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Plan Expiry Date</label>
                                    <input type="text" name="plan_expiry_date" value="<?=$this->lang->line('plan_expiry_date')?htmlspecialchars($this->lang->line('plan_expiry_date')):'Plan Expiry Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>User Plan</label>
                                    <input type="text" name="user_plan" value="<?=$this->lang->line('user_plan')?htmlspecialchars($this->lang->line('user_plan')):'User Plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Razorpay</label>
                                    <input type="text" name="razorpay" value="<?=$this->lang->line('razorpay')?htmlspecialchars($this->lang->line('razorpay')):'Razorpay'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Key ID</label>
                                    <input type="text" name="key_id" value="<?=$this->lang->line('key_id')?htmlspecialchars($this->lang->line('key_id')):'Key ID'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Key Secret</label>
                                    <input type="text" name="key_secret" value="<?=$this->lang->line('key_secret')?htmlspecialchars($this->lang->line('key_secret')):'Key Secret'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Stripe</label>
                                    <input type="text" name="stripe" value="<?=$this->lang->line('stripe')?htmlspecialchars($this->lang->line('stripe')):'Stripe'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Secret Key</label>
                                    <input type="text" name="secret_key" value="<?=$this->lang->line('secret_key')?htmlspecialchars($this->lang->line('secret_key')):'Secret Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Publishable Key</label>
                                    <input type="text" name="publishable_key" value="<?=$this->lang->line('publishable_key')?htmlspecialchars($this->lang->line('publishable_key')):'Publishable Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paypal</label>
                                    <input type="text" name="paypal" value="<?=$this->lang->line('paypal')?htmlspecialchars($this->lang->line('paypal')):'Paypal'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paypal Client ID</label>
                                    <input type="text" name="paypal_client_id" value="<?=$this->lang->line('paypal_client_id')?htmlspecialchars($this->lang->line('paypal_client_id')):'Paypal Client ID'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paypal Secret</label>
                                    <input type="text" name="paypal_secret" value="<?=$this->lang->line('paypal_secret')?htmlspecialchars($this->lang->line('paypal_secret')):'Paypal Secret'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paystack</label>
                                    <input type="text" name="paystack" value="<?=$this->lang->line('paystack')?htmlspecialchars($this->lang->line('paystack')):'Paystack'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paystack Public Key</label>
                                    <input type="text" name="paystack_public_key" value="<?=$this->lang->line('paystack_public_key')?htmlspecialchars($this->lang->line('paystack_public_key')):'Paystack Public Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Paystack Secret Key</label>
                                    <input type="text" name="paystack_secret_key" value="<?=$this->lang->line('paystack_secret_key')?htmlspecialchars($this->lang->line('paystack_secret_key')):'Paystack Secret Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Timezone</label>
                                    <input type="text" name="timezone" value="<?=$this->lang->line('timezone')?htmlspecialchars($this->lang->line('timezone')):'Timezone'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Username/Email</label>
                                    <input type="text" name="username_email" value="<?=$this->lang->line('username_email')?htmlspecialchars($this->lang->line('username_email')):'Username/Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Username</label>
                                    <input type="text" name="username" value="<?=$this->lang->line('username')?htmlspecialchars($this->lang->line('username')):'Username'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SMTP Port</label>
                                    <input type="text" name="smtp_port" value="<?=$this->lang->line('smtp_port')?htmlspecialchars($this->lang->line('smtp_port')):'SMTP Port'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SMTP Host</label>
                                    <input type="text" name="smtp_host" value="<?=$this->lang->line('smtp_host')?htmlspecialchars($this->lang->line('smtp_host')):'SMTP Host'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Favicon</label>
                                    <input type="text" name="favicon" value="<?=$this->lang->line('favicon')?htmlspecialchars($this->lang->line('favicon')):'Favicon'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Half Logo</label>
                                    <input type="text" name="half_logo" value="<?=$this->lang->line('half_logo')?htmlspecialchars($this->lang->line('half_logo')):'Half Logo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Full Logo</label>
                                    <input type="text" name="full_logo" value="<?=$this->lang->line('full_logo')?htmlspecialchars($this->lang->line('full_logo')):'Full Logo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Time Format</label>
                                    <input type="text" name="time_format" value="<?=$this->lang->line('time_format')?htmlspecialchars($this->lang->line('time_format')):'Time Format'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Date Format</label>
                                    <input type="text" name="date_format" value="<?=$this->lang->line('date_format')?htmlspecialchars($this->lang->line('date_format')):'Date Format'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit Feature</label>
                                    <input type="text" name="edit_feature" value="<?=$this->lang->line('edit_feature')?htmlspecialchars($this->lang->line('edit_feature')):'Edit Feature'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Save Changes</label>
                                    <input type="text" name="save_changes" value="<?=$this->lang->line('save_changes')?htmlspecialchars($this->lang->line('save_changes')):'Save Changes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Landing Page</label>
                                    <input type="text" name="landing_page" value="<?=$this->lang->line('landing_page')?htmlspecialchars($this->lang->line('landing_page')):'Landing Page'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Offline / Bank Transfer</label>
                                    <input type="text" name="offline_bank_transfer" value="<?=$this->lang->line('offline_bank_transfer')?htmlspecialchars($this->lang->line('offline_bank_transfer')):'Offline / Bank Transfer'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No Number</label>
                                    <input type="text" name="no_number" value="<?=$this->lang->line('no_number')?htmlspecialchars($this->lang->line('no_number')):'No Number'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Current System Version:</label>
                                    <input type="text" name="current_system_version" value="<?=$this->lang->line('current_system_version')?htmlspecialchars($this->lang->line('current_system_version')):'Current System Version:'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Update Guide</label>
                                    <input type="text" name="update_guide" value="<?=$this->lang->line('update_guide')?htmlspecialchars($this->lang->line('update_guide')):'Update Guide'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Install Update</label>
                                    <input type="text" name="install_update" value="<?=$this->lang->line('install_update')?htmlspecialchars($this->lang->line('install_update')):'Install Update'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Choose file</label>
                                    <input type="text" name="choose_file" value="<?=$this->lang->line('choose_file')?htmlspecialchars($this->lang->line('choose_file')):'Choose file'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Icon</label>
                                    <input type="text" name="icon" value="<?=$this->lang->line('icon')?htmlspecialchars($this->lang->line('icon')):'Icon'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Modules</label>
                                    <input type="text" name="modules" value="<?=$this->lang->line('modules')?htmlspecialchars($this->lang->line('modules')):'Modules'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select All</label>
                                    <input type="text" name="select_all" value="<?=$this->lang->line('select_all')?htmlspecialchars($this->lang->line('select_all')):'Select All'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>One Time</label>
                                    <input type="text" name="one_time" value="<?=$this->lang->line('one_time')?htmlspecialchars($this->lang->line('one_time')):'One Time'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Code</label>
                                    <input type="text" name="custom_code" value="<?=$this->lang->line('custom_code')?htmlspecialchars($this->lang->line('custom_code')):'Custom Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Header Code</label>
                                    <input type="text" name="header_code" value="<?=$this->lang->line('header_code')?htmlspecialchars($this->lang->line('header_code')):'Header Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Footer Code</label>
                                    <input type="text" name="footer_code" value="<?=$this->lang->line('footer_code')?htmlspecialchars($this->lang->line('footer_code')):'Footer Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Email Template</label>
                                    <input type="text" name="select_template" value="<?=$this->lang->line('select_template')?htmlspecialchars($this->lang->line('select_template')):'Select Email Template'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email Templates</label>
                                    <input type="text" name="email_templates" value="<?=$this->lang->line('email_templates')?htmlspecialchars($this->lang->line('email_templates')):'Email Templates'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email Subject</label>
                                    <input type="text" name="email_subject" value="<?=$this->lang->line('email_subject')?htmlspecialchars($this->lang->line('email_subject')):'Email Subject'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email Message</label>
                                    <input type="text" name="email_message" value="<?=$this->lang->line('email_message')?htmlspecialchars($this->lang->line('email_message')):'Email Message'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New user registration</label>
                                    <input type="text" name="new_user_registration" value="<?=$this->lang->line('new_user_registration')?htmlspecialchars($this->lang->line('new_user_registration')):'New user registration'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email verification</label>
                                    <input type="text" name="email_verification" value="<?=$this->lang->line('email_verification')?htmlspecialchars($this->lang->line('email_verification')):'Email verification'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Send email notification</label>
                                    <input type="text" name="send_email_notification" value="<?=$this->lang->line('send_email_notification')?htmlspecialchars($this->lang->line('send_email_notification')):'Send email notification'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>My vCard</label>
                                    <input type="text" name="my_card" value="<?=$this->lang->line('my_card')?htmlspecialchars($this->lang->line('my_card')):'My vCard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select vCard</label>
                                    <input type="text" name="select_vcard" value="<?=$this->lang->line('select_vcard')?htmlspecialchars($this->lang->line('select_vcard')):'Select vCard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>vCards</label>
                                    <input type="text" name="vcards" value="<?=$this->lang->line('vcards')?htmlspecialchars($this->lang->line('vcards')):'vCards'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>vCard</label>
                                    <input type="text" name="vcard" value="<?=$this->lang->line('vcard')?htmlspecialchars($this->lang->line('vcard')):'vCard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme</label>
                                    <input type="text" name="theme" value="<?=$this->lang->line('theme')?htmlspecialchars($this->lang->line('theme')):'Theme'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Contact Details</label>
                                    <input type="text" name="contact_details" value="<?=$this->lang->line('contact_details')?htmlspecialchars($this->lang->line('contact_details')):'Contact Details'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Color</label>
                                    <input type="text" name="color" value="<?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Gradient</label>
                                    <input type="text" name="gradient" value="<?=$this->lang->line('gradient')?htmlspecialchars($this->lang->line('gradient')):'Gradient'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Transparent</label>
                                    <input type="text" name="transparent" value="<?=$this->lang->line('transparent')?htmlspecialchars($this->lang->line('transparent')):'Transparent'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Background Type</label>
                                    <input type="text" name="theme_background_type" value="<?=$this->lang->line('theme_background_type')?htmlspecialchars($this->lang->line('theme_background_type')):'Theme Background Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Card Background Type</label>
                                    <input type="text" name="card_background_type" value="<?=$this->lang->line('card_background_type')?htmlspecialchars($this->lang->line('card_background_type')):'Card Background Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Profile Image</label>
                                    <input type="text" name="profile_image" value="<?=$this->lang->line('profile_image')?htmlspecialchars($this->lang->line('profile_image')):'Profile Image'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Banner Image</label>
                                    <input type="text" name="banner_image" value="<?=$this->lang->line('banner_image')?htmlspecialchars($this->lang->line('banner_image')):'Banner Image'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Image</label>
                                    <input type="text" name="image" value="<?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Mobile</label>
                                    <input type="text" name="mobile" value="<?=$this->lang->line('mobile')?htmlspecialchars($this->lang->line('mobile')):'Mobile'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email</label>
                                    <input type="text" name="email" value="<?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Website</label>
                                    <input type="text" name="website" value="<?=$this->lang->line('website')?htmlspecialchars($this->lang->line('website')):'Website'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Address</label>
                                    <input type="text" name="address" value="<?=$this->lang->line('address')?htmlspecialchars($this->lang->line('address')):'Address'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>Icon</label>
                                    <input type="text" name="icon" value="<?=$this->lang->line('icon')?htmlspecialchars($this->lang->line('icon')):'Icon'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Text</label>
                                    <input type="text" name="text" value="<?=$this->lang->line('text')?htmlspecialchars($this->lang->line('text')):'Text'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Add</label>
                                    <input type="text" name="add" value="<?=$this->lang->line('add')?htmlspecialchars($this->lang->line('add')):'Add'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Title</label>
                                    <input type="text" name="title" value="<?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sub Title</label>
                                    <input type="text" name="sub_title" value="<?=$this->lang->line('sub_title')?htmlspecialchars($this->lang->line('sub_title')):'Sub Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Short Description</label>
                                    <input type="text" name="short_description" value="<?=$this->lang->line('short_description')?htmlspecialchars($this->lang->line('short_description')):'Short Description'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Field</label>
                                    <input type="text" name="custom_field" value="<?=$this->lang->line('custom_field')?htmlspecialchars($this->lang->line('custom_field')):'Custom Field'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Not applicable to all themes.</label>
                                    <input type="text" name="not_applicable_to_all_themes" value="<?=$this->lang->line('not_applicable_to_all_themes')?htmlspecialchars($this->lang->line('not_applicable_to_all_themes')):'Not applicable to all themes.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Slug</label>
                                    <input type="text" name="slug" value="<?=$this->lang->line('slug')?htmlspecialchars($this->lang->line('slug')):'Slug'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Preview</label>
                                    <input type="text" name="preview" value="<?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Share</label>
                                    <input type="text" name="share" value="<?=$this->lang->line('share')?htmlspecialchars($this->lang->line('share')):'Share'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Share My vCard</label>
                                    <input type="text" name="share_my_vcard" value="<?=$this->lang->line('share_my_vcard')?htmlspecialchars($this->lang->line('share_my_vcard')):'Share My vCard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Add to Phone Book</label>
                                    <input type="text" name="add_to_phone_book" value="<?=$this->lang->line('add_to_phone_book')?htmlspecialchars($this->lang->line('add_to_phone_book')):'Add to Phone Book'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>QR Code</label>
                                    <input type="text" name="qr_code" value="<?=$this->lang->line('qr_code')?htmlspecialchars($this->lang->line('qr_code')):'QR Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>My QR Code</label>
                                    <input type="text" name="my_qr_code" value="<?=$this->lang->line('my_qr_code')?htmlspecialchars($this->lang->line('my_qr_code')):'My QR Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Download My QR Code</label>
                                    <input type="text" name="download_my_qr_code" value="<?=$this->lang->line('download_my_qr_code')?htmlspecialchars($this->lang->line('download_my_qr_code')):'Download My QR Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Demo vCard</label>
                                    <input type="text" name="demo_card" value="<?=$this->lang->line('demo_card')?htmlspecialchars($this->lang->line('demo_card')):'Demo vCard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme One</label>
                                    <input type="text" name="theme_one" value="<?=$this->lang->line('theme_one')?htmlspecialchars($this->lang->line('theme_one')):'Theme One'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Two</label>
                                    <input type="text" name="theme_two" value="<?=$this->lang->line('theme_two')?htmlspecialchars($this->lang->line('theme_two')):'Theme Two'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Three</label>
                                    <input type="text" name="theme_three" value="<?=$this->lang->line('theme_three')?htmlspecialchars($this->lang->line('theme_three')):'Theme Three'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Four</label>
                                    <input type="text" name="theme_four" value="<?=$this->lang->line('theme_four')?htmlspecialchars($this->lang->line('theme_four')):'Theme Four'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Five</label>
                                    <input type="text" name="theme_five" value="<?=$this->lang->line('theme_five')?htmlspecialchars($this->lang->line('theme_five')):'Theme Five'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Six</label>
                                    <input type="text" name="theme_six" value="<?=$this->lang->line('theme_six')?htmlspecialchars($this->lang->line('theme_six')):'Theme Six'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Seven</label>
                                    <input type="text" name="theme_seven" value="<?=$this->lang->line('theme_seven')?htmlspecialchars($this->lang->line('theme_seven')):'Theme Seven'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Theme Eight</label>
                                    <input type="text" name="theme_eight" value="<?=$this->lang->line('theme_eight')?htmlspecialchars($this->lang->line('theme_eight')):'Theme Eight'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Products and Services</label>
                                    <input type="text" name="products_services" value="<?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products and Services'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Portfolio</label>
                                    <input type="text" name="portfolio" value="<?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Testimonials</label>
                                    <input type="text" name="testimonials" value="<?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Price</label>
                                    <input type="text" name="price" value="<?=$this->lang->line('price')?htmlspecialchars($this->lang->line('price')):'Price'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pricing</label>
                                    <input type="text" name="pricing" value="<?=$this->lang->line('pricing')?htmlspecialchars($this->lang->line('pricing')):'Pricing'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Enquiry</label>
                                    <input type="text" name="enquiry" value="<?=$this->lang->line('enquiry')?htmlspecialchars($this->lang->line('enquiry')):'Enquiry'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Description</label>
                                    <input type="text" name="description" value="<?=$this->lang->line('description')?htmlspecialchars($this->lang->line('description')):'Description'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>URL</label>
                                    <input type="text" name="url" value="<?=$this->lang->line('url')?htmlspecialchars($this->lang->line('url')):'URL'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Cover Image</label>
                                    <input type="text" name="cover_image" value="<?=$this->lang->line('cover_image')?htmlspecialchars($this->lang->line('cover_image')):'Cover Image'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create</label>
                                    <input type="text" name="create" value="<?=$this->lang->line('create')?htmlspecialchars($this->lang->line('create')):'Create'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>View</label>
                                    <input type="text" name="view" value="<?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Rating</label>
                                    <input type="text" name="rating" value="<?=$this->lang->line('rating')?htmlspecialchars($this->lang->line('rating')):'Rating'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Users</label>
                                    <input type="text" name="users" value="<?=$this->lang->line('users')?htmlspecialchars($this->lang->line('users')):'Users'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Multiple Themes</label>
                                    <input type="text" name="multiple_themes" value="<?=$this->lang->line('multiple_themes')?htmlspecialchars($this->lang->line('multiple_themes')):'Multiple Themes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Fields</label>
                                    <input type="text" name="custom_fields" value="<?=$this->lang->line('custom_fields')?htmlspecialchars($this->lang->line('custom_fields')):'Custom Fields'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Hide Branding</label>
                                    <input type="text" name="hide_branding" value="<?=$this->lang->line('hide_branding')?htmlspecialchars($this->lang->line('hide_branding')):'Hide Branding'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Demo vCard Themes</label>
                                    <input type="text" name="demo_card_themes" value="<?=$this->lang->line('demo_card_themes')?htmlspecialchars($this->lang->line('demo_card_themes')):'Demo vCard Themes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Features</label>
                                    <input type="text" name="features" value="<?=$this->lang->line('features')?htmlspecialchars($this->lang->line('features')):'Features'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>About Us</label>
                                    <input type="text" name="about" value="<?=$this->lang->line('about')?htmlspecialchars($this->lang->line('about')):'About Us'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Terms and Conditions</label>
                                    <input type="text" name="terms_and_conditions" value="<?=$this->lang->line('terms_and_conditions')?htmlspecialchars($this->lang->line('terms_and_conditions')):'Terms and Conditions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Privacy Policy</label>
                                    <input type="text" name="privacy_policy" value="<?=$this->lang->line('privacy_policy')?htmlspecialchars($this->lang->line('privacy_policy')):'Privacy Policy'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Yes</label>
                                    <input type="text" name="yes" value="<?=$this->lang->line('yes')?htmlspecialchars($this->lang->line('yes')):'Yes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No</label>
                                    <input type="text" name="no" value="<?=$this->lang->line('no')?htmlspecialchars($this->lang->line('no')):'No'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subscription</label>
                                    <input type="text" name="subscription" value="<?=$this->lang->line('subscription')?htmlspecialchars($this->lang->line('subscription')):'Subscription'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subscribers</label>
                                    <input type="text" name="subscribers" value="<?=$this->lang->line('subscribers')?htmlspecialchars($this->lang->line('subscribers')):'Subscribers'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subscription Statistics</label>
                                    <input type="text" name="subscription_statistics" value="<?=$this->lang->line('subscription_statistics')?htmlspecialchars($this->lang->line('subscription_statistics')):'Subscription Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subscriber Statistics</label>
                                    <input type="text" name="subscribers_statistics" value="<?=$this->lang->line('subscribers_statistics')?htmlspecialchars($this->lang->line('subscribers_statistics')):'Subscriber Statistics'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Plans</label>
                                    <input type="text" name="subscription_plans" value="<?=$this->lang->line('subscription_plans')?htmlspecialchars($this->lang->line('subscription_plans')):'Plans'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Views</label>
                                    <input type="text" name="views" value="<?=$this->lang->line('views')?htmlspecialchars($this->lang->line('views')):'Views'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Copy Card URL</label>
                                    <input type="text" name="copy" value="<?=$this->lang->line('copy')?htmlspecialchars($this->lang->line('copy')):'Copy Card URL'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Copied to clipboard</label>
                                    <input type="text" name="copied_to_clipboard" value="<?=$this->lang->line('copied_to_clipboard')?htmlspecialchars($this->lang->line('copied_to_clipboard')):'Copied to clipboard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Try Demo</label>
                                    <input type="text" name="try_demo" value="<?=$this->lang->line('try_demo')?htmlspecialchars($this->lang->line('try_demo')):'Try Demo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create My vCard</label>
                                    <input type="text" name="create_my_vcard" value="<?=$this->lang->line('create_my_vcard')?htmlspecialchars($this->lang->line('create_my_vcard')):'Create My vCard'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Go Back</label>
                                    <input type="text" name="go_back" value="<?=$this->lang->line('go_back')?htmlspecialchars($this->lang->line('go_back')):'Go Back'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>I Agree!</label>
                                    <input type="text" name="i_agree" value="<?=$this->lang->line('i_agree')?htmlspecialchars($this->lang->line('i_agree')):'I Agree!'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Account</label>
                                    <input type="text" name="account" value="<?=$this->lang->line('account')?htmlspecialchars($this->lang->line('account')):'Account'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Useful Links</label>
                                    <input type="text" name="useful_links" value="<?=$this->lang->line('useful_links')?htmlspecialchars($this->lang->line('useful_links')):'Useful Links'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Getting Started</label>
                                    <input type="text" name="getting_started" value="<?=$this->lang->line('getting_started')?htmlspecialchars($this->lang->line('getting_started')):'Getting Started'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>3 days trial plan</label>
                                    <input type="text" name="three_days_trial_plan" value="<?=$this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>7 days trial plan</label>
                                    <input type="text" name="seven_days_trial_plan" value="<?=$this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>15 days trial plan</label>
                                    <input type="text" name="fifteen_days_trial_plan" value="<?=$this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>30 days trial plan</label>
                                    <input type="text" name="thirty_days_trial_plan" value="<?=$this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Short Code</label>
                                    <input type="text" name="short_code" value="<?=$this->lang->line('short_code')?htmlspecialchars($this->lang->line('short_code')):'Short Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email Library</label>
                                    <input type="text" name="email_library" value="<?=$this->lang->line('email_library')?htmlspecialchars($this->lang->line('email_library')):'Email Library'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google reCAPTCHA</label>
                                    <input type="text" name="google_recaptcha" value="<?=$this->lang->line('google_recaptcha')?htmlspecialchars($this->lang->line('google_recaptcha')):'Google reCAPTCHA'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Site Key</label>
                                    <input type="text" name="site_key" value="<?=$this->lang->line('site_key')?htmlspecialchars($this->lang->line('site_key')):'Site Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Secret Key</label>
                                    <input type="text" name="secret_key" value="<?=$this->lang->line('secret_key')?htmlspecialchars($this->lang->line('secret_key')):'Secret Key'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Admin</label>
                                    <input type="text" name="admin" value="<?=$this->lang->line('admin')?htmlspecialchars($this->lang->line('admin')):'Admin'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Team Member</label>
                                    <input type="text" name="team_member" value="<?=$this->lang->line('team_member')?htmlspecialchars($this->lang->line('team_member')):'Team Member'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Is RTL</label>
                                    <input type="text" name="is_rtl" value="<?=$this->lang->line('is_rtl')?htmlspecialchars($this->lang->line('is_rtl')):'Is RTL'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>WhatsApp</label>
                                    <input type="text" name="whatsapp" value="<?=$this->lang->line('whatsapp')?htmlspecialchars($this->lang->line('whatsapp')):'WhatsApp'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom URL</label>
                                    <input type="text" name="custom_url" value="<?=$this->lang->line('custom_url')?htmlspecialchars($this->lang->line('custom_url')):'Custom URL'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Scroll to Contact Details</label>
                                    <input type="text" name="scroll_to_contact_details" value="<?=$this->lang->line('scroll_to_contact_details')?htmlspecialchars($this->lang->line('scroll_to_contact_details')):'Scroll to Contact Details'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Scroll to Enquiry Form</label>
                                    <input type="text" name="scroll_to_enquiry_form" value="<?=$this->lang->line('scroll_to_enquiry_form')?htmlspecialchars($this->lang->line('scroll_to_enquiry_form')):'Scroll to Enquiry Form'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Enquiry Button Type</label>
                                    <input type="text" name="enquiry_button_type" value="<?=$this->lang->line('enquiry_button_type')?htmlspecialchars($this->lang->line('enquiry_button_type')):'Enquiry Button Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Unlimited</label>
                                    <input type="text" name="unlimited" value="<?=$this->lang->line('unlimited')?htmlspecialchars($this->lang->line('unlimited')):'Unlimited'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Current Plan</label>
                                    <input type="text" name="current_plan" value="<?=$this->lang->line('current_plan')?htmlspecialchars($this->lang->line('current_plan')):'Current Plan'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No Expiry Date</label>
                                    <input type="text" name="no_expiry_date" value="<?=$this->lang->line('no_expiry_date')?htmlspecialchars($this->lang->line('no_expiry_date')):'No Expiry Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Remaining Days</label>
                                    <input type="text" name="plan_remaining_days" value="<?=$this->lang->line('plan_remaining_days')?htmlspecialchars($this->lang->line('plan_remaining_days')):'Remaining Days'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Gallery</label>
                                    <input type="text" name="gallery" value="<?=$this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Content Type</label>
                                    <input type="text" name="content_type" value="<?=$this->lang->line('content_type')?htmlspecialchars($this->lang->line('content_type')):'Content Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upload Image</label>
                                    <input type="text" name="upload_image" value="<?=$this->lang->line('upload_image')?htmlspecialchars($this->lang->line('upload_image')):'Upload Image'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Image URL</label>
                                    <input type="text" name="custom_image_url" value="<?=$this->lang->line('custom_image_url')?htmlspecialchars($this->lang->line('custom_image_url')):'Custom Image URL'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Vimeo</label>
                                    <input type="text" name="vimeo" value="<?=$this->lang->line('vimeo')?htmlspecialchars($this->lang->line('vimeo')):'Vimeo'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>YouTube</label>
                                    <input type="text" name="youtube" value="<?=$this->lang->line('youtube')?htmlspecialchars($this->lang->line('youtube')):'YouTube'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Contact</label>
                                    <input type="text" name="contact" value="<?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Enquiry Form</label>
                                    <input type="text" name="enquiry_form" value="<?=$this->lang->line('enquiry_form')?htmlspecialchars($this->lang->line('enquiry_form')):'Enquiry Form'?>" class="form-control">
                                </div>
                                


                                
                                <div class="form-group col-md-6">
                                    <label>From Email</label>
                                    <input type="text" name="from_email" value="<?=$this->lang->line('from_email')?htmlspecialchars($this->lang->line('from_email')):'From Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Turn off new user registration</label>
                                    <input type="text" name="turn_off_new_user_registration" value="<?=$this->lang->line('turn_off_new_user_registration')?htmlspecialchars($this->lang->line('turn_off_new_user_registration')):'Turn off new user registration'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Confirmation</label>
                                    <input type="text" name="confirmation" value="<?=$this->lang->line('confirmation')?htmlspecialchars($this->lang->line('confirmation')):'Confirmation'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>SEO</label>
                                    <input type="text" name="seo" value="<?=$this->lang->line('seo')?htmlspecialchars($this->lang->line('seo')):'SEO'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Meta Title</label>
                                    <input type="text" name="meta_title" value="<?=$this->lang->line('meta_title')?htmlspecialchars($this->lang->line('meta_title')):'Meta Title'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Meta Description</label>
                                    <input type="text" name="meta_description" value="<?=$this->lang->line('meta_description')?htmlspecialchars($this->lang->line('meta_description')):'Meta Description'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="<?=$this->lang->line('meta_keywords')?htmlspecialchars($this->lang->line('meta_keywords')):'Meta Keywords'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Payment</label>
                                    <input type="text" name="custom_payment" value="<?=$this->lang->line('custom_payment')?htmlspecialchars($this->lang->line('custom_payment')):'Custom Payment'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upload Receipt</label>
                                    <input type="text" name="upload_receipt" value="<?=$this->lang->line('upload_receipt')?htmlspecialchars($this->lang->line('upload_receipt')):'Upload Receipt'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>OK</label>
                                    <input type="text" name="ok" value="<?=$this->lang->line('ok')?htmlspecialchars($this->lang->line('ok')):'OK'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Social Login</label>
                                    <input type="text" name="social_login" value="<?=$this->lang->line('social_login')?htmlspecialchars($this->lang->line('social_login')):'Social Login'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google</label>
                                    <input type="text" name="google" value="<?=$this->lang->line('google')?htmlspecialchars($this->lang->line('google')):'Google'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google Client ID</label>
                                    <input type="text" name="google_client_id" value="<?=$this->lang->line('google_client_id')?htmlspecialchars($this->lang->line('google_client_id')):'Google Client ID'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Google Client Secret</label>
                                    <input type="text" name="google_client_secret" value="<?=$this->lang->line('google_client_secret')?htmlspecialchars($this->lang->line('google_client_secret')):'Google Client Secret'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Support</label>
                                    <input type="text" name="support" value="<?=$this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Subject</label>
                                    <input type="text" name="subject" value="<?=$this->lang->line('subject')?htmlspecialchars($this->lang->line('subject')):'Subject'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ticket</label>
                                    <input type="text" name="ticket" value="<?=$this->lang->line('ticket')?htmlspecialchars($this->lang->line('ticket')):'Ticket'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Status</label>
                                    <input type="text" name="select_status" value="<?=$this->lang->line('select_status')?htmlspecialchars($this->lang->line('select_status')):'Select Status'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Users</label>
                                    <input type="text" name="select_users" value="<?=$this->lang->line('select_users')?htmlspecialchars($this->lang->line('select_users')):'Select Users'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Received</label>
                                    <input type="text" name="received" value="<?=$this->lang->line('received')?htmlspecialchars($this->lang->line('received')):'Received'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Filter</label>
                                    <input type="text" name="filter" value="<?=$this->lang->line('filter')?htmlspecialchars($this->lang->line('filter')):'Filter'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Sent</label>
                                    <input type="text" name="sent" value="<?=$this->lang->line('sent')?htmlspecialchars($this->lang->line('sent')):'Sent'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Opened and Resolving</label>
                                    <input type="text" name="opened_and_resolving" value="<?=$this->lang->line('opened_and_resolving')?htmlspecialchars($this->lang->line('opened_and_resolving')):'Opened and Resolving'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Resolved and Closed</label>
                                    <input type="text" name="resolved_and_closed" value="<?=$this->lang->line('resolved_and_closed')?htmlspecialchars($this->lang->line('resolved_and_closed')):'Resolved and Closed'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>ID</label>
                                    <input type="text" name="id" value="<?=$this->lang->line('id')?htmlspecialchars($this->lang->line('id')):'ID'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>New support message received</label>
                                    <input type="text" name="new_support_message_received" value="<?=$this->lang->line('new_support_message_received')?htmlspecialchars($this->lang->line('new_support_message_received')):'New support message received'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Enable</label>
                                    <input type="text" name="enable" value="<?=$this->lang->line('enable')?htmlspecialchars($this->lang->line('enable')):'Enable'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Download</label>
                                    <input type="text" name="download" value="<?=$this->lang->line('download')?htmlspecialchars($this->lang->line('download')):'Download'?>" class="form-control">
                                </div>
                              
                                <div class="form-group col-md-6">
                                    <label>Card URL</label>
                                    <input type="text" name="card_URL" value="<?=$this->lang->line('card_URL')?htmlspecialchars($this->lang->line('card_URL')):'Card URL'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Add Block or Social Field</label>
                                    <input type="text" name="add_block_or_social_field" value="<?=$this->lang->line('add_block_or_social_field')?htmlspecialchars($this->lang->line('add_block_or_social_field')):'Add Block or Social Field'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Delete Account</label>
                                    <input type="text" name="delete_account" value="<?=$this->lang->line('delete_account')?htmlspecialchars($this->lang->line('delete_account')):'Delete Account'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Card Font Color</label>
                                    <input type="text" name="card_font_color" value="<?=$this->lang->line('card_font_color')?htmlspecialchars($this->lang->line('card_font_color')):'Card Font Color'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Card Font</label>
                                    <input type="text" name="card_font" value="<?=$this->lang->line('card_font')?htmlspecialchars($this->lang->line('card_font')):'Card Font'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Default</label>
                                    <input type="text" name="default" value="<?=$this->lang->line('default')?htmlspecialchars($this->lang->line('default')):'Default'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Stats</label>
                                    <input type="text" name="stats" value="<?=$this->lang->line('stats')?htmlspecialchars($this->lang->line('stats')):'Stats'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Scans</label>
                                    <input type="text" name="scans" value="<?=$this->lang->line('scans')?htmlspecialchars($this->lang->line('scans')):'Scans'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ads</label>
                                    <input type="text" name="ads" value="<?=$this->lang->line('ads')?htmlspecialchars($this->lang->line('ads')):'Ads'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Ad Code</label>
                                    <input type="text" name="ad_code" value="<?=$this->lang->line('ad_code')?htmlspecialchars($this->lang->line('ad_code')):'Ad Code'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Sections</label>
                                    <input type="text" name="custom_sections" value="<?=$this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Content</label>
                                    <input type="text" name="content" value="<?=$this->lang->line('content')?htmlspecialchars($this->lang->line('content')):'Content'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Show Ads At</label>
                                    <input type="text" name="show_ads_at" value="<?=$this->lang->line('show_ads_at')?htmlspecialchars($this->lang->line('show_ads_at')):'Show Ads At'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>After</label>
                                    <input type="text" name="after" value="<?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Before</label>
                                    <input type="text" name="before" value="<?=$this->lang->line('before')?htmlspecialchars($this->lang->line('before')):'Before'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>No Ads</label>
                                    <input type="text" name="no_ads" value="<?=$this->lang->line('no_ads')?htmlspecialchars($this->lang->line('no_ads')):'No Ads'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Create Custom Section</label>
                                    <input type="text" name="create_custom_section" value="<?=$this->lang->line('create_custom_section')?htmlspecialchars($this->lang->line('create_custom_section')):'Create Custom Section'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edit Custom Section</label>
                                    <input type="text" name="edit_custom_section" value="<?=$this->lang->line('edit_custom_section')?htmlspecialchars($this->lang->line('edit_custom_section')):'Edit Custom Section'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Advanced</label>
                                    <input type="text" name="advanced" value="<?=$this->lang->line('advanced')?htmlspecialchars($this->lang->line('advanced')):'Advanced'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Enquiry Email</label>
                                    <input type="text" name="enquiry_email" value="<?=$this->lang->line('enquiry_email')?htmlspecialchars($this->lang->line('enquiry_email')):'Enquiry Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Show Add to Phone Book</label>
                                    <input type="text" name="show_add_to_phone_book" value="<?=$this->lang->line('show_add_to_phone_book')?htmlspecialchars($this->lang->line('show_add_to_phone_book')):'Show Add to Phone Book'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Show Share Button</label>
                                    <input type="text" name="show_share" value="<?=$this->lang->line('show_share')?htmlspecialchars($this->lang->line('show_share')):'Show Share Button'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Show QR Code on Card</label>
                                    <input type="text" name="show_qr_on_card" value="<?=$this->lang->line('show_qr_on_card')?htmlspecialchars($this->lang->line('show_qr_on_card')):'Show QR Code on Card'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Show QR Code on Share Popup</label>
                                    <input type="text" name="show_qr_on_share_popup" value="<?=$this->lang->line('show_qr_on_share_popup')?htmlspecialchars($this->lang->line('show_qr_on_share_popup')):'Show QR Code on Share Popup'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Search Engine Indexing</label>
                                    <input type="text" name="search_engine_indexing" value="<?=$this->lang->line('search_engine_indexing')?htmlspecialchars($this->lang->line('search_engine_indexing')):'Search Engine Indexing'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom CSS</label>
                                    <input type="text" name="custom_css" value="<?=$this->lang->line('custom_css')?htmlspecialchars($this->lang->line('custom_css')):'Custom CSS'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom JS</label>
                                    <input type="text" name="custom_js" value="<?=$this->lang->line('custom_js')?htmlspecialchars($this->lang->line('custom_js')):'Custom JS'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom JS, CSS</label>
                                    <input type="text" name="custom_js_css" value="<?=$this->lang->line('custom_js_css')?htmlspecialchars($this->lang->line('custom_js_css')):'Custom JS, CSS'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Select Field Type</label>
                                    <input type="text" name="select_field_type" value="<?=$this->lang->line('select_field_type')?htmlspecialchars($this->lang->line('select_field_type')):'Select Field Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Field Type</label>
                                    <input type="text" name="field_type" value="<?=$this->lang->line('field_type')?htmlspecialchars($this->lang->line('field_type')):'Field Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Clone</label>
                                    <input type="text" name="clone" value="<?=$this->lang->line('clone')?htmlspecialchars($this->lang->line('clone')):'Clone'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Reorder</label>
                                    <input type="text" name="reorder" value="<?=$this->lang->line('reorder')?htmlspecialchars($this->lang->line('reorder')):'Reorder'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Drag and Drop</label>
                                    <input type="text" name="drag_and_rop" value="<?=$this->lang->line('drag_and_rop')?htmlspecialchars($this->lang->line('drag_and_rop')):'Drag and Drop'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Drag and Drop row from below table to reorder content.</label>
                                    <input type="text" name="drag_and_rop_row_from_below_table_to_reorder_content" value="<?=$this->lang->line('drag_and_rop_row_from_below_table_to_reorder_content')?htmlspecialchars($this->lang->line('drag_and_rop_row_from_below_table_to_reorder_content')):'Drag and Drop row from below table to reorder content.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Make setion's conetnt carousel.</label>
                                    <input type="text" name="make_setions_conetnt_carousel" value="<?=$this->lang->line('make_setions_conetnt_carousel')?htmlspecialchars($this->lang->line('make_setions_conetnt_carousel')):"Make section content carousel."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Frontend enquiry form</label>
                                    <input type="text" name="frontend_enquiry_form" value="<?=$this->lang->line('frontend_enquiry_form')?htmlspecialchars($this->lang->line('frontend_enquiry_form')):"Frontend enquiry form"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Card enquiry form</label>
                                    <input type="text" name="card_enquiry_form" value="<?=$this->lang->line('card_enquiry_form')?htmlspecialchars($this->lang->line('card_enquiry_form')):"Card enquiry form"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Foreground Color</label>
                                    <input type="text" name="foreground_color" value="<?=$this->lang->line('foreground_color')?htmlspecialchars($this->lang->line('foreground_color')):"Foreground Color"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Background Color</label>
                                    <input type="text" name="background_color" value="<?=$this->lang->line('background_color')?htmlspecialchars($this->lang->line('background_color')):"Background Color"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Text Color</label>
                                    <input type="text" name="text_color" value="<?=$this->lang->line('text_color')?htmlspecialchars($this->lang->line('text_color')):'Text Color'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Corner Radius</label>
                                    <input type="text" name="corner_radius" value="<?=$this->lang->line('corner_radius')?htmlspecialchars($this->lang->line('corner_radius')):"Corner Radius"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>QR Type</label>
                                    <input type="text" name="qr_type" value="<?=$this->lang->line('qr_type')?htmlspecialchars($this->lang->line('qr_type')):"QR Type"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Normal</label>
                                    <input type="text" name="normal" value="<?=$this->lang->line('normal')?htmlspecialchars($this->lang->line('normal')):"Normal"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Size</label>
                                    <input type="text" name="size" value="<?=$this->lang->line('size')?htmlspecialchars($this->lang->line('size')):"Size"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Reorder Sections</label>
                                    <input type="text" name="reorder_sections" value="<?=$this->lang->line('reorder_sections')?htmlspecialchars($this->lang->line('reorder_sections')):"Reorder Sections"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Main Card Section</label>
                                    <input type="text" name="main_card_section" value="<?=$this->lang->line('main_card_section')?htmlspecialchars($this->lang->line('main_card_section')):"Main Card Section"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Predefined Themes</label>
                                    <input type="text" name="predefined_themes" value="<?=$this->lang->line('predefined_themes')?htmlspecialchars($this->lang->line('predefined_themes')):"Predefined Themes"?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>A predefined theme comes with some predefined things like color and background which can't be changed.</label>
                                    <input type="text" name="a_predefined_theme_comes_with_some_predefined_things_like_color_and_background_which_cant_be_changed" value="<?=$this->lang->line('a_predefined_theme_comes_with_some_predefined_things_like_color_and_background_which_cant_be_changed')?htmlspecialchars($this->lang->line('a_predefined_theme_comes_with_some_predefined_things_like_color_and_background_which_cant_be_changed')):"A predefined theme comes with some predefined things like color and background which can't be changed."?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Custom Domain</label>
                                    <input type="text" name="custom_domain" value="<?=$this->lang->line('custom_domain')?htmlspecialchars($this->lang->line('custom_domain')):"Custom Domain"?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Note</label>
                                    <input type="text" name="note" value="<?=$this->lang->line('note')?htmlspecialchars($this->lang->line('note')):"Note"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>OR</label>
                                    <input type="text" name="or" value="<?=$this->lang->line('or')?htmlspecialchars($this->lang->line('or')):"OR"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Make sure that your domain or subdomain has a CNAME record pointing to our</label>
                                    <input type="text" name="make_sure_that_your_domain_or_subdomain_has_a_cname_record_pointing_to_our" value="<?=$this->lang->line('make_sure_that_your_domain_or_subdomain_has_a_cname_record_pointing_to_our')?htmlspecialchars($this->lang->line('make_sure_that_your_domain_or_subdomain_has_a_cname_record_pointing_to_our')):"Make sure that your domain or subdomain has a CNAME record pointing to our"?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>A record pointing to our</label>
                                    <input type="text" name="a_record_pointing_to_our" value="<?=$this->lang->line('a_record_pointing_to_our')?htmlspecialchars($this->lang->line('a_record_pointing_to_our')):"A record pointing to our"?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label>Allowed Domain Formats: yourdomain.com and subdomain.yourdomain.com</label>
                                    <input type="text" name="allowed_domain_formats_yourdomain_com_and_subdomain_yourdomain_com" value="<?=$this->lang->line('allowed_domain_formats_yourdomain_com_and_subdomain_yourdomain_com')?htmlspecialchars($this->lang->line('allowed_domain_formats_yourdomain_com_and_subdomain_yourdomain_com')):'Allowed Domain Formats: yourdomain.com and subdomain.yourdomain.com'?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Automatically redirect my card on this custom domain from</label>
                                    <input type="text" name="automatically_redirect_my_card_on_this_custom_domain_from" value="<?=$this->lang->line('automatically_redirect_my_card_on_this_custom_domain_from')?htmlspecialchars($this->lang->line('automatically_redirect_my_card_on_this_custom_domain_from')):'Automatically redirect my card on this custom domain from'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Domain Request</label>
                                    <input type="text" name="domain_request" value="<?=$this->lang->line('domain_request')?htmlspecialchars($this->lang->line('domain_request')):"Domain Request"?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Custom domains require approval from the administrator. You will be informed about the status update through notification.</label>
                                    <input type="text" name="custom_domains_require_approval_from_the_administrator_you_will_be_informed_about_the_status_update_through_notification" value="<?=$this->lang->line('custom_domains_require_approval_from_the_administrator_you_will_be_informed_about_the_status_update_through_notification')?htmlspecialchars($this->lang->line('custom_domains_require_approval_from_the_administrator_you_will_be_informed_about_the_status_update_through_notification')):"Custom domains require approval from the administrator. You will be informed about the status update through notification."?>" class="form-control">
                                </div>
                              
                                <div class="form-group col-md-6">
                                    <label>Invalid domain name.</label>
                                    <input type="text" name="invalid_domain_name" value="<?=$this->lang->line('invalid_domain_name')?htmlspecialchars($this->lang->line('invalid_domain_name')):"Invalid domain name."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>The domain is already associated with another card.</label>
                                    <input type="text" name="the_domain_is_already_associated_with_another_card" value="<?=$this->lang->line('the_domain_is_already_associated_with_another_card')?htmlspecialchars($this->lang->line('the_domain_is_already_associated_with_another_card')):"The domain is already associated with another card."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Before activating a domain make sure you have added a domain to your hosting with proper directory access.</label>
                                    <input type="text" name="before_activating_a_domain_make_sure_you_have_added_a_domain_to_your_hosting_with_proper_directory_access" value="<?=$this->lang->line('before_activating_a_domain_make_sure_you_have_added_a_domain_to_your_hosting_with_proper_directory_access')?htmlspecialchars($this->lang->line('before_activating_a_domain_make_sure_you_have_added_a_domain_to_your_hosting_with_proper_directory_access')):"Before activating a domain make sure you have added a domain to your hosting with proper directory access."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>a new custom domain submitted.</label>
                                    <input type="text" name="a_new_custom_domain_submitted" value="<?=$this->lang->line('a_new_custom_domain_submitted')?htmlspecialchars($this->lang->line('a_new_custom_domain_submitted')):"a new custom domain submitted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>custom domain status updated.</label>
                                    <input type="text" name="custom_domain_status_updated" value="<?=$this->lang->line('custom_domain_status_updated')?htmlspecialchars($this->lang->line('custom_domain_status_updated')):"custom domain status updated."?>" class="form-control">
                                </div>
                              
                                <div class="form-group col-md-6">
                                    <label>Turn off custom domain system</label>
                                    <input type="text" name="turn_off_custom_domain_system" value="<?=$this->lang->line('turn_off_custom_domain_system')?htmlspecialchars($this->lang->line('turn_off_custom_domain_system')):'Turn off custom domain system'?>" class="form-control">
                                </div>
                              
                                <div class="form-group col-md-6">
                                    <label>Show card view count on a card</label>
                                    <input type="text" name="show_card_view_count_on_a_card" value="<?=$this->lang->line('show_card_view_count_on_a_card')?htmlspecialchars($this->lang->line('show_card_view_count_on_a_card')):'Show card view count on a card'?>" class="form-control">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Show change language option on a card</label>
                                    <input type="text" name="show_change_language_option_on_a_card" value="<?=$this->lang->line('show_change_language_option_on_a_card')?htmlspecialchars($this->lang->line('show_change_language_option_on_a_card')):'Show change language option on a card'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Created</label>
                                    <input type="text" name="created" value="<?=$this->lang->line('created')?htmlspecialchars($this->lang->line('created')):'Created'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Taxes</label>
                                    <input type="text" name="taxes" value="<?=$this->lang->line('taxes')?htmlspecialchars($this->lang->line('taxes')):'Taxes'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tax Name</label>
                                    <input type="text" name="tax_name" value="<?=$this->lang->line('tax_name')?htmlspecialchars($this->lang->line('tax_name')):'Tax Name'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Tax Rate(%)</label>
                                    <input type="text" name="tax_rate" value="<?=$this->lang->line('tax_rate')?htmlspecialchars($this->lang->line('tax_rate')):'Tax Rate(%)'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Order Summary</label>
                                    <input type="text" name="order_summary" value="<?=$this->lang->line('order_summary')?htmlspecialchars($this->lang->line('order_summary')):'Order Summary'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Check your order and select your payment method from the options below.</label>
                                    <input type="text" name="check_your_order_and_select_your_payment_method_from_the_options_below" value="<?=$this->lang->line('check_your_order_and_select_your_payment_method_from_the_options_below')?htmlspecialchars($this->lang->line('check_your_order_and_select_your_payment_method_from_the_options_below')):'Check your order and select your payment method from the options below.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Payment Type</label>
                                    <input type="text" name="payment_type" value="<?=$this->lang->line('payment_type')?htmlspecialchars($this->lang->line('payment_type')):'Payment Type'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Pay Now</label>
                                    <input type="text" name="pay_now" value="<?=$this->lang->line('pay_now')?htmlspecialchars($this->lang->line('pay_now')):'Pay Now'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Invoice</label>
                                    <input type="text" name="invoice" value="<?=$this->lang->line('invoice')?htmlspecialchars($this->lang->line('invoice')):'Invoice'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Billed To</label>
                                    <input type="text" name="billed_to" value="<?=$this->lang->line('billed_to')?$this->lang->line('billed_to'):'Billed To'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Invoice Date</label>
                                    <input type="text" name="invoice_date" value="<?=$this->lang->line('invoice_date')?$this->lang->line('invoice_date'):'Invoice Date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>XML sitemap</label>
                                    <input type="text" name="xml_sitemap" value="<?=$this->lang->line('xml_sitemap')?$this->lang->line('xml_sitemap'):'XML sitemap'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Custom Card Url</label>
                                    <input type="text" name="custom_card_url" value="<?=$this->lang->line('custom_card_url')?$this->lang->line('custom_card_url'):'Custom Card Url'?>" class="form-control">
                                </div>
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              
                              

                              

                              
                              
                              
                              

                              
                              
                                <div class="form-group col-md-12">
                                    <label>This email will not be updated latter.</label>
                                    <input type="text" name="this_email_will_not_be_updated_latter" value="<?=$this->lang->line('this_email_will_not_be_updated_latter')?htmlspecialchars($this->lang->line('this_email_will_not_be_updated_latter')):'This email will not be updated latter.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>We will send a link to reset your password.</label>
                                    <input type="text" name="we_will_send_a_link_to_reset_your_password" value="<?=$this->lang->line('we_will_send_a_link_to_reset_your_password')?htmlspecialchars($this->lang->line('we_will_send_a_link_to_reset_your_password')):'We will send a link to reset your password.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Your subscription plan has been expired on date</label>
                                    <input type="text" name="your_subscription_plan_has_been_expired_on_date" value="<?=$this->lang->line('your_subscription_plan_has_been_expired_on_date')?htmlspecialchars($this->lang->line('your_subscription_plan_has_been_expired_on_date')):'Your subscription plan has been expired on date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Your current subscription plan is expiring on date</label>
                                    <input type="text" name="your_current_subscription_plan_is_expiring_on_date" value="<?=$this->lang->line('your_current_subscription_plan_is_expiring_on_date')?htmlspecialchars($this->lang->line('your_current_subscription_plan_is_expiring_on_date')):'Your current subscription plan is expiring on date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>This is your current active plan and expiring on date</label>
                                    <input type="text" name="this_is_your_current_active_plan_and_expiring_on_date" value="<?=$this->lang->line('this_is_your_current_active_plan_and_expiring_on_date')?htmlspecialchars($this->lang->line('this_is_your_current_active_plan_and_expiring_on_date')):'This is your current active plan and expiring on date'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>This is your current active plan, No Expiry Date.</label>
                                    <input type="text" name="this_is_your_current_active_plan" value="<?=$this->lang->line('this_is_your_current_active_plan')?htmlspecialchars($this->lang->line('this_is_your_current_active_plan')):'This is your current active plan, No Expiry Date.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Leave Password and Confirm Password empty for no change in Password.</label>
                                    <input type="text" name="leave_password_and_confirm_password_empty_for_no_change_in_password" value="<?=$this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password')?htmlspecialchars($this->lang->line('leave_password_and_confirm_password_empty_for_no_change_in_password')):'Leave Password and Confirm Password empty for no change in Password.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Disable "Landing Page" option to Disable whole frontend.</label>
                                    <input type="text" name="disable_landing_page_option_to_disable_whole_frontend" value="<?=$this->lang->line('disable_landing_page_option_to_disable_whole_frontend')?htmlspecialchars($this->lang->line('disable_landing_page_option_to_disable_whole_frontend')):'Disable Landing Page option to Disable whole frontend.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Must enter Title and Description for default language.</label>
                                    <input type="text" name="must_enter_title_and_description_for_default_language" value="<?=$this->lang->line('must_enter_title_and_description_for_default_language')?htmlspecialchars($this->lang->line('must_enter_title_and_description_for_default_language')):'Must enter Title and Description for default language.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Show Subscription Plan Expiry Alert Before</label>
                                    <input type="text" name="show_subscription_plan_expiry_alert_before" value="<?=$this->lang->line('show_subscription_plan_expiry_alert_before')?htmlspecialchars($this->lang->line('show_subscription_plan_expiry_alert_before')):'Show Subscription Plan Expiry Alert Before'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>This will show alert box in main dashboard to the user about their plan expiry date.</label>
                                    <input type="text" name="this_will_show_alert_box_in_main_dashboard_to_the_user_about_their_plan_expiry_date" value="<?=$this->lang->line('this_will_show_alert_box_in_main_dashboard_to_the_user_about_their_plan_expiry_date')?htmlspecialchars($this->lang->line('this_will_show_alert_box_in_main_dashboard_to_the_user_about_their_plan_expiry_date')):'This will show alert box in main dashboard to the user about their plan expiry date.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You have to contact a user by yourself for further process.</label>
                                    <input type="text" name="you_have_to_contact_a_user_by_yourself_for_further_process" value="<?=$this->lang->line('you_have_to_contact_a_user_by_yourself_for_further_process')?htmlspecialchars($this->lang->line('you_have_to_contact_a_user_by_yourself_for_further_process')):'You have to contact a user by yourself for further process.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Select the update zip file and hit Install Update button.</label>
                                    <input type="text" name="select_the_update_zip_file_and_hit_install_update_button" value="<?=$this->lang->line('select_the_update_zip_file_and_hit_install_update_button')?htmlspecialchars($this->lang->line('select_the_update_zip_file_and_hit_install_update_button')):'Select the update zip file and hit Install Update button.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Please take a backup before going further. Follow the further instructions given with the update file.</label>
                                    <input type="text" name="please_take_a_backup_before_going_further_follow_the_further_instructions_given_with_the_update_file" value="<?=$this->lang->line('please_take_a_backup_before_going_further_follow_the_further_instructions_given_with_the_update_file')?htmlspecialchars($this->lang->line('please_take_a_backup_before_going_further_follow_the_further_instructions_given_with_the_update_file')):'Please take a backup before going further. Follow the further instructions given with the update file.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Don't edit it if you don't want to edit language name.</label>
                                    <input type="text" name="dont_edit_it_if_you_dont_want_to_edit_language_name" value="<?=$this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')?htmlspecialchars($this->lang->line('dont_edit_it_if_you_dont_want_to_edit_language_name')):"Don't edit it if you don't want to edit language name."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Leave empty for no changes.</label>
                                    <input type="text" name="leave_empty_for_no_changes" value="<?=$this->lang->line('leave_empty_for_no_changes')?htmlspecialchars($this->lang->line('leave_empty_for_no_changes')):"Leave empty for no changes."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>New user registered.</label>
                                    <input type="text" name="new_user_registered" value="<?=$this->lang->line('new_user_registered')?htmlspecialchars($this->lang->line('new_user_registered')):"New user registered."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Ordered subscription plan</label>
                                    <input type="text" name="ordered_subscription_plan" value="<?=$this->lang->line('ordered_subscription_plan')?htmlspecialchars($this->lang->line('ordered_subscription_plan')):"Ordered subscription plan"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Your Offline / Bank Transfer request accepted for subscription plan</label>
                                    <input type="text" name="your_offline_bank_transfer_request_accepted_for_subscription_plan" value="<?=$this->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')?htmlspecialchars($this->lang->line('your_offline_bank_transfer_request_accepted_for_subscription_plan')):"Your Offline / Bank Transfer request accepted for subscription plan"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offline / Bank Transfer request created for subscription plan</label>
                                    <input type="text" name="offline_bank_transfer_request_created_for_subscription_plan" value="<?=$this->lang->line('offline_bank_transfer_request_created_for_subscription_plan')?htmlspecialchars($this->lang->line('offline_bank_transfer_request_created_for_subscription_plan')):"Offline / Bank Transfer request created for subscription plan"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>User registered successfully. Go to the login page and login with your credentials.</label>
                                    <input type="text" name="user_registered_successfully_go_to_the_login_page_and_login_with_your_credentials" value="<?=$this->lang->line('user_registered_successfully_go_to_the_login_page_and_login_with_your_credentials')?htmlspecialchars($this->lang->line('user_registered_successfully_go_to_the_login_page_and_login_with_your_credentials')):"User registered successfully. Go to the login page and login with your credentials."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Invalid User ID</label>
                                    <input type="text" name="invalid_user_id" value="<?=$this->lang->line('invalid_user_id')?htmlspecialchars($this->lang->line('invalid_user_id')):"Invalid User ID"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You must be an administrator to take this action.</label>
                                    <input type="text" name="you_must_be_an_administrator_to_take_this_action" value="<?=$this->lang->line('you_must_be_an_administrator_to_take_this_action')?htmlspecialchars($this->lang->line('you_must_be_an_administrator_to_take_this_action')):"You must be an administrator to take this action."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Access Denied</label>
                                    <input type="text" name="access_denied" value="<?=$this->lang->line('access_denied')?htmlspecialchars($this->lang->line('access_denied')):"Access Denied"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Something wrong! Try again.</label>
                                    <input type="text" name="something_wrong_try_again" value="<?=$this->lang->line('something_wrong_try_again')?htmlspecialchars($this->lang->line('something_wrong_try_again')):"Something wrong! Try again."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Wrong update file is selected.</label>
                                    <input type="text" name="wrong_update_file_is_selected" value="<?=$this->lang->line('wrong_update_file_is_selected')?htmlspecialchars($this->lang->line('wrong_update_file_is_selected')):"Wrong update file is selected."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Select valid zip file.</label>
                                    <input type="text" name="select_valid_zip_file" value="<?=$this->lang->line('select_valid_zip_file')?htmlspecialchars($this->lang->line('select_valid_zip_file')):"Select valid zip file."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Error occured during file extracting. Select valid zip file OR Please Try again later.</label>
                                    <input type="text" name="error_occured_during_file_extracting_select_valid_zip_file_or_please_try_again_later" value="<?=$this->lang->line('error_occured_during_file_extracting_select_valid_zip_file_or_please_try_again_later')?htmlspecialchars($this->lang->line('error_occured_during_file_extracting_select_valid_zip_file_or_please_try_again_later')):"Error occured during file extracting. Select valid zip file OR Please Try again later."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Error occured during file uploading. Select valid zip file OR Please Try again later.</label>
                                    <input type="text" name="error_occured_during_file_uploading_select_valid_zip_file_or_please_try_again_later" value="<?=$this->lang->line('error_occured_during_file_uploading_select_valid_zip_file_or_please_try_again_later')?htmlspecialchars($this->lang->line('error_occured_during_file_uploading_select_valid_zip_file_or_please_try_again_later')):"Error occured during file uploading. Select valid zip file OR Please Try again later."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment Setting Saved.</label>
                                    <input type="text" name="payment_setting_saved" value="<?=$this->lang->line('payment_setting_saved')?htmlspecialchars($this->lang->line('payment_setting_saved')):"Payment Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Email Setting Saved.</label>
                                    <input type="text" name="email_setting_saved" value="<?=$this->lang->line('email_setting_saved')?htmlspecialchars($this->lang->line('email_setting_saved')):"Email Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>General Setting Saved.</label>
                                    <input type="text" name="general_setting_saved" value="<?=$this->lang->line('general_setting_saved')?htmlspecialchars($this->lang->line('general_setting_saved')):"General Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Frontend Setting Saved.</label>
                                    <input type="text" name="frontend_setting_saved" value="<?=$this->lang->line('frontend_setting_saved')?htmlspecialchars($this->lang->line('frontend_setting_saved')):"Frontend Setting Saved."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>System updated successfully.</label>
                                    <input type="text" name="system_updated_successfully" value="<?=$this->lang->line('system_updated_successfully')?htmlspecialchars($this->lang->line('system_updated_successfully')):"System updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Plan deleted successfully.</label>
                                    <input type="text" name="plan_deleted_successfully" value="<?=$this->lang->line('plan_deleted_successfully')?htmlspecialchars($this->lang->line('plan_deleted_successfully')):"Plan deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Plan updated successfully.</label>
                                    <input type="text" name="plan_updated_successfully" value="<?=$this->lang->line('plan_updated_successfully')?htmlspecialchars($this->lang->line('plan_updated_successfully')):"Plan updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Plan created successfully.</label>
                                    <input type="text" name="plan_created_successfully" value="<?=$this->lang->line('plan_created_successfully')?htmlspecialchars($this->lang->line('plan_created_successfully')):"Plan created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Offline / Bank Transfer request sent successfully.</label>
                                    <input type="text" name="offline_bank_transfer_request_sent_successfully" value="<?=$this->lang->line('offline_bank_transfer_request_sent_successfully')?htmlspecialchars($this->lang->line('offline_bank_transfer_request_sent_successfully')):"Offline / Bank Transfer request sent successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>offline / bank transfer request accepted successfully.</label>
                                    <input type="text" name="offline_request_accepted_successfully" value="<?=$this->lang->line('offline_request_accepted_successfully')?htmlspecialchars($this->lang->line('offline_request_accepted_successfully')):"offline / bank transfer request accepted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Slug already exists. Try another one.</label>
                                    <input type="text" name="slug_already_exists" value="<?=$this->lang->line('slug_already_exists')?htmlspecialchars($this->lang->line('slug_already_exists')):'Slug already exists. Try another one.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Slug will be used for your vcard url. Use only alphanumeric value, no space allowed. (Hyphen(-) allowed).</label>
                                    <input type="text" name="slug_will_be_used_for_your_vcard_url" value="<?=$this->lang->line('slug_will_be_used_for_your_vcard_url')?htmlspecialchars($this->lang->line('slug_will_be_used_for_your_vcard_url')):'Slug will be used for your vcard url. Use only english alphanumeric value, no space allowed. (Hyphen(-) allowed).'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>offline / bank transfer request rejected successfully.</label>
                                    <input type="text" name="offline_request_rejected_successfully" value="<?=$this->lang->line('offline_request_rejected_successfully')?htmlspecialchars($this->lang->line('offline_request_rejected_successfully')):"offline / bank transfer request rejected successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Payment unsuccessful. Please Try again later.</label>
                                    <input type="text" name="payment_unsuccessful_please_try_again_later" value="<?=$this->lang->line('payment_unsuccessful_please_try_again_later')?htmlspecialchars($this->lang->line('payment_unsuccessful_please_try_again_later')):"Payment unsuccessful. Please Try again later."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Choose valid subscription plan.</label>
                                    <input type="text" name="choose_valid_subscription_plan" value="<?=$this->lang->line('choose_valid_subscription_plan')?htmlspecialchars($this->lang->line('choose_valid_subscription_plan')):"Choose valid subscription plan."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Plan subscribed successfully.</label>
                                    <input type="text" name="plan_subscribed_successfully" value="<?=$this->lang->line('plan_subscribed_successfully')?htmlspecialchars($this->lang->line('plan_subscribed_successfully')):"Plan subscribed successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Notification deleted successfully.</label>
                                    <input type="text" name="notification_deleted_successfully" value="<?=$this->lang->line('notification_deleted_successfully')?htmlspecialchars($this->lang->line('notification_deleted_successfully')):"Notification deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Only english characters allowed.</label>
                                    <input type="text" name="only_english_characters_allowed" value="<?=$this->lang->line('only_english_characters_allowed')?htmlspecialchars($this->lang->line('only_english_characters_allowed')):"Only english characters allowed."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Already exists this language.</label>
                                    <input type="text" name="already_exists_this_language" value="<?=$this->lang->line('already_exists_this_language')?htmlspecialchars($this->lang->line('already_exists_this_language')):"Already exists this language."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Language deleted successfully.</label>
                                    <input type="text" name="language_deleted_successfully" value="<?=$this->lang->line('language_deleted_successfully')?htmlspecialchars($this->lang->line('language_deleted_successfully')):"Language deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Language updated successfully.</label>
                                    <input type="text" name="language_updated_successfully" value="<?=$this->lang->line('language_updated_successfully')?htmlspecialchars($this->lang->line('language_updated_successfully')):"Language updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Language created successfully.</label>
                                    <input type="text" name="language_created_successfully" value="<?=$this->lang->line('language_created_successfully')?htmlspecialchars($this->lang->line('language_created_successfully')):"Language created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>We will get back to you soon.</label>
                                    <input type="text" name="we_will_get_back_to_you_soon" value="<?=$this->lang->line('we_will_get_back_to_you_soon')?htmlspecialchars($this->lang->line('we_will_get_back_to_you_soon')):"We will get back to you soon."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Feature deleted successfully.</label>
                                    <input type="text" name="feature_deleted_successfully" value="<?=$this->lang->line('feature_deleted_successfully')?htmlspecialchars($this->lang->line('feature_deleted_successfully')):"Feature deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Feature updated successfully.</label>
                                    <input type="text" name="feature_updated_successfully" value="<?=$this->lang->line('feature_updated_successfully')?htmlspecialchars($this->lang->line('feature_updated_successfully')):"Feature updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Pages updated successfully.</label>
                                    <input type="text" name="pages_updated_successfully" value="<?=$this->lang->line('pages_updated_successfully')?htmlspecialchars($this->lang->line('pages_updated_successfully')):"Pages updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Feature created successfully.</label>
                                    <input type="text" name="feature_created_successfully" value="<?=$this->lang->line('feature_created_successfully')?htmlspecialchars($this->lang->line('feature_created_successfully')):"Feature created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Wait...</label>
                                    <input type="text" name="wait" value="<?=$this->lang->line('wait')?htmlspecialchars($this->lang->line('wait')):"Wait..."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Default language can not be deleted.</label>
                                    <input type="text" name="default_language_can_not_be_deleted" value="<?=$this->lang->line('default_language_can_not_be_deleted')?htmlspecialchars($this->lang->line('default_language_can_not_be_deleted')):"Default language can not be deleted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Notification?</label>
                                    <input type="text" name="you_want_to_delete_this_notification" value="<?=$this->lang->line('you_want_to_delete_this_notification')?htmlspecialchars($this->lang->line('you_want_to_delete_this_notification')):"You want to delete this Notification?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Feature?</label>
                                    <input type="text" name="you_want_to_delete_this_feature" value="<?=$this->lang->line('you_want_to_delete_this_feature')?htmlspecialchars($this->lang->line('you_want_to_delete_this_feature')):"You want to delete this Feature?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want reject this offline / bank transfer request? This can not be undo.</label>
                                    <input type="text" name="you_want_reject_this_offline_request_this_can_not_be_undo" value="<?=$this->lang->line('you_want_reject_this_offline_request_this_can_not_be_undo')?htmlspecialchars($this->lang->line('you_want_reject_this_offline_request_this_can_not_be_undo')):"You want reject this offline / bank transfer request? This can not be undo."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want accept this offline / bank transfer request? This can not be undo.</label>
                                    <input type="text" name="you_want_accept_this_offline_request_this_can_not_be_undo" value="<?=$this->lang->line('you_want_accept_this_offline_request_this_can_not_be_undo')?htmlspecialchars($this->lang->line('you_want_accept_this_offline_request_this_can_not_be_undo')):"You want accept this offline / bank transfer request? This can not be undo."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Default plan can not be deleted.</label>
                                    <input type="text" name="default_plan_can_not_be_deleted" value="<?=$this->lang->line('default_plan_can_not_be_deleted')?htmlspecialchars($this->lang->line('default_plan_can_not_be_deleted')):"Default plan can not be deleted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Plan? All users under this plan will be added to the Default Plan.</label>
                                    <input type="text" name="you_want_to_delete_this_plan_all_users_under_this_plan_will_be_added_to_the_default_plan" value="<?=$this->lang->line('you_want_to_delete_this_plan_all_users_under_this_plan_will_be_added_to_the_default_plan')?htmlspecialchars($this->lang->line('you_want_to_delete_this_plan_all_users_under_this_plan_will_be_added_to_the_default_plan')):"You want to delete this Plan? All users under this plan will be added to the Default Plan."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this user? All related data with this user also will be deleted.</label>
                                    <input type="text" name="you_want_to_delete_this_user_all_related_data_with_this_user_also_will_be_deleted" value="<?=$this->lang->line('you_want_to_delete_this_user_all_related_data_with_this_user_also_will_be_deleted')?htmlspecialchars($this->lang->line('you_want_to_delete_this_user_all_related_data_with_this_user_also_will_be_deleted')):"You want to delete this user? All related data with this user also will be deleted."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to upgrade the system? Please take a backup before going further.</label>
                                    <input type="text" name="you_want_to_upgrade_the_system_please_take_a_backup_before_going_further" value="<?=$this->lang->line('you_want_to_upgrade_the_system_please_take_a_backup_before_going_further')?htmlspecialchars($this->lang->line('you_want_to_upgrade_the_system_please_take_a_backup_before_going_further')):"You want to upgrade the system? Please take a backup before going further."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to activate this user?</label>
                                    <input type="text" name="you_want_to_activate_this_user" value="<?=$this->lang->line('you_want_to_activate_this_user')?htmlspecialchars($this->lang->line('you_want_to_activate_this_user')):"You want to activate this user?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to deactivate this user? This user will be not able to login after deactivation.</label>
                                    <input type="text" name="you_want_to_deactivate_this_user_this_user_will_be_not_able_to_login_after_deactivation" value="<?=$this->lang->line('you_want_to_deactivate_this_user_this_user_will_be_not_able_to_login_after_deactivation')?htmlspecialchars($this->lang->line('you_want_to_deactivate_this_user_this_user_will_be_not_able_to_login_after_deactivation')):"You want to deactivate this user? This user will be not able to login after deactivation."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>We will contact you for further process of payment as soon as possible. Click OK to confirm.</label>
                                    <input type="text" name="we_will_contact_you_for_further_process_of_payment_as_soon_as_possible_click_ok_to_confirm" value="<?=$this->lang->line('we_will_contact_you_for_further_process_of_payment_as_soon_as_possible_click_ok_to_confirm')?htmlspecialchars($this->lang->line('we_will_contact_you_for_further_process_of_payment_as_soon_as_possible_click_ok_to_confirm')):"We will contact you for further process of payment as soon as possible. Click OK to confirm."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Currency code need as per three letter ISO code.</label>
                                    <input type="text" name="currency_code_need_as_per_three_letter_iso_code" value="<?=$this->lang->line('currency_code_need_as_per_three_letter_iso_code')?htmlspecialchars($this->lang->line('currency_code_need_as_per_three_letter_iso_code')):"Currency code need as per three letter ISO code. Make sure payment gateways supporting this currency."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Language?</label>
                                    <input type="text" name="you_want_to_delete_this_language" value="<?=$this->lang->line('you_want_to_delete_this_language')?htmlspecialchars($this->lang->line('you_want_to_delete_this_language')):"You want to delete this Language?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Please check your inbox and confirm your email address to activate your account.</label>
                                    <input type="text" name="please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account" value="<?=$this->lang->line('please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account')?htmlspecialchars($this->lang->line('please_check_your_inbox_and_confirm_your_eamil_address_to_activate_your_account')):'Please check your inbox and confirm your email address to activate your account.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Required email confirmation for new users.</label>
                                    <input type="text" name="required_email_confirmation_for_new_users" value="<?=$this->lang->line('required_email_confirmation_for_new_users')?htmlspecialchars($this->lang->line('required_email_confirmation_for_new_users')):'Required email confirmation for new users'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Make sure to activate the account or ask the user to confirm the email address.</label>
                                    <input type="text" name="make_sure_to_activate_the_account_or_ask_the_user_to_confirm_the_email_address" value="<?=$this->lang->line('make_sure_to_activate_the_account_or_ask_the_user_to_confirm_the_email_address')?htmlspecialchars($this->lang->line('make_sure_to_activate_the_account_or_ask_the_user_to_confirm_the_email_address')):'Make sure to activate the account or ask the user to confirm the email address.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Changes successfully saved.</label>
                                    <input type="text" name="changes_successfully_saved" value="<?=$this->lang->line('changes_successfully_saved')?htmlspecialchars($this->lang->line('changes_successfully_saved')):'Changes successfully saved.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Created successfully.</label>
                                    <input type="text" name="created_successfully" value="<?=$this->lang->line('created_successfully')?htmlspecialchars($this->lang->line('created_successfully')):"Created successfully"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Are you sure?</label>
                                    <input type="text" name="are_you_sure" value="<?=$this->lang->line('are_you_sure')?htmlspecialchars($this->lang->line('are_you_sure')):"Are you sure?"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Updated successfully.</label>
                                    <input type="text" name="updated_successfully" value="<?=$this->lang->line('updated_successfully')?htmlspecialchars($this->lang->line('updated_successfully')):"Updated successfully"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Deleted successfully.</label>
                                    <input type="text" name="deleted_successfully" value="<?=$this->lang->line('deleted_successfully')?htmlspecialchars($this->lang->line('deleted_successfully')):"Deleted successfully"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Use fontawesome icons from fontawesome.com</label>
                                    <input type="text" name="use_fontawesome_icons_from_fontawesome" value="<?=$this->lang->line('use_fontawesome_icons_from_fontawesome')?htmlspecialchars($this->lang->line('use_fontawesome_icons_from_fontawesome')):"Use fontawesome icons from fontawesome.com"?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Best size 400x400px or Square Image.</label>
                                    <input type="text" name="best_size_for_profile_image" value="<?=$this->lang->line('best_size_for_profile_image')?htmlspecialchars($this->lang->line('best_size_for_profile_image')):"Best size 400x400px or Square Image."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Country code with (+) sing.</label>
                                    <input type="text" name="country_code_with_sing" value="<?=$this->lang->line('country_code_with_sing')?htmlspecialchars($this->lang->line('country_code_with_sing')):"Country code with (+) sing."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Country code without (+) sing.</label>
                                    <input type="text" name="country_code_without_sing" value="<?=$this->lang->line('country_code_without_sing')?htmlspecialchars($this->lang->line('country_code_without_sing')):"Country code without (+) sing."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Set zero (0) to hide price.</label>
                                    <input type="text" name="set_zero_to_hide_price" value="<?=$this->lang->line('set_zero_to_hide_price')?htmlspecialchars($this->lang->line('set_zero_to_hide_price')):"Set zero (0) to hide price."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Set value in minus (-1) to make it Unlimited.</label>
                                    <input type="text" name="set_value_in_minus_to_make_it_unlimited" value="<?=$this->lang->line('set_value_in_minus_to_make_it_unlimited')?$this->lang->line('set_value_in_minus_to_make_it_unlimited'):'Set value in minus (-1) to make it Unlimited.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>No card found</label>
                                    <input type="text" name="no_card_found" value="<?=$this->lang->line('no_card_found')?$this->lang->line('no_card_found'):'No card found'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Create a card and come back here.</label>
                                    <input type="text" name="create_a_card_and_come_back_here" value="<?=$this->lang->line('create_a_card_and_come_back_here')?$this->lang->line('create_a_card_and_come_back_here'):'Create a card and come back here.'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label>I agree to the terms and conditions</label>
                                    <input type="text" name="i_agree_to_the_terms_and_conditions" value="<?=$this->lang->line('i_agree_to_the_terms_and_conditions')?htmlspecialchars($this->lang->line('i_agree_to_the_terms_and_conditions')):'I agree to the terms and conditions'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Add details for bank transfer or custom payment</label>
                                    <input type="text" name="add_details_for_bank_transfer_or_custom_payment" value="<?=$this->lang->line('add_details_for_bank_transfer_or_custom_payment')?htmlspecialchars($this->lang->line('add_details_for_bank_transfer_or_custom_payment')):'Add details for bank transfer or custom payment'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Upload and Send for Confirmation</label>
                                    <input type="text" name="upload_and_send_for_confirmation" value="<?=$this->lang->line('upload_and_send_for_confirmation')?htmlspecialchars($this->lang->line('upload_and_send_for_confirmation')):'Upload and Send for Confirmation'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Supported Formats: jpg, jpeg, png</label>
                                    <input type="text" name="supported_formats" value="<?=$this->lang->line('supported_formats')?htmlspecialchars($this->lang->line('supported_formats')):'Supported Formats: jpg, jpeg, png'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>You will be logged out from the current account.</label>
                                    <input type="text" name="you_will_be_logged_out_from_the_current_account" value="<?=$this->lang->line('you_will_be_logged_out_from_the_current_account')?htmlspecialchars($this->lang->line('you_will_be_logged_out_from_the_current_account')):'You will be logged out from the current account.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Please explain your problem in detail. We will get back to you ASAP.</label>
                                    <input type="text" name="please_explain_your_problem_in_detail_we_will_get_back_to_you_ASAP" value="<?=$this->lang->line('please_explain_your_problem_in_detail_we_will_get_back_to_you_ASAP')?htmlspecialchars($this->lang->line('please_explain_your_problem_in_detail_we_will_get_back_to_you_ASAP')):'Please explain your problem in detail. We will get back to you ASAP.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Your account has been successfully created.</label>
                                    <input type="text" name="your_account_has_been_successfully_created" value="<?=$this->lang->line('your_account_has_been_successfully_created')?htmlspecialchars($this->lang->line('your_account_has_been_successfully_created')):'Your account has been successfully created.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Share functionality not supported on this browser, copy link and share.</label>
                                    <input type="text" name="share_functionality_not_supported_on_this_browser" value="<?=$this->lang->line('share_functionality_not_supported_on_this_browser')?htmlspecialchars($this->lang->line('share_functionality_not_supported_on_this_browser')):'Share functionality not supported on this browser, copy link and share.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>This email will be used for inquiry form on vCard. All emails will be forwarded on this email. Leave it blank to disable inquiry form.</label>
                                    <input type="text" name="this_email_will_be_used_for_inquiry_form" value="<?=$this->lang->line('this_email_will_be_used_for_inquiry_form')?htmlspecialchars($this->lang->line('this_email_will_be_used_for_inquiry_form')):'This email will be used for inquiry form on vCard. All emails will be forwarded on this email. Leave it blank to disable inquiry form.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Card cloned successfully.</label>
                                    <input type="text" name="card_cloned_successfully" value="<?=$this->lang->line('card_cloned_successfully')?htmlspecialchars($this->lang->line('card_cloned_successfully')):'Card cloned successfully.'?>" class="form-control">
                                </div>
                                
                                <div class="form-group col-md-12">
                                    <label>Tax deleted successfully.</label>
                                    <input type="text" name="tax_deleted_successfully" value="<?=$this->lang->line('tax_deleted_successfully')?htmlspecialchars($this->lang->line('tax_deleted_successfully')):"Tax deleted successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Tax created successfully.</label>
                                    <input type="text" name="tax_created_successfully" value="<?=$this->lang->line('tax_created_successfully')?htmlspecialchars($this->lang->line('tax_created_successfully')):"Tax created successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Tax updated successfully.</label>
                                    <input type="text" name="tax_updated_successfully" value="<?=$this->lang->line('tax_updated_successfully')?htmlspecialchars($this->lang->line('tax_updated_successfully')):"Tax updated successfully."?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>You want to delete this Tax?</label>
                                    <input type="text" name="you_want_to_delete_this_tax" value="<?=$this->lang->line('you_want_to_delete_this_tax')?htmlspecialchars($this->lang->line('you_want_to_delete_this_tax')):"You want to delete this Tax?"?>" class="form-control">
                                </div>



                                <div class="section-title col-md-12 mt-0">Frontend</div>
                                <div class="form-group col-md-12">
                                    <label>Home (Hero) Title</label>
                                    <input type="text" name="frontend_home_title" value="<?=$this->lang->line('frontend_home_title')?htmlspecialchars($this->lang->line('frontend_home_title')):'The Smart Digital Business Card. Inspire your clients. Digitally.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Home (Hero) Description</label>
                                    <input type="text" name="frontend_home_description" value="<?=$this->lang->line('frontend_home_description')?htmlspecialchars($this->lang->line('frontend_home_description')):'Create and customize stylish digital business cards and share them with anyone, near or far. Smart, elegant & affordable.'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <label>Cookie Message.</label>
                                    <input type="text" name="frontend_cookie_message" value="<?=$this->lang->line('frontend_cookie_message')?htmlspecialchars($this->lang->line('frontend_cookie_message')):'We use cookies to ensure that we give you the best experience on our website.'?>" class="form-control">
                                </div>

                            </div>
                            <div class="card-footer bg-whitesmoke text-md-right">
                                <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?htmlspecialchars($this->lang->line('save_changes')):'Save Changes'?></button>
                            </div>
                            <div class="result"></div>
                        </form>
                    </div>
                </div>




              </div>
            </div>
          </section>
        </div>
      <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<?php $this->load->view('includes/js'); ?>
</body>
</html>
