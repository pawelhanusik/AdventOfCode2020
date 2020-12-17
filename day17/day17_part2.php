<?php

$input = file_get_contents('input.txt');

class Cube {
    private $state;
    private PocketDimension $pocketDimension;
    public int $posX, $posY, $posZ, $posW;

    public function __construct($state, int $posX, int $posY, int $posZ, $posW, PocketDimension &$pocketDimension){
        $this->state = $state;
        $this->posX = $posX;
        $this->posY = $posY;
        $this->posZ = $posZ;
        $this->posW = $posW;
        $this->pocketDimension = $pocketDimension;
    }
    public function isActive(){
        return ($this->state === '#');
    }
    public function activate(){
        $this->state = '#';
    }
    public function deactivate(){
        $this->state = '.';
    }
    public function getNeighbours(){
        $ret = [];

        for($w = -1; $w <= 1; ++$w){
            for($z = -1; $z <= 1; ++$z){
                for($y = -1; $y <= 1; ++$y){
                    for($x = -1; $x <= 1; ++$x){
                        if($x == 0 && $y == 0 && $z == 0 && $w == 0){
                            continue;
                        }
                        $ret[] = $this->pocketDimension->getCubeAt($this->posX + $x, $this->posY + $y, $this->posZ + $z, $this->posW + $w);
                    }
                }
            }
        }

        return $ret;
    }
}
class PocketDimension {
    private $cubes = [];

    public function __construct(string $z0layout){
        $w = 0;
        $z = 0;
        
        $z0layout = explode("\n", $z0layout);
        for($y = 0; $y < count($z0layout); ++$y){
            $line = $z0layout[$y];
            for($x = 0; $x < strlen($line); ++$x){
                $state = $line[$x];
                $this->setCubeAt($x, $y, $z, $w, $state);
            }
        }
    }

    public function setCubeAt(int $x, int $y, int $z, int $w, $state){
        if(!key_exists($w, $this->cubes)){
            $this->cubes[$w] = [];
        }
        if(!key_exists($z, $this->cubes[$w])){
            $this->cubes[$w][$z] = [];
        }
        if(!key_exists($y, $this->cubes[$w][$z])){
            $this->cubes[$w][$z][$y] = [];
        }

        $this->cubes[$w][$z][$y][$x] = new Cube($state, $x, $y, $z, $w, $this);
        return $this->cubes[$w][$z][$y][$x];
    }
    private function cubeExists($x, $y, $z, $w){
        if(key_exists($w, $this->cubes))
            if(key_exists($z, $this->cubes[$w]))
                if(key_exists($y, $this->cubes[$w][$z]))
                    if(key_exists($x, $this->cubes[$w][$z][$y]))
                        return true;
        return false;
    }
    public function getCubeAt($x, $y, $z, $w){
        if($this->cubeExists($x, $y, $z, $w)){
            return $this->cubes[$w][$z][$y][$x];
        }
        return $this->setCubeAt($x, $y, $z, $w, '.');
    }
    public function getAllCubes($onlyActiveMatters = false){
        $ret = [];

        $minX = 0;
        $minY = 0;
        $minZ = 0;
        $minW = 0;
        $maxX = 0;
        $maxY = 0;
        $maxZ = 0;
        $maxW = 0;

        foreach($this->cubes as $cubesAtW){
            foreach($cubesAtW as $cubesAtZ){
                foreach($cubesAtZ as $cubesAtY){
                    foreach($cubesAtY as $cube){
                        if($cube->isActive()){
                            $minX = min($minX, $cube->posX);
                            $minY = min($minY, $cube->posY);
                            $minZ = min($minZ, $cube->posZ);
                            $minW = min($minW, $cube->posW);
                            $maxX = max($maxX, $cube->posX);
                            $maxY = max($maxY, $cube->posY);
                            $maxZ = max($maxZ, $cube->posZ);
                            $maxW = max($maxW, $cube->posW);
                        }
                    }
                }
            }
        }

        if(!$onlyActiveMatters){
            --$minX;
            --$minY;
            --$minZ;
            --$minW;
            ++$maxX;
            ++$maxY;
            ++$maxZ;
            ++$maxW;
        }

        for($w = $minW; $w <= $maxW; ++$w){
            for($z = $minZ; $z <= $maxZ; ++$z){
                for($y = $minY; $y <= $maxY; ++$y){
                    for($x = $minX; $x <= $maxX; ++$x){
                        $ret[] = $this->getCubeAt($x, $y, $z, $w);
                    }
                }
            }
        }

        return $ret;
    }

    public function simulateCycle($numberOfCycles = 1){
        for($i = 0; $i < $numberOfCycles; ++$i){
            echo "Sim step #$i\n";
            $toActivate = [];
            $toDeactivate = [];

            $allCubes = $this->getAllCubes(false);
            foreach($allCubes as $cube){
                $activeNeighbours = 0;
                $neighbours = $cube->getNeighbours();
                foreach($neighbours as $neighbour){
                    if($neighbour->isActive()){
                        ++$activeNeighbours;
                    }
                }

                if( $cube->isActive() ){
                    if( $activeNeighbours != 2 && $activeNeighbours != 3 ){
                        $toDeactivate[] = $cube;
                    }
                }else{
                    if( $activeNeighbours == 3 ){
                        $toActivate[] = $cube;
                    }
                }
            }
            
            foreach($toActivate as $cube){
                $cube->activate();
            }
            foreach($toDeactivate as $cube){
                $cube->deactivate();
            }
        }
    }

    public function getActiveCubes(){
        $allCubes = $this->getAllCubes(true);
        $ret = [];
        foreach($allCubes as $cube){
            if($cube->isActive()){
                $ret[] = $cube;
            }
        }
        return $ret;
    }

    public function __toString(){
        $ws = [];
        $allCubes = $this->getAllCubes(true);
        foreach($allCubes as $cube){
            if(!key_exists($cube->posW, $ws)){
                $ws[$cube->posW] = [];
                ksort($ws);
            }
            if(!key_exists($cube->posZ, $ws[$cube->posW])){
                $ws[$cube->posW][$cube->posZ] = [];
                ksort($ws[$cube->posW]);
            }
            if(!key_exists($cube->posY, $ws[$cube->posW][$cube->posZ])){
                $ws[$cube->posW][$cube->posZ][$cube->posY] = [];
                ksort($ws[$cube->posW][$cube->posZ]);
            }
            $ws[$cube->posW][$cube->posZ][$cube->posY][$cube->posX] = $cube->isActive() ? '#' : '.';
            ksort($ws[$cube->posW][$cube->posZ][$cube->posY]);
        }

        $ret = "";
        foreach($ws as $w => $wVal){
            foreach( $wVal as $z => $zVal ){
                $ret .= "z=$z, w=$w\n";

                foreach( $zVal as $y => $yVal){
                    foreach( $yVal as $x => $xVal ){
                        $ret .= $xVal;
                    }
                    $ret .= "\n";
                }

                $ret .= "\n";
            }
        }
        return $ret;
    }
}

$pd = new PocketDimension($input);
$pd->simulateCycle(6);
$activeCubes = $pd->getActiveCubes();
echo "Number of active cubes: " . count($activeCubes) . "\n";

?>