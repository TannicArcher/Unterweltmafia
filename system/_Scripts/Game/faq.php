<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$faq = array();
	$sql = $db->QueryFetchArrayAll("SELECT id,title,text FROM `game_faq` ORDER BY title ASC");
	foreach ($sql as $faqItem)
	{
		$item = array(
			'id' => $faqItem['id'],
			'title' => View::NoHTML($faqItem['title']),
			'text' => $faqItem['text']
		);
		
		$faq[] = $item;
	}
	
	function array_utf8_encode($dat)
    {
        if (is_string($dat))
            return utf8_encode($dat);
        if (!is_array($dat))
            return $dat;
        $ret = array();
        foreach ($dat as $i => $d)
            $ret[$i] = array_utf8_encode($d);
        return $ret;
    }
?>
<script type="text/javascript">
	<!--
	var faq_data =<?=json_encode(array_utf8_encode($faq))?>
	
	
	window.addEvent('domready', function()
	{
		var wrap = document.getElement('.faq_wrap');
		var content = wrap.getElement('.faq_content').set('tween', { duration: 150 });
		
		var setContent = function(faqItem)
		{
			if (!faq_data[faqItem]) return;
			
			$$('.faq_menu ul li a').removeClass('active');
			document.getElement('.faq_menu ul li a[rel=' + faqItem + ']').addClass('active');
			
			faqItem = faq_data[faqItem];
			
			content.fade(0);
			(function()
			{
				content.set('html', '<h1>' + faqItem['title'] + '</h1>' + faqItem['text']);
				content.fade(1);
			}).delay(150);
		}
		
		wrap.getElements('.faq_menu ul li a').each(function(elem)
		{
			elem.addEvent('click', function()
			{
				setContent(elem.get('rel'));
			});
		});
		
		var hash = window.location.hash;
		if ($chk(hash))
		{
			hash = hash.split('_');
			setContent(hash[1]);
		}
		
		var hash = window.location.hash;
		var checkHash = function()
		{
			if (window.location.hash != hash)
			{
				hash = window.location.hash.split('_');
				setContent(hash[1] || 0);
				
				hash = window.location.hash;
			}
			
			$clear(timer);
			timer = setTimeout(checkHash, 100);
		}
		
		var timer = setTimeout(checkHash, 100);
	});
	-->
</script>
<div class="faq_wrap">
	<div class="faq_menu">
    	<ul>
        <?php
		foreach ($faq as $key => $item)
		{
			echo "<li><a href=\"#faq_" . $key . "\" rel=\"" . $key . "\"" . ($key == 8 ? ' class="active"' : '') . ">" . $item['title'] . "</a></li>\n";
		}
		?>
        </ul>
    </div>
    <div class="faq_content">
    	<h1><?=($faq[8]['title'])?></h1>
    	<?=($faq[8]['text'])?>
    </div>
    <div class="clear"></div>
</div>