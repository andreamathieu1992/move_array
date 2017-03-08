<?php



function boot()
{
         global $config;


	session_start();

	if( !isset($_SESSION['armadio'])){

		$_SESSION['armadio'] = create_armadio();

	}

	if( !isset($_SESSION['valigia'])){

		$_SESSION['valigia'] = create_valigia() ;

	}



  // riempi la valigia parse input
  $action =(isset($_GET['action']) && ($_GET['action']=="move" OR $_GET['action']=="remove")) ? $_GET['action'] : "";
  $id=isset($_GET['id']) ? trim($_GET['id']) : "-1";

 return (["action"=>$action, "id"=> $id]);

}



function move($id)
{

// sposta l'elemento identificato dall'indice $id dall'array sorgente al destinatario

$src=$_SESSION['armadio'];
$dest=$_SESSION['valigia'];

//elementi massimi nella valigia
//$max=get_max();

$max_vol=get_max_vol();

if(is_valigia_full($id,$dest,$max_vol)){

// if (get_size($dest)>=$max ){ ------>vecchio controllo
   return("la valigia è piena!");
 }
// controlla che ci sia l'elemento origine
if (isset($src[$id])){

if(!isset($dest[$id])){
  $dest[$id]=1;
}else {
  $dest[$id]++;
}
$src[$id]--;
if ($src[$id]==0) {
  unset($src[$id]);
}

}

$_SESSION['armadio']= $src ;
$_SESSION['valigia'] = $dest;

}


//rimuove da valigia e punta ad armadio
function remove($id)
{


// sposta l'elemento identificato dall'indice $id dall'array sorgente al destinatario

$src=$_SESSION['valigia'];
$dest=$_SESSION['armadio'];

// controlla che ci sia l'elemento origine
if (isset($src[$id])){

if(!isset($dest[$id])){
  $dest[$id]=1;
}else {
  $dest[$id]++;
}
$src[$id]--;
}
if ($src[$id]==0) {
  unset($src[$id]);
}

$_SESSION['valigia']= $src ;
$_SESSION['armadio'] = $dest;

}

function display()
{
$abiti=get_abiti();

	echo "<br>armadio";
	$data=$_SESSION['armadio'];

	echo "<ul>";
	foreach($data as $abito=>$qta){

		echo "<li>" . $abiti[$abito]['nome'] . " $qta <a href=\"?action=move&id=$abito\">Sposta giù</a> </li>";

	}

	echo "</ul>";


	echo "valigia";
	$data= $_SESSION['valigia'];

	echo "<ul>";

	foreach($data as$abito=>$qta){

		echo "<li>" . $abiti[$abito]['nome'] . " $qta <a href=\"?action=remove&id=$abito&out=1\">Sposta su</li>";

	}
	echo "</ul>";
}

function create_valigia()
{
global $config;

return $config['valigia'];


}

function create_armadio()
{
global $config;

return $config['armadio'];


}


function debug()
{

echo "<pre>";
print_r($_SESSION);


}

function before(){
//se è settata la var reset, resetta la sessione e la fa ripartire
if(isset($_GET['reset'])){
  session_destroy();
  boot();
}

}
function get_max(){
global $config;
return $config['max'];

}
function get_size($data){
  $tot=0;
  foreach ($data as $qta) {
    $tot+=$qta;
  }
  return $tot;
}
function get_abiti(){
  global $config;
  return $config['abiti'];
}
function get_max_vol(){
  global $config;
  return $config['max_vol'];
}
function is_valigia_full($abito,$valigia,$max_vol){
  //somma i volumi di tutti gli abiti nella valigia e il nuovo abito da inserire
  // e confronta il totale con il valore di max_vol

  /*Controlla che tutto funzioni:
    print_r($abito);
    print_r($valigia);
    return 0; ----->sempre true
    die;*/
  $abiti=get_abiti();
  $tot_vol=$abiti[$abito]['volume'];
  foreach ($valigia as $key => $value) {
    $tot_vol +=$abiti[$abito]['volume'] * $value;
  }
  if($tot_vol >$max_vol){
    return 1;
  }
  return 0;
}
