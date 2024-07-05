<?php

// Récupérer les données envoyées par AJAX
$mapJSON = $_POST['map'];
$casesEntreeJSON = $_POST['casesEntree'];
$directionJSON = $_POST["direction"];


// Convertir les chaînes JSON en tableaux PHP
$map = json_decode($mapJSON, true);
$casesEntree = json_decode($casesEntreeJSON, true);
$direction = json_decode($directionJSON, true);

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
            if ($pionOppose >= $pionSoutien) {
                return false;
            }
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
            if ($pionOppose >= $pionSoutien) {
                return false;
            }
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
            if ($pionOppose >= $pionSoutien) {
                return false;
            }
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
            if ($pionOppose >= $pionSoutien) {
                return false;
            }
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
            $y--;
        }
        if ($pionAPousser + $pionOppose > $pionSoutien || $pionOppose >= $pionSoutien) {
            return false;
        } else {

            return true;
        }
    }
}

// Fonction pour vérifier les cases valides
function trouverCasesValides($map, $casesEntree, $direction) {
    $ret = array();
    for($i = 0; $i < count($casesEntree); $i++) {
        $tuple = explode(",", $casesEntree[$i]);
        $x = $tuple[0];
        $y = $tuple[1];
        if ($direction == 0 && $x == 5) {
            if (estPion($map,$x,$y) && pousserValide($map,$x,$y, $direction)) {
                $ret[] = $tuple;
            }
        } 
        if ($direction == 1 && $y == 1) {
            if (estPion($map,$x,$y) && pousserValide($map,$x,$y, $direction)) {
                $ret[] = $tuple;
            }
        }
        if ($direction == 2 && $x == 1) {
            if (estPion($map,$x,$y) && pousserValide($map,$x,$y, $direction)) {
                $ret[] = $tuple;
            }
        }
        if ($direction == 3 && $y == 5) {
            if (estPion($map,$x,$y) && pousserValide($map,$x,$y, $direction)) {
                $ret[] = $tuple;
            }
        }
        if (!estPion($map,$x,$y)) {
            $ret[] = $tuple;
        }
    } 
    return json_encode(["casesValides" => $ret]);
}

$response = trouverCasesValides($map, $casesEntree, $direction);
echo $response;
?>
 