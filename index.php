<?php

class Player
{
    public $name;
    public $coins;

    public function __construct($name, $coins)
    {
        $this->name = $name;
        $this->coins = $coins;
    }
}

class Game
{
    protected $player1;
    protected $player2;

    public function __construct(Player $player1, Player $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function start()
    {
        while (true) {
            // Toss a coin
            $flip = rand(0, 1) ? "heads" : "tails";

            // if heads n1 gains a coin, n2 loses a coin
            // if tails n1 loses a coin, n2 gains a coin
            if ($flip == "heads") {
                $this->player1->coins++;
                $this->player2->coins--;
            } else {
                $this->player1->coins--;
                $this->player2->coins++;
            }

            // If someone has 0 coins, then the game is over.
            if ($this->player1->coins == 0 || $this->player2->coins == 0) {
                return $this->end();
            }
        }
    }
    public function winner()
    {
        if ($this->player1->coins > $this->player2->coins) {
            return $this->player1;
        } else {
            return $this->player2;
        }
    }

    public function end()
    {
        //The winner is the one with the most coins.

        echo <<<EOT
            Game over.
            
            Winner: {$this->winner()->name}
EOT;
    }
}

$game = new Game(
    new Player('Joe', '100'),
    new Player('Jane', '100')
);

$game->start();