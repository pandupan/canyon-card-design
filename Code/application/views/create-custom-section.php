<?php $this->load->view('includes/head'); ?>
<link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap-iconpicker/bootstrap-iconpicker.min.css');?>">

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
              <h1><?=$this->lang->line('create_custom_section')?htmlspecialchars($this->lang->line('create_custom_section')):'Create Custom Section'?></h1>
              <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
                <div class="breadcrumb-item active"><a href="<?=base_url('cards/custom-sections')?>"><?=$this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections'?></a></div>
                <div class="breadcrumb-item"><?=$this->lang->line('create_custom_section')?htmlspecialchars($this->lang->line('create_custom_section')):'Create Custom Section'?></div>
              </div>
            </div>

            <div class="section-body">
              <div class="row">

                <div class="col-md-12">
                    <div class="card card-primary" id="custom_section-card">
                        <form action="<?=base_url('cards/create_custom_sections')?>" method="POST" id="custom_section-form">
                            
                            
                          <div class="card-body row">
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
                              <label><?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?><span class="text-danger">*</span></label>
                              <input type="text" name="title" id="title" class="form-control" required>
                              <input type="hidden" name="update_id" id="update_id">
                            </div>

                            <div class="form-group col-md-12">
                              <label><?=$this->lang->line('content')?htmlspecialchars($this->lang->line('content')):'Content'?><span class="text-danger">*</span></label>
                              <textarea type="text" name="content" id="content" class="form-control"></textarea>
                            </div>

                          </div>
                        
                            <div class="card-footer bg-whitesmoke text-md-right">
                                <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
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

<script src="<?=base_url('assets/modules/tinymce/js/tinymce/tinymce.min.js')?>"></script>
<script>

tinymce.init({
  selector: 'textarea',
  height: 240,
  plugins: 'print preview importcss searchreplace autolink autosave save directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount textpattern noneditable help charmap emoticons code',
  menubar: 'edit view insert format tools table tc help',
  toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor permanentpen removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment code',
  setup: function (editor) {
    editor.on("change keyup", function (e) {
        tinyMCE.triggerSave(); 
    });
  },
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});

</script>

</body>
</html>
