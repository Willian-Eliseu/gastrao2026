<?php

require "sql.php";
require "session.php";
require "configuracao.class.php";
require "fullTbread.class.php";
require "usuarios.class.php";

//error_reporting(E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);

#classe de configuração
$configuracao = new Configuracao($_SESSION[NOME_SESSAO]['tbread'], $_SESSION[NOME_SESSAO]['dataevento']);

#usuário
$instanceUsers = new Usuarios();
$instanceUsers->id = $_POST['userid'];
$instanceUsers->event = $_SESSION[NOME_SESSAO]['id'];

#verifica se o usuário é participante ou administrador
$usuario = $instanceUsers->getUserByIdEvent();
if($usuario == null || $usuario == ""){
    $usuario = $instanceUsers->getAdmByIdEvent();
}

$nome = $usuario['firstname']." ".$usuario['lastname'];
$crm = $usuario['crm'] ? $usuario['crm'] : "123VAZIO";

$tbreadid = isset($_POST['tbreadid']) ? $_POST['tbreadid'] : null;

$userid = $_POST['userid'];
$configTbread = new Tbread($_SESSION[NOME_SESSAO]['id']);
$configTbread->setParamtbread($tbreadid);
//$salas = $configTbread->getTbreadId();
$configTbread->setParametersChat($_SESSION[NOME_SESSAO]['css']);
$configTbread->setIdChat();
$urlChat = $configTbread->chatGenerator($nome);
$urlVideo = $configTbread->videoGenerator($nome, $usuario['email']);
$servidor = $configTbread->getServer();
$hashusuario = $usuario['control_hash'] ? $usuario['control_hash'] : "";
$fulltbread = $configTbread->consultaTBRead();

#presença do usuário - necessário configuração da tabela event_room_dates
$dataEvento = $configuracao->getEventDates($_SESSION[NOME_SESSAO]['id']);
$configuracao->setPresence($dataEvento, $usuario['id']);

$caracteres_sem_acento = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Â'=>'Z', 'Â'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Å'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'Å'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
    'Ä'=>'a', 'î'=>'i', 'â'=>'a', 'È'=>'s', 'È'=>'t', 'Ä'=>'A', 'Î'=>'I', 'Â'=>'A', 'È'=>'S', 'È'=>'T',
);

$retorno = json_encode([
    "server" => $servidor,
    "hash" => $hashusuario,
    "nome" => strtr($nome, $caracteres_sem_acento),
    "email" => $usuario['email'],
    "clienteid" => $fulltbread['clienteid'],
    "cliente" => $fulltbread['cliente'],
    "cidade" => $usuario['city'] ? $usuario['city'] : "ND",
    "estado" => $usuario['state'] ? $usuario['state'] : "ND",
    "stc" => $usuario['subscribe_training_center'],
    "crm" => $crm,
    "ip" => $_POST['ip'],
    "vivoplayer" => $fulltbread['vivo_player'],
    "vivo" => $fulltbread['vivo'],
    "urlVideo" => $urlVideo,
    "urlChat" => $urlChat
]);

echo $retorno;