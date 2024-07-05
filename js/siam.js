// ------------------------------------------------------------------------------------- Initialisation des variables --------------------------------------------------------------------------------------------
map = "";
var adversaire = "";
var pion_choisi = "";
var createur = "";
var joueurCo = "";
var tour;
var premierTour = 0;
var tourJoueur = false;
var reInit = false;
var inGame = false;
var ntour = 1;
var fini = false;

// ---------------------------------------------------------------------------------- Création de Partie (set variables) --------------------------------------------------------------------------------------------

// Fonction pour récupérer les informations de la partie avec l'ID spécifié
function recupererPartie() {
  var idPartie = document.getElementsByClassName("idPartie");
  idPartie = idPartie[0].textContent;
  var idPartieJSON = JSON.stringify(idPartie);

  var formData = new FormData();
  formData.append("idPartie", idPartieJSON);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../Jeu/recupererPartie.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      map = xhr.responseText;
      map = JSON.parse(map);
      pion_choisi = map.pion_choisi;
      adversaire = map.adversaire;
      tour = map.tour;
      premierTour = map.premierTour;
      createur = map.pseudo_utilisateur;
      map = map.partie;
      map = JSON.parse(map);
      setMap(map);
      setAdversaire(adversaire);
      setPionChoisi(pion_choisi);
      setCreateur(createur);
      setTour(tour);
      setPremierTour(premierTour, tour);
      joueurConnecter();
      init();
      estJoueur();
    }
  };
  xhr.send(formData);
}

function setMap(newMap) {
  map = newMap;
}

function setAdversaire(newAdversaire) {
  adversaire = newAdversaire;
  html = document.getElementById("adversaire");
  html.innerHTML = "Adversaire : " + adversaire;
}

function setCreateur(newCreateur) {
  createur = newCreateur;
  html = document.getElementById("createur");
  html.innerHTML = "Créateur de la partie : " + createur;
}

function setPionChoisi(newPionChoisi) {
  pion_choisi = newPionChoisi;
}

function setTour(newTour) {
  tour = newTour;
}

function setPremierTour(newPremierTour, tour) {
  premierTour = newPremierTour;
  if (premierTour == 1) {
    tour = pion_choisi == "elephant" ? 1 : 0;
  } else {
    setTour(tour);
  }
}

function joueurConnecter() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "../Jeu/nomUtilisateur.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      var joueur = xhr.responseText;
      joueur = JSON.parse(joueur);
      joueur = joueur.nomUtilisateur;
      setUtilisateur(joueur);
    }
  };
  xhr.send();
}

function setUtilisateur(joueur) {
  joueurCo = joueur;
  html = document.getElementById("joueurConnecte");
  html.innerHTML = "Joueur connecté: " + joueurCo;
}

// ------------------------------------------------------------------------------- Vérifie si c'est au joueur connecté de jouer ------------------------------------------------------------------------------------

function estJoueur() {
  var idPartie = document.getElementsByClassName("idPartie");
  idPartie = idPartie[0].textContent;
  var idPartieJSON = JSON.stringify(idPartie);
  var adversaireJSON = JSON.stringify(adversaire);

  var formData = new FormData();
  formData.append("idPartie", idPartieJSON);
  formData.append("adversaire", adversaireJSON);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../Jeu/verifTourJoueur.php", true);
  xhr.onload = function () {
    var pion_createur = xhr.responseText;
    pion_createur = JSON.parse(pion_createur);
    tour = pion_createur.tour;
    joueurCo = pion_createur.pseudo_utilisateur;
    pion_createur = pion_createur.pion_choisi;
    estTourJoueur(joueurCo, pion_createur, tour);
  };
  xhr.send(formData);
}

function estTourJoueur(joueurConnecte, pion_createur, tour) {
  if (tour == 1) {
    if ((pion_createur = "elephant")) {
      tourJoueur = joueurConnecte == createur ? true : false;
    } else {
      tourJoueur = joueurConnecte == adversaire ? true : false;
    }
  } else {
    if ((pion_createur = "rhinoceros")) {
      tourJoueur = joueurConnecte == adversaire ? true : false;
    } else {
      tourJoueur = joueurConnecte == createur ? true : false;
    }
  }
  placeNomTour();
  activeHover();
}

