<?php

class Tbread{
    private $eventId;
    private $tbreadId;
    private $parametersChat;
    private $idChat;
    private $paramtbread;
    
    public function __construct($param)
    {
        $this->eventId = $param;
        //consulta salas do tbread
        $consulta = sql([
            'statement'=>'SELECT e.tbread as id FROM tbrevent.event_room e WHERE e.`event` = ?',
            'types'=>'i',
            'parameters'=>[ $param ]
        ]);
        
        $this->tbreadId = $consulta != null ? $consulta : null;
    }

    public function getTbreadId()
    {
        return $this->tbreadId;
    }

    public function getFirst()
    {
        return $this->tbreadId[0]['id'];
    }

    public function setParamtbread($param=null)
    {
        $parametro = $param == null ? $this->getFirst() : $param;
        $consulta = sql([
            'statement'=>'SELECT * FROM tbread.cliente WHERE id = ?',
            'types'=>'i',
            'parameters'=>[ $parametro ],
            'only_first_row'=>'1'
        ]);

        if(isset($consulta)){
            $this->paramtbread = $consulta;
        }else{
            $this->paramtbread = "";
        }
    }

    public function getParameterschat()
    {
        return $this->parametersChat;
    }

    public function setParametersChat($parametersChat=null)
    {
        $param = $parametersChat == null ? "" : base64_encode($parametersChat);
        $this->parametersChat = $param;
        return $this;
    }

    public function getIdChat()
    {
        return $this->idChat;
    }

    public function setIdChat()
    {
        $pagina = trim($this->paramtbread['pagina']);
        $param = $pagina. '-' .date('Y-m-d');
        $this->idChat = $param;

        return $this;
    }

    public function setManualIdChat($pagina)
    {        
        $param = trim($pagina). '-' .date('Y-m-d');
        $this->idChat = $param;

        return $this;
    }

    public function chatGenerator($nome){
        $idchat = $this->idChat;
        $nomeusuario = $nome;
        $parameterschat = $this->parametersChat;

        $urlChat = "https://chat.tbrplay.com.br/$idchat?user.name=$nomeusuario&css=$parameterschat";
        return $urlChat;
    }

    public function getParamtbread($param=null)
    {
        if($param == null){
            return $this->paramtbread;
        }else{
            $consulta = sql([
                'statement'=>'SELECT * FROM tbread.cliente WHERE id = ?',
                'types'=>'i',
                'parameters'=>[ $param ],
                'only_first_row'=>'1'
            ]);
            return $consulta;
        }
    }

    public function timeToSec($time)
    {
        list($h, $m, $s) = explode(':', $time);
        return ($h * 3600) + ($m * 60) + $s;
    }

    public function buscaVideoLooping($tbreadid)
    {
        $busca = sql([
            'statement'=>'SELECT    count(*) AS qtde 
                            FROM    tbread.videos 
                            WHERE   cliente = ? 
                            AND     tipo = "loop"
                            AND     video_loop_ativo = "true" 
                            LIMIT   1',
            'types'=>'i',
            'parameters'=>[
                $tbreadid
            ],
            'only_first_row'=>'1'
        ]);

        return $busca['qtde'];
    }

