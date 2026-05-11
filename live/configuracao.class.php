<?php
class Configuracao
{
    private $vivo;
    private $ondemand;

    public function __construct($evento, $data)
    {
        $busca = sql([
            'statement' => 'SELECT vivo FROM tbread.cliente WHERE id = ?',
            'types' => 'i',
            'parameters' => [
                $evento
            ],
            'only_first_row' => '1'
        ]);

        if ($busca['vivo'] == 1) {
            $this->vivo = "s";
        } else if ($busca['vivo'] == 0 && date('y-m-d') >= date($data)) {
            $this->ondemand = "s";
        }
    }

    public static function getCertificado($evento, $tipo)
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.certificate_config WHERE `event` = ? AND `type` = ?',
            'types' => 'is',
            'parameters' => [
                $evento,
                $tipo
            ],
            'only_first_row' => '1'
        ]);

        return $consulta;
    }

    public function getVivo()
    {
        return $this->vivo;
    }

    public function getOndemand()
    {
        return $this->ondemand;
    }

    public function getPage($page)
    {
        return Configuracao::pageVerify($page) == 1 ? 1 : 0;
    }

    public static function pageVerify($page)
    {
        $filename = "./pages/$page.php";

        if (file_exists($filename)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getAccessToPage($sessao = null)
    {
        if ($sessao['usuario']['firstname'] != "") {
            return 1;
        } else {
            return 0;
        }
    }

    public function getAccessToPagePayment($sessao = null)
    {
        if ($sessao['usuario']['firstname'] != "" && $sessao['usuario']['enable'] == 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function sponsorsSearch()
    {
        $vetor = [];
        $path = "./assets/img";
        $diretorio = dir($path);

        $i = 0;
        while ($arquivo = $diretorio->read()) {
            $verifica = strpos($arquivo, "ponsor_");
            if ($verifica === 1) {
                $vetor[$i]['imagem'] = $arquivo;
                $i++;
            }
        }
        $diretorio->close();
        return $vetor;
    }

    public function sponsorsSearchFolder()
    {
        $vetor = [];
        $path = "./assets/img/sponsors";
        $diretorio = dir($path);

        $i = 0;
        while ($arquivo = $diretorio->read()) {
            $verifica = strpos($arquivo, "ponsor_");
            if ($verifica === 1) {
                $vetor[$i]['imagem'] = $arquivo;
                $i++;
            }
        }
        $diretorio->close();
        return $vetor;
    }

    public function doacao_consulta($sessao)
    {
        $busca = sql([
            'statement' => 'SELECT control_hash FROM tbrevent.registration WHERE id = ?',
            'types' => 'i',
            'parameters' => [
                $sessao['usuario']['id']
            ],
            'only_first_row' => '1'
        ]);

        return $busca;
    }

    public function login_consulta($sessao)
    {
        $busca = sql([
            'statement' => 'SELECT cadastro FROM tbread.cliente WHERE id = ?',
            'types' => 'i',
            'parameters' => [$sessao['tbread']],
            'only_first_row' => '1'
        ]);

        return $busca['cadastro'];
    }

    public function stcSearch($evento, $excessao = null)
    {
        if ($excessao != null) {
            $busca = sql([
                'statement' => 'SELECT * FROM tbrevent.subscribe_training_center WHERE `event` = ? AND id NOT IN(?) ORDER BY `name`',
                'types' => 'ii',
                'parameters' => [
                    $evento,
                    $excessao
                ]
            ]);
        } else {
            $busca = sql([
                'statement' => 'SELECT * FROM tbrevent.subscribe_training_center WHERE `event` = ? ORDER BY `name`',
                'types' => 'i',
                'parameters' => [
                    $evento
                ]
            ]);
        }

        return $busca;
    }

    public function stcValueSearch($idStc)
    {
        $busca = sql([
            'statement' => 'SELECT `value` FROM tbrevent.subscribe_training_center WHERE `id` = ?',
            'types' => 'i',
            'parameters' => [
                $idStc
            ],
            'only_first_row' => '1'
        ]);

        return $busca['value'];
    }

    public static function stcByValue($evento, $value = null)
    {
        if($value == null){
            #gratuito
            $busca = sql([
                'statement' => 'SELECT * FROM tbrevent.subscribe_training_center WHERE `event` = ? AND `value` = 0',
                'types' => 'i',
                'parameters' => [
                    $evento
                ]
            ]);
        }else{
            $busca = sql([
                'statement' => 'SELECT * FROM tbrevent.subscribe_training_center WHERE `event` = ? AND `value` != 0',
                'types' => 'i',
                'parameters' => [
                    $evento
                ]
            ]);
        }
        return $busca;
    }

    public function subscribeData($evento)
    {
        $busca = sql([
            'statement' => 'SELECT fields FROM tbrevent.registration_fields WHERE `event` = ?',
            'types' => 'i',
            'parameters' => [
                $evento
            ],
            'only_first_row' => '1'
        ]);

        return $busca['fields'];
    }

    public function getEventDates($eventId)
    {
        $eventDates = sql([
            "statement" => "SELECT * FROM tbrevent.event_rooms_dates WHERE `event` = ? GROUP BY `date` ORDER BY `date` ASC",
            "types" => "i",
            "parameters" => [
                $eventId
            ]
        ]);

        return $eventDates;
    }

    public function setPresence($eveDats, $userId)
    {
        $c = 0;
        $today = date('Y-m-d');
        foreach ($eveDats as $k => $v) {
            if ($today === $v['date']) {
                $c++;
                $controle = $k + 1;
                //update no banco
                if ($controle == 1) {
                    $presenceDay = "presence";
                } else if ($controle == 2) {
                    $presenceDay = "presence_second_day";
                } else if ($controle == 3) {
                    $presenceDay = "presence_third_day";
                } else if ($controle == 4) {
                    $presenceDay = "presence_fourth_day";
                } else if ($controle == 5) {
                    $presenceDay = "presence_fifth_day";
                } else if ($controle == 6) {
                    $presenceDay = "presence_sixth_day";
                }

                sql([
                    "statement" => "UPDATE tbrevent.registration SET $presenceDay = 'S' WHERE `id` = ?",
                    "types" => "i",
                    "parameters" => [
                        $userId
                    ]
                ]);
            }
        }
    }

    public function getIpTest()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://www.iplocate.io/api/lookup/" . $_SERVER['REMOTE_ADDR'] . "/json?apikey=85328b4cfa0299a701f85029bd371043&callback=");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "X-API-Key: 85328b4cfa0299a701f85029bd371043"
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);
        return $response['country'];
    }
}
