# Reglas del Proyecto Jinhwan Corporation

> Las directrices completas y memoria detallada residen en la carpeta [IA/](file:///c:/laragon/www/jinwha/IA).
> - Directrices y Reglas: [IA/AGENTS.md](file:///c:/laragon/www/jinwha/IA/AGENTS.md)
> - Memoria y Decisiones: [IA/MEMORY.md](file:///c:/laragon/www/jinwha/IA/MEMORY.md)
> - Habilidades y Protocolos: [IA/SKILLS.md](file:///c:/laragon/www/jinwha/IA/SKILLS.md)

---

## Directrices Clave de Entorno y Producción

1. **Hosting y Producción**: El proyecto está desplegado en **InfinityFree**.
   - Base de datos de producción: MariaDB / MySQL `if0_42216592_jinhwa_corporation` (gestionada mediante phpMyAdmin en InfinityFree).
   - Entorno local: **Laragon** (Windows).
   - Cualquier cambio en esquema o migración SQL debe ser compatible con InfinityFree y proveerse listo para ejecutar en phpMyAdmin.

2. **Protocolo de Interacción**:
   - Avisar siempre antes de actuar y declarar si se usará la terminal.
   - En Windows (PowerShell/CMD), **NUNCA usar `&&`**, usar siempre `;`.
   - Respuestas concisas y directas.
   - Siempre proveer el comando de Git al finalizar:
     `git add . ; git commit -m "..." ; git push`

3. **Arquitectura de Base de Datos**:
   - Tablas de usuarios independientes: `estudiante`, `maestro`, `admin`. Prohibido usar tablas genéricas tipo `miembros` o `usuarios`.
