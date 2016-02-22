<?php 
namespace WeiFang;

use WeiFang\Card;


/**
 * Hand
 * Class to keep track of player's cards and 
 * is responsible for calculating value
 * 
 */
class Hand {
	
    private $cards;	// array of card objects in this player's hole and community cards
    private $best;	// array of cards in the best hand
    private $points; // numerical value to weigh value of hand
    private $handName; // human readable name for the type of hand (flush, pair, etc.)
    
    
    /**
     * set possible cards for hand 
     *
     * @access public
     * @param array $cards
     * @return void
     */
    public function __construct($cards)
    {
        $this->cards = $cards;
    }
    
    /**
     * Get already-sorted array of cards
     * in the players hand
     * 
     * @access public
     * @return array
     */
    public function getHand()
    {
	    return $this->sortCards($this->best);
    }
    
    /**
     * Get human readable name of poker hand
     * 
     * @access public
     * @return string
     */
    public function getHandName()
    {
	    return $this->handName;
    }
    
    /**
     * Get list of the 5 cards in the best hand
     * in either an array of Card objects or a 
     * string formatted for output.
     * 
     * @access public
     * @param bool $implode (default: true)
     * @return string|array
     */
    public function getBestCards($implode = true)
    {
	    $cards = $this->best;
	    $strings = array();
	    foreach($cards as $card){
		    $strings[] = $card->getDescription();
	    }
	    
	    
	    return ($implode)?implode(", ", $strings):$cards;
    }
    
    /**
     * Calculate the possible hands with the 
     * players hole cards and community cards.
     * returns back a numerical value signifying 
     * the hand's value
     * 
     * @access public
     * @return int
     */
    public function getPoints()
    {
	    $cards = $this->cards;
	    
	    $high = $this->getHighCard($cards);
		$this->setDetails($high->getValue(), array($high), "High-Card Hand");
	    
	    if($hand = $this->isPair($cards)){
			$this->setDetails(100, $hand, "One Pair");
	    }
	    if($hand = $this->isTwoPairs($cards)){
			$this->setDetails(200, $hand, "Two Pair");
	    }
	    if($hand = $this->isTri($cards)){
			$this->setDetails(300, $hand, "Three of a Kind");
	    }
	    if($hand = $this->isStraight($cards)){
			$this->setDetails(400, $hand, "Straight");
	    }
	    if($hand = $this->isFlush($cards)){
			$this->setDetails(500, $hand, "Flush");
	    }
	    if($hand = $this->isFullHouse($cards)){
			$this->setDetails(600, $hand, "Full House");
	    }
	    if($hand = $this->isQuad($cards)){
			$this->setDetails(700, $hand, "Four of a Kind");
	    }
	    if($hand = $this->isStraightFlush($cards)){
			$this->setDetails(800, $hand, "Straight Flush");
	    }
	    if($hand = $this->isRoyalFlush($cards)){
		    $this->setDetails(900, $hand, "Royal Flush");
	    }
	    
		$this->padBestHand(); 
	    return $this->points;
    }
    
    /**
     * Updates the hand's details while calculating
     * the possible poker hands.
     * 
     * @access public
     * @param int $points (default: 0)
     * @param array $hand (default: array())
     * @param string $name (default: "")
     * @return void
     */
    public function setDetails($points = 0, $hand = array(), $name = "")
    {
	    $this->points = $points;
		$this->best = $hand;
		$this->padBestHand(); // fill the remaining slots with the highest value cards left
		$this->handName = $name;
    }
    
    /**
     * Orders a list of cards from highest to lowest.
     * 
     * @access public
     * @param array $cards
     * @return array
     */
    public function sortCards($cards)
    {
	    $deck = $cards;
	    usort($deck, function($a, $b){
			if ($a->getValue() == $b->getValue()) {
				return 0;
			}
			
			return ($a->getValue() > $b->getValue()) ? -1 : 1;
	    });
	    return $deck;
    }
    
