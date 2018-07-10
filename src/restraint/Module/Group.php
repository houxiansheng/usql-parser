<?php
namespace USQLSqlRestraint\Module;

use USQLSqlRestraint\Abstracts\HandlerAbstract;

class Group extends HandlerAbstract
{

    protected $module = 'group';

    public function handler($index, array $fields)
    {
        return CHECK_SUCCESS;
    }
}