function placeNomTour() {
  var texte = document.getElementById("tour");
  if (tourJoueur) {
    texte.innerHTML = "C'est à vous de jouer !";
    if (tour == 1) {
      texte.innerHTML += " (éléphant)";
    } else {
      texte.innerHTML += " (rhinochéros)";
    }
  } else {
    texte.innerHTML = "C'est à votre adversaire de jouer !";
  }
}

function activeHover() {
  if (tourJoueur) {
    if (tour == 1) {
      var elephant = document.querySelectorAll(".elephant");
      elephant.forEach(function (caseElement) {
        caseElement.classList.add("elephant-hover");
      });
      var rhino = document.querySelectorAll(".rhino");
      rhino.forEach(function (caseElement) {
        caseElement.classList.remove("rhino-hover");
      });
    } else {
      var rhino = document.querySelectorAll(".rhino");
      rhino.forEach(function (caseElement) {
        caseElement.classList.add("rhino-hover");
      });
      var elephant = document.querySelectorAll(".elephant");
      elephant.forEach(function (caseElement) {
        caseElement.classList.remove("elephant-hover");
      });
    }
  } else {
    var elephant = document.querySelectorAll(".case");
    elephant.forEach(function (caseElement) {
      caseElement.classList.remove("elephant-hover");
      caseElement.classList.remove("rhino-hover");
    });
  }
}

// ---------------------------------------------------------------------------------- Initialisation du plateau de jeu --------------------------------------------------------------------------------------------

var init = function (reInit = false) {
  var cases = document.getElementsByClassName("case");

  if (reInit) {
    // Réinitialiser le jeu si nécessaire
    if (
      cases.length > 0 &&
      confirm("Êtes-vous sûrs de vouloir réinitialiser le jeu ?")
    ) {
      while (cases.length > 0) {
        cases[0].remove();
      }
      // Réinitialiser le plateau de jeu avec la disposition initiale
      map = [
        [
          [-1, 0],
          ["E", 2],
          ["E", 2],
          ["E", 2],
          ["E", 2],
          ["E", 2],
          [-1, 0],
        ],
        [
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
        ],
        [
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
        ],
        [
          ["V", 0],
          ["V", 0],
          ["O", 0],
          ["O", 0],
          ["O", 0],
          ["V", 0],
          ["V", 0],
        ],
        [
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
        ],
        [
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
          ["V", 0],
        ],
        [
          [-1, 0],
          ["R", 0],
          ["R", 0],
          ["R", 0],
          ["R", 0],
          ["R", 0],
          [-1, 0],
        ],
      ];
      // Réinitialiser d'autres variables de jeu
      premierCoup = true;
      ntour = 1;
      fini = false;
    } else {
      return false;
    }
    SauvegardeTableau(map);
  }

  // Création des cases du plateau de jeu
  var parentNode = document.getElementById("plateau");
  parentNode.innerHTML = "";
  for (var i = 0; i < map.length; i++) {
    for (var j = 0; j < map[i].length; j++) {
      var newNode = document.createElement("div");
      var top = 5 + 100 * i;
      var left = 5 + 100 * j;

      switch (map[i][j][0]) {
        case -1:
          newNode.className = "case";
          newNode.style.backgroundImage = "none";
          newNode.style.display = "none";
          newNode.style.top = top + "px";
          newNode.style.left = left + "px";
          break;
        case "E":
          newNode.className = "case pion elephant";
          newNode.onclick = function () {
            select(this);
          };
          newNode.style.backgroundImage =
            "url(../../img/1" + map[i][j][1] + ".gif)";
          newNode.style.top = top + "px";
          newNode.style.left = left + "px";
          newNode.setAttribute("onclick", "select(this)");
          break;
        case "R":
          newNode.className = "case pion rhino";
          newNode.onclick = function () {
            select(this);
          };
          newNode.style.backgroundImage =
            "url(../../img/2" + map[i][j][1] + ".gif)";
          newNode.style.top = top + "px";
          newNode.style.left = left + "px";
          newNode.setAttribute("onclick", "select(this)");
          break;
        case "O":
          newNode.className = "case pion rocher";
          newNode.onclick = function () {
            select(this);
          };
          newNode.style.backgroundImage = "url(../../img/rocher.gif)";
          newNode.style.top = top + "px";
          newNode.style.left = left + "px";
          newNode.setAttribute("onclick", "select(this)");
          break;
        default:
          newNode.className = "case";
          newNode.style.backgroundImage = "none";
          newNode.onclick = function () {
            select(this);
          };
          newNode.style.top = top + "px";
          newNode.style.left = left + "px";
          break;
      }
      newNode.setAttribute("coords", i + "," + j);
      parentNode.appendChild(newNode);
    }
  }
};

