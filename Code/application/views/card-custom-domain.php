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
            <?=$this->lang->line('custom_domain')?htmlspecialchars($this->lang->line('custom_domain')):"Custom Domain"?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('custom_domain')?htmlspecialchars($this->lang->line('custom_domain')):"Custom Domain"?></div>
            </div>
          </div>

          <div class="section-body">

            <h2 class="section-title"><?=$this->lang->line('note')?htmlspecialchars($this->lang->line('note')):"Note"?></h2>
            <p class="section-lead">
              
            <?=$this->lang->line('make_sure_that_your_domain_or_subdomain_has_a_cname_record_pointing_to_our')?htmlspecialchars($this->lang->line('make_sure_that_your_domain_or_subdomain_has_a_cname_record_pointing_to_our')):"Make sure that your domain or subdomain has a CNAME record pointing to our"?>
            <code><?=filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING)?></code>
            <?=$this->lang->line('or')?htmlspecialchars($this->lang->line('or')):"OR"?>
            <?=$this->lang->line('a_record_pointing_to_our')?htmlspecialchars($this->lang->line('a_record_pointing_to_our')):"A record pointing to our"?>
            <code><?=gethostbyname(filter_var($_SERVER['HTTP_HOST'], FILTER_SANITIZE_STRING))?></code>
            </p>
            
            <?php if($card){  if(!$this->ion_auth->in_group(3)){ ?> 
              <div class="row">
                <div class="col-md-12 form-group">
                  <select class="form-control select2 filter_change">
                    <?php foreach($my_all_cards as $my_all_card){ ?>
                    <option value="<?=base_url('cards/custom-domain/'.$my_all_card['id'])?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            <?php } ?> 

            <form action="<?=base_url('cards/save')?>" method="POST" id="save_card" enctype="multipart/form-data">
              <input type="hidden" name="changes_type" value="custom_domain">
              <input type="hidden" name="card_id" value="<?=$card['id']?>">
              <div class="card card-primary">

                <div class="card-body">
                  <div class="row">

                    <div class="form-group col-md-8">
                      <label><?=$this->lang->line('custom_domain')?htmlspecialchars($this->lang->line('custom_domain')):"Custom Domain"?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" data-original-title="<?=$this->lang->line('allowed_domain_formats_yourdomain_com_and_subdomain_yourdomain_com')?htmlspecialchars($this->lang->line('allowed_domain_formats_yourdomain_com_and_subdomain_yourdomain_com')):'Allowed Domain Formats: yourdomain.com and subdomain.yourdomain.com'?>"></i></label>
                      <input type="text" name="custom_domain" placeholder="yourdomain.com" value="<?=htmlspecialchars($card['custom_domain'])?>" class="form-control">
                    </div>
                    <div class="form-group col-md-4">
                      <label><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></label>
                      <select name="custom_domain_status" class="form-control" <?=!$this->ion_auth->in_group(3)?'disabled=""':''?>>
                        <option value="0" <?=(isset($card['custom_domain_status']) && $card['custom_domain_status']==0)?'selected':''?>><?=$this->lang->line('deactive')?htmlspecialchars($this->lang->line('deactive')):'Deactive'?></option>
                        <option value="1" <?=(isset($card['custom_domain_status']) && $card['custom_domain_status']==1)?'selected':''?>><?=$this->lang->line('active')?htmlspecialchars($this->lang->line('active')):'Active'?></option>
                      </select>
                      <input type="hidden" name="custom_domain_old" value="<?=htmlspecialchars($card['custom_domain'])?>">
                    </div>

                    <?php if(isset($card['slug']) && $card['slug'] != ''){ ?>
                      <div class="form-group col-md-12">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" id="custom_domain_redirect" name="custom_domain_redirect" <?=(isset($card['custom_domain_redirect']) && $card['custom_domain_redirect'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="custom_domain_redirect">
                          <?=$this->lang->line('automatically_redirect_my_card_on_this_custom_domain_from')?htmlspecialchars($this->lang->line('automatically_redirect_my_card_on_this_custom_domain_from')):'Automatically redirect my card on this custom domain from'?> <code><?=base_url($card['slug'])?></code>
                          </label>
                        </div>
                      </div>
                    <?php } ?>

                    <div class="form-group mb-0 col-md-12">
                      <div class="alert alert-danger">
                        <b><?=$this->lang->line('note')?htmlspecialchars($this->lang->line('note')):"Note"?>:</b> <?=$this->lang->line('custom_domains_require_approval_from_the_administrator_you_will_be_informed_about_the_status_update_through_notification')?htmlspecialchars($this->lang->line('custom_domains_require_approval_from_the_administrator_you_will_be_informed_about_the_status_update_through_notification')):"Custom domains require approval from the administrator. You will be informed about the status update through notification."?>
                      </div>
                    </div>


                  </div>
                </div>
                
                <div class="card-footer bg-whitesmoke text-md-right">
                  <?php if(isset($card['slug']) && $card['slug'] != ''){ ?>
                    <a href="<?=base_url($card['slug'])?>" class="btn btn-icon icon-left btn-success copy_href"><i class="fas fa-copy"></i> <?=$this->lang->line('copy')?htmlspecialchars($this->lang->line('copy')):'Copy Card URL'?></a>
                    <a href="<?=base_url($card['slug'])?>" target="_blank" class="btn btn-icon icon-left btn-danger"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                  <?php } ?>

                  <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
                  
                </div>
                
                <div class="result"></div>
              </div>
            </form>

              <?php }else{ ?> 
                <div class="row">
                  <div class="col-12 col-md-12 col-sm-12">
                    <div class="card">
                      <div class="card-body">
                        <div class="empty-state" data-height="400" style="height: 400px;">
                          <h2><?=$this->lang->line('no_card_found')?$this->lang->line('no_card_found'):'No card found'?></h2>
                          <p class="lead">
                          <?=$this->lang->line('create_a_card_and_come_back_here')?$this->lang->line('create_a_card_and_come_back_here'):'Create a card and come back here.'?>
                          </p>
                          <a href="<?=base_url('cards');?>" class="btn btn-primary mt-4"><?=$this->lang->line('create')?htmlspecialchars($this->lang->line('create')):'Create'?></a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php } ?> 

          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<?php $this->load->view('includes/js'); ?>
  
</body>
</html>