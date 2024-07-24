<?php

// 事件列表
$event_types = array(
    "message.receive.normal" => "普通消息",
    "message.receive.instruction" => "指令消息",
    "bot.followed" => "关注机器人",
    "bot.unfollowed" => "取消关注机器人",
    "group.join" => "加入群",
    "group.leave" => "退出群",
    "button.report.inline" => "按钮事件",
    "bot.setting" => "机器人设置消息事件" // 没想到吧还有这个东西
);
// 发送者级别列表
$sender_levels = array(
    "owner" => "群主",
    "administrator" => "管理员",
    "member" => "成员",
    "unknown" => "未知"
);
// 聊天类型列表
$chat_types = array(
    "group" => "群聊",
    "bot" => "机器人"
);
// 消息类型列表
$content_types = array(
    "text" => "文本消息",
    "image" => "图片消息",
    "markdown" => "Markdown消息",
    "file" => "文件消息",
    // 下面这些官网的文档里没写，是自己发现的
    "html" => "html消息",
    "expression" => "表情消息",
    "post" => "帖子消息",
    "form" => "表单消息", //自定义指令的表单消息
    "unknown" => "未知消息" //可能是语音或视频消息
);

// 获取原始 post 请求体
$raw_data = file_get_contents("php://input");
if ($raw_data == null) {
    echo "这个 php 不是让你在浏览器里面打开的啊";
    exit;
}
$json_data = json_decode($raw_data, true);
// 事件类型，参考 $event_types
$event_type = $json_data["header"]["eventType"];
// 发送者信息
$sender = $json_data["event"]["sender"];
$sender_id = $sender["senderId"]; // 发送者 ID
$sender_type = $sender["senderType"]; // 发送者用户类型
$sender_user_level = $sender["senderUserLevel"]; // 发送者级别，参考 $sender_levels
$sender_nickname = $sender["senderNickname"]; // 发送者昵称
// 聊天信息
$chat = $json_data["event"]["chat"];
$chat_id = $chat["chatId"]; // 聊天对象 ID
$chat_type = $chat["chatType"]; // 聊天对象类型
// 消息信息
$message = $json_data["event"]["message"];
$id = $message["msgId"]; // 消息 ID，全局唯一
$send_time = $message["sendTime"]; // 消息发送时间，毫秒 13 位时间戳
$content_type = $message["contentType"]; // 获取消息类型
// 获取消息内容
if (in_array($content_type, ["text", "markdown", "html", "post"])) {
    // 如果是文本、Markdown、html、帖子消息
    $content = $message["content"]["text"]; // 获取文本消息的内容
}
elseif ($content_type == "image") {
    // 如果是图片消息
    $content = $message["content"]["imageUrl"]; // 获取图片 URL
}
elseif ($content_type == "file") {
    // 如果是文件消息
    $content = "https://chat-file.jwznb.com/" . $message["content"]["fileUrl"]; // 获取文件 URL，但是会 403，我也没办法了
}
elseif ($content_type == "expression") {
    // 如果是表情消息
    $content = "https://chat-img.jwznb.com/" . $message["content"]["imageName"]; // 获取表情 URL，推送的链接不全，必须手动补全域名
}
elseif ($content_type == "form") {
    // 如果是自定义指令的表单消息
    $content = $message["content"]["formJson"]; // 只能帮你到这一步了，后面自己获取吧，注意这里获取到的是 Array
}
elseif ($content_type == "unknown") {
    // 注意视频消息和音频消息都会显示为 unknown，这里要根据有没有 videoUrl 或者 audioUrl 做区分
    if (is_string($message["content"]["videoUrl"])) {
        // 如果是视频消息
        $content = "https://chat-video1.jwznb.com/" . $message["content"]["videoUrl"]; // 获取视频 URL，但是会 403，我也没办法了
    }
    elseif (is_string($message["content"]["audioUrl"])) {
        // 如果是音频消息
        $content = "https://chat-audio1.jwznb.com/" . $message["content"]["audioUrl"]; // 获取音频 URL，但是会 403，我也没办法了
    }
    // 如果这些都不是我也帮不上了
}
$command = $message["commandName"]; // 命令名称

