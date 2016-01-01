<?php

namespace Efemer\Search\Core;

use Elasticsearch\ClientBuilder;

class Elasticsearch {

    public $clientConnection = null;
    public $silent = false;      // will not throw error but will log them

    protected function hosts(){
        $hosts = config('search.hosts');
        return $hosts;
    }

    function client(){
        if (empty($this->clientConnection)) {
            $this->clientConnection = ClientBuilder::create()
                                        ->setHosts($this->hosts())
                                        ->build();
        }
        return $this->clientConnection;
    }

    function health(){
        $result = $this->client()->cluster()->health();
        pr($result);
    }

    function error($exception){
        if ($exception instanceof \Exception) {
            log_error($exception->getMessage());
            if (!$this->silent) throw $exception;
        }
        log_error((string)$exception);
    }

    function logAck($res){

    }

}