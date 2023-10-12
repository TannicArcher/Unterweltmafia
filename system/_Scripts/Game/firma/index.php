<div class="script_header">
	<img src="<?=$config['base_url']?>images/script_headers/firmen.jpg" alt="" />
<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	require_once('businessValidator.php');
	
	$firmatyper = $config['business_types'];
	
	$alle_firmaer = array();
	$sql = $db->Query("SELECT id,name,image,job_1,job_2,created,type FROM `businesses` WHERE `active`='1' ORDER BY bank DESC");
	while ($firma = $db->FetchArray($sql))
	{
		$alle_firmaer[$firma['type']][] = $firma;
	}
?>
<div class="bg_c w600">
	<h1 class="big"><?=$langBase->get('function-companies')?></h1>
    <?php
		if (count($firmatyper) <= 0)
		{
			echo '<h2 class="center">'.$langBase->get('err-06').'</h2>';
		}
		else
		{
			foreach ($firmatyper as $t_id => $firmatype)
			{
	?>
    <div class="bg_c c_1 w500">
    	<h1 class="big"><?=$firmatype['name'][1]?></h1>
        <?php
			$firmaer = $alle_firmaer[$t_id];
			
			if (count($firmaer) <= 0)
			{
				echo '<h2 class="center">'.$langBase->get('err-06').'</h2>';
			}
			else
			{
				$i = 0;
				
				$leftB = array();
				$rightB = array();
				
				foreach ($firmaer as $firma)
				{
					$i++;
					
					if ($i%2)
						$leftB[] = $firma;
					else
						$rightB[] = $firma;
				}
		?>
        <div class="left" style="width: 240px;">
        <?php
		foreach ($leftB as $firma)
		{
		?>
        <div class="bg_c c_2" style="width: 220px;">
        	<h1 class="big"><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>" style="text-decoration: none; font-variant: small-caps; font-size: 14px;"><?=$firma['name']?></a></h1>
            <?php if($firma['image']){ ?><p class="center"><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>"><span style="display: block; max-width: 210px; max-height: 100px;"><img src="<?=$firma['image']?>" alt="" class="handle_image noZoom" /></span></a></p><?php } ?>
            <dl class="dd_right">
                <dt><?=$firmatype['job_titles'][0]?></dt>
                <dd><?=View::Player(array('id' => $firma['job_1']))?></dd>
                <dt><?=$firmatype['job_titles'][1]?></dt>
                <dd><?=View::Player(array('id' => $firma['job_2']), false, 'N/A')?></dd>
            </dl>
            <p class="center clear" style="margin-top: 20px;">
            	<a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>" class="button"><?=$langBase->get('comp-02')?></a>
                <?php if(in_array(Player::Data('id'), array($firma['job_1'], $firma['job_2']))  || Player::Data('level') == 4) echo ' <a href="'.$config['base_url'].'?side=firma/panel&amp;id='.$firma['id'].'&amp;a=main" class="button">'.$langBase->get('comp-03').'</a>'; ?>
            </p>
        </div>
        <?php
		}
		?>
        </div>
        <div class="left" style="width: 240px; margin-left: 20px;">
        <?php
		foreach ($rightB as $firma)
		{
		?>
        <div class="bg_c c_2" style="width: 220px;">
        	<h1 class="big"><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>" style="text-decoration: none; font-variant: small-caps; font-size: 14px;"><?=$firma['name']?></a></h1>
            <?php if($firma['image']){ ?><p class="center"><a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>"><span style="display: block; max-width: 210px; max-height: 100px;"><img src="<?=$firma['image']?>" alt="" class="handle_image noZoom" /></span></a></p><?php } ?>
            <dl class="dd_right">
                <dt><?=$firmatype['job_titles'][0]?></dt>
                <dd><?=View::Player(array('id' => $firma['job_1']))?></dd>
                <dt><?=$firmatype['job_titles'][1]?></dt>
                <dd><?=View::Player(array('id' => $firma['job_2']), false, 'N/A')?></dd>
            </dl>
            <p class="center clear" style="margin-top: 20px;">
            	<a href="<?=$config['base_url']?>?side=firma/firma&amp;id=<?=$firma['id']?>" class="button"><?=$langBase->get('comp-02')?></a>
                <?php if(in_array(Player::Data('id'), array($firma['job_1'], $firma['job_2']))  || Player::Data('level') == 4) echo ' <a href="'.$config['base_url'].'?side=firma/panel&amp;id='.$firma['id'].'&amp;a=main" class="button">'.$langBase->get('comp-03').'</a>'; ?>
            </p>
        </div>
        <?php
		}
		?>
        </div>
        <div class="clear"></div>
        <?php
			}
		?>
    </div>
    <?php
			}
		}
	?>
</div>
</div>