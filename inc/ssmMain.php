<?php

namespace ssm\inc;


class ssmMain{


	static function getAllFiles(){

	}



	/** get strings by language */
	static function getStrings($lan=''){
		if(empty($lan))return array();

		return ssmMain::getFile($lan);

	}


	static function getFile($lan=''){
		if(empty($lan))return array();

		$ret = file_get_contents(SSMROOT."/files/{$lan}.json");
		return json_decode( $ret, true );

	}


	static function saveFile($lan='', $data=array()){

		if(empty($lan))return array();

		$ret = file_put_contents(SSMROOT."/files/{$lan}.json", json_encode($data));
		return true;

	}

	static function saveMainFile($data=array()){

		if(empty($lan))return array();

		$ret = file_put_contents(SSMROOT."/files/mainfile.json", json_encode($data));
		return true;

	}





}