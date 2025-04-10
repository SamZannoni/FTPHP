
<?php
// Chemin vers le répertoire principal
$path = $_SERVER['DOCUMENT_ROOT'];
include_once($path);
// Chemin vers le sous-dossier folder
$folderPath = $path . '/folder';  // Met à jour ici pour pointer vers le sous-dossier 'folder'

// Fonction pour obtenir l'extension d'un fichier
function ext($file) {
    global $folderPath; // Accédez à la variable globale du chemin

    // Ajoutez le chemin absolu au fichier
    $fullPath = $folderPath . '/' . $file;

    if (is_dir($fullPath)) {
        return 'dir'; // Retourne 'dir' pour les dossiers
    } else {
        $ext = pathinfo($fullPath, PATHINFO_EXTENSION); // Récupère l'extension du fichier
        if ($ext === '') {
            return 'noext'; // Pour les fichiers sans extension
        }
        return strtolower($ext); // Retourne l'extension en minuscules
    }
}

// Ajoutez un débogage ici
foreach ($files as $file) {
    echo 'Fichier: ' . $file . ' - Extension: ' . ext($file) . '<br>';  // Débogage
    print '<a href="./folder/' . $file . '"><li class="' . ext($file) . '">' . $file . (!is_dir($file) ? ' <span>' . human_filesize($file) . '</span>' : '') . '</li></a>';
}


// Fonction pour obtenir le titre à partir de l'URL
function title() {
  $url = substr($_SERVER['REQUEST_URI'], 1);
  if (empty($url)) $url = 'home/';
  return $url;
}

// Fonction pour obtenir la taille humaine d'un fichier
function human_filesize($file) {
  $bytes = filesize($file);
  $decimals = 1;
  $factor = floor((strlen($bytes) - 1) / 3);
  if ($factor > 0) $sz = 'KMGT';
  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor - 1] . 'B';
}

// Obtenir la liste des fichiers dans le sous-dossier 'folder'
$files = scandir($folderPath); // Utilisez le chemin vers 'folder'

// Fichiers à exclure
$exclude = array('.', '..', '.DS_Store', 'index.php', '.git', '.gitmodules', '.gitignore', 'node_modules', 'header.php', 'footer.php', 'folder.png', '.woff', 'style.css', 'files.php', 'Banquise-Regular.woff');

// Filtrer les fichiers exclus
foreach ($exclude as $ex) {
  if (($key = array_search($ex, $files)) !== false) {
    unset($files[$key]);
  }
}
?>

<?php require_once $path . '/header.php'; ?> <!-- Inclus le fichier header.php -->

<script>
function goBack() {
  window.history.back();  // Utilise l'historique du navigateur pour revenir en arrière
}
</script>

<div class="container">

  <?php
  // Afficher le titre de la page
  print "<h1><a class='goback' onclick='goBack()' href='/files.php'>⟲</a> Index de " . str_replace('/', ' <span>/</span> ', title()) . "</h1>";

  // Si le tableau des fichiers n'est pas vide
  if (!empty($files)) {
    // Ouvrir la balise de liste non ordonnée
    print "<ul> <span id='info'>File name</span>";

    // Boucle à travers le tableau des fichiers
    foreach ($files as $file) {
      // Afficher chaque fichier
      print '<a href="./folder/' . $file . '"><li class="' . ext($file) . '">' . $file . (!is_dir($file) ? ' <span>' . human_filesize($file) . '</span>' : '') . '</li></a>';
    }

    // Fermer la balise de la liste non ordonnée
    print "</ul>";
  } else {
    // Afficher un message si le répertoire est vide
    print "<p>This folder contains no files.</p>";
  }
  ?>

</div>

<?php require_once $path . '/footer.php'; ?> <!-- Inclus le fichier footer.php -->
