# Manual de Usuario - Jinhwa Corporation
### Sistema de Gestion del Club de Taekwondo

> **Version:** 1.0
> **Fecha:** Agosto 2026
> **Sistema:** Jinhwa Corporation - Gestion Integral de Club de Taekwondo
> **URL de acceso:** http://localhost/jinwha

---

## Tabla de Contenidos

1. Introduccion
2. Requisitos del Sistema
3. Acceso al Sistema
4. Roles y Permisos
5. Portal Web Publico
6. Modulo del Deportista - Estudiante
7. Modulo del Maestro - Instructor
8. Modulo de Administracion
9. Gestion del Perfil Personal
10. Cierre de Sesion
11. Mensajes de Error Comunes

---

## 1. Introduccion

El **Sistema Jinhwa Corporation** es una plataforma web disenada para la gestion integral de un club de Taekwondo. Permite administrar miembros, sedes, material teorico de ascensos, galeria multimedia, eventos, noticias y mucho mas.

El sistema distingue **cuatro tipos de usuario**, cada uno con acceso a modulos especificos segun su rol dentro del club.

---

## 2. Requisitos del Sistema

Para usar el sistema solo necesitas:

| Requisito | Detalle |
|-----------|---------|
| **Navegador web** | Google Chrome (recomendado), Firefox, Edge o Safari actualizados |
| **Conexion a Internet** | Solo para recursos de fuentes y algunos elementos de la interfaz |
| **Cuenta activa** | Correo electronico y contrasena registrados y aprobados por el administrador |

No es necesario instalar ningun software adicional.

---

## 3. Acceso al Sistema

### 3.1 Iniciar Sesion

1. Abre tu navegador y dirigete a `http://localhost/jinwha`
2. Haz clic en el boton **"Iniciar Sesion"** en el menu superior o visita directamente `/login`
3. Ingresa tu **correo electronico** y **contrasena**
4. Haz clic en **"Entrar"**

El sistema te redirigira automaticamente al panel correspondiente a tu rol:

| Rol | Redirige a |
|-----|-----------|
| Administrador | `/admin/dashboard` |
| Maestro / Instructor | `/maestro/dashboard` |
| Deportista / Estudiante | `/estudiante/dashboard` |

### 3.2 Registro de Nuevo Miembro

1. En la pagina de inicio, haz clic en **"Registrarse"** o ve a `/registro`
2. Completa los campos obligatorios:
   - Numero de documento
   - Correo electronico
   - Contrasena
3. Haz clic en **"Enviar solicitud"**

> IMPORTANTE: Tu cuenta quedara en estado **pendiente de aprobacion**. El administrador del club debe aprobarla antes de que puedas iniciar sesion. Recibiras acceso una vez que sea aprobada.

### 3.3 Completar Registro (datos adicionales)

Despues del registro inicial, puede que el sistema te solicite completar informacion adicional (datos deportivos, fecha de nacimiento, EPS, etc.) en la pagina `/registro/completar`.

---

## 4. Roles y Permisos

El sistema cuenta con los siguientes roles:

| Rol | Descripcion | Acceso |
|-----|-------------|--------|
| **Administrador** | Gestion total del sistema | Panel `/admin` con todos los modulos |
| **Maestro** | Instructor del club | Panel `/maestro` con alumnos y ascensos |
| **Deportista** | Alumno activo del club | Panel `/estudiante` con material y perfil |
| **Visitante** | Persona sin cuenta | Solo portal web publico |

Los maestros pueden tener **permisos adicionales** activados por el administrador para acceder a modulos especificos como sedes, registros, ascensos, calendario, galeria y reportes.

---

## 5. Portal Web Publico

El portal publico es accesible para cualquier visitante sin necesidad de iniciar sesion.

### 5.1 Pagina Principal (/)
- Presentacion del club Jinhwa Corporation
- Accesos rapidos a las secciones del sitio
- Boton de acceso al login

### 5.2 Sedes (/sedes)
- Listado de todas las sedes del club
- Direccion, telefono y horarios de cada sede

### 5.3 Nosotros (/nosotros)
- Historia y mision del club
- Informacion sobre la disciplina del Taekwondo

### 5.4 Galeria Multimedia (/galeria)
- Videos y fotos del club
- Integracion con YouTube y otros medios

