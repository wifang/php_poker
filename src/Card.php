<?php 

namespace WeiFang;


/**
 * A single card in a deck.
 */
class Card {
	
    protected $value;	// the rank 
    protected $suit;	// and suit
    
    // as well as possible values
    protected $cardValues = [
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
    ];
    protected $cardSuits = [
        'Club',
        'Spade',
        'Diamond',
        'Heart'
    ];

	
    /**
     * Create a new card based on rank and suit.
     * 
     * @access public
     * @param string $suit (default: null)
     * @param string $value (default: null)
     * @return void
     */
    public function __construct($suit = null, $value = null)
    {
        if ( ! is_null($suit)) {
            $this->suit = $suit;
        }
        if ( ! is_null($value)) {
            $this->value = $value;
        }
    }
    
    /**
     * Returns a legible description of the card
     * suit and value
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->cardValues[$this->getValue()] . ' of ' . $this->getSuit() . 's';
    }
    
    /**
     * Returns the value of this card
     * 
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }
    
    /**
     * Returns the suit of this card
     * 
     * @return string
     */
    public function getSuit()
    {
        return $this->suit;
    }
}