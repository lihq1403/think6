<?php


namespace app\common\containers;

use app\common\exception\SystemErrorException;
use app\common\exception\UnauthorizedHttpException;
use Firebase\JWT\JWT;
use think\facade\Request;
use think\facade\Cache;

class JwtTool
{
    /**
     * 过期时间秒数
     *
     * @var int
     */
    public static $expires = 7200;

    /**
     * 刷新过期时间
     * @var int
     */
    public static $refresh_expires = 2592000;

    /**
     * The header prefix.
     *
     * @var string
     */
    protected $prefix = 'bearer';

    /**
     * The header name.
     *
     * @var string
     */
    protected $header = 'authorization';

    /**
     * 密钥加盐
     * @var string
     */
    protected $salt = '';

    /**
     * 使用场景, 可用来区分不同数据
     * @var string
     */
    protected $scene = '';

    /**
     * JwtTool constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     * @throws SystemErrorException
     * @throws UnauthorizedHttpException
     */
    final function authenticate()
    {
        return $this->certification($this->getClientInfo());
    }

    /**
     * 获取客户端信息
     * @return mixed
     * @throws UnauthorizedHttpException
     *
     */
    protected function getClientInfo()
    {
        $jwt_encode = Request::header($this->header);
        if (empty($jwt_encode)) {
            throw new UnauthorizedHttpException();
        }
        if (preg_match('/' . $this->prefix . '\s*(.*)\b/i', $jwt_encode, $matches)) {
            $jwt_encode = $matches[1];
        }
        return $jwt_encode;
    }

    /**
     * @param string $token
     * @return mixed
     * @throws SystemErrorException
     * @throws UnauthorizedHttpException
     */
    protected function certification(string $token = '')
    {
        $res = $this->verification($token);
        return $res['data'];
    }

    /**
     * 签发token
     * @param $uid
     * @param int $expires
     * @return string
     * @throws SystemErrorException
     *
     */
    public function IssueToken(int $uid, string $scopes = 'access_token', int $expires = 0)
    {
        if (empty($expires)) {
            $expires = $this->getExpires();
        }
        $key = $this->getKey();
        $time = time(); //当前时间
        $token = [
            'iss' => '', //签发者 可选
            'aud' => '', //接收该JWT的一方，可选
            'iat' => $time, //签发时间
            'nbf' => $time, //(Not Before)：某个时间点后才能访问，比如设置time+30，表示当前时间30秒后才能使用
            'exp' => $time + $expires, //过期时间
            'ref' => $time + $this->getRefreshExpires(),
            'scopes' => $scopes,
            'data' => [ //自定义信息，不要定义敏感信息
                'uid' => $uid
            ]
        ];
        return JWT::encode($token, $key);
    }

    /**
     * 获取刷新token
     * @param $uid
     * @return string
     * @throws SystemErrorException
     */
    public function IssueRefreshToken(int $uid)
    {
        return $this->IssueToken($uid, 'refresh_token', $this->getRefreshExpires());
    }

    /**
     * 验证token
     * @param $jwt
     * @return array
     * @throws SystemErrorException
     * @throws UnauthorizedHttpException
     */
    protected function verification(string $jwt)
    {
        //检验是否为注销token
        if (Cache::get('logout_token:' . $this->getClientInfo())) {
            throw new UnauthorizedHttpException();
        }
        $key = $this->getKey();
        JWT::$leeway = 60;//当前时间减去60，把时间留点余地
        $decoded = JWT::decode($jwt, $key, ['HS256']); //HS256方式，这里要和签发的时候对应
        $arr = (array)$decoded;
        return $arr;
    }

    /**
     * @return mixed|string
     * @throws SystemErrorException
     */
    protected function getKey()
    {
        $jwt_secret = config('jwt.jwt_secret');
        if (empty($jwt_secret)) {
            throw new SystemErrorException('尚未配置jwt密钥');
        }
        return $jwt_secret . $this->scene . $this->salt;
    }

    /**
     * 设置盐
     * @param string $salt
     * @return $this
     */
    public function setSalt(string $salt = '')
    {
        $this->salt = $salt;
        return $this;
    }

    /**
     * 设置场景
     * @param string $scene
     * @return $this
     */
    public function setScene(string $scene = '')
    {
        $this->scene = $scene;
        return $this;
    }

    /**
     * 获取有效期
     * @return int|mixed
     */
    protected function getExpires()
    {
        $config_auth_expires = config('jwt.auth_expires');
        if (!empty($config_auth_expires)) {
            $expires = $config_auth_expires;//加载配置
        } else {
            $expires = self::$expires;//默认设置2个小时
        }
        return (int)$expires;
    }

    /**
     * 刷新时间
     * @return int
     */
    protected function getRefreshExpires()
    {
        $expires = config('jwt.refresh_expires');
        if (empty($expires)) {
            $expires = self::$refresh_expires; // 默认30天
        }
        return (int)$expires;
    }

    /**
     * @param $uid
     * @return array
     * @throws SystemErrorException
     */
    public function jsonReturnToken(int $uid)
    {
        return [
            'access_token' => $this->IssueToken($uid),
            'refresh_token' => $this->IssueRefreshToken($uid),
            'token_type' => 'Bearer',
            'expires_in' => $this->getExpires()
        ];
    }

    /**
     * 关进注销小黑屋
     * @throws UnauthorizedHttpException
     */
    public function logout()
    {
        //加入注销缓存
        $token = $this->getClientInfo();
        Cache::set('logout_token:'.$token, $token, $this->getExpires());
    }
}