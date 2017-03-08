<?php

/* visualizza l'elenco degli elementi di due array, ad esempio gli abiti contenuti in un  l'armadio e nella valigia,
e consente all'utente di selezionare cliccare su un abito dell'armadio spostandolo nella valigia.
al click su un abito la pagina viene ricaricata e mostra il contenuto dei due array aggiornato
la sessione è utilizzata per mantere il contenuto degli array tra le pagine
*/

// includi i file di configurazione e le funzioni
include "config/config.php";
include "functions/functions.php";

// avvia l'applicativo
$params= boot();

//include l' header
include "header.php";
//chiamo la funzione before()
before();


// controlla che cosa devi fare
switch ($params['action']) {
  case 'move':
    $msg=move($params['id']);
    break;
  case 'remove':
    remove($params['id']);
    break;
  default:
    echo "";
    break;
}


echo $msg;
display();
