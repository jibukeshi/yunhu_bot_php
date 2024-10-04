# 事件订阅

> 参见官方文档 https://www.yhchat.com/document/300-310

事件订阅是系统可以将软件中的消息或其他事件（加入、退出群事件和关注、取关机器人事件）推送到你的服务器中，你的服务器可以根据对应的消息或者事件做出相应的反应。  
推送是通过 HTTP 协议以 POST 请求的方式推送 JSON 格式的数据。

要使用事件订阅，请将你的 PHP 主文件（注意不是 SDK 文件）的 URL 填入机器人控制台的“配置消息订阅接口”中。

本 SDK 已经将原始的 POST 请求体存入 `$raw_data` 变量中，将 JSON 数据存入 `$json_data` 变量中，并且保存了一些常用字段，详见下面的表格。

## 事件列表

| 事件名称 | 介绍 | 取值 |
| --- | --- | --- |
| 普通消息事件 | 普通消息 | message.receive.normal |
| 指令消息事件 | 指令消息 | message.receive.instruction |
| 关注机器人事件 | 关注机器人事件 | bot.followed |
| 取消关注机器人事件 | 取消关注机器人事件 | bot.unfollowed |
| 加入群事件 | 用户加入群事件 | group.join |
| 退出群事件 | 用户退出群事件 | group.leave |
| 按钮事件 | 消息中按钮点击事件 | button.report.inline |
| 机器人设置消息事件 | 群主或管理在群聊的机器人设置中更改设置的事件 | bot.setting |

你可以使用变量 `$event_type` 获取事件类型，并使用 `$event_types` 将事件的取值与名称对应起来。

## 消息事件

### 数据内容

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| version | string | 事件内容版本号 |
| header | header 对象 | 包括事件的基础信息 |
| event | event 对象 | 包括事件的内容。注意：Event 对象的结构会在不同的 eventType 下发生变化 |

### header 对象

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| eventId | string | 事件 ID，全局唯一 |
| eventTime | int | 事件产生的时间，毫秒 13 位时间戳 |
| eventType | string | 事件类型 |

本 SDK 已经将 `eventType `存入 `$event_type` 变量中。

### event 对象

| 字段 | SDK 保存的变量 | 类型 | 说明 |
| --- | --- | --- | --- |
| sender | `$sender` | sender 对象 | 发送者的信息 |
| chat | `$chat` | chat 对象 | 当前聊天的信息 |
| message | `$message` | message 对象 | 消息内容 |

### sender 对象

| 字段 | SDK 保存的变量 | 类型 | 说明 |
| --- | --- | --- | --- |
| senderId | `$sender_id` | string | 发送者 ID，给用户回复消息需要该字段 |
| senderType | `$sender_type` | string | 发送者用户类型，取值：user |
| senderUserLevel | `$sender_user_level` | string | 发送者级别，取值：owner、administrator、member、unknown |
| senderNickname | `$sender_nickname` | string | 发送者昵称 |

你可以使用 `$chat_types` 将发送者级别的取值与名称对应起来。

### chat 对象

| 字段 | SDK 保存的变量 | 类型 | 说明 |
| --- | --- | --- | --- |
| chatId | `$chat_id` | string | 聊天对象 ID |
| chatType | `$chat_type` | string | 聊天对象类型，取值: bot、group |

你可以使用 `$chat_types` 将聊天对象类型的取值与名称对应起来。

### message 对象

| 字段 | SDK 保存的变量 | 类型 | 说明 |
| --- | --- | --- | --- |
| msgId | `$id` | string | 消息ID，全局唯一 |
| parentId | `$message["parentId"]` | string | 引用消息时的父消息 ID |
| sendTime | `$send_time` | int | 消息发送时间，毫秒 13 位时间戳 |
| chatId | `$message["chatId"]` | string | 当前聊天的对象 ID<br>单聊消息，chatId 即对方用户 ID<br>群聊消息，chatId 即群 ID<br>机器人消息，chatId 即机器人 ID |
| chatType | `$message["parentId"]` | string | 当前聊天的对象类型<br>group 群聊<br>bot 机器人 |
| contentType | `$id` | string | 当前消息类型 |
| content | `$content` | 自动匹配 | 消息正文，不同的消息类型返回值不一样，文本、Markdown、html 消息为文本内容，其他消息详见[图片、文件获取方法详解](https://github.com/jibukeshi/yunhu_bot_php/blob/main/docs/file.md) |
| commandId | `$message["commandId"]` | int | 指令 ID，可用来区分用户发送的指令 |
| commandName | `$command` | string | 指令名称，可用来区分用户发送的指令 |

你可以使用 `$content_types` 将消息类型的取值与名称对应起来。

## JSON 结构体示例

### 普通消息、指令消息事件

```
{
    "version":"1.0",
    "header":{
        "eventId":"xxxxx",
        "eventTime":1647735644000,
        "eventType":"xxxxxx"
    },
    "event":{
        "sender":{
            "senderId":"xxxxx",
            "senderType":"user",
            "senderUserLevel":"member",
            "senderNickname":"昵称",
        },
        "chat":{
            "chatId":"xxxxx",
            "chatType":"group"
        },
        "message":{
            "msgId":"xxxxxx",
            "parentId":"xxxx",
            "sendTime":1647735644000,
            "chatId":"xxxxxxxx",
            "chatType":"group",
            "contentType":"text",
            "content":{
                "text":"早上好"
            },
            "commandId":98,
            "commandName":"计算器"
        }
    }
}
```

### 按钮汇报事件

```
{
    "msgId": "xxxxxx",
    "recvId": "xxx",
    "recvType": "bot",
    "time": 1702050343000,
    "userId": "xxx",
    "value": "value"
}
```

-----

更多不同事件的 JSON 结构体示例以及使用示例请加入群聊，在群云盘中下载 `log.txt`，有许多实际请求的日志。

访问链接加入云湖群聊【测试专用群】  
https://yhfx.jwznb.com/share?key=V4TOmiidzNxg&ts=1719712633   
群ID: 257731539