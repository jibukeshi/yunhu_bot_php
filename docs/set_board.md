# 设置用户看板

> 参见官方文档 https://www.yhchat.com/document/449-446

## 请求函数

`set_board($recv_id, $recv_type, $content_type, $content);`

## 请求参数

| 字段 | 类型 | 是否必填 |
| --- | --- | --- |
| $recv_id | string | 是 |
| $recv_type | string | 是 |
| $content_type | string | 是 |
| $content | string | 是 |

### $recv_id 参数

接收消息对象 ID

### $recv_type 参数

接收对象类型  
用户: `user`  
群: `group`

### $content_type 参数

消息类型，取值如下  
文本消息：`text`  
Markdown 消息：`markdown`  
html 消息：`html`  

### $content 参数

内容文本

## 响应内容

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| code | int | 响应代码 |
| message | string | 响应信息，包括异常信息 |
| data | Object | 返回数据 |