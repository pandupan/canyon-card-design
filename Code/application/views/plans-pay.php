<?php $this->load->view('includes/head'); ?>
</head>
<body>
  <div id="app">
    <div class="main-wrapper">
      <?php $this->load->view('includes/navbar'); ?>
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <div class="section-header-back">
              <a href="javascript:history.go(-1)" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>
            <?=$this->lang->line('order_summary')?htmlspecialchars($this->lang->line('order_summary')):'Order Summary'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item active">
              <a href="<?=base_url('plans')?>"><?=$this->lang->line('subscription_plans')?$this->lang->line('subscription_plans'):'Plans'?></a>
              </div>
              <div class="breadcrumb-item">
              <?=$this->lang->line('order_summary')?htmlspecialchars($this->lang->line('order_summary')):'Order Summary'?>
              </div>
            </div>
          </div>
          <div class="section-body">
          
          
            <div class="row">
              <div class="col-md-7 mx-auto paymet-box">
                  <div class="row">
                    <div class="col-md-11 mx-auto">
                      <div class="section-title"><?=$this->lang->line('order_summary')?htmlspecialchars($this->lang->line('order_summary')):'Order Summary'?></div>
                      <p class="section-lead"><?=$this->lang->line('check_your_order_and_select_your_payment_method_from_the_options_below')?htmlspecialchars($this->lang->line('check_your_order_and_select_your_payment_method_from_the_options_below')):'Check your order and select your payment method from the options below.'?></p>
                      <div class="table-responsive">
                        <table class="table table-striped table-hover table-md">
                          <tr>
                            <th><?=$this->lang->line('plan')?htmlspecialchars($this->lang->line('plan')):'Plan'?></th>
                            <th class="text-center"><?=$this->lang->line('payment_type')?htmlspecialchars($this->lang->line('payment_type')):'Payment Type'?></th>
                            <th class="text-right"><?=$this->lang->line('price')?htmlspecialchars($this->lang->line('price')):'Price'?></th>
                          </tr>
                          <tr>
                            <td id="summary_plan"><?=$plan[0]['title']?></td>
                            <td id="summary_payment_type" class="text-center">
                              <?php
                                if($plan[0]["billing_type"] == 'One Time'){
                                  echo $this->lang->line('one_time')?htmlspecialchars($this->lang->line('one_time')):'One Time';
                                }elseif($plan[0]["billing_type"] == 'Monthly'){
                                  echo $this->lang->line('monthly')?htmlspecialchars($this->lang->line('monthly')):'Monthly';
                                }else{
                                  echo $this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly';
                                } 
                              ?>
                            </td>
                            <td id="summary_price" class="text-right"><?=htmlspecialchars(get_saas_currency('currency_symbol'))?><?=$plan[0]['price']?></td>
                          </tr>
                        </table>
                      </div>
                      <div class="row">
                        <div class="col-lg-8"></div>
                        <div class="col-lg-4 text-right">
                          <div class="invoice-detail-item">
                            <?php
                            if($taxes){
                              $tax_pec = 0;
                              foreach($taxes as $key => $tax){ 
                                $tax_pec = $tax_pec+$tax['tax'];
                            ?>
                                <div class="invoice-detail-name font-weight-bold"><?=$tax['title']?> (<?=$tax['tax']?>%)</div>
                                <div class="invoice-detail-value">+ <?=htmlspecialchars(get_saas_currency('currency_symbol'))?><?=$plan[0]['price']*$tax['tax']/100?></div>
                              <?php } 
                            }
                            $tax_amount = $plan[0]['price']*$tax_pec/100;
                            $total_amount = $plan[0]['price']+$tax_amount;
                            ?>
                          </div>
                          <hr class="mt-2 mb-2">
                          <div class="invoice-detail-item">
                            <div class="invoice-detail-name font-weight-bold"><?=$this->lang->line('total')?htmlspecialchars($this->lang->line('total')):'Total'?></div>
                            <div class="invoice-detail-value invoice-detail-value-lg"><?=htmlspecialchars(get_saas_currency('currency_symbol'))?><?=$total_amount?></div>
                          </div>
                        </div>
                      </div>

                      
                      <hr>
                      <div class="text-md-right mb-3">
                        <button class="btn btn-primary btn-icon icon-left payment-button" data-amount="<?=$total_amount?>" data-id="<?=htmlspecialchars($plan[0]['id'])?>"><i class="fas fa-credit-card"></i> <?=$this->lang->line('pay_now')?htmlspecialchars($this->lang->line('pay_now')):'Pay Now'?></button>
                      </div>


                    </div>
                  </div>
              </div>
            </div>

            <div class="row d-none" id="payment-div">

              <div id="paypal-button" class="col-md-7 mx-auto paymet-box"></div>
              
              <?php if(get_stripe_secret_key() && get_stripe_publishable_key()){ ?>
                <button id="stripe-button" class="col-md-7 btn mx-auto paymet-box">
                  <img src="<?=base_url('assets/img/stripe.png')?>" width="14%" alt="Stripe">
                </button>
              <?php } ?>
              <?php if(get_razorpay_key_id()){ ?>
                <button id="razorpay-button" class="col-md-7 btn mx-auto paymet-box">
                    <img src="<?=base_url('assets/img/razorpay.png')?>" width="27%" alt="Stripe">
                </button>
              <?php } ?>
              <?php if(get_paystack_public_key()){ ?>
                <button id="paystack-button" class="col-md-7 btn mx-auto paymet-box">
                  <img src="<?=base_url('assets/img/paystack.png')?>" width="24%" alt="Paystack">
                </button>
              <?php } ?>

              <?php if(get_offline_bank_transfer()){ ?>
                <div id="accordion" class="col-md-7 paymet-box mx-auto">
                  <div class="accordion mb-0">
                    <div class="accordion-header text-center" role="button" data-toggle="collapse" data-target="#panel-body-3">
                      <h4><?=$this->lang->line('offline_bank_transfer')?$this->lang->line('offline_bank_transfer'):'Offline / Bank Transfer'?></h4>
                    </div>
                    <div class="accordion-body collapse" id="panel-body-3" data-parent="#accordion">
                      <p class="mb-0"><?=get_bank_details()?></p>

                      <form action="<?=base_url('plans/create-offline-request/')?>" method="POST" id="bank-transfer-form">
                        <div class="card-footer bg-whitesmoke">
                          <div class="form-group">
                            <label><?=$this->lang->line('upload_receipt')?htmlspecialchars($this->lang->line('upload_receipt')):'Upload Receipt'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('supported_formats')?htmlspecialchars($this->lang->line('supported_formats')):'Supported Formats: jpg, jpeg, png'?>" data-original-title="<?=$this->lang->line('supported_formats')?htmlspecialchars($this->lang->line('supported_formats')):'Supported Formats: jpg, jpeg, png'?>"></i> </label>
                            <input type="file" name="receipt" class="form-control">
                            <input type="hidden" name="plan_id" id="plan_id">
                          </div>
                          <button class="btn btn-primary savebtn"><?=$this->lang->line('upload_and_send_for_confirmation')?htmlspecialchars($this->lang->line('upload_and_send_for_confirmation')):'Upload and Send for Confirmation'?></button>
                        </div>
                        <div class="result"></div>
                      </form>

                    </div>
                  </div>
                </div>
              <?php } ?>

            </div>
          </div>
        </section>
      </div>
      <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>


