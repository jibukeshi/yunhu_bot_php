# 编辑消息

> 参见官方文档 https://www.yhchat.com/document/400-437

> 本页面中的很多参数可参考发送消息

## 请求函数

`edit($msg_id, $recv_id, $recv_type, $content_type, $content, $buttons = null);`

## 请求参数

| 字段 | 类型 | 是否必填 |
| --- | --- | --- |
| $msg_id | string | 是 |
| $recv_id | string | 是 |
| $recv_type | string | 是 |
| $content_type | string | 是 |
| $content | 自动匹配，参考下面 | 是 |
| $buttons | array | 否 |

### $msg_id 参数

需要编辑的消息 ID

### $recv_id 参数

接收消息对象 ID，**需与原消息保持一致**

### $recv_type 参数

接收对象类型  
用户：`user`  
群：`group`

### $content 参数

#### 文本消息

`$content_type` 为 `text`  
`$content` 类型为 `string`，内容为消息正文

#### Markdown 消息

`$content_type` 为 `markdown`  
`$content` 类型为 `string`，内容为 Markdown 字符串

#### html 消息

`$content_type` 为 `html`  
`$content` 类型为 `string`，内容为 html 代码

#### 图片消息

`$content_type` 为 `image`  
`$content` 类型为 `string`，内容为图片 URL

#### 文件消息

`$content_type` 为 `file`  
`$content` 类型为 `array`，字段见下

| 字段 | 类型 | 是否必填 | 说明 |
| --- | --- | --- | --- |
| name | string | 是 | 文件名 |
| url | string | 是 | 文件URL |

### $buttons 参数

此参数用于消息中的按钮展示，本 sdk 没有对此做过多的处理，直接按照官网的字段填写即可

## 响应内容

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| code | int | 响应代码 |
| message | string | 响应信息，包括异常信息 |
| data | Object | 返回数据 |