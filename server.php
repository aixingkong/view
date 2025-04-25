<?php
// 获取传入的网页地址
$url = isset($_GET['url']) ? $_GET['url'] : '';

if (!empty($url)) {
    // 使用cURL库获取网页源码
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // 允许跟随重定向
    $html = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode == 200) {
        // 从 URL 中截取文件名
        $file_name = basename($url);

        // 获取文件名后缀
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);

        // 判断文件类型并进行下载响应
        if ($file_extension === 'xlsx') {
            // 设置响应头，告诉浏览器以附件形式下载文件
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            // 输出文件内容
            echo $html;
            exit; // 结束脚本执行
         } elseif ($file_extension === 'json') {
            // 设置响应头，返回JSON文件内容
            header('Content-Type: application/json');
            // 输出文件内容
            echo $html;
            exit; // 结束脚本执行
        } else {
            // 返回网页源码
            echo $html;
            exit; // 结束脚本执行
        }
    } else {
        // 如果请求失败，返回错误信息
        echo "Error: Unable to fetch webpage. HTTP code: " . $httpCode;
    }
} else {
    // 如果没有传入网页地址，返回错误信息
    echo "Error: URL parameter is missing.";
}
?>
