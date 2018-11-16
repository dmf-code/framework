<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/9/23
 * Time: 15:59
 */

namespace Rice\Core\Database;

class Driver
{
    //数据库配置
    protected $config;

    //数据库pdo连接id 支持多个连接
    protected $linkId = array();
    //当前pdo连接id
    protected $_linkId = null;
    //pdo操作实例
    protected $pdoStatement = null;
    // 事务指令数
    protected $transTimes = 0;
    //当前sql指令
    protected $querySql = '';
    // 错误信息
    protected $error      = '';
    //pdo连接参数
    protected $options = array(

    );
    protected $bind = array(); // 参数绑定

    public function __construct()
    {
        $this->config = require_once ROOT_PATH . '/Conf/Database.php';

    }

    //连接数据库函数
    public function connect($config=array()){
        if(empty($config)){
            $config = $this->config;
        }
        try{
            $this->_linkId = new \PDO($config['dsn'],$config['username'],$config['password'],array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            #echo "连接成功<br/>";
        }catch (\PDOException $e) {
            #echo $e->errorInfo(),$e->getMessage();
            die ("Error!: " . $e->getMessage() . "<br/>");
        }

        return $this->_linkId;
    }

    //释放查询结果
    public function free(){
        $this->pdoStatement = null;
    }


    //启动事务
    public function startTransaction(){
        $this->connect();
        if(!$this->_linkId)return false;
        if($this->transTimes == 0){
            $this->_linkId->beginTransaction();
        }
        $this->transTimes++;
    }

    //提交数据，要非自动提交
    public function commit(){
        if($this->transTimes>0){
            $result = $this->_linkId->commit();
            $this->transTimes = 0;
            if(!$result){
                $this->error();
                return false;
            }
        }
        return true;
    }

    //回滚数据
    public function rollback(){
        if($this->transTimes>0){
            $result = $this->_linkId->rollback();
            $this->transTimes = 0;
            if(!$result){
                $this->error();
                return false;
            }
        }
        return true;
    }

    //初始化连接
    protected function initConnect(){
        //判断是否存在连接对象
       if(!$this->_linkId)$this->_linkId = $this->connect();
    }

    //关闭数据库
    public function close(){
        $this->_linkId = null;
    }

    //数据库错误信息
    public function error(){
        if($this->pdoStatement){
            $error = $this->pdoStatement->errorInfo();
            $this->error = $error[1].':'.$error[2];
        }else{
            $this->error = '';
        }
        return $this->error;
    }

    //析构函数
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        if($this->pdoStatement){
            $this->free();
        }
        //关闭数据库连接
        $this->close();
    }



    //执行查询,并返回结果集
    public function query($sql,$fetchSql=false){

        #echo '<br/>'.$sql.'<br/>';
        $this->initConnect();
        //判断是否初始化失败
        if(!$this->_linkId)return fasle;
        try{
            //pdo执行sql语句准备
            $this->pdoStatement = $this->_linkId->prepare($sql);

        }catch (\PDOException $e) {
            die ("Error!: " . $e->getMessage() . "<br/>");
            return false;
        }

        return $this;
    }

    /*
     * 数据绑定
     * $name:名
     * $value:值
     */
    public function bindParam($name,$value){
        $this->bind[':'.$name] = $value;
    }

    /*
     * 数据绑定
     * $params:键值对参数数组
     */
    public function bind($params=null){

        if(is_array($params)){
            foreach($params as $k=>$v){

                $this->bindParam($k,$v);
            }
        }

        return $this;
    }

    /*
 * 执行并返回数据
 */
    public function fetch(){

        if(!empty($this->bind)){
            //数据绑定
            foreach($this->bind as $k=>&$v){
                $this->pdoStatement->bindParam($k,$v);
            }

        }

        //执行完成清空绑定数据数组
        $this->bind = array();
        try{
            //执行sql语句
            $this->pdoStatement->execute();
        }catch(\PDOException $e){
            echo "发生错误：";
            echo "错误代号".  $e->getcode().'<br/>';
            echo "错误内容".  $e->getmessage().'<br/>';
        }


        //返回sql执行结果集
        return $this->pdoStatement->fetch(\PDO::FETCH_ASSOC);
    }

    /*
     * 执行并返回数据集
     */
    public function fetchAll(){

        if(!empty($this->bind)){
            //数据绑定
            foreach($this->bind as $k=>&$v){
                $this->pdoStatement->bindParam($k,$v);
            }

        }

        //执行完成清空绑定数据数组
        $this->bind = array();
        try{
            //执行sql语句
            $this->pdoStatement->execute();
        }catch(\PDOException $e){
            echo "发生错误：";
            echo "错误代号".  $e->getcode();
            echo "错误内容".  $e->getmessage();
        }


        //返回sql执行结果集
        return $this->pdoStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

    //执行查询
    public function execute(){

            //数据绑定
            foreach($this->bind as $k=>&$v){

                $this->pdoStatement->bindParam($k,$v);
            }

        try{
            //执行sql语句
            $flag =  $this->pdoStatement->execute();
        }catch(\PDOException $e){
            echo "发生错误：";
            echo "错误代号".  $e->getcode();
            echo "错误内容".  $e->getmessage();
        }

        //执行完成清空绑定数据数组
        $this->bind = array();

        if($flag){
            return true;
        }
        return false;

    }

    /*
     * 返回最后插入行的ID或序列值
     * $name
     */
    public function lastId($name=null){
        try {
            return $this->pdoStatement->lastInsertId();
        }catch(\PDOException $e){
            echo $e->getMessage();
        }
    }

    /*
     * 插入数据
     * $table:数据表
     * $data:插入数据
     */
    public function insert($table='',$data=array()){
        if(empty($data)){
            die('数据不能为空！');
        }

        //字段名处理
        foreach($data as $k=>$v){
            $fields[] = $k;
            $values[] = $v;
            $this->bindParam($k,$v);
        }

        //insert的sql语句处理
        $sql = 'INSERT INTO '.$table.' ( '.implode(',',$fields)
            .') VALUES (';
        foreach($fields as $k=>$v){
            $sql.=':'.$v.',';
        }
        $sql = rtrim($sql,',');
        $sql .= ')';
        echo '<br/>'.$sql.'<br/>';

        return $this->execute($sql);
    }

    /*
     * 删除数据
     * $table:表名
     * $data:插入数据
     */
    public function delete($table='',$id=''){
        $sql = 'DELETE FROM '.$table.' WHERE id='.$id;
        echo $sql.'<br/>';
        return $this->execute($sql);
    }

    /*
     * 修改数据
     * $table:表名
     * $data:插入数据
     */
    public function update($table='',$data=array()){
        if(empty($data)){
            die('数据不能为空！');
        }

        $sql = 'UPDATE '.$table.' SET ';
        //字段名处理
        foreach($data as $k=>$v) {

            $sql = $sql.$k.'=:'.$k.',';

            $this->bindParam($k,$v);
        }
        $sql = rtrim($sql,',');
        $sql = $sql.' WHERE id='.$data['id'];
        #echo $sql.'<br/>';
       return $this->execute($sql);
    }

    /*
     * 查询数据
     * $table:表名
     * $data:插入数据
     */
    public function select($table='',$option=array()){

        $sql = 'SELECT * FROM '.$table.' WHERE ';
        foreach($option as $k=>$v){
            $sql .= $k.'=:'.$k .' AND ';
        }
        $sql = rtrim($sql,' AND ');
        echo $sql.'<br/>';
        foreach($option as $k=>$v) {

            $this->bindParam($k,$v);
        }

        return $this->query($sql);
    }

}