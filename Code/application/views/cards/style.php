<!DOCTYPE html>
<html lang="en">

<head> 
  
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

<title><?=htmlspecialchars($page_title)?></title>
<meta name="description" content="<?=(isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):htmlspecialchars($page_title)?>" />
<meta name="keywords" content="<?=(isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):htmlspecialchars($page_title)?>" />

<meta property="og:type" content="website" />
<meta property="og:title" content="<?=htmlspecialchars($page_title)?>" />
<meta property="og:description" content="<?=(isset($meta_description) && !empty($meta_description))?htmlspecialchars($meta_description):htmlspecialchars($page_title)?>" />
<?php if(isset($meta_image) && !empty($meta_image)){ ?>
<meta property="og:image" itemprop="image" content="<?=htmlspecialchars($meta_image)?>" />
<?php }else{ ?>
<meta property="og:image" itemprop="image" content="<?=base_url('assets/uploads/logos/'.full_logo())?>" />
<?php } ?>

<link rel="shortcut icon" href="<?=isset($meta_image)?htmlspecialchars($meta_image):base_url('assets/uploads/logos/'.favicon())?>">

<?php if($card['id'] == 1 || (isset($card_plan_modules) && isset($card_plan_modules['search_engine_indexing']) && $card_plan_modules['search_engine_indexing'] == 1)){ if(isset($card['search_engine_indexing']) && $card['search_engine_indexing'] != 1){ 
    echo '<meta name="robots" content="noindex">';
 } }else{
    echo '<meta name="robots" content="noindex">';
 } ?>

<link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Dongle&family=Lato&family=Lobster&family=Lora&family=Montserrat&family=Open+Sans&family=Oswald&family=PT+Sans&family=Pacifico&family=Poppins&family=Raleway&family=Roboto&display=swap" rel="stylesheet">

<style>
    
    <?php if(isset($card['card_theme_bg_type']) && $card['card_theme_bg_type'] != '' && $card['card_theme_bg_type'] == 'Image'){ 
        if($card['card_theme_bg'] != '' && file_exists('assets/uploads/card-bg/'.$card['card_theme_bg'])){ 
            $card_theme_bg_image = "url('".base_url('assets/uploads/card-bg/'.$card['card_theme_bg'])."')"
    ?>
        :root{--theme-gb: <?=isset($card_theme_bg_image)?$card_theme_bg_image:theme_color()?>;}
    <?php }else{?>
        :root{--theme-gb: <?=(isset($card['card_theme_bg']) && $card['card_theme_bg'] != '')?$card['card_theme_bg']:theme_color()?>;}
    <?php } }else{ ?>
        :root{--theme-gb: <?=(isset($card['card_theme_bg']) && $card['card_theme_bg'] != '')?$card['card_theme_bg']:theme_color()?>;}
    <?php } ?>

    <?php if(isset($card['card_bg_type']) && $card['card_bg_type'] != '' && $card['card_bg_type'] == 'Image'){ 
        if($card['card_bg'] != '' && file_exists('assets/uploads/card-bg/'.$card['card_bg'])){ 
            $card_bg_image = "url('".base_url('assets/uploads/card-bg/'.$card['card_bg'])."')"
    ?>
        :root{--card-gb: <?=isset($card_bg_image)?$card_bg_image:'#ffffff'?>;}
    <?php }else{?>
        :root{--card-gb: <?=(isset($card['card_bg']) && $card['card_bg'] != '')?$card['card_bg']:'#ffffff'?>;}
    <?php } }else{ ?>
        :root{--card-gb: <?=(isset($card['card_bg']) && $card['card_bg'] != '')?$card['card_bg']:'#ffffff'?>;}
    <?php } ?>

    <?php $card_font = "'Nunito', 'Segoe UI', arial"; 
    if(isset($card['card_font']) && $card['card_font'] != ''){
        if($card['card_font'] == 'Anton'){
            $card_font = "'Anton', sans-serif";
        }elseif($card['card_font'] == 'Bebas Neue'){
            $card_font = "'Bebas Neue', cursive";
        }elseif($card['card_font'] == 'Dongle'){
            $card_font = "'Dongle', sans-serif";
        }elseif($card['card_font'] == 'Lato'){
            $card_font = "'Lato', sans-serif";
        }elseif($card['card_font'] == 'Lobster'){
            $card_font = "'Lobster', cursive";
        }elseif($card['card_font'] == 'Lora'){
            $card_font = "'Lora', serif";
        }elseif($card['card_font'] == 'Montserrat'){
            $card_font = "'Montserrat', sans-serif";
        }elseif($card['card_font'] == 'Open Sans'){
            $card_font = "'Open Sans', sans-serif";
        }elseif($card['card_font'] == 'Oswald'){
            $card_font = "'Oswald', sans-serif";
        }elseif($card['card_font'] == 'Pacifico'){
            $card_font = "'Pacifico', cursive";
        }elseif($card['card_font'] == 'Poppins'){
            $card_font = "'Poppins', sans-serif";
        }elseif($card['card_font'] == 'PT Sans'){
            $card_font = "'PT Sans', sans-serif";
        }elseif($card['card_font'] == 'Raleway'){
            $card_font = "'Raleway', sans-serif";
        }elseif($card['card_font'] == 'Roboto'){
            $card_font = "'Roboto', sans-serif";
        }else{
            $card_font = "'Nunito', 'Segoe UI', arial";
        }
    } ?>
    
    :root{--card-font: <?=isset($card_font)?$card_font:"'Nunito', 'Segoe UI', arial"?>;}

    :root{--card-font-color: <?=(isset($card['card_font_color']) && $card['card_font_color'] != '')?$card['card_font_color']:'#000000'?>;}

</style>

<!-- General CSS Files -->  

<link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap/css/bootstrap.min.css')?>">

<link rel="stylesheet" href="<?=base_url('assets/modules/fontawesome/css/all.min.css')?>">

<!-- CSS Libraries -->
<link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap-daterangepicker/daterangepicker.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/modules/select2/dist/css/select2.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap-table/bootstrap-table.min.css');?>">
<link rel="stylesheet" href="<?=base_url('assets/modules/izitoast/css/iziToast.min.css');?>">
<link rel="stylesheet" href="<?=base_url('assets/modules/codemirror/lib/codemirror.css');?>">
<link rel="stylesheet" href="<?=base_url('assets/modules/codemirror/theme/duotone-dark.css');?>">

<link rel="stylesheet" href="<?=base_url('assets/modules/owlcarousel2/dist/assets/owl.carousel.min.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/modules/owlcarousel2/dist/assets/owl.theme.default.min.css')?>">

<link rel="stylesheet" href="<?=base_url('assets/modules/bootstrap-lightbox/lightbox.css')?>">

<link rel="stylesheet" href="<?=base_url('assets/css/style.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/css/components.css')?>">
<link rel="stylesheet" href="<?=base_url('assets/css/custom.css')?>">

<link rel="stylesheet" href="<?=base_url('assets/css/cards/custom.css')?>">

<?php if($google_analytics){ ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?=htmlspecialchars($google_analytics)?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?=htmlspecialchars($google_analytics)?>');
  </script>
<?php } ?>

<?php if($card['id'] == 1 || (isset($card_plan_modules) && isset($card_plan_modules['custom_js_css']) && $card_plan_modules['custom_js_css'] == 1)){ if(isset($card['custom_css']) && !empty($card['custom_css'])){ 
    echo $card['custom_css'];
 } } ?>

<?=(isset($ads_header_code) && !empty($ads_header_code))?$ads_header_code:''?>




        