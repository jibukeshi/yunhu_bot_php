# 消息列表

> 参见官方文档 https://www.yhchat.com/document/400-450

## 请求函数

`messages($chat_id, $chat_type, $message_id = null, $before = null, $after = null);`

## 请求参数

| 字段 | 类型 | 是否必填 |
| --- | --- | --- |
| $chat_id | string | 是 |
| $chat_type | string | 是 |
| $message_id | string | 否 |
| $before | int |否是 |
| $after | int | 否 |

### $chat_id 参数

获取消息对象 ID

### $chat_type 参数

获取消息对象类型  
用户：`user`  
群：`group`

### $message_id 参数

指定的消息 ID，不填时可以配合 `$before` 参数返回最近的 N 条消息

### $before 参数

指定消息 ID 前 N 条，默认 0 条

### $after 参数

指定消息 ID 后 N 条，默认 0 条

## 响应内容

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| code | int | 响应代码 |
| message | string | 响应信息，包括异常信息 |
| data | Object | 返回数据 |

## 请求示例

获取群ID【xxx】中最新 10 条消息，共返回 10 条消息

```
$result = messages("xxx", "group", null, 10, null);
```

获取群ID【xxx】中消息 ID【xxxx】前 10 条消息，共返回 11 条消息

```
$result = messages("xxx", "group", "xxxx", 10, null);
```

获取群ID【xxx】中消息 ID【xxxx】后 10 条消息，共返回 11 条消息

```
$result = messages("xxx", "group", "xxxx", null, 10);
```

获取群ID【xxx】中消息 ID【xxxx】前后各 10 条消息，共返回 21 条消息

```
$result = messages("xxx", "group", "xxxx", 10, 1p);
```

获取用户ID【xxx】中消息 ID【xxxx】前 10 条消息，共返回 11 条消息

```
$result = messages("xxx", "user", "xxxx", 10, null);
```

## 响应示例

```
{
    "code": 1,
    "data": {
        "list": [
            {
                "msgId": "dad25257f71f41098f733a5079183080",
                "parentId": "",
                "senderId": "7999713",
                "senderType": "user",
                "senderNickname": "NH₃·H₂O",
                "contentType": "markdown",
                "content": {
                    "text": "#不要潜水快来嗨皮啊"
                },
                "sendTime": 1709102439694,
                "commandName": "",
                "commandId": 0
            },
            {
                "msgId": "672d5171b9474c7e870ac311361ac85b",
                "parentId": "",
                "senderId": "8380181",
                "senderType": "user",
                "senderNickname": "梵高",
                "contentType": "text",
                "content": {
                    "text": "是在潜水"
                },
                "sendTime": 1708751609482,
                "commandName": "",
                "commandId": 0
            }
        ],
        "total": 2
    },
    "msg": "success"
}
```