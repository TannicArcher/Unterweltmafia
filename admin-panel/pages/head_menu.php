<?
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
?>
                        <nav class="main-nav">
                            <ul>
                                <li<?=($page == '' ? ' class="active"' : '')?>><a href="index.php"><span class="glyph cloud"></span> Kontrollbereich</a></li>
                                <li<?=($page == 'users' ? ' class="active"' : '')?>>
                                    <a href="index.php?page=users"><span class="glyph user"></span> Mitglieder</a>
									<ul class="submenu">
                                        <li><a href="index.php?page=players">Spieler</a></li>
										<li><a href="index.php?page=vip_pl">VIP Spieler</a></li>
										<li><a href="index.php?page=iusers">Gesperrte Spieler</a></li>
										<li><a href="index.php?page=multi_account">Multi Accounts</a></li>
                                    </ul>
                                </li>
                                <li<?=($page == 'vouchers' ? ' class="active"' : '')?>>
									<a href="index.php?page=vouchers"><span class="glyph favorite"></span> Coins</a>
									<ul class="submenu">
                                        <li><a href="index.php?page=vouchers">Coin Gutscheine</a></li>
										<?if(Player::Data('level') > 3 || User::Data('userlevel') > 3){?><li><a href="index.php?page=packs">Coin Pakete</a></li><?}?>
										<?if(Player::Data('level') > 3 || User::Data('userlevel') > 3){?><li><a href="index.php?page=p_sales">PayPal-Verkäufe</a></li><?}?>
										<?if(Player::Data('level') > 3 || User::Data('userlevel') > 3){?><li><a href="index.php?page=s_sales">SMS-Verkäufe</a></li><?}?>
                                    </ul>
								</li>
                                <li<?=($page == 'acts' ? ' class="active"' : '')?>>
									<a href="index.php?page=acts"><span class="glyph rating"></span> Logs</a>
									<ul class="submenu">
                                        <li><a href="index.php?page=acts">Ereignisse</a></li>
                                        <?if(Player::Data('level') > 3 || User::Data('userlevel') > 3){?><li><a href="index.php?page=edits">Änderungsprotokolle</a></li><?}?>
										<li><a href="index.php?page=ads">Versteckte Anzeigen</a></li>
                                    </ul>
								</li>
								<?php 
									if($config['affiliate_module'] && Player::Data('level') > 3 || User::Data('userlevel') > 3){
								?>
								<li<?=($page == 'afiliati' ? ' class="active"' : '')?>>
									<a href="index.php?page=affiliates"><span class="glyph list"></span> Partner</a>
									<ul class="submenu">
                                        <li><a href="index.php?page=affiliates">Partner</a></li>
                                        <li><a href="index.php?page=aff_min">Warte auf Zahlung</a></li>
                                    </ul>
								</li><?}?>
								<?if(Player::Data('level') > 3 || User::Data('userlevel') > 3){?>
								<li<?=($page == 'sett' ? ' class="active"' : '')?>>
                                    <a href="index.php?page=sett"><span class="glyph archive"></span> System</a>
                                    <ul class="submenu">
                                        <li><a href="index.php?page=sett">Spieleinstellungen</a></li>
                                    </ul>
                                </li><?}?>
								<li<?=($page == 'news' ? ' class="active"' : '')?>>
									<a href="index.php?page=news"><span class="glyph list-2"></span> Verschiedenes</a>
									<ul class="submenu">
                                        <li><a href="index.php?page=news">News</a></li>
										<li><a href="index.php?page=addcomp">Firma hinzufügen</a></li>
                                    </ul>
								</li>
                                <li><a href="<?=$config['base_url']?>?logout"><span class="glyph logout"></span> Abmelden</a></li>
                            </ul>
                        </nav>
						<!-- /navigation menu -->

                        <!-- bar -->
                        <ul class="bar">
							<li class="search">
                                <div>
                                    <form>
										<input type="hidden" name="page" value="players" />
                                        <input type="submit" value="L" title="Klicke zum suchen" class="tooltip glyph" />
                                        <input type="text" placeholder="Spieler suchen" name="s" />
                                    </form>
                                </div>
                            </li>
                            <li>
                                <a href="/game/?side=rediger_profil&a=profiletext" target="_blank">
                                    <span class="glyph settings"></span>
                                </a>
                            </li>
                            <li>
                                <a href="/game/s/<?=Player::Data('name')?>" title="Profil" target="_blank" class="tooltip">
                                    <span class="glyph user"></span>
                                    <span class="text"><?=Player::Data('name')?></span>
                                </a>
                            </li>
                        </ul>