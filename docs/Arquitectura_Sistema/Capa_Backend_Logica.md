# âš™ï¸ Capa LÃ³gica del Sistema (Backend)
#backend #arquitectura/capa

Contiene los controladores, servicios de dominio, evaluadores de reglas y gestores de transacciones.

## Puntos de Entrada
- [[API_Gateway_Enrutador]]
- [[Sanitizador_DTO_Entrada]]

## Controladores de Dominio
- [[Controlador_Miembros]]
- [[Controlador_Ascensos]]
- [[Controlador_Sedes]]
- [[Controlador_Eventos]]

## Servicios de Inteligencia (El NÃºcleo)
- [[Servicio_Motor_Reglas]]
- [[Servicio_Maquina_Estados]]
- [[Servicio_Calculador_Rendimiento]]
- [[Servicio_Generador_Certificados]]
- [[Despachador_Notificaciones]]

## AbstracciÃ³n de Datos
- [[Capa_ORM_Repositorios]]
- [[Gestor_Transacciones_ACID]]
