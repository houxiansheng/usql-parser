<?php
namespace USQL\SqlRestraint\Module;

use USQL\SqlRestraint\Abstracts\HandlerAbstract;
use USQL\SqlRestraint\Common\ErrorLog;

class Limit extends HandlerAbstract
{

    protected $module = 'limit';

    public function handler($index, array $fields)
    {
        $offset = intval($fields['offset']);
        $rowcount = intval($fields['rowcount']);
        if ($offset > 1000) {
            ErrorLog::writeLog($this->module . '-offset-' . $offset);
        }
        if ($rowcount > 10000) {
            ErrorLog::writeLog($this->module . '-rowcount-' . $rowcount);
        }
        return CHECK_SUCCESS;
    }
}