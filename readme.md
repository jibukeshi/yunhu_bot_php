# 使用 PHP 编写的云湖机器人 SDK

请点个 ⭐Star 吧，让我看看有多少人在用

有 bug 可以在 issue 里提，也可以在群聊里发；要帮助请在群里询问。

更多内容请访问链接加入云湖群聊【测试专用群】  
https://yhfx.jwznb.com/share?key=V4TOmiidzNxg&ts=1719712633  
群ID: 257731539

## 特点

1. 将每个功能封装到函数中，方便使用
2. 保存了消息订阅中的常用字段，方便开发
3. 自带调试模式，可写入日志
4. 使用 PHP 编写，运行环境要求低，虚拟主机也可运行机器人
5. 有云湖测试群聊，可以测试你的机器人、获取开发帮助等

## 使用方法

请将你的机器人主逻辑放在 PHP 主文件中，并将 PHP 主文件（注意不是 SDK 文件）的 URL 填入机器人控制台的“配置消息订阅接口”中。

主文件的开头请这样编写

```
$bot_token = "xxx"; // 机器人的Token
$debug_mode = true; // 调试模式，可根据实际需求打开或关闭
require __DIR__ . "/sdk.php"; // 引入 SDK，勿删
```

之后按照各个功能的教程编写即可。

### 消息管理

[发送消息](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/send.md)

[编辑消息](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/edit.md)

[撤回消息](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/recall.md)

[消息列表](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/messages.md)

### 看板管理

[设置用户看板](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/set_board.md)

[设置全局看板](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/set_board_all.md)

[取消用户看板](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/unset_board.md)

[取消全局看板](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/unset_board_all.md)

### 事件订阅

[事件订阅（机器人收消息）](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/receive.md)

## 使用了 SDK 的机器人

待添加