<?php
namespace USQL\SqlRestraint\Module;

use USQL\SqlRestraint\Abstracts\HandlerAbstract;
use USQL\SqlRestraint\Common\ErrorLog;

class Where extends HandlerAbstract
{

    protected $module = 'where';

    protected function aggregateFun($index, $fields)
    {
        ErrorLog::writeLog($this->module . '-fun-' . $fields['base_expr']);
        return CHECK_SUCCESS;
    }
}