### 5.5 Miembros / Instructores (/miembros)
- Perfiles publicos de los maestros e instructores del club
- Descripcion, logros y grados de cada instructor (solo los que tienen activado mostrar_en_web)

---

## 6. Modulo del Deportista / Estudiante

Al iniciar sesion como deportista, acceders a tu panel personal en `/estudiante/dashboard`.

### 6.1 Dashboard Personal

Muestra un resumen de tu informacion:
- Tu nombre, foto de perfil y grado actual (cinturon)
- Tu categoria de competencia
- Sede a la que perteneces
- Accesos rapidos a los demas modulos

### 6.2 Material de Estudio (/estudiante/estudio)

Consulta el contenido teorico organizado por grado (cinturon):

1. El sistema carga automaticamente la teoria correspondiente a tu grado actual
2. Puedes ver el contenido de cada teoria
3. Si hay videos disponibles, aparecera un enlace directo a YouTube u otros recursos

### 6.3 Solicitar Ascenso de Grado

Para solicitar un ascenso de grado:

1. Ve a tu dashboard o al modulo de estudio
2. Busca la opcion **"Solicitar Ascenso"**
3. Selecciona el grado al que deseas ascender
4. Agrega observaciones si lo deseas
5. Envia la solicitud

> La solicitud quedara en estado **"Pendiente"** hasta que un maestro la evalue.

### 6.4 Historial de Ascensos (/estudiante/historial)

Consulta el historial completo de tus solicitudes de ascenso:
- Fecha de solicitud
- Grado solicitado
- Estado: Pendiente, Aprobado o Rechazado
- Observaciones del maestro evaluador
- Fecha de resolucion

### 6.5 Calendario de Eventos (/usuario/calendario)

Visualiza todos los eventos del club en un calendario interactivo:
- Torneos, competencias, seminarios y actividades
- Haz clic en cualquier evento para ver los detalles (titulo, descripcion, fecha inicio/fin)

---

## 7. Modulo del Maestro / Instructor

Al iniciar sesion como maestro, acceders a `/maestro/dashboard`.

### 7.1 Dashboard del Maestro

Muestra estadisticas y resumen del club:
- Numero de alumnos activos
- Solicitudes de ascenso pendientes
- Accesos rapidos a todos los modulos del maestro

### 7.2 Gestion de Alumnos (/maestro/alumnos)

Visualiza la lista de todos los alumnos:
- Nombre completo, grado actual y categoria
- Sede a la que pertenecen
- Informacion de contacto

### 7.3 Solicitudes de Ascenso (/maestro/solicitudes-ascenso)

Como maestro, puedes crear y gestionar solicitudes de ascenso:

**Crear una solicitud:**
1. Ve a `/maestro/solicitudes-ascenso`
2. Selecciona el alumno
3. Indica el grado actual y el grado solicitado
4. Agrega observaciones
5. Haz clic en **"Registrar Solicitud"**

**Ver solicitudes existentes:**
- Listado de todas las solicitudes con su estado actual
- Filtros por estado (pendiente, aprobado, rechazado)

> Las solicitudes de ascenso son aprobadas finalmente por el **Administrador** desde el modulo de registros.

### 7.4 Calendario (/usuario/calendario)

Los maestros tambien tienen acceso al calendario de eventos del club.

---

## 8. Modulo de Administracion

El modulo de administracion esta disponible en `/admin/dashboard` y es exclusivo para usuarios con rol **Administrador**.

### 8.1 Dashboard Administrativo (/admin/dashboard)

Panel principal con estadisticas globales del club:
- Total de miembros activos
- Distribucion por sedes
- Solicitudes pendientes
- Ultimas actividades

### 8.2 Gestion de Miembros (/admin/miembros)

CRUD completo de todos los miembros del club.

**Agregar un nuevo miembro:**
1. Haz clic en **"Nuevo Miembro"**
2. Completa la informacion personal (nombre, apellido, documento, telefono)
3. Asigna la sede y el rol
4. Si es deportista: asigna grado, categoria, fecha de nacimiento, peso, EPS, RH
5. Haz clic en **"Guardar"**

**Editar miembro:**
1. Busca el miembro en el listado
2. Haz clic en el icono de edicion
3. Modifica los datos necesarios y guarda

**Eliminar miembro:**
- Individual: clic en el icono de basura en la fila del miembro
- Masiva: selecciona varios registros con los checkboxes y usa "Eliminar seleccionados"

