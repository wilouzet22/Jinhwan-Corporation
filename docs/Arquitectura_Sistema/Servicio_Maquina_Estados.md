# ðŸ”„ Backend: MÃ¡quina de Estados Finitos de Ascenso
#backend #cerebro #patron #workflow

Orquesta las transiciones deterministas del proceso de graduaciÃ³n evitando inconsistencias o saltos de grado ilÃ­citos.

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
