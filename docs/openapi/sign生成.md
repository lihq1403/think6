# 签名生成规则

#### 简要描述：

1. timestamp+randomstr+key组成数组
2. 数组进行字段升序，变成字符串
3. md5之后再sha1

- key需要询问客户获取，key和appid是一一对应的

#### php生成例子

示例代码：
```
public static function createSign(int $timestamp, string $random_str, string $key)
{
	// 去空
	$params = [
		'timestamp' => $timestamp,
		'randomstr' => $random_str,
		'key' => $key
	];
	// 字典升序
	$sign = self::formatParaMap($params);
	// md5
	$sign = md5($sign);
	// sha1
	$sign = sha1($sign);
	return $sign;
}
```

1. 组装数组
```
array:3 [
  "timestamp" => 1576720983
  "randomstr" => "111"
  "key" => "123"
]
```

2. 数组进行字段升序
```
key=123&randomstr=111&timestamp=1576720983
```

3. md5
```
1d0afc31f922519c7d79419fc20330ad
```

4. sha1
```
07caa2ca7c0cced165e1be6e409cab344943723d
```