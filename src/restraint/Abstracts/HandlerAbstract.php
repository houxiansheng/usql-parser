<?php
namespace SqlRestraint\Abstracts;

use SqlRestraint\Common\ErrorLog;
use SqlRestraint\Common\CommonTool;

abstract class HandlerAbstract
{

    protected $module = null;

    public function handler($index, array $fields)
    {
        switch ($fields['expr_type']) {
            case 'subquery': // 存在子查询，返回继续遍历
                $res = CHECK_RECURION;
                break;
            case 'match-arguments':
                $res = $this->matchArguments($index, $fields);
                break;
            case 'match-mode':
                $res = CHECK_SUCCESS;
                break;
            case 'colref': // 列名
                $res = $this->colRef($index, $fields);
                break;
            case 'reserved': // 保留字段
                $res = CHECK_SUCCESS;
                break;
            case 'const': // 常量
                $res = CHECK_SUCCESS;
                break;
            case 'expression': // 表达式
                $res = $this->expression($index, $fields);
                break;
            case 'aggregate_function':
                $res = $this->aggregateFun($index, $fields);
                break;
            case 'function':
                $res = $this->function($index, $fields);
                break;
            case 'operator': // 操作符
                $res = $this->operator($index, $fields);
                break;
            case 'table': // 表名
                $res = $this->table($index, $fields);
                break;
            case 'bracket_expression':
                $res = $this->bracketExpression($index, $fields);
                break;
            case 'in-list':
                $res = $this->inList($index, $fields);
                break;
            default:
                ErrorLog::writeLog('untreated-' . $this->module . '-' . $fields['expr_type']);
                break;
        }
        return $res;
    }

    protected function matchArguments($index, $fields)
    {
        if (isset($fields['sub_tree']) && $fields['sub_tree']) {
            foreach ($fields['sub_tree'] as $key => $val) {
                $this->handler($key, $val);
            }
        }
    }

    protected function expression($index, $fields)
    {
        if (isset($fields['sub_tree']) && $fields['sub_tree']) {
            foreach ($fields['sub_tree'] as $key => $val) {
                $this->handler($key, $val);
            }
        }
        return CHECK_SUCCESS;
    }

    protected function colRef($index, $fields)
    {
        // 别称定义
        if (isset($fields['alias']) && $fields['alias'] && CommonTool::keyWord($fields['alias']['no_quotes'])) {
            ErrorLog::writeLog($this->module . '-alias-' . $fields['alias']['no_quotes']);
        }
        if (isset($fields['base_expr']) && $fields['base_expr'] && $fields['base_expr'] == '*') {
            ErrorLog::writeLog($this->module . '-*');
        }
    }

    protected function aggregateFun($index, $fields)
    {
        if (isset($fields['alias']) && $fields['alias'] && CommonTool::keyWord($fields['alias']['no_quotes'])) {
            ErrorLog::writeLog($this->module . '-alias-' . $fields['alias']['no_quotes']);
        }
        // 判断下函数是否禁用
        if (CommonTool::math($fields['base_expr'])) {
            ErrorLog::writeLog($this->module . '-fun-' . $fields['base_expr']);
        }
        if (isset($fields['sub_tree']) && $fields['sub_tree']) {
            foreach ($fields['sub_tree'] as $key => $val) {
                $this->handler($key, $val);
            }
        }
        return CHECK_SUCCESS;
    }

    protected function function($index, $fields)
    {
        if ($this->module == 'where') { // where下禁用一切函数,暂不考虑左侧还是右侧
            ErrorLog::writeLog($this->module . '-fun-' . $fields['base_expr']);
        } else {
            if (CommonTool::math($fields['base_expr'])) {
                ErrorLog::writeLog($this->module . '-fun-' . $fields['base_expr']);
            }
        }
        if (isset($fields['sub_tree']) && $fields['sub_tree']) {
            foreach ($fields['sub_tree'] as $key => $val) {
                $this->handler($key, $val);
            }
        }
        return CHECK_SUCCESS;
    }

    protected function table($index, $fields)
    {
        if (isset($fields['ref_clause']) && $fields['ref_clause']) {
            foreach ($fields['ref_clause'] as $key => $single) {
                $this->handler($key, $single);
            }
        }
        if ($index >= 2) {
            ErrorLog::writeLog($this->module . '-join-max');
        }
        if (isset($fields['alias']) && $fields['alias'] && CommonTool::keyWord($fields['alias']['no_quotes'])) {
            ErrorLog::writeLog($this->module . '-alias-' . $fields['alias']['no_quotes']);
        }
        return CHECK_SUCCESS;
    }

    protected function operator($index, $fields)
    {
        $tmp = [
            'not',
            '<>',
            '!',
            'not',
            'like'
        ];
        if (in_array($fields['base_expr'], $tmp)) {
            ErrorLog::writeLog($this->module . '-operator-' . $fields['base_expr']);
        }
        return CHECK_SUCCESS;
    }

    protected function inList($index, $fields)
    {
        $inMaxNum = 1000;
        if (isset($fields['sub_tree']) && $fields['sub_tree'] && count($fields['sub_tree']) > $inMaxNum) {
            ErrorLog::writeLog($this->module . '-in-list-max');
        }
        return CHECK_SUCCESS;
    }

    protected function bracketExpression($index, $fields)
    {
        if (isset($fields['sub_tree']) && $fields['sub_tree']) {
            foreach ($fields['sub_tree'] as $key => $val) {
                $this->handler($key, $val);
            }
        }
        return CHECK_SUCCESS;
    }
}