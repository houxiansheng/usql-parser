<?php
namespace SqlRestraint\Module;

use SqlRestraint\Abstracts\HandlerAbstract;

class Group extends HandlerAbstract
{

    protected $module = 'group';

    public function handler($index, array $fields)
    {
        return CHECK_SUCCESS;
    }
}