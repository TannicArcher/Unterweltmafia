<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	
	class PlayingCardDeck
	{
		private $stockDeck = array(
			/*
				id => id, suit, name, initial
			*/
			
			array(1, 'clubs', 'ace', 'a'),
			array(2, 'clubs', 2, '2'),
			array(3, 'clubs', 3, '3'),
			array(4, 'clubs', 4, '4'),
			array(5, 'clubs', 5, '5'),
			array(6, 'clubs', 6, '6'),
			array(7, 'clubs', 7, '7'),
			array(8, 'clubs', 8, '8'),
			array(9, 'clubs', 9, '9'),
			array(10, 'clubs', 10, '10'),
			array(11, 'clubs', 'jack', 'j'),
			array(12, 'clubs', 'queen', 'q'),
			array(13, 'clubs', 'king', 'k'),
			
			array(14, 'diamonds', 'ace', 'a'),
			array(15, 'diamonds', 2, '2'),
			array(16, 'diamonds', 3, '3'),
			array(17, 'diamonds', 4, '4'),
			array(18, 'diamonds', 5, '5'),
			array(19, 'diamonds', 6, '6'),
			array(20, 'diamonds', 7, '7'),
			array(21, 'diamonds', 8, '8'),
			array(22, 'diamonds', 9, '9'),
			array(23, 'diamonds', 10, '10'),
			array(24, 'diamonds', 'jack', 'j'),
			array(25, 'diamonds', 'queen', 'q'),
			array(26, 'diamonds', 'king', 'k'),
			
			array(27, 'hearts', 'ace', 'a'),
			array(28, 'hearts', 2, '2'),
			array(29, 'hearts', 3, '3'),
			array(30, 'hearts', 4, '4'),
			array(31, 'hearts', 5, '5'),
			array(32, 'hearts', 6, '6'),
			array(33, 'hearts', 7, '7'),
			array(34, 'hearts', 8, '8'),
			array(35, 'hearts', 9, '9'),
			array(36, 'hearts', 10, '10'),
			array(37, 'hearts', 'jack', 'j'),
			array(38, 'hearts', 'queen', 'q'),
			array(39, 'hearts', 'king', 'k'),
			
			array(40, 'spades', 'ace', 'a'),
			array(41, 'spades', 2, '2'),
			array(42, 'spades', 3, '3'),
			array(43, 'spades', 4, '4'),
			array(44, 'spades', 5, '5'),
			array(45, 'spades', 6, '6'),
			array(46, 'spades', 7, '7'),
			array(47, 'spades', 8, '8'),
			array(48, 'spades', 9, '9'),
			array(49, 'spades', 10, '10'),
			array(50, 'spades', 'jack', 'j'),
			array(51, 'spades', 'queen', 'q'),
			array(52, 'spades', 'king', 'k')
		);
		
		private $theDeck = array();
		
		
		function __construct($setDeck = false)
		{
			$this->setDeck(is_array($setDeck) ? $setDeck : $this->stockDeck);
		}
		
		function setDeck($deck)
		{
			$this->theDeck = $deck;
		}
		
		function getDeck($serialize = false)
		{
			return $serialize !== false ? serialize($this->theDeck) : $this->theDeck;
		}
		
		function drawCard()
		{
			$card = array_rand($this->theDeck);
			$theCard = $this->theDeck[$card];
			
			unset($this->theDeck[$card]);
			
			return $theCard;
		}
	}
?>