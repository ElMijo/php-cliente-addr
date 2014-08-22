php-cliente-addr
================

Una pequeña clase para obtener la IP y el HostName del cliente

> **Nota:** No se esta descubriendo el agua tibia.. solo unifique en un solo metodo las propuestas e investigaciones de muchas personas incluyendo las mias.


Instalación
-----------

```json
    "require": {
        ...
        "elmijo/php-cliente-addr": "1.0"
        ...
    }
```

Ejemplo
-------

Datemos un pequeño ejemplo del uso de esta clase:

#### 1.- Incluir la libreria

```php
use ClienteAddr\ClienteAddr;
```

#### 2.- Obtener los valores

```php
echo ClienteAddr::$IP;
echo ClienteAddr::$HOSTNAME;
