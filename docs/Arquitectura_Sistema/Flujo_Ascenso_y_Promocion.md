# ðŸ¥‹ Flujo NeurÃ¡lgico: EvaluaciÃ³n y PromociÃ³n de Grado
#flujo #proceso #cerebro

Muestra cÃ³mo viaja la informaciÃ³n desde la interfaz hasta la base de datos durante un examen de ascenso:

1. El alumno o maestro abre el [[UI_Visualizador_Cinturones]].
2. Se envÃ­a la postulaciÃ³n al [[Controlador_Ascensos]].
3. El [[Servicio_Motor_Reglas]] valida:
   - [[Regla_Tiempo_Minimo_Grado]]
   - [[Regla_Asistencia_Minima]]
   - [[Regla_Matriz_Tecnica]]
4. La [[Servicio_Maquina_Estados]] transiciona el estado de la solicitud en [[DB_Tabla_Solicitudes_Ascenso]].
5. El jurado califica la evaluaciÃ³n en [[UI_Formularios_Operativos]].
6. El [[Gestor_Transacciones_ACID]] impacta atÃ³micamente el nuevo cinturÃ³n en [[DB_Tabla_Usuarios]], genera el certificado con [[Servicio_Generador_Certificados]] y graba en [[DB_Bitacora_Auditoria]].
7. El [[Despachador_Notificaciones]] dispara la respuesta reactiva con animaciÃ³n hacia [[UI_Dashboard_Principal]].
