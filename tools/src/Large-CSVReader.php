<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

namespace NukeViet\Files;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

/**
 * ChunkReadFilter
 *
 * @package PHPOffice/PhpSpreadsheet
 * @author PHPOffice/PhpSpreadsheet
 * @copyright 2018
 * @version N/A
 * @access public
 */
class ChunkReadFilter implements IReadFilter
{
    private $startRow = 0;

    private $endRow = 0;

    /**
     * Set the list of rows that we want to read.
     *
     * @param mixed $startRow
     * @param mixed $chunkSize
     */
    public function setRows($startRow, $chunkSize)
    {
        $this->startRow = $startRow;
        $this->endRow = $startRow + $chunkSize;
    }

    public function readCell($column, $row, $worksheetName = '')
    {
        //  Only read the heading row, and the rows that are configured in $this->_startRow and $this->_endRow
        if (($row == 1) or ($row >= $this->startRow and $row < $this->endRow)) {
            return true;
        }

        return false;
    }
}
