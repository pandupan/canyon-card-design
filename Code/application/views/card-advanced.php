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
            <?=$this->lang->line('advanced')?htmlspecialchars($this->lang->line('advanced')):'Advanced'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('advanced')?htmlspecialchars($this->lang->line('advanced')):'Advanced'?></div>
            </div>
          </div>

          <div class="section-body">

              <?php if($card){  if(!$this->ion_auth->in_group(3)){ ?> 
                <div class="row">
                  <div class="col-md-12 form-group">
                    <select class="form-control select2 filter_change">
                      <?php foreach($my_all_cards as $my_all_card){ ?>
                      <option value="<?=base_url('cards/advanced/'.$my_all_card['id'])?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php } ?> 

              <form action="<?=base_url('cards/save')?>" method="POST" id="save_card" enctype="multipart/form-data">
                <input type="hidden" name="changes_type" value="advanced">
                <input type="hidden" name="card_id" value="<?=$card['id']?>">

                <div class="card card-primary" id="save_card_card">
                  <div class="card-body">

                    <div class="row">

                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('enquiry_email')?htmlspecialchars($this->lang->line('enquiry_email')):'Enquiry Email'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="" data-original-title="<?=$this->lang->line('this_email_will_be_used_for_inquiry_form')?htmlspecialchars($this->lang->line('this_email_will_be_used_for_inquiry_form')):'This email will be used for inquiry form on vCard. All emails will be forwarded on this email. Leave it blank to disable inquiry form.'?>"></i></label>
                        <input type="text" name="enquery_email" placeholder="youremail@domail.com" value="<?=htmlspecialchars($card['enquery_email'])?>" class="form-control">
                      </div>

                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('google_analytics')?htmlspecialchars($this->lang->line('google_analytics')):'Google Analytics'?></label>
                        <input type="text" name="google_analytics" placeholder="UA-187266009-9" value="<?=htmlspecialchars($card['google_analytics'])?>" class="form-control">
                      </div>

                      <hr class="col-md-12"></hr>

                      <?php if(is_module_allowed('hide_branding')){ ?>
                      <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="hide_branding" name="hide_branding" <?=(isset($card['hide_branding']) && $card['hide_branding'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="hide_branding"><?=$this->lang->line('hide_branding')?htmlspecialchars($this->lang->line('hide_branding')):'Hide Branding'?></label>
                        </div>
                      </div>
                      <?php } ?>

                      <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="show_add_to_phone_book" name="show_add_to_phone_book" <?=(isset($card['show_add_to_phone_book']) && $card['show_add_to_phone_book'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="show_add_to_phone_book"><?=$this->lang->line('show_add_to_phone_book')?htmlspecialchars($this->lang->line('show_add_to_phone_book')):'Show Add to Phone Book'?></label>
                        </div>
                      </div>
                      <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="show_share" name="show_share" <?=(isset($card['show_share']) && $card['show_share'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="show_share"><?=$this->lang->line('show_share')?htmlspecialchars($this->lang->line('show_share')):'Show Share Button'?></label>
                        </div>
                      </div>
                      <?php if(is_module_allowed('qr_code')){ ?>
                      <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="show_qr_on_card" name="show_qr_on_card" <?=(isset($card['show_qr_on_card']) && $card['show_qr_on_card'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="show_qr_on_card"><?=$this->lang->line('show_qr_on_card')?htmlspecialchars($this->lang->line('show_qr_on_card')):'Show QR Code on Card'?></label>
                        </div>
                      </div>
                      <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="show_qr_on_share_popup" name="show_qr_on_share_popup" <?=(isset($card['show_qr_on_share_popup']) && $card['show_qr_on_share_popup'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="show_qr_on_share_popup"><?=$this->lang->line('show_qr_on_share_popup')?htmlspecialchars($this->lang->line('show_qr_on_share_popup')):'Show QR Code on Share Popup'?></label>
                        </div>
                      </div>
                      <?php } ?>

                      <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="show_card_view_count_on_a_card" name="show_card_view_count_on_a_card" <?=(isset($card['show_card_view_count_on_a_card']) && $card['show_card_view_count_on_a_card'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="show_card_view_count_on_a_card"><?=$this->lang->line('show_card_view_count_on_a_card')?htmlspecialchars($this->lang->line('show_card_view_count_on_a_card')):'Show card view count on a card'?></label>
                        </div>
                      </div>

                      <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="show_change_language_option_on_a_card" name="show_change_language_option_on_a_card" <?=(isset($card['show_change_language_option_on_a_card']) && $card['show_change_language_option_on_a_card'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="show_change_language_option_on_a_card"><?=$this->lang->line('show_change_language_option_on_a_card')?htmlspecialchars($this->lang->line('show_change_language_option_on_a_card')):'Show change language option on a card'?></label>
                        </div>
                      </div>

                      <?php if(is_module_allowed('search_engine_indexing')){ ?>
                      <div class="form-group col-md-3">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="search_engine_indexing" name="search_engine_indexing" <?=(isset($card['search_engine_indexing']) && $card['search_engine_indexing'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="search_engine_indexing"><?=$this->lang->line('search_engine_indexing')?htmlspecialchars($this->lang->line('search_engine_indexing')):'Search Engine Indexing'?></label>
                        </div>
                      </div>
                      <?php } ?>

                      <div class="form-group col-md-6">
                        <div class="form-check form-check-inline">
                          <input class="form-check-input" type="checkbox" id="make_setions_conetnt_carousel" name="make_setions_conetnt_carousel" <?=(isset($card['make_setions_conetnt_carousel']) && $card['make_setions_conetnt_carousel'] == 1)?'checked':''?>>
                          <label class="form-check-label" for="make_setions_conetnt_carousel"><?=$this->lang->line('make_setions_conetnt_carousel')?htmlspecialchars($this->lang->line('make_setions_conetnt_carousel')):"Make section content carousel."?> (<?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products and Services'?>, <?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?>)</label>
                        </div>
                      </div>

                      <?php if(is_module_allowed('custom_js_css')){ ?>
                      <hr class="col-md-12"></hr>
                      <div class="form-group col-md-6">
                          <label><?=$this->lang->line('custom_css')?htmlspecialchars($this->lang->line('custom_css')):'Custom CSS'?></label>
                          <textarea name="custom_css" id="custom_css"><?=isset($card['custom_css'])?htmlspecialchars($card['custom_css']):''?></textarea>
                      </div>
                      <div class="form-group col-md-6">
                        <label><?=$this->lang->line('custom_js')?htmlspecialchars($this->lang->line('custom_js')):'Custom JS'?></label>
                        <textarea name="custom_js" id="custom_js"><?=isset($card['custom_js'])?htmlspecialchars($card['custom_js']):''?></textarea>
                      </div>
                      <?php } ?>

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

<script>
    CodeMirror.fromTextArea(document.getElementById('custom_css'), { 
      lineNumbers: true,
      theme: 'duotone-dark',
    }).on('change', editor => {
      $("#custom_css").val(editor.getValue());
    });

    CodeMirror.fromTextArea(document.getElementById('custom_js'), { 
      lineNumbers: true,
      theme: 'duotone-dark',
    }).on('change', editor => {
      $("#custom_js").val(editor.getValue());
    });
</script>

</body>
</html>