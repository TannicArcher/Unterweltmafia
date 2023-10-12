<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	foreach ($config['places'] as $id => $place)
	{
		if (!$_SESSION['MZ_travel_id'][$id])
		{
			$_SESSION['MZ_travel_id'][$id] = substr(sha1(uniqid(rand())), 0, 4);
		}
	}
	
	$latency = Player::Data('travel_last') + Player::Data('travel_latency') - time();
	
	if (isset($_GET['reis'], $_GET['id']) && $_GET['reis'] != Player::Data('live'))
	{
		if ($_GET['id'] != $_SESSION['MZ_travel_id'][$_GET['reis']])
		{
			View::Message('ERROR!', 2, true, '/game/?side=' . $_GET['side']);
		}
		
		$place_key = $db->EscapeString($_GET['reis']);
		$place = $config['places'][$place_key];
		
		if (!$place)
		{
			View::Message($langBase->get('harta-01'), 2, true, '/game/?side=' . $_GET['side']);
		}
		elseif ($latency > 0)
		{
			View::Message($langBase->get('harta-02', array('-TIME-' => $latency)), 2, true, '/game/?side=' . $_GET['side']);
		}
		
		$from = $config['places'][Player::Data('live')];
		
		$lat1 = $from[1]['lat_lon'][0]; 
		$lon1 = $from[1]['lat_lon'][1]; 
		$lat2 = $place[1]['lat_lon'][0]; 
		$lon2 = $place[1]['lat_lon'][1]; 
		
		$distance = round((3958*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lon2-$lon1)*($lon2-$lon1))/180), 2);
		
		$price = round($distance * $config['travelPrice_per_km'], 0);
		
		if ($price > Player::Data('cash'))
		{
			View::Message($langBase->get('err-01'), 2, true, '/game/?side=' . $_GET['side']);
		}
		
		$sql = $db->Query("SELECT id,family FROM `family_businesses` WHERE `family`!='0' AND `type`='travel' AND `place`='".Player::Data('live')."'");
		$f_business = $db->FetchArray($sql);
		
		if ($f_business['id'] != '')
		{
			$db->Query("UPDATE `family_businesses` SET `bank_income`=`bank_income`+'".$price."' WHERE `id`='".$f_business['id']."'");
			$db->Query("UPDATE `[families]` SET `bank`=`bank`+'".$price."', `bank_income`=`bank_income`+'".$price."' WHERE `id`='".$f_business['family']."'");
		}
		
		$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$price."', `travel_last`='".time()."', `travel_latency`='".round($distance * $config['travel_latency_sec_per_km'], 0)."', `live`='".$place_key."' WHERE `id`='".Player::Data('id')."'");
		
		unset($_SESSION['MZ_travel_id']);
		View::Message('Okay, du bist in <b>'.$place[0].'</b>.', 1, true, '/game/?side=' . $_GET['side']);
	}
	elseif (isset($_POST['reset_travel']) && $latency > 0)
	{
		if (Player::Data('points') < $config['reset_travel_latency_price'])
		{
			echo View::Message($langBase->get('err-09'), 2);
		}
		else
		{
			$db->Query("UPDATE `[players]` SET `travel_latency`='0', `points`=`points`-'".$config['reset_travel_latency_price']."' WHERE `id`='".Player::Data('id')."'");
			
			View::Message($langBase->get('harta-03', array('-COINS-' => View::CashFormat($config['reset_travel_latency_price']))), 1, true);
		}
	}
	
	
	$sql = $db->Query("SELECT live FROM `[players]` WHERE `health`>'0' AND `level`>'0'");
	while ($player = $db->FetchArray($sql))
	{
		$players[$player['live']][] = $player;
	}
	
	$sql = $db->Query("SELECT place FROM `businesses` WHERE `active`='1' ORDER BY type DESC");
	while ($firma = $db->FetchArray($sql))
	{
		$firmaer[$firma['place']][] = $firma;
	}
	
	$sql = $db->Query("SELECT family,place FROM `family_businesses` WHERE `family`!='0' AND `type`='travel'");
	while ($f_b = $db->FetchArray($sql))
	{
		$f_businesses[$f_b['place']] = $f_b;
	}
	
	$sql = $db->Query("SELECT id,name,territories,image FROM `[families]` WHERE `active`='1'");
	$families = $db->FetchArrayAll($sql);
	
	$f_territories = array();
	foreach ($families as $family)
	{
		$fam_b[$family['id']] = $family;
		
		foreach (unserialize($family['territories']) as $ter)
		{
			$f_territories[$ter] = $family;
		}
	}
	
	$sql = $db->Query("SELECT bunkers FROM `bunker` WHERE `player`='".Player::Data('id')."'");
	$bunker = $db->FetchArray($sql);
	$bunkers = unserialize($bunker['bunkers']);
?>
<script type="text/javascript">
	window.addEvent('domready', function()
	{
		new Map($('map_overview'));
	});
	
	var hovers = [];
	var Map = new Class({
		initialize: function(wrap)
		{
			this.wrap = wrap;
			
			var self = this;
			this.wrap.getElements('a.map_item').each(function(elem)
			{
				self.handle_place(elem);
			});
		},
		handle_place: function(elem)
		{
			var hover = this.wrap.getElement('.item_' + elem.get('rel'));
			hovers.push(hover);
			
			var mouseenter = function()
			{
				hovers.each(function(elm)
				{
					if (!elm.hasClass('hidden')){ elm.addClass('hidden'); }
				});
				
				if (hover.hasClass('hidden')){ hover.removeClass('hidden'); }
				$clear(hover.timer);
			}
			var mouseleave = function()
			{
				hover.timer = setTimeout(function()
				{
					if (!hover.hasClass('hidden')){ hover.addClass('hidden'); }
				}, 1000);
			}
			
			elem.addEvents(
			{
				mouseenter: function()
				{
					mouseenter();
				},
				mouseleave: function(e)
				{
					mouseleave();
				}
			});
			
			hover.addEvents(
			{
				mouseenter: function()
				{
					mouseenter();
				},
				mouseleave: function(e)
				{
					mouseleave();
				}
			});
		}
	});
	-->
