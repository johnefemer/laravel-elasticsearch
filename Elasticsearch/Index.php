<?php

namespace App\Efemer\Factory\Elasticsearch;

class Index extends Search {

    public $params;
    public $name;

    public function __construct($indexName, $params = []){
        $this->name = $indexName;
        $this->params = new ParamBuilder($this, []);
    }

    function indices(){ return $this->client()->indices(); }

    function type($config, $options = []){
        $flush = array_get($options, 'flush', true);

        if (!is_array($config)) $config = [ 'properties' => [ $config => [ 'type' => 'string' ] ] ];
        $typeName = key($config);

        if (!$flush) {
            $this->params->push('mappings.'.$typeName, $config);
            return $this;
        }

        if (!$this->exists()) $this->create();

        $body = $this->params->typeMapping($typeName, $config[$typeName]);
        $res = $this->indices()->putMapping($body);
        if (array_get($res, 'acknowledged') == 1) log_info( 'Type ' . $typeName . ' updated for Index ' . $this->name);
        $this->params->flush();

        $return = "{$this->name}.mappings.".$typeName;
        $result = $this->indices()->getMapping($this->params->payload);
        return array_get($result, $return);

    }


    function settings($params = null, $options = []){
        $flush = array_get($options, 'flush', true);

        $PARAM_KEY = 'settings';
        if (!empty($params)) $this->params->push($PARAM_KEY, $params);

        if (!$flush) return $this;

        $body = $this->params->body($PARAM_KEY, [], ['flat' => false]);

        if (!empty($body)) {
            try {
                if ($this->exists()) {
                    $res = $this->indices()->putSettings($body);
                    if (array_get($res, 'acknowledged') == 1) log_info('Index setting ' . $this->name . ' updated.');
                    $this->params->flush();
                } else {
                    $this->create();
                }
            } catch (\Exception $ex) {
                $this->error($ex);
            }
        }

        $return = "{$this->name}.settings.index" . (is_string($params) ? '.'.$params : '');
        $result = $this->indices()->getSettings($this->params->payload);
        return array_get($result, $return);
    }

    function create($indexName = null, $params = []){
        if (!is_null($indexName)) $this->name = $indexName;

        $body = $this->params->body(null, $params, ['flat' => false]);

        $res = $this->indices()->create($body);
        if (array_get($res, 'acknowledged') == 1) log_info('Index ' . $this->name . ' created.');
        $this->params->flush();

        $result = $this->indices()->getSettings($this->params->payload);
        $return = "{$this->name}.settings.index";
        $result = $this->indices()->getSettings($this->params->payload);
        return array_get($result, $return);
    }


    function exists(){
        $params = $this->params->payload;
        return $this->indices()->exists($params);
    }

    /*
    function mapping($params = null){
        $PARAM_KEY = 'mappings';
        if (!empty($params)) $this->params->mappings($params);
        $body = $this->params->body($PARAM_KEY);
        pr($body);

        if (!empty($body)) {
            try {
                if ($this->exists()) {
                    $res = $this->indices()->putMapping($body);
                    pr($res);
                    if (array_get($res, 'acknowledged') == 1)
                        log_info('Index ' . $this->name . ' mappings updated.');
                } else {
                    //$res = $this->indices()->create($body);
                    //if (array_get($res, 'acknowledged') == 1)
                    //    log_info('Index ' . $this->name . ' created with settings.');
                }
            } catch (\Exception $ex) {
                $this->error($ex);
            }
        }

        //$return = "{$this->name}.settings.index" . (is_string($params) ? '.'.$params : '');
        $result = $this->indices()->getSettings($this->params->payload);
        pr($result);
        //return array_get($result, $return);
    }
    */


} // end class