// 设置原路返回的对象，以方便发送
if ($chat_type == "bot") {
    // 如果为私聊则设置发送对象为发送者
    $recv_id = $sender_id;
    $recv_type = $sender_type;
}
elseif ($chat_type == "group") {
    // 如果在群聊中则设置发送对象为群聊
    $recv_id = $chat_id;
    $recv_type = $chat_type;
}
elseif (in_array($event_type, ["bot.followed", "bot.unfollowed"])) {
    // 如果是关注或取消关注机器人消息则设置发送对象为发送者
    $chat_id = $json_data["event"]["chatId"];
    $chat_type = $json_data["event"]["chatType"];
    // 这里顺便获取下用户信息
    $recv_id = $sender_id = $user_id = $json_data["event"]["userId"]; // 获取用户 ID
    $recv_type = $sender_type = "user";
    $nickname = $sender_nickname = $json_data["event"]["nickname"]; // 获取用户昵称
    $avatar_url = $json_data["event"]["avatarUrl"]; // 获取用户头像
}
elseif (in_array($event_type, ["group.join", "group.leave"])) {
    // 如果是加群或退群消息则自动获取发送对象
    $recv_id = $chat_id = $json_data["event"]["chatId"];
    $recv_type = $chat_type = $json_data["event"]["chatType"];
    // 这里顺便获取下用户信息
    $user_id = $sender_id = $json_data["event"]["userId"]; // 获取用户 ID
    $nickname = $sender_nickname = $json_data["event"]["nickname"]; // 获取用户昵称
    $avatar_url = $json_data["event"]["avatarUrl"]; // 获取用户头像
}
elseif ($event_type == "bot.setting") {
    // 如果是机器人设置消息则设置发送对象为群聊
    $chat_id = $json_data["event"]["chatId"];
    $chat_type = $json_data["event"]["chatType"];
    $recv_id = $group_id = $json_data["event"]["groupId"]; // 获取群聊 ID
    $recv_type = "group";
    $group_name = $json_data["event"]["groupName"]; // 获取群聊名称
    $avatar_url = $json_data["event"]["avatarUrl"]; // 获取群聊头像
    $setting_json = $json_data["event"]["settingJson"]; // 获取设置 JSON
}

