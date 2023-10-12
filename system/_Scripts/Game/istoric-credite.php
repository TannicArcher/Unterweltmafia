<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

	$sql = $db->Query("SELECT Id,Num_Points,Timestamp FROM `paypal_points` WHERE `UserId`='".User::Data('id')."'");
	$points = $db->FetchArrayAll($sql);
	$sql = $db->Query("SELECT points,code,used FROM `p_vouchers` WHERE `player`='".Player::Data('id')."'");
	$points3 = $db->FetchArrayAll($sql);
?>
<div style="width: 620px; margin: 0px auto;">
    <div class="left" style="width: 280px;">
    	<div class="bg_c" style="width: 260px;">
            <h1 class="big"><?=$langBase->get('isc-01')?> Paypal</h1>
			<?php
            if (count($points) <= 0)
            {
                echo '<p>'.$langBase->get('err-06').'</p>';
            }
            else
            {
            ?>
			<table class="table boxHandle">
                    <thead>
                        <tr>
                            <td><?=$langBase->get('isc-03')?></td>
                            <td><?=$langBase->get('txt-27')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($points as $point)
                    {
                        $i++;
                        $c = $i%2 ? 1 : 2;
                    ?>
                        <tr class="c_<?=$c?> boxHandle">
                            <td class="center"><?=View::CashFormat($point['Num_Points'])?> <?=$langBase->get('ot-points')?></td>
                            <td class="t_right"><?=View::Time($point['Timestamp'])?></td>
                        </tr>
                    <?php
                    }
                    ?>
						<tr>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                    </tbody>
			</table><?}?>
        </div>
    </div>
    <div class="left" style="width: 320px; margin-left: 20px;">
        <div class="bg_c" style="width: 300px;">
            <h1 class="big"><?=$langBase->get('isc-02')?></h1>
            <?php
            if (count($points3) <= 0)
            {
                echo '<p>'.$langBase->get('err-06').'</p>';
            }
            else
            {
            ?>
                <input type="hidden" name="hash" value="<?php echo substr($_SESSION['MZ_getPoints_hash'], 3); ?>" />
                <table class="table boxHandle">
					<thead>
                        <tr>
                            <td><?=$langBase->get('isc-03')?></td>
							<td><?=$langBase->get('isc-04')?></td>
                            <td><?=$langBase->get('txt-27')?></td>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($points3 as $point)
                    {
                        $i++;
                        $c = $i%2 ? 1 : 2;
                    ?>
                        <tr class="c_<?=$c?> boxHandle">
                            <td class="t_left"><?=View::CashFormat($point['points'])?> <?=$langBase->get('ot-points')?></td>
                            <td class="t_left"><?=$point['code']?></td>
							<td class="t_left"><?=View::Time($point['used'])?></td>
                        </tr>
                    <?php
                    }
                    ?>
						<tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
            <?php
            }
            ?>
        </div>
	</div>
	<div class="clear"></div>
</div>