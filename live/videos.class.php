<?php
class Videos
{
    public static function getVideosPrimeiro($tbreadid, $ordem)
    {
        $busca =  sql([
            'statement' => 'SELECT v.id, v.titulo, v.modulo, v.nomemodulo, v.ordem, v.new_id, v.sinopse
            FROM tbread.videos v
            WHERE v.cliente = ?
            AND v.publicar = ?
            AND v.ordem >= ?
            ORDER BY v.ordem',
            'types' => 'isi',
            'parameters' => [
                $tbreadid,
                "sim",
                $ordem
            ]
        ]);
        return $busca;
    }

    public static function getVideosAnt($tbreadid, $ordem)
    {
        $busca = sql([
            'statement' => 'SELECT v.id, v.titulo, v.modulo, v.nomemodulo, v.ordem, v.new_id, v.sinopse
                FROM tbread.videos v
                WHERE v.cliente = ?
                AND v.publicar = ?
                AND v.ordem >= ?
                ORDER BY v.ordem',
            'types' => 'isi',
            'parameters' => [
                $tbreadid,
                "sim",
                $ordem
            ]
        ]);

        return $busca;
    }

    public static function getVideosPos($tbreadid, $ordem)
    {
        $busca = sql([
            'statement' => 'SELECT v.id, v.titulo, v.modulo, v.nomemodulo, v.ordem, v.new_id, v.sinopse
                FROM tbread.videos v
                WHERE v.cliente = ?
                AND v.publicar = ?
                AND v.ordem < ?
                ORDER BY v.ordem',
            'types' => 'isi',
            'parameters' => [
                $tbreadid,
                "sim",
                $ordem
            ]
        ]);

        return $busca;
    }

    public static function getAnexo($tbreadid, $id)
    {
        $busca = sql([
            'statement' => 'SELECT * FROM tbread.anexos a WHERE a.cliente = ? AND a.id_pai = ? AND publicar = "sim" AND download = "sim" ORDER BY a.ordem',
            'types' => 'ii',
            'parameters' => [
                $tbreadid, $id
            ]
        ]);

        return $busca;
    }

    public static function getConsulta($id, $newid)
    {
        $busca = sql([
            'statement' => 'SELECT v.id, v.titulo, v.modulo, v.nomemodulo, v.ordem, v.new_id, v.sinopse
                FROM tbread.videos v
                WHERE v.id = ?
                AND v.new_id = ?',
            'types' => 'is',
            'parameters' => [
                $id, $newid
            ],
            'only_first_row' => '1'
        ]);

        return $busca;
    }

    public static function filtraArray($var, $pesquisa)
    {
        if (substr_count(strtolower(Videos::cleanString($var['titulo'])), strtolower(Videos::cleanString($pesquisa))) > 0) {
            return 1;
        } else if (substr_count(strtolower(Videos::cleanString($var['sinopse'])), strtolower(Videos::cleanString($pesquisa))) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function cleanString($text)
    {
        $utf8 = array(
            '/[áàâãªä]/u'   =>   'a',
            '/[ÁÀÂÃÄ]/u'    =>   'A',
            '/[ÍÌÎÏ]/u'     =>   'I',
            '/[íìîï]/u'     =>   'i',
            '/[éèêë]/u'     =>   'e',
            '/[ÉÈÊË]/u'     =>   'E',
            '/[óòôõºö]/u'   =>   'o',
            '/[ÓÒÔÕÖ]/u'    =>   'O',
            '/[úùûü]/u'     =>   'u',
            '/[ÚÙÛÜ]/u'     =>   'U',
            '/ç/'           =>   'c',
            '/Ç/'           =>   'C',
            '/ñ/'           =>   'n',
            '/Ñ/'           =>   'N',
            '/–/'           =>   '-',
            '/[’‘‹›‚]/u'    =>   ' ',
            '/[“”«»„]/u'    =>   ' ',
            '/ /'           =>   ' ',
        );
        return preg_replace(array_keys($utf8), array_values($utf8), $text);
    }

    public static function getPesquisa($tbreadid, $pesquisa)
    {
        $busca = sql([
            'statement' => 'SELECT v.id, v.titulo, v.modulo, COALESCE(v.nomemodulo, "Módulo") AS nomemodulo, v.ordem, v.new_id, v.sinopse
                FROM        tbread.videos v
                WHERE       v.cliente = ?
                AND         v.publicar = ?
                ORDER BY    v.ordem',
            'types' => 'is',
            'parameters' => [$tbreadid, "sim"]
        ]);

        $videos = [];
        if (is_array($busca) && count($busca) > 0) {
            foreach ($busca as $k => $v) {
                if (Videos::filtraArray($v, $pesquisa) > 0) {
                    array_push($videos, $v);
                }
            }
        }

        return $videos;
    }

    public static function setNewAccess($dataagora, $event, $registration, $page)
    {
        sql([
            'statement' => 'INSERT INTO tbrevent.access SET `event` = ?, registration = ?, `page` = ?, category = ?, `login` = ?',
            'types' => 'iisss',
            'parameters' => [
                $event,
                $registration,
                $page,
                "0",
                $dataagora
            ]
        ]);
    }

    public static function accessClose($dataagora, $event, $registration)
    {
        sql([
            'statement' => 'UPDATE tbrevent.access SET logout = ? WHERE event = ? AND registration = ? AND logout IS NULL',
            'types' => 'sii',
            'parameters' => [
                $dataagora,
                $event,
                $registration
            ]
        ]);
    }

    public static function getVideos($tbreadid)
    {
        $busca = sql([
            'statement' => 'SELECT v.id, v.titulo, v.modulo, COALESCE(v.nomemodulo, "Módulo") AS nomemodulo, v.ordem, v.new_id, v.sinopse
                FROM        tbread.videos v
                WHERE       v.cliente = ?
                AND         v.publicar = ?
                ORDER BY    v.ordem',
            'types' => 'is',
            'parameters' => [$tbreadid, "sim"]
        ]);

        return $busca;
    }

    public static function getViews($evento, $video)
    {
        $busca = sql([
            'statement' => 'SELECT COUNT(a.id) AS qtd FROM tbrevent.access a WHERE a.`event` = ? AND a.page LIKE "' . $video . '%"',
            'types' => 'i',
            'parameters' => [
                $evento
            ],
            'only_first_row' => '1'
        ]);

        return $busca['qtd'];
    }
}