</script>
<div id="map_overview">
<?php
if ($latency > 0)
{
?>
<div class="bg_c c_1 travelinfo">
	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
    <p><?=$langBase->get('harta-02', array('-TIME-' => $latency))?></p>
    <div class="hr big" style="margin: 10px 0 10px 0;"></div>
    <form method="post" action="" style="margin: 10px;">
    	<input type="hidden" name="reset_travel" />
        <p class="center"><a href="#" class="button form_submit"><?=$langBase->get('txt-42')?></a></p>
    </form>
    <p class="small dark center"><?=View::CashFormat($config['reset_travel_latency_price'])?> <?=$langBase->get('ot-points')?></p>
</div>
<?php
}

$from = $config['places'][Player::Data('live')];

foreach ($config['places'] as $p_id => $place)
{	
	$lat1 = $from[1]['lat_lon'][0]; 
	$lon1 = $from[1]['lat_lon'][1]; 
	$lat2 = $place[1]['lat_lon'][0]; 
	$lon2 = $place[1]['lat_lon'][1]; 
	
	$distance = round((3958*3.1415926*sqrt(($lat2-$lat1)*($lat2-$lat1) + cos($lat2/57.29578)*cos($lat1/57.29578)*($lon2-$lon1)*($lon2-$lon1))/180), 2);
	
	$price = round($distance * $config['travelPrice_per_km'], 0);
	
	$territory = $f_territories[$p_id];
	
	if ($territory)
	{
		echo '<a href="'.$config['base_url'].'?side=familie/familie&amp;id='.$territory['id'].'" class="fam_image" title="'.$territory['name'].'" style="top: '.($place[1]['px'][0] - 45).'px; left: '.($place[1]['px'][1] - 10).'px;"><img src="'.$territory['image'].'" alt="'.$territory['name'].'" /></a>';
	}
?>
	<a href="#" onclick="return false;" rel="<?=$p_id?>" class="map_item<?php if($p_id == Player::Data('live') && $_GET['sted'] != $p_id) echo ' active'; ?><?php if($_GET['sted'] == $p_id) echo ' selected'; ?>" style="top: <?=$place[1]['px'][0]?>px; left: <?=$place[1]['px'][1]?>px;"><?=$place[0]?></a>
    <div class="hover item_<?=$p_id?> hidden" style="top: <?=($place[1]['px'][0] - 120)?>px; left: <?=($place[1]['px'][1] - 50)?>px;">
    	<img src="<?=$config['base_url']?>images/map_hover_<?=$p_id?>.jpg" alt="<?=$place[0]?>" class="header" />
        <h2 class="center"><?=$place[0]?></h2>
        <dl class="dd_right dt_50">
        	<dt><?=$langBase->get('txt-43')?></dt>
            <dd><?=View::CashFormat(count($players[$p_id]))?></dd>
            <dt><?=$langBase->get('function-companies')?></dt>
            <dd><?=View::CashFormat(count($firmaer[$p_id]))?></dd>
            <dt><?=$langBase->get('ot-family')?></dt>
            <dd><?=($territory ? '<a href="'.$config['base_url'].'?side=familie/familie&amp;id='.$territory['id'].'">'.$territory['name'].'</a>' : 'N/A')?></dd>
        </dl>
        <div class="clear"></div>
        <div class="hr big" style="border-color: #555555; margin-top: 10px;"></div>
        <?php
		if ($f_businesses[$p_id])
		{
			$fam = $fam_b[$f_businesses[$p_id]['family']];
			echo '<p class="center">&laquo;'.$langBase->get('txt-22').'&raquo;</p>';
		}
		
		if ($p_id == Player::Data('live'))
		{
			echo '<p class="center" style="margin: 5px;">'.$langBase->get('harta-04').'</p>';
		}
		elseif ($latency > 0)
		{
			echo '<p class="center" style="margin: 5px;">'.$langBase->get('harta-02', array('-TIME-' => $latency)).'</p>';
		}
		else
		{
		?>
        <dl class="dd_right">
        	<dt><?=$langBase->get('ot-from')?></dt>
            <dd><?=$from[0]?></dd>
            <dt><?=$langBase->get('ot-to')?></dt>
            <dd><?=$place[0]?></dd>
            <dt><?=$langBase->get('ot-distanta')?></dt>
            <dd><?=$distance?> km</dd>
            <dt><?=$langBase->get('txt-03')?></dt>
            <dd><?=View::CashFormat($price)?> $</dd>
            <dt><?=$langBase->get('txt-07')?></dt>
            <dd><?=View::strTime(round($distance * $config['travel_latency_sec_per_km'], 0))?></dd>
        </dl>
        <p class="center clear" style="margin: 5px;"><a href="<?=$config['base_url']?>?side=<?=$_GET['side']?>&amp;reis=<?=$p_id?>&amp;id=<?=$_SESSION['MZ_travel_id'][$p_id]?>">&raquo; <?=$langBase->get('harta-07')?></a></p>
        <?php
		}
		?>
        <div class="hr big" style="border-color: #555555; margin-top: 10px;"></div>
        <p class="center"><?=($bunkers[$p_id] ? $langBase->get('harta-05') : $langBase->get('harta-06'))?></p>
    </div>
<?php
}
?>
</div>