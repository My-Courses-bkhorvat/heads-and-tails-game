<?php

class Player
{
    public $i;
    public $name;
    public $coins;

    public function __construct($name, $coins)
    {
        $this->name = $name;
        $this->coins = $coins;
    }

    public function point(Player $player)
    {
        $this->coins++;
        $player->coins--;
    }

    public function bankrupt()
    {
        return $this->coins == 0;
    }

    public function bank()
    {
        return $this->coins;
    }

    public function oods(Player $player)
    {
        return round($this->bank() / ($this->bank() + $player->bank()), 2) * 100 . "%";
    }
}

class Game
{
    protected $player1;
    protected $player2;
    protected $flips = 1;

    public function __construct(Player $player1, Player $player2)
    {
        $this->player1 = $player1;
        $this->player2 = $player2;
    }

    public function flip()
    {
        // Toss a coin
        return rand(0, 1) ? "heads" : "tails";
    }

    public function start()
    {
        echo <<<EOT
            Game started.
            {$this->player1->name} chances: {$this->player1->oods($this->player2)}
            {$this->player2->name} chances: {$this->player2->oods($this->player1)}
            

EOT;

        $this->play();
    }

    public function play()
    {
        while (true) {
            // if heads n1 gains a coin, n2 loses a coin
            // if tails n1 loses a coin, n2 gains a coin
            if ($this->flip() == "heads") {
                $this->player1->point($this->player2);
            } else {
                $this->player2->point($this->player1);
            }

            // If someone has 0 coins, then the game is over.
            if ($this->player1->bankrupt() || $this->player2->bankrupt()) {
                return $this->end();
            }

            $this->flips++;
        }
    }

    public function winner(): Player
    {
        return $this->player1->bank() > $this->player2->bank() ? $this->player1 : $this->player2;
    }

    public function end()
    {
        //The winner is the one with the most coins.

        echo <<<EOT
            Game over.
            {$this->player1->name}: {$this->player1->coins}
            {$this->player2->name}: {$this->player2->coins}
            
            Winner: {$this->winner()->name}
            
            Count of flips: {$this->flips}
EOT;
    }
}

$game = new Game(
    new Player('Joe', '10000'),
    new Player('Jane', '100')
);

$game->start();
