<?php

/**
 * Created by PhpStorm.
 * User: caozhi
 * Date: 2017/2/6
 * Time: 11:22
 */
class MySQL
{

    protected $link = false;  //数据库连接资源
    protected $sql;           //sql语句

    /**
     * 构造函数，负责连接服务器、选择数据库、设置字符集等
     * @param $config string 配置数组
     */
    public function __construct($config = array()){
        $host = isset($config['host'])? $config['host'] : 'localhost';
        $user = isset($config['user'])? $config['user'] : 'root';
        $password = isset($config['password'])? $config['password'] : '';
        $dbname = isset($config['dbname'])? $config['dbname'] : '';
        $port = isset($config['port'])? $config['port'] : '3306';
        $charset = isset($config['charset'])? $config['charset'] : 'utf8';

        $this->link = @mysql_connect("$host:$port",$user,$password) or die('数据库连接错误');
        mysql_select_db($dbname) or die('数据库选择错误');
        $this->setChar($charset);
    }
    /**
     * 设置字符集
     * @access private
     * @param $charset string 字符集
     */
    private function setChar($charest){
        $sql = 'set names '.$charest;
        $this->query($sql);
    }

    /**
     * 执行sql语句
     * @access public
     * @param $sql string 查询sql语句
     * @return $result，成功返回资源，失败则输出错误信息，并退出
     */
    public function query($sql){
        $this->sql = $sql;
        $result = mysql_query($this->sql,$this->link);

        if (! $result) {
            $this->dieError('',$sql);//可除掉
        }

        return $result;
    }

    public function queryBool($sql){
        $this->sql = $sql;
        $result = mysql_query($this->sql,$this->link);
        if(!$result){
            $this->dieError('',$sql);//可除掉
        }else
        {
            $count = mysql_affected_rows($this->link);
            if($count){
                return 1;
            }else{
                return 0;
            }

        }
    }

    public function insert($sql){
//        $this->query($sql);
        $this->sql = $sql;

        $result = mysql_query($this->sql,$this->link);

        return !$result? $result : $this->getInsertId();
    }

    /**
     * 获取上一步insert操作产生的id
     */
    public function getInsertId(){
        return mysql_insert_id($this->link);
    }
    //这个方法为了执行一条返回多行数据的语句，它可以返回二维数组
    function getRows($sql)
    {
        $result = $this->query($sql) or $this->dieError('查询出错',$sql);
        $arr = Array();
        while ($row = mysql_fetch_assoc($result)) {
            $arr[] = $row;
        }
        mysql_free_result($result);
        return $arr;
    }
    //这个方法为了执行一条返回一行数据的语句，它可以返回一维数组
    function getRow($sql){
        $result = $this->query($sql) or $this->dieError('查询出错',$sql);
        //如果没有出错，则开始处理数据，以返回数组。此时$result是一个结果集
        $rec = mysql_fetch_assoc( $result );//取出第一行数据（其实应该只有这一行）
        mysql_free_result( $result );
        return $rec;
    }
    //这个方法为了执行一条返回一个数据的语句，它可以返回一个直接值
    //这条语句类似这样：select  count(*) as c  from  user_list
    function getData($sql){

        $result = $this->query($sql) or $this->dieError('查询出错',$sql);
        //这里开始处理数据，以返回一个数据（标量数据）！
        $rec = mysql_fetch_row( $result );	//这里也可以使用fetch_array这个函数！
        //这里得到$rec仍然是一个数组，但其类似这样：
        //  array ( 0=> 5 );或者 array( 0=>'user1');
        $data = $rec[0];
        mysql_free_result( $result );	//提前释放资源（销毁结果集），否则需要等到页面结束才自动销毁
        return $data;
    }

    public function close(){
        mysql_close($this->link);
    }

    private function dieError($msg = '', $sql = '')
    {

        echo "<p>{$msg},请参考如下信息：";
        echo "<br />错误代号：" . mysql_errno();    //获取错误代号
        echo "<br />错误信息：" . mysql_error();    //获取错误提示内部
        if ($sql) echo "<br />错误语句：" . $sql;
        $this->close();
        die();
    }

}