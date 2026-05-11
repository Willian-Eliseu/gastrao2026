<?php
class Usuarios
{
    public $id;
    public $event;
    public $language;
    public $cpf;
    public $rg;
    public $crm_uf;
    public $crm;
    public $crm_valid;
    public $treatment;
    public $sex;
    public $age;
    public $firstname;
    public $lastname;
    public $badgename;
    public $cep;
    public $address;
    public $number;
    public $district;
    public $city;
    public $state;
    public $country;
    public $cellphone;
    public $phone;
    public $email;
    public $password;
    public $specialty;
    public $years_experience;
    public $job;
    public $subscribe_training_center;
    public $subscribe_training_center_others;
    public $date;
    public $ip;
    public $ip_country;
    public $ip_country_code;
    public $ip_city;
    public $ip_continent;
    public $ip_latitude;
    public $ip_longitude;
    public $ip_time_zone;
    public $ip_postal_code;
    public $ip_subdivision;
    public $associate;
    public $accept_terms;
    public $notifications_email;
    public $notifications_sms;
    public $contact_organization;
    public $control_hash;
    public $enable;
    public $status;
    public $passkey;

    public function showData()
    {
        return $this->language;
    }

    public function getUserById()
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.registration WHERE id = ?',
            'types' => 'i',
            'parameters' => [
                $this->id
            ],
            'only_first_row' => '1'
        ]);

        return $consulta;
    }

    public function getUserByIdEvent()
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.registration WHERE id = ? AND `event` = ?',
            'types' => 'ii',
            'parameters' => [
                $this->id,
                $this->event
            ],
            'only_first_row' => '1'
        ]);

        return $consulta;
    }

    public function getAdmByIdEvent()
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.master_user WHERE id = ? AND `event` = ?',
            'types' => 'ii',
            'parameters' => [
                $this->id,
                $this->event
            ],
            'only_first_row' => '1'
        ]);

        return $consulta;
    }

    public function userUpdate($data)
    {
        $campos = array();
        $valores = array();
        $qtdParametros = count($data);
        $controle = $qtdParametros - 2;
        $i = 0;

        foreach ($data as $k => $v) {
            $campoParcial = strlen(explode("_", $k)[0]) + 1;
            $campos[$i] = substr($k, $campoParcial);
            $valores[$i] = $v;
            $i++;
        }

        for ($j = 0; $j <= $controle; $j++) {
            sql([
                'statement' => 'UPDATE tbrevent.registration SET ' . $campos[$j] . ' = ? WHERE id = ?',
                'types' => 'si',
                'parameters' => [
                    $valores[$j],
                    $data['subscribe_id']
                ]
            ]);
        }

        $retorno = $GLOBALS['sql']->error;
        return $retorno == "" ? 1 : 0;
    }

    public function setNewPassword()
    {
        sql([
            'statement' => 'UPDATE tbrevent.registration SET `password` = ?, passkey = null WHERE id = ?',
            'types' => 'si',
            'parameters' => [
                $this->password,
                $this->id
            ]
        ]);
    }

    public function getUserByPasskey()
    {
        $consulta = sql([
            'statement' => 'SELECT id FROM tbrevent.registration WHERE passkey = ?',
            'types' => 's',
            'parameters' => [
                $this->passkey
            ],
            'only_first_row' => '1'
        ]);

        return $consulta;
    }

    public function insertKey($chave, $userid)
    {
        sql([
            'statement' => 'UPDATE tbrevent.registration SET passkey = ? WHERE id = ?',
            'types' => 'si',
            'parameters' => [
                $chave,
                $userid
            ]
        ]);
    }

    public function getUserByEmail()
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.registration WHERE `event` = ? AND email = ?',
            'types' => 'is',
            'parameters' => [
                $this->event,
                $this->email
            ],
            'only_first_row' => '1'
        ]);
        return $consulta;
    }

    public function getEventName()
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.`event` WHERE id = ?',
            'types' => 'i',
            'parameters' => [
                $this->event
            ],
            'only_first_row' => '1'
        ]);
        return $consulta;
    }

    public function getRegistrationEmail()
    {
        if ($this->language == null) {
            $consulta = sql([
                'statement' => 'SELECT * FROM tbrevent.registration_email WHERE `event` = ?',
                'types' => 'i',
                'parameters' => [
                    $this->event
                ],
                'only_first_row' => '1'
            ]);
        } else {
            $consulta = sql([
                'statement' => 'SELECT * FROM tbrevent.registration_email WHERE `event` = ? AND `language` = ?',
                'types' => 'is',
                'parameters' => [
                    $this->event,
                    $this->language
                ],
                'only_first_row' => '1'
            ]);
        }

        return $consulta;
    }

    public function verificaUsuarioClean()
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.registration WHERE email = ? AND `event` = ?',
            'types' => 'si',
            'parameters' => [
                $this->email,
                $this->event
            ],
            'only_first_row' => '1'
        ]);
        return $consulta;
    }

    public static function verificaUsuario($email, $evento)
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.registration WHERE email = ? AND `event` = ?',
            'types' => 'si',
            'parameters' => [
                $email,
                $evento
            ],
            'only_first_row' => '1'
        ]);
        return $consulta;
    }

    #login: email e senha
    public function loginESUsuario()
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.registration WHERE email = ? AND `password` = ? AND `event` = ?',
            'types' => 'ssi',
            'parameters' => [
                $this->email,
                $this->password,
                $this->event
            ],
            'only_first_row' => '1'
        ]);
        return $consulta;
    }

    public function loginEUsuario()
    {
        $consulta = sql([
            'statement' => 'SELECT * FROM tbrevent.registration WHERE email = ? AND `event` = ?',
            'types' => 'si',
            'parameters' => [
                $this->email,
                $this->event
            ],
            'only_first_row' => '1'
        ]);
        return $consulta;
    }

    public function loginESAdm()
    {
        $consulta = sql([
            'statement' => 'SELECT *, 1 AS enable FROM tbrevent.master_user WHERE email = ? AND `password` = ? AND `event` = ?',
            'types' => 'ssi',
            'parameters' => [
                $this->email,
                $this->password,
                $this->event
            ],
            'only_first_row' => '1'
        ]);
        return $consulta;
    }

    public function loginEAdm()
    {
        $consulta = sql([
            'statement' => 'SELECT *, 1 AS enable FROM tbrevent.master_user WHERE email = ? AND `event` = ?',
            'types' => 'si',
            'parameters' => [
                $this->email,
                $this->event
            ],
            'only_first_row' => '1'
        ]);
        return $consulta;
    }

    public function cadastroUsuario()
    {
        sql([
            'statement' => 'INSERT INTO
                    tbrevent.registration
                SET
                    `event` = ?,
                    badgename = ?,
                    `language` = ?,
                    treatment = ?,
                    sex = ?,
                    cpf = ?,
                    rg = ?,
                    crm = ?,
                    crm_uf = ?,
                    crm_valid = ?,
                    firstname = UPPER(?),
                    lastname = UPPER(?),
                    cellphone = ?,
                    phone = ?, 
                    cep = ?,
                    `address` = ?,
                    `number` = ?,
                    district= ?,
                    city = ?,
                    `state` = ?,
                    country = ?,
                    email = LOWER(?),
                    `password` = ?,
                    specialty = ?,
                    subscribe_training_center = ?,
                    subscribe_training_center_others = ?,
                    `date` = ?,
                    job =?,
                    ip=?,
                    ip_country=?,
                    ip_country_code=?,
                    ip_city=?,
                    ip_continent=?,
                    ip_latitude=?,
                    ip_longitude=?,
                    ip_time_zone=?,
                    ip_postal_code=?,
                    ip_subdivision=?,
                    `enable`=?,
                    notifications_email=?,
                    accept_terms=?,
                    associate=?,
                    years_experience=?,
                    notifications_sms=?,
                    contact_organization=?,
                    control_hash=?',
            'types' => 'isssssssssssssssssssssssisssssssssssssisssisss',
            'parameters' => [
                $this->event,
                $this->badgename,
                $this->language,
                $this->treatment,
                $this->sex,
                $this->cpf,
                $this->rg,
                $this->crm,
                $this->crm_uf,
                $this->crm_valid,
                $this->firstname,
                $this->lastname,
                $this->cellphone,
                $this->phone,
                $this->cep,
                $this->address,
                $this->number,
                $this->district,
                $this->city,
                $this->state,
                $this->country,
                $this->email,
                $this->password,
                $this->specialty,
                $this->subscribe_training_center,
                $this->subscribe_training_center_others,
                $this->date,
                $this->job,
                $this->ip,
                $this->ip_country,
                $this->ip_country_code,
                $this->ip_city,
                $this->ip_continent,
                $this->ip_latitude,
                $this->ip_longitude,
                $this->ip_time_zone,
                $this->ip_postal_code,
                $this->ip_subdivision,
                $this->enable,
                $this->notifications_email,
                $this->accept_terms,
                $this->associate,
                $this->years_experience,
                $this->notifications_sms,
                $this->contact_organization,
                $this->control_hash
            ]
        ]);

        return $GLOBALS['sql']->insert_id;
    }
}
