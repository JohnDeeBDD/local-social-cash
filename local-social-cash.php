<?php
/*
 Plugin Name: Local Social Cash
 Plugin URI: https://generalchicken.net/
 Description: 
 Version: 1.0
 Author: John Dee
 Author URI: https://generalchicken.net
 */
namespace LocalSocialCash;

//error_reporting(-1);
//ini_set('display_errors', 'On');

require_once (plugin_dir_path(__FILE__). 'src/local-social-cash/autoloader.php');
add_shortcode('biz-report', array(new BizReport, 'returnReportHTML'));
if (isset($_POST['localSocialForm'])){
    add_action('init', array(new UserAdder, 'addUser'));
}
add_action(
    'admin_menu',
    function(){
        add_menu_page(
            'EMAILS',
            'EMAILS',
            'manage_options',
            'EMAILS',
            array(new AdminPage, 'echoAdminPage')
            );
    }
);

$TaskCPT = new TaskCPTs();
$ProofCPT = new ProofCPTs();

add_shortcode('fileuploader', array(new FileUploader, 'returnReportHTML'));
if (isset($_POST['file-from-form'])){
     add_action('init', array(new FileUploader, 'listenToFormSubmission'));
}

add_shortcode('gig-view', array(new GigViewShortcode, 'returnShortcode'));

$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


if (strpos($url,'task') !== false) {
    add_action('init', array(new FileUploader, 'addTextToCommentField'));
}

add_shortcode('client-report', array(new ClientReport, 'returnShortcode'));