<?php
/**
* @package SwiftNinjaProFacebookEmbed
*/
/*
Plugin Name: Simple Page Embed
Plugin URI: https://www.swiftninjapro.com/plugins/wordpress/?plugin=swiftninjapro-facebook-embed
Description: Embed a responsive Facebook page to your website.
Version: 1.1.11
Author: SwiftNinjaPro
Author URI: https://www.swiftninjapro.com
License: GPLv2 or later
Text Domain: swiftninjapro-facebook-embed
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if(!defined('ABSPATH')){
  echo '<meta http-equiv="refresh" content="0; url=/404">';
  die('404 Page Not Found');
}

if(!class_exists('SwiftNinjaProFacebookEmbed')){

  class SwiftNinjaProFacebookEmbed{
    
    public $pluginSettingsName = 'Facebook Embed';
    public $pluginSettingsPermalink = 'swiftninjapro-facebook-embed';
    public $settings_PluginName = 'FacebookEmbed';
    public $pluginShortcode = 'facebook';
    
    public $pluginName;
    private $settings_icon;
    
    function __construct(){
      $this->pluginName = plugin_basename(__FILE__);
    }
    
    function startPlugin(){
      $pluginEnabled = $this->getSetting('Enabled');
      if(isset($pluginEnabled) && ($pluginEnabled || $pluginEnabled === false || $pluginEnabled === '')){
        $pluginEnabled = $this->trueText($pluginEnabled);
      }else{$pluginEnabled = true;}
      if($pluginEnabled && !is_admin()){
        require_once(plugin_dir_path(__FILE__).'main.php');
        $swiftNinjaProFacebookEmbedMain->start($this->settings_PluginName, $this->pluginShortcode);
      }else if($this->pluginShortcode && !$pluginEnabled && !is_admin()){
        add_shortcode($this->pluginShortcode, array($this, 'add_plugin_shortcode'));
      }
    }
    
    function add_plugin_shortcode($atts = ''){return false;}
    
    function register(){
      $this->settings_icon = plugins_url('assets/settings_icon.png', __FILE__);
      add_action('wp_enqueue_scripts', array($this, 'enqueue'));
      add_action('admin_menu', array($this, 'add_admin_pages'));
      add_filter("plugin_action_links_$this->pluginName", array($this, 'settings_link'));
    }
    
    function trueText($text){
      if($text === 'true' || $text === 'TRUE' || $text === 'True' || $text === true || $text === 1 || $text === 'on'){
        return true;
      }else{return false;}
    }
    
    function getSetting($name){
      $sName = 'SwiftNinjaPro'.$this->settings_PluginName.'_'.$name;
      return get_option($sName);
    }
    
    function settings_link($links){
      $settings_link = '<a href="admin.php?page='.$this->pluginSettingsPermalink.'">Settings</a>';
      array_push($links, $settings_link);
      return $links;
    }
    
    function add_admin_pages(){
      if(empty($GLOBALS['admin_page_hooks']['swiftninjapro-settings'])){
        add_menu_page('SwiftNinjaPro Settings', 'SwiftNinjaPro Settings', 'manage_options', 'swiftninjapro-settings', array($this, 'settings_index'), $this->settings_icon, 100);
      }
      $adminOnly = $this->trueText($this->getSetting('AdminOnly'));
      if($adminOnly && current_user_can('administrator')){
        add_submenu_page('swiftninjapro-settings', $this->pluginSettingsName, $this->pluginSettingsName, 'administrator', $this->pluginSettingsPermalink, array($this, 'admin_index'));
      }else if(!$adminOnly){
        add_submenu_page('swiftninjapro-settings', $this->pluginSettingsName, $this->pluginSettingsName, 'manage_options', $this->pluginSettingsPermalink, array($this, 'admin_index'));
      }
    }
    
    function admin_index(){
      require_once(plugin_dir_path(__FILE__).'templates/admin.php');
    }
    
    function settings_index(){
      require_once(plugin_dir_path(__FILE__).'templates/settings-info.php');
    }
    
    function activate(){
      flush_rewrite_rules();
    }
    
    function deactivate(){
      flush_rewrite_rules();
    }
    
    function enqueue(){
      wp_enqueue_style('SwiftNinjaProFacebookEmbedStyle', plugins_url('/assets/style.css', __FILE__));
      wp_enqueue_script('SwiftNinjaProFacebookEmbedScript', plugins_url('/assets/script.js', __FILE__));
    }
    
  }

  $swiftNinjaProFacebookEmbed = new SwiftNinjaProFacebookEmbed();
  $swiftNinjaProFacebookEmbed->register();
  $swiftNinjaProFacebookEmbed->startPlugin();

  register_activation_hook(__FILE__, array($swiftNinjaProFacebookEmbed, 'activate'));
  register_deactivation_hook(__FILE__, array($swiftNinjaProFacebookEmbed, 'deactivate'));

}