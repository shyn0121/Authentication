<?php
//aes-128/gcm php_version:php7.1++
//yii2 
//注意事项 
class Authentication {

    protected $appid, $secret_key, $bizid;
    protected $check_url, $query_url, $loginout;

    //待加密数据
    protected $ai, $name, $id_num;
    protected $time;
    protected $body;
    protected $header;
    protected $sign;
    /**
     * [initConfig 初始化配置]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-05
     * @route    [route]
     * @return   [type]     [description]
     */
    public function initConfig() {
        //测试 配置
        $this->appid      = '6b9408c0f41a4eb993def7675c327b0d';
        $this->secret_key = '5daa7a59d85bb9b26eb7b6b1c645156a';
	
		//测试替换url 测试码
        $this->check_url = 'https://wlc.nppa.gov.cn/test/authentication/check/cSo9or';
        $this->query_url = 'https://wlc.nppa.gov.cn/test/authentication/query/M62kqm';
        $this->loginout  = 'https://wlc.nppa.gov.cn/test/collection/loginout/GQYBfA';

		//时间戳
        $this->time = $this->getMillisecond();
    }
	
	//入口在这里 
	//直接运行这里 
	//1.需要配置$this->check() 函数中的配置 query loginout 同上
    public function index() {
			//testcase01-实名认证接口
			//testcase02-实名认证接口
			//testcase03-实名认证接口
            $check_res = $this->check();
			echo '<pre>';
			var_dump($check_res);exit;
			//testcase04-实名认证结果查询接口
			//testcase05-实名认证结果查询接口
			//testcase06-实名认证结果查询接口
            $query_res = $this->query();
			var_dump($query_res);
			
			//testcase07-游戏用户行为数据上报接口
			//testcase08-游戏用户行为数据上报接口
            $loginout_res = $this->loginout();
			var_dump($loginout_res);
    }

    /**
     * [check 实名认证接口]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-05
     * @route    [route]
     * @return   [type]     [description] ($url, $data, $headers, $is_post = true, $is_ssl = false)
     */
    public function check() {
	
		//测试数据替换数据 用预置数据测试 https://wlc.nppa.gov.cn/fcm_company/%E7%BD%91%E7%BB%9C%E6%B8%B8%E6%88%8F%E9%98%B2%E6%B2%89%E8%BF%B7%E5%AE%9E%E5%90%8D%E8%AE%A4%E8%AF%81%E7%B3%BB%E7%BB%9F%E6%B5%8B%E8%AF%95%E7%B3%BB%E7%BB%9F%E8%AF%B4%E6%98%8E.pdf
        //下面的数据测出为失败 一定要用预置数据哦 query 同理 loginout 也要注意配置
		$this->ai     = 'fadsfdsfas'; //游戏内部对应的唯一标识建议32位
        $this->name   = "邵亚男";
        $this->id_num = "342201199304134645";
        $this->bizid  = "1101999999";

        $this->initConfig();
        if (empty($this->ai) || empty($this->name) || empty($this->id_num)) {
            die("body请对配置参数赋值");
        }

        //业务参数
        $body_params = [
            'ai'    => $this->ai,
            'name'  => $this->name,
            'idNum' => $this->id_num,
        ];
        //string
        $this->body   = $this->getBody($body_params);
        $this->sign   = $this->getSign($this->body);
        $this->header = $this->makeHeader($this->getHeaders($this->sign));
        $return_data  = $this->getJson($this->check_url, $this->body, $this->header, true);
        return $return_data;
    }
    /**
     * [query 实名认证结果查询接口]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-05
     * @route    [route]
     * @return   [type]     [description]
     */
    public function query() {
		//需要用预置数据https://wlc.nppa.gov.cn/fcm_company/%E7%BD%91%E7%BB%9C%E6%B8%B8%E6%88%8F%E9%98%B2%E6%B2%89%E8%BF%B7%E5%AE%9E%E5%90%8D%E8%AE%A4%E8%AF%81%E7%B3%BB%E7%BB%9F%E6%B5%8B%E8%AF%95%E7%B3%BB%E7%BB%9F%E8%AF%B4%E6%98%8E.pdf
        $this->ai    = '300000000000000002'; //游戏内部对应的唯一标识建议32位
        $this->bizid = "1101999999";

        $this->initConfig();
        if (empty($this->ai)) {
            die("body请对配置参数赋值");
        }

        //业务参数
        $body_params = [
            'ai' => $this->ai,
        ];
        //string
        $this->sign   = $this->getSign($body_params);
        $this->header = $this->makeHeader($this->getHeaders($this->sign));
        $return_data  = $this->getJson($this->query_url . '?ai=' . $this->ai, '', $this->header, false);
        return $return_data;
    }

