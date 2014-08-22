<?php
namespace ClienteAddr;

class ClienteAddr
{
    private static $list    = array(
        'HTTP_CLIENT_IP', 
        'HTTP_X_FORWARDED_FOR', 
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP', 
        'HTTP_FORWARDED_FOR', 
        'HTTP_FORWARDED', 
        'REMOTE_ADDR'
    );

    public static $IP       = NULL;

    public static $HOSTNAME = NULL;

    public static function init()
    {
        foreach (self::$list as $key)
        {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        self::$IP       = $ip;
                        self::$HOSTNAME = gethostbyaddr($ip);
                        return ;
                    }
                }
            }            
        }
    }
}
ClienteAddr::init();
?>