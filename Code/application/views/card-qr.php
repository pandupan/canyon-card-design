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
            <?=$this->lang->line('qr_code')?htmlspecialchars($this->lang->line('qr_code')):'QR Code'?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('qr_code')?htmlspecialchars($this->lang->line('qr_code')):'QR Code'?></div>
            </div>
          </div>
          <div class="section-body">

            <?php if(!$this->ion_auth->in_group(3)){ ?> 
              <div class="row">
                <div class="col-md-12 form-group">
                  <select class="form-control select2 filter_change">
                    <?php foreach($my_all_cards as $my_all_card){ ?>
                    <option value="<?=base_url('cards/qr/'.$my_all_card['id'])?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            <?php } ?> 

            <div class="card card-primary">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                  <form action="<?=base_url('cards/save')?>" method="POST" id="save_card" enctype="multipart/form-data">
                    <input type="hidden" name="changes_type" value="qr_code">
                    <input type="hidden" name="card_id" value="<?=$card['id']?>">

                    <div class="form-group">
                      <label class="form-label"><?=$this->lang->line('foreground_color')?htmlspecialchars($this->lang->line('foreground_color')):"Foreground Color"?></label>
                      <input type="color" name="foreground_color" value="<?=isset($card['qr_code_options']['foreground_color'])?htmlspecialchars($card['qr_code_options']['foreground_color']):'#000000'?>" class="form-control foreground_color">
                    </div>
                    <div class="form-group">
                      <label class="form-label"><?=$this->lang->line('background_color')?htmlspecialchars($this->lang->line('background_color')):"Background Color"?></label>
                      <input type="color" name="background_color" value="<?=isset($card['qr_code_options']['background_color'])?htmlspecialchars($card['qr_code_options']['background_color']):'#ffffff'?>" class="form-control background_color">
                    </div>
                    <div class="form-group">
                      <label><?=$this->lang->line('corner_radius')?htmlspecialchars($this->lang->line('corner_radius')):"Corner Radius"?></label>
                      <input type="range" name="corner_radius" class="form-control range corner_radius" min="1" max="50" step="1" value="<?=isset($card['qr_code_options']['corner_radius'])?htmlspecialchars($card['qr_code_options']['corner_radius']):''?>">
                    </div>
                    <div class="form-group">
                      <label><?=$this->lang->line('qr_type')?htmlspecialchars($this->lang->line('qr_type')):"QR Type"?></label>
                      <select name="qr_type" id="qr_type" class="form-control">
                        <option value="0" <?=(isset($card['qr_code_options']['qr_type']) && $card['qr_code_options']['qr_type'] == 0)?'selected':''?>><?=$this->lang->line('normal')?htmlspecialchars($this->lang->line('normal')):"Normal"?></option>
                        <option value="2" <?=(isset($card['qr_code_options']['qr_type']) && $card['qr_code_options']['qr_type'] == 2)?'selected':''?>><?=$this->lang->line('text')?htmlspecialchars($this->lang->line('text')):'Text'?></option>
                        <option value="4" <?=(isset($card['qr_code_options']['qr_type']) && $card['qr_code_options']['qr_type'] == 4)?'selected':''?>><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?></option>
                      </select>
                    </div>

                    <span class="" id="qr_type_option">
                      <span class="" id="text_div">
                        <div class="form-group">
                            <label><?=$this->lang->line('text')?htmlspecialchars($this->lang->line('text')):'Text'?></label>
                            <input type="text" name="text" value="<?=isset($card['qr_code_options']['text'])?htmlspecialchars($card['qr_code_options']['text']):''?>" class="form-control text">
                        </div>
                        <div class="form-group">
                          <label class="form-label"><?=$this->lang->line('text_color')?htmlspecialchars($this->lang->line('text_color')):'Text Color'?></label>
                          <input type="color" name="text_color" value="<?=isset($card['qr_code_options']['text_color'])?htmlspecialchars($card['qr_code_options']['text_color']):''?>" class="form-control text_color">
                        </div>
                      </span>
                      
                      <div class="form-group " id="image_div">
                        <label><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('supported_formats')?htmlspecialchars($this->lang->line('supported_formats')):'Supported Formats: jpg, jpeg, png'?>"></i></label>
                        <input type="file" name="image" accept=".png, .jpg, .jpeg" class="form-control image">
                        <input type="hidden" name="old_image" value="<?=isset($card['qr_code_options']['image'])?htmlspecialchars($card['qr_code_options']['image']):''?>">
                        <img id="image-buffer" name="image_buffer" src="<?=isset($card['qr_code_options']['image'])?base_url('assets/uploads/qr-img/'.htmlspecialchars($card['qr_code_options']['image'])):''?>" class="d-none">
                      </div>

                      <div class="form-group " id="size_div">
                        <label><?=$this->lang->line('size')?htmlspecialchars($this->lang->line('size')):"Size"?></label>
                        <input type="range" name="size" class="form-control range size" min="1" max="50" step="1" value="<?=isset($card['qr_code_options']['size'])?htmlspecialchars($card['qr_code_options']['size']):''?>">
                      </div>
                    </span>

                    <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
                  

                    <div class="result mt-1"></div>
                    </form>
                  </div>

                  <div class="col-md-6">
                    <div class="col-md-12 code">
                    </div>
                    <div class="col-md-12 text-center">
                      <button class="btn btn-icon icon-left btn-outline-dark download_my_qr_code"><?=$this->lang->line('download_my_qr_code')?htmlspecialchars($this->lang->line('download_my_qr_code')):'Download My QR Code'?></button>
                    </div>
                  </div>

                </div>
              </div>
              <div class="card-footer bg-whitesmoke text-md-right">
                <?php if(isset($card['slug']) && $card['slug'] != ''){ ?>
                  <a href="<?=base_url($card['slug'])?>" class="btn btn-icon icon-left btn-success copy_href"><i class="fas fa-copy"></i> <?=$this->lang->line('copy')?htmlspecialchars($this->lang->line('copy')):'Copy Card URL'?></a>
                  <a href="<?=base_url($card['slug'])?>" target="_blank" class="btn btn-icon icon-left btn-danger"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                <?php } ?>
              </div>
            </div>
          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>


<?php $this->load->view('includes/js'); ?>

<script src="<?=base_url('assets/modules/jquery.qrcode.min.js?v='.time())?>"></script>

<script>
var card_url = '<?=isset($card['slug'])?base_url('cards/scan/'.$card['slug']):base_url()?>';
</script>

<script src="<?=base_url('assets/js/page/qr.js?v='.time())?>"></script>

</body>
</html>