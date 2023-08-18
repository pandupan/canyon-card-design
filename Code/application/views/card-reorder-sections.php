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
            <?=$this->lang->line('reorder_sections')?htmlspecialchars($this->lang->line('reorder_sections')):"Reorder Sections"?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('reorder_sections')?htmlspecialchars($this->lang->line('reorder_sections')):"Reorder Sections"?></div>
            </div>
          </div>

          <div class="section-body">

            <h2 class="section-title"><?=$this->lang->line('drag_and_rop')?htmlspecialchars($this->lang->line('drag_and_rop')):'Drag and Drop'?></h2>
            <p class="section-lead"><?=$this->lang->line('drag_and_rop_row_from_below_table_to_reorder_content')?htmlspecialchars($this->lang->line('drag_and_rop_row_from_below_table_to_reorder_content')):'Drag and Drop row from below table to reorder content.'?></p>
            
            <?php if($card){  if(!$this->ion_auth->in_group(3)){ ?> 
              <div class="row">
                <div class="col-md-12 form-group">
                  <select class="form-control select2 filter_change">
                    <?php foreach($my_all_cards as $my_all_card){ ?>
                    <option value="<?=base_url('cards/reorder-sections/'.$my_all_card['id'])?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            <?php } ?> 

            <form action="<?=base_url('cards/save')?>" method="POST" id="save_card" enctype="multipart/form-data">
              <input type="hidden" name="changes_type" value="reorder_sections">
              <input type="hidden" name="card_id" value="<?=$card['id']?>">
              <div class="card card-primary">

                <div class="card-body">
                  <ul id="sortable" class="list-group">
                  <?php 
                  if(isset($card['reorder_sections']) && !empty($card['reorder_sections'])){ 
                    $reorder_sections = json_decode($card['reorder_sections']);
                    foreach($reorder_sections as $reorder_section){
                      if($reorder_section == 'main_card_section'){ ?>
                        <li class="list-group-item"><?=$this->lang->line('main_card_section')?htmlspecialchars($this->lang->line('main_card_section')):"Main Card Section"?> <input type="hidden" name="reorder_sections[]" value="main_card_section"></li>
                      <?php }elseif($reorder_section == 'products_services'){ ?>
                        <li class="list-group-item"><?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products and Services'?> <input type="hidden" name="reorder_sections[]" value="products_services"></li>
                      <?php }elseif($reorder_section == 'portfolio'){ ?>
                        <li class="list-group-item"><?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?> <input type="hidden" name="reorder_sections[]" value="portfolio"></li>
                      <?php }elseif($reorder_section == 'gallery'){ ?>
                        <li class="list-group-item"><?=$this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery'?> <input type="hidden" name="reorder_sections[]" value="gallery"></li>
                      <?php }elseif($reorder_section == 'testimonials'){ ?>
                        <li class="list-group-item"><?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?> <input type="hidden" name="reorder_sections[]" value="testimonials"></li>
                      <?php }elseif($reorder_section == 'qr_code'){ ?>
                        <li class="list-group-item"><?=$this->lang->line('qr_code')?htmlspecialchars($this->lang->line('qr_code')):'QR Code'?> <input type="hidden" name="reorder_sections[]" value="qr_code"></li>
                      <?php }elseif($reorder_section == 'enquiry_form'){ ?>
                        <li class="list-group-item"><?=$this->lang->line('enquiry_form')?htmlspecialchars($this->lang->line('enquiry_form')):'Enquiry Form'?> <input type="hidden" name="reorder_sections[]" value="enquiry_form"></li>
                      <?php }elseif($reorder_section == 'custom_sections'){ ?>
                        <li class="list-group-item"><?=$this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections'?> <input type="hidden" name="reorder_sections[]" value="custom_sections"></li>
                      <?php }
                    } 
                  } ?>
                  </ul>
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

<script src="<?=base_url('assets/modules/jquery-ui.js')?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script>
$( function() {
  $( "#sortable" ).sortable();
} );
</script>
  
</body>
</html>