    /**
     * Calculate if a set of cards is a Royal Flush.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isRoyalFlush($cards)
    {
	    $flush = $this->isFlush($cards); // if they aren't all the same suit, just return false
	    if($flush){
		    if($this->isStraight($flush)){
			    if($this->getHighCard($flush)->getValue() == 14){ 
				    // Is only a Royal flush when it ends with an Ace
				    return $flush;   
			    }
		    }
	    }
	    return false;
    }
    
    /**
     * Calculate if a set of cards is a flush.
     * meaning they all have the same suit,
     * regardless of rank.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isFlush($cards)
    {
	    $suits = array();
	    foreach($cards as $card){
		    $suits[$card->getSuit()][] = $card;
	    }
	    foreach($suits as $suit){
		    if(count($suit) >= 5){
			    return $suit;
		    }
	    }
	    return false;
    }
    
    /**
     * Calculate if a set of cards is a straight run,
     * this requires 5 cards with sequential ranks.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isStraight($cards)
    {
	    $deck = $this->sortCards($cards);
	    
	    $streak = 0;
	    $next = 0;
	    
	    $deck = array_reverse($deck);
	    
	    foreach($deck as $card){
		    if($card->getValue() != $next){
			    $next = $card->getValue();   
			    $streak = array();
		    }
		    else{
			    $streak[] = $card;
		    }
			$next++;
	    }
	    return (count($streak) >= 4)?$streak:false;
	    // send back the cards in the streak, or false
    }
    
    /**
     * Find a single pair inside a set of cards.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isPair($cards)
    {
		$values = array();
		foreach($cards as $card){
			if(!empty($values[$card->getValue()])){
				//if this isn't empty, it contains a matching ranked card
				//return them both
				return array($values[$card->getValue()], $card);
			}
			else{
				$values[$card->getValue()] = $card;
			}
		}
		return false;
    }
    
    /**
     * Find two separate pairs in a set of cards.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isTwoPairs($cards)
    {
	    $values = array();
	    $pairs = array();
	    foreach($cards as $card){
			if(!empty($values[$card->getValue()])){
				$values[$card->getValue()][] = $card;
				$pairs[] = $values[$card->getValue()];
				$values[$card->getValue()] = array();
			}
			else{
				$values[$card->getValue()][] = $card;
			}
		}
		if(count($pairs)>1){
			// once you have more than one pair, send them up
			return array_merge($pairs[0], $pairs[1]);
		}
		// or return false
		return false;
    }
    
    /**
     * Find a group of three matching ranked cards in a set.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isTri($cards)
    {
	    $values = array();
	    foreach($cards as $card){
			if( array_key_exists($card->getValue(), $values) && count($values[$card->getValue()]) == 2 ){
				//once there's 3 matching cards, put them in an array and 
				//return it for further processing
				$values[$card->getValue()][] = $card;
				return $values[$card->getValue()];
			}
			else{
				$values[$card->getValue()][] = $card;
			}
		}
		return false;
    }
    
    /**
     * Calculate a full house in a set of cards.
     * this is a set of 2 and a separate set of 3
     * matching ranked cards.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isFullHouse($cards)
    {
	    $values = array();
	    foreach($cards as $card){
			$values[$card->getValue()] = $card;
		}
		$two = false;	// find a group of two cards
		$three = false; // and another group of three
		foreach($values as $value){
			if(count($value) == 2){
				$two = $value;
			}
			if(count($value) == 3){
				$three = $value;
			}
		}
		if($two && $three){
			return array_merge($two, $three);
			//combine the two separate groups 
			//into one single poker hand
		}
		return false;
    }
    
    /**
     * Find four identically ranked cards in a set.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isQuad($cards)
    {
	    $values = array();
	    foreach($cards as $card){
			if( array_key_exists($card->getValue(), $values) && count($values[$card->getValue()]) == 3 ){
				//once there's 4 matching cards, put them in an array and 
				//return it for further processing
				$values[$card->getValue()][] = $card;
				return $values[$card->getValue()];
			}
			else{
				$values[$card->getValue()][] = $card;
			}
		}
		return false;
    }
    
    /**
     * Find a straight flush in a set of cards.
     * 
     * @access public
     * @param array $cards
     * @return array|false
     */
    public function isStraightFlush($cards)
    {
	    $streak = $this->isStraight($this->cards); //will need to be a straight
	    if($streak && $this->isFlush($streak)){
		    //and also a flush. that's it, send it back for further processing
		    return $streak;
	    }
	    return false;
    }
    
    /**
     * Find the highest value card in a set.
     * 
     * @access public
     * @param array $cards (default: array())
     * @return void
     */
    public function getHighCard($cards = array())
    {
	    if(empty($cards)){
		    $cards = $this->cards;
	    }
	    $deck = $this->sortCards($cards);//sort the set from highest to lowest
	    return $deck[0];//and send back the first result
    }
    
    /**
     * Once a poker hand is found, we need to fill the 
     * rest of the set with the highest value cards to 
     * work as kickers in the event of a tie.
     * 
     * @access public
     * @return void
     */
    public function padBestHand()
    {
	    if(count($this->best) == 5){
		    return false;
	    }
	    $deck = $this->sortCards($this->cards);
	    foreach($deck as $card){
		    $add = true;
		    foreach($this->best as $handCard){
				if($card->getValue() == $handCard->getValue()){
					if($card->getSuit() == $handCard->getSuit()){
						$add = false;
					}
				}
		    }
		    if($add && count($this->best) < 5){
			    $this->best[] = $card;
		    }
	    }
    }
}