var stock = null;
var stockCase = null;

// ---------------------------------------------------------------------------------- Sauvegarde de plateau dans la bd --------------------------------------------------------------------------------------------

function SauvegardeTableau(map) {
  var idPartie = document.getElementsByClassName("idPartie");
  idPartie = idPartie[0].textContent;

  var tourJSON = JSON.stringify(tour);
  var mapJSON = JSON.stringify(map);
  var idPartieJSON = JSON.stringify(idPartie);
  var adversaireJSON = JSON.stringify(adversaire);

  var formData = new FormData();
  formData.append("map", mapJSON);
  formData.append("idPartie", idPartieJSON);
  formData.append("adversaire", adversaireJSON);
  formData.append("tour", tourJSON);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../Jeu/sauvegarderPartie.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      console.log(xhr.responseText);
    }
  };
  xhr.send(formData);
}

// ---------------------------------------------------------------------------------------- Fonctionnement du jeu -------------------------------------------------------------------------------------------------

function cancelSelect(annule = false) {
  if (annule) {
    stock = null;
    document.getElementById("annuler").disabled = true;
    document.getElementById("demiTour").disabled = true;
    document.getElementById("quartTourGauche").disabled = true;
    document.getElementById("quartTourDroite").disabled = true;
  }
  var cases = document.getElementsByTagName("div");
  for (var i = 0; i < cases.length; i++) {
    if (cases[i].classList.contains("selection")) {
      cases[i].classList.remove("selection");
    }
    if (cases[i].classList.contains("cliquable")) {
      cases[i].classList.remove("cliquable");
    }
    if (cases[i].classList.contains("sortieCliquable")) {
      cases[i].classList.remove("sortieCliquable");
    }
  }
}

function estPlateau(x, y) {
  if (x >= 1 && x <= 5 && y >= 1 && y <= 5) {
    return true;
  }
  return false;
}

function estCase(x, y) {
  if (x >= 0 && x <= 6 && y >= 0 && y <= 6) {
    if (map[x][y] == 0) {
      return true;
    }
  }
  return false;
}

function estSortie(x, y) {
  if (x == 0 || x == 6 || y == 0 || y == 6) {
    return true;
  }
  return false;
}

function estRocher(x, y) {
  if (map[x][y][0] == "O") {
    return true;
  }

  return false;
}

function estPion(x, y) {
  test = map[x][y][0];
  if (test == "E" || test == "R" || test == "O") {
    return true;
  }
  return false;
}

function quartTourDroite() {
  cancelSelect();
  if (map[stock[0]][stock[1]][1] == 3) {
    map[stock[0]][stock[1]][1] = 0;
  } else {
    map[stock[0]][stock[1]][1]++;
  }

  if (map[stock[0]][stock[1]][0] == "E") {
    stockCase.style.backgroundImage =
      "url(../../img/1" + map[stock[0]][stock[1]][1] + ".gif)";
  } else {
    stockCase.style.backgroundImage =
      "url(../../img/2" + map[stock[0]][stock[1]][1] + ".gif)";
  }

  if (!estSortie(stock[0], stock[1])) {
    passeTour();
  } else {
    rentrePion();
  }
}

function quartTourGauche() {
  cancelSelect();
  if (map[stock[0]][stock[1]][1] == 0) {
    map[stock[0]][stock[1]][1] = 3;
  } else {
    map[stock[0]][stock[1]][1]--;
  }

  if (map[stock[0]][stock[1]][0] == "E") {
    stockCase.style.backgroundImage =
      "url(../../img/1" + map[stock[0]][stock[1]][1] + ".gif)";
  } else {
    stockCase.style.backgroundImage =
      "url(../../img/2" + map[stock[0]][stock[1]][1] + ".gif)";
  }

  if (!estSortie(stock[0], stock[1])) {
    passeTour();
  } else {
    rentrePion();
  }
}

function demiTour() {
  cancelSelect();
  if (map[stock[0]][stock[1]][1] == 3) {
    map[stock[0]][stock[1]][1] = 1;
  } else if (map[stock[0]][stock[1]][1] == 2) {
    map[stock[0]][stock[1]][1] = 0;
  } else {
    map[stock[0]][stock[1]][1] = map[stock[0]][stock[1]][1] + 2;
  }

  if (map[stock[0]][stock[1]][0] == "E") {
    stockCase.style.backgroundImage =
      "url(../../img/1" + map[stock[0]][stock[1]][1] + ".gif)";
  } else {
    stockCase.style.backgroundImage =
      "url(../../img/2" + map[stock[0]][stock[1]][1] + ".gif)";
  }

  if (!estSortie(stock[0], stock[1])) {
    passeTour();
  } else {
    rentrePion();
  }
}

