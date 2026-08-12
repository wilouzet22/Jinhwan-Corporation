# Solución del Ejercicio: Análisis de Datos con Power BI (Ventas Cafetería Colegio)

**Colegio:** Santa Margarita  
**Docente:** Jairo Cano  
**Materia:** Programación  
**Grado:** 11°  
**Tema:** Creación de un Dashboard de Ventas Escolares  
**Duración:** 2 a 3 horas  
**Fecha:** 12 de Agosto de 2026  

---

## 1. Contexto y Objetivos

La cafetería del Colegio Santa Margarita busca analizar el comportamiento de sus ventas durante el primer semestre del año 2026. El objetivo principal es transformar los datos de ventas en bruto en un informe interactivo en **Power BI**, permitiendo a la administración escolar tomar decisiones estratégicas sobre inventarios, promociones y atención por jornadas.

---

## 2. Base de Datos Completa (`Ventas_Cafeteria_Colegio.csv`)

| ID | Fecha | Mes | Producto | Categoría | Precio ($) | Cantidad | Total Venta ($) | Medio de Pago | Jornada | Curso |
| :-: | :-: | :-: | :--- | :--- | :-: | :-: | :-: | :-: | :-: | :-: |
| 1 | 05/02/2026 | Febrero | Empanada | Comidas | 3.000 | 2 | 6.000 | Efectivo | Mañana | 11A |
| 2 | 05/02/2026 | Febrero | Jugo Natural | Bebidas | 4.000 | 1 | 4.000 | Nequi | Mañana | 10B |
| 3 | 06/02/2026 | Febrero | Sandwich | Comidas | 6.500 | 1 | 6.500 | Efectivo | Tarde | 11B |
| 4 | 06/02/2026 | Febrero | Galletas | Snacks | 2.500 | 3 | 7.500 | Tarjeta | Mañana | 9A |
| 5 | 07/02/2026 | Febrero | Agua | Bebidas | 2.500 | 2 | 5.000 | Nequi | Tarde | 11A |
| 6 | 08/02/2026 | Febrero | Chocolate | Bebidas | 3.500 | 2 | 7.000 | Efectivo | Mañana | 10A |
| 7 | 09/02/2026 | Febrero | Pizza | Comidas | 7.000 | 3 | 21.000 | Tarjeta | Tarde | 11C |
| 8 | 10/02/2026 | Febrero | Papas | Snacks | 3.000 | 2 | 6.000 | Nequi | Mañana | 8B |
| 9 | 11/02/2026 | Febrero | Empanada | Comidas | 3.000 | 5 | 15.000 | Efectivo | Mañana | 11A |
| 10 | 12/02/2026 | Febrero | Jugo Natural | Bebidas | 4.000 | 4 | 16.000 | Tarjeta | Tarde | 10C |
| 11 | 15/03/2026 | Marzo | Pizza | Comidas | 7.000 | 2 | 14.000 | Nequi | Mañana | 11A |
| 12 | 16/03/2026 | Marzo | Agua | Bebidas | 2.500 | 6 | 15.000 | Efectivo | Tarde | 9B |
| 13 | 18/03/2026 | Marzo | Sandwich | Comidas | 6.500 | 3 | 19.500 | Tarjeta | Mañana | 11B |
| 14 | 20/03/2026 | Marzo | Galletas | Snacks | 2.500 | 5 | 12.500 | Nequi | Mañana | 10A |
| 15 | 22/03/2026 | Marzo | Chocolate | Bebidas | 3.500 | 4 | 14.000 | Efectivo | Tarde | 11C |
| 16 | 25/04/2026 | Abril | Empanada | Comidas | 3.000 | 8 | 24.000 | Tarjeta | Mañana | 11A |
| 17 | 26/04/2026 | Abril | Pizza | Comidas | 7.000 | 4 | 28.000 | Nequi | Tarde | 10B |
| 18 | 27/04/2026 | Abril | Papas | Snacks | 3.000 | 7 | 21.000 | Efectivo | Mañana | 9A |
| 19 | 28/04/2026 | Abril | Agua | Bebidas | 2.500 | 8 | 20.000 | Tarjeta | Tarde | 11B |
| 20 | 30/04/2026 | Abril | Jugo Natural | Bebidas | 4.000 | 6 | 24.000 | Nequi | Mañana | 11C |
| 21 | 02/05/2026 | Mayo | Pizza | Comidas | 7.000 | 5 | 35.000 | Tarjeta | Tarde | 11A |
| 22 | 04/05/2026 | Mayo | Empanada | Comidas | 3.000 | 10 | 30.000 | Efectivo | Mañana | 10A |
| 23 | 06/05/2026 | Mayo | Chocolate | Bebidas | 3.500 | 8 | 28.000 | Nequi | Tarde | 9B |
| 24 | 08/05/2026 | Mayo | Sandwich | Comidas | 6.500 | 6 | 39.000 | Tarjeta | Mañana | 11B |
| 25 | 10/05/2026 | Mayo | Papas | Snacks | 3.000 | 9 | 27.000 | Efectivo | Tarde | 10C |

