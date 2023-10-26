<?php 
namespace App\Core;
class Request{

    /**
     * Returns the path of the current request URL.
     *
     * @return string The path of the current request URL.
     */
    public static function getPath():string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $pos = strpos($path, '?');

        if($pos === FALSE) {
            return $path;
        }

        return substr($path, 0, $pos);
    }
    /**
     * Returns the HTTP request method in lowercase.
     *
     * @return string
     * @throws \Exception if the request method is not set in $_SERVER superglobal.
     */
    public static function getMethod():string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Returns an array of sanitized data from either $_GET or $_POST superglobals.
     *
     * @return array An array of sanitized data.
     */
    public static function getBody():array
    {
        $data = [];
        if(self::getMethod() === 'get'){
            foreach($_GET as $key => $val) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if(self::getMethod() === 'post'){
            foreach($_POST as $key => $val) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $data;
    }


}