function deplacement(x, y) {
  var tmp = map[x][y];
  map[x][y] = map[stock[0]][stock[1]];
  map[stock[0]][stock[1]] = tmp;

  stock[0] = x;
  stock[1] = y;

  init();
  passeTour();
}

function placePionSortie() {
  // Pour la sortie d'un pion lors d'une poussé;
  for (var i = 1; i < 6; i++) {
    if (map[0][i] > 0 && map[0][i] <= 5) {
      map[0][i][1] = 2;
    }
    if (map[6][i] > 5 && map[6][i] <= 10) {
      map[6][i][1] = 0;
    }
  }
}

function rentrePion() {
  var casesEntree = [
    "1,1",
    "1,2",
    "1,3",
    "1,4",
    "1,5",
    "2,1",
    "2,5",
    "3,1",
    "3,5",
    "4,1",
    "4,5",
    "5,1",
    "5,2",
    "5,3",
    "5,4",
    "5,5",
  ];

  var mapJSON = JSON.stringify(map);
  var casesEntreeJSON = JSON.stringify(casesEntree);
  var direction = map[stock[0]][stock[1]][1];
  var directionJSON = JSON.stringify(direction);

  var formData = new FormData();
  formData.append("map", mapJSON);
  formData.append("casesEntree", casesEntreeJSON);
  formData.append("direction", directionJSON);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../Jeu/CasesValidesBord.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      // Accéder au tableau de cases valides
      var casesValides = response.casesValides;
      // Convertir le tableau en une chaîne JSON

      for (var i = 0; i < casesValides.length; i++) {
        var cases = document.getElementsByTagName("div");
        var j = 0;
        while (cases[j].getAttribute("coords") != casesValides[i]) {
          j++;
        }
        cases[j].classList.add("cliquable");
      }
    }
  };
  xhr.send(formData);
}

function deplacementValide(x, y) {
  var verifCase = [x, y];

  var mapJSON = JSON.stringify(map);
  var verifCaseJSON = JSON.stringify(verifCase);

  var formData = new FormData();
  formData.append("map", mapJSON);
  formData.append("verifCase", verifCaseJSON);

  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../Jeu/CasesValides.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
      // Accéder au tableau de cases valides
      var casesValides = response.casesValides;
      // Convertir le tableau en une chaîne JSON

      for (var i = 0; i < casesValides.length; i++) {
        var cases = document.getElementsByTagName("div");
        var j = 0;
        while (cases[j].getAttribute("coords") != casesValides[i]) {
          j++;
        }
        if (
          casesValides[i][0] == 6 ||
          casesValides[i][0] == 0 ||
          casesValides[i][1] == 6 ||
          casesValides[i][1] == 0
        ) {
          cases[j].classList.add("sortieCliquable");
        } else {
          cases[j].classList.add("cliquable");
        }
      }
    }
  };
  xhr.send(formData);
}

function passeTour() {
  if (tour == 0) {
    tour = 1;
  } else {
    tour = 0;
  }
  ntour++;
  document.getElementById("passeTour").disabled = true;
  cancelSelect(true);
  init();
  SauvegardeTableau(map);
  estJoueur();
}

function pousser(x, y) {
  var pion;
  if (map[stock[0]][stock[1]][0] == "E") {
    pion = "elephants";
  } else {
    pion = "rhinocéros";
  }

  var direction = null;
  if (map[stock[0]][stock[1]][1] == 2) {
    direction = [1, 0];
  } else if (map[stock[0]][stock[1]][1] == 0) {
    direction = [-1, 0];
  } else if (map[stock[0]][stock[1]][1] == 1) {
    direction = [0, 1];
  } else {
    direction = [0, -1];
  }

  while (estPion(x, y)) {
    var tmp = map[x][y];
    map[x][y] = map[stock[0]][stock[1]];
    map[stock[0]][stock[1]] = tmp;

    x += direction[0];
    y += direction[1];
  }
  var tmp = map[x][y];
  map[x][y] = map[stock[0]][stock[1]];
  map[stock[0]][stock[1]] = tmp;

  if (estSortie(x, y) && estRocher(x, y)) {
    confirm("Les " + pion + " ont gagné !");
    fini = true;
  }
  if (estSortie(x, y) && (map[x][y][0] == "E" || map[x][y][0] == "R")) {
    sortie(x, y, true);
  }

  placePionSortie();

  init();
  SauvegardeTableau(map);
  passeTour();
}

