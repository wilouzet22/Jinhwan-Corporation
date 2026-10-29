# Diagrama de Clases UML — Jinhwa Corporation
> **Arquitectura de Software y Modelo de Dominio de Base de Datos**  
> Formato nativo Mermaid para **Obsidian**, GitHub y herramientas Markdown estándar.

---

## 1. Diagrama de Clases UML (Mermaid)

```mermaid
classDiagram
    direction TB

    %% -------------------------------------------------------------
    %% NÚCLEO DE USUARIOS Y ROLES (Herencia de Actores)
    %% -------------------------------------------------------------
    class Administrador {
        -int id_administrador
        -string nombre
        -string apellido
        -string correo
        -string clave
        -string foto_perfil
        -json permisos_extra
        -boolean activo
        +gestionarSedes() boolean
        +gestionarGrupos() boolean
        +gestionarMaestros() boolean
        +emitirDiplomasAscenso() boolean
        +aprobarPreRegistros() boolean
        +generarReportesGenerales() Reporte
    }

    class Maestro {
        -int id_maestro
        -int id_grado
        -int id_sede
        -string nombre
        -string apellido
        -string tipo_documento
        -string num_doc
        -string telefono
        -string foto_perfil
        -text descripcion_perfil
        -text logros
        -boolean mostrar_en_web
        -string correo
        -string clave
        -json permisos_extra
        -boolean activo
        +autenticar(correo, clave) boolean
        +evaluarAscenso(id_estudiante, id_grado) CertificadoAscenso
        +planificarClase(id_grupo, fecha, ejercicios) CronogramaClase
        +listarAlumnosAsignados() List~Estudiante~
        +publicarEnGaleria(url, descripcion) boolean
    }

    class Estudiante {
        -int id_estudiante
        -int id_grado
        -int id_categoria
        -int id_grupo
        -int id_maestro
        -string nombre
        -string apellido
        -string tipo_documento
        -string num_doc
        -string telefono
        -string correo
        -string clave
        -string foto_perfil
        -date fecha_nacimiento
        -decimal peso
        -string division
        -string eps
        -string rh
        -boolean activo
        -timestamp created_at
        +autenticar(correo, clave) boolean
        +consultarHistorialAscensos() List~CertificadoAscenso~
        +listarTeoriasFavoritas() List~Teoria~
        +marcarTeoriaFavorita(id_teoria) boolean
        +actualizarPerfil() boolean
    }

    %% -------------------------------------------------------------
    %% CATÁLOGOS Y ESTRUCTURA MARCIAL
    %% -------------------------------------------------------------
    class Grado {
        -int id_grado
        -string nombre
        -string color
        -int orden
        -string tipo
        +obtenerPorOrden(orden) Grado
        +listarGrados() List~Grado~
    }

    class Categoria {
        -int id_categoria
        -string nombre
        -int edad_min
        -int edad_max
        +clasificarPorEdad(edad) Categoria
        +listarCategorias() List~Categoria~
    }

    %% -------------------------------------------------------------
    %% OPERACIÓN DOJANCS Y ENTRENAMIENTO
    %% -------------------------------------------------------------
    class Sede {
        -int id_sede
        -string nombre
        -string direccion
        -string telefono
        -string ciudad
        -string horario
        -boolean activa
        +crearSede() boolean
        +editarSede() boolean
        +listarSedes() List~Sede~
    }

    class Grupo {
        -int id_grupo
        -int id_sede
        -int id_maestro
        -string nombre
        -string horario
        -string dias
        -boolean activo
        +asignarMaestro(id_maestro) boolean
        +inscribirAlumno(id_estudiante) boolean
        +listarAlumnos() List~Estudiante~
    }

    class CronogramaClase {
        -int id_cronograma
        -int id_grupo
        -int id_maestro
        -date fecha
        -string objetivo
        -text observaciones
        -timestamp created_at
        +programarClase() boolean
        +agregarEjercicio(id_ejercicio, series, reps) boolean
        +consultarPorFecha(fecha) List~CronogramaClase~
    }

    class ClaseEjercicio {
        -int id_clase_ejercicio
        -int id_cronograma
        -int id_ejercicio
        -int series
        -int repeticiones
        -int duracion_minutos
        -int orden
        -text observaciones
        +actualizarSeriesReps() boolean
    }

    class Ejercicio {
        -int id_ejercicio
        -string nombre
        -text descripcion
        -string tipo
        -string categoria_capacidad
        -string video_url
        +crearEjercicio() boolean
        +listarPorCapacidad(capacidad) List~Ejercicio~
    }

    %% -------------------------------------------------------------
    %% TRAYECTORIA Y CERTIFICACIÓN MARCIAL
    %% -------------------------------------------------------------
    class CertificadoAscenso {
        -int id_certificado
        -int id_estudiante
        -int id_maestro
        -string grado_anterior
        -string grado_nuevo
        -date fecha_examen
        -text observaciones
        -string folio
        -timestamp creado_en
        +generarFolio() string
        +generarDiplomaHTML() string
        +registrarCertificado() boolean
    }

    class HistorialGrados {
        -int id_historial
        -int id_estudiante
        -int id_grado
        -int id_certificado
        -date fecha_obtencion
        +registrarAscenso() boolean
        +consultarHistorial(id_estudiante) List~HistorialGrados~
    }

    %% -------------------------------------------------------------
    %% COMUNICACIÓN, EVENTOS Y TEORÍA
    %% -------------------------------------------------------------
    class Evento {
        -int id_evento
        -int id_maestro
        -int id_sede
        -string titulo
        -text descripcion
        -datetime fecha_inicio
        -datetime fecha_fin
        -string lugar
        -string tipo
        +crearEvento() boolean
        +listarEventos() List~Evento~
    }

    class GaleriaMultimedia {
        -int id_multimedia
        -int id_maestro
        -string url
        -string tipo
        -text descripcion
        -timestamp created_at
        +subirMedia() boolean
        +listarPublicacionesWeb() List~GaleriaMultimedia~
    }

    class Teoria {
        -int id_teoria
        -int id_tipo_teoria
        -int id_grado
        -string titulo
        -text contenido
        -text resumen
        -string imagen
        -int orden
        +obtenerPorGrado(id_grado) List~Teoria~
        +agregarTeoria() boolean
    }

    class TipoTeoria {
        -int id_tipo_teoria
        -string nombre
        -text descripcion
        -string icono
        +listarTipos() List~TipoTeoria~
    }

    class TeoriaFavoritos {
        -int id
        -int id_estudiante
        -int id_teoria
        -timestamp fecha_guardado
        +guardarFavorito() boolean
        +eliminarFavorito() boolean
    }

    %% =============================================================
    %% RELACIONES FORMALES UML
    %% =============================================================
    
    %% Herencia (Generalización)
    Administrador --|> Maestro : extends

    %% Realización / Interfaz
    Maestro ..|> GaleriaMultimedia : publica

    %% Dependencias
    CronogramaClase ..> Ejercicio : usa
    Administrador ..> CertificadoAscenso : emite

    %% Agregaciones (◇ Débiles: el componente puede existir sin el contenedor)
    Sede "1" o-- "0..*" Grupo : Alberga
    Grupo "1" o-- "0..*" Estudiante : Inscribe
    TipoTeoria "1" o-- "0..*" Teoria : Clasifica

    %% Composiciones (◆ Fuertes: el componente deja de existir sin el contenedor)
    Estudiante "1" *-- "0..*" CertificadoAscenso : Recibe Diploma
    Estudiante "1" *-- "0..*" HistorialGrados : Historial
    Grupo "1" *-- "0..*" CronogramaClase : Agenda Clase
    CronogramaClase "1" *-- "1..*" ClaseEjercicio : Contiene

    %% Asociaciones Estructurales
    Maestro "1" --> "0..*" Grupo : Imparte
    Sede "1" --> "0..*" Maestro : Sede Principal
    Grado "1" --> "0..*" Estudiante : Cinturón Actual
    Grado "1" --> "0..*" Maestro : Dan / Rango
    Categoria "1" --> "0..*" Estudiante : Clasificado en
    Maestro "1" --> "0..*" CertificadoAscenso : Evalúa y Firma
    Grado "1" --> "0..*" HistorialGrados : Grado Otorgado
    Maestro "1" --> "0..*" CronogramaClase : Instructor
    Ejercicio "1" --> "0..*" ClaseEjercicio : Detalla
    Maestro "1" --> "0..*" Evento : Organiza
    Sede "1" --> "0..*" Evento : Lugar
    Grado "1" --> "0..*" Teoria : Requisito de
    Estudiante "1" --> "0..*" TeoriaFavoritos : Marca Favorita
    Teoria "1" --> "0..*" TeoriaFavoritos : Guardada en
    CertificadoAscenso "1" --> "1" HistorialGrados : Respalda
```

---

## 2. Leyenda de Relaciones UML

| Notación | Símbolo UML | Tipo de Relación | Descripción en Jinhwa Corporation |
| :---: | :---: | :--- | :--- |
| `--\|>` | $\triangle$ | **Herencia** | `Administrador` hereda capacidades operativas de `Maestro`. |
| `..\|>` | $\dashrightarrow \triangle$ | **Realización** | `Maestro` implementa publicaciones en `GaleriaMultimedia`. |
| `..>` | $\dashrightarrow$ | **Dependencia** | `CronogramaClase` usa `Ejercicio`; `Administrador` emite certificados. |
| `o--` | $\diamondsuit$ | **Agregación** | `Sede` alberga `Grupo`; `Grupo` inscribe `Estudiante`. |
| `*--` | $\blacklozenge$ | **Composición** | `Estudiante` posee `Certificados` e `Historial`. `Grupo` posee clases. |
| `-->` | $\rightarrow$ | **Asociación** | Vínculos estructurales directos (maestro imparte grupo, etc.). |
