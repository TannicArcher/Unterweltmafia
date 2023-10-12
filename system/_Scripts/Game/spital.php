<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$hospital_data = unserialize(Player::Data('hospital_data'));
	$timeleft = $hospital_data['added'] + $hospital_data['time_length'] - time();
	
	if (!empty($hospital_data) && $timeleft <= 0)
	{
		$new_health = Player::Data('health') + $hospital_data['extra_health'] > $config['max_health'] ? $config['max_health'] : Player::Data('health') + $hospital_data['extra_health'];
		$db->Query("UPDATE `[players]` SET `health`='".$new_health."', `hospital_data`='a:0:{}' WHERE `id`='".Player::Data('id')."'");
		
		View::Message($langBase->get('spital-01', array('-SUM-' => round((($new_health - Player::Data('health')) / $config['max_health']) * 100, 2))), 1, true);
	}
	
	$sql = $db->Query("SELECT * FROM `family_businesses` WHERE `type`='hospital' AND `family`!='0' AND `place`='".Player::Data('live')."'");
	$business = $db->FetchArray($sql);
	
	if ($business['family'] != 0)
	{
		$sql = $db->Query("SELECT id,name FROM `[families]` WHERE `id`='".$business['family']."' AND `active`='1'");
		$family = $db->FetchArray($sql);
	}
	
	if ($timeleft <= 0)
	{
		if (isset($_POST['length']))
		{
			$length = round(View::NumbersOnly($db->EscapeString($_POST['length'])), 0);
			$price = round($length * $config['hospital_cost_per_min'], 0);
			$extra_health = round($length * $config['hospital_health_per_min'], 0);
			
			if (Player::Data('health') >= $config['max_health'])
			{
				echo View::Message($langBase->get('spital-02'), 2);
			}
			elseif ($extra_health < $config['hospital_health_per_min'])
			{
				echo View::Message($langBase->get('spital-03'), 2);
			}
			elseif ($extra_health + Player::Data('health') > $config['max_health'] + $config['hospital_health_per_min'])
			{
				echo View::Message($langBase->get('spital-03'), 2);
			}
			elseif ($price > Player::Data('cash'))
			{
				echo View::Message($langBase->get('err-01'), 2);
			}
			else
			{
				$hospital_data = array(
					'added' => time(),
					'time_length' => $length * 60,
					'extra_health' => $extra_health
				);
				
				$db->Query("UPDATE `[players]` SET `hospital_data`='".serialize($hospital_data)."', `cash`=`cash`-'".$price."' WHERE `id`='".Player::Data('id')."'");
				
				if ($family['id'] != '')
				{
					$db->Query("UPDATE `[families]` SET `bank`=`bank`+'".$price."', `bank_income`=`bank_income`+'".$price."' WHERE `id`='".$family['id']."'");
					$db->Query("UPDATE `family_businesses` SET `bank_income`=`bank_income`+'".$price."' WHERE `id`='".$business['id']."'");
				}
				
				View::Message($langBase->get('spital-05', array('-HEALTH-' => round(($hospital_data['extra_health'] / $config['max_health']) * 100, 2), '-TIME-' => trim((View::strTime($length * 60, 1))))), 1, true, '/game/?side=spital');
			}
		}
?>
<script type="text/javascript">
	<!--
	var kr_per_min = <?=$config['hospital_cost_per_min']?>;
	var health_per_min = <?=$config['hospital_health_per_min']?>;
	
	window.addEvent('domready', function()
	{
		var length_box = document.getElement('input[name=length]');
		var extra_health = $('extra_health');
		var price = $('price');
		
		var calculate = function()
		{
			var length = length_box.get('value');
			
			extra_health.set('html', ((health_per_min * length) / <?=$config['max_health']?> * 100).toFixed(2));
			price.set('html', number_format(kr_per_min * length, 0, '.', ' '));
		}
		
		calculate();
		
		$$('input[name=length]').addEvent('keyup', function()
		{
			calculate();
		});
	});
	-->
</script>
<?php
	}
	else
	{
		if (isset($_POST['end']))
		{
			$db->Query("UPDATE `[players]` SET `hospital_data`='a:0:{}' WHERE `id`='".Player::Data('id')."'");
		}
	}
?>
<div style="width: 500px; margin: 0px auto;">
	<div class="left" style="width: 270px;">
    	<div class="bg_c" style="width: 250px;">
        	<h1 class="big"><?=$langBase->get('function-hospital')?></h1>
            <?php
			if ($timeleft > 0)
			{
				echo '<p>'.$langBase->get('spital-06', array('-TIME-' => $timeleft)).'</p>';
			?>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <form method="post" action="">
            	<p class="center">
                	<input type="submit" value="Opreste Tratamentul" name="end" />
                </p>
                <p class="t_justify small dark"><?=$langBase->get('spital-07')?></p>
            </form>
            <?php
			}
			else
			{
			?>
            <p><?=$langBase->get('spital-08')?></p>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <form method="post" action="">
            	<dl class="dd_right">
            		<dt><?=$langBase->get('spital-09')?></dt>
                    <dd><input type="text" name="length" class="flat numbersOnly" maxlength="2" style="min-width: 50px; width: 50px;" value="<?=View::NumbersOnly($_POST['length'])?>" /></dd>
            	</dl>
                <div class="clear"></div>
                <dl class="dd_right">
            		<dt><?=$langBase->get('spital-10')?></dt>
                    <dd><span id="extra_health" style="font-weight: bold;">0</span> %</dd>
                    <dt><?=$langBase->get('txt-03')?></dt>
                    <dd><span id="price">0</span> $</dd>
            	</dl>
               	<p class="center clear">
                	<input type="submit" value="<?=$langBase->get('spital-11')?>" />
                </p>
            </form>
            <?php
			}
			?>
        </div>
    </div>
    <div class="left" style="width: 220px; margin-left: 10px;">
    	<div class="bg_c" style="width: 200px;">
        	<h1 class="big"><?=$langBase->get('txt-22')?></h1>
            <p class="center">
            	<img src="<?=$config['base_url']?>images/sykehus.jpg" alt="" />
            </p>
            <div class="hr big" style="margin: 10px 0 10px 0;"></div>
            <dl class="dd_right">
            	<dt><?=$langBase->get('txt-05')?></dt>
                <dd><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=Player::Data('live')?>"><?=$config['places'][Player::Data('live')][0]?></a></dd>
            <?php
			if ($family['id'] != ''){
			?>	
				<dt><?=$langBase->get('ot-family')?></dt>
				<dd><a href="<?=$config['base_url']?>?side=familie/familie&amp;id=<?=$family['id']?>"><?=$family['name']?></a></dd>
			<?}?>
			</dl>
        </div>
    </div>
	<div class="clear"></div>
</div>