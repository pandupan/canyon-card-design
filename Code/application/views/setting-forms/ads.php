<form action="<?=base_url('settings/save-ads-setting')?>" method="POST" id="language-form">

    <div class="card-header">
      <h4><?=$this->lang->line('ads')?htmlspecialchars($this->lang->line('ads')):'Ads'?></h4>
    </div>

    <div class="card-body row">
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('header_code')?htmlspecialchars($this->lang->line('header_code')):'Header Code'?></label>
        <textarea type="text" name="header_code" class="form-control" placeholder='<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3219644148183980" crossorigin="anonymous"></script>'><?=(isset($header_code) && !empty($header_code))?htmlspecialchars($header_code):''?></textarea>
      </div>
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('footer_code')?htmlspecialchars($this->lang->line('footer_code')):'Footer Code'?></label>
        <textarea type="text" name="footer_code" class="form-control" placeholder='<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3219644148183980" crossorigin="anonymous"></script>'><?=(isset($footer_code) && !empty($footer_code))?htmlspecialchars($footer_code):''?></textarea>
      </div>

      <div class="form-group col-md-12">
        <label><?=$this->lang->line('ad_code')?htmlspecialchars($this->lang->line('ad_code')):'Ad Code'?></label>
        <textarea type="text" name="ad_code" class="form-control" placeholder='<ins class="adsbygoogle"
              style="display:inline-block;width:728px;height:90px"
              data-ad-client="ca-pub-3219644148183980"
              data-ad-slot="3350964798"></ins>
          <script>
              (adsbygoogle = window.adsbygoogle || []).push({});
          </script>'><?=(isset($ad_code) && !empty($ad_code))?htmlspecialchars($ad_code):''?></textarea>
      </div> 
      <div class="form-group col-md-12">
        <label><?=$this->lang->line('show_ads_at')?htmlspecialchars($this->lang->line('show_ads_at')):'Show Ads At'?></label>
        <select name="ad_area[]" class="form-control select2" multiple="">
          <option value="before" <?=in_array('before', $ad_area)?'selected':''?>><?=$this->lang->line('before')?htmlspecialchars($this->lang->line('before')):'Before'?> <?=$this->lang->line('contact_details')?htmlspecialchars($this->lang->line('contact_details')):'Contact Details'?></option>
          <option value="after" <?=in_array('after', $ad_area)?'selected':''?>><?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?> <?=$this->lang->line('contact_details')?htmlspecialchars($this->lang->line('contact_details')):'Contact Details'?></option>
          <option value="products_services" <?=in_array('products_services', $ad_area)?'selected':''?> ><?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?> <?=$this->lang->line('products_services')?htmlspecialchars($this->lang->line('products_services')):'Products/Services'?></option>
          <option value="portfolio" <?=in_array('portfolio', $ad_area)?'selected':''?>><?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?> <?=$this->lang->line('portfolio')?htmlspecialchars($this->lang->line('portfolio')):'Portfolio'?></option>
          <option value="gallery" <?=in_array('gallery', $ad_area)?'selected':''?>><?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?> <?=$this->lang->line('gallery')?htmlspecialchars($this->lang->line('gallery')):'Gallery'?></option>
          <option value="testimonials" <?=in_array('testimonials', $ad_area)?'selected':''?>><?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?> <?=$this->lang->line('testimonials')?htmlspecialchars($this->lang->line('testimonials')):'Testimonials'?></option>
          <option value="qr_code" <?=in_array('qr_code', $ad_area)?'selected':''?>><?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?> <?=$this->lang->line('qr_code')?htmlspecialchars($this->lang->line('qr_code')):'QR Code'?></option>
          <option value="enquiry_form" <?=in_array('enquiry_form', $ad_area)?'selected':''?>><?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?> <?=$this->lang->line('enquiry_form')?htmlspecialchars($this->lang->line('enquiry_form')):'Enquiry Form'?></option>
          <option value="custom_sections" <?=in_array('custom_sections', $ad_area)?'selected':''?>><?=$this->lang->line('after')?htmlspecialchars($this->lang->line('after')):'After'?> <?=$this->lang->line('custom_sections')?htmlspecialchars($this->lang->line('custom_sections')):'Custom Sections'?></option>
        </select>
      </div>

    </div>

    <div class="card-footer bg-whitesmoke text-md-right">
      <button class="btn btn-primary savebtn"><?=$this->lang->line('save_changes')?$this->lang->line('save_changes'):'Save Changes'?></button>
    </div>
    <div class="result"></div>
</form>