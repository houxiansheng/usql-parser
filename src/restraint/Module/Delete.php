<?php
namespace USQLSqlRestraint\Module;

use USQLSqlRestraint\Abstracts\HandlerAbstract;
use USQLSqlRestraint\Common\ErrorLog;

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