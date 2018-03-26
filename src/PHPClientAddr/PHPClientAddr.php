<?php
namespace PHPTools\PHPClientAddr;

/**
 * Clas eque permite obtener la Ip y HostName del cliente
 */
class PHPClientAddr
{
    /**
     * Arreglod e expreciones regulres para validar una IP privada.
     * @var array
     */
    private $privateIp = array(
        '/^0\./','/^127\.0\.0\.1/',
        '/^192\.168\..*/','/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/',
        '/^10\..*/'
    );

    /**
     * Ip del cliente.
     * @var string
     */
    public $ip;

    /**
     * Hostname del Cliente.
     * @var string
     */
    public $hostname;

    /**
     * Arreglo de parametros del servidor.
     * @var array
     */
    private $server;

    /**
     * Arreglo de parametros del entorno.
     * @var array
     */
    private $env;

    /**
     * Thr constructor.
     */
    public function __construct()
    {
        $this->server = $_SERVER;
        $this->env = $_ENV;
        $this->ip = $this->getRemodeAddr();
        $this->getIpForwarded();
        $this->hostname = $this->getRemoteHostname();
    }

    /**
     * Permite obtener el hostname de la IP
     * @return string
     */
    private function getRemoteHostname()
    {
        $hostname = NULL;

        if(!is_null($this->ip)) {
            $hostname = gethostbyaddr($this->ip);
        }
        return $hostname;
    }

    /**
     * Permite obtener la IP proveniente de un servidor proxy
     * @return void
     */
    private function getIpForwarded()
    {
        if(!!$this->isHttpXForwardedFor()) {
            $entries = $this->getHttpXForwardedForEntities();
            $this->ip = $this->getXForwardedIp($entries);
        }
    }

    /**
     * Permite saber si la peticion proviene de un servidor proxy.
     * @return boolean
     */
    private function isHttpXForwardedFor()
    {
        return !!isset($this->server['HTTP_X_FORWARDED_FOR'])&&$this->server['HTTP_X_FORWARDED_FOR']!='';
    }

    /**
     * Permite obtener todas las entidades enviadas por un servidor proxy.
     * @return array
     */
    private function getHttpXForwardedForEntities()
    {
        $entries = preg_split('[, ]', $this->server['HTTP_X_FORWARDED_FOR']);
        reset($entries);
        return $entries;
    }

    /**
     * Permite obtener la IP real proveniente de un servidor proxy.
     * @param  array $entries Arreglo de entidades enviadas por un servidor proxy
     * @return string
     */
    private function getXForwardedIp($entries)
    {
        $ip = $this->ip;
        while (list(, $entry) = each($entries)) {
            $entry = trim($entry);
            if ( preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $entry, $ip_list)) {
                $found_ip = preg_replace( $this->privateIp, $ip, $ip_list[1]);

                if ($ip != $found_ip) {
                    $ip = $found_ip;
                    break;
                }
            }
        }
        return $ip;
    }

    /**
     * Permite obtener la IP real del cliente.
     * @return string
     */
    private function getRemodeAddr()
    {
        $ip = NULL;
        if(PHP_SAPI=='cli') {
            $ip = gethostbyname(gethostname());
        } elseif($this->hasServerRemoteAddr()) {
            $ip = $this->server['REMOTE_ADDR'];
        } elseif($this->hasEnvRemoteAddr()) {
            $ip = $this->env['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Check if the remote Ip is in the glocal variable $_SERVER
     * @return boolean
     */
    private function hasServerRemoteAddr()
    {
        return !!isset($this->server['REMOTE_ADDR'])
            &&!empty($this->server['REMOTE_ADDR'])
        ;
    }

    /**
     * Check if the remote Ip is in the glocal variable $_ENV
     * @return boolean
     */
    private function hasEnvRemoteAddr()
    {
        return !!isset($this->env['REMOTE_ADDR'])
            &&!empty($this->env['REMOTE_ADDR'])
        ;
    }
}
