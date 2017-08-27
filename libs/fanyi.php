<?php
/**
 * 功能: 执行百度文本翻译
 * @param string $text 要翻译的文本
 * @param string $from 原语言语种 默认:自动
 * @param string $to 目标语种 默认:中文
 * @return boolean string 翻译失败:false 翻译成功:翻译结果
 */
class Translate {
    /**
     * 支持的语种
     * @var ArrayAccess
     */
    static $Lang = Array (
            'auto' => '自动检测',
            'ara' => '阿拉伯语',
            'de' => '德语',
            'ru' => '俄语',
            'fra' => '法语',
            'kor' => '韩语',
            'nl' => '荷兰语',
            'pt' => '葡萄牙语',
            'jp' => '日语',
            'th' => '泰语',
            'wyw' => '文言文',
            'spa' => '西班牙语',
            'el' => '希腊语',
            'it' => '意大利语',
            'en' => '英语',
            'yue' => '粤语',
            'zh' => '中文' 
    );
    /**
     * 获取支持的语种
     * @return array 返回支持的语种
     */
    static function getLang() {
        return self::$Lang;
    }

    static function execd($str, $from = 'auto', $to = 'zh') {
        // http://fanyi.baidu.com/v2transapi?from=en&query=hello&to=zh
        $text = str_replace(PHP_EOL,' ', $str); //去除原文中的回车换行
        $url = "http://fanyi.baidu.com/v2transapi";
        $data = array (
                'from' => $from,
                'to' => $to,
                'query' => $text 
        );
        $data = http_build_query ( $data );
        if(function_exists('file_get_contents')) {
            // echo 'file_get_contents';
            $url = $url.'?'.$data;
            $result = file_get_contents($url);
        } else {
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_REFERER, "http://fanyi.baidu.com" );
            // curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:37.0) Gecko/20100101 Firefox/37.0' );
            // curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.133 Safari/537.36' );
            curl_setopt ( $ch, CURLOPT_HEADER, 0 );
            curl_setopt ( $ch, CURLOPT_POST, 1 );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
            $result = curl_exec ( $ch );
            curl_close ( $ch );
        }
        
        $result = json_decode ( $result, true );
        if (!isset($result ['trans_result'] ['data'] ['0'] ['dst'])){
            return false; 
        }
        return $result ['trans_result'] ['data'] ['0'] ['dst'];
    }
}

if (isset($_GET['words']) && !empty($_GET['words'])) {
    $words = trim($_GET['words']); 
    $fy = htmlspecialchars($words);
    echo Translate::execd($fy);
    exit;
}
?>