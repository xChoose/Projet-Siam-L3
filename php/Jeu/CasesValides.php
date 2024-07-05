<?php

// Récupérer les données envoyées par AJAX
$mapJSON = $_POST['map'];
$verifCaseJSON = $_POST['verifCase'];

// Convertir les chaînes JSON en tableaux PHP
$map = json_decode($mapJSON, true);
$verifCase = json_decode($verifCaseJSON, true);


function estPion($map,$x,$y) { 
    if ($map[$x][$y][0] == "E" || $map[$x][$y][0] == "O" || $map[$x][$y][0] == "R") {
        return true;
    }
    return false;
}

function pousserValide($map, $x, $y, $direction) {
    $pionAPousser = 0;
    $pionOppose = 0;
    $pionSoutien = 1;
    $vertical = 0;
    $horizontal = 0;
    if ($direction == 0) {
        $vertical = -1;
    } else if ($direction == 1) {
        $horizontal = 1;
    } else if ($direction == 2) {
        $vertical = 1;
    } else {
        $horizontal = -1;
    }
    if ($vertical == 1) {
        while ($x <= 6 && $x >= 0 && estPion($map,$x,$y)) {
            if ($map[$x][$y][0] == "O") {
                $pionAPousser++;
            } 
            else {
                if ($map[$x][$y][1] == 0) {
                    $pionOppose++;
                }
                if ($map[$x][$y][1] == 2) {
                    $pionSoutien++;
                }
            }
            if ($pionOppose >= $pionSoutien) {
                return false;
            }
            $x++;
        }
        if ($pionAPousser + $pionOppose > $pionSoutien || $pionOppose >= $pionSoutien) {
            return false;
        } else {
            return true;
        }
    } 
    if ($vertical == -1) {
        while ($x <= 6 && $x >= 0 && estPion($map,$x,$y)) {
            if ($map[$x][$y][0] == "O") {
                $pionAPousser++;
            } else {
                if ($map[$x][$y][1] == 2) {
                    $pionOppose++;
                }
                if ($map[$x][$y][1] == 0) {
                    $pionSoutien++;
                }
            }
            if ($pionOppose >= $pionSoutien) {
                return false;
            }
            $x--;
        }
        if ($pionAPousser + $pionOppose > $pionSoutien || $pionOppose >= $pionSoutien) {
            return false;
        } else {
            return true;
        }
    }
    if ($horizontal == 1) {
        while ($y <= 6 && $y >= 0 && estPion($map,$x,$y)) {
            if ($map[$x][$y][0] == "O") {
                $pionAPousser++;
            } else {
                if ($map[$x][$y][1] == 1) {
                    $pionSoutien++;
                }
                if ($map[$x][$y][1] == 3) {
                    $pionOppose++;
                }
            }
            if ($pionOppose >= $pionSoutien) {
                return false;
            }
            $y++;
        }
        if ($pionAPousser + $pionOppose > $pionSoutien || $pionOppose >= $pionSoutien) {
            return false;
        } else {
            return true;
        }
    } 
    if ($horizontal == -1) {
        while (estPion($map,$x,$y)) {
            if ($map[$x][$y][0] == "O") {
                $pionAPousser++;
            } else {
                if ($map[$x][$y][1] == 3) {
                    $pionSoutien++;
                }
                if ($map[$x][$y][1] == 1) {
                    $pionOppose++;
                }
            }
            if ($pionOppose >= $pionSoutien) {
                return false;
            }
            $y--;
        }
        if ($pionAPousser + $pionOppose > $pionSoutien || $pionOppose >= $pionSoutien) {
            return false;
        } else {

            return true;
        }
    }
}

function casesAutour($map, $x, $y) {
    $position = [[-1,0],[0,1],[1,0],[0,-1]];
    $ret = array();

    for ($i = 0; $i < count($position); $i++) {
        $newX = $x + $position[$i][0];
        $newY = $y + $position[$i][1];
        if ($i == $map[$x][$y][1]) {
            if (estPion($map, $newX, $newY) && pousserValide($map, $newX, $newY, $i)) {
                array_push($ret, array($newX, $newY));
            }
        } else {
            if (!estPion($map, $newX, $newY)) {
                array_push($ret, array($newX, $newY));
            }
        }
    }
    return json_encode(["casesValides" => $ret]);
}


$response = casesAutour($map, $verifCase[0], $verifCase[1]);
echo $response;
?>