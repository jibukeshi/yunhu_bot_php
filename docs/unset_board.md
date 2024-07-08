# 取消用户看板

> 参见官方文档 https://www.yhchat.com/document/449-448

## 请求函数

`unset_board($recv_id, $recv_type)`

## 请求参数

| 字段 | 类型 | 是否必填 |
| --- | --- | --- |
| $recv_id | string | 是 |
| $recv_type | string | 是 |

### $recv_id 参数

接收消息对象 ID

### $recv_type 参数

接收对象类型  
用户: `user`  
群: `group`

## 响应内容

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| code | int | 响应代码 |
| message | string | 响应信息，包括异常信息 |
| data | Object | 返回数据 |