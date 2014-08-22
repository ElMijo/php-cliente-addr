<?php
namespace PHPClienteAddr;

/**
 * Clas eque permite obtener la Ip y HostName del cliente
 */
class PHPClienteAddr
{
    /**
     * Lista de posinles variables que contienen la IP del cliente
     * @var array
     */
    private static $list    = array('HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_X_CLUSTER_CLIENT_IP','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR');

    /**
     * Ip del cliente
     * @var string
     */
    public static $IP       = NULL;

    /**
     * Hostname del Cliente
     * @var string
     */
    public static $HOSTNAME = NULL;

    /**
     * Metodo que permite definir los valores de IP y HostName
     * @return [type] [description]
     */
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