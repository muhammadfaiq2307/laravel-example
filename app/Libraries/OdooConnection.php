<?php

namespace App\Libraries;

class OdooConnection
{

    private $url;
    private $database;
    private $username;
    private $uid;
    private $password;
    private $credentials;


    public function __construct(string $url, string $database, array $credentials){
        $this->url      = $url;
        $this->database = $database;
        $this->username = $credentials['username'];
        $this->password = $credentials['password'];
        $this->uid      = self::auth();
        $this->credentials  = array($this->database, $this->uid, $this->password);
    }

    private function request(string $service, string $method, array $args)
    {
        $out = file_get_contents($this->url . '/jsonrpc', false, stream_context_create([
            'http' => [
                'header' => "Content-type: application/json\r\n",
                'content' => json_encode([
                    'jsonrpc' => '2.0',
                    'method' => 'call',
                    'params' => [
                        'service' => $service,
                        'method' => $method,
                        'args' => $args,
                    ],
                    'id' => rand(0, 1000000000),
                ]),
            ],
        ]));

        return json_decode($out, true)['result'];
    }

    public function auth(){
        return $this->request('common', 'login', [$this->database, $this->username, $this->password]);
    }

    public function rpc(string $service, string $method, array $args){
        return $this->request($service, $method, array_merge($this->credentials, $args));
    }

    public function searchRead(string $table, array $filters, array $fields){
        $params = [
            $table,
            'search_read',
            [$filters],
            ['fields' => $fields]
        ];
        return $this->rpc('object','execute_kw', $params);
    }
}
