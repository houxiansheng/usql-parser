<?php
namespace USQL\SqlRestraint;

class Restraint
{

    protected $register = [];

    protected $status = true;

    protected $errMsg = [];

    public function __construct()
    {
        $this->register('SELECT', \USQL\SqlRestraint\Module\Select::class);
        $this->register('DELETE', \USQL\SqlRestraint\Module\Delete::class);
        $this->register('FROM', \USQL\SqlRestraint\Module\From::class);
        $this->register('WHERE', \USQL\SqlRestraint\Module\Where::class);
        $this->register('GROUP', \USQL\SqlRestraint\Module\Group::class);
        $this->register('ORDER', \USQL\SqlRestraint\Module\Order::class);
        $this->register('LIMIT', \USQL\SqlRestraint\Module\Limit::class);
    }

    protected function register($type, $className)
    {
        $this->register[$type]['className'] = $className;
    }

    public function hander($parseArr)
    {
        foreach ($parseArr as $key => $val) {
            if ($this->getHandleObject($key)) {
                $this->recursion($key, $val);
            }
        }
        $err = \USQL\SqlRestraint\Common\ErrorLog::getLog();
        \USQL\SqlRestraint\Common\ErrorLog::destoryErrMsg();
        return $err;
    }

    protected function recursion($module, $content)
    {
        if ($module == 'LIMIT') {
            $res = $this->getHandleObject($module)->handler(0, $content);
            return;
        }
        if ($module == 'DELETE') {
            $res = $this->getHandleObject($module)->handler(0, $content);
            return;
        }
        foreach ($content as $key => $val) {
            if (is_numeric($key)) {
                $res = $this->getHandleObject($module)->handler($key, $val);
                if ($res == CHECK_RECURION) {
                    // 此时一般都遍历subTree
                    foreach ($val['sub_tree'] as $subKey => $subVal) {
                        $this->recursion($subKey, $subVal);
                    }
                } elseif ($res == CHECK_FAIL) {}
            } else {
                $this->recursion($key, $val);
            }
        }
    }

    protected function getHandleObject($module)
    {
        if (! is_object($this->register[$module]['object'])) {
            $this->register[$module]['object'] = new $this->register[$module]['className']();
        }
        return $this->register[$module]['object'];
    }
}