<?php 
namespace WeiFang;

use WeiFang\Card;


/**
 * Deck of cards.
 */
class Deck {
	
	// value specific rankings
	private $values = array(
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
        11 => 'J',
        12 => 'Q',
        13 => 'K',
        14 => 'A'
    );
    // and Human readable suits
	private $suits  = array(
		'Club', 
		'Spade', 
		'Diamond', 
		'Heart'
	);
	
	// and finally an array to store all 52 combinations
	private $cards = array();
		
	
	/**
	 * Create a deck of cards and shuffle it immediately.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct(){
		
		foreach ($this->suits as $suit) {
			foreach ($this->values as $value => $name) {
				// create a new Card object for each of 
				// the classes and ranks
				// and add it to the deck
				$this->cards[] = new Card($suit, $value);
			}
		}

		//shuffle the cards
		shuffle($this->cards);
	}
	
	
	/**
	 * Remove a single card from the deck.
	 * 
	 * @access public
	 * @return Card
	 */
	public function deal()
	{
		return array_pop($this->cards);	
	}
}