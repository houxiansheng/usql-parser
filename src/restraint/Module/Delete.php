<?php
namespace USQL\SqlRestraint\Module;

use USQL\SqlRestraint\Abstracts\HandlerAbstract;
use USQL\SqlRestraint\Common\ErrorLog;

class Delete extends HandlerAbstract
{

    protected $module = 'delete';

    public function handler($index, array $fields)
    {
        ErrorLog::writeLog('delete');
        $res = CHECK_SUCCESS;
        return $res;
    }
}