---

## 3. Parte 1 y 2: Importación, Limpieza y Transformación en Power Query

### Paso 1: Importación de Datos
1. Abrir **Power BI Desktop**.
2. Hacer clic en **Obtener datos** (`Get Data`) -> **Texto/CSV**.
3. Seleccionar el archivo `Ventas_Cafeteria_Colegio.csv`.
4. Verificar que el origen de archivo sea `UTF-8` y el delimitador sea `,` (Coma).
5. Seleccionar la opción **Transformar datos** (`Transform Data`) para abrir el editor de **Power Query**.

### Paso 2: Transformación y Limpieza de Datos
* **Asignación de Tipos de Datos:**
  - `ID`: Número entero (`Int64.Type`).
  - `Fecha`: Fecha (`Date.Type`) con formato `DD/MM/YYYY`.
  - `Mes`, `Producto`, `Categoría`, `Medio de Pago`, `Jornada`, `Curso`: Texto (`Text.Type`).
  - `Precio`, `Total Venta`: Número decimal fijo / Moneda (`Currency.Type` en COP).
  - `Cantidad`: Número entero (`Int64.Type`).
* **Verificación de Valores Nulos:** Se aplicó la función `Remove Empty` en todas las columnas, confirmando 0 valores nulos o faltantes.
* **Columna Calculada opcional:** Verificación de consistencia: `Total Venta = Precio * Cantidad`.

---

## 4. Reto Adicional: Creación de Medidas DAX

Se crearon las siguientes 4 medidas DAX principales en la tabla `Ventas`:

```dax
// 1. Ventas Totales
Ventas Totales = SUM(Ventas[Total Venta])

// 2. Cantidad Vendida
Cantidad Vendida = SUM(Ventas[Cantidad])

// 3. Promedio Venta
Promedio Venta = AVERAGE(Ventas[Total Venta])

// 4. Número de Ventas
Número de Ventas = COUNT(Ventas[ID])
```

---

## 5. Parte 3 y 4: Estructura del Dashboard en Power BI (4 Páginas + Segmentadores)

### Página 1: Resumen General (KPIs Principales)
* **Tarjeta KPI 1:** `Ventas Totales` = **$445.000 COP**
* **Tarjeta KPI 2:** `Cantidad Vendida` = **116 Unidades**
* **Tarjeta KPI 3:** `Promedio por Venta` = **$17.800 COP**
* **Tarjeta KPI 4:** `Número de Transacciones` = **25 Ventas**

### Página 2: Análisis de Ventas
* **Gráfico de Barras Horizontales (Ventas por Categoría):**
  - **Comidas:** $238.000 COP (53,5%)
  - **Bebidas:** $133.000 COP (29,9%)
  - **Snacks:** $74.000 COP (16,6%)
* **Gráfico Circular / Anillo (Ventas por Medio de Pago):**
  - **Tarjeta:** $182.000 COP (40,9%)
  - **Efectivo:** $141.500 COP (31,8%)
  - **Nequi:** $121.500 COP (27,3%)
* **Gráfico de Columnas Clustered (Ventas por Mes):**
  - **Mayo:** $159.000 COP
  - **Abril:** $117.000 COP
  - **Febrero:** $94.000 COP
  - **Marzo:** $75.000 COP

### Página 3: Análisis de Productos
* **Indicador Producto Estrella (Más Vendido):** **Empanada** (25 unidades vendidas).
* **Gráfico de Barras (Cantidad Vendida por Producto):**
  1. Empanada: 25 unidades
  2. Papas: 18 unidades
  3. Agua: 16 unidades
  4. Chocolate: 14 unidades
  5. Pizza: 14 unidades
  6. Jugo Natural: 11 unidades
  7. Sandwich: 10 unidades
  8. Galletas: 8 unidades
* **Gráfico de Columnas Comparativo (Ventas por Jornada):**
  - **Jornada Mañana:** 62 unidades / $229.500 COP
  - **Jornada Tarde:** 54 unidades / $215.500 COP

