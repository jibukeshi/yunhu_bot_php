<?php
$bot_token = ""; // 机器人的 Token
$debug_mode = false; // 调试模式，可根据实际需求打开或关闭
$log_file = "big.txt"; // 日志文件名
require __DIR__ . "/sdk.php"; // 引入 SDK，勿删

$helper = "欢迎使用另一个进群欢迎机器人！
此机器人高仿 2022 年的全员群欢迎机器人。
把此机器人加入到你的群聊中即可开始使用。
欢迎加入云湖群聊【测试全员群】
https://yhfx.jwznb.com/share?key=by8h8DoKmiio&ts=1719664390 
群ID: 257731539"; //帮助文本

// 第一次使用机器人发送帮助消息
if ($event_type == "bot.followed") {
    send($recv_id, $recv_type, "text", $helper);
}

$welcome = array(
    array("type" => "text", "data" => "💡感谢您见证15亿用户大群的建立~"),
    array("type" => "text", "data" => "💡云湖支持动态头像，要不要来一个？"),
    array("type" => "text", "data" => "💡软件支持Windows、Mac、iOS、Android系统"),
    array("type" => "text", "data" => "💡云湖当前处于第二测试阶段，欢迎反馈BUG或者功能建议"),
    array("type" => "text", "data" => "💡软件目前有很多BUG，坐稳不用慌，我们会及时修复"),
    array("type" => "text", "data" => "💡希望大家周五晚上来全员群聚齐，给大家一个美好的夜晚"),
    array("type" => "text", "data" => "💡如果您是酷安用户欢迎加入《我的酷安朋友群》，群ID: 855696428"),
    array("type" => "markdown", "data" => "💡您需要什么机器人？[点击参与问卷调查](https://wj.qq.com/s2/10532242/9d09/)"),
    array("type" => "markdown", "data" => "💡云湖教程❗️ [云湖教程（一）一分钟教会你通过API发消息](https://www.yhchat.com/article/10005)"),
    array("type" => "markdown", "data" => "💡云湖玩法大升级❗️ [云湖玩法（一）：打造自己的网站用户全员群](https://www.yhchat.com/article/10002)"),
    array("type" => "markdown", "data" => "💡云湖玩法大升级❗️ [云湖玩法（二）您的私有网站监控平台](https://www.yhchat.com/article/10003)"),
    array("type" => "markdown", "data" => "云湖开通bilibili官方账号啦，快快关注，不要错过。[云湖官方账号](https://space.bilibili.com/2105298524)

[https://space.bilibili.com/2105298524](https://space.bilibili.com/2105298524)")
);

// 进群欢迎
if ($event_type == "group.join") {
    $choose = $welcome[array_rand($welcome)];
    $result = "欢迎 {$nickname} 加入全员群。\n" . $choose["data"];
    send($recv_id, $recv_type, $choose["type"], $result);
}