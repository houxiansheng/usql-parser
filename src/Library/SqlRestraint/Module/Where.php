<?php
namespace USQL\Library\SqlRestraint\Module;

use USQL\Library\SqlRestraint\Abstracts\HandlerAbstract;
use USQL\Library\SqlRestraint\Common\ErrorLog;

class Where extends HandlerAbstract
{

    protected $module = 'where';

    protected function aggregateFun($index, $fields)
    {
        ErrorLog::writeLog($this->module . '-fun-' . $fields['base_expr']);
        return CHECK_SUCCESS;
    }
}