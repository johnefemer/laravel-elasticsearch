<?php

namespace Efemer\Elasticsearch;

class ParamBuilder implements \ArrayAccess {

    protected $index;
    protected $stash = [];
    public $payload = [];

    public function __construct(Index $index, $params = []){
        if (!empty($params)) $this->stash = $params;
        $this->index = $index;
        $this->prepRequestPayload();
    }

    protected function prepRequestPayload(){
        $this->payload = [
            'index' => $this->index->name
        ];
    }

    public function __call($method, $args){
        $params = [];
        if (!empty($args)) {
            foreach($args as $values) {
                $params = array_merge($params, $values);
            }
        }
        if (!empty($params)) $this->push($method, $params);
        return $this;
    }

    public function push($key, $params = []){
        if (empty($params)) return $this;
        if (!empty($key)) {
            $this->pointer($key, $params);
        } else {
            foreach($params as $k => $v) $this->stash[$k] = $v;
        }
        return $this;
    }

    protected function &pointer($key, $set = []){
        $pointer = &$this->stash;
        if (strpos($key, '.')) {
            $steps = explode('.', $key);
            foreach($steps as $key) {
                if (!isset($pointer[$key])) $pointer[$key] = [];
                $pointer = &$pointer[$key];
            }
        } else {
            if (!isset($pointer[$key])) $pointer[$key] = [];
            $pointer = &$pointer[$key];
        }
        if (!empty($set)) $pointer = $set;
        return $pointer;
    }

    function typeMapping($type, $config = []){
        $payload = $this->payload;
        $payload['type'] = $type;
        $payload['body'] = $this->body('mappings.'.$type, $config, ['payload' => false]);
        return $payload;
    }

    function body($key = null, $params = [], $options = []){

        $flatParams = array_get($options, 'flat', true);
        $withPayload = array_get($options, 'payload', true);

        $this->push($key, $params);

        $body = $this->stash;
        $pointer = &$this->stash;
        if (!empty($key)) {
            $pointer = $this->pointer($key);
            array_set($body, $key, $pointer);
        }

        if (empty($body)) return false;
        if ($flatParams) $body = array_shift($body);

        if ($withPayload) {
            $payload = $this->payload;
            $payload['body'] = $body;
            return $payload;
        }
        return $body;
    }


    public function toArray(){ return $this->stash; }

    function flush(){ $this->stash = []; }

    public function offsetExists($offset){
        $value = $this->offsetGet($offset);
        return !is_null($value);
    }

    public function offsetGet($offset){
        return array_get($this->stash, $offset);
    }

    public function offsetSet($offset, $value){
        array_set($this->stash, $offset, $value);
    }

    public function offsetUnset($offset){
        if (isset($this->stash[$offset])) unset($this->stash[$offset]);
    }

} // end ParamBuilder
