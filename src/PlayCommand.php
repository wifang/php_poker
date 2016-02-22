<?php 
namespace WeiFang;

use WeiFang\Deck;
use WeiFang\Hand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;


/**
 * Class to handle the Command Line response to the "play" command.
 * 
 * @extends Command
 */
class PlayCommand extends Command {
	
	private $players = array();	//array of players
	private $river = array();	//array of communal cards
	
	public function configure(){
		$this->setName('play')	// set the CLI command to react to
			 ->setDescription('Deal cards to players and calculate winner') // Description of the play command
			 ->addArgument('players', InputArgument::REQUIRED, 'Number of players');	// Add an argument to track number of players
	}
	
	public function execute(InputInterface $input, OutputInterface $output){
		// Identify how many players to deal out
		$player_count = $input->getArgument('players');
		$message = "Dealing Cards for " . $player_count . " players.";
		$output->writeln("<comment>{$message}</comment>");
		//Give a quick message to give visual confirmation that the player count was read correctly 
		
		// create deck of cards and shuffle
		$deck = new Deck();
		
		// deal user's "hole" cards
		for ( $i = 0; $i < $player_count; $i++ ) { 
		    $this->players[$i] = array(); 
		    array_push( $this->players[$i], $deck->deal() ); 
		    array_push( $this->players[$i], $deck->deal() ); 
		} 
		// deal "community" cards
		while ( 5 > count($this->river) ) { 
		    array_push( $this->river, $deck->deal() ); 
		}
		
		$winners = array();
		for ( $i = 0; $i < $player_count; $i++ ) { 
			// loop through each of the users
			$cards = array_merge($this->players[$i], $this->river);	//combine all cards into one hand
			$hand = new Hand($cards);
			$points = $hand->getPoints();	//calculate possible poker hands in card set
			$best = $hand->getHand();	// get the best one
			$cards = $hand->getBestCards(true);	// get a human readable string of the cards
			$set = $hand->getBestCards(false); // and an actual list of the cards used
			$handName = $hand->getHandName();	// retreive the human readable name of the poker hand
			
			// setup a new array containing all of the player/hand details needed for the next step
			$winners[] = array(
				'name' => 'Player #'.($i+1),
				'points' => $points,
				'cards' => $cards,
				'hand' => $handName,
				'set' => $set
			);
			
		}
		
		// compare all of the player's hands to find the overall winner
		usort($winners, function($a, $b){
			if ($a['points'] == $b['points']) {
				// if two players got the same poker hand
				for($i=0;$i<5;$i++){
					// loop through all of the other cards until you find that one user
					// has a trumping card over the other
					if( $a['set'][$i]->getValue() == $b['set'][$i]->getValue() ){
						continue;
					}
					return ($a['set'][$i]->getValue() > $b['set'][$i]->getValue()) ? -1 : 1;
					// and return the results to the quicksort 
				}
			}
			//if the players didn't have the same hand, one would have won out with more points
			return ($a['points'] > $b['points']) ? -1 : 1;
	    });
		
		//setup a table for nicer formatted responses to the Command Line
		$table = new Table($output);
		$table->setHeaders(array('Name', 'Hand', 'Cards')); // and define the columns we want to display
		
		$first = true; // the first player in this array will be the overall winner
		foreach($winners as $winner){
			if($first){
				//so we can find them and format this single record to be blue
				// for no real reason other than I like blue and figured the winner should
				// get to stick out a bit more
				$name = "<fg=blue>".$winner['name']."</>";
				$hand = "<fg=blue>".$winner['hand']."</>";
				$cards = "<fg=blue>".$winner['cards']."</>";
				$first = false;
			}else{
				// everyone else gets boring white
				$name = $winner['name'];
				$hand = $winner['hand'];
				$cards = $winner['cards'];
			}
			$row = array(array(
				$name,
				$hand,
				$cards
			));
			$table->addRows($row);
			// add the rows to the table 
		}
		$table->render();
		// and render it in the terminal
	}
}