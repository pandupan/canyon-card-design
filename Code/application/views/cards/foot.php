
<?php
    $vcard_url_share = (isset($card['slug']) && $card['slug'] != '')?base_url(htmlspecialchars($card['slug'])):base_url();
?>

<?php if(isset($card_plan_modules) && isset($card_plan_modules['hide_branding']) && $card_plan_modules['hide_branding'] == 1){ if(isset($card['hide_branding']) && $card['hide_branding'] != 1){ ?>
  <div class="row p-3 justify-content-center">
    <a href="<?=base_url()?>" class="text-decoration-none text-white" target="_blank"><?=htmlspecialchars(footer_text())?></a>
  </div>
<?php } }else{ ?>
  <div class="row p-3 justify-content-center">
    <a href="<?=base_url()?>" class="text-decoration-none text-white" target="_blank"><?=htmlspecialchars(footer_text())?></a>
  </div>
<?php } ?>

<div class="modal fade" id="socialShare" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><?=$this->lang->line('share_my_vcard')?htmlspecialchars($this->lang->line('share_my_vcard')):'Share My vCard'?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

            <?php if((($card['id'] == 1  && $card['show_qr_on_share_popup'] == 1) || (isset($card_plan_modules) && isset($card_plan_modules['qr_code']) && $card_plan_modules['qr_code'] == 1 && $card['show_qr_on_share_popup'] == 1))){ ?>

            <input type="hidden" value="<?=isset($card['qr_code_options']['foreground_color'])?htmlspecialchars($card['qr_code_options']['foreground_color']):'#000000'?>" class="foreground_color">

            <input type="hidden" value="<?=isset($card['qr_code_options']['background_color'])?htmlspecialchars($card['qr_code_options']['background_color']):'#ffffff'?>" class="background_color">

            <input type="hidden" value="<?=isset($card['qr_code_options']['corner_radius'])?htmlspecialchars($card['qr_code_options']['corner_radius']):''?>" class="corner_radius">

            <input type="hidden" value="<?=isset($card['qr_code_options']['text'])?htmlspecialchars($card['qr_code_options']['text']):''?>" class="text">

            <input type="hidden" value="<?=isset($card['qr_code_options']['text_color'])?htmlspecialchars($card['qr_code_options']['text_color']):''?>" class="text_color">

            <input type="hidden" value="<?=isset($card['qr_code_options']['size'])?htmlspecialchars($card['qr_code_options']['size']):''?>" class="size">

            <input type="hidden" id="qr_type" value="<?=isset($card['qr_code_options']['qr_type'])?htmlspecialchars($card['qr_code_options']['qr_type']):'0'?>" class="qr_type">

            <img id="image-buffer" src="<?=isset($card['qr_code_options']['image'])?base_url('assets/uploads/qr-img/'.htmlspecialchars($card['qr_code_options']['image'])):''?>" class="d-none">

            <div class="row d-flex justify-content-center">
              <div class="p-0">
                  <div class="card text-center m-0">
                      <div class="col-md-12 mt-3 code">
                          
                      </div>
                      <div class="col-md-12 my-3">
                          <button class="btn btn-icon icon-left btn-outline-dark download_my_qr_code"><?=$this->lang->line('download_my_qr_code')?htmlspecialchars($this->lang->line('download_my_qr_code')):'Download My QR Code'?></button>
                      </div>
                  </div>
              </div>
		        </div>
            <?php } ?>

            <div class="row justify-content-center contact-details mt-3">
                <a href="https://wa.me/?text=<?=$vcard_url_share?>" target="_blank"><span class="m-1 icon-circle"><i class="fab fa-whatsapp m-0"></i></span></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?=$vcard_url_share?>" target="_blank"><span class="m-1 icon-circle"><i class="fab fa-facebook m-0"></i></span></a>
                <a href="https://twitter.com/intent/tweet?text=<?=$vcard_url_share?>" target="_blank"><span class="m-1 icon-circle"><i class="fab fa-twitter m-0"></i></span></a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?=$vcard_url_share?>" target="_blank"><span class="m-1 icon-circle"><i class="fab fa-linkedin m-0"></i></span></a>
                <a href="mailto:?subject=&body=<?=$vcard_url_share?>" target="_blank"><span class="m-1 icon-circle"><i class="fa fa-at"></i></span></a>
                <a href="#" class="custom-share-button"><span class="m-1 icon-circle"><i class="fa fa-share-alt m-0"></i></span></a>

                <div class="form-group col-md-12 m-0 mt-4">
                    <a href="<?=$vcard_url_share?>" class="copy_href">
                    <div class="input-group mb-2">
                      <input type="text" class="form-control" id="inlineFormInputGroup2" value="<?=$vcard_url_share?>" placeholder="<?=$vcard_url_share?>" readonly disabled>
                      <div class="input-group-append">
                        <div class="input-group-text"><i class="fa fa-copy m-0"></i></div>
                      </div>
                    </div>
                  </a>
                  </div>

            </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('includes/js'); ?>

