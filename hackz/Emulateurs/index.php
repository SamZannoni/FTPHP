<?php
   $path = $_SERVER['DOCUMENT_ROOT'];
   include_once($path);
?>

<?php
// Fonction pour obtenir l'extension du fichier (type)
function ext( $file ) {
  if ( is_dir( $file ) ) {
    return 'dir'; // Si c'est un dossier, retourne 'dir'
  } else {
    // Retourne l'extension du fichier, remplace '7z' par 'sevenz' pour les archives
    return str_replace( '7z', 'sevenz', strtolower( pathinfo( $file )['extension'] ) );
  }
}

// Fonction pour obtenir le titre, depuis l'URL
function title() {
  $url = substr( $_SERVER['REQUEST_URI'], 1 );
  if ( empty( $url ) ) $url = 'home/';
  return $url;
}

// Fonction pour obtenir la taille lisible du fichier
function human_filesize( $file ) {
  // Vérifie si le fichier existe
  if (!file_exists($file)) {
    return 'N/A';  // Si le fichier n'existe pas
  }

  $bytes = filesize( $file ); // Récupère la taille en octets
  if ($bytes === 0) {
    return '0B';  // Si le fichier est vide
  }

  $decimals = 1;
  $factor = floor( ( strlen($bytes) - 1 ) / 3 );
  if ( $factor > 0 ) $sz = 'KMGT';  // Unité en Ko, Mo, Go, To

  // Retourne la taille lisible en format humain (par exemple 1.5MB)
  return sprintf( "%.{$decimals}f", $bytes / pow( 1024, $factor ) ) . @$sz[$factor - 1] . 'B';
}

// Récupère la liste des fichiers dans le répertoire actuel
$files = scandir( '.' );

// Fichiers à exclure
$exclude = array( '.', '..', '.DS_Store', 'index.php', '.git', '.gitmodules', '.gitignore', 'node_modules', 'header.php', 'footer.php',  'folder.png', '.woff', 'style.css', 'files.php', 'Banquise-Regular.woff' );

// Supprime les fichiers exclus du tableau
foreach ( $exclude as $ex ) {
  if ( ( $key = array_search( $ex, $files ) ) !== false ) {
    unset( $files[$key] );
  }
}
?>

<?php require_once $path . '/header.php'; ?>
<script>
function goBack() {
    window.history.back();  // Utilise l'historique du navigateur pour revenir en arrière
}
</script>

<div class="container">

  <?php
  // Afficher le titre du répertoire
  print "<h1><a class='goback' onclick='goBack()' href='/files.php'>⟲</a> Index de " . str_replace( '/', ' <span>/</span> ', title() ) . "</h1>";

  // Si le tableau des fichiers n'est pas vide
  if ( !empty( $files ) ) {
    // Ouvrir la balise de la liste non ordonnée
    print "<ul><span id='info'>File name</span>";

    // Boucle à travers le tableau des fichiers
    foreach ( $files as $file ) {
      $fullPath = './' . $file; // Définir le chemin complet du fichier

      // Afficher chaque fichier avec sa taille, si ce n'est pas un répertoire
      print '<a href="' . $fullPath . '"><li class="' . ext( $file ) . '">' . $file . ( !is_dir( $file ) ? ' <span>' . human_filesize( $fullPath ) . '</span>' : '' ) . '</li></a>';
    }

    // Fermer la balise de la liste non ordonnée
    print "</ul>";
  } else {
    // Afficher un message si le répertoire est vide
    print "<p>This folder contains no files.</p>";
  }
  ?>

</div>

<?php require_once $path . '/footer.php'; ?>
