<?

function GetLinkHref($pagenum)
		{
			$queryString = $_SERVER['QUERY_STRING'];
			
			$pattern = array('/p=[^&]*&?/', '/&$/');
        	$replace = array('', '');
        	$queryString = preg_replace($pattern, $replace, $queryString);
        	$queryString = str_replace('&', '&amp;', $queryString);
			
			if (!empty($queryString))
			{
            	$queryString.= '&amp;';
        	}
			
			return '?'.$queryString.'p='.$pagenum;
		}
						
	$lastpage = ceil($total_pages/$limita);
	$lpm1 = $lastpage - 1;
	if($lastpage > 1){
		if ($lastpage < 6)
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $pagina)
					$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
				else
					$pagination.= "<li><a href=\"".GetLinkHref($counter)."\">$counter</a></li>";					
			}
		}elseif($lastpage > 6)
		{
			if($pagina < 5)		
			{
				for ($counter = 1; $counter < 5; $counter++)
				{
					if ($counter == $pagina)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"".GetLinkHref($counter)."\">$counter</a></li>";					
				}
				$pagination.= "<li><a href=\"#\">...</a></li>";
				$pagination.= "<li><a href=\"".GetLinkHref($lpm1)."\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"".GetLinkHref($lastpage)."\">$lastpage</a></li>";		
			}
			elseif($lastpage - 5 > $pagina && $pagina > ($adjacents * 2))
			{
				$pagination.= "<li><a href=\"".GetLinkHref('1')."\">1</a></li>";
				$pagination.= "<li><a href=\"".GetLinkHref('2')."\">2</a></li>";
				$pagination.= "...";
				for ($counter = $pagina - $adjacents; $counter <= $pagina + $adjacents; $counter++)
				{
					if ($counter == $pagina)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"".GetLinkHref($counter)."\">$counter</a></li>";					
				}
				$pagination.= "<li><a href=\"#\">...</a></li>";
				$pagination.= "<li><a href=\"".GetLinkHref($lpm1)."\">$lpm1</a></li>";
				$pagination.= "<li><a href=\"".GetLinkHref($lastpage)."\">$lastpage</a></li>";		
			}
			else
			{
				$pagination.= "<li><a href=\"".GetLinkHref('1')."\">1</a></li>";
				$pagination.= "<li><a href=\"".GetLinkHref('2')."\">2</a></li>";
				$pagination.= "<li><a href=\"#\">...</a></li>";
				for ($counter = $lastpage - 5; $counter <= $lastpage; $counter++)
				{
					if ($counter == $pagina)
						$pagination.= "<li class=\"active\"><a href=\"#\">$counter</a></li>";
					else
						$pagination.= "<li><a href=\"".GetLinkHref($counter)."\">$counter</a></li>";					
				}
			}
		}
	}
?>