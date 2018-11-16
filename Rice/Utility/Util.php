<?php
/*
 * author:DMF
 */

namespace core;

class util{
	private static $ch;
    private $secret;

	public function __construct()
	{
	}

	public function __destruct()
	{
		// TODO: Implement __destruct() method.
		curl_close(self::$ch);
	}

    /**
     * 微信api不支持中文转义的json结构
     * @param array $arr
     */
    static function json_encode($arr) {
        $parts = array ();
        $is_list = false;
        //Find out if the given array is a numerical array
        $keys = array_keys ( $arr );

        $max_length = count ( $arr ) - 1;

        if ((@$keys [0] === 0) && (@$keys [$max_length] === $max_length )) { //See if the first key is 0 and last key is length - 1
            $is_list = true;
            for($i = 0; $i < count ( $keys ); $i ++) { //See if each key correspondes to its position
                if ($i != $keys [$i]) { //A key fails at position check.
                    $is_list = false; //It is an associative array.
                    break;
                }
            }
        }
        foreach ( $arr as $key => $value ) {
            if (is_array ( $value )) { //Custom handling for arrays
                if ($is_list)
                    $parts [] = self::json_encode ( $value ); /* :RECURSION: */
                else
                    $parts [] = '"' . $key . '":' . self::json_encode ( $value ); /* :RECURSION: */
            } else {
                $str = '';
                if (! $is_list)
                    $str = '"' . $key . '":';
                //Custom handling for multiple data types
                if (is_numeric ( $value ) && $value<2000000000)
                    $str .= $value; //Numbers
                elseif ($value === false)
                    $str .= 'false'; //The booleans
                elseif ($value === true)
                    $str .= 'true';
                else
                    $str .= '"' . addslashes ( $value ) . '"'; //All other things
                // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
                $parts [] = $str;
            }
        }
        $json = implode ( ',', $parts );
        if ($is_list)
            return '[' . $json . ']'; //Return numerical JSON
        return '{' . $json . '}'; //Return associative JSON
    }

	//执行请求
	public static function curl_require($url=null,$post='',$params=null){
		if(empty(self::$ch)||!isset(self::$ch)){
			self::$ch = curl_init();
		}

		//关闭显示数据
		curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER,true);
		//关闭加密协议
		curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt(self::$ch,CURLOPT_URL,$url);

		if($post) {
			curl_setopt(self::$ch, CURLOPT_POST, 1);
            curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt(self::$ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		}

		$data = curl_exec(self::$ch);

		if($err = curl_errno(self::$ch)){
			var_dump($err);
			return false;
		}

		return $data;
	}

    /*
     * 生成token
     */
    public function getToken(){
        $etime = time() + 600;
        $sign = md5($this->secret&$etime&PATH_INFO);
        $token = substr($sign,strlen($sign)/2,8);
        $token = $token.$etime;
        return $token;
    }
    /*
     * 验证token是否过期和正确性
     */
    public function checkToken(){
        $token = Input('token');
        $time = substr($token,8);
        if($time<time()){
            return false;
        }
    }
}