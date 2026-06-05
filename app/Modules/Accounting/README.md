# Accounting Module

El modulo `Accounting` organiza el contexto contable del sistema.

Convenciones:
- `Http` expone la entrada y salida HTTP del modulo.
- `Services` coordinan casos de uso del modulo.
- `Actions` encapsulan operaciones de negocio concretas y reutilizables.
- `Repositories` resuelven persistencia y consultas.
- `Events` y `Listeners` desacoplan efectos secundarios.
- `Database` contiene migraciones y seeders del modulo.

Reglas de dependencia:
- El controlador no contiene logica de negocio.
- El servicio no realiza consultas complejas directamente sobre Eloquent.
- Las integraciones externas deben vivir fuera del modulo o detras de contratos.
