# YM域名解析管理系统
## 简介
域名DNS解析集中管理，解决多个域名解析管理难题

目前支持腾讯云（dnspod）、阿里云（万网）、Cloudflare

后续支持平台逐步开发中，如有需要或bug请提issue，有时间会尽快更新
### 已实现功能
添加平台，查询、删除、添加、修改域名解析，系统登录二步验证
***
## 安装
### 准备工作
1. PHP版本≥8.1
2. MySQL版本≥5.6
3. 需安装PHP扩展：'zip', 'openssl', 'gd', 'curl', 'mysqli'
### 开始安装
#### 1.直接部署
将本程序解压到网站根目录，访问`yourdomain/install`安装，安装成功后即可访问`yourdomain`使用，默认账号admin，默认密码123456

安装完毕后自动生成`install.lock`文件，防止覆盖安装
#### 2.docker部署
请等待更新
### 常见问题
请查阅文档
***
## 使用
官方网站[YM域名解析管理系统](http://rm.yinmai.asia)

使用文档[YM文档](https://wiki.yinmai.asia)
***
## 未来计划
1. 支持更多平台
2. 开放相关API
3. 完善系统相关功能（操作验证、邮件通知等）
4. 支持更多域名操作
5. 基于本系统开发二级域名售卖系统
***
## 其他
感谢[笔下光年](http://www.bixiaguangnian.com/)开发的[光年模板（V5）](http://www.bixiaguangnian.com/manual/lyearadmin5.html)

