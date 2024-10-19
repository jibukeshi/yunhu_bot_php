# 上传图片

> 参见官方文档 https://www.yhchat.com/document/400-452

## 请求函数

`upload_image($image);`

## 请求参数

| 字段 | 类型 | 是否必填 |
| --- | --- | --- |
| $image | string | 是 |

### $image 参数

本地图片路径，仅限单个图片，上传图片大小不超过 10MB。  
可配合 `__DIR__ . "/xxx.jpg"` 获取当前 php 文件同目录下的图片文件。

## 响应内容

| 字段 | 类型 | 说明 |
| --- | --- | --- |
| code | int | 响应代码 |
| message | string | 响应信息，包括异常信息 |
| data | Object | 返回数据 |

### 响应示例

```
{
    "code": 1,
    "data": {
        "imageKey": "xxxxxxxxxxxx"
    },
    "msg": "success"
}
```