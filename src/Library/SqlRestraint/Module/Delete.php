<?php
namespace USQL\Library\SqlRestraint\Module;

use USQL\Library\SqlRestraint\Abstracts\HandlerAbstract;
use USQL\Library\SqlRestraint\Common\ErrorLog;

class Delete extends HandlerAbstract
{

    protected $module = 'delete';

    public function handler($index, array $fields)
    {
        ErrorLog::writeLog('5-delete');
        $res = CHECK_SUCCESS;
        return $res;
    }
}