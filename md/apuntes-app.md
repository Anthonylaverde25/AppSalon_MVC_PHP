## Resumen de la Conexión a la Base de Datos y Uso de Active Record

La conexión con la base de datos se establece a través del archivo `dataBase.php`, el cual será incluido en el archivo `app.php`. En este proceso, el archivo `app.php` se encargará de requerir el archivo `dataBase.php` para establecer la conexión.

En el archivo `app.php`, además de requerir `dataBase.php`, también se hará uso del modelo `ActiveRecord`. Para esto, se puede instanciar el método `setDB` de la clase `ActiveRecord` de la siguiente manera:

```php
// app.php
require_once 'dataBase.php'; // Requiere el archivo para establecer la conexión

// ...

use Model\ActiveRecord; // Asegura que esté presente el namespace correspondiente

// ...

ActiveRecord::setDb($db); // Configura la conexión de la clase ActiveRecord con la base de datos

// ...
```
