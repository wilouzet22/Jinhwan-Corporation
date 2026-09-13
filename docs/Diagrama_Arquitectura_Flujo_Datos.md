# 📐 Diagrama de Arquitectura y Flujo de Información

> **Proyecto de Grado**  
> *Arquitectura de Software en Capas: Frontend, Backend y Persistencia*

---

## 1. Diagrama de Flujo del Sistema

```mermaid
flowchart TB
    %% ==========================================================
    %% CAPA 1: PRESENTACIÓN E INTERFACES DE USUARIO (FRONTEND)
    %% ==========================================================
    subgraph FRONTEND ["🖥️ CAPA DE PRESENTACIÓN (FRONTEND)"]
        direction TB
        subgraph UI_VIEWS ["Vistas y Experiencia de Usuario"]
            V_AUTH["🔐 Módulo de Autenticación & Acceso"]
            V_DASH["📊 Tablero Principal (Dashboard)"]
            V_FORM["📝 Formularios & Gestión Operativa"]
            V_REP["📈 Informes, Métricas & Exportación"]
        end

        subgraph FE_LOGIC ["Lógica de Cliente & Estado"]
            STATE["State Management (Store / React State)"]
            VALID_FE["Validación Previa de Formularios"]
            HTTP_CLIENT["Cliente HTTP (Axios / Fetch Interceptors)"]
        end

        UI_VIEWS --> STATE
        STATE --> VALID_FE
        VALID_FE --> HTTP_CLIENT
    end

    %% CANAL SEGURO DE COMUNICACIÓN
    HTTP_CLIENT ==>|"Solicitud HTTPS / REST API (Payload JSON + JWT)"| API_GW

    %% ==========================================================
    %% CAPA 2: LÓGICA DE NEGOCIO Y SERVICIOS (BACKEND)
    %% ==========================================================
    subgraph BACKEND ["⚙️ CAPA LÓGICA DEL SISTEMA (BACKEND API)"]
        direction TB
        subgraph GATEWAY ["Filtros & Seguridad Perimetral"]
            API_GW["🌐 API Gateway / Enrutador Principal"]
            AUTH_MW["🛡️ Middleware de Autorización (JWT / RBAC)"]
            SANITY["🧹 Sanitización & Validador DTO"]
        end

        subgraph CORE_LOGIC ["Servicios y Dominio"]
            CTRL["Controladores REST"]
            SERV["Servicio de Reglas de Negocio"]
            CALC["Motor de Procesamiento & Cálculos"]
        end

        subgraph ORM_LAYER ["Capa de Abstracción de Datos"]
            REPO["Patrón Repositorio / ORM"]
            TX_MGR["Gestor de Transacciones (ACID)"]
        end

        API_GW --> AUTH_MW
        AUTH_MW --> SANITY
        SANITY --> CTRL
        CTRL --> SERV
        SERV --> CALC
        SERV --> REPO
        REPO --> TX_MGR
    end

    %% ACCESO A PERSISTENCIA
    TX_MGR ==>|"Lectura / Escritura SQL / Conexión Pool"| DB_ENGINE

    %% ==========================================================
    %% CAPA 3: PERSISTENCIA Y GESTIÓN DE DATOS (DATABASE)
    %% ==========================================================
    subgraph PERSISTENCE ["🗄️ CAPA DE PERSISTENCIA (BASE DE DATOS)"]
        direction TB
        DB_ENGINE[("⚙️ Motor de Base de Datos Relacional")]

        subgraph SCHEMAS ["Esquemas de Almacenamiento"]
            TB_USERS[("👤 Usuarios, Perfiles & Roles")]
            TB_BUSINESS[("📦 Entidades Principales & Operaciones")]
            TB_REL[("🔗 Relaciones & Tablas Transaccionales")]
            TB_AUDIT[("📋 Historial, Auditoría & Trazabilidad")]
        end

        DB_ENGINE --> TB_USERS
        DB_ENGINE --> TB_BUSINESS
        DB_ENGINE --> TB_REL
        DB_ENGINE --> TB_AUDIT
    end

    %% ==========================================================
    %% RETORNO DE LA INFORMACIÓN AL USUARIO
    %% ==========================================================
    DB_ENGINE -.->|"Dataset persistido / Confirmación ACID"| TX_MGR
    CTRL -.->|"Respuesta HTTP (Status 200/201/400 + JSON Response)"| HTTP_CLIENT
    HTTP_CLIENT -.->|"Actualización Reactiva del Estado"| STATE
    STATE -.->|"Renderizado de Datos & Notificaciones UI"| UI_VIEWS

    %% ==========================================================
    %% ESTILOS VISUALES MODERNOS Y ACADÉMICOS
    %% ==========================================================
    classDef feStyle fill:#1e293b,stroke:#38bdf8,stroke-width:2px,color:#f8fafc,font-weight:600;
    classDef beStyle fill:#1e1b4b,stroke:#818cf8,stroke-width:2px,color:#f8fafc,font-weight:600;
    classDef dbStyle fill:#064e3b,stroke:#34d399,stroke-width:2px,color:#f8fafc,font-weight:600;

    class V_AUTH,V_DASH,V_FORM,V_REP,STATE,VALID_FE,HTTP_CLIENT feStyle;
    class API_GW,AUTH_MW,SANITY,CTRL,SERV,CALC,REPO,TX_MGR beStyle;
    class DB_ENGINE,TB_USERS,TB_BUSINESS,TB_REL,TB_AUDIT dbStyle;
```

---

## 2. Guía de Sustentación Académica

### A. Capa de Presentación (Frontend)
- **Desacoplamiento visual:** Las vistas (`UI_VIEWS`) no se comunican directamente con el servidor.
- **Manejo de Estado Centralizado:** Toda interacción pasa por un gestor de estado (`STATE`) que controla el ciclo de vida de los datos antes de cualquier petición.
- **Validación Temprana:** Se valida en cliente para ahorrar ancho de banda y mitigar peticiones erróneas antes de salir a la red.

### B. Canal Seguro de Transporte
- **Cifrado y Autenticación:** Protocolo HTTPS seguro transportando payloads en formato JSON junto con tokens de sesión (JWT) en encabezados de autorización.

### C. Capa Lógica y Procesamiento (Backend)
- **Seguridad Perimetral:** El `API Gateway` filtra mediante middlewares de autenticación y autorización basada en roles (RBAC).
- **Sanitización DTO:** Se limpia y valida cada parámetro de entrada para evitar inyecciones y datos anómalos.
- **Capa de Dominio & Servicios:** La lógica de negocio está aislada de los controladores, manteniendo alta cohesión y bajo acoplamiento.
- **Abstracción ORM / Transacciones ACID:** Las operaciones atómicas garantizan consistencia y aislamiento en todo momento.

### D. Capa de Persistencia (Base de Datos)
- **Normalización y Estructura:** Modelado relacional organizado en entidades de usuarios/roles, lógica transaccional de negocio y logs de auditoría para trazabilidad.

### E. Ciclo de Retorno Reactivo
- Las líneas discontinuas representan la respuesta: confirmación de la base de datos $\rightarrow$ código de estado HTTP adecuado $\rightarrow$ cliente HTTP $\rightarrow$ actualización reactiva de la interfaz con notificación visual instantánea al usuario.
