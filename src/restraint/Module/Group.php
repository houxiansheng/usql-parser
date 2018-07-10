<?php
namespace USQL\SqlRestraint\Module;

use USQL\SqlRestraint\Abstracts\HandlerAbstract;

class Group extends HandlerAbstract
{

    protected $module = 'group';

    public function handler($index, array $fields)
    {
        return CHECK_SUCCESS;
    }
}