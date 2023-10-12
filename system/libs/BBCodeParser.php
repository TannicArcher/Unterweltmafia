<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	
	/*
		EXAMPLE
		--------------
		$bbcode = new BBCodeParser('text goes [b]here[/b]', 'a page', true);
		echo $bbcode->result; // text goes <b>here</b>
		--------------
		example above allows smileys
	*/
	
	/**
	  BBCode parser
	*/
	class BBCodeParser
	{
		/**
		 *  Original input
		 *
		 *  @var      string
		*/
		public $string;
		
		/**
		 *  Converted string
		 *
		 *  @var      string
		*/
		public $result;
		
		/**
		 *  Where is the parser currently being used?
		 *
		 *  @var      string
		*/
		private $place = 'default';
		
		/**
		 *  Use smileys
		 *
		 *  @var      bool
		*/
		private $smileys = false;
		
		
		/**
		 *  Constructor
		 *
		 *  @param     string  $str
		 *             string  $place
		 *             bool    $smileys
		*/
		public function __construct ($str, $place = 'default', $smileys = false, $basics_add = array(), $maxImgHeight = false)
		{
			$this->string  = $str;
			$this->place   = $place;
			$this->smileys = $smileys;
			$this->basics_add = $basics_add;
			$this->max_img_height = $maxImgHeight;
			
			$result = $str;
			
			$result = $this->parse($result);
			
			$this->result = $result;
		}
		
		/**
		 *  Parse BBCode
		 *  
		 *  @param    string $string
		 *  @return   string
		*/
		private function parse ($string)
		{
			// Remove HTML chars
			$string = htmlspecialchars($string);
			
			// Line breaks
			$string = nl2br($string);
			
			$string = $this->parse_basics($string);
			$string = $this->parse_quote($string);
			$string = $this->parse_code($string);
			
			if ($this->smileys === true) $string = $this->set_smileys($string);
			
			return $string;
		}
		
		/**
		 *  Parse the basics
		 *  
		 *  @param    string $string
		 *  @return   string
		*/
		private function parse_basics ($string)
		{
			$search = array(
				'@\[(?i)b\](.*?)\[/(?i)b\]@si',
				'@\[(?i)i\](.*?)\[/(?i)i\]@si',
				'@\[(?i)u\](.*?)\[/(?i)u\]@si',
				'@\[(?i)s\](.*?)\[/(?i)s\]@si',
				'@\[(?i)url=(.*?)\](.*?)\[/(?i)url\]@si',
				'@\[(?i)center\](.*?)\[/(?i)center\]@si',
				'/@([-_+a-zA-Z0-9]+)/m',
				'/@\[([-_a-zA-Z0-9\s]+)\]/smx',
				'%\[(?i)color=(#[\da-fA-F]{6}+)\](.*?)\[/(?i)color\]%sm',
				'@\[(?i)thumbnail\](.*?)\[/(?i)thumbnail\]@si',
				'@\[(?i)shadow\](.*?)\[/(?i)shadow\]@si',
				'@\[(?i)img\](.*?)\[/(?i)img\]@si',
				'/http:\/\/www.youtube\.com\/watch\?v=([A-Za-z0-9._%-]*)[&\w;=\+_\-]*/',
				'@\[(?i)size=([0-9]+)\](.*?)\[/(?i)size\]@si',
				'@\[(?i)hr /\]@si',
				'@\[(?i)hr_big /\]@si'
			);
			$replace = array(
				'<b>\\1</b>',
				'<i>\\1</i>',
				'<u>\\1</u>',
				'<s>\\1</s>',
				'<a href="\\1">\\2</a>',
				'<center>\\1</center>',
				'@<a href="'.$GLOBALS['config']['base_url'].'s/$1" class="global_playerlink playerlink" rel="$1">$1</a>',
				'@<a href="'.$GLOBALS['config']['base_url'].'s/$1" class="global_playerlink playerlink" rel="$1">$1</a>',
				'<span style="color: $1;">$2</span>',
				'<span class="bbimg_thumbnail"><img src="$1" alt="" class="handle_image" /></span>',
				'<span style="text-shadow: #000000 1px 1px 1px;">$1</span>',
				($this->max_img_height ? '<span style="display: block; max-height: ' . $this->max_img_height . 'px;"><img src="\\1" alt="" class="handle_image" /></span>' : '<img src="\\1" alt="" class="handle_image" />'),
				'<object width="425" height="350"><param name="movie" value="http://www.youtube.com/v/\\1"></param><param name="wmode" value="transparent"></param><embed src="http://www.youtube.com/v/\\1" type="application/x-shockwave-flash" wmode="transparent" width="425" height="350"></embed></object>',
				'<span style="font-size: \\1px;">\\2</span>',
				'<div class="hr"></div>',
				'<div class="hr big"></div>'
			);
			
			foreach ($this->basics_add as $key => $value)
			{
				$search[] = $key;
				$replace[] = $value;
			}
			
			return preg_replace($search, $replace, $string);
		}
		
		/**
		 *  Parse [quote] tag
		 *  
		 *  @param    string $str
		 *  @return   string
		*/
		private function parse_quote ($str)
		{
			$open = '<div class="quote"><div class="q_top">Citat</div><div class="q_text">';
			$close = '</div></div>';
			
			preg_match_all('/\[quote\]/i', $str, $matches);
			$opentags = count($matches['0']);
			
			preg_match_all('/\[\/quote\]/i', $str, $matches);
			$closetags = count($matches['0']);
			
			if ($opentags == $closetags)
			{
				$str = str_replace ('[' . 'quote]', $open, $str);
				$str = str_replace ('[/' . 'quote]', $close, $str);
			}
			
			return $str;

		}
		
		/**
		 *  Parse [code] tag
		 *  
		 *  @param    string $str
		 *  @return   string
		*/
		private function parse_code ($str)
		{
			$open = '<div class="code"><div class="c_top">Cod</div><div class="c_text">';
			$close = '</div></div>';
			
			preg_match_all('/\[code\]/i', $str, $matches);
			$opentags = count($matches['0']);
			
			preg_match_all('/\[\/code\]/i', $str, $matches);
			$closetags = count($matches['0']);
			
			if ($opentags == $closetags)
			{
				$str = str_replace ('[' . 'code]', $open, $str);
				$str = str_replace ('[/' . 'code]', $close, $str);
			}
			
			return $str;
		}
		
		/**
		 *  Adds smileys to string
		 *  Edit smileys in config file
		 *  
		 *  @param    string $str
		 *  @return   string
		*/
		private function set_smileys ($str)
		{
			$config = $GLOBALS['config'];
			
			foreach( $config['game_smileys'] as $smiley_id => $smiley )
			{
				$str = str_replace($smiley[0], '<img src="'.$config['game_smileys_path'].$smiley_id.'"" />', $str);
			}
			
			return $str;
		}
	}
?>