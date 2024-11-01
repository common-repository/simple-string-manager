<?php

use ssm\inc;

class ssmAdmin{


	private $lan;
	private $lans;

	function __construct(){
		$this->lan	=  get_bloginfo('language');
		$this->lans	=  get_option( "ssm_languages");
		$this->cacheString($this->lan);

	}

	public function getAllWords($word=''){
		$ret = array();
		foreach($this->lans as $v){
			$ret[$v] = $this->getWord($word, $v);
		}

		return $ret;

	}


	public function getWord($word='', $lan=''){
		if(empty($lan))$lan = $this->lan;

		if(!isset($GLOBALS['langs'][$lan]))return false;

		if(isset($GLOBALS['langs'][$lan][$word]))return $GLOBALS['langs'][$lan][$word];
		if(isset($GLOBALS['langs'][$lan][md5($word)]))return $GLOBALS['langs'][$lan][md5($word)];
		return false;

	}

	public function cacheStrings(){

		foreach($this->lans as $v) $this->cacheString($v);

		return;
	}

	public function cacheString($lan=''){
		if(empty($lan))$lan = $this->lan;

		if(isset($GLOBALS['langs'][$lan]))return;
		$GLOBALS['langs'][$lan] = inc\ssmMain::getStrings($lan);

		return;
	}

	public function cacheCurrentStrings($lan=''){
		if(empty($lan))$lan = $this->lan;

		if(isset($GLOBALS['currentlangs']))return;
		$GLOBALS['ssmstrs']= inc\ssmMain::getStrings($lan);

		return;
	}


	public function getStrings($lan=''){
		if(empty($lan))$lan = $this->lan;
		if(isset($GLOBALS['langs'][$lan]))return $GLOBALS['langs'][$lan];
	}




	public function updateAllWords($words=''){

		$wordid = $words['wordid'];
		foreach ($this->lans as $v) {
			if(!isset($words[$v]))continue;
			$word = $words[$v];
			$this->updateWord($word,$v,$wordid);
		}

		return;
	}

	public function updateWord($word='',$lan='', $wordid=''){

		if(empty($lan))$lan = $this->lan;
		$strings = inc\ssmMain::getStrings($lan);

		$strings[$wordid] = $word;

		inc\ssmMain::saveFile($lan, $strings);
		return;
	}


	public function newWord($word=''){
		$strings = inc\ssmMain::getStrings($this->lan);

		$stringHash = md5($word);

		if(isset($strings[$stringHash]))return;

		$mainStrings = inc\ssmMain::getStrings('mainfile');
		if(isset($mainStrings[$stringHash]))return;

		$strings[$stringHash] = $word;
		$mainStrings[$stringHash] = $word;

		inc\ssmMain::saveFile($this->lan, $strings);
		inc\ssmMain::saveFile('mainfile', $mainStrings);
		return;
	}


	public function getCurrentLan(){
		return $this->lan;
	}

	public function getLans(){
		return $this->lans;
	}

}