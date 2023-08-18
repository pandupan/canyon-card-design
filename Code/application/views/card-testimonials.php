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
            <?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?>
            <?php if(!$this->ion_auth->in_group(3)){ if(my_plan_features('testimonials')){ ?> 
              <a href="#" id="modal-add-testimonials" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } }else{ ?>
                <a href="#" id="modal-add-testimonials" class="btn btn-sm btn-icon icon-left btn-primary"><i class="fas fa-plus"></i> <?=$this->lang->line('create')?$this->lang->line('create'):'Create'?></a>
              <?php } ?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?></div>
            </div>
          </div>

          <div class="section-body">

          <?php if(isset($_GET['reorder'])){ ?>
              <h2 class="section-title"><?=$this->lang->line('drag_and_rop')?htmlspecialchars($this->lang->line('drag_and_rop')):'Drag and Drop'?></h2>
              <p class="section-lead"><?=$this->lang->line('drag_and_rop_row_from_below_table_to_reorder_content')?htmlspecialchars($this->lang->line('drag_and_rop_row_from_below_table_to_reorder_content')):'Drag and Drop row from below table to reorder content.'?></p>
            <?php } ?>

            <?php if(!$this->ion_auth->in_group(3)){ ?> 
              <div class="row">
                <div class="col-md-12 form-group">
                  <select class="form-control select2 filter_change">
                    <?php foreach($my_all_cards as $my_all_card){ ?>
                    <option value="<?=base_url('cards/testimonials/'.$my_all_card['id'])?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            <?php } ?> 
          
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <?php if(!isset($_GET['reorder'])){ ?>
                    <div class="card-header">
                      <div class="card-header-action">
                        <a href="<?=base_url('cards/testimonials?reorder')?>" class="btn btn-primary"><?=$this->lang->line('reorder')?htmlspecialchars($this->lang->line('reorder')):'Reorder'?></a>
                      </div>
                    </div>
                  <?php }else{ ?>
                    <div class="card-header">
                      <div class="card-header-action">
                        <a href="<?=base_url('cards/testimonials')?>" class="btn btn-primary"><?=$this->lang->line('go_back')?htmlspecialchars($this->lang->line('go_back')):'Go Back'?></a>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="card-body">
                    <table class='table-striped' id='testimonials_list'
                      data-toggle="table"
                      data-url="<?=base_url('cards/get_testimonials')?>"
                      data-click-to-select="true"
                      <?php if(isset($_GET['reorder'])){ ?>
                        data-use-row-attr-func="true"
                        data-reorderable-rows="true"
                      <?php } ?>
                      data-side-pagination="server"
                      data-pagination="false"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-search="true" data-show-columns="false"
                      data-show-refresh="false" data-trim-on-search="false"
                      data-sort-name="id" data-sort-order="DESC"
                      data-mobile-responsive="true"
                      data-toolbar="" data-show-export="false"
                      data-maintain-selected="true"
                      data-export-types='["txt","excel"]'
                      data-export-options='{
                        "fileName": "testimonials-list",
                        "ignoreColumn": ["state"] 
                      }'
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="title" data-sortable="true"><?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?></th>
                          <th data-field="description" data-sortable="true"><?=$this->lang->line('description')?htmlspecialchars($this->lang->line('description')):'Description'?></th>
                          <th data-field="rating" data-sortable="true"><?=$this->lang->line('rating')?htmlspecialchars($this->lang->line('rating')):'Rating'?></th>
                          <th data-field="image" data-sortable="true"><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?></th>
                          
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
              </div>
            </div>



          </div>
        </section>
      </div>
    
    <?php $this->load->view('includes/footer'); ?>
    </div>
  </div>

<form action="<?=base_url('cards/create_testimonials')?>" method="POST" class="modal-part" id="modal-add-testimonials-part" data-title="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>" data-btn="<?=$this->lang->line('create')?$this->lang->line('create'):'Create'?>">
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
      <label><?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?><span class="text-danger">*</span></label>
      <input type="text" name="title" class="form-control" required>
    </div>

    <div class="form-group col-md-12">
      <label><?=$this->lang->line('description')?htmlspecialchars($this->lang->line('description')):'Description'?><span class="text-danger">*</span></label>
      <textarea type="text" name="description" class="form-control"></textarea>
    </div>
    
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('rating')?htmlspecialchars($this->lang->line('rating')):'Rating'?><span class="text-danger">*</span></label>
      <select name="rating" class="form-control">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
    </div>
    
    <div class="form-group col-md-6">
      <label class="form-label"><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?></label>
      <input type="file" name="image" class="form-control">
    </div>

  </div>
</form>

<div id="modal-edit-testimonials"></div>

<form action="<?=base_url('cards/edit_testimonials')?>" method="POST" class="modal-part" id="modal-edit-testimonials-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
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
      <label><?=$this->lang->line('title')?htmlspecialchars($this->lang->line('title')):'Title'?><span class="text-danger">*</span></label>
      <input type="text" name="title" id="title" class="form-control" required>
      <input type="hidden" name="update_id" id="update_id">
      <input type="hidden" name="old_image" id="old_image">
    </div>

    <div class="form-group col-md-12">
      <label><?=$this->lang->line('description')?htmlspecialchars($this->lang->line('description')):'Description'?><span class="text-danger">*</span></label>
      <textarea type="text" name="description" id="description" class="form-control"></textarea>
    </div>
    
    <div class="form-group col-md-6">
      <label><?=$this->lang->line('rating')?htmlspecialchars($this->lang->line('rating')):'Rating'?><span class="text-danger">*</span></label>
      <select name="rating" id="rating" class="form-control">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>
    </div>

    <div class="form-group col-md-6">
      <label class="form-label"><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="<?=$this->lang->line('leave_empty_for_no_changes')?htmlspecialchars($this->lang->line('leave_empty_for_no_changes')):"Leave empty for no changes."?>"></i></label>
      <input type="file" name="image" class="form-control">
    </div>

  </div>
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
<script src="https://cdn.jsdelivr.net/npm/tablednd@1.0.5/dist/jquery.tablednd.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.3/dist/extensions/reorder-rows/bootstrap-table-reorder-rows.min.js"></script>
<script>
  $('#testimonials_list').bootstrapTable({
    onReorderRow: function (data) {
        var vall = [];
        
        $.each(data, function( key, value ) {
          vall[key] = value.id
        });

        $.ajax({
				type: "POST",
				url: base_url+'cards/order_testimonials/', 
				data: "data="+JSON.stringify(vall),
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