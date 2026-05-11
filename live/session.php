<?php
ini_set('session.gc_maxlifetime', 5400);
session_set_cookie_params(5400);
define("NOME_SESSAO", "session398");
session_name(NOME_SESSAO);
session_start();

$_SESSION[NOME_SESSAO]['id'] = 398;
$_SESSION[NOME_SESSAO]['tbread'] = 1039;
$_SESSION[NOME_SESSAO]['dataevento'] = "2026-05-24";
$_SESSION[NOME_SESSAO]['dataEncerramento'] = '2027-05-24';//data onde o evento deixa de estar disponível para ser acessado
$_SESSION[NOME_SESSAO]['pagina'] = 'gastrao2026';
$_SESSION[NOME_SESSAO]['css'] = "dtec.tbr.com.br/live/chat.css";

//css
$_SESSION[NOME_SESSAO]['bootstrapcss'] = '//eventos.tbr.com.br/global/assets/bootstrap5/css/bootstrap.min.css';
$_SESSION[NOME_SESSAO]['fontawesome'] = '//eventos.tbr.com.br/global/assets/fontawesome62/css/all.min.css';
$_SESSION[NOME_SESSAO]['sweetalertcss'] = '//eventos.tbr.com.br/global/assets/sweetalert2/dist/sweetalert2.min.css';
$_SESSION[NOME_SESSAO]['intltelinputcss'] = '//eventos.tbr.com.br/global/assets/intltelinput/build/css/intlTelInput.css';

//js
$_SESSION[NOME_SESSAO]['bootstrapjs'] = '//eventos.tbr.com.br/global/assets/bootstrap5/js/bootstrap.bundle.min.js';
$_SESSION[NOME_SESSAO]['sweetalertjs'] = '//eventos.tbr.com.br/global/assets/sweetalert2/dist/sweetalert2.min.js';
$_SESSION[NOME_SESSAO]['jquery'] = '//eventos.tbr.com.br/global/assets/jquery/jquery361.js';
$_SESSION[NOME_SESSAO]['mask'] = '//eventos.tbr.com.br/global/assets/mask/mask.min.js';
$_SESSION[NOME_SESSAO]['intltelinputjs'] = '//eventos.tbr.com.br/global/assets/intltelinput/build/js/intlTelInput.min.js';

?>