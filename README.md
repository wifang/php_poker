# PHP CLI Texas Hold-em
PHP CLI program to simulate Texas Hold-em style poker hands. 

###Setup

$ ./php_cli_poker play 5
```

Where 5 is the number of players to be dealt into the game. Each player is dealt two 'hole' cards before dealing 5 communal cards to be shared by all players. From these 7 cards, each player takes the 5 most beneficial cards to complete their best poker hand. 

###Reading the Results

Dealing Cards for 10 players.
+------------+-----------------+--------------------------------------------------------------------------------------+
| Name       | Hand            | Cards                                                                                |
+------------+-----------------+--------------------------------------------------------------------------------------+
| Player #6  | Three of a Kind | 7 of Hearts, 7 of Spades, 7 of Clubs, 8 of Hearts, 4 of Diamonds  |
| Player #8  | Two Pair        | 8 of Diamonds, 8 of Hearts, 7 of Spades, 7 of Clubs, Q of Hearts  |
| Player #3  | Two Pair        | 8 of Spades, 8 of Hearts, 7 of Spades, 7 of Clubs, 10 of Clubs    |
| Player #1  | Two Pair        | 8 of Clubs, 8 of Hearts, 7 of Spades, 7 of Clubs, 10 of Diamonds  |
| Player #7  | Two Pair        | 4 of Hearts, 4 of Clubs, 7 of Spades, 7 of Clubs, 8 of Hearts     |
| Player #5  | Two Pair        | 3 of Clubs, 3 of Hearts, 7 of Spades, 7 of Clubs, K of Hearts     |
| Player #9  | Two Pair        | 3 of Diamonds, 3 of Hearts, 7 of Spades, 7 of Clubs, 8 of Hearts  |
| Player #10 | One Pair        | 7 of Spades, 7 of Clubs, A of Spades, J of Diamonds, 8 of Hearts  |
| Player #4  | One Pair        | 7 of Spades, 7 of Clubs, A of Diamonds, 8 of Hearts, 5 of Hearts  |
| Player #2  | One Pair        | 7 of Spades, 7 of Clubs, 10 of Hearts, 8 of Hearts, 6 of Spades   |
+------------+-----------------+--------------------------------------------------------------------------------------+
```
