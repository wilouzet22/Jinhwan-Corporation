# Bóveda de Diagramas para Obsidian — Jinhwa Corporation

Esta carpeta contiene los diagramas arquitectónicos del sistema en **formato nativo Markdown / Mermaid**, listos para visualizarse y exportarse a PDF desde [Obsidian](https://obsidian.md).

---

## 📁 Archivos Disponibles

| Archivo | Tipo de Diagrama | Contenido |
| :--- | :--- | :--- |
| **[`DIAGRAMA_CLASES_UML.md`](./DIAGRAMA_CLASES_UML.md)** | `classDiagram` | Las 17 clases con 100% de atributos, tipos, métodos, multiplicidades, rombos de composición (◆), agregación (◇) y herencia. |
| **[`DIAGRAMA_ENTIDAD_RELACION.md`](./DIAGRAMA_ENTIDAD_RELACION.md)** | `erDiagram` | Las 17 tablas MariaDB/MySQL con llaves primarias (PK), foráneas (FK), tipos de datos y cardinalidades Chen. |
| **[`DIAGRAMA_CASOS_USO.md`](./DIAGRAMA_CASOS_USO.md)** | `flowchart LR` | Los 24 casos de uso clasificados en 4 módulos con sus respectivos actores y relaciones. |

---

## 🚀 Cómo Abrirlos en Obsidian

1. Abre **Obsidian**.
2. Haz clic en **Abrir carpeta como bóveda** (*Open folder as vault*).
3. Selecciona la carpeta:
   ```text
   C:\laragon\www\jinwha\docs\obsidian
   ```
   *(O abre la carpeta raíz del proyecto `C:\laragon\www\jinwha` y navega a `docs/obsidian/`).*
4. Haz clic en cualquiera de los archivos `.md` y verás el diagrama renderizado automáticamente en tiempo real.

---

## 🖨️ Cómo Exportar a PDF en Obsidian (Alta Resolución Vectorial)

1. Abre el archivo del diagrama que deseas exportar (por ejemplo `DIAGRAMA_CLASES_UML.md`).
2. Presiona `Ctrl + P` (o haz clic en los 3 puntos superiores derechos `...`).
3. Escribe o selecciona: **"Exportar a PDF"** (*Export to PDF*).
4. En las opciones de exportación:
   - **Tamaño de página:** `A4` o `Letter`.
   - **Orientación:** `Horizontal` (*Landscape*).
   - **Márgenes:** `Pequeño` (*Small*) o `Sin margen` (*None*).
   - **Mostrar nombre de archivo:** Desmarcado (opcional).
5. Haz clic en **Exportar a PDF** y elige dónde guardarlo.

> [!TIP]
> **Calidad Vectorial:** El PDF generado por Obsidian es 100% vectorial. Puedes hacer zoom al 500% en cualquier visor de PDF (como Adobe Reader o Chrome) y todas las letras, líneas y símbolos conservarán una nitidez perfecta.
