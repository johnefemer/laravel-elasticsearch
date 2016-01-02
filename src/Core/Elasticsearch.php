<?php

namespace Efemer\Search\Core;

use Elasticsearch\ClientBuilder;

class Elasticsearch {

    public $clientConnection = null;
    public $silent = false;      // will not throw error but will log them

    public function __construct(){
        $this->debug = $this->config('search.silent', false);
    }



    public function hosts(){
        $hosts = $this->config('search.hosts', ['127.0.0.1:9200']);
        return $hosts;
    }

    public function client(){
        if (empty($this->clientConnection)) {
            $this->clientConnection = ClientBuilder::create()
                                        ->setHosts($this->hosts())
                                        ->build();
        }
        return $this->clientConnection;
    }

    // cluster calls
    public function cluster(){ return $this->client()->cluster(); }

    public function health(){
        $result = $this->cluster()->health();
        return $this->result($result);
    }


    // global indices calls
    public function indices(){ return $this->client()->indices(); }

    // index specific calls
    public function index($indexName = null){
        if (is_null($indexName)) $indexName = config('search.defaults.index');
        return new IndexManager($indexName);
    }



    function result($result, $context = null){
        return $result;
    }

    function error($exception){
        if ($exception instanceof \Exception) {
            app('log')->error($exception->getMessage());
            if (!$this->silent) throw $exception;
        } else {
            app('log')->error((string)$exception);
        }
    }

    public function config($key, $default = null){ return config($key, $default); }


} // end Elasticsearch