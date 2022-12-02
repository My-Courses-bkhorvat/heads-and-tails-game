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

if ($_POST['player1Name'] && $_POST['player1Coins'] && $_POST['player2Name'] && $_POST['player2Coins']) {
    if (strlen($_POST['player1Coins']) > 4 && strlen($_POST['player2Coins']) > 4) {
        echo "Big numbers is a very long game. Enter a lower number.";
    } else {
        $game = new Game(
            new Player($_POST['player1Name'], $_POST['player1Coins']),
            new Player($_POST['player2Name'], $_POST['player2Coins'])
        );

        $game->start();
    }
} else {
    echo "Fill in all the fields";
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Heads and tails</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
<form action="index.php" method="post">
    <div class="mb-3">
        <label for="player1Name" class="form-label">Player 1 name</label>
        <input type="text" class="form-control" id="player1Name" name="player1Name" value="<?=$_POST['player1Name']?>">
        <label for="player1Coins" class="form-label">Player 1 coins</label>
        <input type="text" class="form-control" id="player1Coins" name="player1Coins" value="<?=$_POST['player1Coins']?>">
    </div>
    <div class="mb-3">
        <label for="player2Name" class="form-label">Player 2 name</label>
        <input type="text" class="form-control" id="player2Name" name="player2Name" value="<?=$_POST['player2Name']?>">
        <label for="player2Coins" class="form-label">Player 2 coins</label>
        <input type="text" class="form-control" id="player2Coins" name="player2Coins" value="<?=$_POST['player2Coins']?>">
    </div>
    <button type="submit" class="btn btn-primary">Play</button>
</form>
</body>
</html>