# 🧠 Arquitectura del Cerebro del Software (Core Engine & Decision System)

> **Proyecto de Grado**  
> *Modelo Conceptual y Operativo del Motor Central de Negocio, Decisión y Evaluación*

---

## 1. Diagrama de Flujo: El "Cerebro" del Software en Obsidian

```mermaid
flowchart TB
    %% ==========================================================
    %% ENTRADA Y PERCEPCIÓN: FRONTEND & TELEMETRÍA
    %% ==========================================================
    subgraph SENSORS ["📥 CAPA DE PERCEPCIÓN Y ENTRADA (FRONTEND)"]
        direction LR
        IN_REQ["🥋 Solicitud de Ascenso / Evaluación"]
        IN_ATT["📅 Registro de Asistencia & Horas Sede"]
        IN_EVAL["📝 Calificación Técnica de Maestros (Poomsae / Combate)"]
        IN_ADMIN["⚙️ Configuración Curricular de Grados & Sedes"]
    end

    %% ==========================================================
    %% EL CEREBRO DEL SOFTWARE: CORE DECISION & PROCESSING ENGINE
    %% ==========================================================
    subgraph BRAIN ["🧠 EL CEREBRO DEL SOFTWARE (CORE SYSTEM ENGINE)"]
        direction TB

        %% CONTROL DE ACCESO Y CONTEXTO
        subgraph BRAIN_CTX ["1. Constructor de Contexto e Identidad"]
            AUTH_CHK{"🛡️ Guardia de Seguridad"}
            RBAC["Motor de Permisos (Alumno | Maestro | Director de Sede)"]
            CTX_BUILDER["Ensamblador de Contexto del Alumno"]
        end

        %% MOTOR DE REGLAS DE NEGOCIO (RULE ENGINE)
        subgraph RULE_ENGINE ["2. Motor de Reglas de Negocio y Elegibilidad"]
            R_TIME{"⏳ ¿Tiempo Mínimo en Grado Cumplido?"}
            R_ATT{"📊 ¿Asistencia >= 85% Requerida?"}
            R_REQS{"🥋 ¿Matriz de Requisitos Aprobada? (Poomsae/Técnica/Teoría)"}
            RULE_RESOLVER["Evaluador Multicriterio de Decisión"]
        end

        %% MÁQUINA DE ESTADOS FINITOS (STATE MACHINE)
        subgraph WORKFLOW ["3. Orquestador de Estados y Transiciones"]
            SM_START["[Estado: Solicitud Iniciada]"]
            SM_PENDING["[Estado: Pendiente de Jurado]"]
            SM_EVAL["[Estado: En Examen Técnico]"]
            SM_RESULT{"⚖️ Veredicto"}
            SM_APPROVED["🏆 [Aprobado: Promoción de Cinturón]"]
            SM_REJECTED["❌ [Rechazado: Retroalimentación]"]
        end

        %% MOTOR DE ANALÍTICA Y CÁLCULO
        subgraph ANALYTICS ["4. Motor de Procesamiento & Analítica de Rendimiento"]
            CALC_PROGRESS["Algoritmo de % de Avance hacia Siguiente Grado"]
            METRICS_GEN["Generador de Métricas de Retención y Sede"]
            EXP_PREDICT["Proyección Predictiva para Grados Dan (Cinturón Negro)"]
        end

        %% DESPACHADOR DE EVENTOS
        subgraph DISPATCHER ["5. Despachador de Eventos & Efectos Secundarios"]
            EVT_CERT["📜 Generador de Folio y Certificado Digital"]
            EVT_NOTIF["🔔 Notificación Push / Feedback Visual"]
            EVT_SYNC["🔄 Sincronizador de Historial Marcial"]
        end

        %% Conexiones internas del cerebro
        AUTH_CHK --> RBAC
        RBAC --> CTX_BUILDER
        CTX_BUILDER ==> RULE_RESOLVER

        RULE_RESOLVER --> R_TIME
        RULE_RESOLVER --> R_ATT
        RULE_RESOLVER --> R_REQS

        R_TIME & R_ATT & R_REQS ==> SM_START
        SM_START --> SM_PENDING
        SM_PENDING --> SM_EVAL
        SM_EVAL --> SM_RESULT

        SM_RESULT -- "Cumple Criterios" --> SM_APPROVED
        SM_RESULT -- "No Cumple" --> SM_REJECTED

        SM_APPROVED ==> EVT_CERT
        SM_APPROVED ==> CALC_PROGRESS
        SM_REJECTED ==> EVT_NOTIF
        
        CALC_PROGRESS --> METRICS_GEN
        METRICS_GEN --> EXP_PREDICT

        EVT_CERT --> EVT_SYNC
        EVT_SYNC --> EVT_NOTIF
    end

    %% ==========================================================
    %% MEMORIA Y PERSISTENCIA: ALMACÉN DE CONOCIMIENTO
    %% ==========================================================
    subgraph MEMORY ["💾 MEMORIA Y PERSISTENCIA (BASE DE DATOS & AUDIT)"]
        direction TB
        DB_TRANSACT[("🗄️ Base de Datos Transaccional (Miembros, Sedes, Grados)")]
        DB_AUDIT[("📋 Caja Negra / Bitácora Inmutable de Ascensos")]
        CACHE_MEM[("⚡ Memoria Volátil / Caché de Estado Activo")]
    end

    %% ==========================================================
    %% FLUJO BIDIRECCIONAL ENTRE CAPAS
    %% ==========================================================
    IN_REQ & IN_ATT & IN_EVAL & IN_ADMIN ==>|"Entrada de Datos / Acciones"| AUTH_CHK

    CTX_BUILDER <-->|"Consulta de Perfil & Historial"| DB_TRANSACT
    CTX_BUILDER <-->|"Lectura de Sesión Rápida"| CACHE_MEM

    EVT_SYNC ==>|"Commit Transaccional ACID (Nuevo Grado)"| DB_TRANSACT
    EVT_SYNC ==>|"Registro Criptográfico / Auditoría"| DB_AUDIT

    EVT_NOTIF -.->|"Feedback Reactivo / Confetti / Estado Actualizado"| SENSORS

    %% ==========================================================
    %% ESTILOS VISUALES DE ALTA DEFINICIÓN
    %% ==========================================================
    classDef sensorStyle fill:#0f172a,stroke:#38bdf8,stroke-width:2px,color:#f8fafc;
    classDef ctxStyle fill:#1e1b4b,stroke:#818cf8,stroke-width:2px,color:#f8fafc;
    classDef ruleStyle fill:#311042,stroke:#c084fc,stroke-width:2px,color:#f8fafc;
    classDef wfStyle fill:#14532d,stroke:#4ade80,stroke-width:2px,color:#f8fafc;
    classDef anaStyle fill:#1c1917,stroke:#fbbf24,stroke-width:2px,color:#f8fafc;
    classDef dispStyle fill:#3b0764,stroke:#e879f9,stroke-width:2px,color:#f8fafc;
    classDef memStyle fill:#064e3b,stroke:#34d399,stroke-width:2px,color:#f8fafc;

    class IN_REQ,IN_ATT,IN_EVAL,IN_ADMIN sensorStyle;
    class AUTH_CHK,RBAC,CTX_BUILDER ctxStyle;
    class R_TIME,R_ATT,R_REQS,RULE_RESOLVER ruleStyle;
    class SM_START,SM_PENDING,SM_EVAL,SM_RESULT,SM_APPROVED,SM_REJECTED wfStyle;
    class CALC_PROGRESS,METRICS_GEN,EXP_PREDICT anaStyle;
    class EVT_CERT,EVT_NOTIF,EVT_SYNC dispStyle;
    class DB_TRANSACT,DB_AUDIT,CACHE_MEM memStyle;
```

