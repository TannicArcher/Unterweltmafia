<?php
if(! defined('BASEPATH') ){ exit('Unable to view file.'); }

$languages_supported = array(
	'EN' => array('EN', 'English', 'English'),
	'RO' => array('RO', 'Deutsch', 'Deutsch'),
	'FR' => array('FR', 'Francais', 'Francais')
);

$langBase_inputs = array(
	'function-news' => array(
		'EN' => 'News',
		'RO' => 'Aktuelles',
		'FR' => 'Actualités'
	),
	'function-changelog' => array(
		'EN' => 'Changelog',
		'RO' => 'Änderungen',
		'FR' => 'Modifications'
	),
	'function-startpage' => array(
		'EN' => 'Startpage',
		'RO' => 'Startseite',
		'FR' => 'Accueil'
	),
	'function-brekk' => array(
		'EN' => 'Robberies',
		'RO' => 'Raub',
		'FR' => 'Vols'
	),
	'function-brekk_stats' => array(
		'EN' => 'Robberies stats',
		'RO' => 'Raub-Statistiken',
		'FR' => 'Statistiques des vols'
	),
	'function-carTheft' => array(
		'EN' => 'Car theft',
		'RO' => 'Auto klauen',
		'FR' => 'Vol de voitures'
	),
	'function-carSales' => array(
		'EN' => 'Sale cars',
		'RO' => 'Autos verkaufen',
		'FR' => 'Ventes de Voitures'
	),
	'function-blackmail' => array(
		'EN' => 'Blackmail',
		'RO' => 'Erpressung',
		'FR' => 'Chantage'
	),
	'function-mission' => array(
		'EN' => 'Missions',
		'RO' => 'Missionen',
		'FR' => 'Missions'
	),
	'function-planned_crime' => array(
		'EN' => 'Planned crime',
		'RO' => 'Organisierter Raub',
		'FR' => 'Crime organisé'
	),
	'function-kill' => array(
		'EN' => 'Assassin',
		'RO' => 'Killer',
		'FR' => 'Assassin'
	),
	'function-killList' => array(
		'EN' => 'Murder list',
		'RO' => 'Mordliste',
		'FR' => 'Liste des assassinés'
	),
	'function-killshop' => array(
		'EN' => 'Weapons Store',
		'RO' => 'Waffenladen',
		'FR' => 'Armurerie'
	),
	'function-fighting' => array(
		'EN' => 'Fights',
		'RO' => 'Kämpfe',
		'FR' => 'Se battre'
	),
	'function-planned_crime' => array(
		'EN' => 'Planned crime',
		'RO' => 'Raub planen',
		'FR' => 'Crime organisé'
	),
	'function-jail' => array(
		'EN' => 'Jail',
		'RO' => 'Gefängnis',
		'FR' => 'Prison'
	),
	'function-bank' => array(
		'EN' => 'Bank',
		'RO' => 'Bank',
		'FR' => 'Banque'
	),
	'function-stocks' => array(
		'EN' => 'Stocks',
		'RO' => 'Börse',
		'FR' => 'Bourse'
	),
	'function-map' => array(
		'EN' => 'Map',
		'RO' => 'Karte',
		'FR' => 'Carte'
	),
	'function-property' => array(
		'EN' => 'Residence',
		'RO' => 'Haus',
		'FR' => 'Propriété'
	),
	'function-bunker' => array(
		'EN' => 'Bunker',
		'RO' => 'Bunker',
		'FR' => 'Bunker'
	),
	'function-hospital' => array(
		'EN' => 'Hospital',
		'RO' => 'Krankenhaus',
		'FR' => 'Hôpital'
	),
	'function-auction_house' => array(
		'EN' => 'Auctions',
		'RO' => 'Auktionen',
		'FR' => 'Ventes aux enchères'
	),
	'function-garage' => array(
		'EN' => 'Garage',
		'RO' => 'Garage',
		'FR' => 'Garage'
	),
	'function-companies_overview' => array(
		'EN' => 'Companies overview',
		'RO' => 'Firmenübersicht',
		'FR' => 'Vue des sociétés'
	),
	'function-company' => array(
		'EN' => 'Company',
		'RO' => 'Firma',
		'FR' => 'Société'
	),
	'function-companies' => array(
		'EN' => 'Companies',
		'RO' => 'Firmen',
		'FR' => 'Sociétés'
	),
	'function-company_panel' => array(
		'EN' => 'Company panel',
		'RO' => 'Firma verwalten',
		'FR' => 'Administration des sociétés'
	),
	'function-journalist_panel' => array(
		'EN' => 'Journalist panel',
		'RO' => 'Journalist panel',
		'FR' => 'Panneau du journaliste'
	),
	'function-newspaper' => array(
		'EN' => 'Newspaper',
		'RO' => 'Zeitung',
		'FR' => 'Journal'
	),
	'function-read_newspaper' => array(
		'EN' => 'Read newspaper',
		'RO' => 'Zeitung lesen',
		'FR' => 'Journal'
	),
	'function-number_game' => array(
		'EN' => 'Numbers game',
		'RO' => 'Zahlenspiel',
		'FR' => 'Numéro de jeu'
	),
	'function-blackjack' => array(
		'EN' => 'Blackjack',
		'RO' => 'Blackjack',
		'FR' => 'Blackjack'
	),
	'function-coinroll' => array(
		'EN' => 'Coinroll',
		'RO' => 'Kopf oder Zahl',
		'FR' => 'Coinroll'
	),
	'function-guess' => array(
		'EN' => 'Guess the number',
		'RO' => 'Zahlenraten',
		'FR' => 'Devinez le nombre'
	),
	'function-ruleta' => array(
		'EN' => 'Roulette',
		'RO' => 'Roulette',
		'FR' => 'Roulette'
	),
	'function-rtnr' => array(
		'EN' => 'Wheel of Fortune',
		'RO' => 'Glücksrad',
		'FR' => 'Roue de la fortune'
	),
	'function-horserace' => array(
		'EN' => 'Horserace',
		'RO' => 'Pferderennen',
		'FR' => 'Course de chevaux'
	),
	'function-mare_mic' => array(
		'EN' => 'Higher or Lower',
		'RO' => 'Mehr oder weniger',
		'FR' => 'Plus haut, plus bas'
	),
	'function-bannere' => array(
		'EN' => 'Promote',
		'RO' => 'Werben',
		'FR' => 'Promouvoir'
	),
	'function-concurs' => array(
		'EN' => 'Contest',
		'RO' => 'Wettbewerb',
		'FR' => 'Concours de recrutement'
	),
	'function-lottery' => array(
		'EN' => 'Lottery',
		'RO' => 'Lotterie',
		'FR' => 'Loterie'
	),
	'function-streetrace' => array(
		'EN' => 'Streetrace',
		'RO' => 'Straßenrennen',
		'FR' => 'Course de rue'
	),
	'function-pointshop' => array(
		'EN' => 'Coinshop',
		'RO' => 'Coins verwalten',
		'FR' => 'Acheter des crédits'
	),
	'function-istcrd' => array(
		'EN' => 'Bought Coins',
		'RO' => 'Erhaltene Coins',
		'FR' => 'Crédits achetés'
	),
	'function-transfer_points' => array(
		'EN' => 'Transfer coins',
		'RO' => 'Coins tranferieren',
		'FR' => 'Transfert de Crédits'
	),
	'function-marketplace' => array(
		'EN' => 'Marketplace',
		'RO' => 'Marktplatz',
		'FR' => 'Place de Marché'
	),
	'function-forum_reports' => array(
		'EN' => 'Forum reports',
		'RO' => 'Forum Berichte',
		'FR' => 'Rapport du Forum'
	),
	'function-forum_ban_player' => array(
		'EN' => 'Ban a player from the forums',
		'RO' => 'Benutzer im Forum blockieren',
		'FR' => 'Bannir un joueur du Forum'
	),
	'function-forum_banned_players' => array(
		'EN' => 'Banned players from the forums',
		'RO' => 'Gesperrte Spieler aus den Foren',
		'FR' => 'Joueurs bannis du Forum'
	),
	'function-forum_overview' => array(
		'EN' => 'Forum overview',
		'RO' => 'Forum Übersicht',
		'FR' => 'Vue générale Forum'
	),
	'function-forum_topic' => array(
		'EN' => 'Forum topic',
		'RO' => 'Forum-Thema',
		'FR' => 'Sujet du Forum'
	),
	'function-forum_game' => array(
		'EN' => 'Game',
		'RO' => 'Spiel',
		'FR' => 'Forum du jeu'
	),
	'function-forum_sales' => array(
		'EN' => 'Sales',
		'RO' => 'Verkaufen',
		'FR' => 'Forum des ventes'
	),
	'function-forum_buy' => array(
		'EN' => 'Shopping',
		'RO' => 'Kaufen',
		'FR' => 'Forum des achats'
	),
	'function-forum_application' => array(
		'EN' => 'Requests',
		'RO' => 'Fragen',
		'FR' => 'Demandes du Forum'
	),
	'function-forum_offtopic' => array(
		'EN' => 'Off-Topic',
		'RO' => 'Off-Topic',
		'FR' => 'Sujet fermé du Forum'
	),
	'function-forum_supporter' => array(
		'EN' => 'Support',
		'RO' => 'Hilfe',
		'FR' => 'Aide'
	),
	'function-family' => array(
		'EN' => 'Family',
		'RO' => 'Familie',
		'FR' => 'Famille'
	),
	'function-family_overview' => array(
		'EN' => 'Families overview',
		'RO' => 'Familienübersicht',
		'FR' => 'Vue générale des famille'
	),
	'function-family_create' => array(
		'EN' => 'Create family',
		'RO' => 'Familie gründen',
		'FR' => 'Créer une famille'
	),
	'function-family_ownersPanel' => array(
		'EN' => 'Owners panel',
		'RO' => 'Besitzer-Panel',
		'FR' => 'Tableau de bord propriétaire'
	),
	'function-family_membersPanel' => array(
		'EN' => 'Members panel',
		'RO' => 'Mitglieder-Panel',
		'FR' => 'Liste des membres'
	),
	'function-family_businesses' => array(
		'EN' => 'Family businesses',
		'RO' => 'Familienunternehmen',
		'FR' => 'Entreprises et Sociétés familliales'
	),
	'function-edit_profile' => array(
		'EN' => 'Edit profile',
		'RO' => 'Profil bearbeiten',
		'FR' => 'Modifier profil'
	),
	'function-headquarter' => array(
		'EN' => 'Headquarter',
		'RO' => 'Hauptquartier',
		'FR' => 'Quartier général'
	),
	'function-contacts' => array(
		'EN' => 'Contacts',
		'RO' => 'Kontakt',
		'FR' => 'Contacts'
	),
	'function-applications' => array(
		'EN' => 'Applications',
		'RO' => 'Bewerbungen',
		'FR' => 'Demandes'
	),
	'function-logs' => array(
		'EN' => 'Action logs',
		'RO' => 'Ereignisse',
		'FR' => 'Journaux des actions'
	),
	'function-messages' => array(
		'EN' => 'Messages',
		'RO' => 'Postfach',
		'FR' => 'Messages'
	),
	'function-faq' => array(
		'EN' => 'FAQ',
		'RO' => 'Anleitung',
		'FR' => 'FAQ'
	),
	'function-support' => array(
		'EN' => 'Support',
		'RO' => 'Support',
		'FR' => 'Support'
	),
	'function-statistics' => array(
		'EN' => 'Statistics',
		'RO' => 'Statistiken',
		'FR' => 'Statistiques'
	),
	'function-find_player' => array(
		'EN' => 'Search players',
		'RO' => 'Spieler suchen',
		'FR' => 'Trouver un joueur'
	),
	'function-members_list' => array(
		'EN' => 'Members list',
		'RO' => 'Mitgliederliste',
		'FR' => 'Liste des membre'
	),
	'function-crew' => array(
		'EN' => 'Crew',
		'RO' => 'Team',
		'FR' => 'Equipement'
	),
	'function-online_list' => array(
		'EN' => 'Online list',
		'RO' => 'Spieler online',
		'FR' => 'Membres en ligne'
	),
	'function-logout' => array(
		'EN' => 'Log out',
		'RO' => 'Abmelden',
		'FR' => 'Déconnexion'
	),
	'function-playerprofile' => array(
		'EN' => 'Player profile',
		'RO' => 'Spielerprofil',
		'FR' => 'Profil Joueur'
	),
	'function-p_rec' => array(
		'EN' => 'Invite Users',
		'RO' => 'Spieler einladen',
		'FR' => 'Invitations'
	),
	'function-sparge_seif' => array(
		'EN' => 'Crack the Safe',
		'RO' => 'Knacke den Safe',
		'FR' => 'Ouvrir le coffre-fort'
	),
	'function-slots' => array(
		'EN' => 'Slots',
		'RO' => 'Spielutomat',
		'FR' => 'Machines à sous'
	),
	'function-404' => array(
		'EN' => 'Page not found',
		'RO' => 'Seite nicht gefunden',
		'FR' => 'Page non trouvée'
	),
	'function-error' => array(
		'EN' => 'Page cannot be loaded',
		'RO' => 'Seite kann nicht geladen werden',
		'FR' => 'La page ne peut pas être chargée'
	),
	'function-limited_access' => array(
		'EN' => 'Limited Access!',
		'RO' => 'Eingeschränkter Zugang!',
		'FR' => 'Accès limité'
	),
	'function-in_jail' => array(
		'EN' => 'You are in jail',
		'RO' => 'Du bist im Knast',
		'FR' => 'Vous êtes en prison'
	),
	'function-c_name' => array(
		'EN' => 'Change your name',
		'RO' => 'Namen ändern',
		'FR' => 'Modifier Nom'
	),
	'function-c_vip' => array(
		'EN' => 'VIP',
		'RO' => 'VIP-Konto',
		'FR' => 'VIP'
	),
	'function-hall_fame' => array(
		'EN' => 'Hall of Fame',
		'RO' => 'Ruhmeshalle',
		'FR' => 'Salle des célébrités'
	),
	'function-runda_curenta' => array(
		'EN' => 'Active Round',
		'RO' => 'Aktuelle Runde',
		'FR' => 'Round actuel'
	),
	'function-lozuri' => array(
		'EN' => 'Scratch Tickets',
		'RO' => 'Rubbellose',
		'FR' => 'Tickets à gratter'
	),
	'function-bordel' => array(
		'EN' => 'Brothel',
		'RO' => 'Bordell',
		'FR' => 'Maison de passe'
	),
	
	// Firma (config)
	'c_firma-01' => array(
		'EN' => 'Bank',
		'RO' => 'Bank',
		'FR' => 'Banque'
	),
	'c_firma-02' => array(
		'EN' => 'Banks',
		'RO' => 'Banken',
		'FR' => 'Banques'
	),
	'c_firma-03' => array(
		'EN' => 'Banking company',
		'RO' => 'Bankunternehmen',
		'FR' => 'Société banquaire'
	),
	'c_firma-04' => array(
		'EN' => 'Banking companies',
		'RO' => 'Bankunternehmen',
		'FR' => 'Sociétés banquaires'
	),
	'c_firma-05' => array(
		'EN' => 'Director',
		'RO' => 'Direktor',
		'FR' => 'Directeur'
	),
	'c_firma-06' => array(
		'EN' => 'Deputy',
		'RO' => 'Stellvertreter',
		'FR' => 'Député'
	),
	'c_firma-07' => array(
		'EN' => 'No fee',
		'RO' => 'Keine Provision',
		'FR' => 'Sans commissions'
	),
	'c_firma-08' => array(
		'EN' => 'Fixed fees',
		'RO' => 'Feste Provision',
		'FR' => 'Comisions fixes'
	),
	'c_firma-09' => array(
		'EN' => 'Fees by the amount',
		'RO' => 'Provision nach Betrag',
		'FR' => 'Comission suivant montant'
	),
	'c_firma-10' => array(
		'EN' => 'Newspaper',
		'RO' => 'Zeitung',
		'FR' => 'Journal'
	),
	'c_firma-11' => array(
		'EN' => 'Newspapers',
		'RO' => 'Zeitungen',
		'FR' => 'Journaux'
	),
	'c_firma-12' => array(
		'EN' => 'Newspapers company',
		'RO' => 'Zeitungsverleger',
		'FR' => 'Maison de presse'
	),
	'c_firma-13' => array(
		'EN' => 'Newspapers companies',
		'RO' => 'Zeitungsverleger',
		'FR' => 'Journaux des sociétés'
	),
	'c_firma-14' => array(
		'EN' => 'Bullet',
		'RO' => 'Munition',
		'FR' => 'Cartouche'
	),
	'c_firma-15' => array(
		'EN' => 'Bullets',
		'RO' => 'Munitionen',
		'FR' => 'Cartouches'
	),
	'c_firma-16' => array(
		'EN' => 'Bullets factory',
		'RO' => 'Munitionsfabrik',
		'FR' => 'Usine de cartouches'
	),
	'c_firma-17' => array(
		'EN' => 'Bullets factories',
		'RO' => 'Munitionsfabriken',
		'FR' => 'Usines de cartouches'
	),
	'c_firma-18' => array(
		'EN' => 'Airport',
		'RO' => 'Flughafen',
		'FR' => 'Aéroport'
	),
	'c_firma-19' => array(
		'EN' => 'Hospital',
		'RO' => 'Krankenhaus',
		'FR' => 'Hôpital'
	),
	'c_firma-20' => array(
		'EN' => 'Lottery',
		'RO' => 'Lotterie',
		'FR' => 'Loterie'
	),
	'c_firma-21' => array(
		'EN' => 'Horserace',
		'RO' => 'Pferderennen',
		'FR' => 'Course de chevaux'
	),
	
	// Familie (config)
	'c_fam-01' => array(
		'EN' => 'Small family',
		'RO' => 'Kleine Familie',
		'FR' => 'Petite famille'
	),
	'c_fam-02' => array(
		'EN' => 'Average family',
		'RO' => 'Durchschnittsfamilie',
		'FR' => 'Famille moyenne'
	),
	'c_fam-03' => array(
		'EN' => 'Normal family',
		'RO' => 'Normale Familie',
		'FR' => 'Famille normale'
	),
	'c_fam-04' => array(
		'EN' => 'Big family',
		'RO' => 'Große Familie',
		'FR' => 'Grande famille'
	),
	
	// Weapons (config)
	'c_weapon-01' => array(
		'EN' => 'Pepper Spray',
		'RO' => 'Pfefferspray',
		'FR' => 'Jet protecteur'
	),
	'c_weapon-02' => array(
		'EN' => 'Guard Dog',
		'RO' => 'Wachhund',
		'FR' => 'Chien de garde'
	),
	'c_weapon-03' => array(
		'EN' => 'Bulletproof',
		'RO' => 'Kugelsichere Weste',
		'FR' => 'Gilet parre-balle'
	),
	'c_weapon-04' => array(
		'EN' => 'Bodyguard',
		'RO' => 'Leibwächter',
		'FR' => 'Garde du corps'
	),
	'c_weapon-05' => array(
		'EN' => 'Security Company',
		'RO' => 'Sicherheitsunternehmen',
		'FR' => 'Entreprise de sécurité'
	),
	
	// Organisierter Raub (config)
	'c_orgj-01' => array(
		'EN' => 'Leader',
		'RO' => 'Anführer',
		'FR' => 'BOSS'
	),
	'c_orgj-02' => array(
		'EN' => 'Gateway driver',
		'RO' => 'Fluchtfahrer',
		'FR' => 'Chaufeur'
	),
	'c_orgj-03' => array(
		'EN' => 'Access driver',
		'RO' => 'Durchbruchfahrer',
		'FR' => 'Accès pilote'
	),
	'c_orgj-04' => array(
		'EN' => 'Gunman',
		'RO' => 'Schütze',
		'FR' => 'Tireur'
	),
	'c_orgj-05' => array(
		'EN' => 'Economic Bank Robbery',
		'RO' => 'Überfall der Wirtschaftsbank',
		'FR' => 'Voler la banque locale'
	),
	'c_orgj-06' => array(
		'EN' => 'National Bank Robbery',
		'RO' => 'Überfall der Nationalbank',
		'FR' => 'Voler la banque nationale'
	),
	'c_orgj-07' => array(
		'EN' => 'International Bank Robbery',
		'RO' => 'Überfall der Internationalbank',
		'FR' => 'Braquer la banque internationale'
	),
	'c_orgj-08' => array(
		'EN' => 'Kidnap the president',
		'RO' => 'Entführen des Präsidenten',
		'FR' => 'Kidnapper le président'
	),
	'c_orgj-09' => array(
		'EN' => 'Rob a pawnshop',
		'RO' => 'Überfall eines Pfandhauses',
		'FR' => 'Voler un prêteur sur gages'
	),
	'c_orgj-10' => array(
		'EN' => 'Place a bomb at the airport',
		'RO' => 'Bombe am Flughafen platzieren',
		'FR' => 'Placez une bombe à l\'aéroport'
	),
	
	// Autos stehlen (config)
	'c_fmsn-01' => array(
		'EN' => 'Steal a car from the shop',
		'RO' => 'Klaue ein Auto aus einem Laden',
		'FR' => 'Voler une voiture chez le concessionnaire'
	),
	'c_fmsn-02' => array(
		'EN' => 'Steal keys to a businessman',
		'RO' => 'Klaue die Autoschlüssel eines Geschäftsmannes',
		'FR' => 'Voler le PASS d\'un homme d\'affaire'
	),
	'c_fmsn-03' => array(
		'EN' => 'Steal a parked car',
		'RO' => 'Klaue ein geparktes Auto',
		'FR' => 'Voler une voiture sur un parking'
	),
	'c_fmsn-04' => array(
		'EN' => 'Steal an Bugatti Veyron',
		'RO' => 'Klaue einen Bugatti Veyron',
		'FR' => 'Voler une Bugatti Veyron'
	),
	'c_fmsn-05' => array(
		'EN' => 'Steal Ferrari from the Showroom',
		'RO' => 'Klaue einen Ferrari aus einem Austellungsraum',
		'FR' => 'Voler une ferrari au salon de l\'auto'
	),
	'c_fmsn-06' => array(
		'EN' => 'Steal Lamborghini from the Showroom',
		'RO' => 'Klaue einen Lamborghini aus einem Austellungsraum',
		'FR' => 'Voler une Lamborghini au salon de l\'auto'
	),
	'c_fmsn-07' => array(
		'EN' => 'Steal a car at an exhibition',
		'RO' => 'Klaue ein Auto bei einer Autoschau',
		'FR' => 'Voler une voiture lors d\'une exposition'
	),
	'c_fmsn-08' => array(
		'EN' => 'Steal a car from a dealership',
		'RO' => 'Klaue ein Auto von einem Händler',
		'FR' => 'Voler une voiture chez un concessionnaire'
	),
	
	// Missionen (config)
	'c_miss-01' => array(
		'EN' => 'Steal 5 cars in 30 minutes',
		'RO' => 'Klaue 5 Autos in 30 Minuten',
		'FR' => 'Voler 5 voitures en 30 minute'
	),
	'c_miss-02' => array(
		'EN' => 'Do with success, 8 robberies in 30 minutes',
		'RO' => 'Begehe 8 erfolgreiche Raubüberfälle in 30 Minuten',
		'FR' => 'Réalise avec succès 8 vols en 30 minutes'
	),
	'c_miss-03' => array(
		'EN' => 'Earn $100.000 from the robberies, in 1 hour',
		'RO' => 'Erbeute 100.000$ mit Raubüberfällen in 1 Stunde',
		'FR' => 'Vole 100.000$ en 1 heure'
	),
	'c_miss-04' => array(
		'EN' => 'Earn $200.000 from blackmail, in 1 hour',
		'RO' => 'Erbeute 200.000$ in 1 Stunde durch Erpressung',
		'FR' => 'Gagnez 200.000$ de chantage en 1 heure'
	),
	'c_miss-05' => array(
		'EN' => 'Help 20 players to get out of jail in 30 minutes',
		'RO' => 'Befreie 20 Spieler, in 30 Minuten aus dem Gefängnis',
		'FR' => 'Aide 20 gangsters à sortir de prison en 30 minutes'
	),
	'c_miss-06' => array(
		'EN' => 'Collect -PROGRES- % rank progress in 30 minutes',
		'RO' => 'Sammle -PROGRES- % Rangfortschritt in 30 Minuten',
		'FR' => 'Augmente de -PROGRES- % ton rang en 30 minute'
	),
	'c_miss-07' => array(
		'EN' => 'Win 5 times at "Open the Safe" in 30 minutes',
		'RO' => 'Gewinnen 5 Mal bei "Knacke den Safe" in 30 Minuten',
		'FR' => 'Gagnez 5 fois à "Ouvrir le coffre-fort" en 30 minutes'
	),
	
	// Antibot
	'antibot-01' => array(
		'EN' => 'a Car',
		'RO' => 'ein Auto',
		'FR' => 'Une auto'
	),
	'antibot-02' => array(
		'EN' => 'an Airplane',
		'RO' => 'ein Flugzeug',
		'FR' => 'un Avion'
	),
	'antibot-03' => array(
		'EN' => 'a Pen',
		'RO' => 'einen Stift',
		'FR' => 'un Stylo'
	),
	'antibot-04' => array(
		'EN' => 'an Clock',
		'RO' => 'eine Uhr',
		'FR' => 'une horloge'
	),
	'antibot-05' => array(
		'EN' => 'a Train',
		'RO' => 'einen Zug',
		'FR' => 'un Train'
	),
	'antibot-06' => array(
		'EN' => 'an PC',
		'RO' => 'einen PC',
		'FR' => 'un PC'
	),
	'antibot-07' => array(
		'EN' => 'a House',
		'RO' => 'ein Haus',
		'FR' => 'une Maison'
	),
	'antibot-08' => array(
		'EN' => 'an TV',
		'RO' => 'ein TV',
		'FR' => 'une TV'
	),
	'antibot-09' => array(
		'EN' => 'a Keyboard',
		'RO' => 'eine Tastatur',
		'FR' => 'un Clavier'
	),
	'antibot-10' => array(
		'EN' => 'Loading...',
		'RO' => 'Laden...',
		'FR' => 'Chargement'
	),
	'antibot-11' => array(
		'EN' => 'An error occured!',
		'RO' => 'Es ist ein Fehler aufgetreten!',
		'FR' => 'Erreur'
	),
	'antibot-12' => array(
		'EN' => 'Click on the image that contains',
		'RO' => 'Klicke auf das Bild mit',
		'FR' => 'Clickez sur l\'image contenant'
	),
	'antibot-13' => array(
		'EN' => 'Checking results...',
		'RO' => 'Es wird überprüft ...',
		'FR' => 'Analyse les résultats...'
	),
	'antibot-14' => array(
		'EN' => 'An error occurred in data analysis!',
		'RO' => 'Ups.. da war wohl etwas falsch!',
		'FR' => 'Une erreur est survenue dans l\'analyse'
	),
	
	/*
	 * Sprache wechseln
	*/
	'msgContent-langChange' => array(
		'EN' => 'You have changed your language to -NEWLANG-.',
		'RO' => 'Du hast die Sprache in -NEWLANG- geändert.',
		'FR' => 'Vous avez changé la langue en -NEWLANG-.'
	),
	'msgContent-error' => array(
		'EN' => 'Unable to perform action.',
		'RO' => 'Aktion kann nicht ausgeführt werden.',
		'FR' => 'Impossible d\éffectuer cette action.'
	),
	'msgContent-bad_inputs' => array(
		'EN' => 'Error: Bad inputs.',
		'RO' => 'Fehler: Falsche Eingabe.',
		'FR' => 'Erreur: Saisi incorecte.'
	),
	
	/*
	 * Ereignisse
	*/
	'logevents' => array(
		1 => array(
			'EN' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />The family you were a member of, <b>-FAMILY_NAME-</b>, has vanished due to the lack of leadership.',
			'RO' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Deine Familie<b> - FAMILY_NAME - </b>wurde aufgelöst, weil sie keinen Anführer mehr hatte.',
			'FR' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Votre Famille<b> -FAMILY_NAME- </b>, à disparu puisque vous n\'avez plus de dirigeant.'
		),
		2 => array(
			'EN' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />The family you were a member of, <b>-FAMILY_NAME-</b>, has vanished due to the lack of strength.',
			'RO' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Deine Familie<b> -FAMILY_NAME - </b>ist aufgrund mangelnder Stärke aufgelöst worden.',
			'FR' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Votre famille, <b>-FAMILY_NAME-</b>, à disparu, faute de puissance.'
		),
		3 => array(
			'EN' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />The boss of the family <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a> has died, therefor you are now the boss of the family.',
			'RO' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Der Anführer der Familie <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a> ist verstorben und du wurdest zum neuen Anführer ernannt.',
			'FR' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Le Boss de la famille <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a> est mort, vous devenez le dirigeant de cette famille.'
		),
		4 => array(
			'EN' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />You have been invited to join the family <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>',
			'RO' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Du wurdest eingeladen der Familie <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a> beizutreten.',
			'FR' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Vous êtes invité a rejoindre la famille <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.'
		),
		5 => array(
			'EN' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />You are now no longer a member of the family <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.',
			'RO' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Du gehörst der Famlie <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a> nicht mehr an.',
			'FR' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Vous nêtes plus membre de la famille <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.'
		),
		6 => array(
			'EN' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />The family <b>-FAMILY_NAME-</b> has been closed down.',
			'RO' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Die Familie <b>-FAMILY_NAME-</b> wurde aufgelöst.',
			'FR' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />La famille <b>-FAMILY_NAME-</b> n\'existe plus.'
		),
		7 => array(
			'EN' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />You are now a member of the family <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.',
			'RO' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Glückwunsch! Ab sofort gehörst du der Familie <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a> an.',
			'FR' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Vous êtes maintenant membre de la famille <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.'
		),
		8 => array(
			'EN' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />You have left the family <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.',
			'RO' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Du hast die Familie <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a> verlassen.',
			'FR' => '<img src="-FAMILY_IMG-" alt="" class="startImage medium_3" />Vous avez quitté la famille <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.'
		),
		9 => array(
			'EN' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />You are now in the administration of the company <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'RO' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Du bist jetzt Teil der Unternehmensleitung von <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'FR' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Vous faites parti de l\'administration de cette compagnie <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.'
		),
		10 => array(
			'EN' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Your company, <b>-COMPANY_NAME-</b>, has gone bankrupt.',
			'RO' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Deine Firma <b>-COMPANY_NAME-</b> ist pleite gegangen.',
			'FR' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Votre compagnie, <b>-COMPANY_NAME-</b>, est en faillite.'
		),
		11 => array(
			'EN' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />You are now a journalist in the newspapercompany <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'RO' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Du bist jetzt Journalist in beim Verleger von <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'FR' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Vous êtes maintenant journaliste dans cette maison de presse <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.'
		),
		12 => array(
			'EN' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />You are now a customer of the bank <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'RO' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Du bist jetzt Kunde der <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a> Bank.',
			'FR' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Vous êtes maintenant client de cette banque <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.'
		),
		13 => array(
			'EN' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />The bank <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a> rejected your client-application.',
			'RO' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Die <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a> Bank hat deinen Kundenantrag abgelehnt.',
			'FR' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />La banque <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a> à refusé que vous soyez client.'
		),
		14 => array(
			'EN' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />You are no longer a journalist in the newspapercompany <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'RO' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Du bist nicht länger Journalist beim Verleger von <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'FR' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Vous n\'êtes plus journaliste pour cette maison de presse <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.'
		),
		15 => array(
			'EN' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />You have been invited to become a journalist of the newspaperbusiness <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'RO' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Du wurdest eingeladen, Journalist des Verlegers von <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a> zu werden.',
			'FR' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Vous avez été invité à devenir journaliste pour cette maison de presse <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.'
		),
		16 => array(
			'EN' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />You have been kicked from the company <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'RO' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Du wurdest von der Firma <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a> gefeuert.',
			'FR' => '<img src="-COMPANY_IMG-" alt="" class="startImage medium_3" />Vous êtes viré de cette compagnie <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.'
		),
		17 => array(
			'EN' => 'Your company, <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>, has been auctioned off. You got the money that was in the companybank.',
			'RO' => 'Deine Firma <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a> wurde bei einer Auktion versteigert. Du hast das Geld vom Firmenkonto erhalten.',
			'FR' => 'Votre compagnie <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>, à été vendue aux enchères, vous récupérez tout l\'argent se trouvant dans la banque.'
		),
		18 => array(
			'EN' => 'You have received a bank transfer from @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>. You received -TO_ME- $, and the bank received -TO_BANK- $.',
			'RO' => 'Du hast eine Überweisung von @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> erhalten. Du erhälst -TO_ME- $, deine Bank erhält -TO_BANK- $.',
			'FR' => 'Vous avez reçu un trensfert banquaire de @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>. Vous avez reçu -TO_ME- $, Frais banquaires: -TO_BANK- $.'
		),
		19 => array(
			'EN' => 'You vitnessed @<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> murdering @<a href="/game/s/-VICTIM_NAME-" class="global_playerlink">-VICTIM_NAME-</a>.',
			'RO' => 'Du warst Zeuge, wie @<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> kaltblütig @<a href="/game/s/-VICTIM_NAME-" class="global_playerlink">-VICTIM_NAME-</a> umlegte.',
			'FR' => 'Vous êtes témoin, @<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> a assassiné @<a href="/game/s/-VICTIM_NAME-" class="global_playerlink">-VICTIM_NAME-</a>.'
		),
		20 => array(
			'EN' => 'You vitnessed @<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> trying to murder @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-VICTIM_NAME-</a>.',
			'RO' => 'Du warst Zeuge, wie @<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> versuchte @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-VICTIM_NAME-</a> umzulegen.',
			'FR' => 'Vous êtes témoin que @<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> a tenté d\'assassiner @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-VICTIM_NAME-</a>.'
		),
		21 => array(
			'EN' => 'Somebody has killed you.',
			'RO' => 'Jemand hat dich umgelegt.',
			'FR' => 'Quelcun vous à assassiné.'
		),
		22 => array(
			'EN' => 'The marijuana production has ended. You got <b>-MARIJUANA- grams</b> of marijuana.',
			'RO' => 'Die Marihuana-Produktion wurde abgeschlossen. Du hast <b>-MARIJUANA- Gramm</b> Marihuana.',
			'FR' => 'Votre production de marijuana est terminée. Vous gagnez <b>-MARIJUANA- grame</b> de marijuana.'
		),
		23 => array(
			'EN' => 'You have sold -MARIJUANA- grams of marijuana. You got <b>-MONEY- $</b>.',
			'RO' => 'Du hast -MARIJUANA- Gramm Marihuana verkauft und dafür <b>-MONEY- $</b> erhalten.',
			'FR' => 'Vous avez vendu -MARIJUANA- grammes de marijuana, vous gagnez <b>-MONEY- $</b>.'
		),
		24 => array(
			'EN' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> broke you out of jail. As a reward, you gave him/her <b>-REWARD- $</b>.',
			'RO' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> hat die geholfen aus dem Gefängnis auszubrechen. Als Dank hast du ihm/ihr <b>-REWARD- $</b> gegeben.',
			'FR' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> vous a aidé à votre évasion de prison, vous lui donnez une récompense: <b>-REWARD- $</b>.'
		),
		25 => array(
			'EN' => 'You won a streetrace! You got <b>-MONEY- $</b> and some rank.',
			'RO' => 'Super! Du hast ein Straßenrennen gewonnen! Du erhälst <b>-MONEY- $</b> und einige Rangpunkte.',
			'FR' => 'Vous gagnez une course! Vous gagnez <b>-MONEY- $</b> et vous progréssez en Rang.'
		),
		26 => array(
			'EN' => 'You came in <b>-PLACE-st place</b> in the lottery, you won <b>-MONEY- $</b>.',
			'RO' => 'Du hast den Rang <b>-PLACE-</b> in der Lotterie gewonnen. Du bekommst <b>-MONEY- $</b>.',
			'FR' => 'Vous êtes ici <b>-PLACE-</b> à la loterie, vous gagnez <b>-MONEY- $</b>.'
		),
		27 => array(
			'EN' => 'You got <b>-MONEY- $</b> from a planned crime you joined.',
			'RO' => 'Du hast <b>-MONEY- $</b> bei einem organisierten Verbrechen erbeutet.',
			'FR' => 'Vous gagnez <b>-MONEY- $</b> pour votre participation au crime organisé.'
		),
		28 => array(
			'EN' => '<a href="/game/?side=support&amp;id=-TICKET_ID-">Your supportticket</a> has been ended.',
			'RO' => '<a href="/game/?side=support&amp;id=-TICKET_ID-">Dein Support-Ticket</a> wurde geschlossen.',
			'FR' => '<a href="/game/?side=support&amp;id=-TICKET_ID-">Votre ticket de support</a> est fermé.'
		),
		29 => array(
			'EN' => '<img src="-PLAYER_IMG-" alt="" class="startImage medium_3" />@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> blackmailed you for <b>-MONEY- $</b>.',
			'RO' => '<img src="-PLAYER_IMG-" alt="" class="startImage medium_3" />@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> hat dich um <b>-MONEY- $</b> erpresst.',
			'FR' => '<img src="-PLAYER_IMG-" alt="" class="startImage medium_3" />@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> vous fait chanter pour <b>-MONEY- $</b>.'
		),
		30 => array(
			'EN' => 'The player @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>, who you have enlisted, has ranked to -RANK-, so you received <b>-POINTS- coins</b>!',
			'RO' => 'Jucatorul @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>, den du eingeladen hattest, hat nun Rang -RANK- erreicht, dafür erhälst du <b>-POINTS- Coins</b>!',
			'FR' => 'Le joueur @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>, que vous avez parrainé, a atteint le Rang -RANK-, vous gagnez <b>-POINTS- credits</b>!'
		),
		31 => array(
			'EN' => 'Your rankboost-session has ended. You got <b>-RANK- %</b> extra rank.',
			'RO' => 'Deine Rang-Boost Session wurde beendet. Du bekommst <b>-RANK- %</b> Extra-Rang.',
			'FR' => 'La session de Boost du Rang est terminée. Vous gagnez <b>-RANK- %</b> de progression de rang.'
		),
		32 => array(
			'EN' => 'Yoy\'ve won an auction! You are now the owner of the company <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'RO' => 'Du hast eine Auktion gewonnen. Du bist nun stolzer Besitzer der Firma <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.',
			'FR' => 'Vous remportez la vente aux enchères, vous devenez propriétaire de la compagnie <a href="/game/?side=firma/firma&amp;id=-COMPANY_ID-">-COMPANY_NAME-</a>.'
		),
		33 => array(
			'EN' => 'You\'ve been promoted! You now have the rank <b>-NEW_RANK-</b>. You got -BULLETS- bullets.',
			'RO' => 'Glückwunsch zu deiner Beförderung! Du hast nun den Rang <b>-NEW_RANK-</b>. Du erhälst -BULLETS- Munition.',
			'FR' => 'PROMOTION! Vous avez maintenant le Rang <b>-NEW_RANK-</b>. Vous gagnez -BULLETS- Cartouches.'
		),
		34 => array(
			'EN' => 'You\'ve been degraded! You now have the rank <b>-NEW_RANK-</b>.',
			'RO' => '"Du wurdest degradiert! Du hast nun den Rang <b>-NEW_RANK-</b>.',
			'FR' => 'Vous êtes rétrogradé au rang <b>-NEW_RANK-</b>.'
		),
		35 => array(
			'EN' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> has been promoted! He/She now has the rank <b>-NEW_RANK-</b>.',
			'RO' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> ist aufgestiegen! Und hat nun den Rang <b>-NEW_RANK-</b>.',
			'FR' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> a reçu une promotion! Son rang est maintenant: <b>-NEW_RANK-</b>.'
		),
		36 => array(
			'EN' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> has been degraded! He/She now has the rank <b>-NEW_RANK-</b>.',
			'RO' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> wurde degradiert! und hat nun den Rang <b>-NEW_RANK-</b>.',
			'FR' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> a été dégradé, son rang est maintenant: <b>-NEW_RANK-</b>.'
		),
		37 => array(
			'EN' => 'Yoy\'ve won an auction! You are now the boss of the family <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.',
			'RO' => 'Glückwunsch! Du hast eine Auktion gewonnen! Du bist nun der Anführer der Familie <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.',
			'FR' => 'Vous avez remporté l\'enchère, vous êtes maintenant le Boss de la famille <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>.'
		),
		38 => array(
			'EN' => 'Your family, <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>, has been auctioned off. You got the money that was in the familybank.',
			'RO' => 'Deine Familie <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a> wurde versteigert. Du hast das Geld aus dem Familienkonto erhalten.',
			'FR' => 'Votre famille, <a href="/game/?side=familie/familie&amp;id=-FAMILY_ID-">-FAMILY_NAME-</a>, a été vendue aux enchères, vous récupétez l\'argent qui se trouvait en banque de famille.'
		),
		39 => array(
			'EN' => 'Somebody tried to murder you. You lost <b>-LOST_HEALTH- %</b> health.',
			'RO' => 'Jemand hatte versucht dich umzulegen. Du hast <b>-LOST_HEALTH- %</b> Gesundheit verloren.',
			'FR' => 'Quelcun a éssayé de vous tuer, vous avez perdu <b>-LOST_HEALTH- %</b> de votre santé.'
		),
		40 => array(
			'EN' => 'You lost <b>-MONEY- $</b> in a fight against @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>.',
			'RO' => 'Du hast <b>-MONEY- $</b> im Kampf gegen @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> verloren.',
			'FR' => 'Vous avez perdu <b>-MONEY- $</b> dans un combat contre @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>.'
		),
		41 => array(
			'EN' => 'You won <b>-MONEY- $</b> in a fight against @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>.',
			'RO' => 'Du hast <b>-MONEY- $</b> in einem Kampf gegen @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> gewonnen.',
			'FR' => 'Vous gagnez <b>-MONEY- $</b> dans votre combat contre @<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a>.'
		),
		42 => array(
			'EN' => 'It\'s been created a reply in <a href="/game/?side=support&amp;id=-TICKET_ID-">your supportticket</a>.',
			'RO' => 'Es gibt eine Antwort in deinem <a href="/game/?side=support&amp;id=-TICKET_ID-">Support-Ticket</a>.',
			'FR' => 'Une réponse vous attends <a href="/game/?side=support&amp;id=-TICKET_ID-">Ticket support</a>.'
		),
		43 => array(
			'EN' => '<img src="-PLAYER_IMG-" alt="" class="startImage medium_3" />@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> blackmailed you for <b>-MONEY- $</b>.',
			'RO' => '<img src="-PLAYER_IMG-" alt="" class="startImage medium_3" />@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> erpresste dich um <b>-MONEY- $</b>.',
			'FR' => '<img src="-PLAYER_IMG-" alt="" class="startImage medium_3" />@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> vous fait chanter pour <b>-MONEY- $</b>.'
		),
		44 => array(
			'EN' => 'You spent too much time on a minimission.',
			'RO' => 'Du hast zuviel Zeit mit einer kleinen Mission verbracht.',
			'FR' => 'Votre temps de mission est écoulé'
		),
		45 => array(
			'EN' => 'You have completed a minimission. You got <b>-MONEY- $</b>, <b>-BULLETS- bullets</b> and some rank.',
			'RO' => 'Du hast die kleine Mission erfolgreich beendet. Du bekommst <b>-MONEY- $</b>, <b>-BULLETS- Munition</b> und einnige Rangpunkte.',
			'FR' => 'Mission accomplie. Vous gagnez <b>-MONEY- $</b>, <b>-BULLETS- cartouches</b> Vous progréssez aussi en Rang.'
		),
		46 => array(
			'EN' => 'You have completed mission #-MISSION_NUM-. You got <b>-MONEY- $</b>, <b>-BULLETS- bullets</b> and some rank.',
			'RO' => 'Du hast die Mission #-MISSION_NUM- erfolgreich beendet. Du bekommst <b>-MONEY- $</b>, <b>-BULLETS- Munition</b> und einige Rangpunkte.',
			'FR' => 'Mission #-MISSION_NUM- accomplie. Vous gagnez <b>-MONEY- $</b>, <b>-BULLETS- cartouches</b> Vous progréssez aussi en Rang.'
		),
		47 => array(
			'EN' => 'You\'ve lost your coinroll business in <b>-PLACE-</b>.',
			'RO' => 'Du hast deinen Kopf oder Zahl-Laden in <b>-PLACE-</b> verloren.',
		'FR' => 'Vous avez perdu votre entreprise de fausse money ici: <b>-PLACE-</b>.'
		),
		48 => array(
			'EN' => 'Your coinroll-business in -PLACE- has been auctioned off. You got the money that was in the business bank.',
			'RO' => 'Der Kopf oder Zahl-Laden in -PLACE- wurde versteigert. Du hast das Geld vom Firmenkonto erhalten.',
		'FR' => 'Votre bizness de fausse money en -PLACE- a été vendue aux enchères. Vous récupérez l\'argent  qui se trouvait en banque.'
		),
		49 => array(
			'EN' => 'Yoy\'ve won an auction! You are now the owner of the coinroll-business in -PLACE-.',
			'RO' => 'Glückwunsch! Du hast die Auktion gewonnen. Das Falschgeld-Geschäft in -PLACE- gehört jetzt dir.',
		'FR' => 'Vous gagnez l\'enchère! Vous êtes maintenant propriétaire de la fabrique de fausse monaie en  -PLACE-.'
		),
		50 => array(
			'EN' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> invited you to a planned crime.',
			'RO' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> will dich zu einem organisierten Verbrechen überreden.',
		'FR' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> t\'invite à un crime organisé.'
		),
		51 => array(
			'EN' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> wants to send you -CREDITS- coins for -CASH- $',
			'RO' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> bietet dir -CREDITS- Coins für -CASH- $ an',
		'FR' => '@<a href="/game/s/-PLAYER_NAME-" class="global_playerlink">-PLAYER_NAME-</a> veut t\'envoyer -CREDITS- credits contre -CASH- $'
		),
		52 => array(
			'EN' => '@<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> tried to murder you. You lost <b>-LOST_HEALTH- %</b> health.',
			'RO' => '@<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> hat versucht dich umzulegen. Du hast <b>-LOST_HEALTH- %</b> Gesundheit verloren.',
			'FR' => '@<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> a éssayé de vous tuer. Vous perdez <b>-LOST_HEALTH- %</b> santé.'
		),
		53 => array(
			'EN' => 'You\'ve been promoted! You now have the rank <b>-NEW_RANK-</b>. You got -BULLETS- bullets and -POINTS- coins.',
			'RO' => 'Bravo! Du bist aufgestiegen! Du hast nun den Rang <b>-NEW_RANK-</b>. Du erhälst -BULLETS- Munition und -POINTS- Coins.',
			'FR' => 'PROMOTION! Vous avez maintenant le rang <b>-NEW_RANK-</b>. Vous gagnez -BULLETS- cartouches et -POINTS- credits.'
		),
		54 => array(
			'EN' => '@<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> tried to murder you.',
			'RO' => '@<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> hat versucht dich umzulegen.',
			'FR' => '@<a href="/game/s/-MURDERER_NAME-" class="global_playerlink">-MURDERER_NAME-</a> a éssayé de vous tuer.'
		),
		55 => array(
			'EN' => '@<a href="/game/s/-SENDER_NAME-" class="global_playerlink">-SENDER_NAME-</a> sent you 1 respect points.',
			'RO' => '@<a href="/game/s/-SENDER_NAME-" class="global_playerlink">-SENDER_NAME-</a> hat dir 1 Respekt-Punkt gegeben.',
			'FR' => '@<a href="/game/s/-SENDER_NAME-" class="global_playerlink">-SENDER_NAME-</a> vous offre un point de respect.'
		),
		56 => array(
			'EN' => 'Your "Scratch Tickets" business in -PLACE- has been auctioned off. You got the money that was in the business bank.',
			'RO' => 'Dein "Rubbellose"-Geschäft in -PLACE- wurde versteigert. Du erhälst das Geld vom Firmenkonto.',
		'FR' => 'Votre activité "Jeu à gratter" en -PLACE- a été vendue aux enchères. Vous récupérez l\'argent qui se trouvait en banque.'
		),
		57 => array(
			'EN' => 'Yoy\'ve won an auction! You are now the owner of the "Scratch Tickets" business in -PLACE-.',
			'RO' => 'Glückwunsch! Du hast die Auktion vom "Rubbellos"-Geschäft in -PLACE- gewonnen.',
			'FR' => 'Vous remportez l\'enchère! Vous êtes maintenant propriétaire de "Jeux à gratter" en -PLACE-.'
		),
		58 => array(
			'EN' => 'Horserace (#-RACE_ID-) was ended. You bet on <b>-HORSE-</b> and you won <b>$ -MONEY-</b> and rank progress.',
			'RO' => 'Das Pferderennen (#-RACE_ID-) wurde beendet. Du hattest auf das Pferd <b>-HORSE-</b> gesetzt und damit <b>-MONEY- $</b> und Rangpunkte gewonnen. Glückwunsch!',
			'FR' => 'Cursa de Cai (#-RACE_ID-) s-a incheiat. Calul castigator a fost <b>-HORSE-</b>, iar tu ai castigat <b>-MONEY- $</b> si ceva progres nivel.'
		),
	),
	
	// Familien Ereignisse
	'family_logs' => array(
		'new_member' => array(
			'EN' => 'The family has a new member - @<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a>!',
			'RO' => 'Die Familie hat ein neues Mitglied - @<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a>!',
			'FR' => 'La famille compte un nouveau membre - @<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a>!'
		),
		'set_underboss' => array(
			'EN' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> is now deputy!',
			'RO' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> ist jetzt Stellertreter in der Familie!',
			'FR' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> est maintenant vice-famille.'
		),
		'got_business'=> array(
			'EN' => 'Family bought &laquo;-BUSINESS-&raquo;',
			'RO' => 'Die Familie hat das Geschäft &laquo;-BUSINESS-&raquo; übernommen',
			'FR' => 'La famille a repris l\'entreprise &laquo;-BUSINESS-&raquo;'
		),
		'player_left' => array(
			'EN' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> left the family!',
			'RO' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> hat die Familie verlassen!',
			'FR' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> a quitté la famille!'
		),
		'unset_underboss' => array(
			'EN' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> no longer deputy.',
			'RO' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> ist kein Stellvertreter in der Familie meht.',
			'FR' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> n\'est plus député.'
		),
		'kicked_member' => array(
			'EN' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-BOSS-" class="global_playerlink playerlink vip_pl" rel="-BOSS-">-BOSS-</a> fired @<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a>.',
			'RO' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-BOSS-" class="global_playerlink playerlink vip_pl" rel="-BOSS-">-BOSS-</a> hat @<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> aus der Familie entlassen.',
			'FR' => '@<a href="https://nmafia.unterweltmafia.de/game/s/-PLAYER-" class="global_playerlink playerlink" rel="-PLAYER-">-PLAYER-</a> a été retirée de la famille.'
		),
		'attack_sent_success' => array(
			'EN' => 'The family attacked <b>-FAMILY-</b>. We won this fight and the family received <b>$ -CASH-</b>.',
			'RO' => 'Die Familie hat <b>-FAMILY-</b> angegriffen. Wir haben den Kampf gewonnen und die Familie hat <b>-CASH- $</b> bekommen.',
			'FR' => 'Famille attaqué la famille <b>-FAMILY-</b>. L\'attaque a été un succès, et la famille a gagné <b>-CASH- $</b>.'
		),
		'attack_received_success' => array(
			'EN' => '<b>-FAMILY-</b> attacked our family. They stollen <b>-CASH- $</b> and we lost <b>-STRENGTH- strength points</b>.',
			'RO' => '<b>-FAMILY-</b> hat unsere Familie angegriffen und gewonnen. Die haben uns <b>-CASH- $</b> gestohlen und wir haben <b>-STRENGTH- Stärkepunkte</b> verloren.',
			'FR' => 'Famille <b>-FAMILY-</b> nous a attaqués et a gagné. Ils ont volé <b>-CASH- $</b> et perdu <b>-STRENGTH- points de puissance</b>.'
		),
	),
	
	/*
	 * Top Menu
	*/
	'topMenu_message_title' => array(
		'EN' => 'Forums',
		'RO' => 'Forum',
		'FR' => 'Forum'
	),
	'topMenu_message_desc' => array(
		'EN' => 'Last topics',
		'RO' => 'letzte Beiträge',
		'FR' => 'Dernier sujet'
	),
	'topMenu_log_title' => array(
		'EN' => 'Log',
		'RO' => 'Ereignisse',
		'FR' => 'Historique'
	),
	'topMenu_log_desc' => array(
		'EN' => 'Last 5 logevents',
		'RO' => 'letzten 5 Ereignisse',
		'FR' => '5 derniers évènements'
	),
	'topMenu_log_no' => array(
		'EN' => 'No logs.',
		'RO' => 'keine Ereignisse.',
		'FR' => 'Pas d\'historique'
	),
	'topMenu_playerinfo_title' => array(
		'EN' => 'Playerinfo',
		'RO' => 'Spielerinfos',
		'FR' => 'Information'
	),
	'topMenu_playerinfo_desc' => array(
		'EN' => 'Your information',
		'RO' => 'Deine Informationen',
		'FR' => 'Vos informations'
	),
	'topMenu_playerinfo_place' => array(
		'EN' => 'You\'re in <a href="/game/?side=harta">-PLACE-</a>',
		'RO' => 'Du bist in <a href="/game/?side=harta">-PLACE-</a>',
		'FR' => 'Vous êtes en <a href="/game/?side=harta">-PLACE-</a'
	),
	'topMenu_playerinfo_cash' => array(
		'EN' => 'Cash: <span>-MONEY- $</span>',
		'RO' => 'Geld: <span>-MONEY- $</span>',
		'FR' => 'Argent: <span>-MONEY- $</span>'
	),
	'topMenu_playerinfo_prt' => array(
		'EN' => 'You are under protection for 72 hours!',
		'RO' => 'Du stehst 72 Stunden unter Schutz!',
		'FR' => 'Vous êtes sous protection pendant 72 heures'
	),
	'topMenu_playerinfo_prt_d' => array(
		'EN' => 'Disable Protection',
		'RO' => 'Schutz deaktivieren',
		'FR' => 'Désactiver ma protectio'
	),
	
	// Sub Menu
	'subMenu-01' => array(
		'EN' => 'Perform crimes',
		'RO' => 'Verbrechen begehen',
		'FR' => 'Faire des crimes'
	),
	'subMenu-02' => array(
		'EN' => 'Description',
		'RO' => 'Beschreibung',
		'FR' => 'Description'
	),
	'subMenu-03' => array(
		'EN' => 'Profile image',
		'RO' => 'Profilbild',
		'FR' => 'Avatard'
	),
	'subMenu-04' => array(
		'EN' => 'Music',
		'RO' => 'Musik',
		'FR' => 'Musique'
	),
	'subMenu-05' => array(
		'EN' => 'Password',
		'RO' => 'Passwort',
		'FR' => 'Mot de passe'
	),
	'subMenu-06' => array(
		'EN' => 'Forums',
		'RO' => 'Forum',
		'FR' => 'Forum'
	),
	'subMenu-07' => array(
		'EN' => 'My Account',
		'RO' => 'Mein Konto',
		'FR' => 'Mon compte'
	),
	'subMenu-08' => array(
		'EN' => 'Invite friends <span class="small">(Earn coins)</span>',
		'RO' => 'Freunde einladen <span class="small">(Coins verdienen)</span>',
		'FR' => 'Invitez vos amis <span class="small">(Gagne des crédits)'
	),
	'subMenu-09' => array(
		'EN' => 'Send PM',
		'RO' => 'Post senden',
		'FR' => 'Envoyer Message Privé'
	),
	'subMenu-10' => array(
		'EN' => 'Friend',
		'RO' => 'Freund',
		'FR' => 'Ami'
	),
	'subMenu-11' => array(
		'EN' => 'Enemy',
		'RO' => 'Feind',
		'FR' => 'Ennemi'
	),
	'subMenu-12' => array(
		'EN' => 'Friends',
		'RO' => 'Freunde',
		'FR' => 'Amis'
	),
	'subMenu-13' => array(
		'EN' => 'Enemies',
		'RO' => 'Feinde',
		'FR' => 'Ennemis'
	),
	'subMenu-14' => array(
		'EN' => 'Add',
		'RO' => 'hinzufügen',
		'FR' => 'Ajouter'
	),
	'subMenu-15' => array(
		'EN' => 'Send PM',
		'RO' => 'Post senden',
		'FR' => 'Envoyer le message privé'
	),
	'subMenu-16' => array(
		'EN' => 'Create',
		'RO' => 'Erstellen',
		'FR' => 'Créer'
	),
	'subMenu-17' => array(
		'EN' => 'Overview',
		'RO' => 'Übersicht',
		'FR' => 'Vue générale'
	),
	'subMenu-18' => array(
		'EN' => 'Families',
		'RO' => 'Familien',
		'FR' => 'Familles'
	),
	'subMenu-19' => array(
		'EN' => 'New Family',
		'RO' => 'Neue Familie',
		'FR' => 'Nouvelle famille'
	),
	'subMenu-20' => array(
		'EN' => 'Family bussiness',
		'RO' => 'Familienangelegenheiten',
		'FR' => 'Les Affaires de la famille'
	),
	'subMenu-21' => array(
		'EN' => 'Attacks',
		'RO' => 'Angriffe',
		'FR' => 'Attaques'
	),
	'subMenu-22' => array(
		'EN' => 'Attacks list',
		'RO' => 'Angriffsliste',
		'FR' => 'Liste des attaques'
	),
	'subMenu-23' => array(
		'EN' => 'Bunker',
		'RO' => 'Bunker',
		'FR' => 'Bunker'
	),
	'subMenu-24' => array(
		'EN' => 'Respect',
		'RO' => 'Respekt',
		'FR' => 'Respect'
	),
	'subMenu-25' => array(
		'EN' => 'Last 5 Races',
		'RO' => 'Letzten 5 Rennen',
		'FR' => '5 dernières courses'
	),
	
	// Javascript
	'js-01' => array(
		'EN' => 'Clear',
		'RO' => 'Bereit',
		'FR' => 'OK'
	),
	'js-02' => array(
		'EN' => 'Closed',
		'RO' => 'Geschlossen',
		'FR' => 'Fermé'
	),
	'js-03' => array(
		'EN' => 'new',
		'RO' => 'neu',
		'FR' => 'Actualités'
	),
	
	// Header
	'header-01' => array(
		'EN' => 'Homepage',
		'RO' => 'Startseite',
		'FR' => 'Accueil'
	),
	'header-02' => array(
		'EN' => 'News',
		'RO' => 'Neues',
		'FR' => 'Actualités'
	),
	'header-03' => array(
		'EN' => 'Logout',
		'RO' => 'Abmelden',
		'FR' => 'Déconnexion'
	),
	'header-04' => array(
		'EN' => 'Small Header',
		'RO' => 'Verkleinern',
		'FR' => 'Réduire'
	),
	'header-05' => array(
		'EN' => 'Rules',
		'RO' => 'Regeln',
		'FR' => 'Règlement'
	),
	'header-06' => array(
		'EN' => 'EN',
		'RO' => 'RO',
		'FR' => 'FR'
	),
	'header-07' => array(
		'EN' => 'Welcome!',
		'RO' => 'Herzlich willkommen!',
		'FR' => 'Bienvenue'
	),
	'header-08' => array(
		'EN' => 'Contact Us',
		'RO' => 'Kontakt',
		'FR' => 'Nous contacter'
	),
	'header-09' => array(
		'EN' => 'User',
		'RO' => 'Benutzer',
		'FR' => 'Utilisateur'
	),
	'header-10' => array(
		'EN' => 'Login',
		'RO' => 'Anmelden',
		'FR' => 'Connexion'
	),
	'header-11' => array(
		'EN' => 'Register',
		'RO' => 'Registrieren',
		'FR' => 'Enregistrez-vous'
	),
	'header-12' => array(
		'EN' => 'Recover password',
		'RO' => 'Passwortwiederherstellung',
		'FR' => 'Récupérer mon mot de passe'
	),
	'header-13' => array(
		'EN' => 'Affiliate Program',
		'RO' => 'Geld verdienen',
		'FR' => 'Gagner de l\'argent'
	),
	
	// Homepage
	'home-01' => array(
		'EN' => 'Username / E-mail',
		'RO' => 'Spielername / Email',
		'FR' => 'Utilisateur / E-mail'
	),
	'home-02' => array(
		'EN' => 'Password',
		'RO' => 'Passwort',
		'FR' => 'Mot de passe'
	),
	'home-03' => array(
		'EN' => 'Login failed (<a href="?side=recuperare">Forgot your password?</a>)',
		'RO' => 'Anmeldung fehlgeschlagen (<a href="?side=recuperare">Passwort vergessen?</a>)',
		'FR' => 'Connexion impossible (<a href="?side=recuperare">Avez vous perdu votre mot de passe?</a>)'
	),
	'home-04' => array(
		'EN' => 'Server time',
		'RO' => 'Serverzeit',
		'FR' => 'Heure du serveur'
	),
	'home-05' => array(
		'EN' => 'Incorrect username!',
		'RO' => 'Ungültiger Spielername!',
		'FR' => 'Pseudo incorrect'
	),
	'home-06' => array(
		'EN' => 'Username already used!',
		'RO' => 'Dieser Spielername wird bereits verwendet!',
		'FR' => 'Ce pseudo est déjà utilisé'
	),
	'home-07' => array(
		'EN' => 'Congratulations! You have successfully registered on <b>Underworldmafia</b>!<br /><br />Need help?<br />Check <a href="/game/?side=faq">FAQ</a> page or contact us at <a href="/game/?side=support">support</a> page.<br /><br />Good luck!',
		'RO' => 'Herzlichen Glückwunsch! Du wurdest erfolgreich in <b>Unterweltmafia</b> registriert!<br /><br />Brauchst du Hilfe?<br />Besuche die Seite mit der <a href="/game/?side=faq">Anleitung</a> Seite oder kontaktiere den <a href="/game/?side=support">Support</a>.<br /><br />Viel Glück!',
		'FR' => 'Félicitation! Vous êtes enregistré sur <b>Mon-Gangster</b>!<br /><br />Besoin d\'aide?<br />Visitez la <a href="/game/?side=faq">FAQ</a>, ou contactez nous ici <a href="/game/?side=support">suport</a>.<br /><br />Bonne chance!'
	),
	'home-08' => array(
		'EN' => 'Invalid email address!',
		'RO' => 'Ungültige E-Mail-Adresse!',
		'FR' => 'eMail invalide!'
	),
	'home-09' => array(
		'EN' => 'Email addresses do not match!',
		'RO' => 'E-Mail-Adressen stimmen nicht überein!',
		'FR' => 'Votre adresse eMail ne fonctionne pas!'
	),
	'home-10' => array(
		'EN' => 'Email address is already used!',
		'RO' => 'Diese E-Mail wird bereits verwendet!',
		'FR' => 'Cette adresse eMail est déjà utilisée!'
	),
	'home-11' => array(
		'EN' => 'Is allowed only one player account on IP!',
		'RO' => 'Nur ein Spielerkonto auf einer IP ist erlaubt!',
		'FR' => 'Vous nêtes autorisé qu\'a un seul utilisateur par IP!'
	),
	'home-12' => array(
		'EN' => 'Must confirm that you have read the rules!',
		'RO' => 'Du mußt bestätigen, dass du die Regeln gelesen hast und akzeptierst!',
		'FR' => 'Confirmez votre adhésion au règlement!'
	),
	'home-13' => array(
		'EN' => 'Username',
		'RO' => 'Spielername',
		'FR' => 'Utilisateur'
	),
	'home-14' => array(
		'EN' => 'E-mail address',
		'RO' => 'Email',
		'FR' => 'Email'
	),
	'home-15' => array(
		'EN' => 'Repeat e-mail',
		'RO' => 'Email wiederholen',
		'FR' => 'Répétez votre Email'
	),
	'home-16' => array(
		'EN' => 'I have read <a href="/rules.php" target="_blank">game rules</a> & <a href="/regulament.php" target="_blank">conditions</a>',
		'RO' => 'Ich habe die <a href="/rules.php" target="_blank">Spielregeln</a> & <a href="/regulament.php" target="_blank">AGB´s</a> gelesen und akzeptiert.',
		'FR' => 'J\'ai lu <a href="/rules.php" target="_blank">le règlement du jeu</a> & <a href="/regulament.php" target="_blank">conditions</a>'
	),
	'home-17' => array(
		'EN' => 'Email has already sent!',
		'RO' => 'E-Mail wurde bereits gesendet!',
		'FR' => 'Email déjà envoyé!'
	),
	'home-18' => array(
		'EN' => 'Email has been sent!',
		'RO' => 'E-Mail wurde gesendet!',
		'FR' => 'Un eMail vous a été envoyé!'
	),
	'home-19' => array(
		'EN' => 'Next step',
		'RO' => 'Nächster Schritt',
		'FR' => 'Etape suivante'
	),
	'home-20' => array(
		'EN' => 'Password successfully changed!',
		'RO' => 'Passwort erfolgreich geändert!',
		'FR' => 'Votre mot de passe a été changé avec succès!'
	),
	'home-21' => array(
		'EN' => 'Underworldmafia is a new RPG game, where you have to fight, to become the most powerful of the mobs.<br> You can make illegal actions like crimes, car theft, blackmailing or you can play gamble games like blackjack, roulette and many more.',
		'RO' => 'Unterweltmafia ist ein neues RPG-Spiel, in dem du kämpfen musst, um der mächtigste Mafiosi der Unterwelt zu werden.<br>Du kannst Verbrechen, Autodiebstahl, Erpressungen und noch vieles mehr verüben. Und dein Glück mit Glücksspielen wie Blackjack, Roulette und andere versuchen.',
		'FR' => 'Mafia Underworld est un RPG dans lequel vous devez vous frailler un chemin parmi les plus puissants des Gangsters en ligne. <br>Vous apprendrez à réaliser vos premiers meurtres, crimes et vols avant de pouvoir rejoindre ou de créer votre propre famille. Managez vos établissements, faites chanter les autres joueurs. Certains voyous sont à la rue en ce moment, les autres sont déjà millionnaires!'
	),
	'home-22' => array(
		'EN' => 'Register now, it\'s free!',
		'RO' => 'Jetzt kostenlos registrieren!',
		'FR' => 'Enregistrez vous gratuitement!'
	),
	'home-23' => array(
		'EN' => 'Step',
		'RO' => 'Schritt',
		'FR' => 'Etape'
	),
	'home-24' => array(
		'EN' => 'An registration email was sent! Please check in your Inbox / Spam!',
		'RO' => 'Eine Registrierungs-E-Mail wurde gesendet! Siehe in deinem Emailpostfach/(Spam?) nach. ',
		'FR' => 'Un eMail de confirmation vous a été envoyé, vérifiez vos eMail!'
	),
	
	// Menu
	'menu-01' => array(
		'EN' => 'CRIME',
		'RO' => 'VERBRECHEN',
		'FR' => 'CRIME'
	),
	'menus-01' => array(
		'EN' => 'Illegal Actions',
		'RO' => 'Illegale Handlungen',
		'FR' => 'Actes illégaux'
	),
	'menu-02' => array(
		'EN' => 'CITY',
		'RO' => 'STADT',
		'FR' => 'VILLE'
	),
	'menus-02' => array(
		'EN' => 'Useful destinations',
		'RO' => 'Nützliche Orte',
		'FR' => 'Destination'
	),
	'menu-03' => array(
		'EN' => 'CASINO',
		'RO' => 'CASINO',
		'FR' => 'CASINO'
	),
	'menus-03' => array(
		'EN' => 'Gambling',
		'RO' => 'Glücksspiel',
		'FR' => 'Jeu de hasard'
	),
	'menu-04' => array(
		'EN' => 'COINS',
		'RO' => 'COINS',
		'FR' => 'CREDITS'
	),
	'menus-04' => array(
		'EN' => 'Buy / Use coins',
		'RO' => 'Spenden/Coins erhalten',
		'FR' => 'Acheter / Utilise des crédits'
	),
	'menu-05' => array(
		'EN' => 'FORUMS',
		'RO' => 'FORUM',
		'FR' => 'FORUM'
	),
	'menus-05' => array(
		'EN' => 'Buy, sell, etc',
		'RO' => 'Verkauf, Kauf, etc',
		'FR' => 'Acheter, Vendre, etc'
	),
	'menu-06' => array(
		'EN' => 'FAMILY',
		'RO' => 'FAMILIE',
		'FR' => 'FAMILLE'
	),
	'menus-06' => array(
		'EN' => 'Mafia families...',
		'RO' => 'Mafia-Familien...',
		'FR' => 'Familles mafieuses...'
	),
	'menu-07' => array(
		'EN' => 'USER',
		'RO' => 'BENUTZER',
		'FR' => 'UTILISATEUR'
	),
	'menus-07' => array(
		'EN' => 'Settings, etc',
		'RO' => 'Einstellungen, etc',
		'FR' => 'Paramètres, etc'
	),
	'menu-08' => array(
		'EN' => 'SYSTEM',
		'RO' => 'SISTEM',
		'FR' => 'SYSTEME'
	),
	'menus-08' => array(
		'EN' => 'Features',
		'RO' => 'Eigenschaften',
		'FR' => 'Généralités'
	),
	
	/*
	 * Other
	*/
	'ot-rank' => array(
		'EN' => 'Rank',
		'RO' => 'Rang',
		'FR' => 'Rang'
	),
	'ot-rankcash' => array(
		'EN' => 'Money Rank',
		'RO' => 'Geld-Rang',
		'FR' => 'Rang argent'
	),
	'ot-rankplace' => array(
		'EN' => 'Rankplace',
		'RO' => 'Rangplatz',
		'FR' => 'Classement Rang'
	),
	'ot-wanted_level' => array(
		'EN' => 'Wanted-level',
		'RO' => 'Gefahrenstatus',
		'FR' => 'Niveau suivant'
	),
	'ot-health' => array(
		'EN' => 'Health',
		'RO' => 'Gesundheit',
		'FR' => 'Santé'
	),
	'ot-weapon' => array(
		'EN' => 'Weapon',
		'RO' => 'Waffe',
		'FR' => 'Arme'
	),
	'ot-protection' => array(
		'EN' => 'Protection',
		'RO' => 'Schutz',
		'FR' => 'Protection'
	),
	'ot-family' => array(
		'EN' => 'Family',
		'RO' => 'Familie',
		'FR' => 'Famille'
	),
	'ot-cancel' => array(
		'EN' => 'Cancel',
		'RO' => 'Beenden',
		'FR' => 'Annuler'
	),
	'ot-status' => array(
		'EN' => 'Status',
		'RO' => 'Status',
		'FR' => 'Status'
	),
	'ot-seconds' => array(
		'EN' => 'Seconds',
		'RO' => 'Sekunden',
		'FR' => 'Secondes'
	),
	'ot-none' => array(
		'EN' => 'None',
		'RO' => 'Keine',
		'FR' => 'Non'
	),
	'ot-view' => array(
		'EN' => 'View',
		'RO' => 'Zeigen',
		'FR' => 'Voir'
	),
	'ot-read' => array(
		'EN' => 'Read more',
		'RO' => 'Weiterlesen',
		'FR' => 'Plus d\'infos'
	),
	'ot-view-all' => array(
		'EN' => 'View all',
		'RO' => 'Alles zeigen',
		'FR' => 'Voir tout'
	),
	'ot-view-p' => array(
		'EN' => 'View profile',
		'RO' => 'Profil zeigen',
		'FR' => 'Voir profil'
	),
	'ot-send-pm' => array(
		'EN' => 'Send PM',
		'RO' => 'Nachricht senden',
		'FR' => 'Envoyer Message'
	),
	'ot-location' => array(
		'EN' => 'You are in',
		'RO' => 'Du bist in',
		'FR' => 'Vous êtes à'
	),
	'ot-cmoney' => array(
		'EN' => 'You have',
		'RO' => 'Du hast',
		'FR' => 'Vous avez'
	),
	'ot-subject' => array(
		'EN' => 'Subject',
		'RO' => 'Thema',
		'FR' => 'Sujet'
	),
	'ot-lastr' => array(
		'EN' => 'Last reply',
		'RO' => 'Letzte Antwort',
		'FR' => 'Dernière réponse'
	),
	'ot-lasta' => array(
		'EN' => 'Last activity',
		'RO' => 'Letzte Aktivität',
		'FR' => 'Dernière activité'
	),
	'ot-nofind' => array(
		'EN' => 'Didn\'t find any -WHAT-.',
		'RO' => 'Habe kein/e -WHAT- gefunden.',
		'FR' => 'N\'avons pas trouvé -WHAT-'
	),
	'ot-money' => array(
		'EN' => 'Cash',
		'RO' => 'Geld',
		'FR' => 'Argent'
	),
	'ot-points' => array(
		'EN' => 'Coins',
		'RO' => 'Coins',
		'FR' => 'Crédits'
	),
	'ot-bullets' => array(
		'EN' => 'Bullets',
		'RO' => 'Munition',
		'FR' => 'Cartouches'
	),
	'ot-avatar' => array(
		'EN' => 'Change Picture',
		'RO' => 'Profilbild ändern',
		'FR' => 'Modifier Avatard'
	),
	'ot-spital' => array(
		'EN' => 'You are in hospital. You have to wait <span class="countdown">-TIME-</span> seconds.',
		'RO' => 'Du bist im Krankenhaus. Deine Behandlung dauert noch <span class="countdown">-TIME-</span> Sekunden.',
		'FR' => 'Vous êtes à l\'hopital, patientez <span class="countdown">-TIME-</span> secondes.'
	),
	'ot-arestat' => array(
		'EN' => 'You are under arrest for <span class="countdown">-TIME-</span> seconds.',
		'RO' => 'Du wirst für <span class="countdown">-TIME-</span> Sekunden festgenommen.',
		'FR' => 'Vous avez été arrété, patientez <span class="countdown">-TIME-</span> secondes.'
	),
	'ot-istoric' => array(
		'EN' => 'Action logs',
		'RO' => 'Ereignisse',
		'FR' => 'Historique des action'
	),
	'ot-fblike' => array(
		'EN' => 'Like us on Facebook and you will receive <b>5 coins</b>!',
		'RO' => 'Gib uns auf Facebook ein "Gefällt mir" und erhalte <b>5 Coins</b>!',
		'FR' => 'Aimez notre page Facebook et gagnez <b>5 crédits</b>!'
	),
	'ot-invite' => array(
		'EN' => 'Invite your friends and earn coins',
		'RO' => 'Lade deine Freunde ein und erhalte Coins',
		'FR' => 'Invite tes amis et gagne des crédits'
	),
	'ot-stats' => array(
		'EN' => 'Statistics',
		'RO' => 'Statistiken',
		'FR' => 'Statistiques'
	),
	'ot-distanta' => array(
		'EN' => 'Distance',
		'RO' => 'Entfernung',
		'FR' => 'Distance'
	),
	'ot-from' => array(
		'EN' => 'From',
		'RO' => 'Von',
		'FR' => 'De'
	),
	'ot-to' => array(
		'EN' => 'To',
		'RO' => 'Nach',
		'FR' => 'A'
	),
	'ot-city' => array(
		'EN' => 'City',
		'RO' => 'Stadt',
		'FR' => 'Ville'
	),
	'ot-yes' => array(
		'EN' => 'Yes',
		'RO' => 'Ja',
		'FR' => 'Oui'
	),
	'ot-no' => array(
		'EN' => 'No',
		'RO' => 'Nein',
		'FR' => 'Non'
	),
	'ot-back' => array(
		'EN' => 'Back',
		'RO' => 'Zurück',
		'FR' => 'Retour'
	),
	'ot-next' => array(
		'EN' => 'Next',
		'RO' => 'Weiter',
		'FR' => 'Suivant'
	),
	'ot-lang' => array(
		'EN' => 'Language',
		'RO' => 'Sprache',
		'FR' => 'Langue'
	),
	'ot-r-danger' => array(
		'EN' => 'Reset wanted-level',
		'RO' => 'Gefahrenstatus zurücksetzen',
		'FR' => 'Reset niveau attendu'
	),
	'ot-iston' => array(
		'EN' => 'History actions enabled',
		'RO' => 'Aktionsverlauf aktiviert',
		'FR' => 'Historique des actions activé'
	),
	'ot-istoff' => array(
		'EN' => 'History actions disabled',
		'RO' => 'Aktionsverlauf deaktiviert',
		'FR' => 'Historique des actions désactivé'
	),
	
	// Auktionen
	'auction-auction' => array(
		'EN' => 'Auction',
		'RO' => 'Auktion',
		'FR' => 'Enchère'
	),
	'auction-auctions' => array(
		'EN' => 'Auctions',
		'RO' => 'Auktionen',
		'FR' => 'Enchères'
	),
	'auction-create' => array(
		'EN' => 'Create auction',
		'RO' => 'Auktion erstellen',
		'FR' => 'Créer une enchère'
	),
	'auction-object' => array(
		'EN' => 'Object',
		'RO' => 'Objekt',
		'FR' => 'Objet'
	),
	'auction-payment' => array(
		'EN' => 'Payment method',
		'RO' => 'Zahlungsmethode',
		'FR' => 'Mode de paiement'
	),
	'auction-bidstart' => array(
		'EN' => 'Bid start',
		'RO' => 'Startpreis',
		'FR' => 'Commencer'
	),
	'auction-min_increase' => array(
		'EN' => 'Minimum bid increase',
		'RO' => 'Mindestgebotserhöhung',
		'FR' => 'Augmentation des offres minimum'
	),
	'auction-endtime' => array(
		'EN' => 'Start time',
		'RO' => 'Startzeit',
		'FR' => 'Heure de départ'
	),
	'auction-endtime' => array(
		'EN' => 'End time',
		'RO' => 'Endzeit',
		'FR' => 'Temps écoulé'
	),
	'auction-status_open' => array(
		'EN' => 'Open for bids',
		'RO' => 'Offen für Angebote',
		'FR' => 'Faire une offre'
	),
	'auction-status_ended' => array(
		'EN' => 'Ended',
		'RO' => 'Beendet',
		'FR' => 'Terminé'
	),
	'auction-ends_in' => array(
		'EN' => 'Ends in',
		'RO' => 'Endet in',
		'FR' => 'Se termine dans'
	),
	'auction-highest_bid' => array(
		'EN' => 'Highest bid',
		'RO' => 'Höchstes Gebot',
		'FR' => 'Meilleure offre'
	),
	'auction-winner_bid' => array(
		'EN' => 'Winner bid',
		'RO' => 'Gewinner-Gebot',
		'FR' => 'Offre remportée'
	),
	'auction-delete_auction' => array(
		'EN' => 'Delete auction',
		'RO' => 'Auktion löschen',
		'FR' => 'Suprimer l\'enchère'
	),
	'auction-end_auction' => array(
		'EN' => 'End auction',
		'RO' => 'Auktion beenden',
		'FR' => 'Enchère terminée'
	),
	'auction-make_bid' => array(
		'EN' => 'Make bid',
		'RO' => 'Auktion erstellen',
		'FR' => 'Faire une offre'
	),
	'auction-bid' => array(
		'EN' => 'Bid',
		'RO' => 'Angebot',
		'FR' => 'Offre'
	),
	'auction-bids' => array(
		'EN' => 'Bids',
		'RO' => 'Gebote',
		'FR' => 'Offres'
	),
	'auction-001' => array(
		'EN' => 'The object is already out for auction',
		'RO' => 'Dieser Artikel wurde bereits versteigert',
		'FR' => 'Cet objet n\'est plus aux enchères'
	),
	'auction-002' => array(
		'EN' => 'The bidstart has to be greater than -AMOUNT-.',
		'RO' => 'Das Startgebot muss höher als -AMOUNT- sein.',
		'FR' => 'L\'offre doit être supérieure à -AMOUNT-.'
	),
	'auction-003' => array(
		'EN' => 'The bidstart has to be greater than -AMOUNT-.',
		'RO' => 'Das Startgebot muss höher als -AMOUNT- sein.',
		'FR' => 'L\'offre doit être supérieure à -AMOUNT-.'
	),
	'auction-004' => array(
		'EN' => 'Invalid starttime.',
		'RO' => 'Ungültige Startzeit.',
		'FR' => 'Heure de début invalide.'
	),
	'auction-005' => array(
		'EN' => 'The end time has to bee greater than current time plus 10 minutes.',
		'RO' => 'Die Endzeit muss später als die aktuelle Zeit plus 10 Minuten sein.',
		'FR' => 'L\'heure de fin doit être supérieure à l\'heure actuelle + 10 minutes.'
	),
	'auction-006' => array(
		'EN' => 'You have created an auction.',
		'RO' => 'Du hast eine Auktion erstellt.',
		'FR' => 'Votre enchère est créée.'
	),
	'auction-007' => array(
		'EN' => 'You started the auction, so you can not make a bid.',
		'RO' => 'Hey, du kannst doch nicht auf deine eigene Auktion bieten.',
		'FR' => 'Vous avez démarré l\'enchère, mais vous ne pouvez pas faire d\'offre.'
	),
	'auction-008' => array(
		'EN' => 'Couldn\'t make bid.',
		'RO' => 'Du konntest nicht bieten.',
		'FR' => 'Ne peut pas faire d\'offre.'
	),
	'auction-009' => array(
		'EN' => 'You dont have that much -CURRENCY-.',
		'RO' => 'Du hast nicht genügend -CURRENCY-.',
		'FR' => 'Vous n\'avez pas assez de -CURRENCY-.'
	),
	'auction-010' => array(
		'EN' => 'You have to bid at least -AMOUNT-.',
		'RO' => 'Du musst mehr als -AMOUNT- bieten.',
		'FR' => 'Votre offre doit être à minima de -AMOUNT-.'
	),
	'auction-011' => array(
		'EN' => 'You have made a bid.',
		'RO' => 'Du hast ein Gebot abgegeben.',
		'FR' => 'Vous avez fait une offre.'
	),
	'auction-012' => array(
		'EN' => 'You have deleted an auction.',
		'RO' => 'Du hast die Auktion gelöscht.',
		'FR' => 'Vous avez suprimé l\'enchère.'
	),
	'auction-013' => array(
		'EN' => 'You have ended the auction.',
		'RO' => 'Du hast die Auktion beendet.',
		'FR' => 'Vous avez terminé l\'enchère.'
	),
	'auction-014' => array(
		'EN' => 'Select',
		'RO' => 'Wählen',
		'FR' => 'Sélection'
	),
	'auction-015' => array(
		'EN' => 'Bids',
		'RO' => 'Gebote',
		'FR' => 'Offres'
	),
	'auction-016' => array(
		'EN' => 'Active auctions',
		'RO' => 'Offene Auktionen',
		'FR' => 'Enchères actives'
	),
	'auction-017' => array(
		'EN' => 'Finished auctions',
		'RO' => 'Beendete Auktionen',
		'FR' => 'Terminer l\'enchère'
	),
	
	// Allgemeine Texte
	'txt-01' => array(
		'EN' => 'Buy',
		'RO' => 'Kaufen',
		'FR' => 'Acheter'
	),
	'txt-02' => array(
		'EN' => 'Name',
		'RO' => 'Name',
		'FR' => 'Nom'
	),
	'txt-03' => array(
		'EN' => 'Price',
		'RO' => 'Preis',
		'FR' => 'Prix'
	),
	'txt-04' => array(
		'EN' => 'latency',
		'RO' => 'Latenz',
		'FR' => 'Latence'
	),
	'txt-05' => array(
		'EN' => 'Location',
		'RO' => 'Position',
		'FR' => 'Position'
	),
	'txt-06' => array(
		'EN' => 'Player',
		'RO' => 'Spieler',
		'FR' => 'Joueur'
	),
	'txt-07' => array(
		'EN' => 'Time',
		'RO' => 'Zeit',
		'FR' => 'Heure'
	),
	'txt-08' => array(
		'EN' => 'minutes',
		'RO' => 'Minuten',
		'FR' => 'minutes'
	),
	'txt-09' => array(
		'EN' => 'Search',
		'RO' => 'Suche',
		'FR' => 'Chercher'
	),
	'txt-10' => array(
		'EN' => 'Cancel',
		'RO' => 'Abbrechen',
		'FR' => 'Annuler'
	),
	'txt-11' => array(
		'EN' => 'Progress',
		'RO' => 'Fortschritt',
		'FR' => 'Progression'
	),
	'txt-12' => array(
		'EN' => 'Start time',
		'RO' => 'Startzeit',
		'FR' => 'Heure de début'
	),
	'txt-13' => array(
		'EN' => 'End time',
		'RO' => 'Endzeit',
		'FR' => 'Heure de fin'
	),
	'txt-14' => array(
		'EN' => 'Submit',
		'RO' => 'Senden',
		'FR' => 'Soumettre'
	),
	'txt-15' => array(
		'EN' => 'Sell',
		'RO' => 'Verkaufen',
		'FR' => 'Vendre'
	),
	'txt-16' => array(
		'EN' => 'Send request',
		'RO' => 'Anfrage senden',
		'FR' => 'Envoyer une demande'
	),
	'txt-17' => array(
		'EN' => 'Password',
		'RO' => 'Passort',
		'FR' => 'Mot de passe'
	),
	'txt-18' => array(
		'EN' => 'Repeat password',
		'RO' => 'Passwort wiederholen',
		'FR' => 'Répéter le mot de passe'
	),
	'txt-19' => array(
		'EN' => 'Change password',
		'RO' => 'Passwort ändern',
		'FR' => 'Changer le mot de passe'
	),
	'txt-20' => array(
		'EN' => 'Wrong password!',
		'RO' => 'Falsches Passwort!',
		'FR' => 'Mot de passe invalide!'
	),
	'txt-21' => array(
		'EN' => 'Change',
		'RO' => 'Ändern',
		'FR' => 'Modifier'
	),
	'txt-22' => array(
		'EN' => 'Info',
		'RO' => 'Information',
		'FR' => 'Information'
	),
	'txt-23' => array(
		'EN' => 'Sent',
		'RO' => 'Versendet',
		'FR' => 'Envoyé'
	),
	'txt-24' => array(
		'EN' => 'Received',
		'RO' => 'Empfangen',
		'FR' => 'Reçu'
	),
	'txt-25' => array(
		'EN' => 'Amount',
		'RO' => 'Menge',
		'FR' => 'Montant'
	),
	'txt-26' => array(
		'EN' => 'all',
		'RO' => 'alle',
		'FR' => 'Tous'
	),
	'txt-27' => array(
		'EN' => 'Date',
		'RO' => 'Datum',
		'FR' => 'Date'
	),
	'txt-28' => array(
		'EN' => 'Recipient',
		'RO' => 'Empfänger',
		'FR' => 'Destinataire'
	),
	'txt-29' => array(
		'EN' => 'Type',
		'RO' => 'Typ',
		'FR' => 'Catégorie'
	),
	'txt-30' => array(
		'EN' => 'Person',
		'RO' => 'Kontakt',
		'FR' => 'Contact'
	),
	'txt-31' => array(
		'EN' => 'Sender',
		'RO' => 'Absender',
		'FR' => 'Expediteur'
	),
	'txt-32' => array(
		'EN' => 'Expires in',
		'RO' => 'Läuft ab in',
		'FR' => 'Expire dans'
	),
	'txt-33' => array(
		'EN' => 'Refresh',
		'RO' => 'Aktualisieren',
		'FR' => 'Actualiser'
	),
	'txt-34' => array(
		'EN' => 'You',
		'RO' => 'DU',
		'FR' => 'Toi'
	),
	'txt-35' => array(
		'EN' => 'Duration',
		'RO' => 'Dauer',
		'FR' => 'Durée'
	),
	'txt-36' => array(
		'EN' => 'Delete',
		'RO' => 'Löschen',
		'FR' => 'Suprimer'
	),
	'txt-37' => array(
		'EN' => 'Founded',
		'RO' => 'Gedründet',
		'FR' => 'Trouvé'
	),
	'txt-38' => array(
		'EN' => 'Title',
		'RO' => 'Titel',
		'FR' => 'Titre'
	),
	'txt-39' => array(
		'EN' => 'Message',
		'RO' => 'Nachricht',
		'FR' => 'Message'
	),
	'txt-40' => array(
		'EN' => 'Back',
		'RO' => 'Zurück',
		'FR' => 'Retour'
	),
	'txt-41' => array(
		'EN' => 'All',
		'RO' => 'Alle',
		'FR' => 'Tous'
	),
	'txt-42' => array(
		'EN' => 'Reset',
		'RO' => 'Reset',
		'FR' => 'Reset'
	),
	'txt-43' => array(
		'EN' => 'Players',
		'RO' => 'Spieler',
		'FR' => 'Joueurs'
	),
	'txt-44' => array(
		'EN' => 'Join',
		'RO' => 'Beitreten',
		'FR' => 'Joindre'
	),
	'txt-45' => array(
		'EN' => 'seconds',
		'RO' => 'Sekunden',
		'FR' => 'secondes'
	),
	'txt-46' => array(
		'EN' => 'Loading...',
		'RO' => 'Lade...',
		'FR' => 'Chargement...'
	),
	'txt-47' => array(
		'EN' => 'Upload',
		'RO' => 'Hochladen',
		'FR' => 'Upload'
	),
	'txt-48' => array(
		'EN' => 'Time left until the end of the round',
		'RO' => 'Verbleibende Zeit bis zum Ende der Runde',
		'FR' => 'Temps restant avant la fin du round'
	),
	'txt-49' => array(
		'EN' => 'Hide dead players',
		'RO' => 'Tote Spieler nicht zeigen',
		'FR' => 'Cacher les joueurs morts'
	),
	'txt-50' => array(
		'EN' => 'Show dead players',
		'RO' => 'Tote Spieler zeigen',
		'FR' => 'Voir les joueurs morts'
	),
	'txt-51' => array(
		'EN' => 'Welcome to -GAME-!',
		'RO' => 'Herzlich willkommen bei -GAME-!',
		'FR' => 'Bienvenue sur -GAME-!'
	),
	'txt-52' => array(
		'EN' => 'You have to wait <span class="countdown reload">-TIME-</span> seconds!',
		'RO' => 'Du musst noch <span class="countdown reload">-TIME-</span> Sekunden warten!',
		'FR' => 'Vous devez attendre <span class="countdown reload">-TIME-</span> secondes!'
	),
	'txt-53' => array(
		'EN' => 'The list has been updated!',
		'RO' => 'Die Liste wurde aktualisiert!',
		'FR' => 'La liste a été mise à jour!'
	),
	
	// Allgemeine Fehler
	'err-01' => array(
		'EN' => 'You don\'t have enough money!',
		'RO' => 'Du hast nicht genug Geld!',
		'FR' => 'Vous n\'avez pas assez d\'argent!'
	),
	'err-02' => array(
		'EN' => 'Invalid player!',
		'RO' => 'Diesen Spieler gibt es nicht!',
		'FR' => 'Joueur invalide!'
	),
	'err-03' => array(
		'EN' => 'Invalid password!',
		'RO' => 'Passwort ist falsch!',
		'FR' => 'Mot de passe invalide!'
	),
	'err-04' => array(
		'EN' => 'Passwords does not match!',
		'RO' => 'Passwörter stimmen nicht überein!',
		'FR' => 'Le mot de passe n\'est pas correct!'
	),
	'err-05' => array(
		'EN' => 'Are you sure?',
		'RO' => 'Bist du sicher?',
		'FR' => 'Confirmez'
	),
	'err-06' => array(
		'EN' => 'Nothing found!',
		'RO' => 'Nichts gefunden!',
		'FR' => 'Rien trouvé!'
	),
	'err-07' => array(
		'EN' => 'The player is under arrest!',
		'RO' => 'Der Spieler ist im Knast!',
		'FR' => 'Le joueur est arrété!'
	),
	'err-08' => array(
		'EN' => 'Price must be greater than $0',
		'RO' => 'Der Preis sollte schonhöher als 0$ sein.',
		'FR' => 'Le prix doit être supérieur à 0$'
	),
	'err-09' => array(
		'EN' => 'You don\'t have enough coins!',
		'RO' => 'Du hast nicht genug Coins!',
		'FR' => 'Vous n\'avez pas assez de crédits!'
	),
	'err-10' => array(
		'EN' => 'You cannot withdraw so much money!',
		'RO' => 'Du kannst nicht so viel Geld abheben!',
		'FR' => 'Vous ne pouvez pas retirer autant d\'argent!'
	),
	
	// Startseite
	'start-01' => array(
		'EN' => 'Rewards',
		'RO' => 'Belohnung',
		'FR' => 'Récompenses'
	),
	
	// Rüstung
	'armament-01' => array(
		'EN' => 'You got your money back!',
		'RO' => 'Du hast dein Geld zurück bekommen!',
		'FR' => 'Vous avez obtenu votre argent!'
	),
	'armament-02' => array(
		'EN' => 'You bought <b>-AMOUNT- bullets</b>!',
		'RO' => 'Du hast <b>-AMOUNT- Munition</b> gekauft!',
		'FR' => 'Vous avez acheté <b>-AMOUNT- cartouches</b>'
	),
	'armament-03' => array(
		'EN' => 'ERROR!',
		'RO' => 'Aktion konnte nicht ausgeführt werden!',
		'FR' => 'ERREUR'
	),
	'armament-04' => array(
		'EN' => 'You have to buy more than 1 bullet.',
		'RO' => 'Du must schon mehr als nur 1 Munition kaufen.',
		'FR' => 'Vous devez acheter plus de 1 cartouche.'
	),
	'armament-05' => array(
		'EN' => 'There are not enough bullets for sale!',
		'RO' => 'Es gibt nicht genug Munition zum Verkauf!',
		'FR' => 'Il n\'y a pas assez de cartouches à vendre.'
	),
	'armament-06' => array(
		'EN' => '<b>-BULLETS- bullets</b> reserved for you. To complete this order, please click on the correct image in <b><span class="countdown">-TIME-</span> seconds</b>.',
		'RO' => '<b>-BULLETS- Munition</b> sind für dich reserviert. Um diese Bestellung abzuschließen, klicke in <b><span class="countdown">-TIME-</span> Sekunden</b> auf das richtige Bild.',
		'FR' => '<b>-BULLETS- cartouches</b> réservées pour toi. Pour compléter cette commande, click sur la bonne image avant <b><span class="countdown">-TIME-</span> secondes</b>.'
	),
	'armament-07' => array(
		'EN' => 'Unknown error occurred!',
		'RO' => 'Ein Fehler ist aufgetreten.',
		'FR' => 'Erreur inconnue.'
	),
	'armament-08' => array(
		'EN' => 'This weapon is already in use!',
		'RO' => 'Du benutzt diese Waffe bereits!',
		'FR' => 'Cette arme est déjà utilisée!'
	),
	'armament-09' => array(
		'EN' => 'You can use weapon <b>-WEAPON-</b> with <b>-TRAINING- % training</b>.',
		'RO' => 'Du kannst die Waffe <b>-WEAPON-</b> mit <b>-TRAINING- % Training</b> verwenden.',
		'FR' => 'Tu peux utiliser l\'arme <b>-WEAPON-</b> avec <b>-TRAINING- % d\'entrainement</b>.'
	),
	'armament-10' => array(
		'EN' => 'You can\'t buy weapon yet!',
		'RO' => 'Du kannst noch keine Waffe kaufen!',
		'FR' => 'Vous ne pouvez pas acheter cette arme pour l\'instant.'
	),
	'armament-11' => array(
		'EN' => 'There are no more weapons!',
		'RO' => 'Keine weiteren Waffen vefügbar!',
		'FR' => 'l n\'y a plus d\'armes!'
	),
	'armament-12' => array(
		'EN' => 'You bought <b>-WEAPON-</b>.',
		'RO' => 'Du hast <b>-WEAPON-</b> gekauft.',
		'FR' => 'Vous avez acheté <b>-WEAPON-</b>'
	),
	'armament-13' => array(
		'EN' => 'You bought <b>-WEAPON-</b>.',
		'RO' => 'Du hast <b>-WEAPON-</b> gekauft.',
		'FR' => 'Vous avez acheté <b>-WEAPON-</b>'
	),
	'armament-14' => array(
		'EN' => 'Have you trained with the weapon. Training with weapons increased by <b>-PERCENT- %</b>.',
		'RO' => 'Du hast trainiert diese Waffe zu benutzen. Waffentraining hat <b>-PERCENT- %</b> zugenommen.',
		'FR' => 'Etes vous entrainé à utiliser cette arme. L\'entrainement augmente de <b>-PERCENT- %</b>'
	),
	'armament-15' => array(
		'EN' => 'Buy bullets',
		'RO' => 'Munition kaufen',
		'FR' => 'Acheter des cartouches'
	),
	'armament-16' => array(
		'EN' => '<p>No bullets for sale!</p>',
		'RO' => '<p>Keine Munition im Angebot!</p>',
		'FR' => '<p>Pas de cartouches à vendre!</p>'
	),
	'armament-17' => array(
		'EN' => 'There are <b class="yellow">-BULLETS- bullets</b> for sale!',
		'RO' => 'Es sind <b class="yellow">-BULLETS- Munition</b> im Angebot!',
		'FR' => 'Il y a <b class="yellow">-BULLETS- cartouches</b> à vendre!'
	),
	'armament-18' => array(
		'EN' => 'Price for every bullet is: <b>-PRICE- $</b>',
		'RO' => 'Preis pro Munition <b>-PRICE- $</b>',
		'FR' => 'Prix d\'une cartouche: <b>-PRICE- $</b>'
	),
	'armament-19' => array(
		'EN' => 'Bullets',
		'RO' => 'Munition',
		'FR' => 'Cartouches'
	),
	'armament-20' => array(
		'EN' => 'Protection',
		'RO' => 'Schutz',
		'FR' => 'Protection'
	),
	'armament-21' => array(
		'EN' => 'No protection!',
		'RO' => 'Du hast noch keinen Schutz!',
		'FR' => 'Pas de protection!'
	),
	'armament-22' => array(
		'EN' => 'Your protection:',
		'RO' => 'Dein Schutz:',
		'FR' => 'Votre protection:'
	),
	'armament-23' => array(
		'EN' => 'You can buy protection!',
		'RO' => 'Du kannst dir Schutz kaufen!',
		'FR' => 'Vous pouvez acheter de la protection'
	),
	'armament-24' => array(
		'EN' => 'Weapons',
		'RO' => 'Waffen',
		'FR' => 'Armes'
	),
	'armament-25' => array(
		'EN' => '<p>No weapon bought yet!</p>',
		'RO' => '<p>Du hast noch keine Waffe!</p>',
		'FR' => '<p>Aucune arme achetée pour l\'instant!</p>'
	),
	'armament-26' => array(
		'EN' => 'Used weapon: <b>-WEAPON-</b>',
		'RO' => 'Verwende <b>-WEAPON-</b> als Standardwaffe!',
		'FR' => 'Arme utilisée <b>-WEAPON-</b>'
	),
	'armament-27' => array(
		'EN' => 'Training',
		'RO' => 'Training',
		'FR' => 'Entrainement'
	),
	'armament-28' => array(
		'EN' => 'Weapon',
		'RO' => 'Waffe',
		'FR' => 'Arme'
	),
	'armament-29' => array(
		'EN' => 'Select Weapon',
		'RO' => 'Waffe wählen',
		'FR' => 'Sélectionnez une arme'
	),
	'armament-30' => array(
		'EN' => '<p class="red">You have to wait <span class="countdown reload">-TIME-</span> seconds!</p>',
		'RO' => '<p class="red">Du musst <span class="countdown reload">-TIME-</span> Sekunden warten!</p>',
		'FR' => '<p class="red">Vous devez attendre <span class="countdown reload">-TIME-</span> secondes!</p>'
	),
	'armament-31' => array(
		'EN' => '<p>You need to have <b>-PERCENT- %</b> training, if you want to buy another weapon.</p>',
		'RO' => '<p>Du musst <b>-PERCENT- %</b> trainieren, um eine andere Waffe kaufen zu können.</p>',
		'FR' => '<p>Vous devez avoir <b>-PERCENT- %</b> d\'entrainement, si vous souhaitez acheter d\'autres armes.</p>'
	),
	'armament-32' => array(
		'EN' => 'You can buy another weapon',
		'RO' => 'Du kannst eine andere Waffe kaufen',
		'FR' => 'Vous pouvez acheter d\'autres armes'
	),
	
	// Killer
	'asasin-01' => array(
		'EN' => 'Unknown result!',
		'RO' => 'Etwas ist falsch.',
		'FR' => 'Résultat inconnu.'
	),
	'asasin-02' => array(
		'EN' => 'You bought an result. -PLAYER- is located in -CITY-!',
		'RO' => 'Du hast eine Information gekauft -PLAYER- befindet sich in -CITY-!',
		'FR' => 'Vous avez acheté un résultat. -PLAYER- est situé en -CITY-!'
	),
	'asasin-03' => array(
		'EN' => 'Kill Player',
		'RO' => 'Spieler killen',
		'FR' => 'Tue un joueur'
	),
	'asasin-04' => array(
		'EN' => '<p>This function is opened between <b>-START-</b> and <b>-END-</b>. You have to wait <b>-WAIT-</b>.</p>',
		'RO' => '<p>Diese Funktion ist zwischen <b>-START-</b> und <b>-END-</b> aktiv. Du musst noch <b>-WAIT-</b> warten.</p>',
		'FR' => '<p>Cette fonctionnalité est activé entre <b>-START-</b> et <b>-END-</b>. Vous devez attendre <b>-WAIT-</b>.</p>'
	),
	'asasin-05' => array(
		'EN' => 'Find a player',
		'RO' => 'Finde einen Spieler',
		'FR' => 'Localiser un joueur'
	),
	'asasin-06' => array(
		'EN' => '<p>You can\'t kill players yet!</p>',
		'RO' => '<p>Du kannst noch keinen Spieler killen!</p>',
		'FR' => '<p>Vous ne pouvez pas tuer de joueurs pour l\'instant!</p>'
	),
	'asasin-07' => array(
		'EN' => '<p>You need a weapon!</p>',
		'RO' => '<p>Du brauchst eine Waffe dafür!</p>',
		'FR' => '<p>Vous avez besoin d\'un arme!</p>'
	),
	'asasin-08' => array(
		'EN' => 'This function is stopped for the moment!',
		'RO' => 'Diese Funktion ist vorübergehend deaktiviert!',
		'FR' => 'Cette fonctionnalité est stoppée pour l\'instant!'
	),
	'asasin-09' => array(
		'EN' => 'You can\'t kill yourself!',
		'RO' => 'Du kannst dich nicht selbst killen!',
		'FR' => 'Vous ne pouvez pas vous tuer!'
	),
	'asasin-10' => array(
		'EN' => 'You can\'t kill this player yet!',
		'RO' => 'Du kannst diesen Spieler noch nicht killen!',
		'FR' => 'Vous ne pouvez pas tuer ce joueur pour l\'instant!'
	),
	'asasin-11' => array(
		'EN' => 'This player is dead or seriously injured!',
		'RO' => 'Dieser Spieler ist tot oder schwer verletzt!',
		'FR' => 'Ce joueur est mort ou gravement blaissé!'
	),
	'asasin-12' => array(
		'EN' => 'You don\'t have enough bullets!',
		'RO' => 'Du hast nicht genug Munition!',
		'FR' => 'Vous n\'avez pas assez de cartouches!'
	),
	'asasin-13' => array(
		'EN' => 'You have to use minimum 10 bullets!',
		'RO' => 'Du musst mindestens 10 Munition verwenden!',
		'FR' => 'Vous devez utiliser au moins 10 cartouches!'
	),
	'asasin-14' => array(
		'EN' => '-PLAYER- cannot been attacked. You was arrest for <b>-TIME-</b> seconds.',
		'RO' => 'Du konntest -PLAYER- nicht angreifen. Du kommst für <b>-TIME-</b> Sekunden in den Knast.',
		'FR' => '-PLAYER- ne peut être attaqué. Vous êtes arrété pour <b>-TIME-</b> secondes.'
	),
	'asasin-15' => array(
		'EN' => '-PLAYER- cannot been attacked.',
		'RO' => '-PLAYER- konnte nicht angegriffen werden.',
		'FR' => '-PLAYER- ne peut être attaqué.'
	),
	'asasin-16' => array(
		'EN' => '-VICTIM- was killed! You won <b>-CASH- $</b>.',
		'RO' => 'Du hast -VICTIM- gekillt! Du gewinnst <b>-CASH- $</b>.',
		'FR' => '-VICTIM- est mort! Vous gagnez <b>-CASH- $</b>.'
	),
	'asasin-17' => array(
		'EN' => '-VICTIM- could not be killed.! Player lost <b>-LIFE- %</b> life.',
		'RO' => 'Du konntest -VICTIM- nicht killen! Der Spieler hat <b>-LIFE- %</b> Gesundheit verloren.',
		'FR' => '-VICTIM- ne peut pas être tué! Le joueur perd <b>-LIFE- %</b> de sa santé.'
	),
	'asasin-18' => array(
		'EN' => 'You were arrested for <b>-TIME-</b> seconds!',
		'RO' => 'Du kommst für <b>-TIME-</b> Sekunden in den Knast!',
		'FR' => 'Vous avez été arrété pour <b>-TIME-</b> secondes!'
	),
	'asasin-19' => array(
		'EN' => 'Target',
		'RO' => 'Opfer',
		'FR' => 'Cible'
	),
	'asasin-20' => array(
		'EN' => 'Target Rank',
		'RO' => 'Rang des Opfers',
		'FR' => 'Rang de la cible'
	),
	'asasin-21' => array(
		'EN' => '<b>-WEAPON-</b> with <b>-PERCENT- %</b> training',
		'RO' => '<b>-WEAPON-</b> mit <b>-PERCENT- %</b> Training',
		'FR' => '<b>-WEAPON-</b> avec <b>-PERCENT- %</b> d\'entrainement'
	),
	'asasin-22' => array(
		'EN' => 'units',
		'RO' => 'Einheiten',
		'FR' => 'Unités'
	),
	'asasin-23' => array(
		'EN' => 'Kill player',
		'RO' => 'Spieler killen',
		'FR' => 'Assassine le joueur'
	),
	'asasin-24' => array(
		'EN' => 'You have <b>-BULLETS-</b> bullets.',
		'RO' => 'Du hast <b>-BULLETS-</b> Munition.',
		'FR' => 'Vous avez <b>-BULLETS-</b> cartouches.'
	),
	'asasin-25' => array(
		'EN' => 'Mission...',
		'RO' => 'Mission...',
		'FR' => 'Mission...'
	),
	'asasin-26' => array(
		'EN' => 'Bullets',
		'RO' => 'Munition',
		'FR' => 'Cartouches'
	),
	'asasin-27' => array(
		'EN' => 'Next',
		'RO' => 'Weiter',
		'FR' => 'Suivant'
	),
	'asasin-28' => array(
		'EN' => 'Detective have to be hired for over than -TIME- minutes.',
		'RO' => 'Du musst dem Detetiv mehr als -TIME- Minuten geben.',
		'FR' => 'Le détective doit être embauche durant plus de -TIME- minutes.'
	),
	'asasin-29' => array(
		'EN' => 'You cannot hire detective for more than -TIME- minutes.',
		'RO' => 'Du kannst dem Detektiv nich mehr als -TIME- Minuten geben.',
		'FR' => 'Vous ne pouvez pas embaucher un détective plus de -TIME- minutes.'
	),
	'asasin-30' => array(
		'EN' => 'Already search another player!',
		'RO' => 'Du suchst bereits einen Spieler!',
		'FR' => 'Vous cherchez déjà un autre joueur!'
	),
	'asasin-31' => array(
		'EN' => 'You began a search for -PLAYER-. Your search ends in <b>-TIME- minutes</b>.',
		'RO' => 'Die Suche nach -PLAYER- hat begonnen. Die Suche endet in <b>-TIME- Minuten</b>.',
		'FR' => 'Vous débutez la recherche de -PLAYER-. Cette recherche se termine dans  <b>-TIME- minutes</b>'
	),
	'asasin-32' => array(
		'EN' => 'New search',
		'RO' => 'Neue Suche',
		'FR' => 'Nouvelle recherche'
	),
	'asasin-33' => array(
		'EN' => 'Hiring a detective cost <b>-CASH- $</b> per minute.',
		'RO' => 'Einen Detektiv zu beauftragen kostet <b>-CASH- $</b> pro Minute.',
		'FR' => 'Embaucher un détective coute <b>-CASH- $</b> par minute'
	),
	'asasin-34' => array(
		'EN' => '<p>No active searches!</p>',
		'RO' => '<p>Es gibt keine aktiven Suchen!</p>',
		'FR' => '<p>Aucune recherche encours!</p>'
	),
	'asasin-35' => array(
		'EN' => 'Sell results',
		'RO' => 'Ergebnisse verkaufen',
		'FR' => 'Vendre le résultat'
	),
	'asasin-36' => array(
		'EN' => 'Your results',
		'RO' => 'Deine Ergebnisse',
		'FR' => 'Vos résultats'
	),
	'asasin-37' => array(
		'EN' => 'Buy results',
		'RO' => 'Ergebnisse kaufen',
		'FR' => 'Acheter des résultats'
	),
	'asasin-38' => array(
		'EN' => 'Search was interrupted!',
		'RO' => 'Suche wurde unterbrochen!',
		'FR' => 'La recherche a été interrompue!'
	),
	'asasin-39' => array(
		'EN' => 'New Search',
		'RO' => 'Neue Suche',
		'FR' => 'Nouvelle recherche'
	),
	'asasin-40' => array(
		'EN' => 'Result was added to the sale!',
		'RO' => 'Das Ergebnis wurde zum Verkauf hinzugefügt!',
		'FR' => 'Vous mettez le résultat en vente!'
	),
	'asasin-41' => array(
		'EN' => 'The reward must be greater than $0',
		'RO' => 'Die Belohnung muss höher als 0$ liegen!',
		'FR' => 'La récompense doit être supérieur à 0$'
	),
	
	// Statistik (home)
	'stats-01' => array(
		'EN' => 'Total players',
		'RO' => 'Spieler gesamt',
		'FR' => 'Total joueurs'
	),
	'stats-02' => array(
		'EN' => 'Active players',
		'RO' => 'Aktive Spieler',
		'FR' => 'Joueurs actifs'
	),
	'stats-03' => array(
		'EN' => 'Registered today',
		'RO' => 'Heute registriert',
		'FR' => 'Enregistrés aujourd\'hui'
	),
	'stats-04' => array(
		'EN' => 'Online players',
		'RO' => 'Spieler online',
		'FR' => 'Joueurs en ligne'
	),
	
	// Bank
	'banca-01' => array(
		'EN' => 'Your bank went bankrupt, therefore, you have to open another bank account.',
		'RO' => 'Deine Bank ist bankrott gegangen, deshalb musst du ein anderes Bankkonto eröffnen.',
		'FR' => 'Votre banque a fait faillite, vous devez ouvrir un nouveau compte en banque.'
	),
	'banca-02' => array(
		'EN' => '<br />Money from the bank account (-CASH- $) have been added on your account!',
		'RO' => '<br />Das Geld von der Bank (-CASH- $) wurde auf deinem Konto gutgeschrieben!',
		'FR' => '<br />Cet argent en banque (-CASH- $), a été ajouté à votre compte!'
	),
	'banca-03' => array(
		'EN' => 'You already sent a request, therefore, the request has not been sent. <a href="?side=banca&amp;remove">Click here</a> to remove the active application.',
		'RO' => 'Du hast bereits eine Anfrage gesendet, daher wird diese neue Anfrage nicht gesendet. <a href="?side=banca&amp;remove">Klicke hier</a> um die erste Anfrage zu löschen.',
		'FR' => 'Vous avez déjà envoyé une demande, donc cette nouvelle demande ne sera pas envoyée.  <a href="?side=banca&amp;remove">click ici</a> pour suprimer ta première demande.'
	),
	'banca-04' => array(
		'EN' => 'Unknown bank!',
		'RO' => 'Bank nicht vorhanden!',
		'FR' => 'anque inconnue!'
	),
	'banca-05' => array(
		'EN' => 'Request successfully sent!',
		'RO' => 'Deine Anfrage wurde erfolgreich versendet!',
		'FR' => 'Votre requête a été envoyée avec succès'
	),
	'banca-06' => array(
		'EN' => 'Request successfully removed!',
		'RO' => 'Anfrage wurde gelöscht!',
		'FR' => 'Requête suprimée avec succès'
	),
	'banca-07' => array(
		'EN' => 'Open an bank account',
		'RO' => 'Ein Bankkonto eröffnen',
		'FR' => 'Ouvrir un compte banquaire'
	),
	'banca-08' => array(
		'EN' => '<p>To access the bank, you need a bank account.<br />Must submit an application to the banking company. You will receive your account when the company approves the request.</p><p><b>Hint:</b> Some banks may have different registration fees.</p>',
		'RO' => '<p>Um auf die Bank zugreifen zu können, benötigst du ein Bankkonto.<br />Du must einen Antrag bei einer Bank einreichen. Du erhälst dein Konto, wenn diese Bank die Anfrage genehmigt.</p><p><b>Hinweis:</b> Einige Banken haben möglicherweise andere Registrierungsgebühren.</p>',
		'FR' => '<p>Pour accéder à cette banque, il vous faut un compte.<br />Vous devez soumettre une demande d\'adhésion. Un compte vous sera ouvert si la compagnie banquaire accepte votre demande.</p><p><b>Nota:</b> Chaque banque à ses conditions générales d\'adhésion.</p>'
	),
	'banca-09' => array(
		'EN' => 'Submit application',
		'RO' => 'Antrag einreichen',
		'FR' => 'Soumettez votre demande'
	),
	'banca-10' => array(
		'EN' => 'There are -NUM- banks.',
		'RO' => 'Es gibt -NUM- Banken.',
		'FR' => 'Il y a -NUM- banque(s).'
	),
	'banca-11' => array(
		'EN' => 'Bank',
		'RO' => 'Bank',
		'FR' => 'Banque'
	),
	'banca-12' => array(
		'EN' => 'Fee',
		'RO' => 'Gebühren',
		'FR' => 'Taxes'
	),
	'banca-13' => array(
		'EN' => 'The password cannot be the same as the user password.',
		'RO' => 'Das Passwort darf nicht das gleiche wie das Benutzerpasswort sein.',
		'FR' => 'Le mot de passe ne peut pas être le mot de passe de l\'utilisateur du jeu.'
	),
	'banca-14' => array(
		'EN' => 'Password successfully saved!',
		'RO' => 'Passwort wurde gespeichert!',
		'FR' => 'Mot de passe enregistré avec succès!'
	),
	'banca-15' => array(
		'EN' => 'Bank Password',
		'RO' => 'Bank Passwort',
		'FR' => 'Mot de passe de la banque'
	),
	'banca-16' => array(
		'EN' => 'You must choose a password to use bank.',
		'RO' => 'Du musst ein Bank-Passwort für dein Konto anlegen, um die Bank(Dein Konto) nutzen zu können.',
		'FR' => 'Vous devez utiliser un mot de passe pour utiliser la banque'
	),
	'banca-17' => array(
		'EN' => 'Wrong password! Remember, bank password is not the same like your account password.',
		'RO' => 'Falsches Passwort! Denke daran, dass das Bankkennwort nicht mit Ihrem Benutzerkennwort identisch ist.',
		'FR' => 'Mauvais mot de passe! Rappelez vous, le mot de passe de la banque doit être différent que le mot de passe du compte joueur.'
	),
	'banca-18' => array(
		'EN' => 'Successfully logged in!',
		'RO' => 'Erfolgreich eingeloggt!',
		'FR' => 'Connexion réussie'
	),
	'banca-19' => array(
		'EN' => 'A message was sent to your email address!',
		'RO' => 'Es wurde eine Nachricht an deine E-Mail-Adresse gesendet!',
		'FR' => 'Un message vous a été envoyé dans votre boite aux lettres!'
	),
	'banca-20' => array(
		'EN' => 'Login',
		'RO' => 'Anmelden',
		'FR' => 'Login'
	),
	'banca-21' => array(
		'EN' => 'To enter the bank, you must login using your bank password.',
		'RO' => 'Um die Bank zu betreten, musst du sich mit deinem Bankkennwort anmelden.',
		'FR' => 'Pour entrer dans la banque, vous devez vous authentifier avec le mot de passe de banque.'
	),
	'banca-22' => array(
		'EN' => 'You have been disconnected automatically!',
		'RO' => 'Du wurdest automatisch abgemeldet!',
		'FR' => 'Vous êtes déconnecté automatiquement!'
	),
	'banca-23' => array(
		'EN' => 'Account number',
		'RO' => 'Kontonummer',
		'FR' => 'Numéro de compte'
	),
	'banca-24' => array(
		'EN' => 'To complete the change, fill out the form below.',
		'RO' => 'Um die Änderung abzuschließen, fülle bitte das folgende Formular aus.',
		'FR' => 'Pour compléter vos changements, rempissez le formulaire ci-dessous.'
	),
	'banca-25' => array(
		'EN' => 'User password',
		'RO' => 'Benutzer-Passwort',
		'FR' => 'Mot de passe de l\'utilisateur'
	),
	'banca-26' => array(
		'EN' => 'New bank password',
		'RO' => 'Neues Bankpasswort',
		'FR' => 'Nouveau mot de passe de la banque'
	),
	'banca-26' => array(
		'EN' => 'Repeat password',
		'RO' => 'Passwort wiederholen',
		'FR' => 'Répétez le mot de passe'
	),
	'banca-27' => array(
		'EN' => 'If you forgot your bank password, you can reset it here.',
		'RO' => 'Wenn du dein Passwort verloren hast, kannst du es hier zurücksetzen.',
		'FR' => 'Si vous avez perdu votre mot de passe, vous pouvez le réinitialiser ici.'
	),
	'banca-28' => array(
		'EN' => 'You were disconnected from the bank!',
		'RO' => 'Du wurdest von der Bank abgemeldet!',
		'FR' => 'Vous avez été déconnecté de la banque!'
	),
	'banca-29' => array(
		'EN' => 'You closed your bank account!',
		'RO' => 'Du hast dein Bankkonto gekündigt!',
		'FR' => 'Vous avez fermé votre compte en banque!'
	),
	'banca-30' => array(
		'EN' => 'Need to withdraw more than $0',
		'RO' => 'Du musst schon mehr als 0$ abheben.',
		'FR' => 'Vous devez retirer plus que 0$'
	),
	'banca-31' => array(
		'EN' => 'You don\'t have enough money!',
		'RO' => 'Du hast nicht genug Geld!',
		'FR' => 'Vous n\'avez pas assez d\'argent!'
	),
	'banca-32' => array(
		'EN' => 'You withdrew -CASH- $',
		'RO' => 'Du hast -CASH- $ abgehoben.',
		'FR' => 'Vous avez retiré -CASH- $'
	),
	'banca-33' => array(
		'EN' => 'You deposited -CASH- $',
		'RO' => 'Du hast -CASH- $ eingezahlt',
		'FR' => 'Vous avez déposé -CASH- $'
	),
	'banca-34' => array(
		'EN' => 'You need to deposit more than $0',
		'RO' => 'Du musst schon mehr als 0$ einzahlen.',
		'FR' => 'Vous devez déposer plus de 0$'
	),
	'banca-35' => array(
		'EN' => 'You cannot transfer money to yourself!',
		'RO' => 'Du kannst kein Geld an dich selbst überweisen!',
		'FR' => 'Vous ne pouvez pas vous trensférer de l\'argent!'
	),
	'banca-36' => array(
		'EN' => 'The recipient has no bank account!',
		'RO' => 'Der Empfänger hat kein Bankkonto!',
		'FR' => 'Le destinataire n\'a pas de compte banquaire!'
	),
	'banca-37' => array(
		'EN' => 'Select what you want to transfer!',
		'RO' => 'Wähle aus, was du überweisen möchtest!',
		'FR' => 'Sélectionnez ce que vous souhaitez trensférer'
	),
	'banca-38' => array(
		'EN' => 'You have to transfer more than 0',
		'RO' => 'Du musst schon mehr als 0 überweisen.',
		'FR' => 'Vous devez trensférer plus de 0'
	),
	'banca-39' => array(
		'EN' => 'You don\'t have enough money in your bank account!',
		'RO' => 'Du hast nicht genug Geld auf deinem Bankkonto!',
		'FR' => 'Vous n\'avez pas assez d\'argent sur le compte en banque!'
	),
	'banca-40' => array(
		'EN' => 'You don\'t have enough coins!',
		'RO' => 'Du hast nicht genug Coins',
		'FR' => 'Vous n\'avez pas assez de crédits!'
	),
	'banca-41' => array(
		'EN' => 'You can not transfer so many coins. Transfer fee is too high!',
		'RO' => 'Du kannst nichr so viele Coins überweisen. Die Gebühren sind zu hoch!',
		'FR' => 'Vous ne pouvez pas trensférer autant de crédits, les frais sont trop importants!'
	),
	'banca-42' => array(
		'EN' => 'You transferred -AMOUNT- to -PLAYER-!',
		'RO' => 'Du hast -AMOUNT- an -PLAYER- überwiesen!',
		'FR' => 'Vous avez trensféré -AMOUNT- à -PLAYER-!'
	),
	'banca-43' => array(
		'EN' => 'Leave the Bank',
		'RO' => 'Bank verlassen',
		'FR' => 'Quitter la banque'
	),
	'banca-44' => array(
		'EN' => 'Customer name',
		'RO' => 'Kundenname',
		'FR' => 'Nom du client'
	),
	'banca-45' => array(
		'EN' => 'Balance',
		'RO' => 'Kontostand',
		'FR' => 'Solde du compte'
	),
	'banca-46' => array(
		'EN' => 'Banking company',
		'RO' => 'Bankgesellschaft',
		'FR' => 'Compagnie banquaire'
	),
	'banca-47' => array(
		'EN' => 'Transfer fee',
		'RO' => 'Überweisungsgebühren',
		'FR' => 'Taxe de trensfert'
	),
	'banca-48' => array(
		'EN' => 'Fees',
		'RO' => 'Gebühren',
		'FR' => 'Taxes'
	),
	'banca-49' => array(
		'EN' => 'Transfers',
		'RO' => 'Überweisungen',
		'FR' => 'Trensferts'
	),
	'banca-50' => array(
		'EN' => 'Close your bank account',
		'RO' => 'Bankkonto kündigen',
		'FR' => 'Fermer votre compte banquaire'
	),
	'banca-51' => array(
		'EN' => 'Withdraw money',
		'RO' => 'Geld abheben',
		'FR' => 'Retirer de l\'argent'
	),
	'banca-52' => array(
		'EN' => 'Withdraw',
		'RO' => 'Abheben',
		'FR' => 'Retirer'
	),
	'banca-53' => array(
		'EN' => 'Deposit money',
		'RO' => 'Geld einzahlen',
		'FR' => 'Déposer de l\'argent'
	),
	'banca-54' => array(
		'EN' => 'Deposit',
		'RO' => 'Einzahlen',
		'FR' => 'Déposer'
	),
	'banca-55' => array(
		'EN' => 'Last received',
		'RO' => 'Letzter Eingang',
		'FR' => 'Dernier trensfert reçu'
	),
	'banca-56' => array(
		'EN' => 'Received money',
		'RO' => 'Geld erhalten',
		'FR' => 'Argent reçu'
	),
	'banca-57' => array(
		'EN' => 'Transfer money or coins',
		'RO' => 'Überweise Geld oder Coins',
		'FR' => 'Trensférer de l\'argent ou des crédits'
	),
	'banca-58' => array(
		'EN' => 'Transfer fee',
		'RO' => 'Überweisungsgebühren',
		'FR' => 'Taxe de trensfert'
	),
	
	// BlackJack
	'bj-01' => array(
		'EN' => 'The stake must be greater than $0',
		'RO' => 'Der Einsatz muss höher als 0$ sein',
		'FR' => 'L\'enjeu doit être supérieur à 0$'
	),
	'bj-02' => array(
		'EN' => 'You started a game of Blackjack!',
		'RO' => 'Du hast eine Partie Blackjack gestartet!',
		'FR' => 'Vous démarrez votre partie de BlackJack'
	),
	'bj-03' => array(
		'EN' => 'Round no longer available!',
		'RO' => 'Diese Partie ist nicht mehr verfügbar!',
		'FR' => 'Round non disponible!'
	),
	'bj-04' => array(
		'EN' => 'Start a Round',
		'RO' => 'Eine Partie starten',
		'FR' => 'Démarrer le round'
	),
	'bj-05' => array(
		'EN' => 'Stake',
		'RO' => 'Einsatz',
		'FR' => 'Mise'
	),
	'bj-06' => array(
		'EN' => 'You have joined to a game of BlackJack!',
		'RO' => 'Du bist dieser Partie BlackJack beigetreten!',
		'FR' => 'Vous avez rejoint la partie de BlackJack!'
	),
	'bj-07' => array(
		'EN' => 'Start',
		'RO' => 'Partie starten',
		'FR' => 'Commencer'
	),
	'bj-08' => array(
		'EN' => 'Join a round',
		'RO' => 'Der Parie beitreten',
		'FR' => 'Joindre une partie'
	),
	'bj-09' => array(
		'EN' => 'There are no active rounds!',
		'RO' => 'Derzeit keine aktive Partie vorhanden!',
		'FR' => 'Aucune partie active!'
	),
	'bj-10' => array(
		'EN' => 'Round has ended!',
		'RO' => 'Die Parie wurde beendet',
		'FR' => 'La partie se termine!'
	),
	'bj-11' => array(
		'EN' => 'Choosing cards',
		'RO' => 'Karten auswählen',
		'FR' => 'Choisir les cartes'
	),
	'bj-12' => array(
		'EN' => 'Done',
		'RO' => 'Fertig',
		'FR' => 'Fini'
	),
	'bj-13' => array(
		'EN' => 'It was a draw, both players have received their money back!',
		'RO' => 'Es war ein Unentschieden, beide Spieler haben ihr Geld zurückerhalten!',
		'FR' => 'C\'était un match nul, les deux joueurs reçoivent leur argent!'
	),
	'bj-14' => array(
		'EN' => 'Both players lost and  received money back!',
		'RO' => 'Beide Spieler haben verloren und Geld zurückbekommen!',
		'FR' => 'Les deux joueurs ont perdu, vous recevez votre argent!'
	),
	'bj-15' => array(
		'EN' => 'You cannot draw another card. You already have 21 or more!',
		'RO' => 'Du kannst keine weitere Karte ziehen. Du hast bereits 21 oder mehr!',
		'FR' => 'Vous ne pouvez pas tirer une autre carte, vous en avez déjà 21 ou plus!'
	),
	'bj-16' => array(
		'EN' => 'You draw a new card!',
		'RO' => 'Du ziehst eine neue Karte!',
		'FR' => 'Vous tirer une nouvelle carte!'
	),
	'bj-17' => array(
		'EN' => 'You chosen to stop!',
		'RO' => 'Du hast beschlossen aufzuhören!',
		'FR' => 'Vous avez choisi de stopper!'
	),
	'bj-18' => array(
		'EN' => 'Elapsed time',
		'RO' => 'Verstrichene Zeit',
		'FR' => 'Temps écoulé'
	),
	'bj-19' => array(
		'EN' => 'Remaining time',
		'RO' => 'Verbleibende Zeit',
		'FR' => 'Temps restant'
	),
	'bj-20' => array(
		'EN' => 'Dealer',
		'RO' => 'Dealer',
		'FR' => 'Concessionnaire'
	),
	'bj-21' => array(
		'EN' => 'Status',
		'RO' => 'Status',
		'FR' => 'Status'
	),
	'bj-22' => array(
		'EN' => 'Opponent',
		'RO' => 'Gegner',
		'FR' => 'Adversaire'
	),
	'bj-23' => array(
		'EN' => 'Did not choose the cards yet...',
		'RO' => 'Du hast deine Karten noch nicht gewählt...',
		'FR' => 'N\'a pas encore choisi les cartes...'
	),
	'bj-24' => array(
		'EN' => 'You don\'t have any opponent yet!',
		'RO' => 'Du hast noch keinen Gegner!',
		'FR' => 'Vous n\'avez pas d\'adversaire pour le moment!'
	),
	'bj-25' => array(
		'EN' => 'Choose a card',
		'RO' => 'Wähle eine Karte',
		'FR' => 'Choisis une carte'
	),
	'bj-26' => array(
		'EN' => 'Stop',
		'RO' => 'Stop',
		'FR' => 'Stop'
	),
	'bj-27' => array(
		'EN' => 'Past 10 rounds',
		'RO' => 'Letzen 10 Partien',
		'FR' => '10 derniers tours'
	),
	'bj-28' => array(
		'EN' => 'Cards',
		'RO' => 'Karten',
		'FR' => 'Cartes'
	),
	'bj-29' => array(
		'EN' => 'Result',
		'RO' => 'Ergebnis',
		'FR' => 'Résultat'
	),
	'bj-30' => array(
		'EN' => 'won',
		'RO' => 'Gewonnen',
		'FR' => 'Gagné'
	),
	'bj-31' => array(
		'EN' => 'Draw',
		'RO' => 'Unentschieden',
		'FR' => 'Mise'
	),
	'bj-32' => array(
		'EN' => 'You lost',
		'RO' => 'Du hast verloren',
		'FR' => 'Vous avez perdu'
	),
	'bj-33' => array(
		'EN' => 'Over 21',
		'RO' => 'Über 21',
		'FR' => 'Plus de 21'
	),
	'bj-34' => array(
		'EN' => 'You won',
		'RO' => 'Du hast gewonnen',
		'FR' => 'Vous avez gagné'
	),
	'bj-35' => array(
		'EN' => 'Result',
		'RO' => 'Ergebnis',
		'FR' => 'Résultat'
	),
	
	// Bunker
	'buncar-00' => array(
		'EN' => 'Bunker',
		'RO' => 'Bunker',
		'FR' => 'Bunker'
	),
	'buncar-01' => array(
		'EN' => 'You purchased a bunker in <b>-CITY-</b>!',
		'RO' => 'Du hast einen Bunker in <b>-CITY-</b> gekauft!',
		'FR' => 'Vous avez acheté un Bunker en <b>-CITY-</b>!'
	),
	'buncar-02' => array(
		'EN' => 'The player is already in the bunker!',
		'RO' => 'Der Spieler ist schon im Bunker!',
		'FR' => 'Le joueur est déjà dans le bunker!'
	),
	'buncar-03' => array(
		'EN' => 'You don\'t have bunker in <b>-CITY-</b>!',
		'RO' => 'Du hast keinen Bunker in <b>-CITY-</b>!',
		'FR' => 'Vous n\'avez pas de bunker dans <b>-CITY-</b>!'
	),
	'buncar-04' => array(
		'EN' => 'Duration must be at least 1 minute!',
		'RO' => 'Die Dauer muss mindestens 1 Minute betragen!',
		'FR' => 'La durée doit être au moins de 10 minutes!'
	),
	'buncar-05' => array(
		'EN' => 'Duration can not be over -TIME- minutes!',
		'RO' => 'Die Dauer darf nicht länger als -TIME- Minuten sein!',
		'FR' => 'La durée ne peut pas dépasser -TIME- minutes!'
	),
	'buncar-06' => array(
		'EN' => 'You removed a pass!',
		'RO' => 'Du hast einen Pass entfernt!',
		'FR' => 'Vous avez supprimé un laisser-passer!'
	),
	'buncar-07' => array(
		'EN' => 'is on bunker!',
		'RO' => 'ist im Bunker!',
		'FR' => 'est dans le bunker!'
	),
	'buncar-08' => array(
		'EN' => 'You can not give permission to a dead player!',
		'RO' => 'Du kannst einem toten Spieler keine Erlaubnis geben!',
		'FR' => 'Vous ne pouvez pas donner des permissions à un joueur mort'
	),
	'buncar-09' => array(
		'EN' => 'This player has already received permission!',
		'RO' => 'Dieser Spieler hat bereits die Erlaubnis erhalten!',
		'FR' => 'Ce joueur à déjà reçu une permission!'
	),
	'buncar-10' => array(
		'EN' => '-PLAYER- received permission to add you in the bunker!',
		'RO' => '-PLAYER- hat die Erlaubnis erhalten, dich zum Bunker hinzuzufügen!',
		'FR' => '-PLAYER- à reçu une permission pour vous ajouter au bunker!'
	),
	'buncar-11' => array(
		'EN' => 'You left the bunker!',
		'RO' => 'Du hast den Bunker verlassen!',
		'FR' => 'Vous quittez le bunker!'
	),
	'buncar-12' => array(
		'EN' => 'Leave the bunker',
		'RO' => 'Bunker verlassen',
		'FR' => 'Quitter le bunker'
	),
	'buncar-13' => array(
		'EN' => 'You\'re in the bunker for <span class="countdown reload">-TIME-</span> seconds.',
		'RO' => 'Du bist für <span class="countdown reload">-TIME-</span> Sekunden im Bunker.',
		'FR' => 'Vous êtes dans le bunker pendant <span class="countdown reload">-TIME-</span> secondes.'
	),
	'buncar-14' => array(
		'EN' => 'You bought a bunker in -CITY- (<b>-DATE-</b>).',
		'RO' => 'Du hast den Bunker in -CITY- (<b>-DATE-</b>) gekauft.',
		'FR' => 'Vous avez acheté un bunker dans -CITY- (<b>-DATE-</b>).'
	),
	'buncar-15' => array(
		'EN' => 'Add player in bunker',
		'RO' => 'Spieler im Bunker hinzufügen',
		'FR' => 'Ajouter un joueur au bunker'
	),
	'buncar-16' => array(
		'EN' => 'Permissions',
		'RO' => 'Berechtigungen',
		'FR' => 'Permissions'
	),
	'buncar-17' => array(
		'EN' => 'Not received permission to add anyone in the bunker!',
		'RO' => 'Du hast keine Erlaubnis, einen Spieler zum Bunker hinzuzufügen!',
		'FR' => 'Vous n\'avez pas le droit d\'ajouter un joueur au bunker!'
	),
	'buncar-18' => array(
		'EN' => 'Give permissions',
		'RO' => 'Berechtigungen erteilen',
		'FR' => 'Donner la permission'
	),
	'buncar-19' => array(
		'EN' => 'Allow',
		'RO' => 'Erlaubnis',
		'FR' => 'Permettre'
	),
	'buncar-20' => array(
		'EN' => 'Nobody received permission to add you in the bunker!',
		'RO' => 'Niemand hat die Erlaubnis, dich zum Bunker hinzuzufügen!',
		'FR' => 'Personne n\a la permission de vous ajouter au bunker!'
	),
	'buncar-21' => array(
		'EN' => 'Reseived permissions',
		'RO' => 'Erhaltene Berechtigungen',
		'FR' => 'Permissions reçues'
	),
	'buncar-22' => array(
		'EN' => 'You left the bunker!',
		'RO' => 'Du hast den Bunker varlassen!',
		'FR' => 'Vous quittez le bunker!'
	),
	
	// Börse
	'bursa-01' => array(
		'EN' => 'Stocks',
		'RO' => 'Börse',
		'FR' => 'Bourses'
	),
	'bursa-02' => array(
		'EN' => 'Company does not exist!',
		'RO' => 'Diese Firma existiert nicht!',
		'FR' => 'Cette compagnie n\'existe pas!'
	),
	'bursa-03' => array(
		'EN' => 'You must buy at least one stock!',
		'RO' => 'Du musst mindestens eine Aktie kaufen!',
		'FR' => 'Vous devez acheter au moins une action!'
	),
	'bursa-04' => array(
		'EN' => 'You cannot buy more than -MAX- stocks in a company!',
		'RO' => 'Du kannst nicht mehr als -MAX- Aktien bei einem Unternehmen kaufen!',
		'FR' => 'Vous ne pouvez pas acheter plus que -MAX- stocks dans une compagnie!'
	),
	'bursa-05' => array(
		'EN' => 'You bought -NUM- stocks for <b>-CASH- $</b>.',
		'RO' => 'Du hast -NUM- Aktien für <b>-CASH- $</b> gekauft.',
		'FR' => 'Vous avez acheté -NUM- stocks pour <b>-CASH- $</b>.'
	),
	'bursa-06' => array(
		'EN' => 'You must sell at least one stock!',
		'RO' => 'Du musst mindestens 1 Aktie verkaufen!',
		'FR' => 'Vous devez vendre au moins 1 action!'
	),
	'bursa-07' => array(
		'EN' => 'You don\'t have enough stocks!',
		'RO' => 'Du hast nicht genügend Aktien!',
		'FR' => 'Vous n\avez pas assez d\'actions!'
	),
	'bursa-08' => array(
		'EN' => 'You sold -NUM- stocks for <b>-CASH- $</b>.',
		'RO' => 'Du hast -NUM- Aktien für <b>-CASH- $</b> verkauft.',
		'FR' => 'Vous avez vendu -NUM- actions pour <b>-CASH- $</b>'
	),
	'bursa-09' => array(
		'EN' => 'Past changes',
		'RO' => 'Letzte Änderungen',
		'FR' => 'Changements passés'
	),
	'bursa-10' => array(
		'EN' => 'Bought stocks',
		'RO' => 'Gekaufte Aktien',
		'FR' => 'Actions achetés'
	),
	'bursa-11' => array(
		'EN' => 'No change',
		'RO' => 'Keine Änderung',
		'FR' => 'Pas de changement'
	),
	'bursa-12' => array(
		'EN' => 'Stocks',
		'RO' => 'Aktien',
		'FR' => 'Actions'
	),
	
	// Suche
	'cautare-01' => array(
		'EN' => 'Search',
		'RO' => 'Suchen',
		'FR' => 'Chercher'
	),
	'cautare-02' => array(
		'EN' => 'Description',
		'RO' => 'Beschreibung',
		'FR' => 'Description'
	),
	'cautare-03' => array(
		'EN' => 'Results',
		'RO' => 'Rezultate',
		'FR' => 'Résultats'
	),
	
	// Änderungen
	'change-01' => array(
		'EN' => 'Add changelog',
		'RO' => 'Änderung hinzufügen',
		'FR' => 'Ajouter à l\'historique'
	),
	
	// Wettbewerb
	'concurs-01' => array(
		'EN' => 'Contest / Statistics',
		'RO' => 'Wettbewerb / Statistik',
		'FR' => 'Concours / Statistiques'
	),
	'concurs-02' => array(
		'EN' => 'Round',
		'RO' => 'Runde',
		'FR' => 'Round'
	),
	'concurs-03' => array(
		'EN' => 'Total referrals',
		'RO' => 'Einladungen insgesamt',
		'FR' => 'Total Référents'
	),
	'concurs-04' => array(
		'EN' => 'Level reached',
		'RO' => 'Level erreicht',
		'FR' => 'Niveau atteint'
	),
	'concurs-05' => array(
		'EN' => 'Link for invitations',
		'RO' => 'Link für Einladungen',
		'FR' => 'Lien d\'invitation'
	),
	'concurs-06' => array(
		'EN' => 'All ranks',
		'RO' => 'Alle Ränge',
		'FR' => 'Tous les rangs'
	),
	'concurs-07' => array(
		'EN' => 'Level reached',
		'RO' => 'Level erreicht',
		'FR' => 'Niveau recherché'
	),
	'concurs-08' => array(
		'EN' => 'Top 10',
		'RO' => 'Top 10',
		'FR' => 'Top 10'
	),
	'concurs-09' => array(
		'EN' => 'Place',
		'RO' => 'Ort',
		'FR' => 'Localisation'
	),
	'concurs-10' => array(
		'EN' => 'Referrals',
		'RO' => 'Einladungen',
		'FR' => 'Référents'
	),
	'concurs-11' => array(
		'EN' => 'Rewards',
		'RO' => 'Auszeichnungen',
		'FR' => 'Récompenses'
	),
	'concurs-12' => array(
		'EN' => 'This contest is very simple, all you have to do is to invite how many players you can, using your special recruitment link. Are counted only players invited during competition. Win the first 3 players with most recruited players (with or without minimum level reached). The contest runs for periods of 4 weeks, so, the contest is monthly.',
		'RO' => 'Dieser Wettbewerb ist sehr einfach. Du musst lediglich über deinen speziellen Rekrutierungslink so viele Spieler wie möglich einladen. Es werden nur Spieler gezählt, die während des Wettbewerbs eingeladen wurden. Es gewinnen die ersten 3 Spieler mit den meisten angeworbenen Spielern (mit oder ohne erreichtem Mindestlevel). Der Wettbewerb läuft über einen Zeitraum von 4 Wochen, der Wettbewerb ist also monatlich.',
		'FR' => 'Ce concours est très simple, tout ce que vous avez à faire est d\'inviter le nombre de joueurs que vous pouvez, en utilisant votre lien de recrutement spécial.'
	),
	'concurs-13' => array(
		'EN' => '<b>WARN:</b> Do not try to create false accounts or you will be disqualified. All recruited players have to be real.',
		'RO' => '<b>WARUNG:</b> Versuche keine falschen Konten zu erstellen, da du sonst disqualifiziert wirst. Alle angeworbenen Spieler müssen echt sein.',
		'FR' => '<b>ATTENTION:</b> Ne créez pas de comptes fictifs, vous risqueriez d\'être discalifié. Tous les joueurs recrutés doivent être réels.'
	),
	
	// Rennen
	'curse-01' => array(
		'EN' => 'You must choose a driver!',
		'RO' => 'Du musst einen Fahrer auswählen',
		'FR' => 'Vous devez choisir un chauffeur!'
	),
	'curse-02' => array(
		'EN' => 'You must wager at least -CASH- $',
		'RO' => 'Dein Einsatz muss mindestens -CASH- $ betragen',
		'FR' => 'Vous devez miser au moins -CASH- '
	),
	'curse-03' => array(
		'EN' => 'You bet <b>-CASH- $</b> on <b>-DRIVER-</b>!',
		'RO' => 'Du setzt <b>-CASH- $</b> auf <b>-DRIVER-</b>!',
		'FR' => 'Vous pariez <b>-CASH- $</b> sur <b>-DRIVER-</b>!'
	),
	'curse-04' => array(
		'EN' => 'The current Race',
		'RO' => 'Aktuelles Rennen',
		'FR' => 'La course actuelle'
	),
	'curse-05' => array(
		'EN' => 'Races are currently closed!',
		'RO' => 'Die Rennen werden momentan ausgesetzt!',
		'FR' => 'Les courses sont momentanément fermées!'
	),
	'curse-06' => array(
		'EN' => 'No races in progress!',
		'RO' => 'Keine Rennen im Gange!',
		'FR' => 'Aucune course en cours!'
	),
	'curse-07' => array(
		'EN' => 'You have already bet on this race!',
		'RO' => 'Du hast bereits auf dieses Rennen gewettet!',
		'FR' => 'Vous avez déjà parié sur cette course!'
	),
	'curse-08' => array(
		'EN' => 'It is a race active now!',
		'RO' => 'Es ist gerade ein Rennen im Gange!',
		'FR' => 'C\'est une course active!'
	),
	'curse-09' => array(
		'EN' => 'Want to bet?',
		'RO' => 'Willst du wetten?',
		'FR' => 'Vous voulez parier?'
	),
	'curse-10' => array(
		'EN' => 'Driver',
		'RO' => 'Fahrer',
		'FR' => 'Chauffeur'
	),
	'curse-11' => array(
		'EN' => 'Bet',
		'RO' => 'Wette',
		'FR' => 'Pariez'
	),
	'curse-12' => array(
		'EN' => 'Information about car race',
		'RO' => 'Informationen zum Rennen',
		'FR' => 'Informations sur le véhicule de course'
	),
	'curse-13' => array(
		'EN' => 'Car race ends in <span class="yellow">-TIME-</span>.',
		'RO' => 'Das Rennen endet in  <span class="yellow">-TIME-</span>.',
		'FR' => 'La course de voiture se termine dans <span class="yellow">-TIME-</span>.'
	),
	'curse-14' => array(
		'EN' => 'Bets placed',
		'RO' => 'Wetten platziert',
		'FR' => 'Paris placés'
	),
	'curse-15' => array(
		'EN' => 'Total stake',
		'RO' => 'Gesamteinsatz',
		'FR' => 'Mise totale'
	),
	'curse-16' => array(
		'EN' => 'If you win, you will receive <b>-CASH- $</b> and <b>-PROGRESS- % rank progress</b>.',
		'RO' => 'Wenn du gewinnst, erhälst du <b>-CASH- $</b> und <b>-PROGRESS- % Rangfortschritt</b>.',
		'FR' => 'Si vous gagnez, vous recevez <b>-CASH- $</b> et <b>-PROGRESS- % progression de rang</b>.'
	),
	'curse-17' => array(
		'EN' => 'Past races',
		'RO' => 'Letzte Rennen',
		'FR' => 'Courses passées'
	),
	'curse-18' => array(
		'EN' => 'No winners!',
		'RO' => 'Kein Gewinner!',
		'FR' => 'Aucun gagnant!'
	),
	'curse-19' => array(
		'EN' => 'Winners:',
		'RO' => 'Gewinner:',
		'FR' => 'Les gagnants:'
	),
	'curse-20' => array(
		'EN' => 'players',
		'RO' => 'Spieler',
		'FR' => 'Joueurs'
	),
	
	// Mordliste
	'lc-01' => array(
		'EN' => 'Murder list',
		'RO' => 'Mordliste',
		'FR' => 'Liste des mort'
	),
	'lc-02' => array(
		'EN' => 'Period',
		'RO' => 'Zeit',
		'FR' => 'Période'
	),
	'lc-03' => array(
		'EN' => 'hours',
		'RO' => 'Std',
		'FR' => 'heures'
	),
	'lc-04' => array(
		'EN' => 'days',
		'RO' => 'Tage',
		'FR' => 'jours'
	),
	'lc-05' => array(
		'EN' => 'Victim',
		'RO' => 'Opfer',
		'FR' => 'Victime'
	),
	'lc-06' => array(
		'EN' => 'Result',
		'RO' => 'Ergebnis',
		'FR' => 'Résultat'
	),
	'lc-07' => array(
		'EN' => 'Success',
		'RO' => 'Erfolg',
		'FR' => 'Succès'
	),
	'lc-08' => array(
		'EN' => 'Failed',
		'RO' => 'Gescheitert',
		'FR' => 'Ratté'
	),
	'lc-09' => array(
		'EN' => 'No result',
		'RO' => 'Kein Ergebnis',
		'FR' => 'Pas de résultat'
	),
	
	// Autos klauen
	'cars-01' => array(
		'EN' => 'You have to choose the car you want to steal.',
		'RO' => 'Wähle das Auto, was du klauen möchtest.',
		'FR' => 'Vous devez choisir la voiture que vous souhaitez voler'
	),
	'cars-02' => array(
		'EN' => 'You must be at least rank <b>-RANK-</b> to steal the car!',
		'RO' => 'Um dieses Auto klauen zu können, brauchst du mindestens den Rang <b>-RANK-</b>!',
		'FR' => 'Vous devez être au moins au rang <b>-RANK-</b> pour voler la voiture!'
	),
	'cars-03' => array(
		'EN' => 'You have to wait <b><span class="countdown">-TIME-</span> seconds</b>.',
		'RO' => 'Du brauchst <b><span class="countdown">-TIME-</span> Sekunden</b> Zeit um dich für den nächsten Raub vorzubereiten.',
		'FR' => 'Vous devez attendre <b><span class="countdown">-TIME-</span> secondes</b>.'
	),
	'cars-04' => array(
		'EN' => 'Success! You stole a car <b>-CAR-</b> with <b>-POWER- CP</b> and <b>-DAMAGE- % damages</b>.',
		'RO' => 'Super! Du konntest einen <b>-CAR-</b> mit <b>-POWER- PS</b> und <b>-DAMAGE- % Schaden</b> klauen.',
		'FR' => 'Succès! Vous avez volé une voiture <b>-CAR-</b> avec <b>-POWER- CP</b> et <b>-DAMAGE- % de dommages</b>.'
	),
	'cars-05' => array(
		'EN' => 'Failed! Police arrested you for <b>-TIME-</b> seconds.',
		'RO' => 'Gescheitert! Die Polizei steckt dich für <b>-TIME-</b> Sekunden in den Knast.',
		'FR' => 'Ratté! La police vous enferme pendant <b>-TIME-</b> secondes.'
	),
	'cars-06' => array(
		'EN' => 'You could not steal the car!',
		'RO' => 'Du konntest das Auto nicht klauen!',
		'FR' => 'Echec! Vous n\'avez pas réussi à voler la voiture!'
	),
	'cars-07' => array(
		'EN' => 'wait time',
		'RO' => 'Vorbereitungszeit',
		'FR' => 'Attendez'
	),
	'cars-08' => array(
		'EN' => 'chance',
		'RO' => 'Erfolgsrate',
		'FR' => 'chance de réussite'
	),
	'cars-09' => array(
		'EN' => 'Steal car',
		'RO' => 'Auto klauen',
		'FR' => 'Voler la caisse'
	),
	'cars-10' => array(
		'EN' => 'You can try to steal a car now!',
		'RO' => 'Du kannst jetzt versuchen ein Auto zu klauen!',
		'FR' => 'Vous pouvez essayer de voler une voiture maintenant!'
	),
	'cars-11' => array(
		'EN' => 'You made a total of <b>-TOTAL-</b> robberies, of which <span class="yellow bold">-SUCCES-</span> were successfully and <span class="red bold">-ESEC-</span> failed.',
		'RO' => 'Du hast bisher <b>-TOTAL-</b> Autodiebstähle begangen. Davon waren <span class="yellow bold">-SUCCES-</span> Erfolgreich und  <span class="red bold">-ESEC-</span> gingen schief..',
		'FR' => 'Vous avez éffectué un total de <b>-TOTAL-</b> vols, <span class="yellow bold">-SUCCES-</span> avec succès <span class="red bold">-ESEC-</span> ratté.'
	),
	
	// Garage
	'garaj-00' => array(
		'EN' => 'Garage',
		'RO' => 'Garage',
		'FR' => 'Garage'
	),
	'garaj-01' => array(
		'EN' => 'You don\'t have cars in the garage!',
		'RO' => 'Du hast keine Autos in der Garage!',
		'FR' => 'Vous n\'avez pas de voiture dans le garage!'
	),
	'garaj-02' => array(
		'EN' => 'You have -CARS- cars in the garage!',
		'RO' => 'Du hast -CARS- Autos in der Garage!',
		'FR' => 'Vous avez -CARS- voitures au garage!'
	),
	'garaj-03' => array(
		'EN' => 'You must sell at least one car!',
		'RO' => 'Du musst mindestens 1 Auto verkaufen!',
		'FR' => 'Vous devez vendre au moins 1 voiture!'
	),
	'garaj-04' => array(
		'EN' => 'The cars were sold!',
		'RO' => 'Die Autos wurden verkauft!',
		'FR' => 'La voiture a été vendue!'
	),
	'garaj-05' => array(
		'EN' => 'You sold -CARS- cars for <b>-CASH- $</b>',
		'RO' => 'Du hast -CARS- Autos für <b>-CASH- $</b> verkauft',
		'FR' => 'Vous avez vendu -CARS- voitures pour <b>-CASH- $</b>'
	),
	'garaj-06' => array(
		'EN' => 'You must select at least one car!',
		'RO' => 'Du musst mindestens ein Auto auswählen!',
		'FR' => 'Vous devez sélectionner au moins 1 voiture!'
	),
	'garaj-07' => array(
		'EN' => 'Incorrect location!',
		'RO' => 'Falscher Standort!',
		'FR' => 'Localisation incorrecte!'
	),
	'garaj-08' => array(
		'EN' => 'The cars are already in this city!',
		'RO' => 'Die Autos sind schon in dieser Stadt!',
		'FR' => 'Les voitures sont déjà dans la ville!'
	),
	'garaj-09' => array(
		'EN' => 'You haven\'t added any car for sale!',
		'RO' => 'Du hast kein Auto zum Verkauf hinzugefügt!',
		'FR' => 'Vous n\'avez aucun voiture à vendre!'
	),
	'garaj-10' => array(
		'EN' => 'You added -CARS- cars for sale. Price: <b>-PRICE- $</b>',
		'RO' => 'Du hast -CARS- Autos zum Verkauf hinzugefügt. Preis: <b>-PRICE- $</b>',
		'FR' => 'Vous avez ajouté -CARS- voitures à vendre. Prix: <b>-PRICE- $</b>'
	),
	'garaj-11' => array(
		'EN' => 'You haven\'t repaired cars!',
		'RO' => 'Du hast keine Autos repariert!',
		'FR' => 'Vous n\'avez pas réparé la voiture!'
	),
	'garaj-12' => array(
		'EN' => 'You have repaired -CARS- cars and you paid <b>-PRICE- $</b>',
		'RO' => 'Du hast -CARS- Autos repariert und <b>-PRICE- $</b> bezahlt.',
		'FR' => 'Vous avez réparé -CARS- voiture pour <b>-PRICE- $</b>.'
	),
	'garaj-13' => array(
		'EN' => 'Buy cars',
		'RO' => 'Autos kaufen',
		'FR' => 'Achetez des voitures'
	),
	'garaj-14' => array(
		'EN' => 'There\'s no car in the garage!',
		'RO' => 'Es ist kein Auto in der Garage!',
		'FR' => 'l n\'y a pas de voiture dans le garage!'
	),
	'garaj-15' => array(
		'EN' => 'Model',
		'RO' => 'Modell',
		'FR' => 'Modele'
	),
	'garaj-16' => array(
		'EN' => 'Horsepower',
		'RO' => 'Leistung',
		'FR' => 'Puissance Chv.'
	),
	'garaj-17' => array(
		'EN' => 'Damages',
		'RO' => 'Schäden',
		'FR' => 'Dommages'
	),
	'garaj-18' => array(
		'EN' => 'Value',
		'RO' => 'Wert',
		'FR' => 'Valeur'
	),
	'garaj-19' => array(
		'EN' => 'HP',
		'RO' => 'PS',
		'FR' => 'HP'
	),
	'garaj-20' => array(
		'EN' => 'Sell all cars',
		'RO' => 'Alle Autos verkaufen',
		'FR' => 'Vendre toutes les voitures'
	),
	'garaj-21' => array(
		'EN' => 'Move',
		'RO' => 'Umzug',
		'FR' => 'Déplacer'
	),
	'garaj-22' => array(
		'EN' => 'Repair',
		'RO' => 'Reparieren',
		'FR' => 'Réparer'
	),
	'garaj-23' => array(
		'EN' => 'Add for sale',
		'RO' => 'Zum Verkauf hinzufügen',
		'FR' => 'Ajouter à la vente'
	),
	'garaj-24' => array(
		'EN' => 'Where you move the cars?',
		'RO' => 'Wohin sollen die Autos umziehen?',
		'FR' => 'Ou déplacez vous les voitures?'
	),
	'garaj-25' => array(
		'EN' => 'You will receive -PERCENT- %',
		'RO' => 'Du bekommst -PERCENT- % des Preises.',
		'FR' => 'Vous recevrez -PERCENT- %'
	),
	'garaj-26' => array(
		'EN' => 'All garages',
		'RO' => 'Alle Garagen',
		'FR' => 'Tous les garages'
	),
	'garaj-27' => array(
		'EN' => 'cars',
		'RO' => 'Autos',
		'FR' => 'voitures'
	),
	'garaj-28' => array(
		'EN' => 'Car',
		'RO' => 'Auto',
		'FR' => 'Voiture'
	),
	'garaj-29' => array(
		'EN' => 'I couldn\'t buy the car. Maybe someone else bought it sooner!',
		'RO' => 'Ich konnte das Auto nicht kaufen. Vielleicht war jemand anders schneller!',
		'FR' => 'Je ne peux pas acheter la voiture, peut être quelcun d\'autre l\'a acheté plus tôt!'
	),
	'garaj-30' => array(
		'EN' => 'You bought a car for <b>-CASH- $</b>!',
		'RO' => 'Du hast ein Auto für <b>-CASH- $</b> gekauft!',
		'FR' => 'Vous avez acheté une voiture pour <b>-CASH- $</b>'
	),
	'garaj-31' => array(
		'EN' => 'Cars for sale in',
		'RO' => 'Autos zu verkaufen in',
		'FR' => 'Voiture à vendre'
	),
	'garaj-32' => array(
		'EN' => 'The cars were successfully moved!',
		'RO' => 'Autos sind erfolgreich umgezogen!',
		'FR' => 'Les voitures ont été transférés avec succès!'
	),
	
	// Karte
	'harta-01' => array(
		'EN' => 'Wrong location!',
		'RO' => 'Falscher Standort!',
		'FR' => 'Localisation erroné'
	),
	'harta-02' => array(
		'EN' => 'You have to wait <span class="countdown">-TIME-</span> seconds until you can travel again!',
		'RO' => 'Du brauchst <span class="countdown">-TIME-</span> Sekunden Ruhepause, bis du wieder Reisen kannst!',
		'FR' => 'Vous devez attendre <span class="countdown">-TIME-</span> secondes avant de pouvoir voyager!'
	),
	'harta-03' => array(
		'EN' => 'You used -COINS- coins to reset the wait time!',
		'RO' => 'Du hast -COINS- Coins bezahlt, um deine Ruhepause zu beenden!',
		'FR' => 'Vous avez utilisé -COINS- crédits pour la remise à 0 du compteur d\'attente!'
	),
	'harta-04' => array(
		'EN' => 'You are in this City!',
		'RO' => 'Du bist in dieser Stadt!',
		'FR' => 'Vous êtes dans cette ville!'
	),
	'harta-05' => array(
		'EN' => 'You have bunker!',
		'RO' => 'Du hast einen Bunker!',
		'FR' => 'Vous avez un bunker!'
	),
	'harta-06' => array(
		'EN' => 'You don\'t have bunker!',
		'RO' => 'Du hast keinen Bunker!',
		'FR' => 'Vous n\'avez pas de bunker!'
	),
	'harta-07' => array(
		'EN' => 'travel there',
		'RO' => 'dorthin reisen',
		'FR' => 'y aller'
	),
	'harta-08' => array(
		'EN' => 'You have been traveling to',
		'RO' => 'Okay, du bist jetzt in',
		'FR' => 'Vous avez voyagé à'
	),
	
	// Gefängnis
	'jail-00' => array(
		'EN' => 'Jail',
		'RO' => 'Gefängnis',
		'FR' => 'Prison'
	),
	'jail-01' => array(
		'EN' => 'There are no prisoners in jail!',
		'RO' => 'Es sind keine Gefangenen im Gefängnis!',
		'FR' => 'Il n\'y a aucun prisonier!'
	),
	'jail-02' => array(
		'EN' => 'You paid the bail -COINS- coins.',
		'RO' => 'Du hast eine Kaution von -COINS- Coins bezahlt.',
		'FR' => 'Vous avez payé la caution -COINS- crédits.'
	),
	'jail-03' => array(
		'EN' => 'Reward must be over -CASH- $',
		'RO' => 'Die Belohnung muss höher als -CASH- $ sein',
		'FR' => 'La récompense doit être supérieure à -CASH- $'
	),
	'jail-04' => array(
		'EN' => 'You removed the reward!',
		'RO' => 'Du hast die Belohnung zurückgezogen!',
		'FR' => 'Vous avez eupprimé la récompense!'
	),
	'jail-05' => array(
		'EN' => 'You added -CASH- $ reward!',
		'RO' => 'Du hast -CASH- $ Belohnung ausgerufen!',
		'FR' => 'Vous avez ajouté -CASH- $ de récompense'
	),
	'jail-06' => array(
		'EN' => 'You will be issued from jail over <span class="countdown reload">-TIME-</span> seconds.',
		'RO' => 'Du wirst in <span class="countdown reload">-TIME-</span> Sekunden aus dem Gefängnis entlassen.',
		'FR' => 'Vous êtes libéré de prison <span class="countdown reload">-TIME-</span> secondes.'
	),
	'jail-07' => array(
		'EN' => 'Pay the bail',
		'RO' => 'Kaution bezahlen',
		'FR' => 'Payer la caution'
	),
	'jail-08' => array(
		'EN' => 'Offers a reward',
		'RO' => 'Belohnung anbieten',
		'FR' => 'Offrir une récompense'
	),
	'jail-09' => array(
		'EN' => 'Add reward',
		'RO' => 'Belohnungen hinzufügen',
		'FR' => 'Ajouter une récompense'
	),
	'jail-10' => array(
		'EN' => 'You have to wait <b><span class="countdown reload">-TIME-</span> seconds</b> before removing someone from prison!',
		'RO' => 'Du musst <b><span class="countdown reload">-TIME-</span> Sekunden</b> warten, bevor du jemanden aus dem Gefängnis holen kannst!',
		'FR' => 'Vous devez attendre <b><span class="countdown reload">-TIME-</span> secondes</b> avant de faire évader un prisonnier!'
	),
	'jail-11' => array(
		'EN' => 'The player is no more in jail!',
		'RO' => 'Dieser Spieler ist nicht mehr im Gefängnis',
		'FR' => 'Le joueur n\'est plus en prison!'
	),
	'jail-12' => array(
		'EN' => 'You are under arrest, so, you can\'t remove somebody from the jail!',
		'RO' => 'Du sitzt im Gefängis und kannst keinen anderen aus dem Gefängis holen!',
		'FR' => 'Vous êtes en état d\'arrestation, vous ne pouvez pas libérer quelcun de la prison!'
	),
	'jail-13' => array(
		'EN' => 'You can\'t remove yourself from the jail!',
		'RO' => 'Du kannst dich nicht selbst aus dem Gefängis holen!',
		'FR' => 'Vous ne pouvez pas vous supprimer de la prison!'
	),
	'jail-14' => array(
		'EN' => 'Success! You received -CASH- $ reward!',
		'RO' => 'Du hast es geschafft -PLAYER- aus dem Gefängnis zu holen. Du hast -CASH- $ Belohnung erhalten!',
		'FR' => 'Succès! -PLAYER- Vous recevez -CASH- $ en récompense!'
	),
	'jail-15' => array(
		'EN' => 'You was busted when you tried to remove -PLAYER- from the jail. You are under arrest for -TIME-!',
		'RO' => 'Du wurdest beim Versuch -PLAYER- aus dem Gefängnis zu holen erwischt. Du musst für -TIME- ins Gefängnis!',
		'FR' => 'ous avez été arrété en tantant de faire évader -PLAYER- de prison. Vous êtes emprisonné pendant -TIME-!'
	),
	'jail-16' => array(
		'EN' => 'You failed when you tried to remove -PLAYER- from the jail!',
		'RO' => 'Du hast es nicht geschafft, -PLAYER- aus dem Gefängis zu holen!',
		'FR' => 'Vous avez échoué lors de l\'évasion de -PLAYER-!'
	),
	'jail-17' => array(
		'EN' => 'Reward',
		'RO' => 'Belohnung',
		'FR' => 'Récompense'
	),
	'jail-18' => array(
		'EN' => 'Chance',
		'RO' => 'Chance',
		'FR' => 'Chance de réussite'
	),
	'jail-19' => array(
		'EN' => 'Remaining time',
		'RO' => 'Verbleibende Zeit',
		'FR' => 'Temps restant'
	),
	'jail-20' => array(
		'EN' => 'Try to remove',
		'RO' => 'Ausbruchversuch',
		'FR' => 'Essaie de supprimer'
	),
	'jail-21' => array(
		'EN' => 'You must use at least $10.000',
		'RO' => 'Du musst mindestens 10.000$ dafür verwenden',
		'FR' => 'Vous devez utiliser au moins 10.000$'
	),
	'jail-22' => array(
		'EN' => 'You bribed the guards and got out from the jail!',
		'RO' => 'Du hast die Wachen bestochen und bist aus dem Gefängnis entlassen worden!',
		'FR' => 'Vous avez soudoyé les gardes, vous êtes libéré à présent!'
	),
	'jail-23' => array(
		'EN' => 'You couldn\'t bribe the guard! You have received with -TIME- seconds more in prison!',
		'RO' => 'Die Wachen zu bestechen ging schief! Dafür hat man dir -TIME- Sekunden mehr Knast aufgebrummt!',
		'FR' => 'Votre tentative de corruption n\'a pas fonctionné! vous recevez -TIME- secondes supplémentaires en prison!'
	),
	'jail-24' => array(
		'EN' => 'You can try to bribe the prison guards',
		'RO' => 'Du kannst versuchen, Gefängniswärter zu bestechen',
		'FR' => 'Essayez de corrompre les gardes'
	),
	'jail-25' => array(
		'EN' => 'Submit',
		'RO' => 'Schmieren',
		'FR' => 'Soumettre'
	),
	'jail-26' => array(
		'EN' => 'Are you sure you wanna pay -COINS- coins, to get out of jail?',
		'RO' => 'Möchtest du wirklich -COINS- Coins bezahlen, um aus dem Gefängnis zu kommen?',
		'FR' => 'Etes-vous sûr que vous voulez payer -COINS- crédits pour sortir de prison?'
	),
	
	// Kategorie Suport
	'cats-01' => array(
		'EN' => 'General',
		'RO' => 'Allgemein',
		'FR' => 'General'
	),
	'cats-02' => array(
		'EN' => 'Errors',
		'RO' => 'Fehler',
		'FR' => 'Erreurs'
	),
	'cats-03' => array(
		'EN' => 'Other',
		'RO' => 'Anderes',
		'FR' => 'Autre'
	),
	
	// Coins Historie
	'isc-01' => array(
		'EN' => 'Coins by',
		'RO' => 'Coins via',
		'FR' => 'Crédits par'
	),
	'isc-02' => array(
		'EN' => 'Redeemed vouchers',
		'RO' => 'Eingelöste Gutscheine',
		'FR' => 'Bons échangés'
	),
	'isc-03' => array(
		'EN' => 'Coins',
		'RO' => 'Coins',
		'FR' => 'Crédits'
	),
	'isc-04' => array(
		'EN' => 'Coupon code',
		'RO' => 'Gutscheincode',
		'FR' => 'Codes promo'
	),
	
	// Organisierter Raub
	'jaforg-01' => array(
		'EN' => 'It\'s been too long!',
		'RO' => 'Es ist zu lange her!',
		'FR' => 'Ce fût trop long!'
	),
	'jaforg-02' => array(
		'EN' => 'You have to wait <b><span class="countdown reload">-TIME-</span> seconds</b> before another organized robbery!',
		'RO' => 'Für einen weiteren organisierten Raub brauchst du noch <b><span class="countdown reload">-TIME-</span> Sekunden</b> Vorbereitungszeit!',
		'FR' => 'Vous devez attendre <b><span class="countdown reload">-TIME-</span> secondes</b> avant d\'organiser un autre vol!'
	),
	'jaforg-03' => array(
		'EN' => 'Your rank must be at least <b>-LEVEL-</b>!',
		'RO' => 'Dein Rang muss mindestens <b>-LEVEL-</b> sein!',
		'FR' => 'Ton rang doit au moins être de <b>-LEVEL-</b>!'
	),
	'jaforg-04' => array(
		'EN' => 'You started an organized robbery!',
		'RO' => 'Du hast einen organisierten Raub angezettelt!',
		'FR' => 'Vous avez commencé le vol organisé!'
	),
	'jaforg-05' => array(
		'EN' => 'You haven\'t selected any robbery!',
		'RO' => 'Du hast keinen Raub ausgewählt!',
		'FR' => 'Vous n\'avez pas sélectionné un vol!'
	),
	'jaforg-06' => array(
		'EN' => 'You are not invited to this robbery!',
		'RO' => 'Du wurest nicht zu diesem Raub eingeladen!',
		'FR' => 'Vous n\'êtes pas invité à ce vol!'
	),
	'jaforg-07' => array(
		'EN' => 'You have joined to an organized robbery!',
		'RO' => 'Du hast dich einem organisierten Raub angeschlossen!',
		'FR' => 'Vous avez rejoint le vol organisé!'
	),
	'jaforg-08' => array(
		'EN' => 'Start an Organized Robbery',
		'RO' => 'Starte einen organisierten Raub',
		'FR' => 'Démarrer un vol organisé'
	),
	'jaforg-09' => array(
		'EN' => 'Start Robbery',
		'RO' => 'Raub starten',
		'FR' => 'Démarrer le vol'
	),
	'jaforg-10' => array(
		'EN' => 'Join to a robbery',
		'RO' => 'Beteilige dich an einem Raubüberfall',
		'FR' => 'Joindre un vol organisé'
	),
	'jaforg-11' => array(
		'EN' => 'You wasn\'t invited to any robbery!',
		'RO' => 'Du wurdest zu keinem Raub eingeladen!',
		'FR' => 'Vous n\'êtes invité à aucun vol organisé!'
	),
	'jaforg-12' => array(
		'EN' => 'Job',
		'RO' => 'Job',
		'FR' => 'Travailler'
	),
	'jaforg-13' => array(
		'EN' => 'Your Job',
		'RO' => 'Dein Job',
		'FR' => 'Votre Travail'
	),
	'jaforg-14' => array(
		'EN' => 'You abandoned the robbery!',
		'RO' => 'Du hast den organisierten Raub aufgegeben!',
		'FR' => 'Vous avez abandonné le vol!'
	),
	'jaforg-15' => array(
		'EN' => 'You can\'t abandon the robbery!',
		'RO' => 'du kannst den Raub nicht aufgeben!',
		'FR' => 'Vous ne pouvez pas abandonner le vol!'
	),
	'jaforg-16' => array(
		'EN' => 'You need a car for this robbery',
		'RO' => 'Du brauchst für diesen Raub ein Auto',
		'FR' => 'Vous avez besoin d\'un véhicule pour ce vol'
	),
	'jaforg-17' => array(
		'EN' => 'Select a car!',
		'RO' => 'Auto wählen',
		'FR' => 'Sélectionnez une voiture!'
	),
	'jaforg-18' => array(
		'EN' => 'You selected a car!',
		'RO' => 'Du hast ein Auto gewählt!',
		'FR' => 'Vous avez choisi une voiture!'
	),
	'jaforg-19' => array(
		'EN' => 'You are in this robbery and you chosen a car <b>-CAR-</b> with <b>-POWER- HP</b> and <b>-DAMAGE- % damages</b>.',
		'RO' => 'Du befindest dich in diesem Raubüberfall und hast ein Auto <b>-CAR-</b> mit <b>-POWER- PS</b> und <b>-DAMAGE- % Schaden</b> gewählt.',
		'FR' => 'Pour le vol, vous avez choisi la <b>-CAR-</b> avec <b>-POWER- CP</b> et <b>-DAMAGE- % de dommages</b>.'
	),
	'jaforg-20' => array(
		'EN' => 'You need a weapon for the robbery, so, you can buy a weapon from <a href="?side=armament">Murdershop</a>!',
		'RO' => 'Für diesen Raub ist eine Waffe erforderlich. Du kannst eine im <a href="?side=armament">Waffenladen</a> kaufen!',
		'FR' => 'Vous avez besoin d\'une arme pour ce vol, vous pouvez en acheter <a href="?side=armament">à l\'armurerie</a>!'
	),
	'jaforg-21' => array(
		'EN' => 'You started the robbery!',
		'RO' => 'Du hast einen Raubüberfall gestartet!',
		'FR' => 'Vous démarrez le vol!'
	),
	'jaforg-22' => array(
		'EN' => 'The robbery could not be started!',
		'RO' => 'Der Raub konnte nicht gestartet werden!',
		'FR' => 'Le vol ne peut pas démarrer!'
	),
	'jaforg-23' => array(
		'EN' => 'You can start now!',
		'RO' => 'Alles ist bereit, du kannst jetzt starten!',
		'FR' => 'Vous pouvez commencer maintenant!'
	),
	'jaforg-24' => array(
		'EN' => 'You invited a player!',
		'RO' => 'Du hast einen Spieler eingeladen!',
		'FR' => 'Vous avez invité un joueur!'
	),
	'jaforg-25' => array(
		'EN' => 'Missing players!',
		'RO' => 'Keine Spieler!',
		'FR' => 'Joueurs manquants!'
	),
	'jaforg-26' => array(
		'EN' => 'Invite player',
		'RO' => 'Spieler einladen',
		'FR' => 'Inviter un joueur'
	),
	'jaforg-27' => array(
		'EN' => 'Robbery was completed!',
		'RO' => 'Raub wurde abgeschlossen!',
		'FR' => 'Le vol est terminé!'
	),
	'jaforg-28' => array(
		'EN' => 'Robbery wasn\'t completed!',
		'RO' => 'Raub wurde nicht abgeschlossen!',
		'FR' => 'Le vol n\'est pas complété!'
	),
	'jaforg-29' => array(
		'EN' => 'End the robbery',
		'RO' => 'Diesen Raub beenden',
		'FR' => 'Fin du vol'
	),
	'jaforg-30' => array(
		'EN' => 'Cancel the robbery',
		'RO' => 'Diesen Raub abbrechen',
		'FR' => 'Annuler le vol'
	),
	'jaforg-31' => array(
		'EN' => 'Robbery was canceled!',
		'RO' => 'Raub wurde abgeblasen!',
		'FR' => 'Le vol a été annulé'
	),
	'jaforg-31' => array(
		'EN' => 'Robbery wasn\'t canceled!',
		'RO' => 'Raub wurde nicht abgeblasen!',
		'FR' => 'Le vol n\'a pas été annulé!'
	),
	'jaforg-32' => array(
		'EN' => 'You can\'t use more than -MAX- bullets!',
		'RO' => 'Du kannst max -MAX- Munition verwenden!',
		'FR' => 'Vous ne pouvez pas utiliser plus de -MAX- cartouches!'
	),
	'jaforg-33' => array(
		'EN' => 'You must use at least one bullet!',
		'RO' => 'Du must schon mindestens 1 Munition verwenden!',
		'FR' => 'Vous devez utiliser au moins 1 cartouche!'
	),
	'jaforg-34' => array(
		'EN' => 'You don\'t have enough bullets!',
		'RO' => 'Du hast nicht genug Munition!',
		'FR' => 'Vous n\'avez pas assez de cartouches!'
	),
	'jaforg-35' => array(
		'EN' => 'You used <b>-BULLETS- bullets</b> to calm the hostages!',
		'RO' => 'Du hast <b>-BULLETS- Munition</b> verwendet, um die Geiseln zu besänftigen!',
		'FR' => 'Vous avez utilisé <b>-BULLETS- cartouche(s)</b> pour calmer les otages!'
	),
	'jaforg-36' => array(
		'EN' => 'You couldn\'t use bullets!',
		'RO' => 'Du konntest keine Munition verwenden!',
		'FR' => 'Vous ne pouvez pas utiliser les cartouches!'
	),
	'jaforg-37' => array(
		'EN' => 'Your job is to calm the hostages. You can pull a few bullets to calm them (max: <b>-MAX-</b> bullets).',
		'RO' => 'Dein Job ist es Geiseln zu besänftigen. Du kannst etwas Munition in die Luft ballern. (max <b>-MAX-</b> Munition).',
		'FR' => 'Ton job c\'est de calmer les otages. Vous pouvez vous servir de cartouches pour les calmer! (max <b>-MAX-</b> cartouches).'
	),
	'jaforg-38' => array(
		'EN' => 'Panic among hostages:',
		'RO' => 'Panik unter Geiseln:',
		'FR' => 'Panique chez les otages:'
	),
	'jaforg-39' => array(
		'EN' => 'You have nothing to do yet!',
		'RO' => 'Du hast im Moment nichts zu tun!',
		'FR' => 'Vous n\'avez rien a faire pour l\'instant!'
	),
	'jaforg-40' => array(
		'EN' => 'You broke the wall, but the car was completely destroyed!',
		'RO' => 'Du hast die Wand durchbrochen, aber dein Auto wurde dabei komlett zerstört!',
		'FR' => 'Vous avez fracassé le mur, mais la voiture est complètement détruite!'
	),
	'jaforg-41' => array(
		'EN' => 'You broke the wall and car remained with <b>-DAMAGE- %</b> damages!',
		'RO' => 'Du hast die Wand durchbrochen und das Auto erlitt <b>-DAMAGE- %</b> Schaden!',
		'FR' => 'Vous avez brisé le mur, la voiture passe à <b>-DAMAGE- %</b> de dommages!'
	),
	'jaforg-42' => array(
		'EN' => 'Put the team in the building! Use the car to do that.',
		'RO' => 'Bringe das Team ins Gebäude! Benutze das Auto um es zu tun.',
		'FR' => 'Mettez votre équipe dans le batiment! Servez vous d\'une voiture pour faire cela.'
	),
	'jaforg-43' => array(
		'EN' => 'Destroy the wall',
		'RO' => 'Durchbreche die Wand',
		'FR' => 'Foncer dans le mur'
	),
	'jaforg-44' => array(
		'EN' => 'Started?',
		'RO' => 'Start?',
		'FR' => 'Commencez?'
	),
	'jaforg-45' => array(
		'EN' => 'Robbery',
		'RO' => 'Raub',
		'FR' => 'Vol qualifié'
	),
	'jaforg-46' => array(
		'EN' => 'Hostages panic',
		'RO' => 'Panik der Geiseln',
		'FR' => 'Panique des otages'
	),
	'jaforg-47' => array(
		'EN' => 'Hostages panic',
		'RO' => 'Panik der Geiseln',
		'FR' => 'Panique des otages'
	),
	'jaforg-48' => array(
		'EN' => 'Hostages panic',
		'RO' => 'Panik der Geiseln',
		'FR' => 'Panique des otages'
	),
	'jaforg-49' => array(
		'EN' => 'Equipped',
		'RO' => 'Ausgerüstet',
		'FR' => 'Equipé'
	),
	'jaforg-50' => array(
		'EN' => 'Unequipped',
		'RO' => 'Nicht ausgerüstet',
		'FR' => 'Non équipé'
	),
	'jaforg-51' => array(
		'EN' => 'Team',
		'RO' => 'Team',
		'FR' => 'Equipe'
	),
	
	// Raub
	'jaf-01' => array(
		'EN' => ' and -BULLETS- bullets.',
		'RO' => ' und -BULLETS- Munition.',
		'FR' => ' et -BULLETS- cartouches.'
	),
	'jaf-02' => array(
		'EN' => 'You are in -CITY-.',
		'RO' => 'Du bist in -CITY-.',
		'FR' => 'Vous êtes à -CITY-'
	),
	'jaf-03' => array(
		'EN' => 'There hasn\'t been any recent robbery!',
		'RO' => 'Es hat in letzter Zeit keinen Raub gegeben!',
		'FR' => 'Il n\'y a pas eu de vols recemment!'
	),
	'jaf-04' => array(
		'EN' => 'The last robbery on -CITY- was -TIME-!',
		'RO' => 'Der letzte Raub in -CITY- war -TIME-!',
		'FR' => 'Le dernier vol à -CITY- était à -TIME-!'
	),
	'jaf-05' => array(
		'EN' => 'Do this robbery',
		'RO' => 'Jetzt Klauen',
		'FR' => 'Faites ce vol qualifié'
	),
	'jaf-06' => array(
		'EN' => 'You must be at least rank <b>-RANK-</b> for this robbery!',
		'RO' => 'Du musst mindestens Rang <b>-RANK-</b> haben für diesen Raubüberfall!',
		'FR' => 'Vous devez être au moins au Rang <b>-RANK-</b> pour faire ce vol!'
	),
	'jaf-07' => array(
		'EN' => 'Earn between <b>-CASH1-$</b> and <b>-CASH2-$</b>',
		'RO' => 'Erbeute von <b>-CASH1-$</b> bis <b>-CASH2-$</b>',
		'FR' => 'Gagne entre <b>-CASH1-$</b> et <b>-CASH2-$</b>'
	),
	'jaf-08' => array(
		'EN' => 'Success! The robbery succeeded and you won <b>-CASH- $</b>',
		'RO' => 'Erfolg! Deine Beute sind <b>-CASH- $</b>',
		'FR' => 'Success! The robbery succeeded and you won <b>-CASH- $</b>'
	),
	'jaf-09' => array(
		'EN' => 'Failed! Police arrested you for <b>-TIME-</b> seconds.',
		'RO' => 'Gescheitert! Die Polizei nimmt dich für <b>-TIME-</b> Sekunden fest.',
		'FR' => 'Echoué! La police vous arrète pour <b>-TIME-</b> secondes.'
	),
	'jaf-10' => array(
		'EN' => 'Failed! You hasn\'t managed to do this robbery!',
		'RO' => 'Gescheitert! Du hast diesen Raub nicht geschafft!',
		'FR' => 'Echec! Vous n\'avez pas réussi ce vol!'
	),
	'jaf-11' => array(
		'EN' => 'Robs a container',
		'RO' => 'Raube einen Container aus',
		'FR' => 'Volez un conteneur'
	),
	'jaf-12' => array(
		'EN' => 'Robs an local store',
		'RO' => 'Beklaue einen kleinen Laden in der Nähe',
		'FR' => 'Volez un magasin à proximité'
	),
	'jaf-13' => array(
		'EN' => 'Kidnaps a blonde girl',
		'RO' => 'Entführe eine schöne Blondine',
		'FR' => 'Kidnapez une belle blonde'
	),
	'jaf-14' => array(
		'EN' => 'Blackmail a businessman',
		'RO' => 'Erpresse einen Geschäftsmann',
		'FR' => 'Faites chanter un homme d\'affaires'
	),
	'jaf-15' => array(
		'EN' => 'Steal drinks and sell them on street',
		'RO' => 'Klaue Alkohol und verkaufe ihn auf der Straße',
		'FR' => 'Volez des caisses d\'alcool et revendez les dans la rue'
	),
	'jaf-16' => array(
		'EN' => 'Steal from a businessman',
		'RO' => 'Raube einen Geschäftsmann aus',
		'FR' => 'Volez un homme d\'affaires'
	),
	'jaf-17' => array(
		'EN' => 'Robs a coffee shop',
		'RO' => 'Beklaue einen Cafe-Shop',
		'FR' => 'Volez le coffee-shop'
	),
	'jaf-18' => array(
		'EN' => 'Sell flour as a drug',
		'RO' => 'Verkaufe Mehl als Drogen',
		'FR' => 'Vendez de la farine en disant que c\'est de la drogue'
	),
	'jaf-19' => array(
		'EN' => 'Robs an old lady',
		'RO' => 'Beklaue eine ältere Dame',
		'FR' => 'Fracasse la vieille, et chourre son sac'
	),
	'jaf-20' => array(
		'EN' => 'Steal from tourists',
		'RO' => 'Bestehle Touristen',
		'FR' => 'Fracasse et vole des touristes'
	),
	'jaf-21' => array(
		'EN' => 'Steal cigarettes and sell them on street',
		'RO' => 'Zigaretten klauen und auf der Straße verkaufen',
		'FR' => 'Vole des cigarettes et revends les dans la rue'
	),
	'jaf-22' => array(
		'EN' => 'Steal money from a Visa card',
		'RO' => 'Klaue Geld von einer Visa-Karte',
		'FR' => 'Vole une carte bleue et retire du pognon'
	),
	'jaf-23' => array(
		'EN' => 'Steal neighbor\'s dog',
		'RO' => 'Klaue den Hund deines Nachbarn',
		'FR' => 'Vends le chien de ton voisin'
	),
	'jaf-24' => array(
		'EN' => 'Steal purse from a lady',
		'RO' => 'Klaue die Handtasche einer Dame',
		'FR' => 'Vole le sac d\'une meuf en ville'
	),
	'jaf-25' => array(
		'EN' => 'Steal a car',
		'RO' => 'Klaue ein Auto',
		'FR' => 'Vole une voiture'
	),
	'jaf-26' => array(
		'EN' => 'Steal a scooter',
		'RO' => 'Klaue einen Roller',
		'FR' => 'Vole un scooter'
	),
	'jaf-27' => array(
		'EN' => 'Robs a Casino',
		'RO' => 'Beklaue ein Casino',
		'FR' => 'Braque un casino'
	),
	'jaf-28' => array(
		'EN' => 'Robs a pawn',
		'RO' => 'Beklaue einen Bauern',
		'FR' => 'Vole un pion'
	),
	'jaf-29' => array(
		'EN' => 'Steal from an bank headquarters',
		'RO' => 'Beklaue eine Zentralbank',
		'FR' => 'Braque la banque centrale'
	),
	'jaf-30' => array(
		'EN' => 'Robs a lottery',
		'RO' => 'Beklaue eine Lotterie',
		'FR' => 'Braque la loterie'
	),
	'jaf-31' => array(
		'EN' => 'Robs an teenager',
		'RO' => 'Raube einen Teenager aus',
		'FR' => 'Vole un adolescent en ville'
	),
	'jaf-32' => array(
		'EN' => 'Robs National Bank',
		'RO' => 'Beklaue die Nationalbank',
		'FR' => 'Vole la banque nationale'
	),
	'jaf-33' => array(
		'EN' => 'Robs a international Bank',
		'RO' => 'Beklaue eine internationale Bank',
		'FR' => 'Vole la banque internationale'
	),
	'jaf-34' => array(
		'EN' => 'You lost -PERCENT-% health!',
		'RO' => 'Du hast -PERCENT-% Gesundheit verloren!',
		'FR' => 'Vous avez perdu -PERCENT-% de santé!'
	),
	
	// Kontakte
	'contact-01' => array(
		'EN' => 'You can\'t add yourself in the list!',
		'RO' => 'Du kannst dich nicht selbst in die Liste aufnehmen!',
		'FR' => 'Vous ne pouvez pas vous ajouter à la liste!'
	),
	'contact-02' => array(
		'EN' => 'This player is already added on your list!',
		'RO' => 'Dieser Spieler ist bereits in deiner Liste!',
		'FR' => 'Ce joueur est déjà ajouté à votre liste!'
	),
	'contact-03' => array(
		'EN' => 'The player was added on your list!',
		'RO' => 'Der Spieler wurde hinzugefügt!',
		'FR' => 'Ce joueur a été ajouté à votre liste!'
	),
	
	// Haus
	'casa-00' => array(
		'EN' => 'House',
		'RO' => 'Haus',
		'FR' => 'Maison'
	),
	'casa-01' => array(
		'EN' => 'Marijuana production was completed and you received <b>-GRAME- grams</b> of marijuana.',
		'RO' => 'Die Marihuana-Produktion wurde abgeschlossen. Du erntest <b>-GRAME- Gramm</b> Marihuana',
		'FR' => 'La production de marijuana est complétée, vous recevez <b>-GRAME- grame</b> de marijuana.'
	),
	'casa-02' => array(
		'EN' => 'You must have at least one plant, to produce marijuana!',
		'RO' => 'Du musst mindestens eine Pflanze haben, um Marihuana zu produzieren!',
		'FR' => 'Vous devez avoir au moins une plante pour produire de la marijuana!'
	),
	'casa-03' => array(
		'EN' => 'You started the marijuana production!',
		'RO' => 'Du hast mit der Marihuana-Produktion begonnen!',
		'FR' => 'Production de marijuana débutée'
	),
	'casa-04' => array(
		'EN' => 'You bought a house!',
		'RO' => 'Du hast ein Haus gekauft!',
		'FR' => 'Vous avez acheté une maison'
	),
	'casa-05' => array(
		'EN' => 'Gym?',
		'RO' => 'Fitnessraum?',
		'FR' => 'Salle de sport'
	),
	'casa-06' => array(
		'EN' => 'Buy House',
		'RO' => 'Haus kaufen',
		'FR' => 'Achetez une maison'
	),
	'casa-07' => array(
		'EN' => 'Gym: Yes',
		'RO' => 'Fitnessraum: Ja',
		'FR' => 'Salle de sport: Oui'
	),
	'casa-08' => array(
		'EN' => 'Gym: No',
		'RO' => 'Fitnessraum: Nein',
		'FR' => 'Salle de sport: Non'
	),
	'casa-09' => array(
		'EN' => 'Basement: Yes',
		'RO' => 'Keller: Ja',
		'FR' => 'Sous sol: Oui'
	),
	'casa-10' => array(
		'EN' => 'Basement: No',
		'RO' => 'Keller: Nein',
		'FR' => 'Sous sol: Non'
	),
	'casa-11' => array(
		'EN' => 'Basement',
		'RO' => 'Keller',
		'FR' => 'Sous sol'
	),
	'casa-12' => array(
		'EN' => 'Your house has no basement!',
		'RO' => 'Dein Haus hat keinen Keller!',
		'FR' => 'Votre maison n\'a pas de sous sol!'
	),
	'casa-13' => array(
		'EN' => 'No houses available. You already have the best house!',
		'RO' => 'Keine Häuser verfügbar. Du hast schon das beste Haus!',
		'FR' => 'Aucune autre maison de disponible. Vous avez la meilleure maison qu\'il soit!'
	),
	'casa-14' => array(
		'EN' => 'You must buy at least one plant!',
		'RO' => 'Du musst mindestens eine Pflanze kaufen!',
		'FR' => 'Vous devez acheter au moins une plante!'
	),
	'casa-15' => array(
		'EN' => 'You can\'t buy so many plants!',
		'RO' => 'Du kannst nicht so viele Pflanzen kaufen!',
		'FR' => 'Vous ne pouvez pas acheter autant de plantes!'
	),
	'casa-16' => array(
		'EN' => 'You bought -PLANTS- plants for -CASH- $',
		'RO' => 'Du hast -PLANTS- Pflanzen für -CASH- $ gekauft.',
		'FR' => 'Vous avez acheté -PLANTS- plantes pour -CASH- $'
	),
	'casa-17' => array(
		'EN' => 'Have you already purchased this product!',
		'RO' => 'Du hast dieses Produkt bereits gekauft!',
		'FR' => 'Avez vous déjà acheté ce produit!'
	),
	'casa-18' => array(
		'EN' => 'You bought <b>-PRODUCT-</b>!',
		'RO' => 'Du hast <b>-PRODUCT-</b> gekauft!',
		'FR' => 'Vous avez acheté <b>-PRODUCT-</b>!'
	),
	'casa-19' => array(
		'EN' => 'Must sell at least 1 gram of marijuana!',
		'RO' => 'Du musst mindestens 1 Gramm Marihuana verkaufen!',
		'FR' => 'Vous devez vendre au moins 1 gramme de marijuana!'
	),
	'casa-20' => array(
		'EN' => 'You don\'t have enough marijuana!',
		'RO' => 'Du hast nicht genug Marihuana!',
		'FR' => 'Vous n\'avez pas assez de marijuana!'
	),
	'casa-21' => array(
		'EN' => 'You sold -PLANTS- grams for -CASH- $',
		'RO' => 'Du hast -PLANTS- Gramm Marihuana für -CASH- $ verkauft',
		'FR' => 'Vous avez vendu -PLANTS- grammes de marijuana pour -CASH- $'
	),
	'casa-22' => array(
		'EN' => 'Sell marijuana',
		'RO' => 'Marihuana verkaufen',
		'FR' => 'Vendez la marijuana'
	),
	'casa-23' => array(
		'EN' => 'You have <b>-GRAME- grams</b>!',
		'RO' => 'Du hast <b>-GRAME- Gramm</b> Marihuana.',
		'FR' => 'Vous avez <b>-GRAME- grammes</b> de marijuana.'
	),
	'casa-24' => array(
		'EN' => 'gram',
		'RO' => 'Gramm',
		'FR' => 'grammes'
	),
	'casa-25' => array(
		'EN' => 'Amount',
		'RO' => 'Menge',
		'FR' => 'Montant'
	),
	'casa-26' => array(
		'EN' => 'Buy plants',
		'RO' => 'Pflanzen kaufen',
		'FR' => 'Achetez des plantes'
	),
	'casa-27' => array(
		'EN' => 'Buy up to -PLANTE- plants.',
		'RO' => 'Kaufe bis zu -PLANTE- Pflanzen.',
		'FR' => 'Achetez -PLANTE- plantes.'
	),
	'casa-28' => array(
		'EN' => 'Buy equipment',
		'RO' => 'Ausrüstung kaufen',
		'FR' => 'Achetez un équipement'
	),
	'casa-29' => array(
		'EN' => 'Increase marijuana production',
		'RO' => 'Steigere die Marihuana-Produktion',
		'FR' => 'Augmentez la production de marijuana'
	),
	'casa-30' => array(
		'EN' => 'Marijuana Production',
		'RO' => 'Marihuanaproduktion',
		'FR' => 'Production de Marijuana'
	),
	'casa-31' => array(
		'EN' => 'You have <b>-PLANTE-</b> plants.',
		'RO' => 'Du hast <b>-PLANTE-</b> Marihuana-Pflanzen.',
		'FR' => 'Vous avez <b>-PLANTE-</b> plantes de marijuana.'
	),
	'casa-32' => array(
		'EN' => 'Equipment',
		'RO' => 'Ausrüstung',
		'FR' => 'Equipement'
	),
	'casa-33' => array(
		'EN' => 'Currently produce marijuana in -CITY-. Production ends in -TIME-.',
		'RO' => 'Du produzierst derzeit Marihuana in -CITY-. Produktion endet in -TIME-.',
		'FR' => 'Production actuelle de marijuana à -CITY-. La production se termine dans -TIME-.'
	),
	'casa-34' => array(
		'EN' => 'Marijuana production is stopped now!',
		'RO' => 'Du produzierst im Moment kein Marihuana!',
		'FR' => 'La production de Marijuana est maintenant stoppée!'
	),
	'casa-35' => array(
		'EN' => 'Start production',
		'RO' => 'Produktion starten',
		'FR' => 'Démarrer la production'
	),
	
	// Loterie
	'loto-01' => array(
		'EN' => 'You cannot buy more than -MAX- tickets!',
		'RO' => 'Du kannst max -MAX- Lose kaufen!',
		'FR' => 'Vous ne pouvez pas acheter plus de -MAX- billets!'
	),
	'loto-02' => array(
		'EN' => 'You must buy at least one ticket!',
		'RO' => 'Du musst mindestens 1 Los kaufen!',
		'FR' => 'Vous devez au moins acheter 1 billet!'
	),
	'loto-03' => array(
		'EN' => 'You bought -NUM- tickets!',
		'RO' => 'Du hast -NUM- Lose gekauft!',
		'FR' => 'Vous avez acheté -NUM- billets!'
	),
	'loto-04' => array(
		'EN' => 'Active round',
		'RO' => 'Aktive Runde',
		'FR' => 'Round actif'
	),
	'loto-05' => array(
		'EN' => 'Lottery is closed now!',
		'RO' => 'Die Lotterie ist momentan geschlossen!',
		'FR' => 'La loterie est fermée pour le moment!'
	),
	'loto-06' => array(
		'EN' => 'You have to wait <b>-TIME-</b> until the end of the round!',
		'RO' => 'Es dauert noch <b>-TIME-</b> bis zur Ziehung dieser Runde!',
		'FR' => 'Vous devez attendre <b>-TIME-</b> avant la fin du Round!'
	),
	'loto-07' => array(
		'EN' => 'You bought -TICK- tickets for this round. You can buy up to -MAX- tickets.',
		'RO' => 'Du hast -TICK- Lose für diese Runde gekauft. Du kannst bis zu -MAX- Lose kaufen.',
		'FR' => 'Vous avez acheté -TICK- billets. Vous pouvez acheter encore -MAX- billets.'
	),
	'loto-08' => array(
		'EN' => 'Tickets',
		'RO' => 'Lose',
		'FR' => 'Billets'
	),
	'loto-09' => array(
		'EN' => 'Price for one ticket is <b>-CASH- $</b>',
		'RO' => 'Der Preis pro Los sind <b>-CASH- $</b>',
		'FR' => 'Le prix d\'un seul billet est de <b>-CASH- $</b>'
	),
	'loto-10' => array(
		'EN' => 'Sold Tickets',
		'RO' => 'Tickets verkauft',
		'FR' => 'Tickets vendus'
	),
	'loto-11' => array(
		'EN' => 'Earnings',
		'RO' => 'Ausschüttung',
		'FR' => 'Bénéfices'
	),
	'loto-12' => array(
		'EN' => 'Rank',
		'RO' => 'Rang',
		'FR' => 'Progression'
	),
	'loto-13' => array(
		'EN' => '% from stake',
		'RO' => '% vom Spiel',
		'FR' => '% de la mise'
	),
	'loto-14' => array(
		'EN' => 'Last 5 rounds',
		'RO' => 'Letzten 5 Runden',
		'FR' => '5 derniers tours'
	),
	'loto-15' => array(
		'EN' => 'You have to wait <b><span class="countdown reload">-TIME-</span> seconds</b> before buying more tickets!',
		'RO' => 'Du musst <b><span class="countdown reload">-TIME-</span> Sekunden</b> warten, bis du weitere Lose kaufen kannst!',
		'FR' => 'Vous devez attendre <b><span class="countdown reload">-TIME-</span> secondes</b>!'
	),
	
	// Pferderennen
	'hs-01' => array(
		'EN' => 'Buy tickets for the horse you think will wi the next race.<br /> Every thicket costs $ -TICKET_PRICE-.',
		'RO' => 'Cumpara bilete pentru calul care crezi ca va castiga urmatoarea runda.<br /> Pretul unui bilet este de <b>-TICKET_PRICE- $</b>.<br /> Castigul depinde de calul pe care pariezi precum si de numarul de bilete pariate pe respectivul cal.',
		'FR' => 'Cumpara bilete pentru calul care crezi ca va castiga urmatoarea runda.<br /> Pretul unui bilet este de <b>-TICKET_PRICE- $</b>.<br /> Castigul depinde de calul pe care pariezi precum si de numarul de bilete pariate pe respectivul cal.'
	),
	'hs-02' => array(
		'EN' => 'Ending time',
		'RO' => 'Sfarsitul rundei',
		'FR' => 'Fin du tour'
	),
	'hs-03' => array(
		'EN' => 'Last Race',
		'RO' => 'Ultima Cursa',
		'FR' => 'Last Race'
	),
	'hs-04' => array(
		'EN' => 'Lave 5 Races',
		'RO' => 'Ultimele 5 Curse',
		'FR' => '5 dernières courses'
	),
	'hs-05' => array(
		'EN' => 'Race',
		'RO' => 'Cursa',
		'FR' => 'Race'
	),
	'hs-06' => array(
		'EN' => 'You have to buy at least 1 ticket!',
		'RO' => 'Trebuie sa cumperi cel putin 1 bilet!',
		'FR' => 'Vous devez acheter au moins un billet!'
	),
	'hs-07' => array(
		'EN' => 'You bought -TICKETS- tickets and you have paid -CASH-$',
		'RO' => 'Ai cumparat -TICKETS- bilete in valoare de -CASH-$',
		'FR' => 'Ai cumparat -TICKETS- bilete in valoare de -CASH-$'
	),
	'hs-08' => array(
		'EN' => 'There is no active round!',
		'RO' => 'Nu este nici o runda activa!',
		'FR' => 'Il n\'ya pas de tour ronde!'
	),
	'hs-09' => array(
		'EN' => 'Horses',
		'RO' => 'Cai',
		'FR' => 'Chevaux'
	),
	'hs-10' => array(
		'EN' => 'Speed',
		'RO' => 'Viteza',
		'FR' => 'Accélérer'
	),
	'hs-11' => array(
		'EN' => 'Winchance',
		'RO' => 'Sanse',
		'FR' => 'Chances'
	),
	'hs-12' => array(
		'EN' => 'Condition',
		'RO' => 'Conditie',
		'FR' => 'État'
	),
	'hs-13' => array(
		'EN' => 'Payment',
		'RO' => 'Castig',
		'FR' => 'Gain'
	),
	'hs-14' => array(
		'EN' => 'the bet',
		'RO' => 'bet',
		'FR' => 'parier'
	),
	'hs-15' => array(
		'EN' => 'Reputation',
		'RO' => 'Reputatie',
		'FR' => 'Réputation'
	),
	'hs-16' => array(
		'EN' => 'Owned tickets',
		'RO' => 'Bilete Cumparate',
		'FR' => 'Billets Acheter'
	),
	'hs-17' => array(
		'EN' => 'Buy tickets',
		'RO' => 'Cumpara Bilete',
		'FR' => 'Achat de billets'
	),
	'hs-18' => array(
		'EN' => 'You can\'t buy more than -TICKETS- in the same time!',
		'RO' => 'Nu poti cumpara mai mult de -TICKETS- in acelasi timp!',
		'FR' => 'Nu poti cumpara mai mult de -TICKETS- in acelasi timp!'
	),
	'hs-19' => array(
		'EN' => 'You have to wait <b><span class="countdown reload">-TIME-</span> seconds</b> before buying more tickets!',
		'RO' => 'Trebuie sa astepti <b><span class="countdown reload">-TIME-</span> secunde</b> inainte de a cumpara mai multe bilete!',
		'FR' => 'Trebuie sa astepti <b><span class="countdown reload">-TIME-</span> secunde</b> inainte de a cumpara mai multe bilete!'
	),

	// Kämpfe
	'lupta-01' => array(
		'EN' => 'You performed an exercise!',
		'RO' => 'Du hast mit einer Übung begonnen!',
		'FR' => 'Vous avez éffectué un exercice!'
	),
	'lupta-02' => array(
		'EN' => 'The fight was canceled!',
		'RO' => 'Der Kampf wurde abgesagt!',
		'FR' => 'Ce combat est annulé'
	),
	'lupta-03' => array(
		'EN' => 'The stakes should be more than $1000',
		'RO' => 'Dein Einsatz muss mehr als 1000$ betragen!',
		'FR' => 'Les mises doivent être supérieures à 1000$!'
	),
	'lupta-04' => array(
		'EN' => 'You started a fight!',
		'RO' => 'Du hast einen Kampf begonnen!',
		'FR' => 'Le combat débute!'
	),
	'lupta-05' => array(
		'EN' => 'You can\'t fight with yourself!',
		'RO' => 'Du kannst nicht gegen dich selbst kämpfen!',
		'FR' => 'Vous ne pouvez pas vous battre contre vous même!'
	),
	'lupta-06' => array(
		'EN' => 'Success! You were stronger than -PLAYER- and you won -CASH- $',
		'RO' => 'Erfolg! Du warst stärker als -PLAYER- und hast -CASH- $ gewonnen.',
		'FR' => 'Succès! Vous avez été plus fort que -PLAYER-, vous gagnez -CASH- $'
	),
	'lupta-07' => array(
		'EN' => 'Somebody fought before you!',
		'RO' => 'Jemand hat vor dir gekämpft!',
		'FR' => 'Quelqu\'un a combattu avant vous!'
	),
	'lupta-08' => array(
		'EN' => 'Fighter level',
		'RO' => 'Level des Kämpfers',
		'FR' => 'Niveau du combattant'
	),
	'lupta-09' => array(
		'EN' => 'Progress',
		'RO' => 'Fortschritt',
		'FR' => 'Progression'
	),
	'lupta-10' => array(
		'EN' => 'Won battles',
		'RO' => 'Kämfpe gewonnen',
		'FR' => 'Batailles gagnées'
	),
	'lupta-11' => array(
		'EN' => 'Lost battles',
		'RO' => 'Kämpfe verloren',
		'FR' => 'Batailles perdus'
	),
	'lupta-12' => array(
		'EN' => 'Training',
		'RO' => 'Training',
		'FR' => 'Entrainement'
	),
	'lupta-13' => array(
		'EN' => 'Gym',
		'RO' => 'Fitnessraum',
		'FR' => 'Salle de Sport'
	),
	'lupta-14' => array(
		'EN' => 'Wait time',
		'RO' => 'Dauer',
		'FR' => 'Temps d\'attente'
	),
	'lupta-15' => array(
		'EN' => 'Start Training',
		'RO' => 'Training starten',
		'FR' => 'Débuter l\'entrainement'
	),
	'lupta-16' => array(
		'EN' => 'Start Fight',
		'RO' => 'Kampf beginnen',
		'FR' => 'Commencer le combat'
	),
	'lupta-17' => array(
		'EN' => 'Fights',
		'RO' => 'Kämpfe',
		'FR' => 'Combats'
	),
	'lupta-18' => array(
		'EN' => 'Fight',
		'RO' => 'Kampf'
	),
	'lupta-19' => array(
		'EN' => 'Fight started - ',
		'RO' => 'Kampf begann - ',
		'FR' => 'Combat débuté - '
	),
	
	// Zahlungen
	'mdc-01' => array(
		'EN' => 'PayPal transaction has been canceled!',
		'RO' => 'PayPal-Transaktion wurde abgebrochen!',
		'FR' => 'La trensaction PayPal est annulée!'
	),
	'mdc-02' => array(
		'EN' => 'Thank you! The coins have been added to your account!',
		'RO' => 'Vielen Dank! Die Coins wurden deinem Konto hinzugefügt!',
		'FR' => 'Merci! Les crédits sont ajoutés à votre compte!'
	),
	'mdc-03' => array(
		'EN' => 'You already purchased this product!',
		'RO' => 'Du hast dieses Produkt bereits gekauft!',
		'FR' => 'Vous avez déjà acheté ce produit!'
	),
	'mdc-04' => array(
		'EN' => 'You have no weapon bought!',
		'RO' => 'Du hast keine Waffe gekauft!',
		'FR' => 'Vous n\'avez acheté aucune arme!'
	),
	'mdc-05' => array(
		'EN' => 'You already have 100% weapon training!',
		'RO' => 'Du hast bereits 100% Waffentraining!',
		'FR' => 'Votre entrainement est à 100%!'
	),
	'mdc-06' => array(
		'EN' => 'Training with the current weapon has reached 100% and costed <b>-COINS- coins</b>!',
		'RO' => 'Das Training mit der aktuellen Waffe hat 100% erreicht und kostet <b>-COINS- Coins</b>!',
		'FR' => 'L\'entrainement de cette arme est complété à 100%, il vous a couté: <b>-COINS- crédits</b>!'
	),
	'mdc-07' => array(
		'EN' => 'No active searches!',
		'RO' => 'Es gibt keine aktiven Suchen!',
		'FR' => 'Aucune recherche active!'
	),
	'mdc-08' => array(
		'EN' => 'Level will grow faster next 2 hours! You paid <b>-COINS- coins</b>.',
		'RO' => 'Level wird in den nächsten 2 Stunden schneller steigen! Das kostet dich <b>-COINS- Coins</b>.',
		'FR' => 'Votre niveau va augmenter plus vite pendant 2 heure! Cela vous coute <b>-COINS- crédits</b>.'
	),
	'mdc-09' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and you received <b>-CASH- $</b>',
		'RO' => 'Du hast <b>-COINS- Coins</b> für <b>-CASH- $</b> bezahlt.',
		'FR' => 'Vous échnagez <b>-COINS- crédits</b> contre <b>-CASH- $</b>'
	),
	'mdc-10' => array(
		'EN' => 'You already have the best weapon!',
		'RO' => 'Du hast bereits die beste Waffe!',
		'FR' => 'Vous avez déjà la meilleure arme!'
	),
	'mdc-11' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and you received <b>-OBJECT-</b>',
		'RO' => 'Du hast für <b>-COINS- Coins</b> ein/e/en <b>-OBJECT-</b> gekauft.',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> pour obtenir <b>-OBJECT-</b>'
	),
	'mdc-12' => array(
		'EN' => 'You already have the best protection!',
		'RO' => 'Du hast bereits den besten Schutz!',
		'FR' => 'Vous avez déjà la meilleure protection!'
	),
	'mdc-13' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and you received <b>-OBJECT-</b>',
		'RO' => 'Du hast für <b>-COINS- Coins</b> ein/e/en <b>-OBJECT-</b> gekauft.',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> pour obtenir <b>-OBJECT-</b>'
	),
	'mdc-14' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and you received <b>5 levels</b> at "Fights"!',
		'RO' => 'Du hast für <b>-COINS- Coins</b> zusätzliche <b>5 Level</b> für "Kämpfe" gekauft!',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> vous bénéficiez de <b>5 niveaux supplémentaires</b> au "Combat"!'
	),
	'mdc-15' => array(
		'EN' => 'You already have 100% life!',
		'RO' => 'Du hast bereits 100% Gesundheit!',
		'FR' => 'Votre santé est déjà à 100%!'
	),
	'mdc-16' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and you received <b>100%</b> life!',
		'RO' => 'Du hast für <b>-COINS- Coins</b> deine <b>100%</b> Gesundheit wiederhergestellt!',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> pour remettre votre santé à <b>100%</b>!'
	),
	'mdc-17' => array(
		'EN' => 'Currently you don\'t need to reset the time!',
		'RO' => 'Im Moment musst du die Zeit nicht zurücksetzen!',
		'FR' => 'Actuellement vous n\'avez pas besoin de réinitialiser le temps d\'attente!'
	),
	'mdc-18' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and wait time for "Planned crime" was reset!',
		'RO' => 'Du hast für <b>-COINS- Coins</b> die Wartezeit für "organisierten Raub" zurückgesetzt!',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> pour réinitialiser le temps d\'attente pour le crime organisé!'
	),
	'mdc-19' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and you received <b>-OBJECT- bullets</b>',
		'RO' => 'Du hast für <b>-COINS- Coins</b> <b>-OBJECT- Munition</b> gekauft.',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> pour l\'acquisition de <b>-OBJECT- cartouches</b>'
	),
	'mdc-20' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and wait time for "Wheel of Fortune" was reset!',
		'RO' => 'Du hast für <b>-COINS- Coins</b> die Wartezeit für das "Glücksrad" zurück gesetzt!',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> , le temps d\'attente "Roue de la Fortune" à été réinitialisé!'
	),
	'mdc-21' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and you received <b>-OBJECT-</b> extra games at Roulette',
		'RO' => 'Du hast für <b>-COINS- Coins</b> <b>-OBJECT-</b> Extra-Spiele für das Roulette gekauft.',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> pour recevoir <b>-OBJECT-</b> jeux supplémentaires à le roulette'
	),
	'mdc-22' => array(
		'EN' => 'You already hidden ads!',
		'RO' => 'Du hast die Anzeigen bereits ausgeblendet!',
		'FR' => 'Vous avez déjà supprimé les publicités!'
	),
	'mdc-23' => array(
		'EN' => 'You paid <b>-COINS- coins</b> and you hidden the ads on the site for a week!',
		'RO' => 'Du hast für <b>-COINS- Coins</b> die Anzeigen dieser Seite für 1 Woche ausgeblendet!',
		'FR' => 'Vous avez payé <b>-COINS- crédits</b> et avez supprimé les publicité sur le site durant 1 semaine!'
	),
	'mdc-24' => array(
		'EN' => 'Please enter promotional code!',
		'RO' => 'Bitte den Aktionscode eingeben!',
		'FR' => 'Entrez un code promo!'
	),
	'mdc-25' => array(
		'EN' => 'Entered code is invalid or has already been used!',
		'RO' => 'Eingegebener Code ist ungültig oder wurde bereits verwendet!',
		'FR' => 'Ce code est invalide ou déjà utilisé!'
	),
	'mdc-26' => array(
		'EN' => 'Thank you! -COINS- coins have been added on your account!',
		'RO' => 'Vielen Dank! -COINS- Coins wurden deinem Spielekonto hinzugefügt!',
		'FR' => 'Merci! -COINS- crédits ont été ajoutés à votre compte!'
	),
	'mdc-27' => array(
		'EN' => 'Use coins',
		'RO' => 'Coins benutzen',
		'FR' => 'Utilisez des crédits'
	),
	'mdc-28' => array(
		'EN' => 'You have <b>-COINS-</b> coins!',
		'RO' => 'Du hast <b>-COINS-</b> Coins!',
		'FR' => 'Vous avez <b>-COINS-</b> crédits!'
	),
	'mdc-29' => array(
		'EN' => 'Object',
		'RO' => 'Obiekt',
		'FR' => 'Objet'
	),
	'mdc-30' => array(
		'EN' => 'Buy Coins',
		'RO' => 'Spenden',
		'FR' => 'Achetez des crédits'
	),
	'mdc-31' => array(
		'EN' => 'Buy by',
		'RO' => 'Spende mit',
		'FR' => 'Achetez par'
	),
	'mdc-32' => array(
		'EN' => 'To buy coins via <span class="yellow">SMS</span>, please click on the picture below:',
		'RO' => 'Um Coins per <span class="yellow">SMS</span>zu kaufen, klicke bitte auf das Bild unten:',
		'FR' => 'Pour acheter des crédits par <span class="yellow">SMS</span>, clickez sur l\'image ci dessous:'
	),
	'mdc-33' => array(
		'EN' => 'coins',
		'RO' => 'Coins',
		'FR' => 'crédits'
	),
	'mdc-34' => array(
		'EN' => 'Before buying coins, please read <a href="https://nmafia.unterweltmafia.de/regulament.php">the rules</a>.',
		'RO' => 'Bitte lies vor dem spenden die <a href="https://nmafia.unterweltmafia.de/regulament.php">AGB´s</a>.',
		'FR' => 'Avant de valider une commande, lisez <a href="https://nmafia.unterweltmafia.de/rules.php">le règlement</a>.'
	),
	'mdc-35' => array(
		'EN' => 'If your account has not been credited within the next 24 hours, please contact an administrator. <br><br>You can earn coins for referrals! <a href="?side=min_side&amp;a=ref">Click here</a> for more info.',
		'RO' => 'Sollte Deinem Account die Coins nicht innerhalb der nächsten 24 Stunden gutgeschrieben worden sein, kontaktiere bitte einen Administrator. <br><br>Du kannst auch Coins durch Empfehlungen erhalten! <a href="?side=min_side&amp;a=ref">Klicke hier</a> für weitere Infos..',
		'FR' => 'Si votre compte n\'a pas été crédité dans les prochaines 24 heures, veuillez contacter un administrateur. <br><br>Vous pouvez gagner des crédits de vos filleuls! <a href="?side=min_side&amp;a=ref">click ici</a> pour plus d\'infos.'
	),
	'mdc-36' => array(
		'EN' => 'Redeem Coupon',
		'RO' => 'Gutschein einlösen',
		'FR' => 'Echangez un coupon de réduction'
	),
	'mdc-37' => array(
		'EN' => 'If you received an coins voucher, here can be redeemed.',
		'RO' => 'Wenn du einen Coins-Gutschein erhalten hast, kannst du diesen hier einlösen.',
		'FR' => 'Si vous avez un bon d\'achat ou code promo, il peut être échangé ici.'
	),
	'mdc-38' => array(
		'EN' => 'Code',
		'RO' => 'Code',
		'FR' => 'Code promo'
	),
	'mdc-39' => array(
		'EN' => '100% training',
		'RO' => '100% Training',
		'FR' => '100% entrainement'
	),
	'mdc-40' => array(
		'EN' => 'Increase training with weapon to 100%',
		'RO' => 'Erhöht das Training mit Waffen auf 100%',
		'FR' => 'Accroite votre entrainement avec cette arme à 100%'
	),
	'mdc-41' => array(
		'EN' => 'Fast rank (2 hours)',
		'RO' => 'Rangfortschritt (2 Stunden)',
		'FR' => 'Progression de rang (2 heures)'
	),
	'mdc-42' => array(
		'EN' => 'For 2 hours your level increases with 25% faster. For example, instead of 1% progress, you will receive 1.25%',
		'RO' => 'Für 2 Stunden steigt dein Rang um 25% schneller. Beispielsweise anstelle von 1% Rangfortschritt ->1,25%.',
		'FR' => 'Pendant 2 heures, votre rang augmente de 25% plus vite'
	),
	'mdc-43' => array(
		'EN' => 'You will receive',
		'RO' => 'Du bekomst',
		'FR' => 'Vous recevrez'
	),
	'mdc-44' => array(
		'EN' => 'Help Detective',
		'RO' => 'Hilfsdetektiv',
		'FR' => 'Aide Detective'
	),
	'mdc-45' => array(
		'EN' => 'Instant end for all active searches from "Murder"!',
		'RO' => 'Beendet sofort alle aktiven Suchen bei "Killer"!',
		'FR' => 'Fin immédiates de toutes les recherches "Assassin"!'
	),
	'mdc-46' => array(
		'EN' => '5 fight levels',
		'RO' => '5 Kampf-Level',
		'FR' => '5 niveaux de combats'
	),
	'mdc-47' => array(
		'EN' => 'Get 5 more levels at "Fights"',
		'RO' => 'Du bekommst 5 zusätzliche Levels bei "Kämpfe"',
		'FR' => 'Obtenez 5 niveaux supplémentaires au "Combat"'
	),
	'mdc-48' => array(
		'EN' => '500 bullets',
		'RO' => '500 Munition',
		'FR' => '500 cartouches'
	),
	'mdc-49' => array(
		'EN' => 'You will receive 500 bullets',
		'RO' => 'Du bekommst 500 Munition',
		'FR' => 'Vous recevrez 500 Crétouches'
	),
	'mdc-50' => array(
		'EN' => 'The best weapon',
		'RO' => 'Beste Waffen',
		'FR' => 'La meilleure arme'
	),
	'mdc-51' => array(
		'EN' => 'You will receive the best weapon',
		'RO' => 'Du bekommst die beste Waffe',
		'FR' => 'Vous recevrez la meilleure arme'
	),
	'mdc-52' => array(
		'EN' => 'The best protection',
		'RO' => 'Bester Schutz',
		'FR' => 'La meilleure protection'
	),
	'mdc-53' => array(
		'EN' => 'You will receive the best protection',
		'RO' => 'Du bekommst den besten Schutz',
		'FR' => 'Vous recevrez la meilleure protection'
	),
	'mdc-54' => array(
		'EN' => '100% Health',
		'RO' => '100% Gesundheit',
		'FR' => '100% de santé'
	),
	'mdc-55' => array(
		'EN' => 'You will receive 100% health',
		'RO' => 'Du wirst 100% gesund',
		'FR' => 'Vous recevrez 100% de santé'
	),
	'mdc-56' => array(
		'EN' => 'Hide ads',
		'RO' => 'Anzeigen ausblenden',
		'FR' => 'Cacher la publicité'
	),
	'mdc-57' => array(
		'EN' => 'Hide all ads from game, for 2 weeks',
		'RO' => 'Anzeigen im Spiel werden für 2 Wochen ausgeblendet',
		'FR' => 'Cacher les publicité sur le site pendant 2 semaines'
	),
	'mdc-58' => array(
		'EN' => '150 bullets',
		'RO' => '150 Munition',
		'FR' => '150 cartouches'
	),
	'mdc-59' => array(
		'EN' => 'You will receive 150 bullets',
		'RO' => 'Du bekommst 150 Munition',
		'FR' => 'Vous recevrez 150 Cartouches'
	),
	'mdc-60' => array(
		'EN' => 'Reset time',
		'RO' => 'Uhr zurücksetzen',
		'FR' => 'Initialiser le temps'
	),
	'mdc-61' => array(
		'EN' => 'Reset wait time for "Planned crime"',
		'RO' => 'Setzt die Wartezeit für "organisierten Raub" zurück',
		'FR' => 'Réinitialiser le temps d\'attente des crimes organisés'
	),
	'mdc-62' => array(
		'EN' => 'Reset "Wheel of Fotune"',
		'RO' => '"Glücksrad" zurücksetzen',
		'FR' => 'Reset "Roue de la Fortune'
	),
	'mdc-63' => array(
		'EN' => 'Reset wait time for "Wheel of Fotune"',
		'RO' => 'Setzt die Wartezeit für "Glücksrad" zurück"',
		'FR' => 'Reset temps d\'attente "Roue de la Fortune'
	),
	'mdc-64' => array(
		'EN' => 'Reset "Rulette"',
		'RO' => '"Roulette" zurücksetzen',
		'FR' => 'Réinitialiser "Roulette'
	),
	'mdc-65' => array(
		'EN' => 'Allows you to play for another 20 times at roulette',
		'RO' => 'Ermöglicht, weitere 20 Mal beim Roulette zu spielen',
		'FR' => 'Permetez vous de jouer 20 fois de plus à la "Roulette'
	),
	'mdc-66' => array(
		'EN' => 'Change your username',
		'RO' => 'Benutzername ändern',
		'FR' => 'Changez votre pseudo'
	),
	'mdc-67' => array(
		'EN' => 'Lets you change your player name',
		'RO' => 'Hier kannst du deinen Spielernamen ändern',
		'FR' => 'Vous permet de changer de nom d\'utilisateur'
	),
	'mdc-68' => array(
		'EN' => 'VIP Account',
		'RO' => 'VIP Mitglied',
		'FR' => 'Conpte VIP'
	),
	'mdc-69' => array(
		'EN' => 'VIP account offers you several <a href="?side=c_vip">advantages in the game</a>',
		'RO' => 'Die VIP-Mitgliedschaft bietet verschiedene <a href="?side=c_vip">Vorteile im Spiel</a>',
		'FR' => 'Le compte VIP vous offre plusieurs<a href="?side=c_vip">avantages dans le jeu</a>'
	),
	'mdc-70' => array(
		'EN' => 'You already have VIP account!',
		'RO' => 'Du bist bereits VIP Mitglied!',
		'FR' => 'Vous avez déjà un compte VIP!'
	),
	'mdc-71' => array(
		'EN' => 'You paid -COINS- coins and now you have VIP account',
		'RO' => 'Du hast -COINS- Coins für eine VIP-Mitgliedschaft bezahlt. Du bist nun VIP.',
		'FR' => 'Vous payez -COINS- crédits, pour votre compte VIP'
	),
	'mdc-72' => array(
		'EN' => 'Recruits Hookers',
		'RO' => 'Rekrutiere Prostituierte',
		'FR' => 'Recruter des filles'
	),
	'mdc-73' => array(
		'EN' => 'You will receive -NUM- hookers',
		'RO' => 'Du erhälst -NUM- Prostituierte',
		'FR' => 'Recruter -NUM- filles'
	),
	'mdc-74' => array(
		'EN' => 'You successfully received -NUM- hookers',
		'RO' => 'Du hast -NUM- Prostituierte erhalten',
		'FR' => 'Vous avez réussi à recruter -NUM- filles'
	),
	
	// Nachrichten
	'msg-01' => array(
		'EN' => 'answer',
		'RO' => 'antworten',
		'FR' => 'réponse'
	),
	'msg-02' => array(
		'EN' => 'answers',
		'RO' => 'Antworten',
		'FR' => 'réponses'
	),
	'msg-03' => array(
		'EN' => 'Message was deleted!',
		'RO' => 'Die Nachricht wurde gelöscht!',
		'FR' => 'Le message est supprimé!'
	),
	'msg-04' => array(
		'EN' => 'The message was not sent!',
		'RO' => 'Nachricht wurde nicht gesendet!',
		'FR' => 'Le message n\'a pas été trensmis!'
	),
	'msg-05' => array(
		'EN' => 'Send',
		'RO' => 'Senden',
		'FR' => 'Envoyer'
	),
	'msg-06' => array(
		'EN' => 'Recipients',
		'RO' => 'Empfänger',
		'FR' => 'Destinataire'
	),
	'msg-07' => array(
		'EN' => 'Now',
		'RO' => 'Jetzt',
		'FR' => 'Maintenant'
	),
	'msg-08' => array(
		'EN' => 'There must be at least 2 participants in the conversation!',
		'RO' => 'Es müssen mindestens 2 Teilnehmer im Gespräch sein!',
		'FR' => 'Il doit y avoir au moins 2 participants pour la conversation!'
	),
	'msg-09' => array(
		'EN' => 'Can\'t be more than -MAX- recipients in a conversation!',
		'RO' => 'Es können max -MAX- Teilnehmer in einem Gespräch sein!',
		'FR' => 'Il ne peut pas y avoir plus de -MAX- participants dans la conversation!'
	),
	'msg-10' => array(
		'EN' => 'Title must contain more than -NUM- characters!',
		'RO' => 'Der Titel muss mehr als -NUM- Zeichen enthalten!',
		'FR' => 'Le titre doit contenir plus que -NUM- charactères!'
	),
	'msg-11' => array(
		'EN' => 'Title can\'t contain more than -NUM- characters!',
		'RO' => 'Der Titel darf max -NUM- Zeichen enthalten!',
		'FR' => 'Le titre ne peut contenir plus de -NUM- charactères!'
	),
	'msg-12' => array(
		'EN' => 'Message must contain more than -NUM- characters!',
		'RO' => 'Die Nachricht muss mehr als -NUM- Zeichen enthalten!',
		'FR' => 'Le message doit contenir plus de -NUM- charactère!'
	),
	'msg-13' => array(
		'EN' => 'Message was sent!',
		'RO' => 'Nachricht wurde gesendet!',
		'FR' => 'Le message a été envoyé!'
	),
	'msg-14' => array(
		'EN' => 'Unknown player!',
		'RO' => 'Spieler unbekannt!',
		'FR' => 'Joueur inconnu!'
	),
	'msg-15' => array(
		'EN' => 'Subject',
		'RO' => 'Betreff',
		'FR' => 'Sujet'
	),
	'msg-16' => array(
		'EN' => 'Message',
		'RO' => 'Nachricht',
		'FR' => 'Message'
	),
	'msg-17' => array(
		'EN' => 'Unknown Message!',
		'RO' => 'Nachricht unbekannt!',
		'FR' => 'Message inconnu!'
	),
	'msg-18' => array(
		'EN' => 'Automatically retrieve new messages!',
		'RO' => 'Neue Nachrichten automatisch abrufen!',
		'FR' => 'Nouveau message automatique reçu!'
	),
	'msg-19' => array(
		'EN' => 'Automatically retrieve paused!',
		'RO' => 'Automatisch abrufen pausiert!',
		'FR' => 'Récupérer automatiquement en pause!'
	),
	'msg-20' => array(
		'EN' => 'Automatically retrieve stoped!',
		'RO' => 'Automatisch abrufen gestoppt!',
		'FR' => 'Récupération automatique stoppée!'
	),
	
	// Meine Seite/Hauptquartier
	'min-01' => array(
		'EN' => 'Forums',
		'RO' => 'Forum',
		'FR' => 'Forum'
	),
	'min-02' => array(
		'EN' => 'Action logs',
		'RO' => 'Ereignisse',
		'FR' => 'Journaux'
	),
	'min-03' => array(
		'EN' => 'More info',
		'RO' => 'Mehr Infos',
		'FR' => 'lus d\'infos'
	),
	'min-04' => array(
		'EN' => 'Player name',
		'RO' => 'Spielername',
		'FR' => 'Nom du joueur'
	),
	'min-05' => array(
		'EN' => 'Player ID',
		'RO' => 'Spieler ID',
		'FR' => 'ID du joueur'
	),
	'min-06' => array(
		'EN' => 'Registration date',
		'RO' => 'Registrierungsdatum',
		'FR' => 'Date d\'enregistrement'
	),
	'min-07' => array(
		'EN' => 'Money',
		'RO' => 'Geld',
		'FR' => 'Argent'
	),
	'min-08' => array(
		'EN' => 'Player Stats',
		'RO' => 'Spielerstatistiken',
		'FR' => 'Statistiques du joueur'
	),
	'min-09' => array(
		'EN' => 'Sent messages',
		'RO' => 'Nachrichten gesendet',
		'FR' => 'Messages envoyés'
	),
	'min-10' => array(
		'EN' => 'Forum posts',
		'RO' => 'Forumsbeiträge',
		'FR' => 'Sujets du forum'
	),
	'min-11' => array(
		'EN' => 'Robberies',
		'RO' => 'Raubüberfälle',
		'FR' => 'Vols qualifiés'
	),
	'min-12' => array(
		'EN' => 'Contacts',
		'RO' => 'Kontakte',
		'FR' => 'Contacts'
	),
	'min-13' => array(
		'EN' => 'Jail stats',
		'RO' => 'Gefängnisstatistik',
		'FR' => 'Statistiques de la prison'
	),
	'min-14' => array(
		'EN' => 'Arrested',
		'RO' => 'Verhaftet',
		'FR' => 'Arrété'
	),
	'min-15' => array(
		'EN' => 'times',
		'RO' => 'mal',
		'FR' => 'temps'
	),
	'min-16' => array(
		'EN' => 'sec',
		'RO' => 'Sek',
		'FR' => 'sec'
	),
	'min-17' => array(
		'EN' => 'Guards bribed',
		'RO' => 'Wachen bestochen',
		'FR' => 'Gardes soudoyés'
	),
	'min-18' => array(
		'EN' => 'Successes',
		'RO' => 'Erfolgreich',
		'FR' => 'Succès'
	),
	'min-19' => array(
		'EN' => 'Failures',
		'RO' => 'Fehlgeschlagen',
		'FR' => 'Echoué'
	),
	'min-20' => array(
		'EN' => 'Total',
		'RO' => 'Gesamt',
		'FR' => 'Total'
	),
	'min-21' => array(
		'EN' => 'Rewards',
		'RO' => 'Belohungen',
		'FR' => 'Récompenses'
	),
	'min-22' => array(
		'EN' => 'Earned',
		'RO' => 'Verdient',
		'FR' => 'Gagné'
	),
	'min-23' => array(
		'EN' => 'Loss',
		'RO' => 'Verlust',
		'FR' => 'Perte'
	),
	'min-24' => array(
		'EN' => 'Profit',
		'RO' => 'Gewinn',
		'FR' => 'Profit'
	),
	'min-25' => array(
		'EN' => 'Profit',
		'RO' => 'Gewinn',
		'FR' => 'Profit'
	),
	'min-26' => array(
		'EN' => 'Deficit',
		'RO' => 'Defizit',
		'FR' => 'Déficit'
	),
	'min-27' => array(
		'EN' => 'Escapes',
		'RO' => 'Fluchten',
		'FR' => 'Evasion'
	),
	'min-28' => array(
		'EN' => 'Bribe',
		'RO' => 'Bestechung',
		'FR' => 'Soudoyer'
	),
	'min-29' => array(
		'EN' => 'My Topics',
		'RO' => 'Meine Themen',
		'FR' => 'Mes sujets'
	),
	'min-30' => array(
		'EN' => 'My Replies',
		'RO' => 'Meine Antworten',
		'FR' => 'Mes réponses'
	),
	'min-31' => array(
		'EN' => 'Deleted',
		'RO' => 'Gelöscht',
		'FR' => 'Supprimé'
	),
	'min-32' => array(
		'EN' => 'Answers',
		'RO' => 'Antworten',
		'FR' => 'Réponses'
	),
	'min-33' => array(
		'EN' => 'Author',
		'RO' => 'Autor',
		'FR' => 'Auteur'
	),
	'min-34' => array(
		'EN' => 'Log was not deleted!',
		'RO' => 'Ereignisse wurden nicht gelöscht!',
		'FR' => 'L\'historique n\'a pas été supprimé!'
	),
	'min-35' => array(
		'EN' => 'We found <b>-NUM-</b> logs!',
		'RO' => 'Es wurden <b>-NUM-</b> Ereignisse gefunden!',
		'FR' => 'Trouvé <b>-NUM-</b> logs du journal!'
	),
	'min-36' => array(
		'EN' => 'NEW',
		'RO' => 'NEU',
		'FR' => 'NOUVEAU'
	),
	'min-37' => array(
		'EN' => 'Archived',
		'RO' => 'Archiviert',
		'FR' => 'Archivé'
	),
	'min-38' => array(
		'EN' => 'Filter',
		'RO' => 'Filter',
		'FR' => 'Filtrer'
	),
	'min-39' => array(
		'EN' => 'Archived',
		'RO' => 'Archiviert',
		'FR' => 'Archivé'
	),
	'min-40' => array(
		'EN' => 'Unarchived',
		'RO' => 'Nicht archiviert',
		'FR' => 'désarchivé'
	),
	'min-41' => array(
		'EN' => 'Sessions',
		'RO' => 'Aktivitäten',
		'FR' => 'Sessions'
	),
	'min-42' => array(
		'EN' => 'Quick menu',
		'RO' => 'Schnellmenü',
		'FR' => 'Menu rapide'
	),
	'min-43' => array(
		'EN' => 'Registered IP',
		'RO' => 'Registrierte IP',
		'FR' => 'IP enregistrées'
	),
	'min-44' => array(
		'EN' => 'Last IP',
		'RO' => 'Letzte IP',
		'FR' => 'Dernière IP'
	),
	'min-45' => array(
		'EN' => 'Registered',
		'RO' => 'Registriert',
		'FR' => 'Enregistré'
	),
	'min-46' => array(
		'EN' => 'Active',
		'RO' => 'Aktiv',
		'FR' => 'Active'
	),
	'min-47' => array(
		'EN' => 'Dead',
		'RO' => 'Tod',
		'FR' => 'Mort'
	),
	'min-48' => array(
		'EN' => 'Deactivated',
		'RO' => 'Deaktiviert',
		'FR' => 'Désactivé'
	),
	'min-49' => array(
		'EN' => 'In bank',
		'RO' => 'In der Bank',
		'FR' => 'En banque'
	),
	'min-50' => array(
		'EN' => 'Active sessions',
		'RO' => 'Aktive',
		'FR' => 'Sessions actives'
	),
	'min-51' => array(
		'EN' => 'Inactive sessions',
		'RO' => 'Inaktive',
		'FR' => 'Sessions inactives'
	),
	'min-52' => array(
		'EN' => 'Created',
		'RO' => 'Erstellt',
		'FR' => 'Créé'
	),
	'min-53' => array(
		'EN' => 'You can choose up to <b>-MAX-</b> links!',
		'RO' => 'Du kannst bis zu <b>-MAX-</b> Links auswählen!',
		'FR' => 'Vous pouvez choisir parmis <b>-MAX-</b> liens!'
	),
	'min-54' => array(
		'EN' => 'Enable quick menu?',
		'RO' => 'Schnellmenü aktivieren?',
		'FR' => 'Activer le menu rapide?'
	),
	'min-55' => array(
		'EN' => 'Menu',
		'RO' => 'Menü',
		'FR' => 'Menu'
	),
	'min-56' => array(
		'EN' => 'Save',
		'RO' => 'Speichern',
		'FR' => 'Sauvegarder'
	),
	'min-57' => array(
		'EN' => 'Referrals',
		'RO' => 'Einladungen',
		'FR' => 'Parrains'
	),
	'min-58' => array(
		'EN' => 'Earn coins by inviting friends!',
		'RO' => 'Erhalte Coins, indem du Freunde einlädst!',
		'FR' => 'Gagnez des crédits en invitant vos amis!'
	),
	'min-59' => array(
		'EN' => 'If you need coins, we offer you the opportunity to get free and easy coins. What you have to do it\'s easy, you have to send your special link to other people and for each player registered from your link, which reaches the <b>-RANK-</b>, you will receive <b>-COINS- coins</b>.',
		'RO' => 'Wenn du Coins benötigst, gibt es eine einfache Möglichkeit kostenlose Coins zu erhalten. Du brauchst nur deinen persönlichen Link an deine Freunde zu senden. Für jeden neuen Spieler, der sich mit deinem Link registriert und den Rang <b>-RANK-</b> erreicht, bekommst Du <b>-COINS- Coins</b>.',
		'FR' => 'Vous avez besoin de crédits, nous vous offrons la possiblité d\'en obtenir gratuitement. De quelle manière? Il vous suffit de trensmettre votre lien de parrainage, quand le joueur aura atteint le rang <b>-RANK-</b>, vous recevrez <b>-COINS- Crédits</b>.'
	),
	'min-60' => array(
		'EN' => '<b>WARN:</b> Don\'t try to create fake accounts, else you will be banned.',
		'RO' => '<b>ACHTUNG:</b> Das selbst Erstellen mehrerer Konten ist strengstens untersagt. Wer diese Funktion missbraucht, kann gesperrt werden.',
		'FR' => '<b>ATTENTION:</b> Ne créez pas de compte fictifs, vous seriez banni du jeu.'
	),
	'min-61' => array(
		'EN' => 'Reward for each player',
		'RO' => 'Belohnung für jeden Spieler',
		'FR' => 'Récompense pour chaque joueurs'
	),
	
	// Missionen
	'misiune-00' => array(
		'EN' => 'Mission',
		'RO' => 'Mission',
		'FR' => 'Mission'
	),
	'misiune-01' => array(
		'EN' => 'You start the mission!',
		'RO' => 'Du hast die Mission gestartet!',
		'FR' => 'Vous débutez la mission!'
	),
	'misiune-02' => array(
		'EN' => 'You ended the mission!',
		'RO' => 'Du hast die Mission beendet!',
		'FR' => 'La mission est terminée!'
	),
	'misiune-03' => array(
		'EN' => 'Mission could not be completed!',
		'RO' => 'Mission konnte nicht abgeschlossen werden!',
		'FR' => 'La mission ne peut pas être complétée!'
	),
	'misiune-04' => array(
		'EN' => 'You cannot start more missions simultaneously!',
		'RO' => 'Du kannst nicht mehrere Missionen gleichzeitig starten!',
		'FR' => 'Vous ne pouvez pas débuter plus de missions en simultané!'
	),
	'misiune-05' => array(
		'EN' => 'You started a quick mission!',
		'RO' => 'Du hast eine "schnelle" Mission gestartet!',
		'FR' => 'Vous avez démarré la mission "rapide"!'
	),
	'misiune-06' => array(
		'EN' => 'You have no mission yet!',
		'RO' => 'Du hast noch keine Mission!',
		'FR' => 'ous n\'avez pas de mission pour l\'instant!'
	),
	'misiune-07' => array(
		'EN' => 'To complete the mission, complete the following tasks:',
		'RO' => 'Führe folgende Aufgaben aus, um die Mission abzuschließen:',
		'FR' => 'Pour compléter votre mission, complétez les étapes suivantes:'
	),
	'misiune-08' => array(
		'EN' => 'Finish the mission',
		'RO' => 'Mission beenden',
		'FR' => 'Terminer la mission'
	),
	'misiune-09' => array(
		'EN' => '<b>Rewards: </b> -CASH- $, -BULLETS- bullets, -COINS- coins and rank progress.',
		'RO' => '<b>Belohnung: </b> -CASH- $, -BULLETS- Munition, -COINS- Coins und Rangpunkte.',
		'FR' => '<b>Rewards: </b> -CASH- $, -BULLETS- bullets, -COINS- coins and rank progress.'
	),
	'misiune-10' => array(
		'EN' => 'Start mission',
		'RO' => 'Mission starten',
		'FR' => 'Démarrer la mission'
	),
	'misiune-11' => array(
		'EN' => 'Mission started',
		'RO' => 'Mission hat begonnen',
		'FR' => 'Mission démarrée'
	),
	'misiune-12' => array(
		'EN' => 'Quick missions',
		'RO' => 'Schnelle Missionen',
		'FR' => 'Missions rapides'
	),
	'misiune-13' => array(
		'EN' => 'These missions can be performed several times. Here you can earn money, bullets and rank progress!',
		'RO' => 'Diese Missionen können mehrmals durchgeführt werden. Hier kannst du Geld, Munition und Rangfortschritte erhalten!',
		'FR' => 'Ces missions peuvent être réalisées plusieurs fois, ici vous gagnez de l\'argent, des cartouches et de la progression de rang!'
	),
	'misiune-14' => array(
		'EN' => 'No more missions!',
		'RO' => 'Keine weiteren Missionen!',
		'FR' => 'Pas d\'autres missions!'
	),
	'misiune-15' => array(
		'EN' => 'Max time',
		'RO' => 'Maximale Zeit',
		'FR' => 'Temps maxi'
	),
	'misiune-16' => array(
		'EN' => 'Elapsed time',
		'RO' => 'Verstrichene Zeit',
		'FR' => 'Temps écoulé'
	),
	'misiune-17' => array(
		'EN' => 'Rewards',
		'RO' => 'Belohnung',
		'FR' => 'Récompenses'
	),
	'misiune-18' => array(
		'EN' => 'Latency',
		'RO' => 'Latenz',
		'FR' => 'Latence'
	),
	'misiune-19' => array(
		'EN' => 'rank progress',
		'RO' => 'Rang-Fortschritt',
		'FR' => 'progression de rang'
	),
	'misiune-20' => array(
		'EN' => 'You stolen -NUM- cars',
		'RO' => 'Du hast -NUM- Autos geklaut',
		'FR' => 'Vous avez volé -NUM- voitures'
	),
	'misiune-21' => array(
		'EN' => 'You made -NUM- robberies',
		'RO' => 'Du hast -NUM- Raubüberfälle begangen',
		'FR' => 'Vous avez effectué -NUM- vols'
	),
	'misiune-22' => array(
		'EN' => 'You earned -NUM- from 100 000 $',
		'RO' => 'Du hast -NUM- von 100 000 $ verdient',
		'FR' => 'Vous avez gagné -NUM- 100 000 $'
	),
	'misiune-23' => array(
		'EN' => 'You earned -NUM- from 200 000 $',
		'RO' => 'Du hast -NUM- von 200 000 $ verdient',
		'FR' => 'Vous avez gagné -NUM- 200 000 $'
	),
	'misiune-24' => array(
		'EN' => 'You removed -NUM- players from jail',
		'RO' => 'Du hast -NUM- Spieler aus dem Gefängnis befreit',
		'FR' => 'Vous avez fait évader -NUM- joueurs de prison'
	),
	'misiune-25' => array(
		'EN' => 'You have to earn -NUM- % rank progress',
		'RO' => 'Du musst -NUM- % Rangfortschritt erreichen',
		'FR' => 'Vous avez gagné -NUM- % de progression de rang'
	),
	'misiune-26' => array(
		'EN' => 'You stolen',
		'RO' => 'Du klautest',
		'FR' => 'Vous avez volé'
	),
	'misiune-27' => array(
		'EN' => 'You have not won the lottery!',
		'RO' => 'Du hast nicht im Lotto gewonnen!',
		'FR' => 'Vous n\'avez pas gagné à la loterie!'
	),
	'misiune-28' => array(
		'EN' => 'You have won the lottery!',
		'RO' => 'Du hast im Lotto gewonnen!',
		'FR' => 'ous avez gagné à la loterie!'
	),
	'misiune-29' => array(
		'EN' => 'You made -NUM- robberies in different cities',
		'RO' => 'Du hast -NUM- Raubüberfälle in verschiedenen Städten begangen',
		'FR' => 'Vous avez fait -NUM- vols dans différentes villes'
	),
	'misiune-30' => array(
		'EN' => 'You successfully made -NUM- consecutive robberies',
		'RO' => 'Du hast erfolgreich -NUM- Raubüberfälle in Folge begangen',
		'FR' => 'Vous avez réussi -NUM- vols consécutifs'
	),
	'misiune-31' => array(
		'EN' => 'You stolen -NUM- cars from Oslo',
		'RO' => 'Du hast -NUM- Autos in Oslo gestohlen',
		'FR' => 'Vous avez volé -NUM- voitures au départ de Oslo'
	),
	'misiune-32' => array(
		'EN' => 'You blackmailed -NUM- different players',
		'RO' => 'Du hast -NUM- verschiedene Spieler erpresst',
		'FR' => 'Vous avez fait chanter -NUM- joueurs uniques'
	),
	'misiune-33' => array(
		'EN' => 'You freed -NUM- different players from the jail',
		'RO' => 'Du hast -NUM- verschiedene Spieler aus dem Gefängnis befreit',
		'FR' => 'Vous avez libéré -NUM- joueurs de prison'
	),
	'misiune-34' => array(
		'EN' => 'You won -NUM- times at BlackJack',
		'RO' => 'Du hast -NUM- mal bei BlackJack gewonnen',
		'FR' => 'Vous avez gagné -NUM- fois au BlackJack'
	),
	'misiune-35' => array(
		'EN' => 'You don\'t know where is Michele Navarra',
		'RO' => 'Du weißt nicht, wo Michele Navarra ist',
		'FR' => 'Vous ne savez pas ou est Michele Navarra'
	),
	'misiune-36' => array(
		'EN' => 'Michele Navarra is on <b>-CITY-</b>.<br>To complete the mission, you should go on this city and kill him using "Murder" function',
		'RO' => 'Michele Navarra ist in <b>-CITY-</b>.<br>Um die Mission abzuschließen, musst du in diese Stadt gehen und ihn mit der Funktion "Killer" töten',
		'FR' => 'Michele Navarra est à <b>-CITY-</b>.<br>Pour complété votre mission, vous devez vous rendre dans cette ville et le tuer avec la fonction "Meurtre"'
	),
	'misiune-37' => array(
		'EN' => 'You haven\'t won at the "Open the Safe"',
		'RO' => 'Du hast "Knacke den Safe" nicht gewonnen',
		'FR' => 'Vous n\'avez pas réussi à forcer le coffre fort'
	),
	'misiune-38' => array(
		'EN' => 'You won at the "Open the Safe"',
		'RO' => 'Du hast "Knacke den Safe" gewonnen',
		'FR' => 'Vous avez réussi à forcer le Coffre Fort'
	),
	'misiune-39' => array(
		'EN' => 'You won -NUM- times at "Slots"',
		'RO' => 'Du hast -NUM- mal beim "Spielautomat" gewonnen',
		'FR' => 'Vous avez gagné -NUM- fois à La "Machine à sous"'
	),
	'misiune-40' => array(
		'EN' => 'You recruited -NUM- players',
		'RO' => 'Du hast -NUM- neue Spieler angeworben',
		'FR' => 'Vous avez recruté -NUM- joueurs'
	),
	'misiune-41' => array(
		'EN' => 'You have sent -NUM- respect points',
		'RO' => 'Du hast -NUM- Respektpunkte vergeben',
		'FR' => 'Vous avez donné -NUM- points de respects'
	),
	'misiune-42' => array(
		'EN' => 'You received -NUM- respect points',
		'RO' => 'Du hast -NUM- Respektpunkte erhalten',
		'FR' => 'Vous avez reçu -NUM- points de respects'
	),
	'misiune-43' => array(
		'EN' => 'You won -NUM- times at "Scratch Cards"',
		'RO' => 'Du hast -NUM- mal bei "Rubbellose" gewonnen',
		'FR' => 'Vous avez gagné -NUM- fois au "Grattage'
	),
	'misiune-44' => array(
		'EN' => 'You stolen -NUM- Bugatti Veyron',
		'RO' => 'Du hast -NUM- Bugatti Veyron gestohlen',
		'FR' => 'Vous avez volé -NUM- Bugatti Veyron'
	),
	'misiune-45' => array(
		'EN' => 'You stolen -NUM- Ferrari Spider',
		'RO' => 'Du hast -NUM- Ferrari Spider gestohlen',
		'FR' => 'Vous avez volé -NUM- Ferrari Spider'
	),
	'misiune-46' => array(
		'EN' => 'You recruited -NUM- hookers',
		'RO' => 'Du hast -NUM- Prostituierte angeworben',
		'FR' => 'Vous avez recruté -NUM- filles de joie'
	),
	'misiune-47' => array(
		'EN' => 'You have collected $ -SUM-',
		'RO' => 'Du hast -SUM- $ gesammelt',
		'FR' => 'Vous avez collecté -SUM- $'
	),
	'misiune-48' => array(
		'EN' => 'You have added -NUM- hookers in the brothel',
		'RO' => 'Du hast -NUM- Prostituierte ins Bordell gesteckt',
		'FR' => 'Vous avez ajouté -NUM- filles à votre maison de passes'
	),
	'misiune-49' => array(
		'EN' => 'You stolen -NUM- BMW M5',
		'RO' => 'Du hast -NUM- BMW M5 gestohlen',
		'FR' => 'Vous avez volé -NUM- BMW M5'
	),
	'misiune-50' => array(
		'EN' => 'You stolen -NUM- Mercedes SLR McLaren',
		'RO' => 'Du hast -NUM- Mercedes SLR McLaren gestohlen',
		'FR' => 'Vous avez volé -NUM- Mercedes SLR McLaren'
	),
	'misiune-51' => array(
		'EN' => 'You won -NUM- times at HorseRace',
		'RO' => 'Du hast -NUM- mal beim Pferderennen gewonnen',
		'FR' => 'Vous avez gagné -NUM- fois au HorseRace'
	),
	'misiune-52' => array(
		'EN' => 'You won -NUM- times at Lottery',
		'RO' => 'Du hast -NUM- mal beim Lotto gewonnen',
		'FR' => 'Vous avez gagné -NUM- fois au loterie'
	),
	'misiune-53' => array(
		'EN' => 'You won -NUM- times at Open the Safe',
		'RO' => 'Du hast -NUM- mal beim "Knacke den Safe" gewonnen',
		'FR' => 'Vous avez gagné -NUM- fois au Coffre Fort'
	),
	
	// Kopf oder Zahl (Coinroll)
	'moneda-00' => array(
		'EN' => 'Coinroll',
		'RO' => 'Kopf oder Zahl',
		'FR' => 'Loterie de Crédits'
	),
	'moneda-01' => array(
		'EN' => 'Owner',
		'RO' => 'Eigentümer',
		'FR' => 'Propriétaire'
	),
	'moneda-02' => array(
		'EN' => 'Maximum stake',
		'RO' => 'Maximaler Einsatz',
		'FR' => 'Mise maximum'
	),
	'moneda-03' => array(
		'EN' => 'You bought "Coinroll"!',
		'RO' => 'Du einen "Kopf oder Zahl"-Laden gekauft!',
		'FR' => 'Vous avez acheté "Rouleaux de pièces"!'
	),
	'moneda-04' => array(
		'EN' => 'Buy "Coinroll"!',
		'RO' => '"Kopf oder Zahl" -Laden kaufen!',
		'FR' => 'Achetez l\'établissement "Loterie de Crédits"!'
	),
	'moneda-05' => array(
		'EN' => 'There isn\'t such a business in -CITY-.<br />It costs <b>-COINS- coins</b> to buy "Coinroll".',
		'RO' => 'Es gibt keinen solchen Laden in -CITY-.<br />Es kostet <b>-COINS- Coins</b> einen "Kopf oder Zahl"-Laden zu kaufen.',
		'FR' => 'Il n\'y a pas de meilleur buziness à -CITY-.<br />Ca te couteras <b>-COINS- crédits</b> pour acheter La "Loterie de Crédits".'
	),
	'moneda-06' => array(
		'EN' => 'You must wager at least $10',
		'RO' => 'Du mußt mehr als 10$ einsetzen',
		'FR' => 'Vous devez miser au moins 10$'
	),
	'moneda-07' => array(
		'EN' => 'You cannot bet more than -CASH- $',
		'RO' => 'Du kannst nicht mehr als -CASH- $ einsetzen',
		'FR' => 'Vous ne pouvez pas parier plus que -CASH- $'
	),
	'moneda-08' => array(
		'EN' => 'You won <b>-CASH- $</b>. The company went bankrupt and you\'re the new owner!',
		'RO' => 'Du hast <b>-CASH- $</b> gewonnen. Der Laden ging in Konkurs und du bist der neue Eigentümer!',
		'FR' => 'Vous avez gagné <b>-CASH- $</b>. La compagnie a fait faillite, vous devenez l\'heureux propriétaire!'
	),
	'moneda-09' => array(
		'EN' => 'You won <b>-CASH- $</b>.',
		'RO' => 'Du hast die Münze geworfen. Super, du hast <b>-CASH- $</b> gewonnen.',
		'FR' => 'Vous avez gagné <b>-CASH- $</b>.'
	),
	'moneda-10' => array(
		'EN' => 'You lost <b>-CASH- $</b>.',
		'RO' => 'Du hast die Münze geworfen. Leider Pech, du hast <b>-CASH- $</b> verloren.',
		'FR' => 'Vous avez perdu <b>-CASH- $</b>.'
	),
	'moneda-11' => array(
		'EN' => 'You must enter more than $1',
		'RO' => 'Du must mehr als 1$ setzen',
		'FR' => 'Vous devez renseigner plus de 1$'
	),
	'moneda-12' => array(
		'EN' => 'You added -CASH- $',
		'RO' => 'Du hast -CASH- $ eingezahlt',
		'FR' => 'Vous avez ajouté -CASH- $'
	),
	'moneda-13' => array(
		'EN' => 'Must withdraw more than $1',
		'RO' => 'Du mußt mehr als 1$ abheben',
		'FR' => 'Vous devez retirer plus de 1$'
	),
	'moneda-14' => array(
		'EN' => 'You withdrew -CASH- $',
		'RO' => 'Du hast -CASH- $ abgehoben',
		'FR' => 'Vous avez retiré -CASH- $'
	),
	'moneda-15' => array(
		'EN' => 'Max bet must be greater than -CASH- $',
		'RO' => 'Der maximale Einsatz muss höher als -CASH- $ sein',
		'FR' => 'Votre parie doit être supérieure à -CASH- $'
	),
	'moneda-16' => array(
		'EN' => 'Max bet is now -CASH- $ and odds of winning are -PRC- %',
		'RO' => 'Der maximale Einsatz liegt jetzt bei -CASH- $ und die Gewinnchancen liegen bei -PRC- %',
		'FR' => 'La mise max est désormais de -CASH- $ et les chances de gagner sont de -PRC- %'
	),
	'moneda-17' => array(
		'EN' => 'This business belongs to',
		'RO' => 'Dieser Laden gehört ',
		'FR' => 'Cette entreprise appartient à'
	),
	'moneda-18' => array(
		'EN' => 'This company is yours, so you cannot invest money here!',
		'RO' => 'Diese Firma gehört dir, also kannst du hier kein Geld anlegen!',
		'FR' => 'Cette compagnie est à vous, vous ne pouvez pas investir de l\'argent ici!'
	),
	'moneda-19' => array(
		'EN' => 'Odds for win',
		'RO' => 'Gewinnchancen',
		'FR' => 'Chance de gagner'
	),
	'moneda-20' => array(
		'EN' => 'The odds of winning must be between 40% and 60%',
		'RO' => 'Die Gewinnchancen müssen zwischen 40% und 60% liegen',
		'FR' => 'Les chances de gagner doivent être compris entre 40% et 60%'
	),
	'moneda-21' => array(
		'EN' => 'Maximum stake',
		'RO' => 'Maximaler Einsatz',
		'FR' => 'Mise maximum'
	),
	'moneda-22' => array(
		'EN' => 'Manage Coinroll',
		'RO' => 'Kopf oder Zahl verwalten',
		'FR' => 'Administration des affaires'
	),
	'moneda-23' => array(
		'EN' => 'Income',
		'RO' => 'Einkommen',
		'FR' => 'Recette'
	),
	'moneda-24' => array(
		'EN' => 'Loss',
		'RO' => 'Verlust',
		'FR' => 'Pertes'
	),
	'moneda-25' => array(
		'EN' => 'Gross',
		'RO' => 'Gewinn',
		'FR' => 'Profit'
	),

	// Zahlenspiel
	'numere-00' => array(
		'EN' => 'Number game',
		'RO' => 'Zahlenspiel',
		'FR' => 'Numéro du jeu'
	),
	'numere-01' => array(
		'EN' => 'Round has expired! Participants received money back!',
		'RO' => 'Die Runde ist abgelaufen! Die Teilnehmer erhielten ihr Geld zurück!',
		'FR' => 'Round expiré, les participants reçoivent leur argent!'
	),
	'numere-02' => array(
		'EN' => 'You interrupted the round and you got your money back!',
		'RO' => 'Du hast die Runde abgebrochen und dein Geld zurückbekommen!',
		'FR' => 'Vous avez interrompu le Round, et vous avez récupéré votre argent!'
	),
	'numere-03' => array(
		'EN' => 'There were -NUM- winners, and they received -CASH- $ each!',
		'RO' => 'Es gab -NUM- Gewinner, die jeweils -CASH- $ erhielten!',
		'FR' => 'Il y a eu -NUM- gagnants, ils ont reçu -CASH- $ chaqu\'un!'
	),
	'numere-04' => array(
		'EN' => 'Unfortunately no participant won!',
		'RO' => 'Leider hat kein Teilnehmer gewonnen!',
		'FR' => 'Malheureusement, aucun participant a gagné!'
	),
	'numere-05' => array(
		'EN' => 'Active round',
		'RO' => 'Aktive Runde',
		'FR' => 'Autour tour'
	),
	'numere-06' => array(
		'EN' => 'Dealer',
		'RO' => 'Dealer',
		'FR' => 'Dealer'
	),
	'numere-07' => array(
		'EN' => 'Started',
		'RO' => 'Gestartet',
		'FR' => 'Début à'
	),
	'numere-08' => array(
		'EN' => 'Expired at',
		'RO' => 'Endet',
		'FR' => 'Expire dans'
	),
	'numere-09' => array(
		'EN' => 'Stake',
		'RO' => 'Einsatz',
		'FR' => 'Stake'
	),
	'numere-10' => array(
		'EN' => 'Total gain',
		'RO' => 'Gesamtgewinn',
		'FR' => 'Gain total'
	),
	'numere-11' => array(
		'EN' => 'Cancel',
		'RO' => 'Runde abbrechen',
		'FR' => 'Résilier'
	),
	'numere-12' => array(
		'EN' => 'Finish',
		'RO' => 'Runde beenden',
		'FR' => 'Terminer'
	),
	'numere-13' => array(
		'EN' => 'Participants',
		'RO' => 'Teilnehmer',
		'FR' => 'Les participants'
	),
	'numere-14' => array(
		'EN' => 'Participant',
		'RO' => 'Teilnehmer',
		'FR' => 'Participant'
	),
	'numere-15' => array(
		'EN' => 'Number',
		'RO' => 'Nummer',
		'FR' => 'Nombre'
	),
	'numere-16' => array(
		'EN' => 'The maximum number of players has been reached!',
		'RO' => 'Die maximale Spielerzahl wurde erreicht!',
		'FR' => 'Le nombre maximum de joueurs a été atteint!'
	),
	'numere-17' => array(
		'EN' => 'The number must be between -MIN- and -MAX-!',
		'RO' => 'Die Nummer muss zwischen -MIN- und -MAX- liegen!',
		'FR' => 'Le nombre doit être compris entre -MIN- et -MAX-!'
	),
	'numere-18' => array(
		'EN' => 'You have successfully joined!',
		'RO' => 'Du hast dich erfolgreich angemeldet!',
		'FR' => 'Vous avez enregistré avec succès!'
	),
	'numere-19' => array(
		'EN' => 'Are you enrolled in this round!',
		'RO' => 'Du bist in dieser Runde angemeldet!',
		'FR' => 'Êtes-vous inscrit dans cette ronde!'
	),
	'numere-20' => array(
		'EN' => 'Pick a Number',
		'RO' => 'Wähle eine Nummer',
		'FR' => 'Choisissez un numéro'
	),
	'numere-21' => array(
		'EN' => 'Join',
		'RO' => 'Beitreten',
		'FR' => 'Rejoindre'
	),
	'numere-22' => array(
		'EN' => 'Completed rounds',
		'RO' => 'Abgeschlossene Runden',
		'FR' => 'Parties ont conclu'
	),
	'numere-23' => array(
		'EN' => 'Number',
		'RO' => 'Nummer',
		'FR' => 'Nombre'
	),
	'numere-24' => array(
		'EN' => 'Start a Round',
		'RO' => 'Starte eine Runde',
		'FR' => 'Démarrer une ronde'
	),
	'numere-25' => array(
		'EN' => 'between -MIN- and -MAX-',
		'RO' => 'zwischen -MIN- und -MAX-',
		'FR' => 'entre -MIN- et -MAX-'
	),
	'numere-26' => array(
		'EN' => 'Start',
		'RO' => 'Runde starten',
		'FR' => 'Lancer ronde'
	),
	'numere-27' => array(
		'EN' => 'Instructions',
		'RO' => 'Anleitung',
		'FR' => 'Instructions'
	),
	'numere-28' => array(
		'EN' => 'Active Rounds',
		'RO' => 'Aktive Runden',
		'FR' => 'Rounds actifs'
	),
	'numere-29' => array(
		'EN' => 'This game is very simple and up to 10 players can play the same round. Every player have to choose a number between 2 and 12. At the end of the round, the ones who choosed winning number are winning the money (they will split the money). If the dealer doesn\'t complete the round, players receive back their money.',
		'RO' => 'Dieses Spiel ist sehr einfach. Bis zu 10 Spieler können in der selben Runde spielen. Jeder Spieler muss eine Zahl zwischen 2 und 12 wählen. Am Ende der Runde gewinnen diejenigen, die die Gewinnzahl gewählt haben, das Geld (das Geld wird aufgeteilt). Wenn der Dealer die Runde nicht beendet, erhalten die Spieler ihr Geld zurück.',
		'FR' => 'This game is very simple and up to 10 players can play the same round. Every player have to choose a number between 2 and 12. At the end of the round, the ones who choosed winning number are winning the money (they will split the money). If the dealer doesn\'t complete the round, players receive back their money.'
	),

	// Spieler Online
	'online-01' => array(
		'EN' => 'There are -NUM- online players!',
		'RO' => 'Es sind -NUM- Spieler online!',
		'FR' => 'Il y a -NUM- joueurs en ligne!'
	),
	
	// Markt
	'piata-01' => array(
		'EN' => 'You bought <b>-ITEM-</b> for <b>-CASH- $</b>.',
		'RO' => 'Du hast <b>-ITEM-</b> für <b>-CASH- $</b> gekauft.',
		'FR' => 'Vous avez acheté <b>-ITEM-</b> pour <b>-CASH- $</b>.'
	),
	'piata-02' => array(
		'EN' => 'You removed an item from the marketplace!',
		'RO' => 'Du hast einen Artikel vom Marktplatz entfernt!',
		'FR' => 'Vous avez supprimé un élément du marché!'
	),
	'piata-03' => array(
		'EN' => 'You haven\'t selected the item you want to be sold!',
		'RO' => 'Du hast den Artikel, den du verkaufen möchtest, nicht ausgewählt!',
		'FR' => 'Vous n\'avez pas sélectionné l\'objet à vendre!'
	),
	'piata-04' => array(
		'EN' => 'You don\'t have enough -ITEM-!',
		'RO' => 'Du hast nicht genügend -ITEM-!',
		'FR' => 'Vous n\'avez pas assez de -ITEM-!'
	),
	'piata-05' => array(
		'EN' => 'You can have only 2 active sales.',
		'RO' => 'Du kannst nur 2 aktive Verkäufe gleichzeitig haben.',
		'FR' => 'Vous ne pouvez avoir que 2 ventes en simultané.'
	),
	'piata-06' => array(
		'EN' => 'The amount must be higher than 1!',
		'RO' => 'Die Menge muss höher als 1 sein!',
		'FR' => 'Le montant doit être supérieur à 1'
	),
	'piata-07' => array(
		'EN' => 'Price must be higher than $1000',
		'RO' => 'Der Preis muss höher als 1000 $ sein ',
		'FR' => 'Le prix doit être suppérieur que 1 000 $'
	),
	'piata-08' => array(
		'EN' => 'You put a product on sale!',
		'RO' => 'Du hast ein Produkt zum Verkauf gestellt!',
		'FR' => 'Vous avez mis un produit à vendre!'
	),
	'piata-09' => array(
		'EN' => 'Sell something',
		'RO' => 'Etwas verkaufen',
		'FR' => 'Vendre quelque chose'
	),
	'piata-10' => array(
		'EN' => 'Active sales',
		'RO' => 'Aktive Verkäufe',
		'FR' => 'Ventes encours'
	),
	'piata-11' => array(
		'EN' => 'Seller',
		'RO' => 'Verkäufer',
		'FR' => 'Vendeur'
	),
	'piata-12' => array(
		'EN' => 'My sales',
		'RO' => 'Meine Verkäufe',
		'FR' => 'Mes ventes'
	),
	'piata-13' => array(
		'EN' => 'Buyer',
		'RO' => 'Käufer',
		'FR' => 'Acheteur'
	),
	
	// Profil bearbeiten
	'edp-01' => array(
		'EN' => 'The text should contain more than -NUM- characters.',
		'RO' => 'Der Text muss mehr als -NUM- Zeichen enthalten.',
		'FR' => 'Le texte doit contenir plus de -NUM- charactères.'
	),
	'edp-02' => array(
		'EN' => 'The text can\'t contain more than -NUM- characters.',
		'RO' => 'Der Text darf max -NUM- Zeichen enthalten.',
		'FR' => 'Le texte ne peut pas contenir plus de -NUM- charactères.'
	),
	'edp-03' => array(
		'EN' => 'Description was changed!',
		'RO' => 'Beschreibung wurde geändert!',
		'FR' => 'La description a été changée!'
	),
	'edp-04' => array(
		'EN' => 'Edit Description',
		'RO' => 'Beschreibung bearbeiten',
		'FR' => 'Editer la description'
	),
	'edp-05' => array(
		'EN' => 'Unknown extension! Picture must have extension .gif, .jpg or .png',
		'RO' => 'Unbekanntes Format! Das Bild muss .gif, .jpg oder .png sein',
		'FR' => 'Extension inconnue! les images doivent avoir l\'extension .gif, .jpg ou .png'
	),
	'edp-06' => array(
		'EN' => 'Picture must be less than 500KB',
		'RO' => 'Das Bild muss kleiner als 500KB sein',
		'FR' => 'Les images ne doivent pas dépasser 500KB'
	),
	'edp-07' => array(
		'EN' => 'You have successfully changed your profile picture!',
		'RO' => 'Du hast dein Profilbild erfolgreich geändert!',
		'FR' => 'Vous avez modifié l\'image du profil avec succès!'
	),
	'edp-08' => array(
		'EN' => 'Current picture',
		'RO' => 'Aktuelles Bild',
		'FR' => 'Avatar actuel'
	),
	'edp-09' => array(
		'EN' => 'Change your profile song (Youtube URL)',
		'RO' => 'Ändere deine Profil-Musik (Youtube URL)',
		'FR' => 'Changer son profil de son (Lien Youtube)'
	),
	'edp-10' => array(
		'EN' => 'URL',
		'RO' => 'URL',
		'FR' => 'URL'
	),
	'edp-11' => array(
		'EN' => 'Play music on your profile',
		'RO' => 'Musik im Profil abspielen',
		'FR' => 'Jouer de la musique sur le profil'
	),
	'edp-12' => array(
		'EN' => 'You changed your profile song!',
		'RO' => 'Du hast deine Musik im Profil geändert!',
		'FR' => 'Vous avez changé votre musique sur le profil!'
	),
	'edp-13' => array(
		'EN' => 'Current song',
		'RO' => 'Aktuelle Musik',
		'FR' => 'Melodie actuelle'
	),
	'edp-14' => array(
		'EN' => 'You haven\'t added song to profile!',
		'RO' => 'Du hast deinem Profil keine Musik hinzugefügt!',
		'FR' => 'Vous n\'avez pas ajouté de son à votre profil!'
	),
	'edp-15' => array(
		'EN' => 'The password cannot be the same like your bank password!',
		'RO' => 'Das Passwort darf nicht mit deinem Bankpasswort identisch sein!',
		'FR' => 'Votre mot de passe ne peut pas être identique à celui de la banque!'
	),
	'edp-16' => array(
		'EN' => 'Change password',
		'RO' => 'Passwort ändern',
		'FR' => 'Modifier le mot de passe'
	),
	'edp-17' => array(
		'EN' => 'Current password',
		'RO' => 'Aktuelles Passwort',
		'FR' => 'Mot de passe actuel'
	),
	'edp-18' => array(
		'EN' => 'New password',
		'RO' => 'Neues Passwort',
		'FR' => 'Nouveau mot de passe'
	),
	'edp-19' => array(
		'EN' => 'Repeat password',
		'RO' => 'Passwort wiederholen',
		'FR' => 'Répétez le mot de passe'
	),
	'edp-20' => array(
		'EN' => 'Signature cannot have more than -NUM- lines!',
		'RO' => 'Signatur darf max -NUM- Zeilen enthalten!',
		'FR' => 'Les signatures ne peuvent pas avoir plus de -NUM- lignes!'
	),
	'edp-21' => array(
		'EN' => 'You have changed your forum signature!',
		'RO' => 'Du hast deine Foren-Signatur geändert!',
		'FR' => 'Vous avez modifié votre signature de forum!'
	),
	'edp-22' => array(
		'EN' => 'Signature',
		'RO' => 'Signatur',
		'FR' => 'Signature'
	),
	'edp-23' => array(
		'EN' => 'Settings',
		'RO' => 'Einstellungen',
		'FR' => 'Paramètres'
	),
	'edp-24' => array(
		'EN' => 'Show signature on forums',
		'RO' => 'Signatur in Foren anzeigen',
		'FR' => 'Montrer la signature sur le forum'
	),
	'edp-25' => array(
		'EN' => 'Answers on page',
		'RO' => 'Antworten auf Seite',
		'FR' => 'Réponses sur la page'
	),
	
	// Erpressung
	'santaj-00' => array(
		'EN' => 'Blackmail',
		'RO' => 'Erpressung',
		'FR' => 'Chantage'
	),
	'santaj-01' => array(
		'EN' => 'You must choose an rank!',
		'RO' => 'Du musst einen Rang auswählen!',
		'FR' => 'Vous devez choisir un rang!'
	),
	'santaj-02' => array(
		'EN' => 'Victim not found!',
		'RO' => 'Kein Opfer gefunden!',
		'FR' => 'Victime non trouvée!'
	),
	'santaj-03' => array(
		'EN' => 'Success! You have blackmailed -PLAYER- for <b>-CASH- $</b>.',
		'RO' => 'Erfolg! Du hast -PLAYER- um <b>-CASH- $</b> erpresst.',
		'FR' => 'Succès! Vous avez fait chanter -PLAYER- pour <b>-CASH- $</b>.'
	),
	'santaj-04' => array(
		'EN' => 'Failed! You could not blackmail -PLAYER-. You was arrested for <b>-TIME-</b> seconds.',
		'RO' => 'Fehlgeschlagen! Du hast es nicht geschafft -PLAYER- zu erpressen. Du kommst für <b>-TIME-</b> Sekunden in den Knast.',
		'FR' => 'Echec! Vous ne pouvez pas faire chanter -PLAYER-. Vous êtes arrété pour <b>-TIME-</b> secondes'
	),
	'santaj-05' => array(
		'EN' => 'Failed! You could not blackmail -PLAYER-.',
		'RO' => 'Fehlgeschlagen! Du hast es nicht geschafft -PLAYER- zu erpressen.',
		'FR' => 'Echec! Vous ne pouvez pas faire chanter -PLAYER-.'
	),
	'santaj-06' => array(
		'EN' => 'You must have at least the rank <b>-RANK-</b> to blackmail someone.',
		'RO' => 'Du musst mindestens Rang <b>-RANK-</b> haben um jemanden erpressen zu können.',
		'FR' => 'Vous devez avoir au moins le rang <b>-RANK-</b> pour faire chanter quelqu\'un.'
	),
	'santaj-07' => array(
		'EN' => 'You have to wait <b><span class="countdown reload">-TIME-</span> seconds</b>.',
		'RO' => 'Du musst <b><span class="countdown reload">-TIME-</span> Sekunden</b> warten.',
		'FR' => 'Vous devez attendre <b><span class="countdown reload">-TIME-</span> secondes</b>.'
	),
	'santaj-08' => array(
		'EN' => 'Choose a rank for blackmail',
		'RO' => 'Wähle einen Rang für die Erpressung',
		'FR' => 'Choisissez un rang pour faire chanter'
	),
	'santaj-09' => array(
		'EN' => 'your rank',
		'RO' => 'Dein Rang',
		'FR' => 'Votre rang'
	),
	'santaj-10' => array(
		'EN' => 'Blackmail',
		'RO' => 'Erpressen',
		'FR' => 'Chantage'
	),
	'santaj-11' => array(
		'EN' => 'You made <b>-TOTAL-</b> blackmail, of which <span class="yellow bold">-SUCCESS-</span> were successful and <span class="red bold">-FAIL-</span> failed.',
		'RO' => 'Du hast bisher <b>-TOTAL-</b> Erpressungen durchgeführt, davon waren <span class="yellow bold">-SUCCESS-</span> erfolgreich und <span class="red bold">-FAIL-</span> sind fehlgeschlagen.',
		'FR' => 'ous avez réalisé <b>-TOTAL-</b> chantages, <span class="yellow bold">-SUCCESS-</span> avec succès <span class="red bold">-FAIL-</span> ont échoués.'
	),
	'santaj-12' => array(
		'EN' => 'You were blackmailed -NUM- times.',
		'RO' => 'Du wurdest bisher -NUM- mal erpresst.',
		'FR' => 'On vous a fait chanter -NUM- fois.'
	),
	'santaj-13' => array(
		'EN' => 'You won <b class="yellow">-WIN- $</b> and you lost <b class="red">-LOST- $</b>.',
		'RO' => 'Du hast <b class="yellow">-WIN- $</b> gewonnen und <b class="red">-LOST- $</b> verloren.',
		'FR' => 'Vous avez gagné <b class="yellow">-WIN- $</b> et vous avez perdu <b class="red">-LOST- $</b>.'
	),
	'santaj-14' => array(
		'EN' => 'Money',
		'RO' => 'Geld',
		'FR' => 'Argent'
	),
	'santaj-15' => array(
		'EN' => 'Last 5 blackmail received',
		'RO' => 'Letzten 5 erhaltene Erpressungen',
		'FR' => '5 derniers chantages subits'
	),
	'santaj-16' => array(
		'EN' => 'Last 5 blackmail made',
		'RO' => 'Letzten 5 Erpressungen',
		'FR' => '5 derniers chantages effectués'
	),
	
	// Spielautomat
	'slot-01' => array(
		'EN' => 'Video Slots',
		'RO' => 'Geldspielautomat',
		'FR' => 'Machine à sous'
	),
	'slot-02' => array(
		'EN' => 'Congratulations! You won -CASH- $',
		'RO' => 'Glückwunsch! Du hast -CASH- $ gewonnen',
		'FR' => 'Felicitation! Vous avez gagné -CASH- $'
	),
	'slot-03' => array(
		'EN' => 'You lost!',
		'RO' => 'Leider nichts gewonnen!',
		'FR' => 'Vous avez perdu!'
	),
	'slot-04' => array(
		'EN' => 'Stake',
		'RO' => 'Einsatz',
		'FR' => 'Mise'
	),
	'slot-05' => array(
		'EN' => 'Maximum gain',
		'RO' => 'Maximaler Gewinn',
		'FR' => 'Gain maximum'
	),
	'slot-06' => array(
		'EN' => 'Write the bet and click "Start"!',
		'RO' => 'Gib deinen Einsatz ein und klicke auf "Start"!',
		'FR' => 'Pariez et clickez sur "Commencer"!'
	),
	'slot-07' => array(
		'EN' => 'Start',
		'RO' => 'Start',
		'FR' => 'Commencer'
	),
	
	// Bewerbung
	'cereri-01' => array(
		'EN' => 'You can\'t have more than -NUM- requests sent simultaneously!',
		'RO' => 'Es können nicht mehr als -NUM- Bewerbungen gleichzeitig gesendet werden!',
		'FR' => 'Vous ne pouvez pas avoir plus de -NUM- requêtes envoyées en simultané!'
	),
	'cereri-02' => array(
		'EN' => 'The request must contain at least -NUM- characters!',
		'RO' => 'Die Bewerbung muss mindestens -NUM- Zeichen enthalten!',
		'FR' => 'La requête doit contenir au moins -NUM- charactère!'
	),
	'cereri-03' => array(
		'EN' => 'You created a new application!',
		'RO' => 'Du hast eine neue Bewerbung erstellt!',
		'FR' => 'Vous avez créé une nouvelle demande!'
	),
	'cereri-04' => array(
		'EN' => 'Send a request',
		'RO' => 'Eine Bewerbung senden',
		'FR' => 'Envoyer une requête'
	),
	'cereri-05' => array(
		'EN' => 'Send your history',
		'RO' => 'Lebenslauf senden',
		'FR' => 'Envoyer votre historique'
	),
	'cereri-06' => array(
		'EN' => 'Save and continue...',
		'RO' => 'Speichern und fortfahren ...',
		'FR' => 'Sauvegarder et continuer...'
	),
	'cereri-07' => array(
		'EN' => 'Unread',
		'RO' => 'Ungelesen',
		'FR' => 'Non lu'
	),
	'cereri-08' => array(
		'EN' => 'Rejected',
		'RO' => 'Abgelehnt',
		'FR' => 'Rejeté'
	),
	'cereri-09' => array(
		'EN' => 'Accepted',
		'RO' => 'Akzeptiert',
		'FR' => 'Accepté'
	),
	'cereri-10' => array(
		'EN' => 'Lack recipient',
		'RO' => 'Empfänger fehlt',
		'FR' => 'Il manque le destinataire'
	),
	'cereri-11' => array(
		'EN' => 'The company is no longer active!',
		'RO' => 'Das Unternehmen ist nicht mehr aktiv!',
		'FR' => 'Cette compagnie n\'est plus active!'
	),
	'cereri-12' => array(
		'EN' => 'The request could not be confirmed, because player is already have a job!',
		'RO' => 'Die Bewerbung konnte nicht angenommen werden, da der Spieler bereits einen Job hat!',
		'FR' => 'La requête ne peut pas être confirmée car le joueur est déjà engagé!'
	),
	'cereri-13' => array(
		'EN' => 'The application was successfully confirmed!',
		'RO' => 'Die Bewerbung wurde erfolgreich bestätigt!',
		'FR' => 'La demande est confirmée!'
	),
	'cereri-14' => array(
		'EN' => 'The family is no longer active!',
		'RO' => 'Die Familie, an die die Bewerbung gerichtet ist, ist nicht mehr aktiv!',
		'FR' => 'Cette famille n\'est plus active!'
	),
	'cereri-15' => array(
		'EN' => 'The request could not be confirmed, because player is already a member of a family!',
		'RO' => 'Die Bewerbung konnte nicht angenommen werden, da der Spieler bereits Mitglied einer Familie ist!',
		'FR' => 'La requête ne peut pas être confirmée car le joueur appartient déjà à une famille!'
	),
	'cereri-16' => array(
		'EN' => 'The request could not be confirmed because are not available places in the family!',
		'RO' => 'Die Bewerbung konnte nicht angenommen werden, da in der Familie keine Plätze mehr verfügbar sind!',
		'FR' => 'La requête n\'est pas confirmée car la famille ne peut plus accueillir d\'autres membres!'
	),
	'cereri-17' => array(
		'EN' => 'You confirmed the request, and the player is now a member of the family <b>-FAMILY-</b>!',
		'RO' => 'Du hast die Bewerbung angenommen und der Spieler ist jetzt Mitglied der Familie <b>-FAMILY-</b>!',
		'FR' => 'Vous avez confirmé la requête, et le joueur est maintenant membre de la famille <b>-FAMILY-</b>!'
	),
	'cereri-18' => array(
		'EN' => 'You declined request!',
		'RO' => 'Du hast die Bewerbung abgelehnt!',
		'FR' => 'Vous avez décliné la requête!'
	),
	'cereri-19' => array(
		'EN' => 'Requests was reset!',
		'RO' => 'Bewerbungen wurden zurückgesetzt!',
		'FR' => 'La requête est initialisée!'
	),
	'cereri-20' => array(
		'EN' => 'The company does not currently accept applications!',
		'RO' => 'Das Unternehmen nimmt derzeit keine Bewerbungen an!',
		'FR' => 'La compagnie refuse!'
	),
	'cereri-21' => array(
		'EN' => 'The company does not currently accept applications!',
		'RO' => 'Das Unternehmen nimmt derzeit keine Bewerbungen an!',
		'FR' => 'La compagnie refuse!'
	),
	'cereri-22' => array(
		'EN' => 'The request could not be sent!',
		'RO' => 'Die Bewerbung konnte nicht gesendet werden!',
		'FR' => 'a requête ne peut pas être envoyée!'
	),
	'cereri-23' => array(
		'EN' => 'The application was removed!',
		'RO' => 'Bewerbung wurde entfernt!',
		'FR' => 'La demande est retirée!'
	),
	'cereri-24' => array(
		'EN' => 'Application',
		'RO' => 'Bewerbung',
		'FR' => 'Demande'
	),
	'cereri-25' => array(
		'EN' => 'You saved and sent the request!',
		'RO' => 'Du hast die Bewerbung gespeichert und gesendet!',
		'FR' => 'Vous avez sauvegardé et envoyé votre requête!'
	),
	'cereri-26' => array(
		'EN' => 'The application was saved',
		'RO' => 'Die Bewerbung wurde gespeichert',
		'FR' => 'La demande est sauvegardée'
	),
	'cereri-27' => array(
		'EN' => 'but could not be sent!',
		'RO' => 'konnte aber nicht gesendet werden!',
		'FR' => 'Mais ne peut pas être envoyée!'
	),
	'cereri-28' => array(
		'EN' => 'Sent',
		'RO' => 'Gesendet',
		'FR' => 'Envoyé'
	),
	'cereri-29' => array(
		'EN' => 'Reset',
		'RO' => 'Zurücksetzen',
		'FR' => 'Réinitialise'
	),
	'cereri-30' => array(
		'EN' => 'Application was accepted!<br /><a href="?side=cereri&amp;view=-CVID-&amp;confirm">Click here</a> to accept the job!<br />To reject the job press <a href="?side=cereri&amp;view=-CVID-&amp;deny">click here</a>.',
		'RO' => 'Bewerbung wurde angenommen!<br /><a href="?side=cereri&amp;view=-CVID-&amp;confirm">Klicke hier</a> um den Job anzunehmen!<br />Um den Job abzulehnen, <a href="?side=cereri&amp;view=-CVID-&amp;deny">klicke hier</a>.',
		'FR' => 'La demande a été acceptée!<br /><a href="?side=cereri&amp;view=-CVID-&amp;confirm">click ici</a> pour accepter le job!<br />Pour rejeter le job, <a href="?side=cereri&amp;view=-CVID-&amp;deny">click ici</a>.'
	),
	'cereri-31' => array(
		'EN' => 'Applications',
		'RO' => 'Bewerbungen',
		'FR' => 'Demandes'
	),
	'cereri-32' => array(
		'EN' => 'Select',
		'RO' => 'Auswahl',
		'FR' => 'Sélection'
	),
	'cereri-33' => array(
		'EN' => 'Send your history',
		'RO' => 'Deinen Lebenslauf senden',
		'FR' => 'Envoyer votre historique'
	),
	'cereri-34' => array(
		'EN' => 'Save and Send',
		'RO' => 'Speichern und senden',
		'FR' => 'Sauvegarder et envoyer'
	),
	'cereri-35' => array(
		'EN' => 'Show',
		'RO' => 'Anzeigen',
		'FR' => 'Montrer'
	),
	'cereri-36' => array(
		'EN' => 'Unsent',
		'RO' => 'Nicht gesendet',
		'FR' => 'Non envoyé'
	),
	
	// Knacke den Safe
	'seif-01' => array(
		'EN' => 'The stakes should be between 10 and 50 coins!',
		'RO' => 'Der Einsatz sollte zwischen 10 und 50 Coins liegen!',
		'FR' => 'La mise doit être entre 10 et 50 crédits!'
	),
	'seif-02' => array(
		'EN' => 'Please choose one of 4 codes!',
		'RO' => 'Bitte wähle einen von 4 Codes!',
		'FR' => 'Choissez un des 4 codes!'
	),
	'seif-03' => array(
		'EN' => 'Unfortunately you haven\'t guessed the code. Correct code was code #-NR-!',
		'RO' => 'Leider hast du nicht den richtigen Code erraten. Der richtige Code wäre der -NR-. Code gewesen!',
		'FR' => 'Malheureusement vous n\'avez pas le bon code. Le code correct était -NR-!'
	),
	'seif-04' => array(
		'EN' => 'Congratulations! You guessed the code and you won -COINS- coins, -CASH- $, -BULLETS- bullets and rank progress.',
		'RO' => 'Super, das war richtig! Du hast -COINS- Coins, -CASH- $, -BULLETS- Munition und Rangfortschritte gewonnen.',
		'FR' => 'Felicitation! Vous avez trouvé le bon code, vous gagnez -COINS- crédits, -CASH- $, -BULLETS- cartouches, et vous progréssez en Rang.'
	),
	'seif-05' => array(
		'EN' => 'In this game are 4 codes. One of them is the code that opens the safe. You can try to guess the correct code and to get the contents of safe.',
		'RO' => 'In diesem Spiel gibt es 4 Codes. Einer davon ist der Code, der den Safe öffnet. Du kannst versuchen, den richtigen Code zu erraten, um den Inhalt vom Safe zu erhalten.',
		'FR' => 'Vous avez 4 codes, 1 seul parmis eux ouvre le coffre-fort. Vous pouvez essayer de trouver le bon code. Si vous entrez le bon code, le contenu du coffre fort est à vous!.'
	),
	'seif-06' => array(
		'EN' => 'Stake',
		'RO' => 'Einsatz',
		'FR' => 'Mise'
	),
	'seif-07' => array(
		'EN' => 'You can win <div id="points" style="display:inline;">20</div> coins, <div id="cash" style="display:inline;">-CASH- $</div>, <div id="bullet" style="display:inline;">-BULLETS-</div> bullets and -RANK- % rank progress!',
		'RO' => 'Im Safe befinden sich derzeit <div id="points" style="display:inline;">20</div> Coins, <div id="cash" style="display:inline;">-CASH- $</div>, <div id="bullet" style="display:inline;">-BULLETS-</div> Munition und -RANK- % Rangfortschritt!',
		'FR' => 'Vous pouvez gagner <div id="points" style="display:inline;">20</div> crédits, <div id="cash" style="display:inline;">-CASH- $</div>, <div id="bullet" style="display:inline;">-BULLETS-</div> cartouches et -RANK- % progression de Rang!'
	),
	'seif-08' => array(
		'EN' => 'Choose the code',
		'RO' => 'Wähle den Code',
		'FR' => 'Choisissez le code'
	),
	'seif-09' => array(
		'EN' => 'Open',
		'RO' => 'Öffnen',
		'FR' => 'Ouvert'
	),
	
	// Profil
	'profil-01' => array(
		'EN' => 'Online',
		'RO' => 'Online',
		'FR' => 'En ligne'
	),
	'profil-02' => array(
		'EN' => 'Offline',
		'RO' => 'Offline',
		'FR' => 'Déconnecté'
	),
	'profil-03' => array(
		'EN' => 'Busy',
		'RO' => 'Beschäftigt',
		'FR' => 'Actifs'
	),
	'profil-04' => array(
		'EN' => 'Away',
		'RO' => 'Weg',
		'FR' => 'Loin'
	),
	'profil-05' => array(
		'EN' => 'Has <span>-POINTS- criminal points</span>.',
		'RO' => 'Hat <span>-POINTS- Verbrechenspunkte</span>.',
		'FR' => 'A <span>-POINTS- Points de crimes</span>.'
	),
	'profil-06' => array(
		'EN' => 'He made <span>-NUM1- attacks</span>, of wich <span>-NUM2-</span> were successful.',
		'RO' => 'Hat <span>-NUM1- Angriffe verübt</span>, von denen <span>-NUM2-</span> Erfolgreich waren.',
		'FR' => 'Il a fait <span>-NUM1- attaques</span>, de l\'objectif dont <span>-NUM2-</span> réussis avec succès.'
	),
	'profil-07' => array(
		'EN' => 'He made <span>-NUM1- robberies</span>, <span>-NUM2- car thefts</span> and <span>-NUM3- blackmail</span>.',
		'RO' => 'Hat <span>-NUM1- Überfälle</span> begangen, <span>-NUM2- Autodienstähle</span> und <span>-NUM3- Erpressungen</span>.',
		'FR' => 'Il a fait <span>-NUM1- vols</span>, <span>-NUM2- vol de voitures</span> et <span>-NUM3- chantages</span>.'
	),
	'profil-08' => array(
		'EN' => 'Private messages',
		'RO' => 'Private Nachrichten',
		'FR' => 'Messages privés'
	),
	'profil-09' => array(
		'EN' => 'Forum posts',
		'RO' => 'Forumsbeiträge',
		'FR' => 'Sujets du forum'
	),
	'profil-10' => array(
		'EN' => 'messages',
		'RO' => 'Beiträge',
		'FR' => 'messages'
	),
	'profil-11' => array(
		'EN' => 'This player has no family',
		'RO' => 'Dieser Spieler hat keine Familie',
		'FR' => 'Ce joueur n\'a pas de familles'
	),
	'profil-12' => array(
		'EN' => 'Boss',
		'RO' => 'Boss',
		'FR' => 'Boss'
	),
	'profil-13' => array(
		'EN' => 'Adviser',
		'RO' => 'Berater',
		'FR' => 'Conseiller'
	),
	'profil-14' => array(
		'EN' => 'No description!',
		'RO' => 'Keine Beschreibung!',
		'FR' => 'as de description!'
	),
	'profil-15' => array(
		'EN' => 'Latest Visitors',
		'RO' => 'Letzten Besucher',
		'FR' => 'Derniers visiteurs'
	),
	'profil-16' => array(
		'EN' => 'Received <span>-NUM- respect points</span>.',
		'RO' => 'Hat bisher<span>-NUM- Respektpunkte</span> erhalten.',
		'FR' => 'Vous avez reçu <span>-NUM- points de respects</span>.'
	),
	'profil-17' => array(
		'EN' => 'Public Profile',
		'RO' => 'Öffentliches Profil',
		'FR' => 'Profil Public'
	),
	
	// Krankenhaus
	'spital-01' => array(
		'EN' => 'Your treatment is over and you got <b>-SUM- %</b> health.',
		'RO' => 'Deine Behandlung ist abgeschlossen und du hast <b>-SUM- %</b> Gesundheit erhalten.',
		'FR' => 'Votre traitement est terminé, vous avez reçu <b>-SUM- %</b> santé.'
	),
	'spital-02' => array(
		'EN' => 'You already have 100% health',
		'RO' => 'Du hast bereits 100% Gesundheit',
		'FR' => 'Vous avez 100% de santé'
	),
	'spital-03' => array(
		'EN' => 'You must choose a longer period!',
		'RO' => 'Du musst einen längeren Zeitraum wählen!',
		'FR' => 'Vous devez choisir une période plus longue!'
	),
	'spital-04' => array(
		'EN' => 'You must choose a shorter period!',
		'RO' => 'Du musst einen kürzeren Zeitraum wählen!',
		'FR' => 'Vous devez choisir une période plus courte!'
	),
	'spital-05' => array(
		'EN' => 'You were hospitalized. You will receive <b>-HEALTH- %</b> health in <b>-TIME-</b>.',
		'RO' => 'Du wurdest ins Krankenhaus eingeliefert. Du wirst in <b>-TIME-</b> entlassen und erhälst <b>-HEALTH- %</b> Gesundheit!',
		'FR' => 'Vous avez été hospitalisé <b>-TIME-</b> vous avez reçu <b>-HEALTH- %</b> de santé!'
	),
	'spital-06' => array(
		'EN' => 'Your treatment ends in <b><span class="countdown reload">-TIME-</span> seconds</b>!',
		'RO' => 'Deine Behandlung endet in <b><span class="countdown reload">-TIME-</span> Sekunden</b>!',
		'FR' => 'Votre traitement se termine <b><span class="countdown reload">-TIME-</span> secondes</b>!'
	),
	'spital-07' => array(
		'EN' => 'If you stop the treatment, you don\'t receive health, and money are not returned!',
		'RO' => 'Wenn du die Behandlung abbrichst, erhälst du keine Gesundheit und es wird kein Geld zurückerstattet!',
		'FR' => 'Si vous arrétez le traitement, vous ne recevrez pas de santé, et vous perdez votre argent!'
	),
	'spital-08' => array(
		'EN' => 'You are not hospitalized!',
		'RO' => 'Du bist nicht im Krankenhaus!',
		'FR' => 'Vous n\'êtes pas hospitalisé!'
	),
	'spital-09' => array(
		'EN' => 'Minutes',
		'RO' => 'Minuten',
		'FR' => 'Minutes'
	),
	'spital-10' => array(
		'EN' => 'Health',
		'RO' => 'Gesundheit',
		'FR' => 'Santé'
	),
	'spital-11' => array(
		'EN' => 'Submit',
		'RO' => 'Einweisen',
		'FR' => 'Soumettre'
	),
	
	// Team
	'crew-01' => array(
		'EN' => 'Dismiss',
		'RO' => 'Entlassen',
		'FR' => 'Rejeter'
	),
	'crew-02' => array(
		'EN' => 'Support',
		'RO' => 'Support',
		'FR' => 'Support'
	),
	'crew-03' => array(
		'EN' => 'Moderator',
		'RO' => 'Moderator',
		'FR' => 'Moderateur'
	),
	'crew-04' => array(
		'EN' => 'Administrator',
		'RO' => 'Administrator',
		'FR' => 'Administrateur'
	),
	'crew-05' => array(
		'EN' => 'Founder',
		'RO' => 'Gründer',
		'FR' => 'Fondateur'
	),
	
	// Statistiken
	'sts-01' => array(
		'EN' => 'Last update',
		'RO' => 'Letzte Aktualisierung',
		'FR' => 'Dernière mise à jour'
	),
	'sts-02' => array(
		'EN' => 'Relase date',
		'RO' => 'Gestartet am',
		'FR' => 'Date de sortie'
	),
	'sts-03' => array(
		'EN' => 'Most online players',
		'RO' => 'Die meisten Spieler online',
		'FR' => 'La plus part des joueurs en ligne'
	),
	'sts-04' => array(
		'EN' => 'Some Stats',
		'RO' => 'Verschiedene Statistiken',
		'FR' => 'Quelques statistiques'
	),
	'sts-05' => array(
		'EN' => 'Banned users',
		'RO' => 'Gesperrte Benutzer',
		'FR' => 'Utilisateurs bannis'
	),
	'sts-06' => array(
		'EN' => 'Dead players',
		'RO' => 'Tote Spieler',
		'FR' => 'Joueurs morts'
	),
	'sts-07' => array(
		'EN' => 'Active in the last 24 hours',
		'RO' => 'Aktiv in den letzten 24 Stunden',
		'FR' => 'Actif dans les dernières 24h'
	),
	'sts-08' => array(
		'EN' => 'Active in the last 12 hours',
		'RO' => 'Aktiv in den letzten 12 Stunden',
		'FR' => 'Actif dans les dernières 12h'
	),
	'sts-09' => array(
		'EN' => 'Active in the last 6 hours',
		'RO' => 'Aktiv in den letzten 6 Stunden',
		'FR' => 'Actif dans les dernières 6h'
	),
	'sts-10' => array(
		'EN' => 'New players',
		'RO' => 'Neue Spieler',
		'FR' => 'Nouveaux joueurs'
	),
	'sts-11' => array(
		'EN' => 'today',
		'RO' => 'heute',
		'FR' => 'aujourd\'hui'
	),
	'sts-12' => array(
		'EN' => 'yesterday',
		'RO' => 'gestern',
		'FR' => 'Hier'
	),
	'sts-13' => array(
		'EN' => '2 days ago',
		'RO' => 'vor 2 Tagen',
		'FR' => 'il y a deux jours'
	),
	'sts-14' => array(
		'EN' => 'Forums / Messages',
		'RO' => 'Forum / Nachrichten',
		'FR' => 'Forum / Messages'
	),
	'sts-15' => array(
		'EN' => 'Forum topics',
		'RO' => 'Forenthemen',
		'FR' => 'Sujets du forum'
	),
	'sts-16' => array(
		'EN' => 'Forum replies',
		'RO' => 'Forum Antworten',
		'FR' => 'Réponses du forum'
	),
	'sts-17' => array(
		'EN' => 'Sent PMs',
		'RO' => 'Private Nachrichten',
		'FR' => 'Message privé'
	),
	'sts-18' => array(
		'EN' => 'Action logs',
		'RO' => 'Ereignisse',
		'FR' => 'Journal'
	),
	'sts-19' => array(
		'EN' => 'Admin / Moderator',
		'RO' => 'Admin / Moderator',
		'FR' => 'Admin / Moderateur'
	),
	'sts-20' => array(
		'EN' => 'Players cash',
		'RO' => 'Spieler Geld',
		'FR' => 'Argent des joueurs'
	),
	'sts-21' => array(
		'EN' => 'Players coins',
		'RO' => 'Spieler Coins',
		'FR' => 'Crédits des joueurs'
	),
	'sts-22' => array(
		'EN' => 'Money in banks',
		'RO' => 'Geld in Banken',
		'FR' => 'Argent en banques'
	),
	'sts-23' => array(
		'EN' => 'Money in family accounts',
		'RO' => 'Geld auf Familienkonten',
		'FR' => 'Argent dans les comptes de familles'
	),
	'sts-24' => array(
		'EN' => 'Ranks',
		'RO' => 'Ränge',
		'FR' => 'Rang'
	),
	'sts-25' => array(
		'EN' => 'Last 10 registered',
		'RO' => 'Letzten 10 registrierten Spieler',
		'FR' => 'Top 10 - Rang'
	),
	'sts-26' => array(
		'EN' => 'Top 10 Ranks',
		'RO' => 'Top 10 - Ränge',
		'FR' => 'XXXXXXXXX'
	),
	'sts-27' => array(
		'EN' => 'Your place',
		'RO' => 'Dein Platz',
		'FR' => 'Votre position'
	),
	'sts-28' => array(
		'EN' => 'Top 10 Assassins',
		'RO' => 'Top 10 - Killer',
		'FR' => 'Top 10 Assassins'
	),
	'sts-29' => array(
		'EN' => 'points',
		'RO' => 'Punkte',
		'FR' => 'points'
	),
	'sts-30' => array(
		'EN' => 'Top 10 Richest',
		'RO' => 'Top 10 - Reichste',
		'FR' => 'Top 10 - Des plus riches'
	),
	'sts-31' => array(
		'EN' => 'Top 10 escapees',
		'RO' => 'Top 10 - Ausbrecher',
		'FR' => 'Top 10 Evadés'
	),
	'sts-32' => array(
		'EN' => 'Top 10 Respected',
		'RO' => 'Top 10 - Respekt',
		'FR' => 'Top 10 Respects'
	),
	'sts-33' => array(
		'EN' => 'Total robberies',
		'RO' => 'Raubüberfälle gesamt',
		'FR' => 'Total vol'
	),
	'sts-34' => array(
		'EN' => 'All',
		'RO' => 'Alle',
		'FR' => 'Tous'
	),
	'sts-35' => array(
		'EN' => 'Successes',
		'RO' => 'Erfolgreich',
		'FR' => 'Succès'
	),
	'sts-36' => array(
		'EN' => 'Failures',
		'RO' => 'Fehlgeschlagen',
		'FR' => 'Echec'
	),
	'sts-37' => array(
		'EN' => 'Robberies made​in each location',
		'RO' => 'Raubüberfälle an jedem Ort',
		'FR' => 'Vols effectués pour chaque emplacement'
	),
	'sts-38' => array(
		'EN' => 'Earnings',
		'RO' => 'Einnahmen',
		'FR' => 'Bénéfices'
	),
	'sts-39' => array(
		'EN' => 'Rank progress',
		'RO' => 'Rangfortschritt',
		'FR' => 'Progression de Rang'
	),
	'sts-40' => array(
		'EN' => 'Wanted Level',
		'RO' => 'Gefahrenstatus',
		'FR' => 'Rang recherché'
	),
	
	// Kommentare
	'stiri-01' => array(
		'EN' => 'Comment must contain at least 2 characters!',
		'RO' => 'Kommentar muss mindestens 2 Zeichen enthalten!',
		'FR' => 'Le commentaire doit contenir au moins 2 carractères!'
	),
	'stiri-02' => array(
		'EN' => 'Comment cannot contain more than 1000 characters!',
		'RO' => 'Kommentar darf nicht mehr als 1000 Zeichen enthalten!',
		'FR' => 'Le commentaire ne peut contenir plus de 1000 carractères!'
	),
	'stiri-03' => array(
		'EN' => 'You wrote a comment!',
		'RO' => 'Du hast einen Kommentar geschrieben!',
		'FR' => 'Vous avez écrit un commentaire!'
	),
	'stiri-04' => array(
		'EN' => 'You deleted the comment!',
		'RO' => 'Du hast den Kommentar gelöscht!',
		'FR' => 'Vous avez supprimé le commentaire!'
	),
	'stiri-05' => array(
		'EN' => 'Comments',
		'RO' => 'Kommentare',
		'FR' => 'Commentaires'
	),
	'stiri-06' => array(
		'EN' => 'Written by',
		'RO' => 'Geschrieben von',
		'FR' => 'Ecrit par '
	),
	'stiri-07' => array(
		'EN' => 'Write a Comment',
		'RO' => 'Kommentar schreiben',
		'FR' => 'Ecrivez un commentaire'
	),
	'stiri-08' => array(
		'EN' => 'Comment',
		'RO' => 'Kommentar',
		'FR' => 'Commentaire'
	),
	
	// Coins tranferieren
	'trc-01' => array(
		'EN' => 'You canceled a transfer!',
		'RO' => 'Du hast den Transfer abgebrochen!',
		'FR' => 'Vous avez annulé le transfert!'
	),
	'trc-02' => array(
		'EN' => 'Successfully transferred!',
		'RO' => 'Erfolgreicher Transfer!',
		'FR' => 'Transfert réussi!'
	),
	'trc-03' => array(
		'EN' => 'You must transfer at least 1 credit!',
		'RO' => 'Du musst mindestens 1 Coin transferieren!',
		'FR' => 'Vous devez transférer au moins 1 crédit!'
	),
	'trc-04' => array(
		'EN' => 'Price must be higher than $ 1000',
		'RO' => 'Der Preis muss höher als 1.000 $ sein!',
		'FR' => 'Le prix doit être supérieur à 1 000 $'
	),
	'trc-05' => array(
		'EN' => 'You started an transfer!',
		'RO' => 'Du hast einen Transfer gestartet!',
		'FR' => 'Vous avez débuté le transfert!'
	),
	'trc-06' => array(
		'EN' => 'Transfer',
		'RO' => 'Transfer',
		'FR' => 'Transfert'
	),
	'trc-07' => array(
		'EN' => 'You have <b>-NUM- coins</b>.',
		'RO' => 'Du hast <b>-NUM- Coins</b>.',
		'FR' => 'Vous avez <b>-NUM- crédits</b>.'
	),
	'trc-08' => array(
		'EN' => 'Sent coins',
		'RO' => 'Gesendete Coins',
		'FR' => 'Crédits envoyés'
	),
	'trc-09' => array(
		'EN' => 'Received coins',
		'RO' => 'Erhaltene Coins',
		'FR' => 'Crédits reçus'
	),
	
	// Support
	'suport-01' => array(
		'EN' => 'You solved the problem!',
		'RO' => 'Du hast das Problem gelöst!',
		'FR' => 'Vous avez résolu le problème!'
	),
	'suport-02' => array(
		'EN' => 'You sent the ticket!',
		'RO' => 'Du hast das Ticket gesendet!',
		'FR' => 'Vous avez envoyé le ticket!'
	),
	'suport-03' => array(
		'EN' => 'The answer must contain at least 2 characters!',
		'RO' => 'Die Antwort muss mindestens 2 Zeichen lang sein!',
		'FR' => 'La réponse doit contenir au moins 2 carractères!'
	),
	'suport-04' => array(
		'EN' => 'You sent an answer!',
		'RO' => 'Du hast eine Antwort gesendet!',
		'FR' => 'Vous avez envoyé une réponse'
	),
	'suport-05' => array(
		'EN' => 'You deleted an answer!',
		'RO' => 'Du hast eine Antwort gelöscht!',
		'FR' => 'Vous avez supprimé une réponse!'
	),
	'suport-06' => array(
		'EN' => 'Support tickets',
		'RO' => 'Support-Tickets',
		'FR' => 'Support tickets'
	),
	'suport-07' => array(
		'EN' => 'Title',
		'RO' => 'Titel',
		'FR' => 'Titre'
	),
	'suport-08' => array(
		'EN' => 'Category',
		'RO' => 'Kategorie',
		'FR' => 'Catégorie'
	),
	'suport-09' => array(
		'EN' => 'Opened',
		'RO' => 'Offen',
		'FR' => 'Ouvert'
	),
	'suport-10' => array(
		'EN' => 'Resolved',
		'RO' => 'Gelöst',
		'FR' => 'Résolu'
	),
	'suport-11' => array(
		'EN' => 'Resolved by',
		'RO' => 'Gelöst von',
		'FR' => 'XXXRésolu par'
	),
	'suport-12' => array(
		'EN' => 'Close',
		'RO' => 'Schließen',
		'FR' => 'Fermer'
	),
	'suport-13' => array(
		'EN' => 'Open',
		'RO' => 'Öffnen',
		'FR' => 'Ouvert'
	),
	'suport-14' => array(
		'EN' => 'Answer',
		'RO' => 'Antworten',
		'FR' => 'Répondre'
	),
	'suport-15' => array(
		'EN' => 'Answers',
		'RO' => 'Antworten',
		'FR' => 'Réponses'
	),
	'suport-16' => array(
		'EN' => 'Support panel',
		'RO' => 'Support Panel',
		'FR' => 'Panneau support'
	),
	'suport-17' => array(
		'EN' => 'Hide closed tickets',
		'RO' => 'Geschlossene Tickets verbergen',
		'FR' => 'Cacher les tickets fermés'
	),
	'suport-18' => array(
		'EN' => 'Show closed tickets',
		'RO' => 'Geschlossene Tickets zeigen',
		'FR' => 'Voir les tickets fermés'
	),
	'suport-19' => array(
		'EN' => 'Tickets',
		'RO' => 'Tickets',
		'FR' => 'Tickets'
	),
	'suport-20' => array(
		'EN' => 'New Ticket',
		'RO' => 'Neues Ticket',
		'FR' => 'Nouveau Ticket'
	),
	'suport-21' => array(
		'EN' => 'Title must contain at least -NUM- characters.',
		'RO' => 'Der Titel muss mindestens -NUM- Zeichen enthalten.',
		'FR' => 'Le titre doit contenir au moins -NUM- carractères.'
	),
	'suport-22' => array(
		'EN' => 'Title cannot contain more than -NUM- characters.',
		'RO' => 'Der Titel darf nicht mehr als -NUM- Zeichen enthalten.',
		'FR' => 'Le titre ne peut pas contenir plus de -NUM- carractères.'
	),
	'suport-23' => array(
		'EN' => 'Text must contain at least -NUM- characters.',
		'RO' => 'Der Text muss mindestens -NUM- Zeichen enthalten.',
		'FR' => 'Le texte doit contenir au moins -NUM- carractères.'
	),
	'suport-24' => array(
		'EN' => 'Wrong category!',
		'RO' => 'Falsche Kategorie!',
		'FR' => 'Mauvaise catégorie!'
	),
	'suport-25' => array(
		'EN' => 'New ticket',
		'RO' => 'Neues Ticket',
		'FR' => 'Nouveau ticket'
	),
	'suport-26' => array(
		'EN' => 'Open ticket',
		'RO' => 'Ticket öffnen',
		'FR' => 'Ouvrir un ticket'
	),
	'suport-27' => array(
		'EN' => 'Opened tickets',
		'RO' => 'Offene Tickets',
		'FR' => 'Tickets ouverts'
	),

	// Firma
	'comp-01' => array(
		'EN' => 'Founded',
		'RO' => 'Gegründet',
		'FR' => 'Fondée'
	),
	'comp-02' => array(
		'EN' => 'More',
		'RO' => 'Mehr',
		'FR' => 'Plus'
	),
	'comp-03' => array(
		'EN' => 'Admin',
		'RO' => 'Admin',
		'FR' => 'Administrateur'
	),
	'comp-04' => array(
		'EN' => 'Accept applications',
		'RO' => 'Bewerbungen annehmen',
		'FR' => 'Accepte les demandes'
	),
	'comp-05' => array(
		'EN' => 'Other info',
		'RO' => 'Sonstige Infos',
		'FR' => 'Autre info'
	),
	'comp-06' => array(
		'EN' => 'Registration Fee',
		'RO' => 'Registrierungsgebühr',
		'FR' => 'Taxe d\'inscription'
	),
	'comp-07' => array(
		'EN' => 'Transfer Fee',
		'RO' => 'Überweisunggebühr',
		'FR' => 'Taxe de transfert'
	),
	'comp-08' => array(
		'EN' => 'Customers',
		'RO' => 'Kunden',
		'FR' => 'Clients'
	),
	'comp-09' => array(
		'EN' => 'Fees',
		'RO' => 'Provisionen',
		'FR' => 'Taxes'
	),
	'comp-10' => array(
		'EN' => 'Show Fees',
		'RO' => 'Provisionen anzeigen',
		'FR' => 'Voir les taxes'
	),
	'comp-11' => array(
		'EN' => 'From',
		'RO' => 'Von',
		'FR' => 'De'
	),
	'comp-12' => array(
		'EN' => 'To',
		'RO' => 'Bis',
		'FR' => 'A'
	),
	'comp-13' => array(
		'EN' => 'Percent',
		'RO' => 'Prozent',
		'FR' => 'Pourcent'
	),
	'comp-14' => array(
		'EN' => 'Published newspapers',
		'RO' => 'Veröffentlichte Zeitungen',
		'FR' => 'Journeaux publiés'
	),
	'comp-15' => array(
		'EN' => 'Bought',
		'RO' => 'Gekauft',
		'FR' => 'Acheté'
	),
	'comp-16' => array(
		'EN' => 'Journalists',
		'RO' => 'Journalist',
		'FR' => 'Journaliste'
	),
	'comp-17' => array(
		'EN' => 'Now you\'re journalist at this newspaper company!',
		'RO' => 'Jetzt bist du Journalist bei dieser Zeitungsfirma!',
		'FR' => 'Vous êtes maintenant journaliste dans cette maison de presse!'
	),
	'comp-18' => array(
		'EN' => 'You refused the invitation to become a journalist!',
		'RO' => 'Du hast das Angebot, Journalist zu werden, abgelehnt!',
		'FR' => 'Vous avez refusé l\'invitation à devenir journaliste!'
	),
	'comp-19' => array(
		'EN' => 'You have been invited to become a journalist!',
		'RO' => 'Du hast ein Angebot bekommen, Journalist zu werden!',
		'FR' => 'Vous avez été invité à devenir journaliste!'
	),
	'comp-20' => array(
		'EN' => 'Accept',
		'RO' => 'Annehmen',
		'FR' => 'Accepter'
	),
	'comp-21' => array(
		'EN' => 'Decline',
		'RO' => 'Ablehnen',
		'FR' => 'Refuser'
	),
	'comp-22' => array(
		'EN' => 'You are journalist for this company!',
		'RO' => 'Du bist Journalist für diese Firma!',
		'FR' => 'Vous êtes journaliste pour cette Maison de presse!'
	),
	'comp-23' => array(
		'EN' => 'Control panel',
		'RO' => 'Control-Panel',
		'FR' => 'Panneau de contrôle'
	),
	'comp-24' => array(
		'EN' => 'Control panel',
		'RO' => 'Control-Panel',
		'FR' => 'Panneau de contrôle'
	),
	'comp-25' => array(
		'EN' => 'Company',
		'RO' => 'Firma',
		'FR' => 'Société / Entreprise'
	),
	'comp-26' => array(
		'EN' => 'Sales',
		'RO' => 'Vertrieb',
		'FR' => 'Ventes'
	),
	'comp-27' => array(
		'EN' => 'Bought?',
		'RO' => 'Gekauft?',
		'FR' => 'Acheté?'
	),
	'comp-28' => array(
		'EN' => 'Read Newspaper',
		'RO' => 'Zeitung lesen',
		'FR' => 'Lire le journal'
	),
	'comp-29' => array(
		'EN' => 'You bought the newspaper!',
		'RO' => 'Du hast die Zeitung gekauft!',
		'FR' => 'Vous avez acheté le journal!'
	),
	'comp-30' => array(
		'EN' => 'Newspaper articles',
		'RO' => 'Zeitungsartikel',
		'FR' => 'Articles du journal'
	),
	'comp-31' => array(
		'EN' => 'Added by -PLAYER-, at <span>-TIME-</span>',
		'RO' => 'Hinzugefügt von -PLAYER-, am <span>-TIME-</span>',
		'FR' => 'Ajouté par -PLAYER-, à <span>-TIME-</span>'
	),
	'comp-32' => array(
		'EN' => 'Published by',
		'RO' => 'Herausgegeben von',
		'FR' => 'Publié par'
	),
	'comp-33' => array(
		'EN' => 'You cannot leave the company!',
		'RO' => 'Du kannst die Firma nicht verlassen!',
		'FR' => 'Vous ne pouvez pas quitter la compagnie!'
	),
	'comp-34' => array(
		'EN' => 'Article wrong!',
		'RO' => 'Artikel falsch!',
		'FR' => 'Article faux!'
	),
	'comp-35' => array(
		'EN' => 'You sent an article!',
		'RO' => 'Du hast einen Artikel gesendet!',
		'FR' => 'Vous avez envoyé un article!'
	),
	'comp-36' => array(
		'EN' => 'Resign',
		'RO' => 'Zurücktreten',
		'FR' => 'Démissionner'
	),
	'comp-37' => array(
		'EN' => 'Submit an article',
		'RO' => 'Einen Artikel einreichen',
		'FR' => 'Soumettre un article'
	),
	'comp-38' => array(
		'EN' => 'Text',
		'RO' => 'Text',
		'FR' => 'Texte'
	),
	'comp-39' => array(
		'EN' => 'This company is bankrupt and will be closed in -TIME-!<br />To keep the company must have at least $ 1 in the company account!',
		'RO' => 'Diese Firma ist pleite und wird in -TIME- geschlossen!<br />Um die Firma zu halten, muss mindestes 1$ auf dem Firmenkonto sein!',
		'FR' => 'Cette société est en faillite et sera fermée -TIME-!<br />Pour garder l\'entreprise, le compte en banque doit avoir au moins 1$!'
	),
	'comp-40' => array(
		'EN' => 'Founds',
		'RO' => 'Gegründet',
		'FR' => 'Fonder'
	),
	'comp-41' => array(
		'EN' => 'Settings',
		'RO' => 'Einstellungen',
		'FR' => 'Paramètres'
	),
	'comp-42' => array(
		'EN' => 'Logs',
		'RO' => 'Ereignisse',
		'FR' => 'Historique'
	),
	'comp-43' => array(
		'EN' => 'Transfers',
		'RO' => 'Transfers',
		'FR' => 'Transferts'
	),
	'comp-44' => array(
		'EN' => 'Fees',
		'RO' => 'Gebühren',
		'FR' => 'Taxes'
	),
	'comp-45' => array(
		'EN' => 'Customers',
		'RO' => 'Kunden',
		'FR' => 'Clients'
	),
	'comp-46' => array(
		'EN' => 'Print Newspaper',
		'RO' => 'Zeitung drucken',
		'FR' => 'Imprimer le journal'
	),
	'comp-47' => array(
		'EN' => 'Newspapers',
		'RO' => 'Zeitungen',
		'FR' => 'Journeaux'
	),
	'comp-48' => array(
		'EN' => 'Create bullets',
		'RO' => 'Munition herstellen',
		'FR' => 'Fabriquer des cartouches'
	),
	'comp-49' => array(
		'EN' => 'Last -NUM- logs',
		'RO' => 'Letzten -NUM- Ereignisse',
		'FR' => 'Derniers -NUM- historiques'
	),
	'comp-50' => array(
		'EN' => 'You can\'t withdraw these money!',
		'RO' => 'Du kannst dieses Geld nicht abheben!',
		'FR' => 'Vous ne pouvez pas retirer cet argent!'
	),
	'comp-51' => array(
		'EN' => 'You must withdraw at least $1',
		'RO' => 'Du musst mindestens 1$ abheben',
		'FR' => 'Vous devez retirer au moins 1$'
	),
	'comp-52' => array(
		'EN' => 'You withdrew -CASH- $ from company!',
		'RO' => 'Du hast -CASH- $ von der Firma abgehoben!',
		'FR' => 'Vous avez retiré -CASH- $ de votre société!'
	),
	'comp-53' => array(
		'EN' => 'You cannot deposit so much!',
		'RO' => 'Du kannst nicht so viel einzahlen!',
		'FR' => 'Vous ne pouvez pas déposer autant d\'argent!'
	),
	'comp-54' => array(
		'EN' => 'You must deposit at least $1',
		'RO' => 'Du musst mindestens 1$ einzahlen!',
		'FR' => 'Vous devez déposer au moins 1$'
	),
	'comp-55' => array(
		'EN' => 'You deposited -CASH- $ on company!',
		'RO' => 'Du hast -CASH- $ in die Firma eingezahlt!',
		'FR' => 'Vous avez déposé -CASH- $ dans le compte de la société!'
	),
	'comp-56' => array(
		'EN' => 'Company Account',
		'RO' => 'Firmenkonto',
		'FR' => 'Compte de la société'
	),
	'comp-57' => array(
		'EN' => 'The company was disbanded!',
		'RO' => 'Die Firma wurde aufgelöst!',
		'FR' => 'La société est dissoute!'
	),
	'comp-58' => array(
		'EN' => 'Dissolve the company',
		'RO' => 'Die Firma auflösen',
		'FR' => 'Dissoudre la société'
	),
	'comp-59' => array(
		'EN' => 'The transfer fee must be higher than -NUM-',
		'RO' => 'Die Überweisungsgebühr muss höher als  -NUM- sein',
		'FR' => 'Le montant du transfert doit être supérieur à -NUM'
	),
	'comp-60' => array(
		'EN' => 'The transfer fee must be less than -NUM-',
		'RO' => 'Die Überweisungsgebühr muss niedriger als -NUM- sein',
		'FR' => 'La taxe de transfert doit être inférieure à -NUM-'
	),
	'comp-61' => array(
		'EN' => 'Changes successfully saved!',
		'RO' => 'Änderungen erfolgreich gespeichert!',
		'FR' => 'Changements enregistrés avec succès!'
	),
	'comp-62' => array(
		'EN' => 'Money transfers',
		'RO' => 'Geldtransfers',
		'FR' => 'Transferts d\'argent'
	),
	'comp-63' => array(
		'EN' => 'Coins transfers',
		'RO' => 'Coin-Transfers',
		'FR' => 'Transferts de crédits'
	),
	'comp-64' => array(
		'EN' => 'Fee',
		'RO' => 'Gebühr',
		'FR' => 'Taxe'
	),
	'comp-65' => array(
		'EN' => 'and',
		'RO' => 'und',
		'FR' => 'et'
	),
	'comp-66' => array(
		'EN' => 'Between',
		'RO' => 'Zwischen',
		'FR' => 'Entre'
	),
	'comp-67' => array(
		'EN' => 'Payments',
		'RO' => 'Zahlungen',
		'FR' => 'Payments'
	),
	'comp-68' => array(
		'EN' => 'Average',
		'RO' => 'Durchschnitt',
		'FR' => 'Moyenne'
	),
	'comp-69' => array(
		'EN' => 'Percentage average',
		'RO' => 'Durchschnitt in%',
		'FR' => 'Moyenne en %'
	),
	'comp-70' => array(
		'EN' => 'Change fees',
		'RO' => 'Gebühr ändern',
		'FR' => 'Modifier taxe'
	),
	'comp-71' => array(
		'EN' => 'New item',
		'RO' => 'Neuer Artikel',
		'FR' => 'Nouvel article'
	),
	'comp-72' => array(
		'EN' => 'Customer cannot be removed!',
		'RO' => 'Kunde kann nicht entfernt werden!',
		'FR' => 'Le client ne peut être retiré!'
	),
	'comp-73' => array(
		'EN' => 'Customer has been removed!',
		'RO' => 'Kunde wurde entfernt!',
		'FR' => 'Le client a été retiré!'
	),
	'comp-74' => array(
		'EN' => 'Customer',
		'RO' => 'Kunde',
		'FR' => 'Client'
	),
	'comp-75' => array(
		'EN' => 'Received fees',
		'RO' => 'Erhaltene Gebühren',
		'FR' => 'Taxe reçu'
	),
	'comp-76' => array(
		'EN' => 'We found <b>-NUM-</b> customers.',
		'RO' => 'Wir haben <b>-NUM-</b> Kunden gefunden.',
		'FR' => 'Nous avons trouvé <b>-NUM-</b> clients.'
	),
	'comp-77' => array(
		'EN' => 'Customers have been removed!',
		'RO' => 'Kunden wurden entfernt!',
		'FR' => 'Les clients sont retirés!'
	),
	'comp-78' => array(
		'EN' => 'The fee can not be less than $0',
		'RO' => 'Die Gebühr kann nicht unter 0$ liegen',
		'FR' => 'La taxe ne peut être inférieure à 0$'
	),
	'comp-79' => array(
		'EN' => 'You changed the registration fee!',
		'RO' => 'Du hast die Anmeldegebühr geändert!',
		'FR' => 'Vous avez modifié la taxe d\'inscription!'
	),
	'comp-80' => array(
		'EN' => 'No customer found!',
		'RO' => 'Kein Kunde gefunden!',
		'FR' => 'Aucun client trouvé!'
	),
	'comp-81' => array(
		'EN' => 'Search customers',
		'RO' => 'Kunden suchen',
		'FR' => 'Chercher des clients'
	),
	'comp-82' => array(
		'EN' => 'Press ENTER to search',
		'RO' => 'Drücke ENTER um zu suchen',
		'FR' => 'Présser ENTER pour lancer la recherche'
	),
	'comp-83' => array(
		'EN' => 'Active applications',
		'RO' => 'Aktive Anwendungen',
		'FR' => 'Demandes en cours'
	),
	'comp-84' => array(
		'EN' => 'Change registration fee',
		'RO' => 'Anmeldegebühr ändern',
		'FR' => 'Modifier la taxe d\'enregistrement'
	),
	'comp-85' => array(
		'EN' => 'Bank accounts were approved and the bank received -CASH- $',
		'RO' => 'Die Konten wurden genehmigt und die Bank erhielt -CASH- $',
		'FR' => 'Les comptes banquaires sont approuvés, la banque reçoit -CASH- $'
	),
	'comp-86' => array(
		'EN' => 'The applications were refused!',
		'RO' => 'Die Bewerbungen wurden abgelehnt!',
		'FR' => 'La demande a été refusée!'
	),
	'comp-87' => array(
		'EN' => 'Approve all',
		'RO' => 'Alle genehmigen',
		'FR' => 'Accepter tous'
	),
	'comp-88' => array(
		'EN' => 'Reject all',
		'RO' => 'Alle ablehnen',
		'FR' => 'Rejeter tous'
	),
	'comp-89' => array(
		'EN' => 'Approve',
		'RO' => 'Genehmigen',
		'FR' => 'Approuver'
	),
	'comp-90' => array(
		'EN' => 'Reject',
		'RO' => 'Ablehnen',
		'FR' => 'Rejeter'
	),
	'comp-91' => array(
		'EN' => 'The company can\'t have more than -NUM- unpublished newspapers!',
		'RO' => 'Das Unternehmen kann nicht mehr als -NUM- unveröffentlichte Zeitungen haben!',
		'FR' => 'La Société ne peut avoir plus de -NUM- journeaux non publiés!'
	),
	'comp-92' => array(
		'EN' => 'You created an newspaper!',
		'RO' => 'Du hast eine Zeitung erstellt!',
		'FR' => 'Vous avez créé un journal!'
	),
	'comp-93' => array(
		'EN' => 'Image',
		'RO' => 'Bild',
		'FR' => 'Image'
	),
	'comp-94' => array(
		'EN' => 'The newspaper was deleted!',
		'RO' => 'Die Zeitung wurde gelöscht!',
		'FR' => 'Le journal est suprimé!'
	),
	'comp-95' => array(
		'EN' => 'You cannot publish an newspaper without articles!',
		'RO' => 'Du kannst keine Zeitung ohne Artikel veröffentlichen!',
		'FR' => 'ous ne pouvez pas publier un journal sans articles!'
	),
	'comp-96' => array(
		'EN' => 'The newspaper was published!',
		'RO' => 'Die Zeitung wurde veröffentlicht!',
		'FR' => 'Le journal a été publié!'
	),
	'comp-97' => array(
		'EN' => 'The newspaper wasn\'t published!',
		'RO' => 'Die Zeitung wurde nicht veröffentlicht!',
		'FR' => 'Le journal n\'a pas été publié!'
	),
	'comp-98' => array(
		'EN' => 'Newspaper',
		'RO' => 'Zeitung',
		'FR' => 'Journal'
	),
	'comp-99' => array(
		'EN' => 'Published',
		'RO' => 'Veröffentlicht',
		'FR' => 'Publié'
	),
	'comp-100' => array(
		'EN' => 'Publish newspaper',
		'RO' => 'Zeitung veröffentlichen',
		'FR' => 'Publier le journal'
	),
	'comp-101' => array(
		'EN' => 'Remove newspaper',
		'RO' => 'Zeitung raus nehmen',
		'FR' => 'Retirer le journal'
	),
	'comp-102' => array(
		'EN' => 'Preview',
		'RO' => 'Vorschau',
		'FR' => 'Prévisialiser'
	),
	'comp-103' => array(
		'EN' => 'Read the newspaper',
		'RO' => 'Zeitung lesen',
		'FR' => 'Lire le journal'
	),
	'comp-104' => array(
		'EN' => 'Change newspaper',
		'RO' => 'Zeitung ändern',
		'FR' => 'Changer de journal'
	),
	'comp-105' => array(
		'EN' => 'You cannot edit a published newspaper!',
		'RO' => 'Du kannst eine veröffentlichte Zeitung nicht bearbeiten!',
		'FR' => 'Vous ne pouvez pas éditer un journal publié!'
	),
	'comp-106' => array(
		'EN' => 'Articles',
		'RO' => 'Artikel',
		'FR' => 'Articles'
	),
	'comp-107' => array(
		'EN' => 'Article successfully added!',
		'RO' => 'Artikel erfolgreich hinzugefügt!',
		'FR' => 'Article ajouté avec succès!'
	),
	'comp-108' => array(
		'EN' => 'Article successfully removed!',
		'RO' => 'Artikel erfolgreich entfernt!',
		'FR' => 'Article retiré avec succès!'
	),
	'comp-109' => array(
		'EN' => 'Article',
		'RO' => 'Artikel',
		'FR' => 'Article'
	),
	'comp-110' => array(
		'EN' => 'Reporter',
		'RO' => 'Journalist',
		'FR' => 'Journaliste'
	),
	'comp-111' => array(
		'EN' => 'Add',
		'RO' => 'hinzufügen',
		'FR' => 'Ajouter'
	),
	'comp-112' => array(
		'EN' => 'Remove',
		'RO' => 'löschen',
		'FR' => 'Retirer'
	),
	'comp-113' => array(
		'EN' => 'Change article',
		'RO' => 'Artikel ändern',
		'FR' => 'Modifier l\'article'
	),
	'comp-114' => array(
		'EN' => 'Unpublished',
		'RO' => 'Unveröffentlicht',
		'FR' => 'Non publié'
	),
	'comp-115' => array(
		'EN' => 'Published',
		'RO' => 'Veröffentlicht',
		'FR' => 'Publié'
	),
	'comp-116' => array(
		'EN' => 'You cannot fire yourself!',
		'RO' => 'Du kannst dich nicht selbst feuern!',
		'FR' => 'Vous ne pouvez pas vous licencier!'
	),
	'comp-117' => array(
		'EN' => 'This player cannot be removed!',
		'RO' => 'Dieser Spieler kann nicht entfernt werden!',
		'FR' => 'Ce joueur ne peut pas être retiré!'
	),
	'comp-118' => array(
		'EN' => 'The journalist was fired!',
		'RO' => 'Der Journalist wurde gefeuert!',
		'FR' => 'Le journaliste a été licencié!'
	),
	'comp-119' => array(
		'EN' => 'You cannot invite a dead player!',
		'RO' => 'Du kannst keinen toten Spieler einladen!',
		'FR' => 'Vous ne pouvez pas inviter un joueur mort!'
	),
	'comp-120' => array(
		'EN' => 'The player is journalist or has been already invited!',
		'RO' => 'Der Spieler ist Journalist oder wurde bereits eingeladen!',
		'FR' => 'Le joueur est journaliste ou a déjà été invité!'
	),
	'comp-121' => array(
		'EN' => 'The player was invited!',
		'RO' => 'Der Spieler wurde eingeladen!',
		'FR' => 'Le joueur a été invité!'
	),
	'comp-122' => array(
		'EN' => 'The invitation was removed!',
		'RO' => 'Einladung wurde zurückgezogen!',
		'FR' => 'L\'invitation est retirée!'
	),
	'comp-123' => array(
		'EN' => 'Invite',
		'RO' => 'Einladen',
		'FR' => 'Inviter'
	),
	'comp-124' => array(
		'EN' => 'Sent invites',
		'RO' => 'Gesendete Einladungen',
		'FR' => 'Invitation envoyée'
	),
	'comp-125' => array(
		'EN' => 'You must create at least one bullet!',
		'RO' => 'Du musst mindestens 1 Munition herstellen!',
		'FR' => 'ous devez fabriquer au moins 1 cartouche!'
	),
	'comp-126' => array(
		'EN' => 'The company doesn\'t have enough bullets!',
		'RO' => 'Die Firma hat nicht genug Munition!',
		'FR' => 'La société n\'a pas assez de cartouches!'
	),
	'comp-127' => array(
		'EN' => '<b>-NUM- bullets</b> were added for sale!',
		'RO' => '<b>-NUM- Munition</b> wurden zum Verkauf hinzugefügt!',
		'FR' => '<b>-NUM- cartouches</b> ajoutées à la vente!'
	),
	'comp-128' => array(
		'EN' => 'The company cannot produce more than <b>-BULLETS- bullets</b> in the same time!',
		'RO' => 'Die Firma kann nicht mehr als <b>-BULLETS- Munition</b> gleichzeitig produzieren!',
		'FR' => 'La société ne peut fabriques plus que de <b>-BULLETS- cartouches</b> en simultané!'
	),
	'comp-129' => array(
		'EN' => 'Not enough money to produce so many bullets!',
		'RO' => 'Nicht genug Geld, um so viel Munition zu produzieren!',
		'FR' => 'Pas assez d\'argent pour produire autant de cartouches!'
	),
	'comp-130' => array(
		'EN' => 'Bullets were manufactured successfully!',
		'RO' => 'Munition wurde erfolgreich hergestellt!',
		'FR' => 'Les cartouches ont été fabriquées avec succès!'
	),
	'comp-131' => array(
		'EN' => 'Sell bullets',
		'RO' => 'Munition verkaufen',
		'FR' => 'Vendre des cartouches'
	),
	'comp-132' => array(
		'EN' => 'The factory produced <b>-BULLETS1- bullets</b> (-BULLETS2- bullets for sale).',
		'RO' => 'Die Fabrik produziert  <b>-BULLETS1- Munition</b> (-BULLETS2- Munition zum Verkauf).',
		'FR' => 'L\'usine produit <b>-BULLETS1- cartouches</b> (-BULLETS2- cartouches à vendre).'
	),
	'comp-133' => array(
		'EN' => 'Application content',
		'RO' => 'Grund der Anfrage',
		'FR' => 'Contenu de la demande'
	),
	'comp-134' => array(
		'EN' => 'The player was fired!',
		'RO' => 'Der Spieler wurde gefeuert!',
		'FR' => 'Le joueur a été licencié!'
	),
	'comp-135' => array(
		'EN' => 'No changes were found!',
		'RO' => 'Es wurden keine Änderungen gefunden!',
		'FR' => 'Aucun changement trouvé!'
	),
	'comp-136' => array(
		'EN' => 'Transfer Fee',
		'RO' => 'Überweisungsgebühren',
		'FR' => 'Frais de transfert'
	),
	'comp-137' => array(
		'EN' => 'Deposit Fee',
		'RO' => 'Anmeldegebühr',
		'FR' => 'Taxe de dépôt'
	),
	'comp-138' => array(
		'EN' => 'Filing fee shall not be less than -NUM-%',
		'RO' => 'Die Anmeldegebühr muss mindestens -NUM-% betragen',
		'FR' => 'Taxe de dépôt ne doit pas être inférieure à -NUM-%'
	),
	'comp-139' => array(
		'EN' => 'Fees',
		'RO' => 'Gebühren',
		'FR' => 'Taxe'
	),
	'comp-140' => array(
		'EN' => 'Total Deposits',
		'RO' => 'Einzahlungen gesamt',
		'FR' => 'Les dépôts effectués'
	),
	'comp-141' => array(
		'EN' => 'Deposit Fees',
		'RO' => 'Anmeldegebühr',
		'FR' => 'Taxe de dépôt'
	),

	// Begrenzt
	'limit-01' => array(
		'EN' => 'Your account has 0% health (the player is dead), you\'ve probably been killed by another player.',
		'RO' => 'Dein Account hat 0% Gesundheit (der Spieler ist tot), du wurdest wahrscheinlich von einem anderen Spieler gekillt.',
		'FR' => 'Votre compte est à 0% de santé <b>Vous êtes mort</b>, vous avez probablement été tué par un autre joueur.'
	),
	'limit-02' => array(
		'EN' => 'Your account has been banned.',
		'RO' => 'Dein Account wurde gesperrt.',
		'FR' => 'Votre compte a été banni.'
	),
	'limit-03' => array(
		'EN' => 'You have limited access to the game. Why?',
		'RO' => 'Du hast nur eingeschränkten Zugriff auf das Spiel. Warum?',
		'FR' => 'Vous avez un accès limité au jeu, pourquoi?'
	),
	'limit-04' => array(
		'EN' => 'Reason',
		'RO' => 'Grund',
		'FR' => 'Raison'
	),
	'limit-05' => array(
		'EN' => 'Revive',
		'RO' => 'Reanimieren',
		'FR' => 'Réanimer'
	),
	'limit-06' => array(
		'EN' => 'You have created a new player account!',
		'RO' => 'Du hast ein neues Spielerkonto erstellt!',
		'FR' => 'Vous avez créé un nouveau compte joueur!'
	),
	'limit-07' => array(
		'EN' => 'You have activated your account with coins!',
		'RO' => 'Du hast dein Konto mit Coins aktiviert!',
		'FR' => 'ous avez réactivé votre compte avec des crédits!'
	),
	'limit-08' => array(
		'EN' => 'You have 2 ways to keep your current account.',
		'RO' => 'du hast 2 Möglichkeiten, um dein Konto zu behalten.',
		'FR' => 'ous avez 2 possibilités pour garder votre compte.'
	),
	'limit-09' => array(
		'EN' => 'Pay -NUM- coins',
		'RO' => 'Bezahle -NUM- Coins',
		'FR' => 'Réglez -NUM- Crédits'
	),
	'limit-10' => array(
		'EN' => 'Pay -NUM- &euro;',
		'RO' => 'Bezahle -NUM- &euro;',
		'FR' => 'Régler -NUM- &euro;'
	),
	'limit-11' => array(
		'EN' => 'Pay by',
		'RO' => 'Zahlung per',
		'FR' => 'Payer par'
	),
	'limit-12' => array(
		'EN' => 'Send an SMS ...',
		'RO' => 'Sende eine SMS ...',
		'FR' => 'Envoyez un SMS ...'
	),
	'limit-13' => array(
		'EN' => 'New player',
		'RO' => 'Neuer Spieler',
		'FR' => 'Nouveau joueur'
	),
	'limit-14' => array(
		'EN' => 'Player name',
		'RO' => 'Spielername',
		'FR' => 'Nom du joueur'
	),
	'limit-15' => array(
		'EN' => 'Keep your picture',
		'RO' => 'Behalte dein Bild',
		'FR' => 'Garder votre image'
	),
	'limit-16' => array(
		'EN' => 'Keep your description',
		'RO' => 'Behalte deine Beschreibung',
		'FR' => 'Garder votre description'
	),
	'limit-17' => array(
		'EN' => 'Keep your signature',
		'RO' => 'Behalte deine Signatur',
		'FR' => 'Garder votre signature'
	),
	'limit-18' => array(
		'EN' => 'Your account is inactive! The reason can be:',
		'RO' => 'Dein Konto ist inaktiv. Dies kann folgende Gründe haben:',
		'FR' => 'Votre compte est inactif, la raison peut être:'
	),
	
	// 404
	'404-01' => array(
		'EN' => 'Page not found!',
		'RO' => 'Ops.. Diese Seite wurde nicht gefunden!',
		'FR' => 'Page non trouvée!'
	),
	'404-02' => array(
		'EN' => 'If you accessed this page by clicking a link in the menu, please contact the administrator.',
		'RO' => 'Wenn du diese Seite durch Klicken auf einen Link im Menü aufgerufen hast, wende dich bitte an den Administrator.',
		'FR' => 'Si vous accédez à cette page en cliquant sur un lien du menu, contactez un administrateur.'
	),
	
	// Familie
	'fam-01' => array(
		'EN' => 'The family name is invalid!',
		'RO' => 'Der Familienname ist ungültig!',
		'FR' => 'Nom de famille invalide!'
	),
	'fam-02' => array(
		'EN' => 'Cannot be created other families in this city!',
		'RO' => 'Kann keine weitere Familie in dieser Stadt gegründet werden!',
		'FR' => 'Ne peut créer une autre famille dans cette ville!'
	),
	'fam-03' => array(
		'EN' => 'This family name is already used!',
		'RO' => 'Dieser Familienname wird bereits verwendet!',
		'FR' => 'Ce nom de famille est déjà utilisé!'
	),
	'fam-04' => array(
		'EN' => 'The family was successfully created!',
		'RO' => 'Die Familie wurde erfolgreich gegründet!',
		'FR' => 'La famille est crée avec succès'
	),
	'fam-05' => array(
		'EN' => 'Create a Family',
		'RO' => 'Eine Familie gründen',
		'FR' => 'Créer une famille'
	),
	'fam-06' => array(
		'EN' => 'You are already in a family!',
		'RO' => 'Du bist bereits in einer Familie!',
		'FR' => 'Vous êtes déjà dans une famille!'
	),
	'fam-07' => array(
		'EN' => 'Your rank must be at least -RANK- to create a family.',
		'RO' => 'Du musst mindestens den Rang -RANK- haben, um eine Familie zu gründen.',
		'FR' => 'Votre rang doit être au moins -RANK- pour créer une famille.'
	),
	'fam-08' => array(
		'EN' => 'Type',
		'RO' => 'Art',
		'FR' => 'Catégorie'
	),
	'fam-09' => array(
		'EN' => 'Create',
		'RO' => 'Gründen',
		'FR' => 'Créer'
	),
	'fam-10' => array(
		'EN' => 'Family Business',
		'RO' => 'Familien-Business',
		'FR' => 'Buziness de famille'
	),
	'fam-11' => array(
		'EN' => 'Owned by',
		'RO' => 'Eigentümer',
		'FR' => 'Propriétaire'
	),
	'fam-12' => array(
		'EN' => 'Guardians',
		'RO' => 'Wächter',
		'FR' => 'Gardiens'
	),
	'fam-13' => array(
		'EN' => 'Family Attacks',
		'RO' => 'Familienangriffe',
		'FR' => 'Attaques de famille'
	),
	'fam-14' => array(
		'EN' => 'Aggressor',
		'RO' => 'Aggressor',
		'FR' => 'Aggresseur'
	),
	'fam-15' => array(
		'EN' => 'Target',
		'RO' => 'Ziel',
		'FR' => 'Cible'
	),
	'fam-16' => array(
		'EN' => 'Members',
		'RO' => 'Mitglieder',
		'FR' => 'Membres'
	),
	'fam-17' => array(
		'EN' => 'Family does not exist!',
		'RO' => 'Familie existiert nicht!',
		'FR' => 'La famille n\'existe pas!'
	),
	'fam-18' => array(
		'EN' => 'There are no more available places!',
		'RO' => 'Es sind keine Plätze mehr frei!',
		'FR' => 'Il n\'y a pas de places disponible!'
	),
	'fam-19' => array(
		'EN' => 'You joined this family!',
		'RO' => 'Du bist dieser Familie beigetreten!',
		'FR' => 'Vous avez rejoint cette famille!'
	),
	'fam-20' => array(
		'EN' => 'Joined',
		'RO' => 'Beitreten',
		'FR' => 'Inscrit'
	),
	'fam-21' => array(
		'EN' => 'Leader',
		'RO' => 'Anführer',
		'FR' => 'Patron'
	),
	'fam-22' => array(
		'EN' => 'Adviser',
		'RO' => 'Berater',
		'FR' => 'Conseiller'
	),
	'fam-23' => array(
		'EN' => 'Vacancies',
		'RO' => 'Stellenangebote',
		'FR' => 'Offres d\'emploi'
	),
	'fam-24' => array(
		'EN' => 'Killed players',
		'RO' => 'Ermordete Spieler',
		'FR' => 'Crimes'
	),
	'fam-25' => array(
		'EN' => 'The family has -NUM- territories',
		'RO' => 'Die Familie hat -NUM- Territorien',
		'FR' => 'La famille a -NUM- territoires'
	),
	'fam-26' => array(
		'EN' => 'You was invited in this family!',
		'RO' => 'Du wurdest eingeladen, sich der Familie anzuschließen!',
		'FR' => 'Vous avez été invité à rejoindre la famille!'
	),
	'fam-27' => array(
		'EN' => 'Accept',
		'RO' => 'Einladung annehmen',
		'FR' => 'Accepter l\'invitation'
	),
	'fam-28' => array(
		'EN' => 'Territories',
		'RO' => 'Territorien',
		'FR' => 'Territoires'
	),
	'fam-29' => array(
		'EN' => 'Donate',
		'RO' => 'Spenden',
		'FR' => 'Donner'
	),
	'fam-30' => array(
		'EN' => 'Leave',
		'RO' => 'Verlassen',
		'FR' => 'Quitter'
	),
	'fam-31' => array(
		'EN' => 'Must donate at least $100',
		'RO' => 'Du musst mindestens 100$ spenden',
		'FR' => 'Vous devez donner au moins 100$'
	),
	'fam-32' => array(
		'EN' => 'You donated <b>-CASH- $</b> to family!',
		'RO' => 'Du hast <b>-CASH- $</b> an die Familie gespendet!',
		'FR' => 'Vous avez donné <b>-CASH- $</b> à la famille!'
	),
	'fam-33' => array(
		'EN' => 'My donations',
		'RO' => 'Meine Spenden',
		'FR' => 'Mes dons'
	),
	'fam-34' => array(
		'EN' => 'You are the family leader, and you cannot leave the family!',
		'RO' => 'du bist das Familienoberhaupt und kannst die Familie nicht verlassen!',
		'FR' => 'Vous êtes le BOSS de la famille, vous ne pouvez pas quitter votre famille!'
	),
	'fam-35' => array(
		'EN' => 'You left the family!',
		'RO' => 'Du hast die Familie verlassen!',
		'FR' => 'Vous avez quitté la famille!'
	),
	'fam-36' => array(
		'EN' => 'Leave family!',
		'RO' => 'Familie verlassen',
		'FR' => 'Quitter la famille'
	),
	'fam-37' => array(
		'EN' => 'You are not in the family business!',
		'RO' => 'Du bist nicht im Familienunternehmen!',
		'FR' => 'Vous ne faites pas parti de l\'entreprise familliale!'
	),
	'fam-38' => array(
		'EN' => 'Member of the family business!',
		'RO' => 'Mitglied des Familienunternehmens!',
		'FR' => 'Membre de l\'entreprise familliale!'
	),
	'fam-39' => array(
		'EN' => 'You aren\'t in any family!',
		'RO' => 'Du gehörst keiner Familie an!',
		'FR' => 'Vous n\'êtes dans aucune famille'
	),
	'fam-40' => array(
		'EN' => 'You don\'t have access to family management!',
		'RO' => 'Du hast keinen Zugang zur Familienverwaltung!',
		'FR' => 'Vous n\'avez pas accès au management de famille!'
	),
	'fam-41' => array(
		'EN' => 'Donations',
		'RO' => 'Spenden',
		'FR' => 'Dons'
	),
	'fam-42' => array(
		'EN' => 'Business',
		'RO' => 'Unternehmen',
		'FR' => 'Business'
	),
	'fam-43' => array(
		'EN' => 'Remove family',
		'RO' => 'Familie auflösen',
		'FR' => 'Suprimer la famille'
	),
	'fam-44' => array(
		'EN' => 'Attacks',
		'RO' => 'Angriffe',
		'FR' => 'Attaques'
	),
	'fam-45' => array(
		'EN' => 'Only the leader can withdraw money!',
		'RO' => 'Nur der Anführer kann Geld abheben!',
		'FR' => 'Seul le BOSS peut retirer de l\'argent!'
	),
	'fam-46' => array(
		'EN' => 'You deposited -CASH- $',
		'RO' => 'du hast -CASH- $ eingezahlt',
		'FR' => 'Vous avez déposé -CASH- $'
	),
	'fam-47' => array(
		'EN' => 'Balance',
		'RO' => 'Balance',
		'FR' => 'Solde'
	),
	'fam-48' => array(
		'EN' => 'You changed family information!',
		'RO' => 'Du hast die Familieninformationen geändert!',
		'FR' => 'Vous avez changé les informations de famille!'
	),
	'fam-49' => array(
		'EN' => 'You already invited this player!',
		'RO' => 'Du hast diesen Spieler bereits eingeladen!',
		'FR' => 'Vous avez déjà invité ce joueur!'
	),
	'fam-50' => array(
		'EN' => 'The player is member of the family!',
		'RO' => 'Der Spieler ist Mitglied der Familie!',
		'FR' => 'Le joueur est membre de la famille!'
	),
	'fam-51' => array(
		'EN' => 'You invited a player!',
		'RO' => 'Du hast einen Spieler eingeladen!',
		'FR' => 'Vous avez invité un joueur!'
	),
	'fam-52' => array(
		'EN' => 'The player is already in business!',
		'RO' => 'Der Spieler ist bereits im Familienunternehmen!',
		'FR' => 'Le joueur est déjà dans l\'entreprise familliale!'
	),
	'fam-53' => array(
		'EN' => 'There must be at least one guard for every company!',
		'RO' => 'Für jede Firma muss mindestens eine Wache vorhanden sein!',
		'FR' => 'Il doit y avoir au moin 1 Garde pour chaque société!'
	),
	'fam-54' => array(
		'EN' => 'Player no longer advisor!',
		'RO' => 'Der Spieler ist kein Berater mehr!',
		'FR' => 'Le joueur n\'est plus conseiller!'
	),
	'fam-55' => array(
		'EN' => 'The player is now advisor!',
		'RO' => 'Der Spieler ist jetzt Berater!',
		'FR' => 'Le joueur est maintenant Conseiller!'
	),
	'fam-56' => array(
		'EN' => 'is now guardian at',
		'RO' => 'ist jetzt Wächter bei',
		'FR' => 'est maintenant gardien au'
	),
	'fam-57' => array(
		'EN' => '-PLAYER- was fired!',
		'RO' => 'Du hast -PLAYER- aus deiner Familie gefeuert!',
		'FR' => '-PLAYER- a été licencié de la famille!'
	),
	'fam-58' => array(
		'EN' => 'This player is leader!',
		'RO' => 'Dieser Spieler ist der Anführer der Familie!',
		'FR' => 'Ce joueur est BOSS de famille!'
	),
	'fam-59' => array(
		'EN' => 'This player is advisor!',
		'RO' => 'Dieser Spieler ist der Berater der Familie!',
		'FR' => 'Ce joueur est conseiller de famille!'
	),
	'fam-60' => array(
		'EN' => 'Set Advisor',
		'RO' => 'Berater einstellen',
		'FR' => 'Passer conseiller'
	),
	'fam-61' => array(
		'EN' => 'Security',
		'RO' => 'Sicherheit',
		'FR' => 'Sécurité'
	),
	'fam-62' => array(
		'EN' => 'Business',
		'RO' => 'Unternehmen',
		'FR' => 'Business'
	),
	'fam-63' => array(
		'EN' => 'Guardian',
		'RO' => 'Wächter',
		'FR' => 'Gardien'
	),
	'fam-64' => array(
		'EN' => 'Details',
		'RO' => 'Details',
		'FR' => 'Détails'
	),
	'fam-65' => array(
		'EN' => 'Not enough money!',
		'RO' => 'Nicht genug Geld!',
		'FR' => 'Pas assez d\'argent!'
	),
	'fam-66' => array(
		'EN' => 'You bought the family business!',
		'RO' => 'du hast das Familienunternehmen gekauft!',
		'FR' => 'Vous avez acheté l\'entreprise familliale!'
	),
	'fam-67' => array(
		'EN' => 'You gave up business!',
		'RO' => 'Du hast die Firma aufgegeben!',
		'FR' => 'Vous avez abandonné l\'entreprise!'
	),
	'fam-68' => array(
		'EN' => 'Dissolve',
		'RO' => 'Auflösen',
		'FR' => 'Dissoudre'
	),
	'fam-69' => array(
		'EN' => 'The company is owned by another family!',
		'RO' => 'Das Unternehmen ist im Besitz einer anderen Familie!',
		'FR' => 'Cette société est la propriété d\'une autre famille!'
	),
	'fam-70' => array(
		'EN' => 'Not enough money in the family!',
		'RO' => 'Es ist nicht genug Geld auf dem Familienkonto!',
		'FR' => 'Pas assez d\'argent dans la famille!'
	),
	'fam-71' => array(
		'EN' => 'Invitations',
		'RO' => 'Einladungen',
		'FR' => 'Invitations'
	),
	'fam-72' => array(
		'EN' => 'Panel',
		'RO' => 'Panel',
		'FR' => 'Panneau de contrôle'
	),
	'fam-73' => array(
		'EN' => 'Economy',
		'RO' => 'Wirtschaft',
		'FR' => 'Economie'
	),
	'fam-74' => array(
		'EN' => 'Places',
		'RO' => 'Orte',
		'FR' => 'Lieux'
	),
	'fam-75' => array(
		'EN' => 'Maximum',
		'RO' => 'Maximum',
		'FR' => 'Maximum'
	),
	'fam-76' => array(
		'EN' => 'You cannot buy extra spaces for this company',
		'RO' => 'Du kannst keine zusätzlichen Flächen für diese Firma kaufen',
		'FR' => 'Vous ne pouvez pas acheter des espaces supplémentaires pour cette société'
	),
	'fam-77' => array(
		'EN' => 'More info',
		'RO' => 'Mehr Infos',
		'FR' => 'Plus d\'infos'
	),
	'fam-78' => array(
		'EN' => 'You deleted your family!',
		'RO' => 'Du hast deine Familie gelöscht!',
		'FR' => 'Vous avez suprimé votre famille!'
	),
	'fam-79' => array(
		'EN' => 'You cannot attack your own family!',
		'RO' => 'Du kannst deine eigene Familie nicht angreifen!',
		'FR' => 'Vous ne pouvez pas attaquer votre propre famille!'
	),
	'fam-80' => array(
		'EN' => 'The family is under protection!',
		'RO' => 'Die Familie steht unter Schutz!',
		'FR' => 'La famille est sous protection!'
	),
	'fam-81' => array(
		'EN' => 'Congratulations! Your family stole <b>-CASH- $</b>',
		'RO' => 'Herzlichen Glückwunsch! Deine Familie hat <b>-CASH- $</b> erbeutet',
		'FR' => 'Félicitation! Votre famille a volé <b>-CASH- $</b>'
	),
	'fam-82' => array(
		'EN' => 'Failed! The family was too strong and your family lost <b>-NUM- power</b>.',
		'RO' => 'Gescheitert! Die Familie war zu stark. Deine Familie verlor <b>-NUM- an Macht</b>.',
		'FR' => 'Echec! La famille est trop forte, vous perdez <b>-NUM- de Puissance</b>.'
	),
	'fam-83' => array(
		'EN' => 'The family did not attack yet!',
		'RO' => 'Die Familie hat noch nicht angegriffen!',
		'FR' => 'La famille n\'a pas encore attaqué!'
	),
	'fam-84' => array(
		'EN' => 'The last attack was given',
		'RO' => 'Der letzte Angriff wurde gegeben',
		'FR' => 'La dernière attaque a été donnée'
	),
	'fam-85' => array(
		'EN' => 'The family is under protection and cannot attacks!',
		'RO' => 'Die Familie steht unter Schutz und kann nicht angreifen!',
		'FR' => 'La famille est sous protection, elle ne peut pas attaquer!'
	),
	'fam-86' => array(
		'EN' => 'Attack',
		'RO' => 'Angreifen',
		'FR' => 'Attaquer'
	),
	'fam-87' => array(
		'EN' => 'Family power',
		'RO' => 'Familienmacht',
		'FR' => 'Puissance de famille'
	),
	'fam-88' => array(
		'EN' => 'Order by',
		'RO' => 'Sortieren',
		'FR' => 'Trier'
	),
	'fam-89' => array(
		'EN' => 'Crimes',
		'RO' => 'Verbrechen',
		'FR' => 'Crimes'
	),
	'fam-90' => array(
		'EN' => 'Upgrade',
		'RO' => 'Upgrade',
		'FR' => 'Expansion'
	),
	'fam-91' => array(
		'EN' => 'Family successfully upgraded!',
		'RO' => 'Die Familie wurde erfolgreich erweitert!',
		'FR' => 'La famille a été étendu avec succès!'
	),
	'fam-92' => array(
		'EN' => 'Your family is already fully upgraded!',
		'RO' => 'Ihre Familie ist bereits vollständig aufgerüstet!',
		'FR' => 'La famille est déjà à pleine capacité!'
	),
	'fam-93' => array(
		'EN' => 'Reject',
		'RO' => 'Einladung ablehnen',
		'FR' => 'Refuser l\'invitation'
	),
	'fam-94' => array(
		'EN' => 'The invitation was refused!',
		'RO' => 'Einladung wurde abgelehnt!',
		'FR' => 'L\'invitation a été refusé!'
	),
	'fam-95' => array(
		'EN' => 'Reset time',
		'RO' => 'Zeit zurücksetzen',
		'FR' => 'Temps de réarmement'
	),
	'fam-96' => array(
		'EN' => 'You need at least -NUM- coins to reset time!',
		'RO' => 'du musst mindestens -NUM- Coins haben, um die Zeit zurückzusetzen!',
		'FR' => 'Vous devez avoir au moins -NUM- crédits pour réinitialiser le temps!'
	),
	'fam-97' => array(
		'EN' => 'Waiting time was reseted!',
		'RO' => 'Die Wartezeit wurde zurückgesetzt!',
		'FR' => 'Le temps d\'attente a été remis à zéro!'
	),
	'fam-98' => array(
		'EN' => 'You can reset waiting time for <b>-NUM- coins</b>',
		'RO' => 'Du kannst die Wartezeit für <b>-NUM- Coins</b> zurücksetzen',
		'FR' => 'Vous pouvez réinitialiser le délai d\'attente pour <b>-NUM- crédits</b>'
	),
	
	// Forum
	'forum-01' => array(
		'EN' => 'You have been <b>permanently</b> banned on forum!',
		'RO' => 'Du wurdest <b>dauerhaft</b> für das Forum gesperrt!',
		'FR' => 'Vous avez été banni <b>permanent</b> du Forum!'
	),
	'forum-02' => array(
		'EN' => 'You have been banned on forum until <b>-TIME-</b>!',
		'RO' => 'Du wurdest für <b>-TIME-</b> für das Forum gesperrt!',
		'FR' => 'Vous êtes banni du Forum jusqu\'à <b>-TIME-</b>!'
	),
	'forum-03' => array(
		'EN' => 'Forum not found!',
		'RO' => 'Forum existiert nicht!',
		'FR' => 'Forum inexistant!'
	),
	'forum-04' => array(
		'EN' => 'You don\'t have access to this forum!',
		'RO' => 'Du hast keinen Zugang zu diesem Forum!',
		'FR' => 'Vous n\'avez pas accès à ce Forum!'
	),
	'forum-05' => array(
		'EN' => 'You don\'t have access to the forum!',
		'RO' => 'Du hast keinen Zugang zum Forum!',
		'FR' => 'Vous n\'avez pas accès au Forum!'
	),
	'forum-06' => array(
		'EN' => 'You emptied forum',
		'RO' => 'Du hast das Forum geleert',
		'FR' => 'Vous avez vidé le Forum'
	),
	'forum-07' => array(
		'EN' => 'Banned players',
		'RO' => 'Gesperrte Spieler',
		'FR' => 'Joueurs bannis'
	),
	'forum-08' => array(
		'EN' => 'Reported posts',
		'RO' => 'Gemeldete Beiträge',
		'FR' => 'Messages signalés'
	),
	'forum-09' => array(
		'EN' => 'Hide deleted topics',
		'RO' => 'Gelöschte Themen ausblenden',
		'FR' => 'Cacher les Sujets suprimés'
	),
	'forum-10' => array(
		'EN' => 'Show deleted topics',
		'RO' => 'Gelöschte Themen anzeigen',
		'FR' => 'Montrer les Sujets suprimés'
	),
	'forum-11' => array(
		'EN' => 'Empty forum',
		'RO' => 'Forum leeren',
		'FR' => 'Forum vide'
	),
	'forum-12' => array(
		'EN' => 'New topic',
		'RO' => 'Neues Thema',
		'FR' => 'Nouveau sujet'
	),
	'forum-13' => array(
		'EN' => 'Sticky',
		'RO' => 'Wichtig',
		'FR' => 'Important'
	),
	'forum-14' => array(
		'EN' => 'Normal',
		'RO' => 'Normal',
		'FR' => 'Normal'
	),
	'forum-15' => array(
		'EN' => 'No topics in this forum!',
		'RO' => 'Keine Themen in diesem Forum!',
		'FR' => 'ucun sujet dans ce Forum!'
	),
	'forum-16' => array(
		'EN' => 'New messages',
		'RO' => 'Neue Nachrichten',
		'FR' => 'Nouveau message'
	),
	'forum-17' => array(
		'EN' => 'Closed topic',
		'RO' => 'Thema geschlossen',
		'FR' => 'Sujet fermé'
	),
	'forum-18' => array(
		'EN' => 'No new messages',
		'RO' => 'Keine neuen Nachrichten',
		'FR' => 'Pas de nouveaux messages'
	),
	'forum-19' => array(
		'EN' => 'posts',
		'RO' => 'Beiträge',
		'FR' => 'messages'
	),
	'forum-20' => array(
		'EN' => 'Filter',
		'RO' => 'Filter',
		'FR' => 'Filtrer'
	),
	'forum-21' => array(
		'EN' => 'Hide deleted posts',
		'RO' => 'Gelöschte Beiträge ausblenden',
		'FR' => 'Cacher les sujets suprimés'
	),
	'forum-22' => array(
		'EN' => 'Show deleted posts',
		'RO' => 'Zeige gelöschte Beiträge',
		'FR' => 'Montrer les sujets suprimés'
	),
	'forum-23' => array(
		'EN' => 'Set sticky',
		'RO' => 'Markiere "Wichtig"',
		'FR' => 'Marquer "Important"'
	),
	'forum-24' => array(
		'EN' => 'Remove sticky',
		'RO' => 'Lösche den Hinweis "Wichtig"',
		'FR' => 'Supprimer indication "Important"'
	),
	'forum-25' => array(
		'EN' => 'Close topic',
		'RO' => 'Thema schließen',
		'FR' => 'Fermer le sujet'
	),
	'forum-26' => array(
		'EN' => 'Open topic',
		'RO' => 'Thema öffnen',
		'FR' => 'Ouvrir le sujet'
	),
	'forum-27' => array(
		'EN' => 'Edit',
		'RO' => 'Bearbeiten',
		'FR' => 'Editer'
	),
	'forum-28' => array(
		'EN' => 'Restore',
		'RO' => 'Wiederherstellen',
		'FR' => 'Restaurer'
	),
	'forum-29' => array(
		'EN' => 'Posted',
		'RO' => 'Gepostet',
		'FR' => 'Postés'
	),
	'forum-30' => array(
		'EN' => 'Quote',
		'RO' => 'Zitat',
		'FR' => 'Citer'
	),
	'forum-31' => array(
		'EN' => 'Answer',
		'RO' => 'Antworten',
		'FR' => 'Réponse'
	),
	'forum-32' => array(
		'EN' => 'Report',
		'RO' => 'Bericht',
		'FR' => 'Rapport'
	),
	'forum-33' => array(
		'EN' => 'Reported',
		'RO' => 'Berichtet',
		'FR' => 'Rapporté'
	),
	'forum-34' => array(
		'EN' => 'You have limited access to the game!',
		'RO' => 'Du hast nur eingeschränkten Zugriff auf das Spiel!',
		'FR' => 'Vous avez un accès limité au jeu!'
	),
	'forum-35' => array(
		'EN' => 'Message cannot contain more than -NUM- characters!',
		'RO' => 'Die Nachricht darf nicht mehr als -NUM- Zeichen enthalten!',
		'FR' => 'Le message doit contenir plus de -NUM- carractères!'
	),
	'forum-36' => array(
		'EN' => 'Wrong forum!',
		'RO' => 'Falsches Forum!',
		'FR' => 'Forum incorrect!'
	),
	'forum-37' => array(
		'EN' => 'You cannot reply on a topic closed!',
		'RO' => 'Du kannst nicht auf ein geschlossenes Thema antworten!',
		'FR' => 'Vous ne pouvez pas répondre à un sujet fermé!'
	),
	'forum-38' => array(
		'EN' => 'You must wait -TIME- seconds before posting again!',
		'RO' => 'Du musst -TIME- Sekunden warten, bevor du wieder was posten kannst!',
		'FR' => 'Vous devez attendre -TIME- avant de poster une nouvelle fois!'
	),
	'forum-39' => array(
		'EN' => 'Last edit',
		'RO' => 'Letzte Bearbeitung',
		'FR' => 'Dernières modifications'
	),
	
	// Namen ändern
	'cname-01' => array(
		'EN' => 'Enter your new player name below!',
		'RO' => 'Gib unten deinen neuen Spielernamen ein!',
		'FR' => 'Entrez votre nouveau nom joueur ci-dessous!'
	),
	'cname-02' => array(
		'EN' => 'Enter your new player name below!',
		'RO' => 'Gib unten deinen neuen Spielernamen ein!',
		'FR' => 'Entrez votre nouveau nom joueur ci-dessous!'
	),
	'cname-03' => array(
		'EN' => 'You have to pay some coins if you wanna change your name!',
		'RO' => 'Es kostet dich ein paar Coins, wenn du deinen Namen ändern willst!',
		'FR' => 'Vous devez payer en Crédits le changement de nom!'
	),
	'cname-04' => array(
		'EN' => 'Your player name has been changed successfully!',
		'RO' => 'Dein Spielername wurde erfolgreich geändert!',
		'FR' => 'Votre nom de joueur est changé avec succès!'
	),
	'cname-05' => array(
		'EN' => 'The new name must be different from current name!',
		'RO' => 'Der neue Name darf nicht mit dem aktuellen Namen identisch sein!',
		'FR' => 'Le nouveau nom doit être différent de l\'actuel!'
	),
	
	// VIP Info
	'cvip-01' => array(
		'EN' => 'What are the benefits of a VIP account?',
		'RO' => 'Was sind die Vorteile einer VIP-Mitgliedschaft?',
		'FR' => 'Quels sont les bénéfices d\'un compte VIP?'
	),
	'cvip-02' => array(
		'EN' => 'Benefits',
		'RO' => 'Die Vorteile eines VIP-Kontos',
		'FR' => 'Bénéfices'
	),
	'cvip-03' => array(
		'EN' => 'A gold star is displayed at the end of your name',
		'RO' => 'Am Ende deines Namens wird ein goldener Stern angezeigt',
		'FR' => 'Une étoile d\'OR est affichée à la fin de votre nom'
	),
	'cvip-04' => array(
		'EN' => 'Waiting times for Robberies, Car theft, Brothel, Blackmail and Wheel of Fortune are reduced by 25%',
		'RO' => 'Wartezeiten und Vorbereitungszeiten für Raubüberfälle, Autodiebstahl, Bordell, Erpressung und Glücksrad werden um 25% reduziert',
		'FR' => 'Les Temps d\'attentes pour les vols, vols de voitures, bordel, chantage et roue de la fortune sont réduits de 25%'
	),
	'cvip-05' => array(
		'EN' => 'You can play 40 times a day at Roulette (instead of 20)',
		'RO' => 'Du kannst 40 Mal am Tag beim Roulette spielen (statt 20 Mal am Tag)',
		'FR' => 'Vous pouvez jouer 40 fois / jour à la roulette (au lieu de 20 fois)'
	),
	'cvip-06' => array(
		'EN' => 'Price for treatment in hospital is with 50% lower',
		'RO' => 'Der Preis für die Behandlung im Krankenhaus ist um 50% günstiger',
		'FR' => 'Le prix des soins à hôpital est réduit de 50%'
	),
	'cvip-07' => array(
		'EN' => 'Sometimes, you spend less time in jail',
		'RO' => 'Manchmal verbringt man weniger Zeit im Gefängnis',
		'FR' => 'Vous passerez moins de temps en prison'
	),
	'cvip-08' => array(
		'EN' => 'Waiting time and travel cost in another city are reduced by 25%',
		'RO' => 'Wartezeit und Reisekosten in eine andere Stadt werden um 25% weniger',
		'FR' => 'Le temps d\'attente et le coût de vos voyages sont réduits de 25%'
	),
	'cvip-09' => array(
		'EN' => '<a href="?side=magazin-credite">Buy VIP account</a> and enjoy all these benefits!',
		'RO' => '<a href="?side=magazin-credite">Hole dir VIP</a> und profitiere von all diesen Vorteilen!',
		'FR' => '<a href="?side=magazin-credite">Acheter un compte VIP</a> et profitez de ses avantages!'
	),
	'cvip-10' => array(
		'EN' => 'You can send up to 10 respect points per day (instead of 5)',
		'RO' => 'Du kannst bis zu 10 Respektpunkte pro Tag vergeben (anstatt 5).',
		'FR' => 'Vous envoyez 10 points de respect par jour (au lieu de 5)'
	),
	'cvip-11' => array(
		'EN' => 'You can recruit more hookers',
		'RO' => 'Du kannst mehr Prostituierte einstellen',
		'FR' => 'Vous pouvez recruter plus de fille'
	),
	'cvip-12' => array(
		'EN' => 'Besides the money earned in rewards, you will receive a credit for each reward',
		'RO' => 'Neben dem Geld, das du für Belohnungen bekommst, erhälst du 1 Coin pro Belohnung',
		'FR' => 'En plus de l\'argent reçu comme récompense, vous gagnez 1 Crédit / récompense'
	),
	
	// Runde
	'runda-01' => array(
		'EN' => 'Remaining time',
		'RO' => 'Verbleibende Zeit',
		'FR' => 'Temps d\'attente'
	),
	'runda-02' => array(
		'EN' => 'Round',
		'RO' => 'Runde',
		'FR' => 'Tour de jeu'
	),
	'runda-03' => array(
		'EN' => 'Start date',
		'RO' => 'Gestartet',
		'FR' => 'Date de départ'
	),
	'runda-04' => array(
		'EN' => 'End date',
		'RO' => 'Endet',
		'FR' => 'Date de fin'
	),
	'runda-05' => array(
		'EN' => 'Total prize',
		'RO' => 'Gesamtpreis',
		'FR' => 'Prix total'
	),
	'runda-06' => array(
		'EN' => 'Top 3 Ranks',
		'RO' => 'Top 3 Ränge',
		'FR' => 'Top 3 Rangs'
	),
	'runda-07' => array(
		'EN' => 'Top 3 Richest',
		'RO' => 'Top 3 Reichsten',
		'FR' => 'Top 3 les plus Riches'
	),
	'runda-08' => array(
		'EN' => 'Top 3 Assassins',
		'RO' => 'Top 3 Killer',
		'FR' => 'Top 3 Criminels'
	),
	'runda-09' => array(
		'EN' => 'Top 3 Escapees',
		'RO' => 'Top 3 Ausbrecher',
		'FR' => 'Top 3 Evadés'
	),
	'runda-10' => array(
		'EN' => 'Top 3 Recruiters',
		'RO' => 'Top 3 Anwerber',
		'FR' => 'Top 3 Recruteurs'
	),
	'runda-11' => array(
		'EN' => 'The rules are very simple, you must be active throughout the round to be in top 3 in each ranking. On each ranking according of the position, are offered some points. At the end of the round, for each player in the top 3 from each ranking, are calculated her total points and the top 3 players in the resulted top by calculating the total points, win prize money, the first player wins 50%, the second player wins 30% and the 3rd place wins 20% of the prize.',
		'RO' => 'Die Regeln sind sehr einfach. Du musst während der gesamten Runde aktiv sein, um in jeder Rangliste unter den Top 3 zu sein. Zu jeder Platzierung werden je nach Position einige Punkte angeboten. Am Ende der Runde werden für jeden Spieler in den Top 3 aus jeder Rangliste die Gesamtpunkte berechnet und für die Top 3 Spieler in den daraus resultierenden Wertung durch Berechnung der Gesamtpunkte das Gewinnpreisgeld ermittelt. Der erste Platz gewinnt 50%, der Der zweite Platz gewinnt 30% und der 3. Platz 20% des Preises.',
		'FR' => 'Les règles sont très simples, vous devez être actif tout au long du challenge pour apparaitre au TOP 3 de chaque classements. Pour chaque classement en fonction de votre position vous remportez des points calculés sur votre total. Le premier gagne 50% de son total de points, le second gagne 30% et le troisième gagne 20%.'
	),
	'runda-12' => array(
		'EN' => 'For example',
		'RO' => 'Ein Beispiel',
		'FR' => 'Exemple'
	),
	'runda-13' => array(
		'EN' => 'Player X is second ranked in Top 3 Ranks and 1st ranked in Top 3 Richest, that means he has 9 points.<br>Player Y is 1st ranked in Top 3 Ranks and 3rd ranked in Top 3 Assassins, that means he has 7 points.<br>Player X is 1st player and player Y is second.',
		'RO' => 'Spieler X belegt den zweiten Platz in den Top 3 Ränge und den ersten Platz in den Top 3 der Reichsten, dh er hat 9 Punkte. <br> Spieler Y belegt den ersten Platz in den Top 3 Ränge und den dritten Platz in den Top 3 Killer, dh er hat 7 Punkte . <br> Spieler X ist 1. und Spieler Y ist Zweiter.',
		'FR' => 'Joueur X est 2eme au top 3 RANGS et le premier au top 3 LE PLUS RICHE, cela signifie qu\'il a 9 points.<br> Le joueur Y est 1er au top 3 RANGS, et 3eme au Top 3 Assassins, cela signifie qu\'il a obtenu 7 points.<br> Le joueur X est premier du challenge, et le joueur Y est second.'
	),
	'runda-14' => array(
		'EN' => 'Total Prize is the amount who will to be divided for the winners and this amount is x% of revenue generated from sales of loans (percentage will remain anonymous for security reasons). This means that every time a player buys loans, <b>the prize is increased</b>.',
		'RO' => 'Der Gesamtpreis ist der Betrag, der an die Gewinner verteilt wird. Dieser Betrag entspricht tatsächlich x% der Einnahmen aus Kreditverkäufen (der Prozentsatz bleibt aus Sicherheitsgründen anonym). Dies bedeutet, dass jedes Mal, wenn ein Spieler Kredite kauft, der <b> Gesamtpreis steigt </ b>.',
		'FR' => 'Le prix total est le montant divisé par les gagnants, ce montant est de x% du chiffre d\'affaire généré par les ventes. (Le pourcentage n\'est pas divulgué). Cela signifie que chaque fois qu\'un joueur achète, <b>le prix augmente</b>.'
	),
	'runda-15' => array(
		'EN' => 'Receiving prizes',
		'RO' => 'Preise erhalten',
		'FR' => 'Recevoir son prix'
	),
	'runda-16' => array(
		'EN' => 'The money will be sent to players via PayPal. For those who don\'t have a PayPal account, can make one <a href="https://www.paypal.com/ro/mrb/pal=DY53KKEJPAAB8" target="_blank">here</a>.<br><br><i>PayPal is an online payment processing system.</i>',
		'RO' => 'Das Geld wird per PayPal an die Spieler gesendet. Für diejenigen, die kein PayPal-Konto haben, können <a href="https://www.paypal.com/ro/mrb/pal=DY53KKEJPAAB8" target="_blank">hier</a> eins erstellen.<br><br><i>PayPal ist ein Online-Zahlungsabwicklungssystem.</i>',
		'FR' => 'L\'argent sera envoyé aux joueurs par PayPal. Si vous n\'avez pas de compte PayPal, créez-en un gratuitement <a href="https://www.paypal.com/" target="_blank">ICI</a>.<br><br><i>PayPal est un moyen de paiement en ligne sécurisé.</i>'
	),
	'runda-17' => array(
		'EN' => 'Final Ranking',
		'RO' => 'Endergebnis',
		'FR' => 'Clasement Final'
	),
	'runda-18' => array(
		'EN' => 'Select round',
		'RO' => 'Wähle eine Runde',
		'FR' => 'Choix du challenge'
	),
	'runda-19' => array(
		'EN' => 'Here you can see past rounds and winners of these rounds. To view the statistics of a round, please select the desired round below:',
		'RO' => 'Hier siehst du die Statistiken vergangener Runden sowie die Gewinner dieser Runden. Um die Statistik einer Runde zu sehen, wähle bitte die gewünschte Runde aus:',
		'FR' => 'Ici vous pouvez voir les challenges passés et leur gagnants. Pour voir les statistiques d\'un challenge, sélectionnez le challenge ci-dessous:'
	),
	'runda-20' => array(
		'EN' => 'Top 3 Respect',
		'RO' => 'Top 3 Respekt',
		'FR' => 'Top 3 Respect'
	),
	
	// Respekt
	'respect-01' => array(
		'EN' => 'You can not give more than -NUM- respect points per day!',
		'RO' => 'Du kannst nicht mehr als -NUM- Respektpunkte pro Tag vergeben!',
		'FR' => 'Vous ne pouvez pas donner plus de -NUM- points de respects par jour!'
	),
	'respect-02' => array(
		'EN' => 'You can give 1 respect point per day, for each player!',
		'RO' => 'Du kannst pro Spieler und Tag 1 Respektpunkt vergeben!',
		'FR' => 'Vous pouvez donner 1 point de respect par joueur et par jour!'
	),
	'respect-03' => array(
		'EN' => 'You can\'t send respect points to this player!',
		'RO' => 'Du kannst diesem Spieler keine Respektpunkte geben!',
		'FR' => 'Vous ne pouvez pas envoyer de points de respects à ce joueur!'
	),
	'respect-04' => array(
		'EN' => 'You cannot send respect points to yourself!',
		'RO' => 'Du kannst dir nicht selbst Respektpunkte geben!',
		'FR' => 'Vous ne pouvez pas vous donner un point de respect!'
	),
	'respect-05' => array(
		'EN' => 'You have sent 1 respect point to this player!',
		'RO' => 'Du hast diesem Spieler 1 Respektpunkt gegeben!',
		'FR' => 'Vous avez envoyé un point de respect à ce joueur!'
	),
	'respect-06' => array(
		'EN' => 'You must be at least at rank <b>-RANK-</b> to send respect points!',
		'RO' => 'Du musst mindestens den Rang <b>-RANK-</b> haben, um Respektpunkte vergeben zu können!',
		'FR' => 'Vous devez avoir au minimum le rang <b>-RANK-</b> pour attribuer des points de respect!'
	),
	
	// Rubbellos
	'loz-00' => array(
		'EN' => 'Scratch Tickets',
		'RO' => 'Rubbellose',
		'FR' => 'Billets à gratter'
	),
	'loz-01' => array(
		'EN' => 'Buy "Scratch Tickets"',
		'RO' => '"Rubbellose" kaufen',
		'FR' => 'Acheter des "Tickets à gratter"'
	),
	'loz-02' => array(
		'EN' => 'There isn\'t such a business in -CITY-.<br />It costs <b>-COINS- coins</b> to start this bussiness.',
		'RO' => 'Es gibt keinen solchen Laden in -CITY-.<br />Die Gründung eines solchen Ladens kostet dich <b>-COINS- Coins</b>.',
		'FR' => 'Il n\'y à pas une telle entreprise à -CITY-.<br />Ca te coutera <b>-COINS- crédits</b> pour démarrer cette activité.'
	),
	'loz-03' => array(
		'EN' => 'You bought "Scratch Tickets"',
		'RO' => 'Du hast einen "Rubbellose"-Laden gekauft',
		'FR' => 'Vous avez acheté des "Tickets à gratter"'
	),
	'loz-04' => array(
		'EN' => 'This business belongs to',
		'RO' => 'Dieser Laden gehört',
		'FR' => 'Cette société appartient à'
	),
	'loz-05' => array(
		'EN' => 'This company is yours, so you cannot invest money here!',
		'RO' => 'Dieser Laden gehört dir, also kannst du hier kein Geld anlegen!',
		'FR' => 'Cette Société vous appartient, vous ne pouvez pas investir d\'argent ici!'
	),
	'loz-06' => array(
		'EN' => 'Add tickets',
		'RO' => 'Lose hinzufügen',
		'FR' => 'Ajouter des tickets'
	),
	'loz-07' => array(
		'EN' => 'Price per ticket',
		'RO' => 'Preis pro Los',
		'FR' => 'Prix du ticket'
	),
	'loz-08' => array(
		'EN' => 'Total price',
		'RO' => 'Gesamtpreis',
		'FR' => 'Prix total'
	),
	'loz-09' => array(
		'EN' => 'There are no more tickets!',
		'RO' => 'Es gibt keine Lose mehr!',
		'FR' => 'Aucun ticket disponible!'
	),
	'loz-10' => array(
		'EN' => 'You bought -NUM- tickets',
		'RO' => 'Du hast -NUM- Lose gekauft',
		'FR' => 'Vous avez acheté -NUM- tickets'
	),
	'loz-11' => array(
		'EN' => 'Available tickets',
		'RO' => 'Verfügbare Lose',
		'FR' => 'Tickets disponibles'
	),
	'loz-12' => array(
		'EN' => 'Not enough money in the company account!',
		'RO' => 'Nicht genug Geld auf dem Firmenkonto!',
		'FR' => 'Pas assez d\'argent dans le compte de la société!'
	),
	'loz-13' => array(
		'EN' => 'You must add at least one ticket!',
		'RO' => 'Du musst mindestens 1 Los hinzufügen!',
		'FR' => 'Vous devez ajouter au moins 1 ticket!'
	),
	'loz-14' => array(
		'EN' => 'Buy Ticket',
		'RO' => 'Los kaufen',
		'FR' => 'Acheter ticket'
	),
	'loz-15' => array(
		'EN' => 'Available Tickets',
		'RO' => 'Verfügbare Lose',
		'FR' => 'Tickets disponibles'
	),
	'loz-16' => array(
		'EN' => 'Buy an ticket and scratch it to see if you won. Each ticket can bring cash prizes and the price for a ticket is $10 000',
		'RO' => 'Kaufe ein Rubbellos und kratze es mit einer Münze frei um zu sehen, ob du etwas gewonnen hast. Jedes Los kann Geldpreise beinhalten. Der Preis für ein Los beträgt 10 000$',
		'FR' => 'Achetez un ticket et gratter le pour voir votre gain. Chaque ticket peut apporter du CASH, le coût du ticket est de 10 000$'
	),
	'loz-17' => array(
		'EN' => 'Buy another ticket',
		'RO' => 'Kaufe ein anderes Los',
		'FR' => 'Achetez un autre ticket'
	),
	
	// Bordell
	'bordel-01' => array(
		'EN' => 'Hookers',
		'RO' => 'Prostituierte',
		'FR' => 'Filles de joie'
	),
	'bordel-02' => array(
		'EN' => 'On street',
		'RO' => 'Auf der Straße',
		'FR' => 'Dans la rue'
	),
	'bordel-03' => array(
		'EN' => 'On brothel',
		'RO' => 'Im Bordell',
		'FR' => 'A l\'hôtel'
	),
	'bordel-04' => array(
		'EN' => 'Recruits Hookers',
		'RO' => 'Prostituierte anwerben',
		'FR' => 'Recruter des filles'
	),
	'bordel-05' => array(
		'EN' => 'Add hookers in brothel',
		'RO' => 'Prostituierte dem Bordell hinzufügen',
		'FR' => 'Ajouter des filles à la maison de passe'
	),
	'bordel-06' => array(
		'EN' => 'For every hooker in the brothel, you will receive $50 per hour, and for every hooker on the street, you will receive $30 per hour!',
		'RO' => 'Für jede Prostituierte im Bordell bekommst du 50$ pro Stunde und für jede Prostituierte auf der Straße 30$ pro Stunde!',
		'FR' => 'Pour chaque filles dans la maison de passe, vous recevez 50$/heure, pour chaque filles dans la rue, vous gagnez 30$/heure!.'
	),
	'bordel-07' => array(
		'EN' => 'Add',
		'RO' => 'hinzufügen',
		'FR' => 'Ajouter'
	),
	'bordel-08' => array(
		'EN' => 'You have not bought a brothel. To buy a brothel, click on "Buy brothel".<br /> The price of a brothel is $ -PRICE-',
		'RO' => 'Du hast noch kein Bordell gekauft. Um ein Bordell zu kaufen, klicke auf "Bordell kaufen".<br /> Der Preis für ein Bordell beträgt -PRICE- $',
		'FR' => 'Vous n\'avez pas de maison de passe. Pour en acheter une, cliquez sur "Acheter une maison de passe".<br /> Le prix est de -PRICE- $'
	),
	'bordel-09' => array(
		'EN' => 'Buy brothel',
		'RO' => 'Bordell kaufen',
		'FR' => 'Acheter une maison de passe'
	),
	'bordel-10' => array(
		'EN' => 'You bought an brothel!',
		'RO' => 'Du hast ein Bordell gekauft!',
		'FR' => 'Vous avez acheté une maison de passe!'
	),
	'bordel-11' => array(
		'EN' => 'You must wait at least 30 minutes before recruit other hookers!',
		'RO' => 'Du musst mindestens 30 Minuten warten, bevor du andere Prostituierte einstellst!',
		'FR' => 'Vous devez attendre au moins 30 minutes avant de recruter de nouvelles filles!'
	),
	'bordel-12' => array(
		'EN' => 'You recruited -NUM- hookers!',
		'RO' => 'Du hast -NUM- Prostituierte angeworben!',
		'FR' => 'Vous avez recruté -NUM- filles!'
	),
	'bordel-13' => array(
		'EN' => 'You cannot add so many hookers in the brothel!',
		'RO' => 'Sie können nicht so viele Prostituierte ins Bordell schicken!',
		'FR' => 'Vous ne pouvez pas ajouter de filles à cette maison de passe!'
	),
	'bordel-14' => array(
		'EN' => 'You must add at least a hooker!',
		'RO' => 'Du musst mindestens eine Prostituierte hinzufügen!',
		'FR' => 'Vous devez ajouter au moins 1 fille!'
	),
	'bordel-15' => array(
		'EN' => 'You have added -NUM- prostitutes in the brothel!',
		'RO' => 'Du hast -NUM- Prostituierte ins Bordell geschickt!',
		'FR' => 'Vous avez placé -NUM- filles dans la maison de passe!'
	),
	'bordel-16' => array(
		'EN' => 'You cannot collect the money yet!',
		'RO' => 'Du kannst das Geld noch nicht einsammeln!',
		'FR' => 'Vous ne pouvez pas collecter l\'argent pour le moment!'
	),
	'bordel-17' => array(
		'EN' => 'You have collected $ -CASH- from your hookers!',
		'RO' => 'Du hast -CASH-$ von deinen Prostituierten eingesammelt!',
		'FR' => 'Vous avez collecté -CASH-$ de vos filles de joie!'
	),
	'bordel-18' => array(
		'EN' => 'Collect Money',
		'RO' => 'Geld einsammeln',
		'FR' => 'Collecter l\'argent'
	),
	'bordel-19' => array(
		'EN' => 'From the street',
		'RO' => 'Von der Straße',
		'FR' => 'De la rue'
	),
	'bordel-20' => array(
		'EN' => 'From the brothel',
		'RO' => 'Vom Bordell',
		'FR' => 'De la maison de passe'
	),
	'bordel-21' => array(
		'EN' => 'Collect Money',
		'RO' => 'Geld einsammeln',
		'FR' => 'Collectez l\'argent'
	),
	'bordel-22' => array(
		'EN' => 'You have to wait <span class="countdown">-SEC-</span> seconds!',
		'RO' => 'Du musst <span class="countdown">-SEC-</span> Sekunden warten!',
		'FR' => 'Vous devez attendre <span class="countdown">-SEC-</span> secondes!'
	),
	'bordel-23' => array(
		'EN' => 'Failed! Police arrested you for <b>-SEC-</b> seconds!',
		'RO' => 'Erwischt! Die Polizei steckt dich für <b>-SEC-</b> Sekunden in den Knast!',
		'FR' => 'Echec! La Police vous a arrêté pour <b>-SEC-</b> secondes.'
	),
	
	// Admin
	'admin-01' => array(
		'EN' => 'Notes',
		'RO' => 'Notizen',
		'FR' => 'Notes'
	),
	'admin-02' => array(
		'EN' => 'Administration',
		'RO' => 'Administrator',
		'FR' => 'Administration'
	),
	'admin-03' => array(
		'EN' => 'User ID',
		'RO' => 'Spieler ID',
		'FR' => 'ID Utilisateur'
	),
	'admin-04' => array(
		'EN' => 'User ID',
		'RO' => 'Spieler ID',
		'FR' => 'ID Utilisateur'
	),
	'admin-05' => array(
		'EN' => 'This player is already disabled!',
		'RO' => 'Dieser Spieler ist bereits deaktiviert!',
		'FR' => 'Ce joueur est déjà désactivé!'
	),
	'admin-06' => array(
		'EN' => 'The reason must contain at least -NUM- characters!',
		'RO' => 'Die Begründung muss mindestens -NUM- Zeichen enthalten!',
		'FR' => 'La raison doit contenir au moins -NUM- carractères!'
	),
	'admin-07' => array(
		'EN' => 'This player was banned!',
		'RO' => 'Dieses Spielerkonto wurde gesperrt!',
		'FR' => 'Ce joueur a été banni!'
	),
	'admin-08' => array(
		'EN' => 'This player was unblocked!',
		'RO' => 'Dieses Spielerkonto wurde wieder aktiviert!',
		'FR' => 'Ce joueur a été débloqué!'
	),
	'admin-09' => array(
		'EN' => 'No changes found!',
		'RO' => 'Keine Änderungen gefunden!',
		'FR' => 'Aucun changements trouvés!'
	),
	'admin-10' => array(
		'EN' => 'Bad profile image!',
		'RO' => 'Schlechtes Profilbild!',
		'FR' => 'Mauvaise image de profil!'
	),
	'admin-11' => array(
		'EN' => 'Value cannot be less than 0',
		'RO' => 'Der Wert darf nicht kleiner als 0 sein',
		'FR' => 'La valeur ne peut être inférieure à 0'
	),
	'admin-12' => array(
		'EN' => 'Wrong rank!',
		'RO' => 'Falscher Rang!',
		'FR' => 'Mauvais Rang!'
	),
	'admin-13' => array(
		'EN' => 'Wrong city!',
		'RO' => 'Falsche Stadt!',
		'FR' => 'Localisation érronée!'
	),
	'admin-14' => array(
		'EN' => 'Wrong health percent!',
		'RO' => 'Falsche Gesundheitsprozente!',
		'FR' => '% de santé érroné!'
	),
	'admin-15' => array(
		'EN' => 'Wrong wanted-level percent!',
		'RO' => 'Falsche Gefahrenstatus Prozente!',
		'FR' => 'Mauvais % rang souhaité!'
	),
	'admin-16' => array(
		'EN' => 'Crimes',
		'RO' => 'Verbrechen',
		'FR' => 'Crimes'
	),
	
	// Waffen
	'waffe-01' => array(
		'EN' => 'Gun',
		'RO' => 'Pistole',
		'FR' => 'Pistolet'
	),
	
	// Training
	'train-01' => array(
		'EN' => '25 pushups',
		'RO' => '25 Liegestützen',
		'FR' => '25 pompes'	
	),
	'train-02' => array(
		'EN' => '50 pushups',
		'RO' => '50 Liegestützen',
		'FR' => '50 pompes'	
	),
	'train-03' => array(
		'EN' => 'Run 3 km',
		'RO' => '3 km Laufen',
		'FR' => 'Course à pied 3 km'	
	),
	'train-04' => array(
		'EN' => 'Run 5 km',
		'RO' => '5 km Laufen',
		'FR' => 'Course à pied 5 km'	
	),
	'train-05' => array(
		'EN' => 'Run 12 km',
		'RO' => '12 km Laufen',
		'FR' => 'Course à pied 12 km'	
	),
	'train-06' => array(
		'EN' => 'Public',
		'RO' => 'Öffentlich',
		'FR' => 'Publique'		
	),
	
	// Normale Missionen
	'nmiss-01' => array(
		'EN' => 'We need some proofs',
		'RO' => 'Wir brauchen paar Beweise',
		'FR' => 'Nous avons besoin de preuves'	
	),
	'nmiss-02' => array(
		'EN' => 'This is your first mission from us, we have to know how powerful you are.<br /> Complete these tasks and come back to get your reward!',
		'RO' => 'Dies ist deine erste Mission von uns. Wir müssen wissen, wie mächtig du bist. <br /> Erfülle diese Aufgaben und kehren hier her zurück, um deine Belohnung zu erhalten!',
		'FR' => 'C’est votre première mission de notre part, nous devons savoir à quel point vous êtes puissant. <br /> Accomplissez ces tâches et revenez pour obtenir votre récompense!'	
	),
	'nmiss-03' => array(
		'EN' => 'Successfully complete a robbery each city',
		'RO' => 'Führe einen erfolgreichen Raubüberfall in jeder Stadt aus',
		'FR' => 'Compléter avec succès un vol chaque ville'	
	),
	'nmiss-04' => array(
		'EN' => 'Successfully complete 3 consecutive robberies',
		'RO' => 'Schließe 3 Raubüberfälle hintereinander erfolgreich ab',
		'FR' => 'Compléter avec succès 3 vols consécutifs'	
	),
	'nmiss-05' => array(
		'EN' => 'Steal 10 cars from Oslo',
		'RO' => 'Klaue in Oslo 10 Autos',
		'FR' => 'Voler 10 voitures d\'Oslo'	
	),
	'nmiss-06' => array(
		'EN' => 'Blackmail 3 different players',
		'RO' => 'Erpresse 3 verschiedene Spieler',
		'FR' => 'Faire chanter 3 joueurs différents'		
	),
	'nmiss-07' => array(
		'EN' => 'Help 5 players to escape from prison',
		'RO' => 'Hilf 5 Spielern, aus dem Gefängnis zu fliehen',
		'FR' => 'Aidez 5 joueurs à s\'évader de prison'		
	),
	'nmiss-08' => array(
		'EN' => 'xxx',
		'RO' => 'xxx',
		'FR' => 'xxx'		
	),
	'nmiss-09' => array(
		'EN' => 'xxx',
		'RO' => 'xxx',
		'FR' => 'xxx'		
	),
	
	// Tipps
	'tipp-01' => array(
		'EN' => 'Increase your rank and eanr money at:<br /> <a href="' . $config['base_url'] . '?side=jafuri">Robberies</a>, <a href="' . $config['base_url'] . '?side=fura-masini">Car Theft</a>, <a href="' . $config['base_url'] . '?side=santaj">Blackmailing</a> or <a href="' . $config['base_url'] . '?side=jaf-organizat">Planned crime</a>.',
		'RO' => 'Erhöhen deinen Rang und verdiene Geld mit: <a href="' . $config['base_url'] . '?side=jafuri">Raubüberfällen</a>, <a href="' . $config['base_url'] . '?side=fura-masini">Autodiebstahl</a>, <a href="' . $config['base_url'] . '?side=santaj">Erpressung</a> oder <a href="' . $config['base_url'] . '?side=jaf-organizat">Organisierte Verbrechen</a>.',
		'FR' => 'Augmentez votre rang et gagnez de l\'argent à: <a href="' . $config['base_url'] . '?side=jafuri">Les vols</a>, <a href="' . $config['base_url'] . '?side=fura-masini">Vol de voiture</a>, <a href="' . $config['base_url'] . '?side=santaj">Chantage</a> ou <a href="' . $config['base_url'] . '?side=jaf-organizat">Crime planifié</a>.'		
	),
	'tipp-02' => array(
		'EN' => '<a href="' . $config['base_url'] . '?side=blackjack">Blackjack</a>, is a good way to multiply your cash.',
		'RO' => '<a href="' . $config['base_url'] . '?side=blackjack">Blackjack</a>, ist eine gute Möglichkeit, um dein Geld zu vermehren.',
		'FR' => 'Le <a href="' . $config['base_url'] . '?side=blackjack">Blackjack</a>, est un bon moyen de multiplier votre argent.'		
	),
	'tipp-03' => array(
		'EN' => 'Need help?<br />Visit <a href="' . $config['base_url'] . '?side=faq">FAQ page</a> or <a href="' . $config['base_url'] . '?side=support">contact us</a>.',
		'RO' => 'Du brauchst Hilfe?<br />Besuche die Seite mit der <a href="' . $config['base_url'] . '?side=faq">Anleitung</a> oder <a href="' . $config['base_url'] . '?side=support">kontaktiere uns</a>.',
		'FR' => 'Besoin d\'aide pour?<br />Visitez la <a href="' . $config['base_url'] . '?side=faq">page FAQ</a> ou <a href="' . $config['base_url'] . '?side=support">contactez-nous</a>.'		
	),
	'tipp-04' => array(
		'EN' => 'You can arrange the menu as you wish!<br />You can change the order of each link.',
		'RO' => 'Du kannst das Menü nach deinen Wünschen zusammenstellen! <br /> Du kannst die Reihenfolge der einzelnen Links ändern.',
		'FR' => 'Vous pouvez organiser le menu comme vous le souhaitez! <br /> Vous pouvez modifier l’ordre de chaque lien.'		
	),
	'tipp-05' => array(
		'EN' => 'Get <b>more coins</b> from referrals.<br /><a href="' . $config['base_url'] . '?side=min_side&amp;a=ref">Click here</a> for more info.',
		'RO' => 'Erhalte <b>mehr Coins</b> durch Einladungen.<br /><a href="' . $config['base_url'] . '?side=min_side&amp;a=ref">Klicke hier</a> für mehr Infos.',
		'FR' => 'Obtenez <b>plus de crédits</b> de références.<br /><a href="' . $config['base_url'] . '?side=min_side&amp;a=ref">Cliquez ici pour</a> plus d\'informations.'		
	),
	
	// Registrierung-Mail
	'regis-01' => array(
		'EN' => 'Signup',
		'RO' => 'Registrieren',
		'FR' => 'S\'inscrire'		
	),
	'regis-02' => array(
		'EN' => 'Hello,',
		'RO' => 'Hallo und herzlich Willkommen,',
		'FR' => 'Bonjour'		
	),
	'regis-03' => array(
		'EN' => 'Click bellow to go at step 2:',
		'RO' => 'Klicke auf den nachstehenden Link um mit Schritt 2 der Registrierung fortzufahen:',
		'FR' => 'Cliquez ci-dessous pour aller à l\'étape 2:'		
	),
	'regis-04' => array(
		'EN' => 'This link is active until',
		'RO' => 'Dieser Link gilt bis',
		'FR' => 'Ce lien est actif jusqu\'au'		
	),
	'regis-05' => array(
		'EN' => 'Best Regards,',
		'RO' => 'Bis gleich, wir freuen uns auf dich,',
		'FR' => 'Meilleures salutations,'		
	),
	
	// Kontakt
	'kontakt-01' => array(
		'EN' => 'Full Name:',
		'RO' => 'Name:',
		'FR' => 'Nom complet:'		
	),
	'kontakt-02' => array(
		'EN' => 'Subject:',
		'RO' => 'Betreff:',
		'FR' => 'Assujettir:'		
	),
	'kontakt-03' => array(
		'EN' => 'Message:',
		'RO' => 'Nachricht:',
		'FR' => 'Message:'		
	),
	'kontakt-04' => array(
		'EN' => 'Please complete your full name!',
		'RO' => 'Bitte einen Namen eingeben!',
		'FR' => 'Veuillez compléter votre nom complet!'		
	),
	'kontakt-05' => array(
		'EN' => 'Please complete your email address!',
		'RO' => 'Bitte eine gütige Email Adresse eingeben!',
		'FR' => 'S\'il vous plaît compléter votre adresse e-mail!'		
	),
	'kontakt-06' => array(
		'EN' => 'Please complete subject!',
		'RO' => 'Bitte einen Betreff eingeben!',
		'FR' => 'S\'il vous plaît compléter le sujet!'		
	),
	'kontakt-07' => array(
		'EN' => 'Please complete with your message!',
		'RO' => 'Bitte eine Nachricht eingeben!',
		'FR' => 'Veuillez compléter avec votre message!'		
	),
		'kontakt-08' => array(
		'EN' => 'Your message was successfully sent!',
		'RO' => 'Die Nachricht wurde erfolgreich versendet!',
		'FR' => 'Votre message a été envoyé avec succès!'		
	),
	
	// Häuser
	'gebaeude-01' => array(
		'EN' => 'Palace',
		'RO' => 'Palast',
		'FR' => 'Palais'		
	),
	'gebaeude-02' => array(
		'EN' => 'Duplex',
		'RO' => 'Doppelhaus',
		'FR' => 'Duplex'		
	),
	'gebaeude-03' => array(
		'EN' => 'House',
		'RO' => 'Haus',
		'FR' => 'Maison'		
	),
	'gebaeude-04' => array(
		'EN' => 'Apartment',
		'RO' => 'Wohnung',
		'FR' => 'Appartement'		
	),
	'gebaeude-05' => array(
		'EN' => 'Studio',
		'RO' => 'Zimmer',
		'FR' => 'Studio'		
	),
	'gebaeude-06' => array(
		'EN' => 'Trailer',
		'RO' => 'Wohnwagen',
		'FR' => 'Caravane'		
	),
);
?>