<script src="<?=base_url('assets/modules/owlcarousel2/dist/owl.carousel.min.js')?>"></script>

<script>
  var all = [{
    "version": "3.0",
    "n": "<?=isset($card['title'])?htmlspecialchars($card['title']):'vCard'?>",
    "fn": ";<?=isset($card['title'])?htmlspecialchars($card['title']):'vCard'?>",
    "org": "<?=isset($card['title'])?htmlspecialchars($card['title']):'vCard'?>",
    "title": "<?=isset($card['sub_title'])?htmlspecialchars($card['sub_title']):'WAPTechy'?>",
    "note": "<?=isset($card['description'])?str_replace(["\r\n", "\r", "\n"], " ", htmlspecialchars($card['description'])):''?>",
    "tel": <?=json_encode($vcard_mobile)?>,
    "email": <?=json_encode($vcard_email)?>,
    "adr": <?=json_encode($vcard_addr)?>,
    "url": <?=json_encode($vcard_links)?>,
    "PHOTO;BASE64:": "<?=isset($meta_image) && !empty($meta_image)?base64_encode(file_get_contents($meta_image)):''?>",
  }];
</script>

<script src="<?=base_url('assets/js/page/vcard.js?v='.time())?>"></script>

<script src="<?=base_url('assets/modules/jquery.qrcode.min.js?v='.time())?>"></script>

<script>
var card_url = '<?=isset($card['slug'])?base_url($card['slug']):base_url()?>';
</script>

<script src="<?=base_url('assets/js/page/qr.js?v='.time())?>"></script>

<script src="<?=base_url('assets/modules/bootstrap-lightbox/lightbox.js')?>"></script>

<script>
$(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});

$("#enquiryform").submit(function(e) {
	e.preventDefault();
  	let save_button = $(this).find('.savebtn'),
    output_status = $(this).find('.result'),
    card = $('#enquiryform');

  	let card_progress = $.cardProgress(card, {
    	spinner: true
  	});
  	save_button.addClass('btn-progress');
  	output_status.html('');
  	var formData = new FormData(this);
    $.ajax({
	    type:'POST',
	    url: $(this).attr('action'),
	    data:formData,
	    cache:false,
	    contentType: false,
	    processData: false,
	    dataType: "json",
	    success:function(result){
	    	card_progress.dismiss(function() {
			    if(result['error'] == false){
              output_status.prepend('<div class="alert alert-success">'+result['message']+'</div>');
			    }else{
			        output_status.prepend('<div class="alert alert-danger">'+result['message']+'</div>');
			    }
			    output_status.find('.alert').delay(4000).fadeOut();
			    save_button.removeClass('btn-progress');      
			    $('html, body').animate({
			        scrollTop: output_status.offset().top
			    }, 1000);
		    });
		},
    error:function(){
	    card_progress.dismiss(function() {
        output_status.prepend('<div class="alert alert-danger">'+something_wrong_try_again+'</div>');
        output_status.find('.alert').delay(4000).fadeOut();
        save_button.removeClass('btn-progress');      
        $('html, body').animate({
            scrollTop: output_status.offset().top
        }, 1000);
		  });
    }
    });
  	return false;
});

$('.custom-share-button').on('click', () => {
  if (navigator.share) {
    navigator.share({
        title: '<?=isset($card['title'])?htmlspecialchars($card['title']):''?>',
        url: '<?=$vcard_url_share?>',
      })
      .then(() => console.log('Successful share'))
      .catch((error) => 
      iziToast.error({
        title: share_functionality_not_supported_on_this_browser,
        message: "",
        position: 'topRight'
      }));
  } else {
      iziToast.error({
        title: share_functionality_not_supported_on_this_browser,
        message: "",
        position: 'topRight'
      })
  }
})
</script>

<?php if(($card['id'] == 1 && !empty($card['custom_css'])) || isset($card_plan_modules) && isset($card_plan_modules['custom_js_css']) && $card_plan_modules['custom_js_css'] == 1){ if(isset($card['custom_js']) && !empty($card['custom_js'])){ 
    echo $card['custom_js'];
} } ?>

<?=(isset($ads_footer_code) && !empty($ads_footer_code))?$ads_footer_code:''?>