    public function videoGenerator($nome, $usuarioEmail)
    {
        #parâmetros
        //$url = "//cdn.tbr.com.br/player/";
	    $url = "//player.tbr.srv.br/?type=hls&source=";
	    $id = $this->paramtbread['id'];
        $ct = $this->idChat;
        $server = $this->paramtbread['stream_transmissao'];
        $newbroadcast = $this->paramtbread['newbroadcast'];
        $nomeusuario = $nome;
        $dualAudio = $this->paramtbread['dual_audio'];
        $canalEsquerdo = $this->paramtbread['nome_canal_esquerdo'];
        $canalDireito = $this->paramtbread['nome_canal_direito'];
        $startRecord = $this->paramtbread['start_record'];
        $endRecord = $this->paramtbread['end_record'];

        #atribuições
	/*
        $parameters_player = "?session=" . $ct;
        $parameters_player .= "&server=" . $server;
        $parameters_player .= "&lowlatency=" . $newbroadcast;
        $parameters_player .= "&data=" . base64_encode(json_encode([
            'name' => $nomeusuario,
            'email' => $usuarioEmail
        ]));
        $parameters_player .= "&dual_audio=" . $dualAudio;
        $parameters_player .= "&audio=" . base64_encode(utf8_decode($canalEsquerdo . "|" . $canalDireito));
        $parameters_player .= "&language=" . base64_encode("pt-BR|en-US");
	*/
        $parameters_player = $url.$server;
        $video_loop_active = $this->buscaVideoLooping($id);

        if ($video_loop_active == 0) {
            $parameters_player .= "&startTime=" . $this->timeToSec($startRecord);
            $parameters_player .= "&endTime=" . $this->timeToSec($endRecord);
        }

        return $parameters_player;
    }

    public function videoGeneratorBaixa($nome, $usuarioEmail)
    {
        #parâmetros
        $url = "//cdn.tbr.com.br/player/";
	    //$url = "//player.tbr.srv.br/?type=hls&source=";
	    $id = $this->paramtbread['id'];
        $ct = $this->idChat;
        $server = $this->paramtbread['stream_transmissao'];
        $newbroadcast = $this->paramtbread['newbroadcast'];
        $nomeusuario = $nome;
        $dualAudio = $this->paramtbread['dual_audio'];
        $canalEsquerdo = $this->paramtbread['nome_canal_esquerdo'];
        $canalDireito = $this->paramtbread['nome_canal_direito'];
        $startRecord = $this->paramtbread['start_record'];
        $endRecord = $this->paramtbread['end_record'];

        #atribuições
        $parameters_player = "?session=" . $ct;
        $parameters_player .= "&server=" . $server;
        $parameters_player .= "&lowlatency=" . $newbroadcast;
        $parameters_player .= "&data=" . base64_encode(json_encode([
            'name' => $nomeusuario,
            'email' => $usuarioEmail
        ]));
        $parameters_player .= "&dual_audio=" . $dualAudio;
        $parameters_player .= "&audio=" . base64_encode(utf8_decode($canalEsquerdo . "|" . $canalDireito));
        $parameters_player .= "&language=" . base64_encode("pt-BR|en-US");
	
        $parameters_player = $url.$server;
        $video_loop_active = $this->buscaVideoLooping($id);

        if ($video_loop_active == 0) {
            $parameters_player .= "&startTime=" . $this->timeToSec($startRecord);
            $parameters_player .= "&endTime=" . $this->timeToSec($endRecord);
        }

        return $parameters_player;
    }

    public function ondemandVideoGenerator($newid)
    {
        $url = "//cdn.tbr.com.br/player/";
        $dualAudio = $this->paramtbread['dual_audio'];
        $canalEsquerdo = $this->paramtbread['nome_canal_esquerdo'];
        $canalDireito = $this->paramtbread['nome_canal_direito'];

        #atribuições
        $parameters_player = "?vod=1";
        $parameters_player .= "&video=".$newid;
        $parameters_player .= "&dual_audio=" . $dualAudio;
        $parameters_player .= "&audio=" . base64_encode(utf8_decode($canalEsquerdo . "|" . $canalDireito));
        $parameters_player .= "&language=" . base64_encode("pt-BR|en-US");

        return $url.$parameters_player;
    }

    public function getServer()
    {
        return $this->paramtbread['stream_transmissao'];
    }

    public function consultaTBRead(){
        $retorno = [
            "clienteid" => $this->paramtbread['id'],
            "cliente" => $this->paramtbread['nome'],
            "vivo_player" => $this->paramtbread['vivo_player'],
            "vivo" => $this->paramtbread['vivo']
        ];
        return $retorno;
    }
}