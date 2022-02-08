<h1 align="center"> laravel-express </h1>

<p align="center">基于快递100做个适配laravel的实时快递查询、快递查询地图轨迹组件</p>


## 前提
在使用本扩展之前，你需要去 [快递100开放平台](https://api.kuaidi100.com/) 快递100开放平台注册账号，并开通相关接口权限<br>
1、实时查询接口<br>
2、快递查询地图轨迹<br>

## 安装、发布配置文件

```shell
$ composer require walkerdistance/laravel-express -vvv

$ php artisan vendor:publish --tag=express
```

## 使用
1、发布完成配置文件后(config/express.php) 设置配置文件中的相关参数<br>
```shell
//服务访问
$data = app('expressInquiry')->getPollQuery('快递单号');
or
$data = app(ExpressInquiry::class)->getPollQuery('快递单号');
```
2、直接使用
```shell
$express = new ExpressInquiry([
    'customer' => '***********************',
    'api_key' => '***********',
]);
$data = $express->getPollQuery('快递单号');
```