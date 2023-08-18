## Función del Método sincronizar

El método sincronizar en la clase Usuario se encarga de asignar valores de un arreglo a las propiedades correspondientes de un objeto Usuario.

Pasos
El método toma un arreglo $args como argumento.
Itera a través de las claves y valores del arreglo $args usando un bucle foreach.
Para cada clave ($key) y valor ($value):
Verifica si la propiedad con el nombre $key existe en la instancia actual de Usuario ($this).
También verifica si el valor ($value) no es nulo.
Si ambas condiciones son verdaderas, asigna el valor a la propiedad correspondiente de la instancia de Usuario.
Beneficios
Permite llenar las propiedades del objeto Usuario con valores proporcionados, evitando valores nulos o incorrectos.
Ayuda a mantener la consistencia y precisión de los datos almacenados en el objeto Usuario.
En resumen, el método sincronizar garantiza que los datos ingresados sean asignados a las propiedades del objeto Usuario, asegurando que solo se asignen valores válidos y no nulos, lo que ayuda a mantener la integridad de los datos.
