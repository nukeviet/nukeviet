<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Mr.Thang (kid.apt@gmail.com)
 * @Copyright (C) 2016 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 09/16/2016 14:30
 */

namespace NukeViet\ElasticSearch;

use Elasticsearch;

class Functions
{

    private $_client;

    private $_index;

    /**
     * @param mixed $elas_host, $elas_port, $elas_index
     * Elasticsearch::__construct()
     */
    
    public function __construct($elas_host, $elas_port, $elas_index)
    {
        $hosts = array(
            $elas_host . ':' . $elas_port
        );
        $this->_client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)
            ->setRetries(0)
            ->build();
        $this->_index = $elas_index;
    }

    /**
     *
     * @param mixed $table, $id, $body
     * @return
     */
    public function insert_data($table, $id, $body)
    {
        $params = array(
            'index' => $this->_index,
            'type' => $table,
            'id' => $id,
            'body' => $body
        );
        $response = $this->_client->index($params);
    }

    /**
     *
     * @param mixed $table, $id, $body
     * @return
     */
    public function update_data($table, $id, $body)
    {
        $params = array();
        $params['index'] = $this->_index;
        $params['type'] = $table;
        $params['id'] = $id; //gan id= id cua rowcontent
        $params['body']['doc'] = $body;
        
        $response = $this->_client->update($params);
    }

    /**
     *
     * @param mixed $table, $id, $body
     * @return
     */
    public function delete_data($table, $id)
    {
        $params = array();
        $params['index'] = $this->_index;
        $params['type'] = $table;
        $params['id'] = $id;
        
        $response = $this->_client->delete($params);
    }

    /**
     *
     * @param mixed $table, $params
     * @return
     */
    public function search_data($table, $array_query_elastic)
    {
        $params = array();
        $params['index'] = $this->_index;
        $params['type'] = $table;
        $params['body'] = $array_query_elastic;
        
        return $this->_client->search($params);
    }
}