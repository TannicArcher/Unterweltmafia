<?
define('BASEPATH', true);
require_once('../system/config.php');

if (!IS_ONLINE)
	{
		include('pages/login.php');
		exit;
	}elseif (Player::Data('level') < 3 || User::Data('userlevel') < 3){
	    View::Message($langBase->get('function-404'), 2, true, '/game/?side=startside');
	}else{
		$page = $_GET['page'];

		if($page == "sett"){
			include("pages/settings.php");
		}elseif($page == "update"){
			include("pages/update.php");
		}elseif($page == "users"){
			include("pages/users.php");
		}elseif($page == "iusers"){
			include("pages/iusers.php");
		}elseif($page == "players"){
			include("pages/players.php");
		}elseif($page == "multi_account"){
			include("pages/multi_account.php");
		}elseif($config['affiliate_module'] && $page == "affiliates"){
			include("pages/affiliates.php");
		}elseif($config['affiliate_module'] && $page == "aff_min"){
			include("pages/aff_min.php");
		}elseif($page == "vip_pl"){
			include("pages/vip_pl.php");
		}elseif($page == "search"){
			include("pages/search.php");
		}elseif($page == "p_sales"){
			include("pages/p_sales.php");
		}elseif($page == "s_sales"){
			include("pages/s_sales.php");
		}elseif($page == "packs"){
			include("pages/packs.php");
		}elseif($page == "vouchers"){
			include("pages/vouchers.php");
		}elseif($page == "edits"){
			include("pages/edits.php");
		}elseif($page == "acts"){
			include("pages/acts.php");
		}elseif($page == "news"){
			include("pages/news.php");
		}elseif($page == "addcomp"){
			include("pages/addcomp.php");
		}elseif($page == "ads"){
			include("pages/ads.php");
		}else{
			include("pages/dashboard.php");
		}
	}
?>