> ADVERTENCIA: La eliminacion de un miembro borra tambien sus credenciales y perfil (ON DELETE CASCADE).

### 8.3 Gestion de Sedes (/admin/sedes)

CRUD de sedes fisicas del club:
1. Haz clic en **"Nueva Sede"**
2. Completa: nombre, direccion, telefono, horario
3. Guarda los cambios

### 8.4 Gestion de Contenido Teorico (/admin/ascensos)

Administra el material de estudio por grado:
1. Selecciona el grado al que aplica
2. Selecciona el tipo de teoria (General, etc.)
3. Escribe el nombre y contenido
4. Agrega una URL de video si aplica
5. Guarda

### 8.5 Solicitudes de Registro y Ascenso (/admin/registros)

Este modulo muestra dos tipos de solicitudes:

**Nuevos Registros (activo = 0):**
Personas que se han registrado pero aun no estan aprobadas:
- Aprobar: Activa la cuenta (activo = 1) y permite el acceso
- Rechazar: Elimina el registro permanentemente

**Solicitudes de Ascenso Pendientes:**
Solicitudes creadas por maestros para ascender a un alumno:
- Aprobar: Actualiza el grado del deportista en perfil_deportistas
- Rechazar: Marca la solicitud como rechazada

### 8.6 Calendario de Eventos (/admin/calendario)

Gestiona los eventos del club de forma visual:
1. Haz clic en una fecha del calendario o en "Nuevo Evento"
2. Completa: titulo, descripcion, fecha de inicio y fecha de fin
3. Guarda el evento

### 8.7 Galeria Multimedia (/admin/galeria)

Administra el contenido multimedia del club:
1. Ve a `/admin/galeria/crear`
2. Ingresa una URL (YouTube, imagen, etc.)
3. Agrega titulo y descripcion opcionales
4. Guarda

### 8.8 Reportes del Club (/admin/reportes)

Visualiza reportes estadisticos del club:
- Distribucion de miembros por sede, grado y categoria
- Historial de ascensos aprobados/rechazados

### 8.9 Perfiles Publicos de Instructores (/admin/perfiles-publicos)

Controla que maestros aparecen en el portal web publico:
- Activa/desactiva la visibilidad de cada instructor (mostrar_en_web)
- Edita la descripcion del perfil y los logros

---

## 9. Gestion del Perfil Personal

Todos los usuarios pueden editar su perfil personal.

**Acceso:** /perfil o /usuario/perfil o /estudiante/perfil

**Datos editables:**
- Nombre y apellido
- Telefono
- Foto de perfil
- Para deportistas: peso, EPS, RH, fecha de nacimiento

**Pasos para actualizar:**
1. Ve a tu perfil desde el menu de navegacion
2. Modifica los campos que deseas cambiar
3. Si deseas cambiar la foto: selecciona un nuevo archivo de imagen
4. Haz clic en "Guardar cambios"

---

## 10. Cierre de Sesion

Para salir del sistema de forma segura:
1. Haz clic en tu nombre o avatar en la esquina superior derecha del panel
2. Selecciona "Cerrar Sesion"
3. O ve directamente a `/logout`

> La sesion se cierra automaticamente despues de 30 minutos de inactividad o 8 horas de sesion continua por razones de seguridad.

---

## 11. Mensajes de Error Comunes

| Mensaje / Codigo | Causa | Solucion |
|-----------------|-------|----------|
| error=1 al login | Contrasena incorrecta | Verifica tu contrasena |
| error=2 al login | Correo no encontrado | Verifica que tu correo este registrado |
| error=pending | Cuenta pendiente de aprobacion | Espera que el administrador apruebe tu registro |
| error=timeout | Sesion expirada por inactividad | Inicia sesion nuevamente |
| error=security | Posible robo de sesion (User-Agent cambio) | Inicia sesion desde el mismo dispositivo |
| error=expired | Sesion de mas de 8 horas | Inicia sesion nuevamente |
| msg=access_denied | No tienes permiso para esa seccion | Contacta al administrador |
| 404 Not Found | Pagina no encontrada | Verifica la URL |

---

## Soporte

Para problemas tecnicos o dudas sobre el sistema, contacta al administrador del club.

---

Manual generado para el Sistema de Gestion Jinhwa Corporation - Version 1.0 - Agosto 2026