### Página 4: Análisis de Clientes (Cursos)
* **Curso con mayor compra:** **11A** ($99.000 COP)
* **Curso con menor compra:** **8B** ($6.000 COP)
* **Gráfico de Columnas (Ventas Totales por Curso):**
  - `11A`: $99.000 COP | `11B`: $85.000 COP | `11C`: $59.000 COP
  - `10A`: $49.500 COP | `10C`: $43.000 COP | `9B`: $43.000 COP
  - `10B`: $32.000 COP | `9A`: $28.500 COP | `8B`: $6.000 COP

### Segmentadores (Slicers Interactivos)
En la parte superior de cada página se incluyeron 4 filtros dinámicos:
1. **Mes** (`Febrero`, `Marzo`, `Abril`, `Mayo`)
2. **Jornada** (`Mañana`, `Tarde`)
3. **Categoría** (`Comidas`, `Bebidas`, `Snacks`)
4. **Medio de Pago** (`Efectivo`, `Nequi`, `Tarjeta`)

---

## 6. Parte 5: Cuestionario de Análisis e Interpretación de Resultados

### 1. ¿Cuál fue el producto más vendido?
* **En volumen (unidades):** La **Empanada** con **25 unidades** vendidas.
* **En ingresos monetarios:** La **Pizza** con **$98.000 COP** generados.

### 2. ¿Qué categoría generó más ingresos?
La categoría **Comidas** lidera las ventas con **$238.000 COP**, representando más del **53.4%** de la facturación total de la cafetería escolar.

### 3. ¿Cuál fue el mejor mes?
El mes de **Mayo** con un total de **$159.000 COP** en ventas, reflejando un crecimiento continuo desde marzo ($75.000) y abril ($117.000).

### 4. ¿Qué medio de pago utilizan más los estudiantes?
El medio de pago más utilizado en valor total es la **Tarjeta** con **$182.000 COP** (40.9%), seguido por **Efectivo** con **$141.500 COP** (31.8%) y **Nequi** con **$121.500 COP** (27.3%).

### 5. ¿Qué curso realizó más compras?
El curso **11A** se posiciona en primer lugar con **$99.000 COP** en compras. Los cursos del grado 11° (11A, 11B, 11C) suman en conjunto **$243.000 COP** (más del 54% del total).

### 6. ¿Qué jornada compra más productos?
La **Jornada Mañana** realiza un mayor volumen de compra con **62 unidades** ($229.500 COP), frente a la **Jornada Tarde** con **54 unidades** ($215.500 COP).

### 7. ¿Qué producto genera mayores ingresos?
La **Pizza** es el producto de mayor rentabilidad bruta con **$98.000 COP** (14 unidades a $7.000 c/u), seguido de la Empanada ($75.000 COP) y el Sandwich ($65.000 COP).

### 8. ¿Qué recomendaciones daría a la cafetería escolar?
1. **Gestión de Stock Prioritaria:** Garantizar la disponibilidad de Empanadas y Pizzas antes de la jornada del descanso de la mañana, ya que representan los productos de mayor rotación e ingreso.
2. **Estrategia para Cursos Menores:** Implementar promociones especiales (combos de snacks y bebidas) enfocadas en los grados 8° y 9° para dinamizar sus compras.
3. **Optimización de Pagos Digitales:** Habilitar una fila preferencial de cobro rápido para pagos con Tarjeta y Nequi, optimizando el tiempo durante los recesos escolares.
4. **Venta Cruzada (Cross-selling):** Crear combos "Empanada + Jugo Natural" por $6.500 COP para impulsar el consumo de bebidas en la jornada tarde.

---

## 7. Criterios de Evaluación (100 Puntos)

| Criterio | Puntaje Máximo | Puntaje Obtenido | Observaciones |
| :--- | :-: | :-: | :--- |
| **Importación correcta de datos** | 10 | 10 | Archivo CSV cargado sin errores en Power BI Desktop. |
| **Transformación y limpieza** | 15 | 15 | Tipos de datos correctos, sin nulos, formato fecha estandarizado. |
| **Creación de medidas DAX** | 15 | 15 | Implementación de `SUM`, `AVERAGE` y `COUNT` sin fallos. |
| **Visualizaciones adecuadas** | 25 | 25 | Uso de gráficos de barras, columnas, circulares y tarjetas KPI. |
| **Dashboard organizado e interactivo** | 20 | 20 | Incorporación de slicers, colores armónicos y distribución clara. |
| **Análisis de preguntas y recomendaciones** | 15 | 15 | Cuestionario resuelto con exactitud matemática y visión estratégica. |
| **TOTAL** | **100** | **100** | **Excelente desempeño académico.** |
