"use strict";

$(".custom_fields").on('change', function() {
  
  var $type = $(this).val();
  var $field_icon = 'fas fa-link';
  var $placeholder_text = text;
  var $placeholder_url = url;

	if($type != ''){
		
    if($type == 'mobile'){
      $field_icon = 'fas fa-mobile-alt';
      $placeholder_text = '+001234567890';
      $placeholder_url = '+001234567890';
    }else if($type == 'email'){
      $field_icon = 'far fa-envelope';
      $placeholder_text = 'youremail@example.com';
      $placeholder_url = 'youremail@example.com';
    }else if($type == 'address'){
      $field_icon = 'fas fa-map-marker-alt';
      $placeholder_text = 'Silicon Valley, California, USA';
      $placeholder_url = 'https://goo.gl/maps/';
    }else if($type == 'whatsapp'){
      $field_icon = 'fab fa-whatsapp';
      $placeholder_text = 'WhatsApp';
      $placeholder_url = '001234567890';
    }else if($type == 'linkedin'){
      $field_icon = 'fab fa-linkedin-in';
      $placeholder_text = 'LinkedIn';
      $placeholder_url = 'https://www.linkedin.com/';
    }else if($type == 'website'){
      $field_icon = 'fas fa-link';
      $placeholder_text = website;
      $placeholder_url = base_url;
    }else if($type == 'facebook'){
      $field_icon = 'fab fa-facebook-f';
      $placeholder_text = 'Facebook';
      $placeholder_url = 'https://www.facebook.com/';
    }else if($type == 'twitter'){
      $field_icon = 'fab fa-twitter';
      $placeholder_text = 'Twitter';
      $placeholder_url = 'https://twitter.com/';
    }else if($type == 'instagram'){
      $field_icon = 'fab fa-instagram';
      $placeholder_text = 'Instagram';
      $placeholder_url = 'https://www.instagram.com/';
    }else if($type == 'telegram'){
      $field_icon = 'fab fa-telegram-plane';
      $placeholder_text = 'Telegram';
      $placeholder_url = 'https://t.me/yoururl';
    }else if($type == 'skype'){
      $field_icon = 'fab fa-skype';
      $placeholder_text = 'Skype';
      $placeholder_url = username;
    }else if($type == 'youtube'){
      $field_icon = 'fab fa-youtube';
      $placeholder_text = 'YouTube';
      $placeholder_url = 'https://www.youtube.com/';
    }else if($type == 'tiktok'){
      $field_icon = 'fas fa-microphone-alt';
      $placeholder_text = 'TikTok';
      $placeholder_url = 'https://www.tiktok.com/';
    }else if($type == 'snapchat'){
      $field_icon = 'fab fa-snapchat-ghost';
      $placeholder_text = 'Snapchat';
      $placeholder_url = 'https://www.snapchat.com/';
    }else if($type == 'paypal'){
      $field_icon = 'fab fa-paypal';
      $placeholder_text = 'Paypal';
      $placeholder_url = 'https://www.paypal.com/';
    }else if($type == 'github'){
      $field_icon = 'fab fa-github';
      $placeholder_text = 'Github';
      $placeholder_url = 'https://github.com/';
    }else if($type == 'pinterest'){
      $field_icon = 'fab fa-pinterest-p';
      $placeholder_text = 'Pinterest';
      $placeholder_url = 'https://www.pinterest.com/';
    }else if($type == 'wechat'){
      $field_icon = 'fab fa-rocketchat';
      $placeholder_text = 'WeChat';
      $placeholder_url = username;
    }else if($type == 'signal'){
      $field_icon = 'fas fa-signal';
      $placeholder_text = 'Signal';
      $placeholder_url = '+001234567890';
    }else if($type == 'discord'){
      $field_icon = 'fab fa-discord';
      $placeholder_text = 'Discord';
      $placeholder_url = 'https://discord.com/';
    }else if($type == 'reddit'){
      $field_icon = 'fab fa-reddit-alien';
      $placeholder_text = 'Reddit';
      $placeholder_url = 'https://www.reddit.com/';
    }else if($type == 'spotify'){
      $field_icon = 'fab fa-spotify';
      $placeholder_text = 'Spotify';
      $placeholder_url = 'https://www.spotify.com/';
    }else if($type == 'vimeo'){
      $field_icon = 'fab fa-vimeo-v';
      $placeholder_text = 'Vimeo';
      $placeholder_url = 'https://vimeo.com/';
    }else if($type == 'soundcloud'){
      $field_icon = 'fab fa-soundcloud';
      $placeholder_text = 'Soundcloud';
      $placeholder_url = 'https://soundcloud.com/';
    }else if($type == 'dribbble'){
      $field_icon = 'fab fa-dribbble';
      $placeholder_text = 'Dribbble';
      $placeholder_url = 'https://dribbble.com/';
    }else if($type == 'behance'){
      $field_icon = 'fab fa-behance';
      $placeholder_text = 'Behance';
      $placeholder_url = 'https://www.behance.net/';
    }else if($type == 'flickr'){
      $field_icon = 'fab fa-flickr';
      $placeholder_text = 'Flickr';
      $placeholder_url = 'https://www.flickr.com/';
    }else if($type == 'twitch'){
      $field_icon = 'fab fa-twitch';
      $placeholder_text = 'Twitch';
      $placeholder_url = 'https://www.twitch.tv/';
    }else{
      $field_icon = 'fas fa-fire';
      $placeholder_text = text;
      $placeholder_url = url;
    }

    $(".input_fields_wrap").html('<input type="hidden" name="type" value="'+$type+'"><div class="col-md-2">'+
      '<button role="iconpicker" data-icon="'+$field_icon+'" data-cols="5" data-iconset="fontawesome5" data-label-header="{0} of {1} pages" data-label-footer="{0} - {1} of {2} icons" data-placement="top" data-rows="5" data-search="true" data-search-text="" data-selected-class="btn-success" data-unselected-class="" class="icon m-1 btn btn-block btn-default border iconpicker dropdown-toggle" name="icon"><i class="'+$field_icon+'"></i><input type="hidden" name="icon" value="'+$field_icon+'"><span class="caret"></span></button>'+
      '</div>'+
      '<div class="col-md-5">'+
        '<input type="text" name="title" id="title" placeholder="'+$placeholder_text+'" class="form-control">'+
      '</div>'+
      '<div class="col-md-5">'+
        '<input type="text" name="url" id="url" placeholder="'+$placeholder_url+'" class="form-control">'+
      '</div>');

    $('.icon').iconpicker({ 
      cols: 5,
      icon: $field_icon,
      iconset: 'fontawesome5',
      labelHeader: '{0} of {1} pages',
      labelFooter: '{0} - {1} of {2} icons',
      placement: 'top',
      rows: 5,
      search: true,
      searchText: '',
      selectedClass: 'btn-success',
      unselectedClass: ''
    });
	} 
});