function sortie(x, y, pousse = false) {
  if (!pousse) {
    x = stock[0];
    y = stock[1];
  }
  var i = 1;
  if (map[x][y][0] == "E") {
    while (map[0][i][0] != "V") {
      i++;
      if (i == 6) {
        break;
      }
    }
    var tmp = map[0][i];
    map[0][i] = map[x][y];
    map[x][y] = tmp;
    map[0][i][1] = 2;
  }

  if (map[x][y][0] == "R") {
    while (map[6][i][0] != "V") {
      i++;
      if (i == 6) {
        break;
      }
    }
    var tmp = map[6][i];
    map[6][i] = map[x][y];
    map[x][y] = tmp;
    map[6][i][1] = 0;
  }
  if (!pousse) {
    passeTour();
  }
  init();
  SauvegardeTableau(map);
}

function select(element) {
  if (!fini) {
    if (tourJoueur) {
      if (
        stock == null &&
        ((element.classList.contains("rhino") && tour == 0) ||
          (element.classList.contains("elephant") && tour == 1)) &&
        !element.classList.contains("rocher")
      ) {
        document.getElementById("annuler").disabled = false;
        element.classList.add("selection");
        posX = parseInt(element.getAttribute("coords").slice(0, 1));
        posY = parseInt(element.getAttribute("coords").slice(2, 3));
        stock = [posX, posY];
        stockCase = element;
        if (estSortie(posX, posY)) {
          rentrePion();
        } else {
          deplacementValide(posX, posY);
        }

        document.getElementById("demiTour").disabled = false;
        document.getElementById("quartTourGauche").disabled = false;
        document.getElementById("quartTourDroite").disabled = false;
      } else if (stock != null) {
        var elementX = parseInt(element.getAttribute("coords").slice(0, 1));
        var elementY = parseInt(element.getAttribute("coords").slice(2, 3));
        if (stock[0] == elementX && stock[1] == elementY) {
          cancelSelect(true);
        } else {
          if (estPlateau(elementX, elementY)) {
            if (
              !estPion(elementX, elementY) &&
              element.classList.contains("cliquable")
            ) {
              deplacement(elementX, elementY);
            } else if (
              element.classList.contains("cliquable") &&
              estPion(elementX, elementY)
            ) {
              pousser(elementX, elementY);
            }
          } else {
            if (element.classList.contains("sortieCliquable")) {
              sortie(elementX, elementY);
            }
          }
        }
      }
    }
  }
}

// ---------------------------------------------------------------------------------- Actualisation de la page --------------------------------------------------------------------------------------------

// Fonction pour vérifier les mises à jour périodiquement
function verifierMisesAJour() {
  var idPartie = document.getElementsByClassName("idPartie");
  idPartie = idPartie[0].textContent;
  var idPartieJSON = JSON.stringify(idPartie);

  var formData = new FormData();
  formData.append("idPartie", idPartieJSON);
  // Envoi d'une requête au serveur pour vérifier les mises à jour
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "../Jeu/verifier_mises_a_jour.php", true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      mapdb = xhr.responseText;
      mapdb = JSON.parse(mapdb);
      mapdb = mapdb.partie;
      mapdb = JSON.parse(mapdb);
      if (!tourJoueur) {
        if (!testMap(mapdb)) {
          // S'il y a eu des mises à jour, rafraîchir la page de l'adversaire
          window.location.reload(true); // Rechargement de la page en forçant le cache
        }
      }
    }
  };
  xhr.send(formData);
}

function testMap(mapdb) {
  for (var i = 0; i < 7; i++) {
    for (var j = 0; j < 7; j++) {
      if (map[i][j][1] != mapdb[i][j][1]) {
        return false;
      }
      if (map[i][j][0] != mapdb[i][j][0]) {
        return false;
      }
    }
  }
  return true;
}

recupererPartie();
// Vérifier les mises à jour toutes les 5 secondes (5000 millisecondes)
setInterval(verifierMisesAJour, 4000);

window.onload = recupererPartie();
