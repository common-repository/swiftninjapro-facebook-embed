<?php
/**
* @package SwiftNinjaProFacebookEmbed
*/

if(!defined('ABSPATH')){
  echo '<meta http-equiv="refresh" content="0; url=/404">';
  die('404 Page Not Found');
}

if(!class_exists('SwiftNinjaProFacebookEmbedMain')){

  class SwiftNinjaProFacebookEmbedMain{
    
    public $pluginSettingsName;
    public $pluginShortcode;
    
    function start($pluginSettingsName, $pluginShortcode){
      $this->pluginSettingsName = $pluginSettingsName;
      $this->pluginShortcode = $pluginShortcode;
      if($pluginShortcode){add_shortcode($pluginShortcode, array($this, 'add_plugin_shortcode'));}
    }
    
    
    function add_plugin_shortcode($atts = ''){
      $value = shortcode_atts(array('url' => false,), $atts);
      $url = htmlentities($value['url']);
    
      $defaultUrl = $this->settings_getOption('DefaultUrl');
      if($defaultUrl == ''){$defaultUrl = false;}
      
      if(!$url && $defaultUrl){
        return $this->getFacebookIframe($defaultUrl);
      }else if(!$url){
        return false;
      }else{
        return $this->getFacebookIframe($url);
      }
    
    }
    
    
    function getFacebookIframe($url){
      return '<div class="facebook-container"><iframe class="facebook" src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F'.$url.'%2F&tabs=timeline&width=10000&height=500&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=false&appId" width="500" height="800" style="border: none; overflow: hidden;" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe></div>';
    }
    
    
    function trueText($text){
      if($text === 'true' || $text === 'TRUE' || $text === 'True' || $text === true || $text === 1 || $text === 'on'){
        return true;
      }else{return false;}
    }
    
    function settings_getOption($name, $trueText = false){
      $sName = $this->settings_setOptionName($name);
      $option = htmlentities(get_option($sName));
      if($trueText){$option = $this->trueText($option);}
      return $option;
    }

    function settings_setOptionName($name){
      return htmlentities('SwiftNinjaPro'.$this->pluginSettingsName.'_'.$name);
    }
    
    function htmlentitiesURL($url, $addQuots){
      $link = htmlspecialchars_decode($url);
      $link = str_replace('&quot;', '[ninja_quot]', $link);
      $link = str_replace('/', '[ninja_slash]', $link);
      $link = htmlentities($link);
      $link = str_replace('[ninja_slash]', '/', $link);
      if($addQuots){
        $link = str_replace('[ninja_quot]', '"', $link);
      }else{
        $link = str_replace('[ninja_quot]', '', $link);
      }
      
      return $link;
    }
    
    function get_string_between($string, $start, $end, $pos = 1){
      $cPos = 0;
      $ini = 0;
      $result = '';
      for($i = 0; $i < $pos; $i++){
        $ini = strpos($string, $start, $cPos);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        $result = substr($string, $ini, $len);
        $cPos = $ini + $len;
      }
      return $result;
    }
    
  }

  $swiftNinjaProFacebookEmbedMain = new SwiftNinjaProFacebookEmbedMain();

}
