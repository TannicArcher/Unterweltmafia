<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	if (isset($_GET['general']))
	{
		$firmaer = array();
		$owners = array();
		
		$sql = $db->Query("SELECT id,owner,place,lozuri,created FROM `lozuri` WHERE `active`='1' ORDER BY id DESC");
		while ($firma = $db->FetchArray($sql))
		{
			$owner = $db->QueryFetchArray("SELECT id,name,level,health FROM `[players]` WHERE `id`='".$firma['owner']."'");

			if ($owner['level'] <= 0 || $owner['health'] <= 0)
			{
				$db->Query("UPDATE `lozuri` SET `active`='0' WHERE `id`='".$firma['id']."'");
			}
			else
			{
				$firmaer[] = $firma;
				$owners[$firma['id']] = $owner;
			}
		}
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('subMenu-17')?></h1>
    <?
	if (count($firmaer) <= 0)
	{
		echo '<p>'.$langBase->get('err-06').'</p>';
	}
	else
	{
?>
	<table class="table">
    	<thead>
        	<tr>
            	<td><?=$langBase->get('ot-city')?></td>
                <td><?=$langBase->get('moneda-01')?></td>
                <td><?=$langBase->get('loz-11')?></td>
                <td><?=$langBase->get('txt-27')?></td>
            </tr>
        </thead>
        <tbody>
        <?
		foreach ($firmaer as $firma)
		{
		?>
        	<tr class="c_<?=($i++%2 ? 1 : 2)?>">
            	<td class="center"><a href="<?=$config['base_url']?>?side=harta&amp;sted=<?=$firma['place']?>"><?=$config['places'][$firma['place']][0]?></a></td>
                <td><?=View::Player($owners[$firma['id']])?></td>
                <td class="center"><?=View::CashFormat($firma['lozuri'])?></td>
                <td class="t_right"><?=View::Time($firma['created'], false, 'H:i')?></td>
            </tr>
        <?
		}
		?>
        	<tr class="c_3"><td colspan="5"></td></tr>
        </tbody>
    </table>
<?}?>
</div>
<?
	}else{
	$firma = $db->QueryFetchArray("SELECT * FROM `lozuri` WHERE `place`='".Player::Data('live')."' AND `active`='1'");

	if ($firma['id'] != ''){
		$firma_owner = $db->QueryFetchArray("SELECT id,name,level,health,userid FROM `[players]` WHERE `id`='".$firma['owner']."'");	
		if ($firma_owner['level'] <= 0 || $firma_owner['health'] <= 0){
			$db->Query("UPDATE `lozuri` SET `active`='0' WHERE `id`='".$firma['id']."'");
			unset($firma_owner, $firma);
		}
	}
		
	if ($firma['id'] == ''){
		if (isset($_POST['lz_buy']) && $firma_owner['id'] != Player::Data('id'))
		{
			if(Player::Data('points') < $config['lozuri_price']){
				echo View::Message($langBase->get('err-09'), 2);
			}else{
				$db->Query("UPDATE `[players]` SET `points`=`points`-'".$config['lozuri_price']."' WHERE `id`='".Player::Data('id')."'");
				$db->Query("INSERT INTO `lozuri` (`owner`, `place`, `lozuri`, `bank`, `created`)VALUES('".Player::Data('id')."', '".Player::Data('live')."', '10', '0', '".time()."')");
				
				View::Message($langBase->get('loz-03'), 1, true);
			}
		}
?>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('loz-01')?></h1>
    <p><?=$langBase->get('loz-02', array('-CITY-' => $config['places'][Player::Data('live')][0], '-COINS-' => View::CashFormat($config['lozuri_price'])))?></p>
    <form method="post" action="">
    	<p class="center"><input type="submit" name="lz_buy" value="<?=$langBase->get('txt-01')?>" /></p>
    </form>
</div>	
<?
}else{
	if(isset($_POST['submit'])){
?>
<script language="javascript"> function showPrize(id,what) { document.getElementById(id).src = what; } </script>
<form method="post">
<div class="bg_c w600">
<?
	if(Player::Data('cash') < 10000){
		View::Message($langBase->get('err-01'), 2, true);
	}elseif($firma['lozuri'] < 1){
		View::Message($langBase->get('loz-09'), 2, true);
	}else{
		$db->Query("UPDATE `[players]` SET `cash`=`cash`-'10000' WHERE `id`='".Player::Data('id')."'");
		$db->Query("UPDATE `lozuri` SET `lozuri`=`lozuri`-'1', `bank`=`bank`+'10000', `bank_income`=`bank_income`+'10000' WHERE `id`='".$firma['id']."'");

	if(mt_rand(0,2) == 1){
		$result1 = mt_rand(1,100);
		if($result1 >= 1 && $result1 <= 54){
			$result1 = "5000";
		}elseif($result1 >= 55 && $result1 <= 84){
			$result1 = "50000";
		}elseif($result1 >= 85 && $result1 <= 89){
			$result1 = "100000";
		}elseif($result1 >= 90 && $result1 <= 94){
			$result1 = "500";
		}elseif($result1 >= 95 && $result1 <= 99){
			$result1 = "30000";
		}elseif($result1 === 100){
			$result1 = "1000000";
		}

		$ticket_values = array('500', '5000', '50000', '30000', '100000', '1000000');

		$a[1]	=	$result1;
		$a[2]	=	$result1;
		$a[3]	=	$ticket_values[mt_rand(0,2)];
		$a[4]	=	$ticket_values[mt_rand(3,5)];
		$a[5]	=	$result1;
		$a[6]	=	$ticket_values[mt_rand(3,5)];

		/* Misiune */
		if ($player_mission->current_mission == 7 && $player_mission_data['started'] == 1)
		{
			if ($player_mission_data['objects'][2]['completed'] != 1)
			{
				$num = $player_mission_data['objects'][2]['wins'] + 1;
				$player_mission_data['objects'][2]['wins'] = $num;
				$player_mission->missions_data[$player_mission->current_mission]['objects'][2]['wins'] = $num;
				$player_mission->saveMissionData();
				
				if ($num >= 5)
				{
					$player_mission->completeObject(2);
				}
			}
		}

		if($result1 == "5000"){
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'5000' WHERE `id`='".Player::Data('id')."'");
		}elseif($result1 == "50000"){
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'50000' WHERE `id`='".Player::Data('id')."'");
		}elseif($result1 == "100000"){
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'100000' WHERE `id`='".Player::Data('id')."'");
		}elseif($result1 == "1000000"){
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'1000000' WHERE `id`='".Player::Data('id')."'");
		}elseif($result1 == "500"){
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'500' WHERE `id`='".Player::Data('id')."'");
		}elseif($result1 == "30000"){
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'30000' WHERE `id`='".Player::Data('id')."'");
		}
	}else{
		$verlies = rand(1,3);
		if($verlies == 1){
			$results = array('5000', '5000', '30000', '1000000', '100000', '1000000');
		} elseif($verlies == 2){
			$results = array('50000', '50000', '30000', '500', '100000', '1000000');
		} elseif($verlies == 3){
			$results = array('50000', '30000', '30000', '500', '100000', '1000000');
		}
		
		$j = 1;
		shuffle($results);
		foreach($results as $x){
			$a[$j] = $x; $j++;
		}
	}
?>
<table width="500" height="350" background="images/loz/loz.png" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td width="550" height="150"><br><br><table height="10" cellpadding="0" cellspacing="0">
			<tr>
				<td height="58"></td>
			</tr>
		</table>
		<table align="right" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100" height="40">
				<a style="cursor: hand;" onClick="showPrize('pic1','images/loz/<?php echo $a[1]; ?>.jpg')"><img id="pic1" src="images/loz/vol.jpg" width="80" height="52"></a>
				</td>
				<td width="8" height="40">
				</td>
				<td width="100" height="40">
				<a style="cursor: hand;" onClick="showPrize('pic2','images/loz/<?php echo $a[2]; ?>.jpg')"><img id="pic2" src="images/loz/vol.jpg" width="80" height="52"></a>
				</td>
				<td width="8" height="40">
				</td>
				<td width="100" height="40">
				<a style="cursor: hand;" onClick="showPrize('pic3','images/loz/<?php echo $a[3]; ?>.jpg')"><img id="pic3" src="images/loz/vol.jpg" width="80" height="52"></a>
				</td>
				<td width="8" height="40">
				</td>
			</tr>
			<tr>
				<td width="10" height="5">
				</td>
				<td width="50" height="5">
				</td>
				<td width="20" height="5">
				</td>
				<td width="50" height="5">
				</td>
				<td width="100" height="5">
				</td>
				<td width="50" height="14">
				</td>
			</tr>
			<tr>
				<td width="100" height="40">
				<a style="cursor: hand;" onClick="showPrize('pic4','images/loz/<?php echo $a[4]; ?>.jpg')"><img id="pic4" src="images/loz/vol.jpg" width="80" height="52"></a>
				</td>
				<td width="8" height="40">
				</td>
				<td width="100" height="40">
				<a style="cursor: hand;" onClick="showPrize('pic5','images/loz/<?php echo $a[5]; ?>.jpg')"><img id="pic5" src="images/loz/vol.jpg" width="80" height="52"></a>
				</td>
				<td width="8" height="40">
				</td>
				<td width="100" height="40">
				<a style="cursor: hand;" onClick="showPrize('pic6','images/loz/<?php echo $a[6]; ?>.jpg')"><img id="pic6" src="images/loz/vol.jpg" width="80" height="52"></a>
				</td>
				<td width="8" height="40">
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<?}?>
	<center><?=$langBase->get('loz-17')?> (10 000$) <br><input type="submit" name="submit" value="<?=$langBase->get('loz-14')?>" style="width: 100;"></center>
</div>
</form>
<?}else{?>
<div class="bg_c w500">
	<h1 class="big"><?=$config['places'][Player::Data('live')][0]?> - <?=$langBase->get('loz-00')?></h1>
	<p><?=$langBase->get('loz-04')?> <?=View::Player($firma_owner)?>!</p>
	<div class="hr big" style="margin: 10px 0 10px 0;"></div>
<?
	if ($firma_owner['id'] == Player::Data('id'))
	{
		echo '<p>'.$langBase->get('loz-05').'</p>';
	}
	else
	{
?>
	<form method="post">
		<?=$langBase->get('loz-16')?><br />
		<center><img src="images/loz/loz_blank.png" width="300" /></center>
		<center><?=$langBase->get('loz-15')?>: <b><?=$firma['lozuri']?></b> | <?=$langBase->get('txt-03')?>: <b>10 000$</b><br /><input type="submit" name="submit" value="<?=$langBase->get('loz-14')?>" style="width: 200;"></center>
	</form>
<?}?>
</div>
<?
if ($firma_owner['id'] == Player::Data('id'))
{
	if(isset($_POST['bank_settinn'])){
		$amount = View::NumbersOnly($db->EscapeString($_POST['bank_settinn']));
				
		if ($amount <= 0){
			echo View::Message($langBase->get('moneda-11'), 2);
		}elseif($amount > Player::Data('cash')){
			echo View::Message($langBase->get('err-01'), 2);
		}else{
			$db->Query("UPDATE `[players]` SET `cash`=`cash`-'".$amount."' WHERE `id`='".Player::Data('id')."'");
			$db->Query("UPDATE `lozuri` SET `bank`=`bank`+'".$amount."' WHERE `id`='".$firma['id']."'");
					
			View::Message($langBase->get('moneda-12', array('-CASH-' => View::CashFormat($amount))), 1, true);
		}
	}elseif($_POST['bank_taut']){
		$amount = View::NumbersOnly($db->EscapeString($_POST['bank_taut']));
				
		if ($amount <= 0){
			echo View::Message($langBase->get('moneda-13'), 2);
		}elseif($amount > $firma['bank']){
			echo View::Message($langBase->get('err-10'), 2);
		}else{
			$db->Query("UPDATE `lozuri` SET `bank`=`bank`-'".$amount."' WHERE `id`='".$firma['id']."'");
			$db->Query("UPDATE `[players]` SET `cash`=`cash`+'".$amount."' WHERE `id`='".Player::Data('id')."'");
					
			View::Message($langBase->get('moneda-14', array('-CASH-' => View::CashFormat($amount))), 1, true);
		}
	}elseif($_POST['loz_num']){
		$loz_num = View::NumbersOnly($db->EscapeString($_POST['loz_num']));
		$amount = $_POST['loz_num']*9000;

		if ($loz_num < 1){
			echo View::Message($langBase->get('loz-13'), 2);
		}elseif($amount > $firma['bank']){
			echo View::Message($langBase->get('loz-12'), 2);
		}else{
			$db->Query("UPDATE `lozuri` SET `lozuri`=`lozuri`+'".$loz_num."', `bank`=`bank`-'".$amount."', `bank_loss`=`bank_loss`+'".$amount."' WHERE `id`='".$firma['id']."'");
					
			View::Message($langBase->get('loz-10', array('-NUM-' => View::CashFormat($loz_num))), 1, true);
		}
	}
?>
    <script type="text/javascript">
        <!--
		window.addEvent('domready', function()
		{
			setPrice(0);
		});
        
        function setPrice(index)
        {
            return $('tot_price').set('html', number_format(index*9000, 0, '.', ' ') + '$');
        }
        -->
    </script>
<div class="bg_c w300">
	<h1 class="big"><?=$langBase->get('moneda-22')?></h1>
    <h2 class="center" style="margin-bottom: 0;"><?=$config['places'][Player::Data('live')][0]?> - <?=$langBase->get('loz-00')?></h2>
    <p class="center dark small" style="margin-top: 0;"><?=View::Time($firma['created'])?></p>
	<dl class="dd_right" style="width: 200px; margin: 10px auto;">
		<dt><?=$langBase->get('loz-11')?></dt>
		<dd><?=View::CashFormat($firma['lozuri'])?></dd>
	</dl>
	<div class="clear"></div>
	<div class="hr big" style="margin: 10px;"></div>
    <form method="post" action="">
        <dl class="dd_right" style="width: 200px; margin: 10px auto;">
            <dt><?=$langBase->get('loz-06')?></dt>
            <dd><input type="text" name="loz_num" class="flat" value="0" oninput="setPrice(this.value)" /></dd>
			<dt><?=$langBase->get('loz-07')?></dt>
			<dd>9 000$</dd>
			<dt><?=$langBase->get('loz-08')?></dt>
			<dd id="tot_price"></dd>
			<dt></dt>
            <dd><input type="submit" value="<?=$langBase->get('loz-06')?>" /></dd>
        </dl>
    </form>
    <div class="clear"></div>
    <div class="hr big" style="margin: 10px;"></div>
    <dl class="dd_right" style="width: 200px; margin: 10px auto;">
    	<dt><?=$langBase->get('moneda-23')?></dt>
        <dd><?=View::CashFormat($firma['bank_income'])?> $</dd>
        <dt><?=$langBase->get('moneda-24')?></dt>
        <dd><?=View::CashFormat($firma['bank_loss'])?> $</dd>
        <dt><?=$langBase->get('moneda-25')?></dt>
        <dd><?=(View::CashFormat($firma['bank_income']-$firma['bank_loss']))?> $</dd>
    </dl>
    <div class="clear"></div>
    <dl class="dd_right" style="width: 200px; margin: 10px auto;">
    	<dt><?=$langBase->get('banca-45')?></dt>
        <dd><?=View::CashFormat($firma['bank'])?> $</dd>
    </dl>
    <div class="clear"></div>
    <form method="post" action="">
    	<dl class="dd_right" style="width: 200px; margin: 10px auto;">
            <dt><?=$langBase->get('banca-54')?></dt>
            <dd><input type="text" name="bank_settinn" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['panel_taut']))?> $" /></dd>
            <dt></dt>
            <dd><input type="submit" value="<?=$langBase->get('banca-54')?>" /></dd>
        </dl>
        <div class="clear"></div>
    </form>
    <form method="post" action="">
    	<dl class="dd_right" style="width: 200px; margin: 10px auto;">
            <dt><?=$langBase->get('banca-52')?></dt>
            <dd><input type="text" name="bank_taut" class="flat" value="<?=View::CashFormat(View::NumbersOnly($_POST['panel_taut']))?> $" /></dd>
            <dt></dt>
            <dd><input type="submit" value="<?=$langBase->get('banca-52')?>" /></dd>
        </dl>
        <div class="clear"></div>
    </form>
</div>
<?}}}}?>