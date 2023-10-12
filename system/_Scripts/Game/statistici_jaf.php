<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$brekk = $db->QueryFetchArray("SELECT id,stats FROM `brekk` WHERE `playerid`='".Player::Data('id')."'");

	if(empty($brekk['id'])){
		header('Location: /?side=jafuri');
		exit;
	}

	$brekk_stats = unserialize($brekk['stats']);
?>
<script type="text/javascript">
	<!--
	window.addEvent('domready', function()
	{
		var wrap_g = $('g_change');
		var links_g = wrap_g.getChildren('a');
		var current_g = 0;
		
		links_g.each(function(elem)
		{
			elem.addEvent('click', function()
			{
				if (elem.hasClass('prev'))
					current_g += 1;
				else
					current_g -= 1;
				
				if (current_g < 0)
					current_g = 0;
				else
					$('graph_performed_brekk').reload('/game/graphs/performed_brekk.php?player=<?=Player::Data('id')?>&time_add=' + current_g);
				
				return false;
			});
		});
	});
	-->
</script>

<div class="script_header"><img src="<?=$config['base_url']?>images/script_headers/robberies.jpg" alt="" /></div>

<div class="graph_container">
	<p class="center" id="g_change" style="margin-bottom: 5px;">
        <a href="#" class="prev">&laquo; <?=$langBase->get('ot-back')?></a>
        <a href="#" class="next" style="margin-left: 10px;"><?=$langBase->get('ot-next')?> &raquo;</a>
    </p>
	<div id="graph_performed_brekk"></div>
</div>


<div class="main">
	<h1 class="heading"><?=$langBase->get('ot-stats')?></h1>
    
    <table class="table styled_list">
    	<tbody>
        	<tr>
            	<th colspan="2"><?=$langBase->get('sts-33')?></th>
            </tr>
        	<tr>
            	<td class="title"><?=$langBase->get('sts-34')?></td>
                <td class="text"><?=View::CashFormat($brekk_stats['sucessfull']+$brekk_stats['failed'])?></td>
            </tr>
            <tr>
            	<td class="title"><?=$langBase->get('sts-35')?></td>
                <td class="text"><?=View::CashFormat($brekk_stats['sucessfull'])?></td>
            </tr>
            <tr>
            	<td class="title"><?=$langBase->get('sts-36')?></td>
                <td class="text"><?=View::CashFormat($brekk_stats['failed'])?></td>
            </tr>
            <tr>
            	<th colspan="2"><?=$langBase->get('sts-37')?></th>
            </tr>
        	<?php
				foreach( $config['places'] as $country ):
			?>
            <tr>
            	<td class="title"><?=$country[0]?></td>
                <td class="text"><?=View::CashFormat($brekk_stats['conducted_each_place'][$country[0]])?></td>
            </tr>
            <?php
				endforeach;
			?>
            <tr>
            	<th colspan="2"><?=$langBase->get('sts-38')?></th>
            </tr>
        	<tr>
            	<td class="title"><?=$langBase->get('min-07')?></td>
                <td class="text"><?=View::CashFormat($brekk_stats['cash_earned'])?> $</td>
            </tr>
            <tr>
            	<td class="title"><?=$langBase->get('sts-39')?></td>
                <?php
					$bar_with_earned = round(((Player::Data('rankpoints')+$brekk_stats['rankpoints_earned']-$config['ranks'][Player::Data('rank')][1])/($config['ranks'][Player::Data('rank')][2]-$config['ranks'][Player::Data('rank')][1]))*100, 4);
					
					$rankpercent = $bar_with_earned - $rankp;
				?>
                <td class="text"><?=$rankpercent?> %</td>
            </tr>
            <tr>
            	<td class="title"><?=$langBase->get('sts-40')?></td>
                <td class="text"><?=View::AsPercent($brekk_stats['wanted_level_earned'], $config['max_wanted-level'], 4)?> %</td>
            </tr>
        </tbody>
    </table>
    
</div>