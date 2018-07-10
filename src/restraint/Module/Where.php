<?php
namespace USQLSqlRestraint\Module;

use USQLSqlRestraint\Abstracts\HandlerAbstract;
use USQLSqlRestraint\Common\ErrorLog;

class Where extends HandlerAbstract
{

    protected $module = 'where';

    protected function aggregateFun($index, $fields)
    {
        ErrorLog::writeLog($this->module . '-fun-' . $fields['base_expr']);
        return CHECK_SUCCESS;
    }
}