<form action="<?=base_url('plans/create')?>" method="POST" class="modal-part" id="modal-add-plan-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="row">
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?><span class="text-danger">*</span></label>
      <input type="text" name="title" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('price')?$this->lang->line('price').' - '.get_saas_currency('currency_code'):'Price - '.get_saas_currency('currency_code')?><span class="text-danger">*</span></label>
      <input type="number" name="price" class="form-control">
    </div>
    
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('billing_type')?$this->lang->line('billing_type'):'Billing Type'?><span class="text-danger">*</span></label>
      <select name="billing_type" class="form-control select2">
        <option value="Monthly"><?=$this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly'?></option>
        <option value="Yearly"><?=$this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly'?></option>
        <option value="One Time"><?=$this->lang->line('one_time')?$this->lang->line('one_time'):'One Time'?></option>
        <option value="three_days_trial_plan"><?=$this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan'?></option>
        <option value="seven_days_trial_plan"><?=$this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan'?></option>
        <option value="fifteen_days_trial_plan"><?=$this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan'?></option>
        <option value="thirty_days_trial_plan"><?=$this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan'?></option>
      </select>
    </div>

    <div class="form-group col-md-3">
      <label><?=$this->lang->line('vcards')?htmlspecialchars($this->lang->line('vcards')):'vCards'?><span class="text-danger">*</span></label>
      <input type="number" name="cards_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?>/<?=$this->lang->line('custom_fields')?htmlspecialchars($this->lang->line('custom_fields')):'Custom Fields'?><span class="text-danger">*</span></label>
      <input type="number" name="custom_fields_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products and Services'?><span class="text-danger">*</span></label>
      <input type="number" name="products_services_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?><span class="text-danger">*</span></label>
      <input type="number" name="portfolio_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?><span class="text-danger">*</span></label>
      <input type="number" name="testimonials_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery'?><span class="text-danger">*</span></label>
      <input type="number" name="gallery_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections'?><span class="text-danger">*</span></label>
      <input type="number" name="custom_sections_count" class="form-control">
    </div>

    <div class="form-group col-md-12">
      <small class="form-text text-muted"><?=$this->lang->line('set_value_in_minus_to_make_it_unlimited')?$this->lang->line('set_value_in_minus_to_make_it_unlimited'):'Set value in minus (-1) to make it Unlimited.'?></small>
    </div>

    <div class="form-group col-md-12">
      <h6><?=$this->lang->line('modules')?htmlspecialchars($this->lang->line('modules')):'Modules'?></h6>
    </div>
    
    <div class="form-group col-md-12">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="select_all" name="select_all" >
        <label class="form-check-label" for="select_all"><?=$this->lang->line('select_all')?$this->lang->line('select_all'):'Select All'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="custom_fields" name="custom_fields" >
        <label class="form-check-label" for="custom_fields"><?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?>/<?=$this->lang->line('custom_fields')?htmlspecialchars($this->lang->line('custom_fields')):'Custom Fields'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="products_services" name="products_services" >
        <label class="form-check-label" for="products_services"><?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products/Services'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="portfolio" name="portfolio" >
        <label class="form-check-label" for="portfolio"><?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="testimonials" name="testimonials" >
        <label class="form-check-label" for="testimonials"><?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?></label>
      </div>
    </div>
        
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="gallery" name="gallery" >
        <label class="form-check-label" for="gallery"><?=$this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery'?></label>
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="custom_sections" name="custom_sections" >
        <label class="form-check-label" for="custom_sections"><?=$this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections'?></label>
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="qr_code" name="qr_code" >
        <label class="form-check-label" for="qr_code"><?=$this->lang->line('qr_code')?htmlspecialchars($this->lang->line('qr_code')):'QR Code'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="hide_branding" name="hide_branding" >
        <label class="form-check-label" for="hide_branding"><?=$this->lang->line('hide_branding')?htmlspecialchars($this->lang->line('hide_branding')):'Hide Branding'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="enquiry_form" name="enquiry_form" >
        <label class="form-check-label" for="enquiry_form"><?=$this->lang->line('enquiry_form')?htmlspecialchars($this->lang->line('enquiry_form')):'Enquiry Form'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="support" name="support" >
        <label class="form-check-label" for="support"><?=$this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support'?></label>
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="ads" name="ads" >
        <label class="form-check-label" for="ads"><?=$this->lang->line('no_ads')?htmlspecialchars($this->lang->line('no_ads')):'No Ads'?></label>
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="custom_js_css" name="custom_js_css" >
        <label class="form-check-label" for="custom_js_css"><?=$this->lang->line('custom_js_css')?htmlspecialchars($this->lang->line('custom_js_css')):'Custom JS, CSS'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="search_engine_indexing" name="search_engine_indexing" >
        <label class="form-check-label" for="search_engine_indexing"><?=$this->lang->line('search_engine_indexing')?htmlspecialchars($this->lang->line('search_engine_indexing')):'Search Engine Indexing'?></label>
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="multiple_themes" name="multiple_themes" >
        <label class="form-check-label" for="multiple_themes"><?=$this->lang->line('multiple_themes')?htmlspecialchars($this->lang->line('multiple_themes')):'Multiple Themes'?></label>
      </div>
    </div>

    <?php if(!turn_off_custom_domain_system()){ ?>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="custom_domain" name="custom_domain" >
        <label class="form-check-label" for="custom_domain"><?=$this->lang->line('custom_domain')?htmlspecialchars($this->lang->line('custom_domain')):"Custom Domain"?></label>
      </div>
    </div>
    <?php } ?>

  </div>
