<?php
namespace USQL\SqlRestraint\Module;

use USQL\SqlRestraint\Abstracts\HandlerAbstract;

class Order extends HandlerAbstract
{

    protected $module = 'order';

    public function handler($index, array $fields)
    {
        return CHECK_SUCCESS;
    }
}