    /**
     * [loginout 游戏用户行为数据上报接口]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-05
     * @route    [route]
     * @return   [type]     [description]
     */
    public function loginout() {
        $this->bizid  = "1101999999";
        $this->initConfig();

        $cdata = [

                    //可行 游客
                    'no' => 1,
                    'si' => 'w7ligxjw355ftctm94yqt9dcew4zd723',
                    'bt' =>0,
                    'ot' => time(),
                    'ct' => 2,
                    'di'=>'uyiv6clpf7cu296pd4ppv11le820dhkw',
                    'pi' => '1fffbjzos82bs9cnyj1dna7d6d29zg4esnh99u',
                    // 
                    //可行 已认证
                    // 'no' => 1,
                    // 'si' => 'w7ligxjw355ftctm94yqt9dcew4zd723',
                    // 'bt' =>0,
                    // 'ot' => time(),
                    // 'ct' => 0,
                    // 'pi' => '1fffbjzos82bs9cnyj1dna7d6d29zg4esnh99u',
                ];

        //业务参数
        $body_params = [
            'collections'=>[$cdata],
        ];
        //string
        $this->body   = $this->getBody($body_params);
        $this->sign   = $this->getSign($this->body);
        $this->header = $this->makeHeader($this->getHeaders($this->sign));
        $return_data  = $this->getJson($this->loginout, $this->body, $this->header, true);
        return $return_data;
    }
    /**
     * [接口调用签名]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-05
     * @data    [请求体 json]
     * @return   [type]     [description]
     */
    public function getSign($rawBody) {

        $data = $this->getHeaders();
        if (is_array($rawBody)) {
            $data += $rawBody;
        }
        ksort($data);
        $source = [$this->secret_key];
        foreach ($data as $key => $value) {
            if ($key !== 'sign' && $key != 'Content-Type') {
                $source[] = "{$key}{$value}";
            }
        }

        if (!is_array($rawBody)) {
            $source[] = $rawBody;
        }

        $presign = implode("", $source);
        return hash("sha256", $presign);
    }

    //报文体
    public function getBody($data) {
        $string = Aes::encrypt(json_encode($data), $this->secret_key);
        return json_encode(['data' => $string]);
    }

    public function getHeaders($sign = null) {
        return [
            'Content-Type' => "application/json;charset=utf-8",
            // "Content-type:multipart/form-data",
            // "Content-type:application/x-www-form-urlencoded",
            "appId"        => $this->appid,
            "bizId"        => $this->bizid,
            "timestamps"   => $this->time,
            "sign"         => $sign,
        ];
    }

    public function makeHeader($params) {
        $header = [];
        foreach ($params as $k => $v) {
            $header[] = $k . ': ' . $v;
        }
        return $header;
    }
    /**
     * [businessSign 业务参数 加密传输]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-05
     * @route    [route]
     * @param    [array]     $header [description]
     * @return   [string]             [description]
     */
    public function businessSign() {
        //业务参数
        $en_data = [
            'ai'    => $this->ai,
            'name'  => $this->name,
            'idNum' => $this->id_num,
        ];
        $en_data      = json_encode($en_data);
        $data         = [];
        $data_encrypt = Aes::encrypt($en_data, $this->secret_key);
        $data['data'] = $data_encrypt;
        return json_encode($data);
    }

