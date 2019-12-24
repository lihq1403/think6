# 系统

## 思维导图地址
地址：
密码：

## 开发说明

获取前台用户
```php
HomeAuth::user()
```


前台用户基本基本都在 ```app\common\tools\HomeTools``` 里面

前台使用jwt认证机制

后台使用jwt认证机制

## 安装说明
```php
composer install
```
会自动复制.env.example为.env
.env 是配置文件

命令行可以直接生成一个长时间有效的jwt，参数后面的数字为用户uid
```php
php think jwt:generate_user 1
```

## 开发模式

统一使用 Restful HTTP API 路由模式
```php
GET /issues                                      列出所有的 issue
GET /orgs/:org/issues                            列出某个项目的 issue
GET /repos/:owner/:repo/issues/:number           获取某个项目的某个 issue
POST /repos/:owner/:repo/issues                  为某个项目创建 issue
PATCH /repos/:owner/:repo/issues/:number         修改某个 issue
PUT /repos/:owner/:repo/issues/:number/lock      锁住某个 issue
DELETE /repos/:owner/:repo/issues/:number/lock   解锁某个 issue
```

由于想要统一接收参数，所以我用的如下：
```php
// 文章管理
Route::post('article', 'admin/article/store'); // 添加文章
Route::put('article', 'admin/article/update'); // 编辑文章
Route::get('article', 'admin/article/show'); // 文章详情
Route::get('articles', 'admin/article/index'); // 文章列表
Route::delete('article', 'admin/article/destroy'); // 文章删除
```
参数都是通过统一方法接收
```php
$params = $this->_apiParam(['code', 'state']);
```
成功返回
```php
return $this->successResponse('msg', []);
```
失败，使用异常抛出，具体抛出异常可以在```app\common\exception\Http```里面接收异常并进行处理，然后响应
```php
throw new CommonException('msg');
```

代码风格 一般 遵循 [PSR-2](https://github.com/hfcorriez/fig-standards/blob/zh_CN/%E6%8E%A5%E5%8F%97/PSR-2-coding-style-guide.md) 规范

1. class名与class的方法尽量用驼峰法命名法，protect与private的方法与变量用‘_’做前缀。注释必须给出属性
2. 普通function函数用下划线命名法
3. CLASS与FUNCTION的左括号新起一行
4. 尽量使用对象化语言，少使用thinkphp5的input（）等快捷方法，而且使用request对象
5. 尽量追求phpstorm右上角有绿色打勾，页面内没有异常着色
6. 写完代码请按【Ctrl+Alt+L】格式化代码

