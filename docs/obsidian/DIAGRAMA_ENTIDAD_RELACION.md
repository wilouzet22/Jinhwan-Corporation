# Modelo Entidad-Relación (ER) — Jinhwa Corporation
> **Esquema de Base de Datos Relacional MariaDB / MySQL**  
> Formato nativo Mermaid para **Obsidian**, GitHub y documentación técnica.

---

## 1. Diagrama Entidad-Relación Completo (Mermaid)

```mermaid
erDiagram
    %% =============================================================
    %% ENTIDADES Y ATRIBUTOS
    %% =============================================================

    ESTUDIANTE {
        int id_estudiante PK
        int id_grado FK
        int id_categoria FK
        int id_grupo FK
        int id_maestro FK
        string nombre
        string apellido
        string tipo_documento
        string num_doc
        string telefono
        string correo
        string clave
        string foto_perfil
        date fecha_nacimiento
        decimal peso
        string division
        string eps
        string rh
        boolean activo
        timestamp created_at
    }

    MAESTRO {
        int id_maestro PK
        int id_grado FK
        int id_sede FK
        string nombre
        string apellido
        string tipo_documento
        string num_doc
        string telefono
        string foto_perfil
        text descripcion_perfil
        text logros
        boolean mostrar_en_web
        string correo
        string clave
        json permisos_extra
        boolean activo
    }

    ADMINISTRADOR {
        int id_administrador PK
        string nombre
        string apellido
        string correo
        string clave
        string foto_perfil
        json permisos_extra
        boolean activo
    }

    SEDES {
        int id_sede PK
        string nombre
        string direccion
        string telefono
        string ciudad
        string horario
        boolean activa
    }

    GRUPOS {
        int id_grupo PK
        int id_sede FK
        int id_maestro FK
        string nombre
        string horario
        string dias
        boolean activo
    }

    GRADOS {
        int id_grado PK
        string nombre
        string color
        int orden
        string tipo
    }

    CATEGORIAS {
        int id_categoria PK
        string nombre
        int edad_min
        int edad_max
    }

    CERTIFICADOS_ASCENSO {
        int id_certificado PK
        int id_estudiante FK
        int id_maestro FK
        string grado_anterior
        string grado_nuevo
        date fecha_examen
        text observaciones
        string folio
        timestamp creado_en
    }

    HISTORIAL_GRADOS {
        int id_historial PK
        int id_estudiante FK
        int id_grado FK
        int id_certificado FK
        date fecha_obtencion
    }

    CRONOGRAMAS_CLASE {
        int id_cronograma PK
        int id_grupo FK
        int id_maestro FK
        date fecha
        string objetivo
        text observaciones
        timestamp created_at
    }

    CLASE_EJERCICIOS {
        int id_clase_ejercicio PK
        int id_cronograma FK
        int id_ejercicio FK
        int series
        int repeticiones
        int duracion_minutos
        int orden
        text observaciones
    }

    EJERCICIOS {
        int id_ejercicio PK
        string nombre
        text descripcion
        string tipo
        string categoria_capacidad
        string video_url
    }

    EVENTOS {
        int id_evento PK
        int id_maestro FK
        int id_sede FK
        string titulo
        text descripcion
        datetime fecha_inicio
        datetime fecha_fin
        string lugar
        string tipo
    }

    GALERIA_MULTIMEDIA {
        int id_multimedia PK
        int id_maestro FK
        string url
        string tipo
        text descripcion
        timestamp created_at
    }

    TEORIAS {
        int id_teoria PK
        int id_tipo_teoria FK
        int id_grado FK
        string titulo
        text contenido
        text resumen
        string imagen
        int orden
    }

    TIPOS_TEORIA {
        int id_tipo_teoria PK
        string nombre
        text descripcion
        string icono
    }

    TEORIA_FAVORITOS {
        int id PK
        int id_estudiante FK
        int id_teoria FK
        timestamp fecha_guardado
    }

    %% =============================================================
    %% RELACIONES Y CARDINALIDADES (Chen / Crow's Foot)
    %% =============================================================

    %% Sedes y Grupos
    SEDES ||--o{ GRUPOS : "alberga"
    SEDES ||--o{ MAESTRO : "sede principal"
    SEDES ||--o{ EVENTOS : "lugar"

    %% Grupos y Maestros
    MAESTRO ||--o{ GRUPOS : "imparte"
    GRUPOS ||--o{ ESTUDIANTE : "inscribe"
    GRUPOS ||--o{ CRONOGRAMAS_CLASE : "agenda"

    %% Grados y Categorías
    GRADOS ||--o{ ESTUDIANTE : "cinturon actual"
    GRADOS ||--o{ MAESTRO : "dan o rango"
    GRADOS ||--o{ HISTORIAL_GRADOS : "otorga"
    GRADOS ||--o{ TEORIAS : "requerimiento"
    CATEGORIAS ||--o{ ESTUDIANTE : "clasifica"

    %% Certificados y Trayectoria
    ESTUDIANTE ||--o{ CERTIFICADOS_ASCENSO : "recibe"
    MAESTRO ||--o{ CERTIFICADOS_ASCENSO : "evalua y firma"
    ESTUDIANTE ||--o{ HISTORIAL_GRADOS : "posee historial"
    CERTIFICADOS_ASCENSO ||--|| HISTORIAL_GRADOS : "respalda (1 a 1)"

    %% Clases y Ejercicios Físicos
    MAESTRO ||--o{ CRONOGRAMAS_CLASE : "instructor"
    CRONOGRAMAS_CLASE ||--|{ CLASE_EJERCICIOS : "contiene"
    EJERCICIOS ||--o{ CLASE_EJERCICIOS : "detalla"

    %% Eventos y Contenidos
    MAESTRO ||--o{ EVENTOS : "organiza"
    MAESTRO ||--o{ GALERIA_MULTIMEDIA : "publica"

    %% Teorías y Favoritos
    TIPOS_TEORIA ||--o{ TEORIAS : "categoriza"
    ESTUDIANTE ||--o{ TEORIA_FAVORITOS : "marca"
    TEORIAS ||--o{ TEORIA_FAVORITOS : "guardada en"
```

---

## 2. Cardinalidades Representadas

| Símbolo | Significado | Ejemplo |
| :---: | :--- | :--- |
| `\|\|--o{` | **Uno a Muchos (1:N opcional)** | Una `SEDE` alberga de 0 a muchos `GRUPOS`. |
| `\|\|--\|{` | **Uno a Muchos (1:N obligatorio)** | Un `CRONOGRAMA` contiene al menos 1 `CLASE_EJERCICIO`. |
| `\|\|--\|\|` | **Uno a Uno (1:1 obligatorio)** | Cada `CERTIFICADO_ASCENSO` respalda exactamente 1 `HISTORIAL_GRADOS`. |
