# 图片、文件获取方法详解

自从 2024-10-04 更新的版本起，采用了新的方法从云湖 CDN 获取图片、文件、视频、音频等。

如需使用反代，请使用 https://github.com/jibukeshi/yunhu_cdn_worker 搭建，并将其域名填入到下文的反代域名中。

请在主文件的开头添加以下变量以选择获取的模式。若不填或填写错误将采用默认方法获取。

## 图片获取

请将图片获取模式的选择放在 `$img_mode` 变量中。

| `$img_mode` 的取值 | 说明 | `$content` 返回示例 |
| --- | --- | --- |
| 0 | 直接获取 content 的 array | `{"imageUrl":"https://chat-storage1.jwznb.com/60d583a02be3eda7c5c321d5fdc26d57.jpg?sign=4970416e3a364f5ef1be8734664dad4c\u0026t=66c1d120","imageName":"60d583a02be3eda7c5c321d5fdc26d57.jpg","etag":"FsCZiw8KEYserpsznnJe6PDrZhlE"}` |
| 1（默认） | 获取 chat-img.jwznb.com 的图片链接，永久有效 | `https://chat-img.jwznb.com/60d583a02be3eda7c5c321d5fdc26d57.jpg` |
| 2 | 获取 imageName，自行拼接使用 | `60d583a02be3eda7c5c321d5fdc26d57.jpg` |
| 3 | 获取官方提供的 imageUrl，带 sign 参数、有有效期 | `https://chat-storage1.jwznb.com/60d583a02be3eda7c5c321d5fdc26d57.jpg?sign=4970416e3a364f5ef1be8734664dad4c\u0026t=66c1d120` |
| 域名 | 使用自己的反代链接 | `https://chat-img.sub.example.com/60d583a02be3eda7c5c321d5fdc26d57.jpg` |

## 文件、视频、音频获取

请将文件获取模式的选择放在 `$file_mode` 变量中。

| `$file_mode`的取值 | 说明 | `$content` 返回示例 |
| --- | --- | --- |
| 0 | 直接获取 content 的 array | `{"fileName":"sbsb.zip","fileUrl":"42436bcfa610aaed7ec3b82e6f47c8f1.zip","etag":"FvlEN1GFP5ADH_VOMm6qjlFp_EfH","fileSize":1146}` |
| 1（默认） | 获取 chat-file.jwznb.com 的文件链接，注意需要添加 Referer 为 `http://myapp.jwznb.com/`，否则会 403 | `https://chat-file.jwznb.com/42436bcfa610aaed7ec3b82e6f47c8f1.zip` |
| 2 | 获取 fileUrl，自行拼接使用 | `42436bcfa610aaed7ec3b82e6f47c8f1.zip` |
| 域名 | 使用自己的反代链接 | `https://chat-file.sub.example.com/42436bcfa610aaed7ec3b82e6f47c8f1.zip` |

视频、音频的获取与文件共用 `$file_mode` 变量，与之不同的是，视频的域名为 `chat-video1.jwzmb.com`，音频的域名为 `chat-audio1.jwznb.com`。

## 表情获取

请将表情获取模式的选择放在 `$expression_mode` 变量中。

| `$expression_mode`的取值 | 说明 | `$content` 返回示例 |
| --- | --- | --- |
| 0 | 直接获取 content 的 array | `{"imageName":"sticker/5044953f41b1153b3437d4e0e3adad8769fc3aee.gif","expressionId":"0","stickerId":9416,"stickerPackId":415}` |
| 1（默认） | 获取 chat-img.jwznb.com 的图片链接，永久有效 | `https://chat-img.jwznb.com/sticker/5044953f41b1153b3437d4e0e3adad8769fc3aee.gif` |
| 2 | 获取 imageName，自行拼接使用 | `sticker/5044953f41b1153b3437d4e0e3adad8769fc3aee.gif` |
| 域名 | 使用自己的反代链接 | `https://chat-img.sub.example.com/sticker/5044953f41b1153b3437d4e0e3adad8769fc3aee.gif` |