# 撤回消息

> 参见官方文档 https://www.yhchat.com/document/400-451

## 请求函数

`recall($msg_id, $chat_id, $chat_type);`

## 请求参数

| 字段 | 类型 | 是否必填 |
| --- | --- | --- |
| $msg_id | string | 是 |
| $chat_id | string | 是 |
| $chat_type | string | 是 |

### $msg_id 参数

需要撤回的消息 ID

### $chat_id 参数

消息对象 ID

### $chat_type 参数

消息对象类型  
用户：`user`  
群：`group`

## 响应内容

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| code | int | 响应代码 |
| message | string | 响应信息，包括异常信息 |
| data | Object | 返回数据 |