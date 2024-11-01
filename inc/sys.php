<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////
/////// plugin additional functions

/////////////////////////////////////////////////////
add_action('wp_ajax_nopriv_aa', 'aa');
add_action('wp_ajax_aa', 'aa');

if (!function_exists('aa')){
function aa() {

	if(isset($_POST['aa']) && function_exists($_POST['aa'])){
		print call_user_func($_POST['aa']);
	}

	exit;
}}


if (!function_exists('print_rr')){
function print_rr($arr=array()){
	print "<pre>";
		print_r($arr);
	print "</pre>";
}}

if (!function_exists('__file_part')) {
function __file_part($cf = ''){//v1
//////// get file
	$cf = __default_configer($cf,'file=&dir=part&data=ar&plugin=');
	if(empty($cf['file']))return false;
	global $wpdb, $p, $act, $fl;
	$incfile = false;

	if(!$incfile = __chek_file("file={$cf['file']}&dir={$cf['dir']}&plugin={$cf['plugin']}")){//print_rr($cf);
	//print __chek_file("file={$cf['file']}&dir={$cf['dir']}&plugin={$cf['plugin']}&return=dir")."{$cf['file']}.php";
		file_put_contents(__chek_file("file={$cf['file']}&dir={$cf['dir']}&plugin={$cf['plugin']}&return=dir")."{$cf['file']}.php", $cf['file']);
		return false;
	}

	ob_start();
	include $incfile;
	$ret = ob_get_contents();
	ob_end_clean();
	return $ret;
}}

if (!function_exists('__chek_file')){
function __chek_file( $cf = '' ){//v1
	$cf = __default_configer($cf,'file=&dir=part&return=fullpatch&plugin=');

	//$abspatch = substr(__FILE__,0,strpos(__FILE__,'plugins'));
	$patch = ABSPATH .'wp-content/plugins/'.$cf['plugin'].'/';

	if(is_dir($patch.$cf['dir'].'/')){
		$patch=$patch.$cf['dir'].'/';
	}else{ return false; }

	if( $cf['return']=='dir' )return $patch;

	if(empty($cf['file']))return false;
	if(is_file("{$patch}{$cf['file']}.php"))return "{$patch}{$cf['file']}.php";

	return false;
}}




if (!function_exists('chek_val')) {
function chek_val($array = '', $key='', $chektype='empty',$toequal=''){//v.1
//	print $array;
	
	if(empty($key)&&!is_numeric($key)){ return false; }
	if(!is_array($array))$tmp = decode($array);
	if(isset($tmp) && is_array($tmp))$array = $tmp;
//if($key=='name'){ print_rr($array); }
	$val=false;
	if(is_array($array) && isset($array[$key])){ //// && $array[$key]!=''
		$val = $array[$key]; 
	}elseif(is_array($array) && !isset($array[$key])){
		return false;
	}else{ $val = $array; }
	$val2 = decode($val);
//	print_rr($array);
	if(($chektype=='numeric'||$chektype=='num') && !is_numeric($val)){ return false; }
	if($chektype=='str' && !is_string($val)){ return false; }
	
	if(($chektype=='arr'||$chektype=='ar'||$chektype=='array') && is_array($val2)){ $val = $val2; }
	if(($chektype=='arr'||$chektype=='ar'||$chektype=='array') && !is_array($val)){ return false; }
	if(($chektype=='email'||$chektype=='mail') && !is_array($val)){ return _chek_email($val); }
	
	
	if($chektype=='empty' && empty($val)){ return false; }
//	if(!empty($toequal) && $val!=$toequal){ return false; }
	if(!empty($val)||$val!='')return $val;
	return true;
}}















if (!function_exists('__default_configer')) {
function __default_configer($inparr='',$defaults=false){
	if(!$defaults)return false;
	if(!is_array($defaults) && strpos($defaults,'=')!==false)parse_str($defaults, $defaults);

	if(!is_array($inparr) && strpos($inparr,'=')!==false)parse_str($inparr, $inparr);
	if(!is_array($inparr))return $defaults;
	
	foreach($defaults as $k=>$v){ 
		if($v!=0 && $v=='false'){ $defaults[$k]=false; }
		if($v!=0 && $v=='array'){
			if(isset($inparr[$k]) && !is_array($inparr[$k])){ 
				$defaults[$k] = array(); continue;
			}elseif(!isset($inparr[$k])){  
				$defaults[$k] = array(); continue;
			}	
		}


		if(!isset($inparr[$k]) || (empty($inparr[$k]) && !is_numeric($inparr[$k]) ))continue;
		if((is_numeric($defaults[$k]) || $defaults[$k]=='num') && !is_numeric($inparr[$k])){ 
			$defaults[$k] = false; continue;
		}
		
		$defaults[$k]=$inparr[$k];
	}
	return $defaults;
}}


if (!function_exists('from_to_extractor')) {
function from_to_extractor($src='', $from='------', $to='------', $option=array()){
	
	if(empty($src)) return false;	
	if(!empty($from) && strpos($src, $from )!==false){
		$src = substr($src, strpos($src, $from )+strlen($from));
		//$src = str_replace($from,'', $src);
	}
	
	if(!empty($to) && strpos($src, $to)!==false){
		$src = substr($src,0, strpos($src, $to ));
	}
		return trim($src);
}}





