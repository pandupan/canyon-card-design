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
            <?=$this->lang->line('theme')?$this->lang->line('theme'):'Theme'?> 
            </h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="<?=base_url()?>"><?=$this->lang->line('dashboard')?$this->lang->line('dashboard'):'Dashboard'?></a></div>
              <div class="breadcrumb-item"><?=$this->lang->line('theme')?$this->lang->line('theme'):'Theme'?></div>
            </div>
          </div>
          <div class="section-body">  

              <?php if($card){ if(!$this->ion_auth->in_group(3)){ ?> 
                <div class="row">
                  <div class="col-md-12 form-group">
                    <select class="form-control select2 filter_change">
                      <?php foreach($my_all_cards as $my_all_card){ ?>
                      <option value="<?=base_url('cards/theme/'.$my_all_card['id'])?>" <?=($card['id'] == $my_all_card['id'])?"selected":""?>><?=htmlspecialchars($my_all_card['title'])?> - <?=htmlspecialchars($my_all_card['sub_title'])?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
              <?php } ?> 

              <form action="<?=base_url('cards/save')?>" method="POST" id="save_card" enctype="multipart/form-data">
                <input type="hidden" name="changes_type" value="theme">
                <input type="hidden" name="card_id" value="<?=$card['id']?>">
                <input type="hidden" name="old_theme_image" value="<?=($card['card_theme_bg_type'] == 'Image' && $card['card_theme_bg'] != '')?htmlspecialchars($card['card_theme_bg']):''?>">
                <input type="hidden" name="old_card_image" value="<?=($card['card_bg_type'] == 'Image' && $card['card_bg'] != '')?htmlspecialchars($card['card_bg']):''?>">
                <div class="card card-primary" id="save_card_card">
                  <div class="card-body">

                    <div class="form-group">
                      <div class="row gutters-sm">
                        <div class="col-12 col-sm-3 text-center <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_one')?'':(is_module_allowed('multiple_themes')?'':'d-none')?>">
                          <label class="imagecheck">
                            <input name="theme_name" type="radio" value="theme_one" class="imagecheck-input" <?=(isset($card['theme_name']) && ($card['theme_name'] == 'theme_one' || $card['theme_name'] == ''))?'checked':(is_module_allowed('multiple_themes')?'':' disabled')?> />
                            <figure class="imagecheck-figure">
                              <img src="<?=base_url("assets/uploads/themes/one.png")?>" alt="<?=$this->lang->line('theme_one')?htmlspecialchars($this->lang->line('theme_one')):'Theme One'?>" class="imagecheck-image">
                              <?=$this->lang->line('theme_one')?htmlspecialchars($this->lang->line('theme_one')):'Theme One'?>
                            </figure>
                          </label>
                          <a href="<?=(isset($demo['slug']) && $demo['slug'] != '')?base_url($demo['slug'].'/theme_one'):base_url()?>" target="_blank" class="btn btn-sm btn-icon icon-left btn-primary mt-1 mb-1"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                        </div>
                        <div class="col-12 col-sm-3 text-center <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_two')?'':(is_module_allowed('multiple_themes')?'':'d-none')?>">
                          <label class="imagecheck">
                            <input name="theme_name" type="radio" value="theme_two" class="imagecheck-input" <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_two')?'checked':(is_module_allowed('multiple_themes')?'':' disabled')?> />
                            <figure class="imagecheck-figure">
                              <img src="<?=base_url("assets/uploads/themes/two.png")?>" alt="<?=$this->lang->line('theme_two')?htmlspecialchars($this->lang->line('theme_two')):'Theme Two'?>" class="imagecheck-image">
                              <?=$this->lang->line('theme_two')?htmlspecialchars($this->lang->line('theme_two')):'Theme Two'?>
                            </figure>
                          </label>
                          <a href="<?=(isset($demo['slug']) && $demo['slug'] != '')?base_url($demo['slug'].'/theme_two'):base_url()?>" target="_blank" class="btn btn-sm btn-icon icon-left btn-primary mt-1 mb-1"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                        </div>
                        <div class="col-12 col-sm-3 text-center <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_three')?'':(is_module_allowed('multiple_themes')?'':'d-none')?>">
                          <label class="imagecheck">
                            <input name="theme_name" type="radio" value="theme_three" class="imagecheck-input" <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_three')?'checked':(is_module_allowed('multiple_themes')?'':' disabled')?> />
                            <figure class="imagecheck-figure">
                              <img src="<?=base_url("assets/uploads/themes/three.png")?>" alt="<?=$this->lang->line('theme_three')?htmlspecialchars($this->lang->line('theme_three')):'Theme Three'?>" class="imagecheck-image">
                              <?=$this->lang->line('theme_three')?htmlspecialchars($this->lang->line('theme_three')):'Theme Three'?> 
                            </figure>
                          </label>
                          <a href="<?=(isset($demo['slug']) && $demo['slug'] != '')?base_url($demo['slug'].'/theme_three'):base_url()?>" target="_blank" class="btn btn-sm btn-icon icon-left btn-primary mt-1 mb-1"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                        </div>

                        <div class="col-12 col-sm-3 text-center <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_four')?'':(is_module_allowed('multiple_themes')?'':'d-none')?>">
                          <label class="imagecheck">
                            <input name="theme_name" type="radio" value="theme_four" class="imagecheck-input" <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_four')?'checked':(is_module_allowed('multiple_themes')?'':' disabled')?> />
                            <figure class="imagecheck-figure">
                              <img src="<?=base_url("assets/uploads/themes/four.png")?>" alt="<?=$this->lang->line('theme_four')?htmlspecialchars($this->lang->line('theme_four')):'Theme Four'?>" class="imagecheck-image">
                              <?=$this->lang->line('theme_four')?htmlspecialchars($this->lang->line('theme_four')):'Theme Four'?>
                            </figure>
                          </label>
                          <a href="<?=(isset($demo['slug']) && $demo['slug'] != '')?base_url($demo['slug'].'/theme_four'):base_url()?>" target="_blank" class="btn btn-sm btn-icon icon-left btn-primary mt-1 mb-1"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                        </div>

                        <div class="col-md-12">
                          <h2 class="section-title"><?=$this->lang->line('predefined_themes')?htmlspecialchars($this->lang->line('predefined_themes')):"Predefined Themes"?></h2>
                          <p class="section-lead"><?=$this->lang->line('a_predefined_theme_comes_with_some_predefined_things_like_color_and_background_which_cant_be_changed')?htmlspecialchars($this->lang->line('a_predefined_theme_comes_with_some_predefined_things_like_color_and_background_which_cant_be_changed')):"A predefined theme comes with some predefined things like color and background which can't be changed."?></p>
                        </div>
         

                        <div class="col-12 col-sm-3 text-center <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_five')?'':(is_module_allowed('multiple_themes')?'':'d-none')?>">
                          <label class="imagecheck">
                            <input name="theme_name" type="radio" value="theme_five" class="imagecheck-input" <?=(isset($card['theme_name']) && ($card['theme_name'] == 'theme_five' || $card['theme_name'] == ''))?'checked':(is_module_allowed('multiple_themes')?'':' disabled')?> />
                            <figure class="imagecheck-figure">
                              <img src="<?=base_url("assets/uploads/themes/five.png")?>" alt="<?=$this->lang->line('theme_five')?htmlspecialchars($this->lang->line('theme_five')):'Theme Five'?>" class="imagecheck-image">
                              <?=$this->lang->line('theme_five')?htmlspecialchars($this->lang->line('theme_five')):'Theme Five'?>
                            </figure>
                          </label>
                          <a href="<?=(isset($demo['slug']) && $demo['slug'] != '')?base_url($demo['slug'].'/theme_five'):base_url()?>" target="_blank" class="btn btn-sm btn-icon icon-left btn-primary mt-1 mb-1"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                        </div>
                        <div class="col-12 col-sm-3 text-center <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_six')?'':(is_module_allowed('multiple_themes')?'':'d-none')?>">
                          <label class="imagecheck">
                            <input name="theme_name" type="radio" value="theme_six" class="imagecheck-input" <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_six')?'checked':(is_module_allowed('multiple_themes')?'':' disabled')?> />
                            <figure class="imagecheck-figure">
                              <img src="<?=base_url("assets/uploads/themes/six.png")?>" alt="<?=$this->lang->line('theme_six')?htmlspecialchars($this->lang->line('theme_six')):'Theme Six'?>" class="imagecheck-image">
                              <?=$this->lang->line('theme_six')?htmlspecialchars($this->lang->line('theme_six')):'Theme Six'?>
                            </figure>
                          </label>
                          <a href="<?=(isset($demo['slug']) && $demo['slug'] != '')?base_url($demo['slug'].'/theme_six'):base_url()?>" target="_blank" class="btn btn-sm btn-icon icon-left btn-primary mt-1 mb-1"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                        </div>
                        <div class="col-12 col-sm-3 text-center <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_seven')?'':(is_module_allowed('multiple_themes')?'':'d-none')?>">
                          <label class="imagecheck">
                            <input name="theme_name" type="radio" value="theme_seven" class="imagecheck-input" <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_seven')?'checked':(is_module_allowed('multiple_themes')?'':' disabled')?> />
                            <figure class="imagecheck-figure">
                              <img src="<?=base_url("assets/uploads/themes/seven.png")?>" alt="<?=$this->lang->line('theme_seven')?htmlspecialchars($this->lang->line('theme_seven')):'Theme Seven'?>" class="imagecheck-image">
                              <?=$this->lang->line('theme_seven')?htmlspecialchars($this->lang->line('theme_seven')):'Theme Seven'?> 
                            </figure>
                          </label>
                          <a href="<?=(isset($demo['slug']) && $demo['slug'] != '')?base_url($demo['slug'].'/theme_seven'):base_url()?>" target="_blank" class="btn btn-sm btn-icon icon-left btn-primary mt-1 mb-1"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                        </div>

                        <div class="col-12 col-sm-3 text-center <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_eight')?'':(is_module_allowed('multiple_themes')?'':'d-none')?>">
                          <label class="imagecheck">
                            <input name="theme_name" type="radio" value="theme_eight" class="imagecheck-input" <?=(isset($card['theme_name']) && $card['theme_name'] == 'theme_eight')?'checked':(is_module_allowed('multiple_themes')?'':' disabled')?> />
                            <figure class="imagecheck-figure">
                              <img src="<?=base_url("assets/uploads/themes/eight.png")?>" alt="<?=$this->lang->line('theme_eight')?htmlspecialchars($this->lang->line('theme_eight')):'Theme Eight'?>" class="imagecheck-image">
                              <?=$this->lang->line('theme_eight')?htmlspecialchars($this->lang->line('theme_eight')):'Theme Eight'?>
                            </figure>
                          </label>
                          <a href="<?=(isset($demo['slug']) && $demo['slug'] != '')?base_url($demo['slug'].'/theme_eight'):base_url()?>" target="_blank" class="btn btn-sm btn-icon icon-left btn-primary mt-1 mb-1"><i class="fas fa-eye"></i> <?=$this->lang->line('preview')?htmlspecialchars($this->lang->line('preview')):'Preview'?></a>
                        </div>



                      </div>
                    </div>

                    <div class="row">


                      <div class="form-group col-md-6">
                        <label class="form-label"><?=$this->lang->line('theme_background_type')?htmlspecialchars($this->lang->line('theme_background_type')):'Theme Background Type'?><span class="text-danger">*</span></label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="card_theme_bg_type" value="Color" class="selectgroup-input" <?=(isset($card['card_theme_bg_type']) && ($card['card_theme_bg_type'] == 'Color' || $card['card_theme_bg_type'] == ''))?'checked=""':''?> >
                            <span class="selectgroup-button"><?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?></span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="card_theme_bg_type" value="Image" class="selectgroup-input" <?=(isset($card['card_theme_bg_type']) && $card['card_theme_bg_type'] == 'Image')?'checked=""':''?> >
                            <span class="selectgroup-button"><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?></span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="card_theme_bg_type" value="Gradient" class="selectgroup-input" <?=(isset($card['card_theme_bg_type']) && $card['card_theme_bg_type'] == 'Gradient')?'checked=""':''?> >
                            <span class="selectgroup-button"><?=$this->lang->line('gradient')?htmlspecialchars($this->lang->line('gradient')):'Gradient'?></span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="card_theme_bg_type" value="Transparent" class="selectgroup-input" <?=(isset($card['card_theme_bg_type']) && $card['card_theme_bg_type'] == 'Transparent')?'checked=""':''?> >
                            <span class="selectgroup-button"><?=$this->lang->line('transparent')?htmlspecialchars($this->lang->line('transparent')):'Transparent'?></span>
                          </label>
                        </div>
                      </div>

                      <div id="theme_by_type_color" class="form-group col-md-6 <?=(isset($card['card_theme_bg_type']) && ($card['card_theme_bg_type'] == 'Color' || $card['card_theme_bg_type'] == ''))?'':'d-none'?>">
                        <label><?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?><span class="text-danger">*</span></label>
                        <input type="color" name="theme_color" value="<?=($card['card_theme_bg_type'] == 'Color' && $card['card_theme_bg'] != '')?htmlspecialchars($card['card_theme_bg']):theme_color()?>" class="form-control">
                      </div>

                      <span id="theme_by_type_image" class="col-md-6 <?=(isset($card['card_theme_bg_type']) && $card['card_theme_bg_type'] == 'Image')?'':'d-none'?>">
                        <span class="row">
                          <div class="form-group col-md-6">
                            <label class="form-label"><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?><span class="text-danger">*</span></label>
                            <input type="file" name="theme_image" class="form-control">
                          </div>
                          <div class="form-group col-md-6 my-auto <?=($card['card_theme_bg'] != '' && file_exists('assets/uploads/card-bg/'.htmlspecialchars($card['card_theme_bg'])))?'':'d-none'?>">
                            <img alt="Theme Image" src="<?=base_url('assets/uploads/card-bg/'.htmlspecialchars($card['card_theme_bg']))?>" class="system-logos" style="width: 45%;">
                          </div>
                        </span>
                      </span>
                      
                      <span id="theme_by_type_gradient" class="col-md-6 <?=(isset($card['card_theme_bg_type']) && $card['card_theme_bg_type'] == 'Gradient')?'':'d-none'?>">
                        <span class="row">
                          <div class="form-group col-md-6">
                            <label class="form-label"><?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?> 1<span class="text-danger">*</span></label>
                            <input type="color" name="color_1" value="<?=($card['card_theme_bg_type'] == 'Gradient' && isset($card['color_1']) && $card['color_1'] != '')?htmlspecialchars($card['color_1']):theme_color()?>" class="form-control">
                          </div>
                          <div class="form-group col-md-6">
                            <label class="form-label"><?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?> 2<span class="text-danger">*</span></label>
                            <input type="color" name="color_2" value="<?=($card['card_theme_bg_type'] == 'Gradient' && isset($card['color_2']) && $card['color_2'] != '')?htmlspecialchars($card['color_2']):theme_color()?>" class="form-control">
                          </div>
                        </span>
                      </span>


                      
                      <div class="form-group col-md-6">
                        <label class="form-label"><?=$this->lang->line('card_background_type')?htmlspecialchars($this->lang->line('card_background_type')):'Card Background Type'?><span class="text-danger">*</span></label>
                        <div class="selectgroup w-100">
                          <label class="selectgroup-item">
                            <input type="radio" name="card_bg_type" value="Color" class="selectgroup-input" <?=(isset($card['card_bg_type']) && ($card['card_bg_type'] == 'Color' || $card['card_bg_type'] == ''))?'checked=""':''?> >
                            <span class="selectgroup-button"><?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?></span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="card_bg_type" value="Image" class="selectgroup-input" <?=(isset($card['card_bg_type']) && $card['card_bg_type'] == 'Image')?'checked=""':''?> >
                            <span class="selectgroup-button"><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?></span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="card_bg_type" value="Gradient" class="selectgroup-input" <?=(isset($card['card_bg_type']) && $card['card_bg_type'] == 'Gradient')?'checked=""':''?> >
                            <span class="selectgroup-button"><?=$this->lang->line('gradient')?htmlspecialchars($this->lang->line('gradient')):'Gradient'?></span>
                          </label>
                          <label class="selectgroup-item">
                            <input type="radio" name="card_bg_type" value="Transparent" class="selectgroup-input" <?=(isset($card['card_bg_type']) && $card['card_bg_type'] == 'Transparent')?'checked=""':''?> >
                            <span class="selectgroup-button"><?=$this->lang->line('transparent')?htmlspecialchars($this->lang->line('transparent')):'Transparent'?></span>
                          </label>
                        </div>
                      </div>

                      <div id="card_by_type_color" class="form-group col-md-6 <?=(isset($card['card_bg_type']) && ($card['card_bg_type'] == 'Color' || $card['card_bg_type'] == ''))?'':'d-none'?>">
                        <label><?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?><span class="text-danger">*</span></label>
                        <input type="color" name="card_color" value="<?=(isset($card['card_bg_type']) && $card['card_bg_type'] == 'Color' && isset($card['card_bg']) && $card['card_bg'] != '')?htmlspecialchars($card['card_bg']):'#ffffff'?>" class="form-control">
                      </div>

                      <span id="card_by_type_image" class="col-md-6 <?=(isset($card['card_bg_type']) && $card['card_bg_type'] == 'Image')?'':'d-none'?>">
                        <span class="row">
                          <div class="form-group col-md-6">
                            <label class="form-label"><?=$this->lang->line('image')?htmlspecialchars($this->lang->line('image')):'Image'?><span class="text-danger">*</span></label>
                            <input type="file" name="card_image" class="form-control">
                          </div>
                          <div class="form-group col-md-6 my-auto <?=(isset($card['card_bg']) && $card['card_bg'] != '' && file_exists('assets/uploads/card-bg/'.htmlspecialchars($card['card_bg'])))?'':'d-none'?>">
                            <img alt="card Image" src="<?=(isset($card['card_bg']) && $card['card_bg'] != '' && file_exists('assets/uploads/card-bg/'.htmlspecialchars($card['card_bg'])))?base_url('assets/uploads/card-bg/'.htmlspecialchars($card['card_bg'])):'d-none'?>" class="system-logos" style="width: 45%;">
                          </div>
                        </span>
                      </span>
                      
                      <span id="card_by_type_gradient" class="col-md-6 <?=(isset($card['card_bg_type']) && $card['card_bg_type'] == 'Gradient')?'':'d-none'?>">
                        <span class="row">
                          <div class="form-group col-md-6">
                            <label class="form-label"><?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?> 1<span class="text-danger">*</span></label>
                            <input type="color" name="card_color_1" value="<?=(isset($card['card_bg_type']) && $card['card_bg_type'] == 'Gradient' && isset($card['card_color_1']) && $card['card_color_1'] != '')?htmlspecialchars($card['card_color_1']):'#ffffff'?>" class="form-control">
                          </div>
                          <div class="form-group col-md-6">
                            <label class="form-label"><?=$this->lang->line('color')?htmlspecialchars($this->lang->line('color')):'Color'?> 2<span class="text-danger">*</span></label>
                            <input type="color" name="card_color_2" value="<?=(isset($card['card_bg_type']) && $card['card_bg_type'] == 'Gradient' && isset($card['card_color_2']) && $card['card_color_2'] != '')?htmlspecialchars($card['card_color_2']):'#ffffff'?>" class="form-control">
                          </div>
                        </span>
                      </span>



                      
                      <div class="form-group col-md-6">
                        <label class="form-label"><?=$this->lang->line('card_font_color')?htmlspecialchars($this->lang->line('card_font_color')):'Card Font Color'?><span class="text-danger">*</span></label>
                        <input type="color" name="card_font_color" value="<?=(isset($card['card_font_color']) && $card['card_font_color'] != '')?htmlspecialchars($card['card_font_color']):'#000000'?>" class="form-control">
                      </div>
                      <div class="form-group col-md-6">
                        <label class="form-label"><?=$this->lang->line('card_font')?htmlspecialchars($this->lang->line('card_font')):'Card Font'?></label>
                        <select name="card_font" class="form-control">
                          <option value="" <?=(isset($card['card_font']) && $card['card_font'] == '')?'selected':''?>><?=$this->lang->line('default')?htmlspecialchars($this->lang->line('default')):'Default'?> - Nunito</option>
                          <option value="Anton" <?=(isset($card['card_font']) && $card['card_font'] == 'Anton')?'selected':''?>>Anton</option>
                          <option value="Bebas Neue" <?=(isset($card['card_font']) && $card['card_font'] == 'Bebas Neue')?'selected':''?>>Bebas Neue</option>
                          <option value="Dongle" <?=(isset($card['card_font']) && $card['card_font'] == 'Dongle')?'selected':''?>>Dongle</option>
                          <option value="Lato" <?=(isset($card['card_font']) && $card['card_font'] == 'Lato')?'selected':''?>>Lato</option>
                          <option value="Lobster" <?=(isset($card['card_font']) && $card['card_font'] == 'Lobster')?'selected':''?>>Lobster</option>
                          <option value="Lora" <?=(isset($card['card_font']) && $card['card_font'] == 'Lora')?'selected':''?>>Lora</option>
                          <option value="Montserrat" <?=(isset($card['card_font']) && $card['card_font'] == 'Montserrat')?'selected':''?>>Montserrat</option>
                          <option value="Open Sans" <?=(isset($card['card_font']) && $card['card_font'] == 'Open Sans')?'selected':''?>>Open Sans</option>
                          <option value="Oswald" <?=(isset($card['card_font']) && $card['card_font'] == 'Oswald')?'selected':''?>>Oswald</option>
                          <option value="Pacifico" <?=(isset($card['card_font']) && $card['card_font'] == 'Pacifico')?'selected':''?>>Pacifico</option>
                          <option value="Poppins" <?=(isset($card['card_font']) && $card['card_font'] == 'Poppins')?'selected':''?>>Poppins</option>
                          <option value="PT Sans" <?=(isset($card['card_font']) && $card['card_font'] == 'PT Sans')?'selected':''?>>PT Sans</option>
                          <option value="Raleway" <?=(isset($card['card_font']) && $card['card_font'] == 'Raleway')?'selected':''?>>Raleway</option>
                          <option value="Roboto" <?=(isset($card['card_font']) && $card['card_font'] == 'Roboto')?'selected':''?>>Roboto</option>
                        </select>
                      </div>

                    </div>


                  </div>
                  <div class="card-footer bg-#ffffffsmoke text-md-right">
                    <?php if(isset($card['slug']) && $card['slug'] != ''){ ?>
                      <a href="<?=base_url($card['slug'])?>" class="btn btn-icon icon-left btn-success copy_href"><i class="fas fa-copy"></i> <?=$this->lang->line('copy')?htmlspecialchars($this->lang->line('copy')):'Copy Card URL'?></a>
                    <?php } ?>
                    <?php if(isset($card['slug']) && $card['slug'] != ''){ ?>
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