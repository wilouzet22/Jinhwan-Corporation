# ðŸ‘¤ Tabla BD: Usuarios y Miembros
#database #tabla

Almacena datos personales, credenciales hasheadas, fecha de nacimiento, contacto y referencia a sede y grado actual.

## Relaciones
- Pertenece a [[DB_Tabla_Sedes]]
- Ostenta un [[DB_Tabla_Grados]]
- Posee un rol en [[DB_Tabla_Roles_Permisos]]
- Registra asistencias en [[DB_Tabla_Asistencias]]
- Solicita ascensos en [[DB_Tabla_Solicitudes_Ascenso]]
- Conecta con [[Controlador_Miembros]]
