<?php $this->load->view('includes/head'); ?>
<link href="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.css" rel="stylesheet">
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
              <?php if(isset($_GET['reorder'])){ ?>
                <?=$this->lang->line('reorder')?htmlspecialchars($this->lang->line('reorder')):'Reorder'?>
              <?php } ?>

              <?=$this->lang->line('contact_details')?htmlspecialchars($this->lang->line('contact_details')):'Contact Details'?>
              <?php if(!$this->ion_auth->in_group(3)){ if(my_plan_features('card_fields')){ ?> 
                <a href="#" id="modal-add-contact_details" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } }else{ ?>
                <a href="#" id="modal-add-contact_details" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('contact_details')?htmlspecialchars($this->lang->line('contact_details')):'Contact Details'?></div>
            </div>
          </div>

          <div class="section-body">

          <?php if(isset($_GET['reorder'])){ ?>
            <h2 class="section-title"><?=$this->lang->line('drag_and_rop')?htmlspecialchars($this->lang->line('drag_and_rop')):'Drag and Drop'?></h2>
            <p class="section-lead"><?=$this->lang->line('drag_and_rop_row_from_below_table_to_reorder_content')?htmlspecialchars($this->lang->line('drag_and_rop_row_from_below_table_to_reorder_content')):'Drag and Drop row from below table to reorder content.'?></p>
          <?php } ?>
            
            <?php if($card){  if(!$this->ion_auth->in_group(3)){ ?> 
              <div class="row">
                <div class="col-md-12 form-group">
                  <select class="form-control select2 filter_change">
                    <?php foreach($my_all_cards as $my_all_card){ ?>
                    <option value="<?=base_url('cards/details/'.$my_all_card['id'])?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <?php } ?> 

              <div class="card card-primary">
                <?php if(!isset($_GET['reorder'])){ ?>
                  <div class="card-header">
                    <div class="card-header-action">
                      <a href="<?=base_url('cards/details?reorder')?>" class="btn btn-primary"><?=$this->lang->line('reorder')?htmlspecialchars($this->lang->line('reorder')):'Reorder'?></a>
                    </div>
                  </div>
                <?php }else{ ?>
                  <div class="card-header">
                    <div class="card-header-action">
                      <a href="<?=base_url('cards/details')?>" class="btn btn-primary"><?=$this->lang->line('go_back')?htmlspecialchars($this->lang->line('go_back')):'Go Back'?></a>
                    </div>
                  </div>
                <?php } ?>

                <div class="card-body">



                        <table class='table-striped' id='custom_fields_list'
                            data-toggle="table"
                            data-url="<?=base_url('cards/get_custom_fields')?>"
                            data-click-to-select="true"

                            <?php if(isset($_GET['reorder'])){ ?>
                              data-use-row-attr-func="true"
                              data-reorderable-rows="true"
                            <?php } ?>

                            data-side-pagination="server"
                            data-pagination="false"
                            data-page-list="[5, 10, 20, 50, 100, 200]"
                            data-search="false" data-show-columns="false"
                            data-show-refresh="false" data-trim-on-search="false"
                            data-sort-name="id" data-sort-order="asc"
                            data-mobile-responsive="true"
                            data-toolbar="" data-show-export="false"
                            data-maintain-selected="true"
                            data-export-types='["txt","excel"]'
                            data-export-options='{
                              "fileName": "custom_fields-list",
                              "ignoreColumn": ["state"] 
                            }'
                            data-query-params="queryParams">
                            <thead>
                              <tr>
                                <th data-field="type" data-sortable="true"><?=$this->lang->line('field_type')?htmlspecialchars($this->lang->line('field_type')):'Field Type'?></th>
                                <th data-field="icon" data-sortable="true"><?=$this->lang->line('icon')?$this->lang->line('icon'):'Icon'?></th>
                                <th data-field="title" data-sortable="true"><?=$this->lang->line('title')?$this->lang->line('title'):'Title'?></th>
                                <th data-field="url" data-sortable="true"><?=$this->lang->line('url')?$this->lang->line('url'):'URL'?></th>

                                <?php if(!isset($_GET['reorder'])){ ?>
                                  <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
                                <?php } ?>

                              </tr>
                            </thead>
                        </table>




                </div>
                <div class="card-footer bg-whitesmoke text-md-right">
                  <?php if(isset($card['slug']) && $card['slug'] != ''){ ?>
                    <a href="<?=base_url($card['slug'])?>" class="btn btn-icon icon-left btn-success copy_href"><i class="fas fa-copy"></i> <?=$this->lang->line('copy')?htmlspecialchars($this->lang->line('copy')):'Copy Card URL'?></a>
                    <a href="<?=base_url($card['slug'])?>" target="_blank" class="btn btn-icon icon-left btn-danger"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                  <?php } ?>
                </div>
              </div>

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


<form action="<?=base_url('cards/create_custom_fields')?>" method="POST" class="modal-part" id="modal-add-contact_details-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
  <div class="row">
    <?php if(!$this->ion_auth->in_group(3)){ ?>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('select_vcard')?htmlspecialchars($this->lang->line('select_vcard')):'Select vCard'?><span class="text-danger">*</span></label>
        <select class="form-control select2" name="card_id">
          <?php foreach($my_all_cards as $my_all_card){ ?>
          <option value="<?=$my_all_card['id']?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
          <?php } ?>
        </select>
      </div>
    <?php }else{ ?>
      <input type="hidden" name="card_id" value="<?=htmlspecialchars($card['id'])?>" class="form-control">
    <?php } ?>

    
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('select_field_type')?htmlspecialchars($this->lang->line('select_field_type')):'Select Field Type'?><span class="text-danger">*</span></label>
      <select class="form-control custom_fields select2" name="type">
        <option value=""><?=$this->lang->line('add_block_or_social_field')?htmlspecialchars($this->lang->line('add_block_or_social_field')):'Add Block or Social Field'?></option>
        <option value="mobile"><?=$this->lang->line('mobile')?htmlspecialchars($this->lang->line('mobile')):'Mobile'?> / <?=$this->lang->line('phone')?htmlspecialchars($this->lang->line('phone')):'Phone'?></option>
        <option value="email"><?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?></option>
        <option value="address"><?=$this->lang->line('address')?htmlspecialchars($this->lang->line('address')):'Address'?></option>
        <option value="whatsapp">WhatsApp</option>
        <option value="linkedin">LinkedIn</option>
        <option value="website"><?=$this->lang->line('website')?htmlspecialchars($this->lang->line('website')):'Website'?></option>
        <option value="facebook">Facebook</option>
        <option value="twitter">Twitter</option>
        <option value="instagram">Instagram</option>
        <option value="telegram">Telegram</option>
        <option value="skype">Skype</option>
        <option value="youtube">YouTube</option>
        <option value="tiktok">TikTok</option>
        <option value="snapchat">Snapchat</option>
        <option value="paypal">PayPal</option>
        <option value="github">Github</option>
        <option value="pinterest">Pinterest</option>
        <option value="wechat">WeChat</option>
        <option value="signal">Signal</option>
        <option value="discord">Discord</option>
        <option value="reddit">Reddit</option>
        <option value="spotify">Spotify</option>
        <option value="vimeo">Vimeo</option>
        <option value="soundcloud">Soundcloud</option>
        <option value="dribbble">Dribbble</option>
        <option value="behance">Behance</option>
        <option value="flickr">Flickr</option>
        <option value="twitch">Twitch</option>
        <option value="custom"><?=$this->lang->line('custom_url')?htmlspecialchars($this->lang->line('custom_url')):'Custom URL'?></option>
      </select>
    </div>

  </div>
  <div class="row input_fields_wrap"></div>
</form>


<div id="modal-edit-contact_details"></div>

<form action="<?=base_url('cards/edit_custom_fields')?>" method="POST" class="modal-part" id="modal-edit-contact_details-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <div class="row">
    <?php if(!$this->ion_auth->in_group(3)){ ?>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('select_vcard')?htmlspecialchars($this->lang->line('select_vcard')):'Select vCard'?><span class="text-danger">*</span></label>
        <select class="form-control select2" name="card_id">
          <?php foreach($my_all_cards as $my_all_card){ ?>
          <option value="<?=$my_all_card['id']?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
          <?php } ?>
        </select>
      </div>
    <?php }else{ ?>
        <input type="hidden" name="card_id" value="<?=htmlspecialchars($card['id'])?>" class="form-control">
    <?php } ?>

    <input type="hidden" name="update_id" id="update_id">
        
    <div class="form-group col-md-12">
      <label><?=$this->lang->line('select_field_type')?htmlspecialchars($this->lang->line('select_field_type')):'Select Field Type'?><span class="text-danger">*</span></label>
      <select class="form-control custom_fields select2" name="type" id="type">
        <option value=""><?=$this->lang->line('add_block_or_social_field')?htmlspecialchars($this->lang->line('add_block_or_social_field')):'Add Block or Social Field'?></option>
        <option value="mobile"><?=$this->lang->line('mobile')?htmlspecialchars($this->lang->line('mobile')):'Mobile'?> / <?=$this->lang->line('phone')?htmlspecialchars($this->lang->line('phone')):'Phone'?></option>
        <option value="email"><?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?></option>
        <option value="address"><?=$this->lang->line('address')?htmlspecialchars($this->lang->line('address')):'Address'?></option>
        <option value="whatsapp">WhatsApp</option>
        <option value="linkedin">LinkedIn</option>
        <option value="website"><?=$this->lang->line('website')?htmlspecialchars($this->lang->line('website')):'Website'?></option>
        <option value="facebook">Facebook</option>
        <option value="twitter">Twitter</option>
        <option value="instagram">Instagram</option>
        <option value="telegram">Telegram</option>
        <option value="skype">Skype</option>
        <option value="youtube">YouTube</option>
        <option value="tiktok">TikTok</option>
        <option value="snapchat">Snapchat</option>
        <option value="paypal">PayPal</option>
        <option value="github">Github</option>
        <option value="pinterest">Pinterest</option>
        <option value="wechat">WeChat</option>
        <option value="signal">Signal</option>
        <option value="discord">Discord</option>
        <option value="reddit">Reddit</option>
        <option value="spotify">Spotify</option>
        <option value="vimeo">Vimeo</option>
        <option value="soundcloud">Soundcloud</option>
        <option value="dribbble">Dribbble</option>
        <option value="behance">Behance</option>
        <option value="flickr">Flickr</option>
        <option value="twitch">Twitch</option>
        <option value="custom"><?=$this->lang->line('custom_url')?htmlspecialchars($this->lang->line('custom_url')):'Custom URL'?></option>
      </select>
    </div>
  </div>

  <div class="row input_fields_wrap"></div>
</form>

<?php $this->load->view('includes/js'); ?>

<script>
function queryParams(p){
	return {
		card_id:<?php echo $card['id']; ?>,
		limit:p.limit,
		sort:p.sort,
		order:p.order,
		offset:p.offset,
		search:p.search
	};
}
</script>

<script src="<?=base_url('assets/modules/bootstrap-iconpicker/bootstrap-iconpicker.min.js');?>"></script>

<script src="<?=base_url('assets/js/page/card-details.js')?>"></script>

<script src="https://cdn.jsdelivr.net/npm/tablednd@1.0.5/dist/jquery.tablednd.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js"></script>
<script>
  $('#custom_fields_list').bootstrapTable({
    onReorderRow: function (data) {
        $.ajax({
				type: "POST",
				url: base_url+'cards/order_custom_fields/', 
				data: "data="+JSON.stringify(data),
				dataType: "json",
				success: function(result) 
				{	
				  if(result['error'] == false){
            
				  }else{
					iziToast.error({
					  title: result['message'],
					  message: "",
					  position: 'topRight'
					});
				  }
				}        
			});
    }
  })

</script>



</body>
</html>