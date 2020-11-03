## 介绍
QuarkAdmin 是一个可以帮你快速搭建管理后台的工具；它提供的丰富组件，能帮助您使用很少的代码就能搭建出功能完善的管理后台。

## 系统特性

**内置功能**
* 管理员管理
* 用户管理
* 权限系统
* 菜单管理
* 系统配置
* 操作日志
* 附件管理

**内置组件**
* Layout组件
* Container组件
* Card组件
* Table组件
* Form组件
* Show组件
* TabForm组件
* ...

## 安装

需要安装PHP7.2+ 和 Laravel6.0+，首先确保安装好了laravel，并且数据库连接设置正确。

``` bash
# 第一步，安装依赖
composer require quarkcms/quark-admin

# 第二步，然后运行下面的命令来发布资源：
php artisan quarkadmin:publish

# 第三步，然后运行下面的命令完成安装：
php artisan quarkadmin:install
```

运行命令的时候，如果遇到了下面的错误:

SQLSTATE[42000]: Syntax error or access violation: 1071 Specified key was too long; max key length is 1000 bytes ...

您可以找到 config 目录下的 database.php 文件，进行更改：
``` php
// 将 strict 改为 false
'strict' => false,
// 将 engine 改为 'InnoDB'
'engine' => 'InnoDB',
```

完成安装后，执行如下命令，快速启动服务：
``` bash
php -S 127.0.0.1:8080 -t ./public
```
后台地址： http://127.0.0.1:8080/admin/index

默认用户名：administrator 密码：123456

## 技术支持
为了避免打扰作者日常工作，你可以在Github上提交[Issues](https://github.com/quarkcms/quark-admin/issues)

相关教程，你可以查看[在线文档](http://www.quarkcms.com/quark-admin/)

## License
QuarkAdmin is licensed under The MIT License (MIT).