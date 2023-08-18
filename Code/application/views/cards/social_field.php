<?php
if($type == 'mobile'){
    $vcard_mobile[]['value'] = htmlspecialchars($url);
    $edite_url = 'tel:'.htmlspecialchars($url);
    $target_blank = false;
}elseif($type == 'email'){
    $vcard_email[]['value'] = htmlspecialchars($url);
    $edite_url = 'mailto:'.htmlspecialchars($url);
    $target_blank = false;
}elseif($type == 'whatsapp'){
    $vcard_links[]['value'] = 'https://wa.me/'.htmlspecialchars($url);
    $edite_url = 'https://wa.me/'.htmlspecialchars($url);
    $target_blank = true;
}elseif($type == 'skype'){
    $vcard_links[]['value'] = 'skype:'.htmlspecialchars($url);
    $edite_url = 'skype:'.htmlspecialchars($url);
    $target_blank = false;
}elseif($type == 'wechat'){
    $vcard_links[]['value'] = 'weixin://dl/chat?'.htmlspecialchars($url);
    $edite_url = 'weixin://dl/chat?'.htmlspecialchars($url);
    $target_blank = false;
}elseif($type == 'signal'){
    $vcard_links[]['value'] = 'https://signal.me/#p/'.htmlspecialchars($url);
    $edite_url = 'https://signal.me/#p/'.htmlspecialchars($url);
    $target_blank = true;
}elseif($type == 'address'){
    $vcard_addr[]['value'] = ';'.str_replace(",",";",htmlspecialchars($text));
    $edite_url = htmlspecialchars($url);
    $target_blank = true;
}else{
    $vcard_links[]['value'] = htmlspecialchars($url);
    $edite_url = htmlspecialchars($url);
    $target_blank = true;
}

?>