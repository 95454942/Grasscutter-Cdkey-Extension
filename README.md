## Grasscutter外挂CDKEY兑换系统 v0.2

### 此程序只是半成品，只有一个简易的后端和一些没有完成的函数占位符，需要更多功能的请自行动手，这个版本我会给予自己的需求继续维护，有问题请提issue

> 请勿用于非法用途

**此程序基于PHP语言编写，开发环境使用PHP7.4作为解析程序，Mysql版本为5.7**


兑换码DEMO:yuanshen
### Items配置:
数据库中cdkeys表有一个items字段 其中数据应为json array格式 内容格式应为:["物品id 物品数量 物品等级"[,["物品id 物品数量 物品等级"]....]<br>
**e.g.** 
[
    "102 120000 1",
    "201 6480 1",
    "1073 1 90"
]
物品内容为:等级经验120000 原石6480 纳西妲x1 90级

### 兑换码生成API：
https:/你的域名/cdkey.php?action=gencdkey&count=cdkey数量
目前这个还没有做鉴权 请勿对外公开，action的关键字可自己在action.php中自行修改
### 配置方法:

1. 在cdkey.php中配置好自己的数据库信息和OpenCommand插件信息，导入db.sql到你的数据库
2. 在configs.php中正确配置你的数据库账号密码以及consolekey
3. 按照以下格式将你的url填入RegionHandler.java的cdkey_url中:
   cdkeyAPI地址?sign_type=2&auth_appid=apicdkey&authkey_ver=1&server=Game_main
4. **GC必须启用openCommand插件**
5. 运行程序
<hr >
引用的项目:

[gc-opencommand-plugin](https://github.com/jie65535/gc-opencommand-plugin) <br>
[PHP Curl Class](https://github.com/php-curl-class/php-curl-class)