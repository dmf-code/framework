<?php
/**
 * Created by PhpStorm.
 * User: DMF
 * Date: 2017/12/26
 * Time: 10:15
 */

namespace Rice\Core;


class Redis
{
    private $conf;
    private $redis;
    public function __construct()
    {
        $this->conf = require_once ROOT_PATH . '/Conf/redis.conf.php';

        $this->redis = new \Redis();

        $info = $this->redis->pconnect($this->conf['ip'],$this->conf['port'],1);
        if(!$info){
            die('redis配置失败！');
        }
    }
    /*
     * 描述：设置key和value的值
     * 参数：Key Value
     * 返回值：BOOL 成功返回：TRUE;失败返回：FALSE
     */
    public function set($key,$value){
        try{
            return $this->redis->set($key,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }

    }
    /*
     * 描述：获取有关指定键的值
     * 参数：key
     * 返回值：string或BOOL 如果键不存在，则返回 FALSE。否则，返回指定键对应的value值。
     */
    public function get($key){
        try{
            return $this->redis->get($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：删除指定的键
     * 参数：一个键，或不确定数目的参数，每一个关键的数组：key1 key2 key3 … keyN
     * 返回值：删除的项数
     */
    public function delete(){
        try{
            return $this->redis->delete();
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：如果在数据库中不存在该键，设置关键值参数
     * 参数：key value
     * 返回值：BOOL 成功返回：TRUE;失败返回：FALSE
     */
    public function setnx($key,$value){
        try{
            return $this->redis->setnx($key,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：验证指定的键是否存在
     * 参数key
     * 返回值：Bool 成功返回：TRUE;失败返回：FALSE
     */
    public function exists($key){
        try{
            return $this->redis->exists($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：数字递增存储键值键.
     * 参数：key value：将被添加到键的值
     * 返回值：INT the new value
     */
    public function incr($key,$value){
        try{
            return $this->redis->incr($key,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：数字递减存储键值。
     * 参数：key value：将被添加到键的值
     * 返回值：INT the new value
     */
    public function decr($key,$value){
        try{
            return $this->redis->decr($key,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：取得所有指定键的值。如果一个或多个键不存在，该数组中该键的值为假
     * 参数：其中包含键值的列表数组
     * 返回值：返回包含所有键的值的数组
     */
    public function getMultiple($array){
        try{
            return $this->redis->getMultiple($array);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：由列表头部添加字符串值。如果不存在该键则创建该列表。如果该键存在，而且不是一个列表，返回FALSE。
     * 参数：key,value
     * 返回值：成功返回数组长度，失败false
     */
    public function lPush($key,$value){
        try{
            return $this->redis->lPush($key,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：由列表尾部添加字符串值。如果不存在该键则创建该列表。如果该键存在，而且不是一个列表，返回FALSE。
     * 参数：key,value
     * 返回值：成功返回数组长度，失败false
     */
    public function rPush($key,$value){
        try{
            return $this->redis->rPush($key,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：返回和移除列表的第一个元素
     * 参数：key
     * 返回值：成功返回第一个元素的值 ，失败返回false
     */
    public function lpop($key){
        try{
            return $this->redis->lpop($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：返回的列表的长度。如果列表不存在或为空，该命令返回0。如果该键不是列表，该命令返回FALSE。
     * 参数：Key
     * 返回值：成功返回数组长度，失败false
     */
    public function lsize($key){
        try{
            return $this->redis->lsize($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    public function llen($key){
        try{
            return $this->redis->llen($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：返回指定键存储在列表中指定的元素。 0第一个元素，1第二个… -1最后一个元素，-2的倒数第二…错误的索引或键不指向列表则返回FALSE。
     * 参数：key index
     * 返回值：成功返回指定元素的值，失败false
     */
    public function lget($key,$index){
        try{
            return $this->redis->lget($key,$index);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：为列表指定的索引赋新的值,若不存在该索引返回false.
     * 参数：key index value
     * 返回值：成功返回true,失败false
     */
    public function lset($key,$index,$value){
        try{
            return $this->redis->lset($key,$index,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：返回在该区域中的指定键列表中开始到结束存储的指定元素，lGetRange(key, start, end)。0第一个元素，1第二个元素… -1最后一个元素，-2的倒数第二…
     * 参数：key start end
     * 返回值：成功返回查找的值，失败false
     */
    public function lgetrange($key,$start,$end){
        try{
            return $this->redis->lgetrange($key,$start,$end);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：从列表中从头部开始移除count个匹配的值。如果count为零，所有匹配的元素都被删除。如果count是负数，内容从尾部开始删除。
     * 参数：key count value
     * 返回值：成功返回删除的个数，失败false
     */
    public function lremove($key,$count,$value){
        try{
            return $this->redis->lremove($key,$count,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：为一个Key添加一个值。如果这个值已经在这个Key中，则返回FALSE。
     * 参数：key value
     * 返回值：成功返回true,失败false
     */
    public function sadd($key,$value){
        try{
            return $this->redis->sadd($key,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：删除Key中指定的value值
     * 参数：key member
     * 返回值：true or false
     */
    public function sremove($key,$member){
        try{
            return $this->redis->sremove($key,$member);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：将Key1中的value移动到Key2中
     * 参数：srcKey dstKey member
     * 返回值：true or false
     */
    public function smove($srcKey,$dstKey,$member){
        try{
            return $this->redis->smove($srcKey,$dstKey,$member);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：检查集合中是否存在指定的值。
     * 参数：key value
     * 返回值：true or false
     */
    public function scontains($key,$value){
        try{
            return $this->redis->scontains($key,$value);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：返回集合中存储值的数量
     * 参数：key
     * 返回值：成功返回数组个数，失败0
     */
    public function ssize($key){
        try{
            return $this->redis->ssize($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：随机移除并返回key中的一个值
     * 参数：key
     * 返回值：成功返回删除的值，失败false
     */
    public function spop($key){
        try{
            return $this->redis->spop($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：返回一个所有指定键的交集。如果只指定一个键，那么这个命令生成这个集合的成员。如果不存在某个键，则返回FALSE。
     * 参数：key1, key2, keyN
     * 返回值：成功返回数组交集，失败false
     */
    public function sinter($array){
        try{
            return $this->redis->sinter($array);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：执行sInter命令并把结果储存到新建的变量中。
     * 参数：
     * Key: dstkey, the key to store the diff into.
     * Keys: key1, key2… keyN. key1..keyN are intersected as in sInter.
     * 返回值：成功返回，交集的个数，失败false
     */
    public function sinterstore($dstkey,$keys){
        try{
            return $this->redis->sinterstore($dstkey,$keys);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：
     * 返回一个所有指定键的并集
     * 参数：
     * Keys: key1, key2, … , keyN
     * 返回值：成功返回合并后的集，失败false
     */
    public function sunion($keys){
        try{
            return $this->redis->sunion($keys);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：执行sunion命令并把结果储存到新建的变量中。
     * 参数：
     * Key: dstkey, the key to store the diff into.
     * Keys: key1, key2… keyN. key1..keyN are intersected as in sInter.
     * 返回值：成功返回，交集的个数，失败false
     */
    public function sunionstore($key,$keys){
        try{
            return $this->redis->sunionstore($key,$keys);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：返回第一个集合中存在并在其他所有集合中不存在的结果
     * 参数：Keys: key1, key2, … , keyN: Any number of keys corresponding to sets in redis.
     * 返回值：成功返回数组，失败false
     */
    public function sdiff($keys){
        try{
            return $this->redis->sdiff($keys);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：执行sdiff命令并把结果储存到新建的变量中。
     * 参数：
     * Key: dstkey, the key to store the diff into.
     * Keys: key1, key2, … , keyN: Any number of keys corresponding to sets in redis
     * 返回值：成功返回数字，失败false
     */
    public function sdiffstore($key,$keys){
        try{
            return $this->redis->sdiffstore($key,$keys);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    /*
     * 描述：
     * 返回集合的内容
     * 参数：Key: key
     * 返回值：An array of elements, the contents of the set.
     */
    public function smembers($key){
        try{
            return $this->redis->smembers($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    public function sgetmembers($key){
        try{
            return $this->redis->sgetmembers($key);
        }catch(\RedisException $e){
            die ("Error!: " . $e->getMessage() . "<br/>");
        }
    }
}
