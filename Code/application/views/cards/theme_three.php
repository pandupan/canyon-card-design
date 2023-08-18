<?php $this->load->view('cards/style'); ?>
<link rel="stylesheet" href="<?=base_url('assets/css/cards/theme-three.css')?>">
</head>
<body>
    
    <div class="container pt-5">

        <?php if(isset($card['reorder_sections']) && !empty($card['reorder_sections'])){  
        $reorder_sections = json_decode($card['reorder_sections']);
        foreach($reorder_sections as $reorder_section){

        if($reorder_section == 'main_card_section'){ ?>

            <?=(isset($ad_code) && !empty($ad_code) && in_array('before', $ad_area))?'<div class="row d-flex justify-content-center">'.$ad_code.'</div>':''?>
            <div class="row pt-4 mb-1">
                <div class="col-xl-5 col-md-6 col-sm-10 mx-auto shadow rounded p-0">
                    <div class="card card-profile shadow mb-0">

                        
                        <?php if($card['show_card_view_count_on_a_card'] == 1 || $card['show_change_language_option_on_a_card'] == 1){ ?>
                            <span class="float-lang">

                                <?php if($card['show_card_view_count_on_a_card'] == 1){ ?>
                                    <button type="button" class="btn btn-sm btn-icon icon-left btn-outline-dark">
                                        <i class="fas fa-eye m-0"><?=$this->lang->line('views')?htmlspecialchars($this->lang->line('views')):'Views'?>: <?=isset($card['views'])?htmlspecialchars($card['views']):0?></i> 
                                    </button>
                                <?php } ?>

                                <?php $languages = get_languages('', '', 1);
                                    if($languages && $card['show_change_language_option_on_a_card'] == 1){ ?>
                                    <div class="btn-group dropleft">
                                        <button type="button" class="btn btn-sm btn-icon icon-left btn-outline-dark dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-language"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                        <?php foreach($languages as $language){  ?>
                                            <a href="<?=base_url('languages/change/'.$language['language'])?>" class="dropdown-item"><?=ucfirst($language['language'])?></a>
                                        <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </span>
                        <?php } ?>
                         

                        <div class="row justify-content-center">
                            <div class="col-lg-3 order-lg-2">
                                <div class="card-profile-image">
                                    <img src="<?=isset($meta_image)?htmlspecialchars($meta_image):''?>" alt="<?=isset($card['title'])?htmlspecialchars($card['title']):''?>" class="rounded-circle">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row pt-4 mt-4">
                                <div class="col">
                                    <div class="card-profile-stats d-flex justify-content-center mt-5 pb-0">
                                        <div>
                                            <span class="heading"><?=isset($card['title'])?htmlspecialchars($card['title']):''?></span>
                                            <span class="description"><?=isset($card['sub_title'])?htmlspecialchars($card['sub_title']):''?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="h5">
                                <?=isset($card['description'])?htmlspecialchars($card['description']):''?>
                                </div>
                            </div>
                            <hr>

                            <ul class="contact-details">

                                <?php
                                $vcard_mobile = array();
                                $vcard_email = array();
                                $vcard_addr = array();
                                $vcard_links = array();
                                if(($card['id'] == 1 && isset($custom_fields) && $custom_fields != '') || (isset($custom_fields) && $custom_fields != '')){ foreach($custom_fields as $key => $custom_field){
                                    $type = $custom_field['type'];
                                    $icon = $custom_field['icon'];
                                    $text = $custom_field['title'];
                                    $url = $custom_field['url'];
                                    include 'social_field.php'; ?>
                                    <li>
                                        <a href="<?=(isset($edite_url) && $edite_url != '')?htmlspecialchars($edite_url):'#'?>" <?=(isset($target_blank) && $target_blank != '')?'target="_blank"':''?> class="media contact-details-item">
                                        <span class="mr-3 icon-circle"><i class="m-0 <?=(isset($icon) && $icon != '')?htmlspecialchars($icon):'fa fa-hand-holding-heart'?>"></i></span>
                                        <h6 class="mt-0 mb-0"><?=(isset($text) && $text != '')?htmlspecialchars($text):((isset($edite_url) && $edite_url != '')?htmlspecialchars($edite_url):'')?></h6></a>
                                    </li>
                                <?php } } ?>

                            </ul>

                            <hr>
                            <div class="row justify-content-center">
                            <?php if($card['show_add_to_phone_book'] == 1){ ?>
                            <a id="download-file" download="<?=isset($card['title'])?htmlspecialchars($card['title']):''?>.vcf" href="#" class="btn btn-sm btn-icon icon-left btn-outline-dark col-md-5 mt-1 mr-1 ml-1"><i class="fas fa-download"></i> <?=$this->lang->line('add_to_phone_book')?htmlspecialchars($this->lang->line('add_to_phone_book')):'Add to Phone Book'?></a>
                            <?php } ?>
                            <?php if($card['show_share'] == 1){ ?>
                            <a data-toggle="modal" data-target="#socialShare" href="#" class="btn btn-sm btn-icon icon-left btn-outline-dark col-md-5 mt-1 mr-1 ml-1"><i class="fas fa-share-alt"></i> <?=$this->lang->line('share')?htmlspecialchars($this->lang->line('share')):'Share'?></a>
                            <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <?=(isset($ad_code) && !empty($ad_code) && in_array('after', $ad_area))?'<div class="row d-flex justify-content-center">'.$ad_code.'</div>':''?>

        <?php }elseif($reorder_section == 'products_services'){ ?>

            <?php if(($card['id'] == 1 || ($this->data['card_plan_modules'] && $this->data['card_plan_modules']['products_services'])) && isset($products) && $products != ''){ ?>
            <div class="row mb-1">
                <div class="col-xl-5 col-md-6 col-sm-10 mx-auto shadow rounded p-0">
                    <div class="card p-2 mb-0">
                        <div class="card-header d-flex justify-content-center p-0">
                            <h4><?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products/Services'?></h4>
                        </div>
                        
                        <?php if(isset($card['make_setions_conetnt_carousel']) && $card['make_setions_conetnt_carousel'] != 1){ ?>

                        <?php foreach($products as $product){ ?>
                            <div class="col-md-12">
                                <article class="article article-style-b border rounded mb-3">
                                    <div class="article-header">
                                        <div class="article-image" style="background-image: url('<?=$product['image']!=""?base_url("assets/uploads/product-image/".$product['image']):''?>');">
                                        </div>
                                        <?php if(is_numeric($product['price']) && $product['price'] == 0){ }else{ ?>
                                        <div class="article-badge">
                                            <div class="article-badge-item"><?=$this->lang->line('price')?htmlspecialchars($this->lang->line('price')):'Price'?>: <?=$product['price']?></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="article-details">
                                        <div class="article-title">
                                        <h2><a href="<?=$product['url']!=""?htmlspecialchars($product['url']):'#'?>" <?=$product['url']=="" || $product['url']=="#" || $product['url']=="#enquiryform"?'':'target="_blank"'?>><?=htmlspecialchars($product['title'])?></a></h2>
                                        </div>
                                        <p><?=htmlspecialchars($product['description'])?></p>
                                        <div class="article-cta">
                                        <a href="<?=$product['url']!=""?htmlspecialchars($product['url']):'#'?>" <?=$product['url']=="" || $product['url']=="#" || $product['url']=="#enquiryform"?'':'target="_blank"'?>><?=$this->lang->line('enquiry')?htmlspecialchars($this->lang->line('enquiry')):'Enquiry'?> <i class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php } ?>

                        <?php }else{ ?>

                        <div class="col-md-12 owl-carousel owl-theme products-carousel">
                            <?php foreach($products as $product){ ?>
                                <article class="article article-style-b border rounded mb-3">
                                    <div class="article-header">
                                        <div class="article-image" style="background-image: url('<?=$product['image']!=""?base_url("assets/uploads/product-image/".$product['image']):''?>');">
                                        </div>
                                        <?php if(is_numeric($product['price']) && $product['price'] == 0){ }else{ ?>
                                        <div class="article-badge">
                                            <div class="article-badge-item"><?=$this->lang->line('price')?htmlspecialchars($this->lang->line('price')):'Price'?>: <?=$product['price']?></div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="article-details">
                                        <div class="article-title">
                                        <h2><a href="<?=$product['url']!=""?htmlspecialchars($product['url']):'#'?>" <?=$product['url']=="" || $product['url']=="#" || $product['url']=="#enquiryform"?'':'target="_blank"'?>><?=htmlspecialchars($product['title'])?></a></h2>
                                        </div>
                                        <p><?=htmlspecialchars($product['description'])?></p>
                                        <div class="article-cta">
                                        <a href="<?=$product['url']!=""?htmlspecialchars($product['url']):'#'?>" <?=$product['url']=="" || $product['url']=="#" || $product['url']=="#enquiryform"?'':'target="_blank"'?>><?=$this->lang->line('enquiry')?htmlspecialchars($this->lang->line('enquiry')):'Enquiry'?> <i class="fas fa-chevron-right"></i></a>
                                        </div>
                                    </div>
                                </article>
                            <?php } ?>
                        </div>
                        <?php } ?>



                    </div>
                </div>   
            </div> 
            <?=(isset($ad_code) && !empty($ad_code) && in_array('products_services', $ad_area))?'<div class="row d-flex justify-content-center">'.$ad_code.'</div>':''?>
            <?php } ?>

        <?php }elseif($reorder_section == 'portfolio'){ ?>

            <?php if(($card['id'] == 1 || ($this->data['card_plan_modules'] && $this->data['card_plan_modules']['portfolio'])) && isset($portfolio) && $portfolio != ''){ ?>
            <div class="row mb-1">
                <div class="col-xl-5 col-md-6 col-sm-10 mx-auto shadow rounded p-0">
                    <div class="card p-2 mb-0">
                        <div class="card-header d-flex justify-content-center p-0">
                            <h4><?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?></h4>
                        </div>
                        
                        <?php if(isset($card['make_setions_conetnt_carousel']) && $card['make_setions_conetnt_carousel'] != 1){ ?>
                
                        <?php foreach($portfolio as $product){ ?>
                            <div class="col-md-12">
                                <article class="article border rounded mb-3">
                                    <div class="article-header">
                                        <div class="article-image" style="background-image: url('<?=$product['image']!=""?base_url("assets/uploads/product-image/".$product['image']):''?>');">
                                        </div>
                                        <div class="article-title">
                                        <h2><a href="<?=$product['url']!=""?htmlspecialchars($product['url']):'#'?>" target="_blank"><?=htmlspecialchars($product['title'])?></a></h2>
                                        </div>
                                    </div>
                                    <div class="article-details">
                                        <p><?=htmlspecialchars($product['description'])?></p>
                                        <div class="article-cta">
                                        <a href="<?=$product['url']!=""?htmlspecialchars($product['url']):'#'?>" target="_blank" class="btn btn-outline-dark"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php } ?>

                        <?php }else{ ?>

                        <div class="col-md-12 owl-carousel owl-theme products-carousel">
                            <?php foreach($portfolio as $product){ ?>
                                <article class="article border rounded mb-3">
                                    <div class="article-header">
                                        <div class="article-image" style="background-image: url('<?=$product['image']!=""?base_url("assets/uploads/product-image/".$product['image']):''?>');">
                                        </div>
                                        <div class="article-title">
                                        <h2><a href="<?=$product['url']!=""?htmlspecialchars($product['url']):'#'?>" target="_blank"><?=htmlspecialchars($product['title'])?></a></h2>
                                        </div>
                                    </div>
                                    <div class="article-details">
                                        <p><?=htmlspecialchars($product['description'])?></p>
                                        <div class="article-cta">
                                        <a href="<?=$product['url']!=""?htmlspecialchars($product['url']):'#'?>" target="_blank" class="btn btn-outline-dark"><?=$this->lang->line('view')?htmlspecialchars($this->lang->line('view')):'View'?></a>
                                        </div>
                                    </div>
                                </article>
                            <?php } ?>
                        </div>
                        <?php } ?>
                    </div>
                </div>   
            </div> 
            <?=(isset($ad_code) && !empty($ad_code) && in_array('portfolio', $ad_area))?'<div class="row d-flex justify-content-center">'.$ad_code.'</div>':''?>
            <?php } ?>

        <?php }elseif($reorder_section == 'gallery'){ ?>

            <?php if(($card['id'] == 1 || ($this->data['card_plan_modules'] && $this->data['card_plan_modules']['gallery'])) && isset($gallery) && $gallery != ''){ ?>
            <div class="row mb-1">
                <div class="col-xl-5 col-md-6 col-sm-10 mx-auto shadow rounded p-0">
                    <div class="card p-2 mb-0">
                        <div class="card-header d-flex justify-content-center p-0">
                            <h4><?=$this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery'?></h4>
                        </div>
                        <div class="col-md-12">
                            <div class="gallery gallery-md text-center">
                            <?php foreach($gallery as $gal){ if($gal['content_type'] == 'upload'){ ?>

                            <a href="<?=$gal['url']!=""?base_url("assets/uploads/product-image/".$gal['url']):''?>" data-toggle="lightbox"><div class="gallery-item" data-image="<?=$gal['url']!=""?base_url("assets/uploads/product-image/".$gal['url']):base_url("assets/img/video-thumbnail.png")?>"></div></a>

                            <?php }else{ ?>

                            <a href="<?=$gal['url']!=""?$gal['url']:''?>" data-toggle="lightbox"><div class="gallery-item" data-image="<?=$gal['content_type']=='custom' && $gal['url']!=""?$gal['url']:$gal['thumb']?>"></div></a>

                            <?php } } ?>
                            </div>
                        </div>
                    </div>
                </div>   
            </div> 
            <?=(isset($ad_code) && !empty($ad_code) && in_array('gallery', $ad_area))?'<div class="row d-flex justify-content-center">'.$ad_code.'</div>':''?>
            <?php } ?>

        <?php }elseif($reorder_section == 'testimonials'){ ?>

            <?php if(($card['id'] == 1 || ($this->data['card_plan_modules'] && $this->data['card_plan_modules']['testimonials'])) && isset($testimonials) && $testimonials != ''){ ?>
            <div class="row mb-1">
                <div class="col-xl-5 col-md-6 col-sm-10 mx-auto shadow rounded p-0">
                    <div class="card p-2 mb-0">
                        <div class="card-header d-flex justify-content-center p-0">
                            <h4><?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?></h4>
                        </div>
                        <div class="col-md-12">
                            <div class="owl-carousel owl-theme products-carousel">
                                <?php foreach($testimonials as $product){ ?>
                                <div>
                                    <div class="product-item pb-3">
                                        <div class="product-image">
                                        <img alt="image" src="<?=$product['image']!=""?base_url("assets/uploads/product-image/".$product['image']):base_url('assets/uploads/logos/'.half_logo())?>" class="img-fluid">
                                        </div>
                                        <div class="product-details">
                                        <div class="product-name"><?=htmlspecialchars($product['title'])?></div>
                                        <div class="product-review">
                                            <i class="<?=$product['rating']>=1?'fas':'far'?> fa-star"></i>
                                            <i class="<?=$product['rating']>=2?'fas':'far'?> fa-star"></i>
                                            <i class="<?=$product['rating']>=3?'fas':'far'?> fa-star"></i>
                                            <i class="<?=$product['rating']>=4?'fas':'far'?> fa-star"></i>
                                            <i class="<?=$product['rating']>=5?'fas':'far'?> fa-star"></i>
                                        </div>
                                        <div class="text-muted text-small"><?=htmlspecialchars($product['description'])?></div>
                                        </div>  
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>   
            </div> 
            <?=(isset($ad_code) && !empty($ad_code) && in_array('testimonials', $ad_area))?'<div class="row d-flex justify-content-center">'.$ad_code.'</div>':''?>
            <?php } ?>

        <?php }elseif($reorder_section == 'qr_code'){ ?>

            <?php if((($card['id'] == 1  && $card['show_qr_on_card'] == 1) || (isset($card_plan_modules) && isset($card_plan_modules['qr_code']) && $card_plan_modules['qr_code'] == 1 && $card['show_qr_on_card'] == 1))){ ?>
            <div class="row mb-1">
                <div class="col-xl-5 col-md-6 col-sm-10 mx-auto shadow rounded p-0">
                    <div class="card text-center p-2 mb-0">
                        <div class="card-header d-flex justify-content-center p-0">
                            <h4><?=$this->lang->line('my_qr_code')?htmlspecialchars($this->lang->line('my_qr_code')):'My QR Code'?></h4>
                        </div>
                        <div class="col-md-12 code">
                            
                        </div>
                        <div class="col-md-12 my-3">
                            <button  class="btn btn-icon icon-left btn-outline-dark download_my_qr_code"><?=$this->lang->line('download_my_qr_code')?htmlspecialchars($this->lang->line('download_my_qr_code')):'Download My QR Code'?></button>
                        </div>
                    </div>
                </div>   
            </div> 
            <?=(isset($ad_code) && !empty($ad_code) && in_array('qr_code', $ad_area))?'<div class="row d-flex justify-content-center">'.$ad_code.'</div>':''?>
            <?php } ?>

        <?php }elseif($reorder_section == 'enquiry_form'){ ?>

            <?php if((($card['id'] == 1  && !empty($card['enquery_email'])) || (isset($card_plan_modules) && isset($card_plan_modules['enquiry_form']) && $card_plan_modules['enquiry_form'] == 1 && !empty($card['enquery_email'])))){ ?>
            <div class="row d-flex justify-content-center">
                <div class="col-md-5 m-0 mt-1 p-0">
                    <form action="<?=base_url('cards/send_mail')?>" method="POST" id="enquiryform" enctype="multipart/form-data">
                        <input type="hidden" name="user_email" value="<?=htmlspecialchars($card['enquery_email'])?>">
                        <input type="hidden" name="card_name" value="<?=isset($card['title'])?htmlspecialchars($card['title']):''?>">
                        <input type="hidden" name="card_url" value="<?=current_url()?>">
                            
                        <div class="card p-2 m-0">
                            <div class="card-header text-center d-flex justify-content-center p-0">
                                <h4><?=$this->lang->line('enquiry_form')?htmlspecialchars($this->lang->line('enquiry_form')):'Enquiry Form'?></h4>
                            </div>
                            <div class="row p-3">
                                <div class="form-group col-md-12">
                                    <input type="text" name="name" placeholder="<?=$this->lang->line('name')?htmlspecialchars($this->lang->line('name')):'Name'?>" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="email" placeholder="<?=$this->lang->line('email')?htmlspecialchars($this->lang->line('email')):'Email'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <input type="text" name="mobile" placeholder="<?=$this->lang->line('mobile')?htmlspecialchars($this->lang->line('mobile')):'Mobile'?>" class="form-control">
                                </div>
                                <div class="form-group col-md-12">
                                    <textarea type="text" name="msg" placeholder="<?=$this->lang->line('type_your_message')?htmlspecialchars($this->lang->line('type_your_message')):'Type your message'?>" class="form-control" required></textarea>
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-outline-dark savebtn"><?=$this->lang->line('send')?htmlspecialchars($this->lang->line('send')):'Send'?></button>
                                </div>
                                <div class="result"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>         
            <?=(isset($ad_code) && !empty($ad_code) && in_array('enquiry_form', $ad_area))?'<div class="row d-flex justify-content-center">'.$ad_code.'</div>':''?>
            <?php } ?>

        <?php }elseif($reorder_section == 'custom_sections'){ ?>

            <?php if(($card['id'] == 1  && isset($custom_sections) && $custom_sections != '') || (isset($card_plan_modules) && isset($card_plan_modules['custom_sections']) && $card_plan_modules['custom_sections'] == 1 && isset($custom_sections) && $custom_sections != '')){ foreach($custom_sections as $custom_section){ ?>
                <div class="row mb-1">
                    <div class="col-xl-5 col-md-6 col-sm-10 mx-auto shadow rounded p-0">
                        <div class="card p-2 mb-0">
                            <div class="card-header d-flex justify-content-center p-0">
                                <h4><?=$custom_section['title']?></h4>
                            </div>
                            <div class="col-md-12">
                                <?=$custom_section['content']?>
                            </div>
                        </div>
                    </div>   
                </div> 
            <?php if(isset($ad_code) && !empty($ad_code) && in_array('custom_sections', $ad_area)){ 
                    echo '<div class="row d-flex justify-content-center">'.$ad_code.'</div>'; 
                } 
            } } ?>

        <?php }

        } } ?>


	</div>

 
<?php include 'foot.php' ?>

</body>
</html>