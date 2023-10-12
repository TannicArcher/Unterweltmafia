<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	
	class Pagination
	{
		private $db = false;
		var $sqlText, $sqlResult, $arrayResult, $num_rows, $per_page, $current_page, $get_var, $adjacents;
		
		function __construct($sqlText, $per_page, $get_var, $adjacents = 1)
		{
			global $db;
			
			// Set get variable and current page
			$this->get_var = $get_var;
			$this->current_page = $_GET[$get_var];
			$this->current_page = (empty($this->current_page) || $this->current_page <= 0) ? 1 : $this->current_page;
			
			// Set SQL query and get results
			$this->sqlText = $sqlText;
			$this->sqlResult = $db->Query($this->sqlText);
			$this->num_rows = $db->GetNumRows($this->sqlResult);
			
			// Save results in array
			$arrayResult = array();
			while ($res = $db->FetchArray($this->sqlResult))
			{
				$arrayResult[] = $res;
			}
			$this->arrayResult = $arrayResult;
			
			// Higher than max
			$this->current_page = $this->current_page > ceil($this->num_rows / $per_page) ? ceil($this->num_rows / $per_page) : $this->current_page;
			
			// Other inputs
			$this->per_page = $per_page;
			$this->adjacents = $adjacents;
		}
		
		function GetNumPages($add = 0)
		{
			// return max number of pages (last page number)
			return ceil(($this->num_rows+$add) / $this->per_page);
		}
		
		function GetSQLRows($all = 'limit')
		{
			if ($all != 'limit')
			{
				return $this->arrayResult;
			}
			else
			{
				return array_slice($this->arrayResult, ($this->current_page - 1) * $this->per_page, $this->per_page);
			}
		}
		
		function GetLinkHref($pagenum)
		{
			$queryString = $_SERVER['QUERY_STRING'];
			
			$pattern = array('/' . $this->get_var.'=[^&]*&?/', '/&$/');
        	$replace = array('', '');
        	$queryString = preg_replace($pattern, $replace, $queryString);
        	$queryString = str_replace('&', '&amp;', $queryString);
			
			if (!empty($queryString))
			{
            	$queryString.= '&amp;';
        	}
			
			return $GLOBALS['config']['base_url'].'?'.$queryString.$this->get_var.'='.$pagenum;
		}
		
		function GetSeperator()
		{
			// ... seperator
			return '<a href="#" onclick="var page = prompt(\'Please enter an number between 1 and ' . $this->GetNumPages() . '.\'); if( page > 0 &amp;&amp; page &lt;= ' . $this->GetNumPages() . ' ){ NavigateTo(\''.$this->GetLinkHref("'+page+'").'\'); } return false;" class="seperator">...</a>';
		}
		
		function GetPageLinks()
		{
			$begin     = $this->current_page < 0 ? 0 : $this->current_page * $this->per_page;
			$num_rows  = $this->num_rows;
			$links     = '';
			
			$links .= '<div class="pagination">';
			
			if( $num_rows <= $this->per_page )
			{
				$links .= '<a href="'.$this->GetLinkHref(1).'" class="active">1</a> ';
			}
			else
			{
				$links .= $begin / $this->per_page <= 1 ? '<span class="prev">Back</span> ' : '<a href="'.$this->GetLinkHref($this->current_page-1).'" class="prev">Back</a> ';
				
				if( $this->GetNumPages() < 7 + ($this->adjacents*2) )
				{
					for( $i = 1; $i <= $this->GetNumPages(); $i++ )
					{
						$class = $i == $this->current_page ? ' class="active"' : '';
						$links .= '<a href="'.$this->GetLinkHref($i).'"'.$class.'>'.($i).'</a> ';
					}
				}
				elseif( $this->GetNumPages() > 5 + ($this->adjacents*2) )
				{
					if ( $this->current_page < 1 + ($this->adjacents*2) )
					{
						for( $i = 1; $i < 4 + ($this->adjacents*2); $i++ )
						{
							$class = $i == $this->current_page ? ' class="active"' : '';
							$links .= '<a href="'.$this->GetLinkHref($i).'"'.$class.'>'.($i).'</a> ';
						}
						$links .= $this->GetSeperator();
						$links .= '<a href="'.$this->GetLinkHref($this->GetNumPages()-1).'">'.($this->GetNumPages()-1).'</a> '; // last page - 1
						$links .= '<a href="'.$this->GetLinkHref($this->GetNumPages()).'">'.($this->GetNumPages()).'</a> '; // last page
					}
					elseif( $this->GetNumPages() - ($this->adjacents*2) > $this->current_page && $this->current_page > ($this->adjacents*2) )
					{
						$links .= '<a href="'.$this->GetLinkHref(1).'">1</a> '; // first page
						$links .= '<a href="'.$this->GetLinkHref(2).'">2</a> '; // second page
						$links .= $this->GetSeperator();
						for( $i = $this->current_page - $this->adjacents; $i <= $this->current_page + $this->adjacents; $i++ )
						{
							$class = $i == $this->current_page ? ' class="active"' : '';
							$links .= '<a href="'.$this->GetLinkHref($i).'"'.$class.'>'.($i).'</a> ';
						}
						$links .= $this->GetSeperator();
						$links .= '<a href="'.$this->GetLinkHref($this->GetNumPages()-1).'">'.($this->GetNumPages()-1).'</a> '; // last page - 1
						$links .= '<a href="'.$this->GetLinkHref($this->GetNumPages()).'">'.($this->GetNumPages()).'</a> '; // last page
					}
					else
					{
						$links .= '<a href="'.$this->GetLinkHref(1).'">1</a> '; // first page
						$links .= '<a href="'.$this->GetLinkHref(2).'">2</a> '; // second page
						$links .= $this->GetSeperator();
						for( $i = $this->GetNumPages() - (2 + ($this->adjacents*2)); $i <= $this->GetNumPages(); $i++ )
						{
							$class = $i == $this->current_page ? ' class="active"' : '';
							$links .= '<a href="'.$this->GetLinkHref($i).'"'.$class.'>'.($i).'</a> ';
						}
					}
				}
				
				$links .= $this->current_page >= $this->GetNumPages() ? '<span class="next">Next</span>' : '<a href="'.$this->GetLinkHref($this->current_page+1).'" class="next">Next</a>';
			}
			
			$links .= '</div>';
			
			return $links;
			
		}
	}
?>