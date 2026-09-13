$targetDirs = @(
    "C:\Users\USER\Documents\Obsidian Vault\Arquitectura_Sistema",
    "c:\laragon\www\jinwha\docs\Arquitectura_Sistema"
)

foreach ($dir in $targetDirs) {
    if (!(Test-Path $dir)) {
        New-Item -ItemType Directory -Force -Path $dir | Out-Null
    }
}

$notes = @{
    "00_Cerebro_Central_del_Sistema.md" = @"
# 🧠 Cerebro Central del Sistema (Orquestador Core)
#cerebro #arquitectura/core #proyecto_de_grado

Es el núcleo de inteligencia del software. Orquesta y enlaza todas las capas del sistema garantizando desacoplamiento, trazabilidad y consistencia.

## Conexiones Principales
- [[Capa_Frontend_Presentacion]]
- [[Capa_Backend_Logica]]
- [[Capa_Persistencia_Datos]]
- [[Capa_Seguridad_Perimetral]]

## Flujos de Información Centrales
- [[Flujo_Ascenso_y_Promocion]]
- [[Flujo_Autenticacion_y_Sesion]]
- [[Flujo_Auditoria_y_Trazabilidad]]

## Módulos Neurálgicos
- [[Servicio_Motor_Reglas]]
- [[Servicio_Maquina_Estados]]
- [[Servicio_Calculador_Rendimiento]]
- [[Gestor_Transacciones_ACID]]
"@

    "Capa_Frontend_Presentacion.md" = @"
# 🖥️ Capa de Presentación (Frontend)
#frontend #arquitectura/capa

Responsable de la interacción con el usuario, renderizado reactivo y captura de eventos.

## Componentes y Vistas
- [[UI_Dashboard_Principal]]
- [[UI_Modulo_Autenticacion]]
- [[UI_Visualizador_Cinturones]]
- [[UI_Directorio_Miembros]]
- [[UI_Formularios_Operativos]]
- [[UI_Calendario_Eventos]]

## Lógica de Cliente
- [[Frontend_Gestor_Estado]]
- [[Frontend_Validador_Cliente]]
- [[Frontend_Cliente_HTTP]]

## Enlace con el Cerebro
- Conecta directamente hacia [[API_Gateway_Enrutador]] a través de [[Frontend_Cliente_HTTP]].
"@

    "Capa_Backend_Logica.md" = @"
# ⚙️ Capa Lógica del Sistema (Backend)
#backend #arquitectura/capa

Contiene los controladores, servicios de dominio, evaluadores de reglas y gestores de transacciones.

## Puntos de Entrada
- [[API_Gateway_Enrutador]]
- [[Sanitizador_DTO_Entrada]]

## Controladores de Dominio
- [[Controlador_Miembros]]
- [[Controlador_Ascensos]]
- [[Controlador_Sedes]]
- [[Controlador_Eventos]]

## Servicios de Inteligencia (El Núcleo)
- [[Servicio_Motor_Reglas]]
- [[Servicio_Maquina_Estados]]
- [[Servicio_Calculador_Rendimiento]]
- [[Servicio_Generador_Certificados]]
- [[Despachador_Notificaciones]]

## Abstracción de Datos
- [[Capa_ORM_Repositorios]]
- [[Gestor_Transacciones_ACID]]
"@

    "Capa_Persistencia_Datos.md" = @"
# 🗄️ Capa de Persistencia (Base de Datos)
#database #arquitectura/capa

Garantiza el almacenamiento estructurado, integridad relacional y durabilidad ACID de toda la información del sistema.

## Motor y Almacén
- [[Motor_Base_Datos_SQL]]
- [[Cache_Memoria_Volatil]]

## Tablas y Esquemas
- [[DB_Tabla_Usuarios]]
- [[DB_Tabla_Sedes]]
- [[DB_Tabla_Grados]]
- [[DB_Tabla_Solicitudes_Ascenso]]
- [[DB_Tabla_Requisitos]]
- [[DB_Tabla_Asistencias]]
- [[DB_Tabla_Eventos]]
- [[DB_Tabla_Roles_Permisos]]
- [[DB_Bitacora_Auditoria]]
"@

    "Capa_Seguridad_Perimetral.md" = @"
# 🛡️ Capa de Seguridad Perimetral
#seguridad #arquitectura/capa

Asegura que ninguna petición ingrese al cerebro del software sin validación de identidad y permisos.

## Componentes de Seguridad
- [[Middleware_Autenticacion_JWT]]
- [[Middleware_Autorizacion_RBAC]]
- [[Seguridad_Cifrado_Tokens]]
- [[DB_Tabla_Roles_Permisos]]
- Conecta con [[API_Gateway_Enrutador]] y [[00_Cerebro_Central_del_Sistema]].
"@

    "UI_Dashboard_Principal.md" = @"
# 📊 Vista: Dashboard Principal
#frontend #vista #ux

Pantalla central del usuario con métricas en tiempo real, resumen de progresos y accesos rápidos.

## Conexiones
- [[Frontend_Gestor_Estado]]
- [[UI_Visualizador_Cinturones]]
- [[UI_Directorio_Miembros]]
- [[UI_Calendario_Eventos]]
- Recibe actualizaciones en vivo desde [[Despachador_Notificaciones]].
"@

    "UI_Modulo_Autenticacion.md" = @"
# 🔐 Vista: Módulo de Autenticación
#frontend #vista #seguridad

Formulario de inicio de sesión, recuperación de contraseña y gestión de credenciales.

## Conexiones
- [[Frontend_Validador_Cliente]]
- [[Frontend_Cliente_HTTP]]
- Se comunica en el backend con [[Middleware_Autenticacion_JWT]] y [[Flujo_Autenticacion_y_Sesion]].
"@

    "UI_Visualizador_Cinturones.md" = @"
# 🥋 Vista: Visualizador de Progreso de Cinturones
#frontend #vista #gamificacion

Componente interactivo (`BeltProgressVisualizer`) que muestra el porcentaje de avance, requisitos pendientes y feedback visual de graduación.

## Conexiones
- [[Frontend_Gestor_Estado]]
- [[Servicio_Calculador_Rendimiento]]
- [[Servicio_Motor_Reglas]]
- [[DB_Tabla_Grados]]
- Dispara el [[Flujo_Ascenso_y_Promocion]].
"@

    "UI_Directorio_Miembros.md" = @"
# 👥 Vista: Directorio de Miembros
#frontend #vista

Gestión y visualización del listado de alumnos, maestros y directores por sede.

## Conexiones
- [[Frontend_Gestor_Estado]]
- [[Controlador_Miembros]]
- [[DB_Tabla_Usuarios]]
- [[DB_Tabla_Sedes]]
"@

    "UI_Formularios_Operativos.md" = @"
# 📝 Vista: Formularios de Gestión Operativa
#frontend #vista

Formularios dinámicos para registro de alumnos, creación de solicitudes y carga de calificaciones.

## Conexiones
- [[Frontend_Validador_Cliente]]
- [[Frontend_Cliente_HTTP]]
- [[Controlador_Ascensos]]
- [[Controlador_Miembros]]
"@

    "UI_Calendario_Eventos.md" = @"
# 📅 Vista: Calendario Interactivo de Eventos
#frontend #vista

Visualización de fechas de exámenes, torneos, seminarios y clases especiales por sede.

## Conexiones
- [[Frontend_Gestor_Estado]]
- [[Controlador_Eventos]]
- [[DB_Tabla_Eventos]]
- [[DB_Tabla_Sedes]]
"@

    "Frontend_Gestor_Estado.md" = @"
# ⚡ Frontend: Gestor de Estado Global
#frontend #estado

Almacén reactivo en memoria de cliente (Store / Context / Hooks). Controla la consistencia visual sin recargar la página.

## Conexiones
- [[UI_Dashboard_Principal]]
- [[UI_Visualizador_Cinturones]]
- [[UI_Directorio_Miembros]]
- [[Frontend_Cliente_HTTP]]
- [[00_Cerebro_Central_del_Sistema]]
"@

    "Frontend_Validador_Cliente.md" = @"
# 🧹 Frontend: Validadores de Cliente
#frontend #seguridad

Validación de formatos, campos requeridos y tipos de datos antes del envío a la red.

## Conexiones
- [[UI_Modulo_Autenticacion]]
- [[UI_Formularios_Operativos]]
- [[Frontend_Cliente_HTTP]]
- Se empareja con [[Sanitizador_DTO_Entrada]] en el backend.
"@

    "Frontend_Cliente_HTTP.md" = @"
# 📡 Frontend: Cliente HTTP e Interceptores
#frontend #red

Módulo de transporte (Axios / Fetch) con interceptores para inyección automática de tokens Bearer JWT y control de errores 401/403/500.

## Conexiones
- [[Frontend_Gestor_Estado]]
- [[Frontend_Validador_Cliente]]
- [[API_Gateway_Enrutador]]
- [[Middleware_Autenticacion_JWT]]
"@

    "API_Gateway_Enrutador.md" = @"
# 🌐 Backend: API Gateway y Enrutador Principal
#backend #gateway #seguridad

Punto único de entrada de todas las peticiones HTTPS del sistema. Aplica filtros de seguridad, rate limiting y enrutamiento a controladores.

## Conexiones
- [[Frontend_Cliente_HTTP]]
- [[Middleware_Autenticacion_JWT]]
- [[Middleware_Autorizacion_RBAC]]
- [[Sanitizador_DTO_Entrada]]
- [[Controlador_Miembros]]
- [[Controlador_Ascensos]]
- [[Controlador_Sedes]]
- [[Controlador_Eventos]]
"@

    "Middleware_Autenticacion_JWT.md" = @"
# 🔐 Backend: Middleware de Autenticación JWT
#backend #seguridad #middleware

Verifica la firma criptográfica y vigencia del token de sesión en cada solicitud entrante.

## Conexiones
- [[API_Gateway_Enrutador]]
- [[Seguridad_Cifrado_Tokens]]
- [[Cache_Memoria_Volatil]]
- [[DB_Tabla_Usuarios]]
- [[Flujo_Autenticacion_y_Sesion]]
"@

    "Middleware_Autorizacion_RBAC.md" = @"
# 🛡️ Backend: Middleware de Autorización RBAC (Roles)
#backend #seguridad #middleware

Control de acceso basado en roles: Alumno, Maestro de Sede, Director General y Administrador.

## Conexiones
- [[API_Gateway_Enrutador]]
- [[DB_Tabla_Roles_Permisos]]
- [[Controlador_Ascensos]]
- [[Controlador_Sedes]]
"@

    "Sanitizador_DTO_Entrada.md" = @"
# 🧼 Backend: Sanitizador de DTOs y Validación
#backend #seguridad

Limpia caracteres peligrosos (prevención XSS/SQL Injection) y valida esquemas tipados de datos entrantes.

## Conexiones
- [[API_Gateway_Enrutador]]
- [[Controlador_Ascensos]]
- [[Controlador_Miembros]]
"@

    "Controlador_Miembros.md" = @"
# 👥 Backend: Controlador de Miembros y Alumnos
#backend #controlador

Expone endpoints REST para alta, modificación, consulta y bajas lógicas de practicantes y maestros.

## Conexiones
- [[API_Gateway_Enrutador]]
- [[Capa_ORM_Repositorios]]
- [[DB_Tabla_Usuarios]]
- [[DB_Tabla_Sedes]]
"@

    "Controlador_Ascensos.md" = @"
# 🥋 Backend: Controlador de Ascensos y Exámenes
#backend #controlador

Gestiona el ciclo de vida de exámenes, inscripción a promociones y registro de calificaciones de jurado.

## Conexiones
- [[API_Gateway_Enrutador]]
- [[Servicio_Motor_Reglas]]
- [[Servicio_Maquina_Estados]]
- [[Capa_ORM_Repositorios]]
- [[DB_Tabla_Solicitudes_Ascenso]]
"@

    "Controlador_Sedes.md" = @"
# 🏛️ Backend: Controlador de Sedes (Doojangs)
#backend #controlador

Administración de sucursales, maestros titulares, horarios y capacidad física.

## Conexiones
- [[API_Gateway_Enrutador]]
- [[Capa_ORM_Repositorios]]
- [[DB_Tabla_Sedes]]
"@

    "Controlador_Eventos.md" = @"
# 📅 Backend: Controlador de Eventos y Torneos
#backend #controlador

Gestión de torneos, seminarios técnicos y cronograma de clases especiales.

## Conexiones
- [[API_Gateway_Enrutador]]
- [[Capa_ORM_Repositorios]]
- [[DB_Tabla_Eventos]]
"@

    "Servicio_Motor_Reglas.md" = @"
# ⚖️ Backend: Motor de Reglas de Negocio (Rule Engine)
#backend #cerebro #reglas #core

El evaluador lógico del cerebro. Analiza si un alumno es formalmente elegible para ser promovido de cinturón.

## Reglas Evaluadas
- [[Regla_Tiempo_Minimo_Grado]]
- [[Regla_Asistencia_Minima]]
- [[Regla_Matriz_Tecnica]]

## Conexiones
- [[00_Cerebro_Central_del_Sistema]]
- [[Controlador_Ascensos]]
- [[Servicio_Maquina_Estados]]
- [[Flujo_Ascenso_y_Promocion]]
"@

    "Regla_Tiempo_Minimo_Grado.md" = @"
# ⏳ Regla: Tiempo Mínimo de Permanencia en Grado
#backend #regla

Verifica que el alumno haya cumplido los meses reglamentarios en su cinturón actual antes de solicitar examen.

## Conexiones
- [[Servicio_Motor_Reglas]]
- [[DB_Tabla_Grados]]
- [[DB_Tabla_Usuarios]]
"@

    "Regla_Asistencia_Minima.md" = @"
# 📊 Regla: Cumplimiento de Asistencia Mínima (85%)
#backend #regla

Calcula las horas efectivas de entrenamiento presencial registradas en el doojang.

## Conexiones
- [[Servicio_Motor_Reglas]]
- [[DB_Tabla_Asistencias]]
- [[DB_Tabla_Sedes]]
"@

    "Regla_Matriz_Tecnica.md" = @"
# 🥋 Regla: Matriz Técnica de Competencias
#backend #regla

Valida que el alumno domine: Poomsae oficial, combinaciones de patada, combate reglado y terminología marcial.

## Conexiones
- [[Servicio_Motor_Reglas]]
- [[DB_Tabla_Requisitos]]
- [[DB_Tabla_Grados]]
"@

    "Servicio_Maquina_Estados.md" = @"
# 🔄 Backend: Máquina de Estados Finitos de Ascenso
#backend #cerebro #patron #workflow

Orquesta las transiciones deterministas del proceso de graduación evitando inconsistencias o saltos de grado ilícitos.

## Estados del Proceso
- [[Estado_Solicitud_Iniciada]]
- [[Estado_Pendiente_Jurado]]
- [[Estado_Evaluacion_Tecnica]]
- [[Estado_Aprobado_Certificado]]
- [[Estado_Rechazado_Feedback]]

## Conexiones
- [[00_Cerebro_Central_del_Sistema]]
- [[Servicio_Motor_Reglas]]
- [[Gestor_Transacciones_ACID]]
- [[Servicio_Generador_Certificados]]
"@

    "Estado_Solicitud_Iniciada.md" = @"
# 🟡 Estado: Solicitud Iniciada
#estado #workflow

El practicante o maestro inicia la postulación al examen de grado.
- Conecta con [[Servicio_Maquina_Estados]] y [[DB_Tabla_Solicitudes_Ascenso]].
"@

    "Estado_Pendiente_Jurado.md" = @"
# 🟠 Estado: Pendiente de Aprobación por Jurado
#estado #workflow

La solicitud superó las reglas automáticas y espera designación de mesa evaluadora.
- Conecta con [[Servicio_Maquina_Estados]] y [[DB_Tabla_Roles_Permisos]].
"@

    "Estado_Evaluacion_Tecnica.md" = @"
# 🔵 Estado: En Evaluación Técnica Presencial
#estado #workflow

El alumno presenta su examen frente a los maestros evaluadores.
- Conecta con [[Servicio_Maquina_Estados]] y [[DB_Tabla_Requisitos]].
"@

    "Estado_Aprobado_Certificado.md" = @"
# 🟢 Estado: Aprobado con Emisión de Grado
#estado #workflow

El jurado otorga la calificación aprobatoria. Se genera el nuevo grado marcial.
- Conecta con [[Servicio_Maquina_Estados]], [[Servicio_Generador_Certificados]] y [[DB_Tabla_Grados]].
"@

    "Estado_Rechazado_Feedback.md" = @"
# 🔴 Estado: Rechazado con Retroalimentación
#estado #workflow

El alumno no alcanzó el estándar técnico y recibe plan de mejora para el siguiente ciclo.
- Conecta con [[Servicio_Maquina_Estados]] y [[Despachador_Notificaciones]].
"@

    "Servicio_Calculador_Rendimiento.md" = @"
# 📈 Backend: Calculador de Rendimiento y Proyección Dan
#backend #cerebro #algoritmo

Algoritmo que computa el porcentaje continuo de avance del alumno y predice la fecha estimada para alcanzar el Cinturón Negro (1er Dan).

## Conexiones
- [[00_Cerebro_Central_del_Sistema]]
- [[UI_Visualizador_Cinturones]]
- [[DB_Tabla_Grados]]
- [[Algoritmo_Proyeccion_Dan]]
"@

    "Algoritmo_Proyeccion_Dan.md" = @"
# 🧮 Algoritmo: Modelo Predictivo de Graduación Dan
#backend #algoritmo #investigacion

Modela la curva de aprendizaje, asistencia histórica y tasa de retención del alumno para proyectar su evolución a grado de maestro.

## Conexiones
- [[Servicio_Calculador_Rendimiento]]
- [[DB_Tabla_Usuarios]]
- [[DB_Tabla_Grados]]
"@

    "Servicio_Generador_Certificados.md" = @"
# 📜 Backend: Generador de Certificados y Folios
#backend #servicios

Emite el folio criptográfico único del diploma de grado con firma digital del maestro evaluador.

## Conexiones
- [[Servicio_Maquina_Estados]]
- [[Gestor_Transacciones_ACID]]
- [[DB_Bitacora_Auditoria]]
- [[Despachador_Notificaciones]]
"@

    "Despachador_Notificaciones.md" = @"
# 🔔 Backend: Despachador de Eventos y Notificaciones
#backend #eventos #comunicacion

Notifica en tiempo real a la interfaz de usuario los cambios de estado, felicitaciones de ascenso (confetti) o alertas de asistencia.

## Conexiones
- [[UI_Dashboard_Principal]]
- [[Frontend_Gestor_Estado]]
- [[Servicio_Generador_Certificados]]
"@

    "Capa_ORM_Repositorios.md" = @"
# 📦 Backend: Capa ORM y Patrón Repositorio
#backend #persistencia #patron

Aísla la lógica de negocio de las consultas directas de base de datos, implementando repositorios desacoplados.

## Conexiones
- [[Controlador_Miembros]]
- [[Controlador_Ascensos]]
- [[Controlador_Sedes]]
- [[Controlador_Eventos]]
- [[Gestor_Transacciones_ACID]]
- [[Motor_Base_Datos_SQL]]
"@

    "Gestor_Transacciones_ACID.md" = @"
# ⚛️ Backend: Gestor de Transacciones ACID
#backend #cerebro #persistencia #consistencia

Asegura atomicidad: si se promueve un cinturón, la actualización del perfil, la inserción en auditoría y el cierre de solicitud se completan juntos o se hace Rollback total.

## Conexiones
- [[00_Cerebro_Central_del_Sistema]]
- [[Capa_ORM_Repositorios]]
- [[Motor_Base_Datos_SQL]]
- [[DB_Bitacora_Auditoria]]
- [[Servicio_Maquina_Estados]]
"@

    "Motor_Base_Datos_SQL.md" = @"
# ⚙️ Base de Datos: Motor Relacional SQL
#database #motor

Motor de base de datos relacional (MySQL / PostgreSQL / SQLite) con constraints, foreign keys e índices optimizados.

## Tablas Gestionadas
- [[DB_Tabla_Usuarios]]
- [[DB_Tabla_Sedes]]
- [[DB_Tabla_Grados]]
- [[DB_Tabla_Solicitudes_Ascenso]]
- [[DB_Tabla_Requisitos]]
- [[DB_Tabla_Asistencias]]
- [[DB_Tabla_Eventos]]
- [[DB_Tabla_Roles_Permisos]]
- [[DB_Bitacora_Auditoria]]
- [[Capa_ORM_Repositorios]]
"@

    "DB_Tabla_Usuarios.md" = @"
# 👤 Tabla BD: Usuarios y Miembros
#database #tabla

Almacena datos personales, credenciales hasheadas, fecha de nacimiento, contacto y referencia a sede y grado actual.

## Relaciones
- Pertenece a [[DB_Tabla_Sedes]]
- Ostenta un [[DB_Tabla_Grados]]
- Posee un rol en [[DB_Tabla_Roles_Permisos]]
- Registra asistencias en [[DB_Tabla_Asistencias]]
- Solicita ascensos en [[DB_Tabla_Solicitudes_Ascenso]]
- Conecta con [[Controlador_Miembros]]
"@

    "DB_Tabla_Sedes.md" = @"
# 🏛️ Tabla BD: Sedes y Doojangs
#database #tabla

Información geográfica, horarios, maestro director y capacidad de alumnos por filial.

## Relaciones
- Agrupa múltiples [[DB_Tabla_Usuarios]]
- Aloja [[DB_Tabla_Eventos]]
- Registra [[DB_Tabla_Asistencias]]
"@

    "DB_Tabla_Grados.md" = @"
# 🥋 Tabla BD: Grados y Cinturones
#database #tabla

Catálogo oficial de grados: Blanco, Pinta Amarillo, Amarillo, Pinta Verde... hasta Grados Dan (Cinturón Negro).

## Relaciones
- Define los [[DB_Tabla_Requisitos]] por grado
- Asignado en [[DB_Tabla_Usuarios]]
- Objetivo en [[DB_Tabla_Solicitudes_Ascenso]]
- Evaluado en [[Regla_Tiempo_Minimo_Grado]]
"@

    "DB_Tabla_Solicitudes_Ascenso.md" = @"
# 📋 Tabla BD: Solicitudes de Ascenso
#database #tabla

Registro transaccional de cada examen de promoción con sus estados históricos y notas técnicas.

## Relaciones
- Vincula a [[DB_Tabla_Usuarios]]
- Apunta a nuevo [[DB_Tabla_Grados]]
- Controlada por [[Servicio_Maquina_Estados]]
- Registrada en [[DB_Bitacora_Auditoria]]
"@

    "DB_Tabla_Requisitos.md" = @"
# 📝 Tabla BD: Requisitos de Evaluación
#database #tabla

Matriz curricular de poomsaes, técnicas de patada, terminología en coreano y combate reglado.

## Relaciones
- Pertenece a cada [[DB_Tabla_Grados]]
- Validada por [[Regla_Matriz_Tecnica]]
"@

    "DB_Tabla_Asistencias.md" = @"
# 📅 Tabla BD: Asistencias y Registro de Entrenamiento
#database #tabla

Trazabilidad de asistencia diaria a clase presencial por alumno y sede.

## Relaciones
- Vinculada a [[DB_Tabla_Usuarios]] y [[DB_Tabla_Sedes]]
- Consultada por [[Regla_Asistencia_Minima]]
"@

    "DB_Tabla_Eventos.md" = @"
# 🏆 Tabla BD: Eventos, Exámenes y Torneos
#database #tabla

Fechas de calendario, jurados invitados y sedes sede de torneos.

## Relaciones
- Conecta con [[DB_Tabla_Sedes]]
- Mostrada en [[UI_Calendario_Eventos]]
"@

    "DB_Tabla_Roles_Permisos.md" = @"
# 🔑 Tabla BD: Roles y Permisos (RBAC)
#database #seguridad #tabla

Definición de roles: Administrador Global, Maestro de Sede, Alumno y Acudiente.

## Relaciones
- Asignada a [[DB_Tabla_Usuarios]]
- Verificada en [[Middleware_Autorizacion_RBAC]]
"@

    "DB_Bitacora_Auditoria.md" = @"
# 📋 Tabla BD: Bitácora de Auditoría e Inmutabilidad
#database #auditoria #seguridad

Caja negra del sistema. Almacena quién, cuándo y bajo qué jurado se aprobó cada cambio sensible de grado o usuario.

## Relaciones
- Escrita por [[Gestor_Transacciones_ACID]]
- Verificada por [[Servicio_Generador_Certificados]]
- Evidencia central en [[Flujo_Auditoria_y_Trazabilidad]]
"@

    "Cache_Memoria_Volatil.md" = @"
# ⚡ Persistencia: Memoria Volátil y Caché
#database #cache #rendimiento

Almacena sesiones activas, tokens de refresco y resultados de consultas frecuentes para reducir latencia de base de datos.

## Relaciones
- Conecta con [[Middleware_Autenticacion_JWT]]
- Conecta con [[API_Gateway_Enrutador]]
"@

    "Seguridad_Cifrado_Tokens.md" = @"
# 🔐 Seguridad: Cifrado y Firma de Tokens
#seguridad #criptografia

Módulo criptográfico para firma de JSON Web Tokens (algoritmo RS256/HS256) y hashing de contraseñas con Argon2/Bcrypt.

## Conexiones
- [[Middleware_Autenticacion_JWT]]
- [[Capa_Seguridad_Perimetral]]
"@

    "Flujo_Ascenso_y_Promocion.md" = @"
# 🥋 Flujo Neurálgico: Evaluación y Promoción de Grado
#flujo #proceso #cerebro

Muestra cómo viaja la información desde la interfaz hasta la base de datos durante un examen de ascenso:

1. El alumno o maestro abre el [[UI_Visualizador_Cinturones]].
2. Se envía la postulación al [[Controlador_Ascensos]].
3. El [[Servicio_Motor_Reglas]] valida:
   - [[Regla_Tiempo_Minimo_Grado]]
   - [[Regla_Asistencia_Minima]]
   - [[Regla_Matriz_Tecnica]]
4. La [[Servicio_Maquina_Estados]] transiciona el estado de la solicitud en [[DB_Tabla_Solicitudes_Ascenso]].
5. El jurado califica la evaluación en [[UI_Formularios_Operativos]].
6. El [[Gestor_Transacciones_ACID]] impacta atómicamente el nuevo cinturón en [[DB_Tabla_Usuarios]], genera el certificado con [[Servicio_Generador_Certificados]] y graba en [[DB_Bitacora_Auditoria]].
7. El [[Despachador_Notificaciones]] dispara la respuesta reactiva con animación hacia [[UI_Dashboard_Principal]].
"@

    "Flujo_Autenticacion_y_Sesion.md" = @"
# 🔐 Flujo Neurálgico: Autenticación y Autorización
#flujo #proceso #seguridad

1. Usuario ingresa credenciales en [[UI_Modulo_Autenticacion]].
2. [[Frontend_Validador_Cliente]] valida localmente y transmite por [[Frontend_Cliente_HTTP]].
3. [[API_Gateway_Enrutador]] recibe la petición HTTPS y la transfiere a [[Middleware_Autenticacion_JWT]].
4. Se verifica el hash en [[DB_Tabla_Usuarios]] y se consultan permisos en [[DB_Tabla_Roles_Permisos]].
5. Se emite un JWT firmado por [[Seguridad_Cifrado_Tokens]] y se guarda sesión en [[Cache_Memoria_Volatil]].
6. El token se inyecta en el [[Frontend_Gestor_Estado]] para hidratar la sesión del usuario.
"@

    "Flujo_Auditoria_y_Trazabilidad.md" = @"
# 📋 Flujo Neurálgico: Trazabilidad y Seguridad Forense
#flujo #proceso #auditoria

Garantiza ante el jurado calificador que el software cuenta con respaldo inmutable de cada decisión de negocio tomada por el cerebro:

- Cualquier cambio de estado orquestado por [[00_Cerebro_Central_del_Sistema]] pasa por el [[Gestor_Transacciones_ACID]].
- Se genera un registro inalterable en [[DB_Bitacora_Auditoria]].
- Permite reconstruir la línea temporal de ascensos y decisiones del doojang en cualquier momento.
"@
}

foreach ($item in $notes.GetEnumerator()) {
    $fileName = $item.Key
    $content = $item.Value
    
    foreach ($dir in $targetDirs) {
        $filePath = Join-Path $dir $fileName
        Set-Content -Path $filePath -Value $content -Encoding UTF8
    }
}

Write-Host "Todas las notas interconectadas han sido generadas con éxito."