    /**
     * [getJson description]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-08
     * @route    [route]
     * @param    [string]     $url       [url]
     * @param    string     $post_body [请求数据]
     * @param    array      $headers   [请求头]
     * @param    boolean    $is_post   [请求方式 post:true get:false]
     * @param    boolean    $is_ssl    [description]
     * @return   [array]                [返回值]
     */
    public function getJson($url, $post_body = '', $headers = [], $is_post = true, $is_ssl = false) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $is_ssl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); //设置超时
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); //$headers array
        if ($post_body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body); //$post_body = json串
        }

        $output  = curl_exec($ch);
        $errorno = curl_errno($ch);
        if (!$output || $errorno) {
            return $errorno;
        }
        curl_close($ch);
        return json_decode($output, true);
    }

    public function getMillisecond() {
        list($msec, $sec) = explode(' ', microtime());
        return (float) sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    }
}

/**
 * Class Aes 对称加密
 * version : (PHP 7.1+)
 */
class Aes {

    /**
     * php7.1++
     * [encrypt 加密]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-05
     * @route    [route]
     * @param    string     $data    [加密数据] json串
     * @param    string     $key     [key]
     * @param    string     $type    [解密类型]
     * @param    [type]     $options [description]
     * @param    string     $tag     [引用]
     * @return   [type]              [description]
     */
    public static function encrypt($data, $key, $cipher = 'aes-128-gcm', $options = OPENSSL_RAW_DATA, $tag = null) {

        $encrypt_data = false;
        //必须将key 转成ascii
        $key = hex2bin($key);
        // $options = in_array($options, [OPENSSL_RAW_DATA, OPENSSL_ZERO_PADDING]) ? $options : OPENSSL_RAW_DATA;
        if (in_array($cipher, openssl_get_cipher_methods())) {
            $ivlen        = openssl_cipher_iv_length($cipher);
            $iv           = openssl_random_pseudo_bytes($ivlen);
            $encrypt_data = openssl_encrypt($data, $cipher, $key, $options, $iv, $tag);
            $encrypt_data = base64_encode($iv . $encrypt_data . $tag);
        }
        return $encrypt_data;
		//下面也可以
        // $key     = hex2bin($key);
        // $ivlen   = openssl_cipher_iv_length($cipher);
        // $iv      = openssl_random_pseudo_bytes($ivlen);
        // $encrypt = openssl_encrypt($data, $cipher, $key, $options, $iv, $tag);
        // $encrypt = bin2hex($iv) . bin2hex($encrypt) . bin2hex($tag);
        // return base64_encode(hex2bin($encrypt));
    }

    /**
     * [decrypt 解密]
     * @Author   shyn0121@qq.com
     * @DateTime 2021-03-05
     * @route    [route]
     * @param    string     $data    [解密数据]
     * @param    string     $key     [key]
     * @param    string     $type    [解密类型]
     * @param    [type]     $options [description]
     * @param    string     $tag     [引用]
     * @return   [type]              [json]
     */
    public static function decrypt($data, $key, $cipher = 'aes-128-gcm', $options = OPENSSL_RAW_DATA, $tag = "") {

        $r                  = base64_decode($data);
        $ivlen              = openssl_cipher_iv_length($cipher);
        $key                = hex2bin($key);
        $iv                 = substr($r, 0, 12);
        $tag                = substr($r, -16);
        $ciphertext         = substr($r, $ivlen, -16);
        $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options, $iv, $tag);
        if ($original_plaintext == false) {
            $err = openssl_error_string();
            return $err;
        }
        return $original_plaintext;
    }

}

?>
