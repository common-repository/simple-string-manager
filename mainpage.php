<?php
/*
Plugin Name: Simple String Manager
Plugin URI: http://ssm.anaxe.net/
Description: Manage strings/words from admin panel; Use as string translator at multilanguage sites;
Version: 2.0.2
Author: ssm.anaxe.net
Author URI: http://ssm.anaxe.net/
Update Server: http://ssm.anaxe.net/
Min WP Version: 3.2
Max WP Version: 4.3 +
*/

define(SSMNAME, 'ssm');
define(SSMDIR, 'simple-string-manager');
define(SSMVERSION, '2.0.2');
define(SSMROOT, __DIR__);

require_once SSMROOT."/inc/sys.php";
include_once SSMROOT.'/inc/ssmAdmin.php';
include_once SSMROOT.'/inc/ssmMain.php';

use ssm\inc;

ssmPluginEnvironment();





/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////
function ssmPluginEnvironment(){



	if (get_option("ssm_version") < SSMVERSION)ssmInstall();
	ssmInitVars();

	if(is_admin()){
		/// admin
		add_action('admin_menu', "ssmAdminMenu");

		ssmLocalize();

	}else{
		//// front
		$strings = new ssmAdmin();
		$strings->cacheCurrentStrings();
		add_filter( 'gettext', 'changeWord', 20, 3);

	}
	

	return;
}


function changeWord($translated,$name,$domain){

	if(isset($GLOBALS['ssmstrs'][md5($name)]))return $GLOBALS['ssmstrs'][md5($name)];

	$strings = new ssmAdmin();
	$strings->newWord($name);

	return $translated;

}

function updateForm(){

		$strings = new ssmAdmin();
		$strings->updateAllWords($_POST);
return;


}


/////////////////////////////////
function ssmAdminMenu() {
//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );


	//create top-level menu
	$ident = SSMROOT.'/admin/main.php';
	add_menu_page( 'Simple String Manager', 'String Manager', 'manage_options', $ident);
	//add_submenu_page( $ident, 'Field Values', 'Field Values', 'manage_options', 'dddd', 'ssmGetPage' );


	return false;
}


function ssmGetPage(){
	if(!isset($_GET['page']))return false;

	$var = strip_tags($_GET['page']);
	if(empty($var))return false;
	
	print "<div class='plugincontainer {SSMDIR}'>";
	if (function_exists($var)){
		print call_user_func_array($var);
	}elseif(is_file(SSMROOT.'admin/'.$var.'.php')){

		include_once SSMROOT.'admin/'.$var.'.php';
	}
	print "</div>";
	return false;
}

function ssmInitVars(){
	$current =  get_bloginfo('language');
	$tmp = get_option( SSMNAME."_languages", array());

	if(!isset($tmp[$current])){ 
		$tmp[$current] = $current; 
		update_option( SSMNAME."_languages", $tmp);
		if(!is_file(SSMROOT.'/files/'.$current.'.json')) file_put_contents(SSMROOT.'/files/'.$current.'.json','');
	}

	return;
}


function ssmLocalize(){
	wp_localize_script( "myfunc_ssm", "dinob", [
		'home_url' => home_url(),
	]);
}



////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////// install plugin
function ssmInstall(){

   $installed_ver = get_option( "ssm_version" );
	if( $installed_ver >= SSMVERSION )return false;

   update_option( "ssm_version", SSMVERSION);
	return false;
}



