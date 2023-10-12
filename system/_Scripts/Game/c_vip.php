<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<div class="bg_c w500">
<div style="width: 100%; margin-top: 15px">
<p align="center"><b><?=$langBase->get('cvip-01')?></b></p>
    <table class="table">
	<thead>
	  <tr>
		<td colspan="2"><?=$langBase->get('cvip-02')?><img src="images/game_vip.png" /></td>
	  </tr>
    </thead>
      <tr class="c_1">
        <td width="5%" align="center"><img src="images/icons/set_1/vip.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-03')?></td>
      </tr>
      <tr class="c_2">
        <td width="5%" align="center"><img src="images/icons/set_1/clock.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-04')?></td>
      </tr>
      <tr class="c_1">
        <td width="5%" align="center"><img src="images/icons/set_1/stats_bank.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-05')?></td>
      </tr>
      <tr class="c_2">
        <td width="5%" align="center"><img src="images/icons/set_1/stats_health.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-06')?></td>
      </tr>
      <tr class="c_1">
        <td width="5%" align="center"><img src="images/icons/set_1/clock.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-07')?></td>
      </tr>
      <tr class="c_2">
        <td width="5%" align="center"><img src="images/icons/set_1/airport.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-08')?></td>
      </tr>
	  <tr class="c_1">
        <td width="5%" align="center"><img src="images/icons/respect.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-10')?></td>
      </tr>
	  <tr class="c_2">
        <td width="5%" align="center"><img src="images/icons/redlight.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-11')?></td>
      </tr>
	  <tr class="c_1">
        <td width="5%" align="center"><img src="images/icons/set_1/vip.png"/></td>
        <td class="t_left"><?=$langBase->get('cvip-12')?></td>
      </tr>
    </table>
<?if(Player::Data('vip_days') <= 0){?><p><b><?=$langBase->get('cvip-09')?></b></p><?}?>
</div>
</div>