<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
<div class="startside_main">
    <div class="start_left">
    	<h1 class="title"><?=$langBase->get('function-news')?><a href="<?=$config['base_url']?>?side=changelog" class="visalt" style="margin-right: 80px; font-size: 11px; color: #999999;"><?=$langBase->get('function-changelog')?></a><a href="<?=$config['base_url']?>?side=stiri" class="visalt"><?=$langBase->get('ot-view-all')?></a></h1>
        <?php
			$sql = $db->Query("SELECT id,text,added,comments FROM `news` WHERE `deleted`='0' ORDER BY id DESC LIMIT 0,3");
			$all_news = $db->FetchArrayAll($sql);
			
			if( count($all_news) <= 0 ){
				echo '<h2 class="center">There are no news.</h2>';
				
			}else{
				
				$added = 0;
				
				foreach($all_news as $news):
					$comments = unserialize($news['comments']);
					$added++;
					if($added == 1){
						$text_size = " newest";
					}else{
						$text_size = "";
					}
					
					$text = $news['text'];
					
					if( View::Lenght($text) > 225 ){
						$text = substr($text, 0, 225)." [...]";
					}
					
					$bbText = new BBCodeParser($text, 'news_preview', true);
			?>
            <div class="news">
            	<p class="text<?=$text_size?>"><?=$bbText->result?></p>
                <p class="time"><?=View::Time($news['added'], true)?> | <?=$langBase->get('stiri-05')?>: <?=View::CashFormat(count($comments))?> | <a href="<?=$config['base_url']?>news/<?=$news['id']?>"><?=$langBase->get('ot-read')?></a></p>
                <div class="seperator"></div>
            </div>
            <?php
				endforeach;
				
			}
		?>
    </div>
    
    <div class="start_right">
    	<h1 class="title"><?=$langBase->get('ot-istoric')?><a href="<?=$config['base_url']?>?side=min_side&amp;a=player&amp;b=log" class="visalt"><?=$langBase->get('ot-view-all')?></a></h1>
        <?php
			$sql = $db->Query("SELECT data,type FROM `" . $config['sql_logdb'] . "`.`logevents` WHERE `user`='".User::Data('id')."' AND `archived`='0' ORDER BY id DESC LIMIT 15");
			$logevents = $db->FetchArrayAll($sql);
			
			if( count($logevents) <= 0 ){
				echo '<h2 class="center">'.$langBase->get('err-06').'</h2>';
				
			}else{
		?>
        	<table width="100%" cellspacing="1" cellpadding="2">
            	<tbody>
        <?php
			foreach($logevents as $event):
		?>
        	<tr>
                <td><?=View::NoImages($langBase->getLogEventText($event['type'], unserialize(base64_decode($event['data']))))?></td>
            </tr>
        <?php
			endforeach;
		?>
        		</tbody>
            </table>
        <?php
			}
		?>
    </div>
    <div class="clear"></div>
</div></div>
<div style="margin: 20px 10px 10px 10px; padding: 10px; background: #0b0b0b;border:3px solid #202020;-moz-border-radius:7px;-webkit-border-radius:7px;border-radius:7px">
	<p class="large" style="margin-top: 0;"><a href="<?=$config['base_url']?>?side=min_side&amp;a=ref&amp;b=main"><?=$langBase->get('ot-invite')?></a></p>
    <p class="center"><input type="text" class="flat" onfocus="this.select()" value="<?=$config['game_url']?>/reg/<?=User::Data('id')?>" style="min-width: 400px; width: 400px;" /></p>