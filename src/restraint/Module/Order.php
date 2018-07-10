<?php
namespace USQLSqlRestraint\Module;

use USQLSqlRestraint\Abstracts\HandlerAbstract;

class Order extends HandlerAbstract
{

    protected $module = 'order';

    public function handler($index, array $fields)
    {
        return CHECK_SUCCESS;
    }
}