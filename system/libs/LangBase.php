<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	class LangBase
	{
		public $language;
		private $lang_inputs;
		
		function __construct($inputs, $lang)
		{
			global $languages_supported;
			
			$this->language = $lang;
			if (!@$languages_supported[$this->language]) $this->language = 'RO';
			
			$this->lang_inputs = $inputs;
		}
		
		function get($keyword, $inputs = array())
		{
			return $this->setInputs(@$this->lang_inputs[$keyword][$this->language], $inputs);
		}
		
		function getLogEventText($type, $inputs)
		{
			return $this->setInputs(@$this->lang_inputs['logevents'][$type][$this->language], $inputs);
		}
		
		function setInputs($string, $inputs)
		{
			if (empty($inputs) || !is_array($inputs)) return $string;
			
			foreach ($inputs as $search => $replace)
			{
				$string = str_replace($search, $replace, $string);
			}
			
			return $string;
		}
	}
?>