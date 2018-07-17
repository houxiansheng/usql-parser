<?php
namespace USQL;

use USQL\Library\SqlRestraint\Restraint;
use USQL\Library\GoogleSqlParser\PHPSQLParser;
use USQL\Library\SqlRestraint\Common\HistorySql;
use USQL\Library\Kafka\producerAdapt;
use USQL\Library\Config;

class SqlStandard
{

    private static $self;

    private $phpSqlParser = null;

    private $restraint = null;

    private function __construct()
    {
        $this->phpSqlParser = new PHPSQLParser(false, false);
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
        HistorySql::write($sql);
        try {
            $parser = $this->phpSqlParser->parse($sql, true);
            $res = $this->restraint->hander($parser);
            $return = [
                'code' => 0,
                'errMsg' => 'success',
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

    public function __destruct()
    { // 发送统计好的sql信息
        $topicName = Config::get('kafka.topic');
        $sql = HistorySql::get();
        if (is_array($sql) && $sql) {
            try {
                $data = [
                    'sql' => json_encode($sql)
                ];
                // 临时替换为curl方式
                $res = $this->sendCurl($data);
                // $producerAdapt = new producerAdapt();
                // $res = $producerAdapt->send($topicName, $data);
            } catch (\Exception $e) {}
        }
    }

    private function sendCurl($data)
    {
        $url = 'http://mysqlparser.com/api/kafka/sql';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}