</form>

<div id="modal-edit-plan"></div>
<form action="<?=base_url('plans/edit')?>" method="POST" class="modal-part" id="modal-edit-plan-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <div class="row">
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?><span class="text-danger">*</span></label>
      <input type="hidden" name="update_id" id="update_id">
      <input type="text" name="title" id="title" class="form-control" required="">
    </div>
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('price')?$this->lang->line('price').' - '.get_saas_currency('currency_code'):'Price - '.get_saas_currency('currency_code')?><span class="text-danger">*</span></label>
      <input type="number" name="price" id="price" class="form-control">
    </div>
    
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('billing_type')?$this->lang->line('billing_type'):'Billing Type'?>v<span class="text-danger">*</span></label>
      <select name="billing_type" id="billing_type" class="form-control select2">
        <option value="Monthly"><?=$this->lang->line('monthly')?$this->lang->line('monthly'):'Monthly'?></option>
        <option value="Yearly"><?=$this->lang->line('yearly')?$this->lang->line('yearly'):'Yearly'?></option>
        <option value="One Time"><?=$this->lang->line('one_time')?$this->lang->line('one_time'):'One Time'?></option>
        <option value="three_days_trial_plan"><?=$this->lang->line('three_days_trial_plan')?htmlspecialchars($this->lang->line('three_days_trial_plan')):'3 days trial plan'?></option>
        <option value="seven_days_trial_plan"><?=$this->lang->line('seven_days_trial_plan')?htmlspecialchars($this->lang->line('seven_days_trial_plan')):'7 days trial plan'?></option>
        <option value="fifteen_days_trial_plan"><?=$this->lang->line('fifteen_days_trial_plan')?htmlspecialchars($this->lang->line('fifteen_days_trial_plan')):'15 days trial plan'?></option>
        <option value="thirty_days_trial_plan"><?=$this->lang->line('thirty_days_trial_plan')?htmlspecialchars($this->lang->line('thirty_days_trial_plan')):'30 days trial plan'?></option>
      </select>
    </div>

    <div class="form-group col-md-3">
      <label><?=$this->lang->line('vcards')?htmlspecialchars($this->lang->line('vcards')):'vCards'?><span class="text-danger">*</span></label>
      <input type="number" name="cards_count" id="cards_count" class="form-control">
    </div>

    <div class="form-group col-md-3">
      <label><?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?>/<?=$this->lang->line('custom_fields')?htmlspecialchars($this->lang->line('custom_fields')):'Custom Fields'?><span class="text-danger">*</span></label>
      <input type="number" name="custom_fields_count" id="custom_fields_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products and Services'?><span class="text-danger">*</span></label>
      <input type="number" name="products_services_count" id="products_services_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?><span class="text-danger">*</span></label>
      <input type="number" name="portfolio_count" id="portfolio_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?><span class="text-danger">*</span></label>
      <input type="number" name="testimonials_count" id="testimonials_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery'?><span class="text-danger">*</span></label>
      <input type="number" name="gallery_count" id="gallery_count" class="form-control">
    </div>
    <div class="form-group col-md-3">
      <label><?=$this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections'?><span class="text-danger">*</span></label>
      <input type="number" name="custom_sections_count" id="custom_sections_count" class="form-control">
    </div>

    <div class="form-group col-md-12">
      <small class="form-text text-muted"><?=$this->lang->line('set_value_in_minus_to_make_it_unlimited')?$this->lang->line('set_value_in_minus_to_make_it_unlimited'):'Set value in minus (-1) to make it Unlimited.'?></small>
    </div>

    <div class="form-group col-md-12">
      <h6><?=$this->lang->line('modules')?htmlspecialchars($this->lang->line('modules')):'Modules'?></h6>
    </div>
    
    <div class="form-group col-md-12">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="select_all_update" name="select_all" >
        <label class="form-check-label" for="select_all_update"><?=$this->lang->line('select_all')?$this->lang->line('select_all'):'Select All'?></label>
      </div>
    </div>
    
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="custom_fields_update" name="custom_fields" >
        <label class="form-check-label" for="custom_fields_update"><?=$this->lang->line('contact')?htmlspecialchars($this->lang->line('contact')):'Contact'?>/<?=$this->lang->line('custom_fields')?htmlspecialchars($this->lang->line('custom_fields')):'Custom Fields'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="products_services_update" name="products_services" >
        <label class="form-check-label" for="products_services_update"><?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products/Services'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="portfolio_update" name="portfolio" >
        <label class="form-check-label" for="portfolio_update"><?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="testimonials_update" name="testimonials" >
        <label class="form-check-label" for="testimonials_update"><?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="gallery_update" name="gallery" >
        <label class="form-check-label" for="gallery_update"><?=$this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="custom_sections_update" name="custom_sections" >
        <label class="form-check-label" for="custom_sections_update"><?=$this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="qr_code_update" name="qr_code" >
        <label class="form-check-label" for="qr_code_update"><?=$this->lang->line('qr_code')?htmlspecialchars($this->lang->line('qr_code')):'QR Code'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="hide_branding_update" name="hide_branding" >
        <label class="form-check-label" for="hide_branding_update"><?=$this->lang->line('hide_branding')?htmlspecialchars($this->lang->line('hide_branding')):'Hide Branding'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="enquiry_form_update" name="enquiry_form" >
        <label class="form-check-label" for="enquiry_form_update"><?=$this->lang->line('enquiry_form')?htmlspecialchars($this->lang->line('enquiry_form')):'Enquiry Form'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="support_update" name="support" >
        <label class="form-check-label" for="support_update"><?=$this->lang->line('support')?htmlspecialchars($this->lang->line('support')):'Support'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="ads_update" name="ads" >
        <label class="form-check-label" for="ads_update"><?=$this->lang->line('no_ads')?htmlspecialchars($this->lang->line('no_ads')):'No Ads'?></label>
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="custom_js_css_update" name="custom_js_css" >
        <label class="form-check-label" for="custom_js_css_update"><?=$this->lang->line('custom_js_css')?htmlspecialchars($this->lang->line('custom_js_css')):'Custom JS, CSS'?></label>
      </div>
    </div>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="search_engine_indexing_update" name="search_engine_indexing" >
        <label class="form-check-label" for="search_engine_indexing_update"><?=$this->lang->line('search_engine_indexing')?htmlspecialchars($this->lang->line('search_engine_indexing')):'Search Engine Indexing'?></label>
      </div>
    </div>

    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="multiple_themes_update" name="multiple_themes" >
        <label class="form-check-label" for="multiple_themes_update"><?=$this->lang->line('multiple_themes')?htmlspecialchars($this->lang->line('multiple_themes')):'Multiple Themes'?></label>
      </div>
    </div>
    
    <?php if(!turn_off_custom_domain_system()){ ?>
    <div class="form-group col-md-3">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" id="custom_domain_update" name="custom_domain" >
        <label class="form-check-label" for="custom_domain_update"><?=$this->lang->line('custom_domain')?htmlspecialchars($this->lang->line('custom_domain')):"Custom Domain"?></label>
      </div>
    </div>
    <?php } ?>

  </div>
</form>

<?php $this->load->view('includes/js'); ?>

<script>
paypal_client_id = "<?=get_payment_paypal()?>";
get_stripe_publishable_key = "<?=get_stripe_publishable_key()?>";
razorpay_key_id = "<?=get_razorpay_key_id()?>";
offline_bank_transfer = "<?=get_offline_bank_transfer()?>";
paystack_user_email_id = "<?=$this->session->userdata('email')?>";
paystack_public_key = "<?=get_paystack_public_key()?>";
</script>

<?php if(get_payment_paypal()){ ?>
<script src="https://www.paypal.com/sdk/js?client-id=<?=get_payment_paypal()?>&currency=<?=get_saas_currency('currency_code')?>"></script>
<?php } ?>

<?php if(get_stripe_publishable_key()){ ?>
<script src="https://js.stripe.com/v3/"></script>
<?php } ?>

<?php if(get_paystack_public_key()){ ?>
<script src="https://js.paystack.co/v1/inline.js"></script>
<?php } ?>

<?php if(get_razorpay_key_id()){ ?>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<?php } ?>

<script src="<?=base_url('assets/js/page/payment.js');?>"></script>

</body>
</html>
