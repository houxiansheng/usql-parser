<?php
namespace SqlRestraint\Module;

use SqlRestraint\Abstracts\HandlerAbstract;
use SqlRestraint\Common\ErrorLog;

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