<?php
class Authenticate
{
    public static function authenticate($user)
    {
        // set http auth headers for apache+php-cgi work around
        if (isset($_SERVER['HTTP_AUTHORIZATION']) && preg_match('/Basic\s+(.*)$/i', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            list ($name, $password) = explode(':', base64_decode($matches[1]));
            $_SERVER['PHP_AUTH_USER'] = strip_tags($name);
            $_SERVER['PHP_AUTH_PW'] = strip_tags($password);
        }
        
        // set http auth headers for apache+php-cgi work around if variable gets renamed by apache
        if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION']) && preg_match('/Basic\s+(.*)$/i', $_SERVER['REDIRECT_HTTP_AUTHORIZATION'], $matches)) {
            list ($name, $password) = explode(':', base64_decode($matches[1]));
            $_SERVER['PHP_AUTH_USER'] = strip_tags($name);
            $_SERVER['PHP_AUTH_PW'] = strip_tags($password);
        }
        
        if (@$_SERVER['PHP_AUTH_USER'] !== base64_decode(base64_decode($user)) or @$_SERVER['PHP_AUTH_PW'] !== md5(gzdeflate(base64_encode(md5(gzdeflate(base64_encode(md5(base64_decode(base64_decode($user)) . self::getHttpHost())))))))) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 401 Unauthorized');
            header('WWW-Authenticate: Basic realm="Welcome to Cpanel, please enter the authentication key as the login."');
            die();
        }
    }
}
