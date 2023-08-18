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
            <?=$this->lang->line('domain_request')?htmlspecialchars($this->lang->line('domain_request')):"Domain Request"?>
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('domain_request')?htmlspecialchars($this->lang->line('domain_request')):"Domain Request"?></div>
            </div>
          </div>

          <div class="section-body">



            <h2 class="section-title"><?=$this->lang->line('note')?htmlspecialchars($this->lang->line('note')):"Note"?></h2>
            <p class="section-lead"><?=$this->lang->line('before_activating_a_domain_make_sure_you_have_added_a_domain_to_your_hosting_with_proper_directory_access')?htmlspecialchars($this->lang->line('before_activating_a_domain_make_sure_you_have_added_a_domain_to_your_hosting_with_proper_directory_access')):"Before activating a domain make sure you have added a domain to your hosting with proper directory access."?>
            </p>

          
            <div class="row">

              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-body"> 
                    <table class='table-striped' id='cards_list'
                      data-toggle="table"
                      data-url="<?=base_url('cards/get_domain_request')?>"
                      data-click-to-select="true"
                      data-side-pagination="server"
                      data-pagination="true"
                      data-page-list="[5, 10, 20, 50, 100, 200]"
                      data-search="true" data-show-columns="false"
                      data-show-refresh="false" data-trim-on-search="false"
                      data-sort-name="id" data-sort-order="DESC"
                      data-mobile-responsive="true"
                      data-toolbar="#tool" data-show-export="false"
                      data-maintain-selected="true"
                      data-export-types='["txt","excel"]'
                      data-export-options='{
                        "fileName": "cards-list",
                        "ignoreColumn": ["state"] 
                      }'
                      data-query-params="queryParams">
                      <thead>
                        <tr>
                          <th data-field="custom_domain" data-sortable="true"><?=$this->lang->line('custom_domain')?htmlspecialchars($this->lang->line('custom_domain')):"Custom Domain"?></th>

                          <th data-field="title" data-sortable="true"><?=$this->lang->line('vcards')?htmlspecialchars($this->lang->line('vcards')):'vCards'?></th>

                          <th data-field="first_name" data-sortable="true"><?=$this->lang->line('user')?htmlspecialchars($this->lang->line('user')):'User'?></th>

                          <th data-field="action" data-sortable="false"><?=$this->lang->line('action')?$this->lang->line('action'):'Action'?></th>
                        </tr>
                      </thead>
                    </table>
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


  <div id="modal-edit-card"></div>

<form action="<?=base_url('cards/save')?>" method="POST" class="modal-part" id="modal-edit-card-part" data-title="<?=$this->lang->line('edit')?$this->lang->line('edit'):'Edit'?>" data-btn="<?=$this->lang->line('update')?$this->lang->line('update'):'Update'?>">
  <div class="row">
    
    <input type="hidden" name="card_id" id="card_id">
    <input type="hidden" name="changes_type" value="custom_domain">

    <div class="form-group col-md-8">
      <label><?=$this->lang->line('custom_domain')?htmlspecialchars($this->lang->line('custom_domain')):"Custom Domain"?> <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" data-original-title="<?=$this->lang->line('allowed_domain_formats_yourdomain_com_and_subdomain_yourdomain_com')?htmlspecialchars($this->lang->line('allowed_domain_formats_yourdomain_com_and_subdomain_yourdomain_com')):'Allowed Domain Formats: yourdomain.com and subdomain.yourdomain.com'?>"></i></label>
      <input type="text" name="custom_domain" id="custom_domain" placeholder="yourdomain.com" value="" class="form-control">
    </div>
    <div class="form-group col-md-4">
      <label><?=$this->lang->line('status')?htmlspecialchars($this->lang->line('status')):'Status'?></label>
      <select name="custom_domain_status" id="custom_domain_status" class="form-control" <?=!$this->ion_auth->in_group(3)?'disabled=""':''?>>
        <option value="0"><?=$this->lang->line('deactive')?htmlspecialchars($this->lang->line('deactive')):'Deactive'?></option>
        <option value="1"><?=$this->lang->line('active')?htmlspecialchars($this->lang->line('active')):'Active'?></option>
      </select>
      <input type="hidden" name="custom_domain_old" value="">
    </div>

  </div>
</form>

<?php $this->load->view('includes/js'); ?>
  
</body>
</html>