<?php
class Tools
{
    public static function getValue($key, $type = INPUT_GET, $filter = FILTER_SANITIZE_FULL_SPECIAL_CHARS, $options = array('options' => array('default' => '')))
    {
        return filter_input($type, $key, $filter, $options);
    }

    public static function safeOutPut($string, $type = ENT_QUOTES)
    {
        if (is_array($string)) {
            return array_map(array('self', 'safeOutPut'), $string);
        }
        return htmlentities((string) $string, $type, 'utf-8');
    }

    public static function safeOutPutDecode($string)
    {
        if (is_array($string)) {
            return array_map(array('self', 'safeOutPutDecode'), $string);
        }
        return html_entity_decode((string) $string, ENT_QUOTES, 'utf-8');
    }

    public static function redirect($url, $httpResponseCode = false)
    {
        if (empty($httpResponseCode)) {
            header('Location: ' . $url);
        } else {
            header('Location: ' . $url, true, (int) $httpResponseCode);
        }
        session_write_close();
        exit();
    }

    public static function dateFormat($date, $currentFormat = 'Y-m-d H:i:s', $targetFormat = 'Y-m-d H:i:s')
    {
        $dataTime = DateTime::createFromFormat($currentFormat, $date);
        return $dataTime ? $dataTime->format($targetFormat) : false;
    }

    public static function getStr($string, $start, $end)
    {
        if (empty($string) || empty($start) || empty($end)) {
            return '';
        }
        return preg_match('/' . $start . '(.*?)' . $end . '/su', $string, $matches) ? trim($matches[1]) : false;
    }

    public static function round($number, $decimals = 2, $mode = PHP_ROUND_HALF_UP)
    {
        return number_format(round($number, $decimals, $mode), $decimals, '.', '');
    }

    public static function formatUrl($url)
    {
        return str_replace(array(
            ' ',
            '&amp;'
        ), array(
            '%20',
            '&'
        ), $url);
    }

    public static function removeLink($string)
    {
        return preg_replace('/<a.*?>(.*?)<\/a>/isu', "\$1", $string);
    }

    public static function getUrlFromStr($string)
    {
        return preg_match_all('/<a[^>]*href\s*=\s*([\'"]?)([^\'">]*)\1(?=\s|\/|>)/isu', $string, $matches) ? (array) $matches[2] : array();
    }

    public static function getImgFromStr($string)
    {
        return preg_match_all('/<img[^>]*src\s*=\s*([\'"]?)([^\'">]*)\1(?=\s|\/|>)/isu', $string, $matches) ? (array) $matches[2] : array();
    }

    public static function convertCharset($string, $newCharset, $nowCharset = 'utf-8')
    {
        return $nowCharset !== $newCharset ? mb_convert_encoding($string, $newCharset, $nowCharset) : $string;
    }

    public static function convertSzie($bytesNumber, $decimals = 2)
    {
        $unit = array('B', 'K', 'M', 'G', 'T', 'P');
        return number_format($bytesNumber / pow(1024, ($i = floor(log($bytesNumber, 1024)))), $decimals) . ' ' . $unit[$i];
    }

    public static function convertTime($secondsNumber, $decimals = 2)
    {
        switch (1) {
            case $secondsNumber < 1: // 1 s
                return number_format($secondsNumber, $decimals) . ' s';
            case $secondsNumber < 86400: // 1 day
                $unit = array( 's', 'min', 'h' );
                return number_format($secondsNumber / pow(60, ($i = floor(log($secondsNumber, 60)))), $decimals) . ' ' . $unit[$i];
            case $secondsNumber < 604800: // 1 week
                return number_format($secondsNumber / 86400, $decimals) . ' d';
            case $secondsNumber < 2592000: // 1 month = 30 day
                return number_format($secondsNumber / 604800, $decimals) . ' week';
            case $secondsNumber < 31536000: // 1 yr
                return number_format($secondsNumber / 2592000, $decimals) . ' month';
            case $secondsNumber >= 31536000: // >1 yr
                return number_format($secondsNumber / 31536000, $decimals) . ' yr';
        }
    }
}
