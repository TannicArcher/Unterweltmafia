<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	$sql = $db->Query("SELECT * FROM `game_stats` WHERE `id`='1'");
	$game_stats = $db->FetchArray($sql);
	
	$user_stats      =  unserialize($game_stats['user_stats']);
	$player_stats    =  unserialize($game_stats['player_stats']);
	$money_stats     =  unserialize($game_stats['money_stats']);
	$online_stats    =  unserialize($game_stats['online_stats']);
	$messages_stats  =  unserialize($game_stats['messages_stats']);
	$forum_stats     =  unserialize($game_stats['forum_stats']);
	$logevent_stats  =  unserialize($game_stats['logevent_stats']);
?>
<div style="margin: 0 auto; width: 630px;">
	<div class="left" style="width: 310px;">
    	<div class="bg_c c_1 t_justify" style="width: 290px; margin: 10px 0 10px -1px;">
            <p style="margin-top: 5px;"><?=$langBase->get('sts-01')?>: <b><?=View::Time($game_stats['last_updated'], true)?></b>.<br /><span style="color: #555555;"><?=View::strTime(time() - $game_stats['last_updated'], 1, ', ')?> ago.</span></p>
			<p><?=$langBase->get('runda-02')?>: <b>#<?=$admin_config['game_round']['value']?></b></p>
            <p><b><?=$langBase->get('sts-03')?>: </b><br /><span style="color: #b04600;"><?=View::CashFormat($online_stats['highest_online'][0])?> <?=$langBase->get('txt-43')?></span> - <?=View::Time($online_stats['highest_online'][1], true)?></p>
        </div>
        <table class="table">
        	<thead><tr><td colspan="2"><?=$langBase->get('sts-04')?></td></tr></thead>
            <tbody>
                <tr class="c_3">
                	<td><?=$langBase->get('stats-01')?></td>
                    <td class="t_right"><?=View::CashFormat($player_stats['num_total'])?></td>
                </tr>
                <tr class="c_2">
                	<td><?=$langBase->get('stats-02')?></td>
                    <td class="t_right"><?=View::CashFormat($player_stats['num_active'])?></td>
                </tr>
                <tr class="c_3">
                	<td><?=$langBase->get('sts-05')?></td>
                    <td class="t_right"><?=View::CashFormat($user_stats['num_deactivated'])?></td>
                </tr>
                <tr class="c_3">
                	<td><?=$langBase->get('sts-06')?></td>
                    <td class="t_right"><?=View::CashFormat($player_stats['num_dead'])?></td>
                </tr>
                <tr class="c_1"><td colspan="2"><b style="color: #444444;">Players activity</b></td></tr>
                <tr class="c_2">
                	<td><?=$langBase->get('sts-07')?></td>
                    <td class="t_right"><?=View::CashFormat($online_stats['last_24_hours'])?></td>
                </tr>
                <tr class="c_3">
                	<td><?=$langBase->get('sts-08')?></td>
                    <td class="t_right"><?=View::CashFormat($online_stats['last_12_hours'])?></td>
                </tr>
                <tr class="c_2">
                	<td><?=$langBase->get('sts-09')?></td>
                    <td class="t_right"><?=View::CashFormat($online_stats['last_6_hours'])?></td>
                </tr>
                <tr class="c_1"><td colspan="2"><b style="color: #444444;"><?=$langBase->get('sts-10')?></b></td></tr>
                <tr class="c_2">
                	<td><?=$langBase->get('sts-10')?> <b><?=$langBase->get('sts-11')?></b></td>
                    <td class="t_right"><?=View::CashFormat($player_stats['regged_today'])?></td>
                </tr>
                <tr class="c_3">
                	<td><?=$langBase->get('sts-10')?> <b><?=$langBase->get('sts-12')?></b></td>
                    <td class="t_right"><?=View::CashFormat($player_stats['regged_yesterday'])?></td>
                </tr>
                <tr class="c_2">
                	<td><?=$langBase->get('sts-10')?> <b><?=$langBase->get('sts-13')?></b></td>
                    <td class="t_right"><?=View::CashFormat($player_stats['regged_2_days'])?></td>
                </tr>
                <tr class="c_1"><td colspan="2"><b style="color: #444444;"><?=$langBase->get('sts-14')?></b></td></tr>
                <tr class="c_2">
                	<td><?=$langBase->get('sts-15')?></td>
                    <td class="t_right"><?=View::CashFormat($forum_stats['threads_num_total'])?></td>
                </tr>
                <tr class="c_3">
                	<td><?=$langBase->get('sts-16')?></td>
                    <td class="t_right"><?=View::CashFormat($forum_stats['posts_num_total'])?></td>
                </tr>
                <tr class="c_2">
                	<td><?=$langBase->get('sts-17')?></td>
                    <td class="t_right"><?=View::CashFormat($messages_stats['num_total'])?></td>
                </tr>
                <tr class="c_3">
                	<td><?=$langBase->get('sts-18')?></td>
                    <td class="t_right"><?=View::CashFormat($logevent_stats['num_total'])?></td>
                </tr>
                <?php
				if (Player::Data('level') >= 3):
				?>
                <tr class="c_1"><td colspan="2"><b style="color: #444444;"><?=$langBase->get('sts-19')?></b></td></tr>
                <tr class="c_2">
                	<td><?=$langBase->get('sts-20')?></td>
                    <td class="t_right"><?=View::CashFormat($money_stats['players_cash']+$money_stats['players_bank'])?> $<br /><span class="subtext"><?=(View::CashFormat(round(($money_stats['players_cash']+$money_stats['players_bank'])/$player_stats['num_active'])))?> $</span></td>
                </tr>
                <tr class="c_3">
                	<td><?=$langBase->get('sts-21')?></td>
                    <td class="t_right"><?=View::CashFormat($money_stats['players_points'])?> C<br /><span class="subtext"><?=(View::CashFormat(round($money_stats['players_points']/$player_stats['num_active'])))?> C</span></td>
                </tr>
                <tr class="c_2">
                	<td><?=$langBase->get('sts-22')?></td>
                    <td class="t_right"><?=View::CashFormat($money_stats['business'])?> $</td>
                </tr>
                <tr class="c_3">
                	<td><?=$langBase->get('sts-23')?></td>
                    <td class="t_right"><?=View::CashFormat($money_stats['families'])?> $</td>
                </tr>
                <?php
				endif;
				?>
            </tbody>
        </table>
        <table class="table">
            <thead>
                <tr><td colspan="4"><?=$langBase->get('sts-24')?></td></tr>
            </thead>
            <tbody>
            <?php
            $ranks = array_reverse($config['ranks'], true);
            foreach ($ranks as $key => $rank)
            {
                $i++;
                $c = $i%2 ? 2 : 3;
                
                $num = $player_stats['ranklist'][$key];
            ?>
                <tr class="c_<?=$c?>">
                    <td>#<?=$key?></td>
                    <td><?=$rank[0]?></td>
                    <td class="center"><?=View::AsPercent($num, $player_stats['ranklist_num'], 2)?> %</td>
                    <td class="t_right"><b><?=View::CashFormat($num)?></b></td>
                </tr>
            <?php
            }
            unset($i);
            ?>
         	</tbody>
         </table>
         <table class="table">
        	<thead>
            	<tr><td colspan="3"><?=$langBase->get('sts-25')?></td></tr>
            </thead>
            <tbody>
            <?php
			$sql = $db->Query("SELECT id,created,level,name,health FROM `[players]` ORDER BY id DESC LIMIT 10");
			while ($player = $db->FetchArray($sql))
			{
				$i++;
				$c = $i%2 ? 2 : 3;
			?>
            	<tr class="c_<?=$c?>">
                	<td class="center">#<?=$i?></td>
                    <td><?=View::Player($player)?></td>
                    <td class="t_right"><?=View::Time($player['created'])?></td>
                </tr>
            <?php
			}
			unset($i);
			?>
            </tbody>
        </table>
    </div>
    <div class="left" style="width: 310px; margin-left: 10px;">
        <table class="table">
        	<thead>
            	<tr><td colspan="3"><?=$langBase->get('sts-26')?></td></tr>
            </thead>
            <tbody>
            <?php
			$sql = $db->Query("SELECT id,rank,level,name,health FROM `[players]` WHERE `health`>'0' AND `level`>'0' AND `level`<'3' ORDER BY rankpoints DESC LIMIT 10");
			while ($player = $db->FetchArray($sql))
			{
				$i++;
				$c = $i%2 ? 2 : 3;
			?>
            	<tr class="c_<?=$c?>">
                	<td class="center">#<?=$i?></td>
                    <td><?=View::Player($player)?></td>
                    <td class="t_right"><?=$config['ranks'][$player['rank']][0]?></td>
                </tr>
            <?php
			}
			unset($i);
			?>
            	<tr class="c_3">
                	<td colspan="3"><?=$langBase->get('sts-27')?>: #<?=Player::Data('rank_pos')?></td>
                </tr>
            </tbody>
        </table>
        <table class="table">
        	<thead>
            	<tr><td colspan="3"><?=$langBase->get('sts-28')?></td></tr>
            </thead>
            <tbody>
            <?php
			$sql = $db->Query("SELECT id,killpoints,level,name,health FROM `[players]` WHERE `health`>'0' AND `level`>'0' AND `killpoints`>='1' AND `level`<'3' ORDER BY `killpoints` DESC LIMIT 10");
			while ($player = $db->FetchArray($sql))
			{
				$i++;
				$c = $i%2 ? 2 : 3;
			?>
            	<tr class="c_<?=$c?>">
                	<td class="center">#<?=$i?></td>
                    <td><?=View::Player($player)?></td>
                    <td class="t_right"><?=View::CashFormat($player['killpoints'])?> <span class="dark"><?=$langBase->get('sts-29')?></span></td>
                </tr>
            <?php
			}
			unset($i);
			?>
            </tbody>
        </table>
        <table class="table">
        	<thead>
            	<tr><td colspan="3"><?=$langBase->get('sts-30')?></td></tr>
            </thead>
            <tbody>
            <?php
			$sql = $db->Query("SELECT id,level,name,health,cash,bank FROM `[players]` WHERE `health`>'0' AND `level`>'0' AND `level`<'3' ORDER BY cash + bank DESC LIMIT 10");
			while ($player = $db->FetchArray($sql))
			{
				$i++;
				$c = $i%2 ? 2 : 3;
			?>
            	<tr class="c_<?=$c?>">
                	<td class="center">#<?=$i?></td>
                    <td><?=View::Player($player)?></td>
                    <td class="t_right"><?=(View::MoneyRank($player['cash']+$player['bank'], true))?></td>
                </tr>
            <?php
			}
			unset($i);
			?>
            </tbody>
        </table>
        <table class="table">
        	<thead>
            	<tr><td colspan="3"><?=$langBase->get('sts-31')?></td></tr>
            </thead>
            <tbody>
            <?php
			foreach ($player_stats['top_jail_breakouts'] as $player)
			{
				$i++;
				$c = $i%2 ? 2 : 3;
			?>
            	<tr class="c_<?=$c?>">
					<td class="center">#<?=$i?></td>
                	<td><?=View::Player(array('id' => $player[0]))?></td>
                    <td class="t_right"><?=View::CashFormat($player[1])?></td>
                </tr>
            <?php
			}
			unset($i);
			?>
            </tbody>
        </table>
        <table class="table">
        	<thead>
            	<tr><td colspan="3"><?=$langBase->get('sts-32')?></td></tr>
            </thead>
            <tbody>
            <?php
			$sql = $db->Query("SELECT id,respect FROM `[players]` WHERE `health`>'0' AND `level`>'0' AND `level`<'3' ORDER BY `respect` DESC LIMIT 10");
			while ($player = $db->FetchArray($sql))
			{
				$i++;
				$c = $i%2 ? 2 : 3;
			?>
            	<tr class="c_<?=$c?>">
                	<td class="center">#<?=$i?></td>
                    <td><?=View::Player($player)?></td>
                    <td class="t_right"><?=View::CashFormat($player['respect'])?></td>
                </tr>
            <?php
			}
			unset($i);
			?>
            </tbody>
        </table>
    </div>
    <div class="clear"></div>
</div>
<?php
if (Player::Data('level') > 2)
{
	echo '
<div class="graph_container">
	<div id="graph_money"></div>
</div>';
}
?>