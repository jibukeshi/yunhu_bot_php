# 机器人发送消息

> 参见官方文档 https://www.yhchat.com/document/400-410 和 https://www.yhchat.com/document/400-421

## 请求函数

`send($recv_id, $recv_type, $content_type, $content, $buttons = null);`

## 请求参数

| 字段 | 类型 | 是否必填 |
| --- | --- | --- |
| $recv_id | 自动匹配，参考下面 | 是 |
| $recv_type | string | 是 |
| $content_type | string | 是 |
| $content | 自动匹配，参考下面 | 是 |
| $buttons | array | 否 |

### $recv_id 参数

#### 发送单条消息

`$recv_id` 类型为 `string`，内容为接收消息对象 ID

#### 批量发送消息

`$recv_id` 类型为 `array`，内容为接收消息对象 ID 列表

### $recv_type 参数

接收对象类型  
用户: user  
群: group

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

| 字段 | 类型 | 是否必填 | 说明 |
| --- | --- | --- | --- |
| text | string | 是 | 按钮上的文字 |
| actionType | int | 是 | 1: 跳转URL<br>2: 复制<br>3: 点击汇报 |
| url | string | 否 | 当actionType为1时使用 |
| value | string | 否 | 当actionType为2时，该值会复制到剪贴板<br>当actionType为3时，该值会发送给订阅端 |

## 响应内容

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| code | int | 响应代码 |
| message | string | 响应信息，包括异常信息 |
| data | Object | 返回数据 |

## 完整示例

给 ID 为 `7058262` 的用户发送一条文本消息，内容为`这里是消息内容`，两个按钮，第一个按钮点击复制 `xxxx`，第二个按钮点击跳转 `http://www.baidu.com`，完整的代码为

```
send("7058262", "user", "text", "这里是消息内容", array(
    array(
        "text" => "复制",
        "actionType" => 2,
        "value" => "xxxx"
    ),
    array(
        "text" => "点击跳转",
        "actionType" => 1,
        "value" => "http://www.baidu.com"
    )
));
```