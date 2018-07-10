<?php
namespace USQL;

use USQLSqlRestraint\Restraint;




class SqlStandard
{

    private static $self;

    private $phpSqlParser = null;

    private $restraint = null;

    private function __construct()
    {
        $this->phpSqlParser = new \PHPSQLParser(false, false);
        $this->restraint = new Restraint();
    }

    public static function instance()
    {
        if (is_object(self::$self)) {
            return self::$self;
        }
        self::$self = new self();
        return self::$self;
    }

    /**
     * 处理sql语句
     *
     * @param string $sql            
     * @return array [
     *         'code' => '错误码0:正常1：SQL语句异常',
     *         'errMsg' => '错误信息',
     *         'data' => [
     *         'parser' => 'sql解析后的结构',
     *         'msg' => [
     *         '不符合规范地方'
     *         ]
     *         ]
     *         ]
     */
    public function handler($sql)
    {
        try {
            $parser = $this->phpSqlParser->parse($sql, true);
            $res = $this->restraint->hander($parser);
            $return = [
                'code' => 0,
                'errMsg' => '成功',
                'data' => [
                    'parser' => $parser,
                    'msg' => $res
                ]
            ];
        } catch (\Exception $e) {
            $return = [
                'code' => 1,
                'errMsg' => $e->getMessage(),
                'data' => []
            
            ];
        }
        return $return;
    }
}