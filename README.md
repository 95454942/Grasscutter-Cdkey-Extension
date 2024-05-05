## Grasscutter外挂CDKEY兑换系统 v0.1

### 此程序只是半成品，只有一个简易的后端和一些没有完成的函数占位符，需要更多功能的请自行动手，这个版本我会给予自己的需求继续维护，有问题请提issue

> <font size="5" color="red">杜绝将此程序用于商业或者违反中国法律的用途!!!<br>否则我将直接删除此仓库!!!</font>

**此程序基于PHP语言编写，开发环境使用PHP7.4作为解析程序，Mysql版本为5.7**



### 程序原理: 

兑换码DEMO:yuanshen2024CDKEYEND10001

相信聪明的小伙伴已经看出来了 我使用CDKEYEND作为分隔符 分割cdkey和UID，因为我不会java gc的核心源代码我看不懂，也懒得深究 所以直接用此方法逃课

### 使用方法:

1. 在cdkey.php中配置好自己的数据库信息和OpenCommand插件信息，导入db.sql到你的数据库
2. 按照以下格式将你的url填入RegionHandler.java的cdkey_url中:<br>http[s]://replace-to-Your-Website-url/cdkey.php?1=1 为什么要有?1=1  因为游戏客户端请求为CDKEY_URL&参数
3. 玩家使用方法: 使用 **兑换码***CDKEYEND***玩家UID** 作为最终兑换码可在游戏内兑换，末尾的玩家UID是什么 系统就会给谁发奖励，会前端的可以自己写一个页面，让玩家在前端手动设置UID，后端CDKEY生成不要带后缀和分隔符，那是给程序解析用的！！！
4. **GC必须启用openCommand插件**
5. 运行程序
<hr >
引用的项目:

[gc-opencommand-plugin](https://github.com/jie65535/gc-opencommand-plugin) <br>
[PHP Curl Class](https://github.com/php-curl-class/php-curl-class)