---

## 2. Argumentación Académica para la Sustentación

Ante el tribunal o jurado evaluador, defender el **"Cerebro del Software"** demuestra que el sistema no es un simple CRUD de base de datos, sino una **arquitectura inteligente orientada al dominio (Domain-Driven Design)**.

### A. Submódulo 1: Ingestión y Constructor de Contexto
- **Propósito:** Ninguna decisión se toma con datos aislados. El constructor de contexto toma la solicitud del usuario, valida su identidad criptográfica (JWT) y reconstruye su estado actual (antigüedad, rol y sede asignada).
- **Justificación:** Protege la integridad del sistema impidiendo que clientes maliciosos manipulen parámetros locales.

### B. Submódulo 2: Motor de Reglas de Negocio (Rule Engine)
- **Patrón utilizado:** *Specification Pattern / Rules Engine*.
- **Lógica:** Desacopla las condiciones de aprobación en evaluadores atómicos:
  1. Verificación temporal de permanencia mínima en grado.
  2. Verificación de asistencia física/disciplinar (mínimo 85%).
  3. Matriz de competencias técnicas (Poomsae, Kyorugi/Combate, Teoría marcial).

### C. Submódulo 3: Máquina de Estados Finitos (Workflow State Machine)
- **Propósito:** El ascenso de un alumno no es una modificación directa en la base de datos; es un **proceso formal con estados finitos deterministas**:
  $$\text{Iniciada} \rightarrow \text{Pendiente} \rightarrow \text{Evaluación Técnica} \rightarrow \{\text{Aprobada} \mid \text{Rechazada}\}$$
- **Beneficio Académico:** Elimina inconsistencias, estados intermedios inválidos y garantiza que ningún grado sea otorgado sin la intervención explícita del jurado evaluador.

### D. Submódulo 4: Motor Analítico y Métricas
- **Propósito:** Calcula dinámicamente el porcentaje de avance, la tasa de deserción por grado y la proyección de graduación a cinturón negro (Grados Dan).

### E. Submódulo 5: Despachador de Eventos (Event-Driven Architecture)
- **Efectos secundarios atómicos:** Tras la aprobación, el cerebro dispara de forma asíncrona:
  1. Emisión del folio único del certificado.
  2. Registro inmutable en la bitácora de auditoría.
  3. Notificación reactiva inmediata hacia la interfaz de usuario.
