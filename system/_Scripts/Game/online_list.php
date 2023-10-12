<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$sql = "SELECT id,name,profileimage,rank,last_active,bank,cash FROM `[players]` WHERE `online`+'3600'>'".time()."' ORDER BY online DESC";
	$pagination = new Pagination($sql, 20, 'p');
	$pagination_links = $pagination->GetPageLinks();
	$online_list = $pagination->GetSQLRows();
	
	foreach ($online_list as $key => $player)
	{
		$online_list[$key]['rank'] = $config['ranks'][$player['rank']][0];
		$online_list[$key]['last_active'] = View::Time($player['last_active']);
		$online_list[$key]['money_rank'] = View::MoneyRank($player['cash']+$player['bank'], true);
	}
?>
<script type="text/javascript">
	<!--
	var online_list = <?=json_encode($online_list)?>;
	
	
	function makeList(type)
	{
		if (!['grid', 'list'].contains(type))
		{
			return;
		}
		
		document.location.hash = type;
		
		$$('#players_online_views a').removeClass('active');
		$$('#players_online_views a[rel=' + type + ']').addClass('active');
		
		var content = $('players_online_content').removeClass('grid').removeClass('list').addClass(type);
		
		if (type == 'grid')
		{
			online_list.each(function(player)
			{
				new Element('a',
				{
					href: '/game/s/' + player.name,
					'class': 'user_container',
					html: '<img src="' + player.profileimage + '" alt="" class="profileimage" /><span>' + player.name + '</span>'
				}).inject(content);
			});
		}
		else
		{
			online_list.each(function(player)
			{
				new Element('div',
				{
					'class': 'user_container',
					'html': '<img src="' + player.profileimage + '" alt="" class="profileimage" /><span class="name">' + player.name + '<br />' + player.rank + '</span><div class="right"><?=$langBase->get('ot-rankcash')?>: ' + player.money_rank + '<br /><?=$langBase->get('ot-lasta')?>: ' + player.last_active + '</div><a href="/game/?side=mesaje&amp;a=ny&amp;nick=' + player.name + '" class="send_pm"><?=$langBase->get('subMenu-09')?></a>'
				}).addEvent('click', function()
				{
					document.location = '/game/s/' + player.name;
				}).inject(content);
			});
		}
	}
	
	window.addEvent('domready', function()
	{
		var hash = document.location.hash.replace('#', '');
		makeList(['grid', 'list'].contains(hash) ? hash : 'grid');
		
		var content = $('players_online_content');
		
		$$('#players_online_views a').each(function(elem)
		{
			elem.addEvent('click', function()
			{
				if (elem.hasClass('active'))
				{
					return;
				}
				
				content.empty();
				
				makeList(elem.get('rel'));
				
				return false;
			});
		});
	});
	-->
</script>
<div id="players_online_views">
	<h2><?=$langBase->get('online-01', array('-NUM-' => View::CashFormat($pagination->num_rows)))?></h2>
	<a href="#" rel="list" title="List"></a> <a href="#" rel="grid" title="Grid"></a>
</div>
<div class="clear"></div>
<div id="players_online_content"></div>
<div class="clear"></div>
<div style="margin: 20px;">
<?=$pagination_links?>
</div>