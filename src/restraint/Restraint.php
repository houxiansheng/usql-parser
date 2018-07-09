<?php
namespace SqlRestraint;













class Restraint
{

    protected $register = [];

    protected $status = true;

    protected $errMsg = [];

    public function __construct()
    {
        $this->register('SELECT', \SqlRestraint\Module\Select::class);
        $this->register('DELETE', \SqlRestraint\Module\Delete::class);
        $this->register('FROM', \SqlRestraint\Module\From::class);
        $this->register('WHERE', \SqlRestraint\Module\Where::class);
        $this->register('GROUP', \SqlRestraint\Module\Group::class);
        $this->register('ORDER', \SqlRestraint\Module\Order::class);
        $this->register('LIMIT', \SqlRestraint\Module\Limit::class);
    }

    protected function register($type, $className)
    {
        $this->register[$type] = new $className();
    }

    public function hander($parseArr)
    {
        foreach ($parseArr as $key => $val) {
            if ($this->register[$key]) {
                $this->recursion($key, $val);
            }
        }
        $err = \SqlRestraint\Common\ErrorLog::getLog();
        \SqlRestraint\Common\ErrorLog::destoryErrMsg();
        return $err;
    }

    protected function recursion($module, $content)
    {
        if ($module == 'LIMIT') {
            $res = $this->register[$module]->handler(0, $content);
            return;
        }
        if ($module == 'DELETE') {
            $res = $this->register[$module]->handler(0, $content);
            return;
        }
        foreach ($content as $key => $val) {
            if (is_numeric($key)) {
                $res = $this->register[$module]->handler($key, $val);
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
}