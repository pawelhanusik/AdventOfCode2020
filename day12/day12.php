<?php

class Pos {
    public $x, $y, $facing;

    public function __construct($x = 0, $y = 0){
        $this->x = $x;
        $this->y = $y;
    }
    public function __toString()
    {
        return "$this->x $this->y";
    }
}
class Movable {
    public $pos;
    
    public function goDirection($direction, $amount){
        switch($direction){
        case 'N':
            $this->pos->y += $amount;
            break;
        case 'E':
            $this->pos->x += $amount;
            break;
        case 'S':
            $this->pos->y -= $amount;
            break;
        case 'W':
            $this->pos->x -= $amount;
            break;
        }
    }
}
class Waypoint extends Movable {
    public function __construct(Pos $pos = null){
        $this->pos = $pos !== null ? $pos : new Pos(10, 1);
    }

    public function rotate($dir, $amount){
        $tmpPos = clone $this->pos;
        if($dir == 'R'){
            switch($amount){
            case 90:
                $this->pos->x = +$tmpPos->y;
                $this->pos->y = -$tmpPos->x;
                break;
            case 180:
                $this->pos->x = -$tmpPos->x;
                $this->pos->y = -$tmpPos->y;
                break;
            case 270:
                $this->pos->x = -$tmpPos->y;
                $this->pos->y = +$tmpPos->x;
            }
        }else{
            switch($amount){
            case 90:
                $this->pos->x = -$tmpPos->y;
                $this->pos->y = +$tmpPos->x;
                break;
            case 180:
                $this->pos->x = -$tmpPos->x;
                $this->pos->y = -$tmpPos->y;
                break;
            case 270:
                $this->pos->x = +$tmpPos->y;
                $this->pos->y = -$tmpPos->x;
            }
        }
    }
}
class Ship extends Movable {
    public int $rot;
    public ?Waypoint $waypoint;
    private $directions = ['N', 'E', 'S', 'W'];

    public function __construct(Pos $pos = null, int $rot = 90, $waypoint = null){
        $this->pos = $pos !== null ? $pos : new Pos();
        $this->rot = $rot;
        $this->waypoint = $waypoint;
    }

    public static function PartOne(){
        return new Ship(new Pos(0, 0), 90, null);
    }
    public static function PartTwo(){
        return new Ship(new Pos(0, 0), 90, new Waypoint(new Pos(10, 1)));
    }

    
    public function getFacingDirection(){
        return $this->directions[($this->rot / 90) % 4];
    }
    public function rotate($direction, $amount){
        if($direction == 'R'){
            $this->rot += $amount;
        }else{
            $this->rot -= $amount;
            $this->rot += 360;
        }
    }
    public function goForward($amount){
        $this->goDirection($this->getFacingDirection(), $amount);
    }
    public function goByWaypoint($amount){
        $this->pos->x += $this->waypoint->pos->x * $amount;
        $this->pos->y += $this->waypoint->pos->y * $amount;
    }

    public function parseInstruction($dir, $amount){
        if($this->waypoint === null){
            if($dir == 'F'){
                $this->goForward($amount);
            }else if($dir == 'L' || $dir == 'R'){
                $this->rotate($dir, $amount);
            }else{
                $this->goDirection($dir, $amount);
            }
        }else{
            if($dir == 'F'){
                $this->goByWaypoint($amount);
            }else if($dir == 'L' || $dir == 'R'){
                $this->waypoint->rotate($dir, $amount);
            }else{
                $this->waypoint->goDirection($dir, $amount);
            }
        }
    }

    public function manhattanDistance(){
        return abs($this->pos->x) + abs($this->pos->y);
    }
    public function __toString()
    {
        return "Ship at $this->pos, facing " . $this->getFacingDirection() . ($this->waypoint !== null ? ', Waypoint at: ' . $this->waypoint->pos : '');
    }
}

//===============================================

$input = file_get_contents('input.txt');
$input = explode("\n", $input);

echo "Part One\n";
$ship = Ship::PartOne();
foreach($input as $instruction){
    $dir = $instruction[0];
    $amount = intval( substr($instruction, 1) );
    $ship->parseInstruction($dir, $amount);
}
echo "Manhattan distance: " . $ship->manhattanDistance() . "\n";

echo "Part Two\n";
$ship = Ship::PartTwo();
foreach($input as $instruction){
    $dir = $instruction[0];
    $amount = intval( substr($instruction, 1) );
    $ship->parseInstruction($dir, $amount);
}
echo "Manhattan distance: " . $ship->manhattanDistance() . "\n";
?>