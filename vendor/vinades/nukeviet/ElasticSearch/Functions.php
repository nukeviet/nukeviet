<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

namespace NukeViet\ElasticSearch;

use Elasticsearch;

/**
 * NukeViet\ElasticSearch\Functions
 *
 * @package NukeViet\ElasticSearch
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @version 4.5.00
 * @access public
 */
class Functions
{
    private $_client;

    private $_index;

    /**
     * __construct()
     *
     * @param string $elas_host
     * @param int    $elas_port
     * @param string $elas_index
     */
    public function __construct($elas_host, $elas_port, $elas_index)
    {
        $hosts = [
            $elas_host . ':' . $elas_port
        ];
        $this->_client = Elasticsearch\ClientBuilder::create()->setHosts($hosts)
            ->setRetries(0)
            ->build();
        $this->_index = $elas_index;
    }

    /**
     * insert_data()
     *
     * @param mixed $table
     * @param mixed $id
     * @param mixed $body
     */
    public function insert_data($table, $id, $body)
    {
        $params = [
            'index' => $this->_index,
            'type' => $table,
            'id' => $id,
            'body' => $body
        ];
        $response = $this->_client->index($params);
    }

    /**
     * update_data()
     *
     * @param mixed $table
     * @param mixed $id
     * @param mixed $body
     */
    public function update_data($table, $id, $body)
    {
        $params = [];
        $params['index'] = $this->_index;
        $params['type'] = $table;
        $params['id'] = $id; //gan id= id cua rowcontent
        $params['body']['doc'] = $body;

        $response = $this->_client->update($params);
    }

    /**
     * delete_data()
     *
     * @param mixed $table
     * @param mixed $id
     */
    public function delete_data($table, $id)
    {
        $params = [];
        $params['index'] = $this->_index;
        $params['type'] = $table;
        $params['id'] = $id;

        $response = $this->_client->delete($params);
    }

    /**
     * search_data()
     *
     * @param mixed $table
     * @param mixed $array_query_elastic
     * @return mixed
     */
    public function search_data($table, $array_query_elastic)
    {
        $params = [];
        $params['index'] = $this->_index;
        $params['type'] = $table;
        $params['body'] = $array_query_elastic;

        return $this->_client->search($params);
    }
}
