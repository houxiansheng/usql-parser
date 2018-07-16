<?php
namespace USQL\Library\SqlRestraint\Module;

use USQL\Library\SqlRestraint\Abstracts\HandlerAbstract;

class Order extends HandlerAbstract
{

    protected $module = 'order';

    public function handler($index, array $fields)
    {
        return CHECK_SUCCESS;
    }
}