// 请求 API 封装
function send_request($tool, $send_data) {
    global $bot_token, $debug_mode, $log_file; // 获取全局变量中的 Token、调试模式开关和日志文件名
    $send_body = json_encode($send_data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); // 保留原始的 Unicode 字符和斜杠不转义
    $send_header = array("Content-Type: application/json; charset=utf-8"); // 请求头
    $send_url = "https://chat-go.jwzhd.com/open-apis/v1/bot/{$tool}?token={$bot_token}";
    $send = curl_init();
    curl_setopt($send, CURLOPT_URL, $send_url); // 设置 URL
    curl_setopt($send, CURLOPT_POST, true);
    curl_setopt($send, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($send, CURLOPT_POSTFIELDS, $send_body); // 设置请求体
    curl_setopt($send, CURLOPT_HTTPHEADER, $send_header); // 设置请求头
    $back_data = curl_exec($send);
    // 调试模式，把每个 API 的请求体和响应体都写入日志
    if ($debug_mode) {
        write_log($log_file, "请求 URL：{$send_url} | 请求体：{$send_body} | 响应体：{$back_data}\n");
    }
    curl_close($send);
    return $back_data;
}

// 发送消息封装（支持批量发送消息）
// 5 个参数分别是 接收消息对象 ID、接收对象类型、消息类型、消息对象、按钮
function send($recv_id, $recv_type, $content_type, $content, $buttons = null) {
    $send_data = array(); // 初始化请求体
    // 判断是不是批量发送
    if (is_array($recv_id)) {
        // 如果是批量发送
        $tool = "batch_send";
        $send_data["recvIds"] = $recv_id;
    } else {
        // 如果不是批量发送
        $tool = "send";
        $send_data["recvId"] = $recv_id;
    }
    $send_data["recvType"] = $recv_type; // 设置接收对象类型
    $send_data["contentType"] = $content_type; // 设置消息类型
    $send_data["content"] = array(); // 初始化消息对象
    // 设置消息对象
    if (in_array($content_type, ["text", "markdown", "html"])) {
        // 如果是文本、Markdown、html 消息
        $send_data["content"]["text"] = $content; // 设置消息正文
    }
    elseif ($content_type == "image") {
        // 如果是图片消息
        $send_data["content"]["imageUrl"] = $content; // 设置图片 URL
    }
    elseif ($content_type == "file") {
        // 如果是文件消息
        $send_data["content"]["fileName"] = $content["name"]; // 设置文件名
        $send_data["content"]["fileUrl"] = $content["url"]; // 设置文件 URL
    }
    else {
        exit("无效的数据类型" . $content_type);
    }
    if (is_array($buttons)) {
        // 如果有按钮
        $send_data["content"]["buttons"] = $buttons;
    }
    $back_data = send_request($tool, $send_data);
    return $back_data;
}

// 编辑消息封装，基本可以照抄发送消息
// 6 个参数分别是 消息 ID、接收消息对象 ID、接收对象类型、消息类型、消息对象、按钮
function edit($msg_id, $recv_id, $recv_type, $content_type, $content, $buttons = null) {
    $send_data = array(); // 初始化请求体
    $send_data["msgId"] = $msg_id; // 设置消息 ID
    $send_data["recvId"] = $recv_id; // 设置接收消息对象 ID
    $send_data["recvType"] = $recv_type; // 设置接收对象类型
    $send_data["contentType"] = $content_type; // 设置消息类型
    $send_data["content"] = array(); // 初始化消息对象
    // 设置消息对象
    if (in_array($content_type, ["text", "markdown", "html"])) {
        // 如果是文本、Markdown、html 消息
        $send_data["content"]["text"] = $content; // 设置消息正文
    }
    elseif ($content_type == "image") {
        // 如果是图片消息
        $send_data["content"]["imageUrl"] = $content; // 设置图片 URL
    }
    elseif ($content_type == "file") {
        // 如果是文件消息
        $send_data["content"]["fileName"] = $content["name"]; // 设置文件名
        $send_data["content"]["fileUrl"] = $content["url"]; // 设置文件 URL
    }
    else {
        exit("无效的数据类型" . $content_type);
    }
    if (is_array($buttons)) {
        // 如果有按钮
        $send_data["content"]["buttons"] = $buttons;
    }
    $back_data = send_request("edit", $send_data);
    return $back_data;
}

// 撤回消息封装
// 3 个参数分别是 消息 ID、消息对象 ID、消息对象类型
function recall($msg_id, $chat_id, $chat_type) {
    $send_data = array(); // 初始化请求体
    $send_data["msgId"] = $msg_id; // 设置消息 ID
    $send_data["chatId"] = $chat_id; // 设置消息对象 ID
    $send_data["chatType"] = $chat_type; // 设置消息对象类型
    $back_data = send_request("recall", $send_data);
    return $back_data;
}

// 消息列表封装
// 这个还是 GET 请求，5 个参数分别是 获取消息对象 ID、消息 ID、指定消息 ID 前 N 条、指定消息 ID 后 N 条
function messages($chat_id, $chat_type, $message_id = null, $before = null, $after = null) {
    global $bot_token; // 获取全局变量中的 Token
    $send_header = array("Content-Type: application/json; charset=utf-8"); // 请求头
    $send_url = "https://chat-go.jwzhd.com/open-apis/v1/bot/messages?token={$bot_token}&chat-id={$chat_id}&chat-type={$chat_type}&message-id={$message_id}&before={$before}&after={$after}";
    $send = curl_init();
    curl_setopt($send, CURLOPT_URL, $send_url); // 设置 URL
    curl_setopt($send, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($send, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($send, CURLOPT_HTTPHEADER, $send_header); // 设置请求头
    $back_data = curl_exec($send);
    curl_close($send);
    return $back_data;
}

// 设置用户看板封装
// 4 个参数分别是 接收消息对象 ID、接收对象类型、消息类型、内容文本
function set_board($recv_id, $recv_type, $content_type, $content) {
    $send_data = array(); // 初始化请求体
    $send_data["recvId"] = $recv_id; // 设置接受消息对象 ID
    $send_data["recvType"] = $recv_type; // 设置接收消息对象类型
    $send_data["contentType"] = $content_type; // 设置消息类型
    $send_data["content"] = $content; // 设置内容文本
    $back_data = send_request("board", $send_data);
    return $back_data;
}

// 设置全局看板封装
// 2 个参数分别是 消息类型、内容文本
function set_board_all($content_type, $content) {
    $send_data = array(); // 初始化请求体
    $send_data["contentType"] = $content_type; // 设置消息类型
    $send_data["content"] = $content; // 设置内容文本
    $back_data = send_request("board-all", $send_data);
    return $back_data;
}

// 取消用户看板封装
// 2 个参数分别是 接收消息对象 ID、接收对象类型
function unset_board($recv_id, $recv_type) {
    $send_data = array(); // 初始化请求体
    $send_data["recvId"] = $recv_id; // 设置接收消息对象 ID
    $send_data["recvType"] = $recv_type; // 设置接收消息对象类型
    $back_data = send_request("board-dismiss", $send_data);
    return $back_data;
}

// 取消全局看板封装
// 没有请求参数
function unset_board_all() {
    $back_data = send_request("board-all-dismiss", null);
    return $back_data;
}

// 写入日志封装
function write_log($file, $log) {
    date_default_timezone_set("Asia/Shanghai"); // 设置时区
    $time = date("Y-m-d H:i:s"); // 获取时间
    file_put_contents($file, $time . " | " . $log, FILE_APPEND);
}

// 调试模式，把每个消息订阅的请求都写入日志
if ($debug_mode) {
    if ($event_type == "message.receive.normal") {
        $part1 = "{$chat_types[$chat_type]}：{$chat_id} | [{$sender_levels[$sender_user_level]}]{$sender_nickname}({$sender_id})：{$content}";
    }
    elseif ($event_type == "message.receive.instruction") {
        $part1 = "{$chat_types[$chat_type]}：{$chat_id} | 指令名称：{$command} | [{$sender_levels[$sender_user_level]}]{$sender_nickname}({$sender_id})：{$content}";
    }
    elseif (in_array($event_type, ["bot.followed", "bot.unfollowed", "group.join", "group.leave"])) {
        $part1 = "{$chat_types[$chat_type]}：{$chat_id} | {$nickname}({$user_id})";
    }
    elseif ($event_type == "bot.setting") {
        $part1 = "{$group_name}：{$group_id} | {$chat_types[$chat_type]}：{$chat_id}";
    }
    $part2 = "{$event_types[$event_type]} | " . $part1 . " | 原始请求体：{$raw_data}\n";
    write_log($log_file, $part2);
}