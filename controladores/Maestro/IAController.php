<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Security.php';
require_once __DIR__ . '/../../config/ia.php';
require_once __DIR__ . '/../../modelos/Ejercicio.php';
require_once __DIR__ . '/../../modelos/Grupo.php';

class MaestroIAController extends Controller
{
    private $ejercicioModel;
    private $grupoModel;

    public function __construct()
    {
        Security::verifySession();
        Security::verifyMaestro();
        $this->ejercicioModel = new Ejercicio();
        $this->grupoModel = new Grupo();
    }

    /**
     * Endpoint POST /maestro/ia/chat
     * Procesa mensajes del profesor y consulta NVIDIA NIM
     */
    public function chat()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        $mensaje = trim($input['mensaje'] ?? '');
        $contexto = trim($input['contexto'] ?? 'cronogramas'); // 'cronogramas' | 'ejercicios'
        $historial = $input['historial'] ?? [];

        if (empty($mensaje)) {
            echo json_encode(['success' => false, 'error' => 'El mensaje no puede estar vacío']);
            exit;
        }

        // Obtener la biblioteca actual de ejercicios para dar contexto a la IA
        $biblioteca = $this->ejercicioModel->getAll();
        $catalogoTexto = "";
        foreach ($biblioteca as $ej) {
            $catalogoTexto .= "- [ID: {$ej['id_ejercicio']}] \"{$ej['nombre']}\" (Tipo: {$ej['tipo']}): " . mb_substr($ej['explicacion'] ?? '', 0, 90) . "\n";
        }

        // Obtener los grupos reales de la escuela
        $grupos = $this->grupoModel->getAll();
        $gruposTexto = "";
        foreach ($grupos as $g) {
            $sedeStr = !empty($g['nombre_sede']) ? " (Sede: {$g['nombre_sede']})" : "";
            $horarioStr = !empty($g['horario']) ? " - Horario: {$g['horario']}" : "";
            $gruposTexto .= "- [ID: {$g['id_grupo']}] \"{$g['nombre']}\"{$sedeStr}{$horarioStr}\n";
        }

        // Construir System Prompt especializado e interactivo
        $systemPrompt = $this->construirSystemPrompt($contexto, $catalogoTexto, $gruposTexto);

        // Armar el arreglo de mensajes para la API
        $messagesPayload = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Añadir historial previo si existe (limitado a los últimos 8 mensajes para mantener contexto conversacional)
        if (is_array($historial) && !empty($historial)) {
            $historialReciente = array_slice($historial, -8);
            foreach ($historialReciente as $h) {
                if (isset($h['role'], $h['content']) && in_array($h['role'], ['user', 'assistant'])) {
                    $messagesPayload[] = [
                        'role' => $h['role'],
                        'content' => is_string($h['content']) ? $h['content'] : json_encode($h['content'], JSON_UNESCAPED_UNICODE)
                    ];
                }
            }
        }

        // Mensaje actual del usuario
        $messagesPayload[] = [
            'role' => 'user',
            'content' => $mensaje
        ];

        // Llamar a NVIDIA NIM
        $resultado = $this->llamarNvidiaAPI($messagesPayload, NVIDIA_MODEL);

        // Fallback a modelo secundario si el principal falla
        if (!$resultado['success'] && defined('NVIDIA_FALLBACK_MODEL') && !empty(NVIDIA_FALLBACK_MODEL)) {
            $resultado = $this->llamarNvidiaAPI($messagesPayload, NVIDIA_FALLBACK_MODEL);
        }

        if (!$resultado['success']) {
            echo json_encode([
                'success' => false,
                'error' => $resultado['error'] ?? 'No se pudo conectar con el servicio de IA'
            ]);
            exit;
        }

        // Parsear el JSON devuelto por la IA
        $parsed = $this->extraerJSON($resultado['content']);

        if (!$parsed) {
            // Si la IA respondió texto sin formato JSON, lo devolvemos como respuesta de texto normal
            echo json_encode([
                'success' => true,
                'data' => [
                    'mensaje' => $resultado['content'],
                    'accion' => 'texto'
                ]
            ]);
            exit;
        }

        // Si es llenado de cronograma, asociar IDs de biblioteca y grupo
        if (($parsed['accion'] ?? '') === 'llenar_cronograma' && isset($parsed['cronograma'])) {
            $parsed['cronograma'] = $this->completarDatosCronograma($parsed['cronograma'], $biblioteca, $grupos);
        }

        echo json_encode([
            'success' => true,
            'data' => $parsed
        ]);
        exit;
    }

    /**
     * Endpoint POST /maestro/ia/ejercicio/guardar
     * Guarda rápidamente un ejercicio sugerido por la IA en la biblioteca
     */
    public function guardarEjercicioSugerido()
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            exit;
        }

        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true);

        $nombre = trim($input['nombre'] ?? '');
        $tipo = trim($input['tipo'] ?? 'Otro');
        $explicacion = trim($input['explicacion'] ?? '');

        if (empty($nombre)) {
            echo json_encode(['success' => false, 'error' => 'El nombre del ejercicio es obligatorio']);
            exit;
        }

        $newId = $this->ejercicioModel->create([
            'tipo' => $tipo,
            'nombre' => $nombre,
            'explicacion' => $explicacion
        ]);

        if ($newId) {
            echo json_encode([
                'success' => true,
                'id_ejercicio' => $newId,
                'nombre' => $nombre,
                'tipo' => $tipo,
                'explicacion' => $explicacion
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al guardar en la base de datos']);
        }
        exit;
    }

    /**
     * Construye el system prompt según el contexto y catálogo
     */
    private function construirSystemPrompt($contexto, $catalogoTexto, $gruposTexto)
    {
        $diasEsp = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $diaHoy = $diasEsp[date('w')];
        $fechaHoy = date('Y-m-d');
        $tiposValidos = "Fuerza general, Fuerza Especifica, Pliometria, Coordinación, Resistencia Aerobica, Resistencia anaerobica, Combate, Flexibilidad, Velocidad, Otro";

        return "Eres el Asistente Inteligente de Taekwondo para Maestros e Instructores de la Corporación Jinhwan.
Tu función es ayudar a los profesores a planificar sus clases de Taekwondo paso a paso de manera interactiva, profesional y pedagógica.

DATOS DEL SISTEMA:
- Fecha de Hoy: {$fechaHoy} ({$diaHoy})
- Grupos registrados en la academia:
{$gruposTexto}
- Catálogo de ejercicios registrado en la biblioteca:
{$catalogoTexto}
- Tipos válidos de ejercicios: [{$tiposValidos}].

IMPORTANTE: DEBES RESPONDER SIEMPRE CON UN OBJETO JSON VÁLIDO (sin bloques markdown ```json, solo el objeto JSON).

==================================================
COMPORTAMIENTO CONVERSACIONAL Y PEDAGÓGICO
==================================================
Para que una clase pueda registrarse en el sistema, se requieren indispensablemente:
1. El GRUPO (debe corresponder a uno de los grupos de la academia listados arriba).
2. El DÍA o FECHA de la clase (ej: Mañana, Viernes, Lunes próximo, o fecha específica).
3. El OBJETIVO general y los ejercicios en sus 3 FASES (Inicial, Central, Final).

REGLAS DE INTERACCIÓN:

A) SI EL MAESTRO TE PIDE GENERAR UNA CLASE PERO AÚN NO HA DEFINIDO EL GRUPO O LA FECHA:
(Por ejemplo: \"Quiero que me generes un cronograma de clase con un objetivo que tú escojas\", \"Ayúdame a planificar una clase\", \"Hazme una clase de combate\"):
-> NO entregues aún la acción 'llenar_cronograma'.
-> Responde con accion: 'texto'.
-> Proponle con entusiasmo el objetivo de entrenamiento que tú escogiste (o 2 variantes según tu criterio marcial).
-> Pídele amablemente los datos que te faltan para completar la planificación:
   1. ¿Para qué grupo será? (Enumera los grupos disponibles arriba de forma clara).
   2. ¿Para qué día de la semana o fecha deseas programarla?
   3. Pregúntale si está de acuerdo con el objetivo propuesto o si desea ajustarlo.

Ejemplo de respuesta JSON en este caso:
{
  \"mensaje\": \"¡Excelente iniciativa, Sabomnim! Te propongo una clase enfocada en: **Desarrollo de potencia y velocidad en patada Bandal Chagui con anticipación ofensiva**.\\n\\nPara dejar tu cronograma listo y aplicarlo al sistema, por favor indícame:\\n1. **¿Para qué grupo será?** (Disponibles: Infantil, Juvenil Principiante, Adultos...)\\n2. **¿Para qué día o fecha?** (ej: Mañana, este Viernes, etc.)\\n\\n¿Te parece bien este objetivo o prefieres enfocarlo en otra área?\",
  \"accion\": \"texto\"
}

B) SI EL MAESTRO YA ESPECIFICÓ EL GRUPO Y LA FECHA (o los responde en su mensaje posterior, o te dice explícitamente 'escoge tú el grupo y el día'):
-> Genera la planificación completa con accion: 'llenar_cronograma'.
-> Calcula la fecha correspondiente a partir de la fecha de hoy ({$fechaHoy}, {$diaHoy}) en formato YYYY-MM-DD.
-> Selecciona el 'id_grupo' numérico correspondiente a dicho grupo.
-> Estructura las 3 fases (Inicial, Central, Final) usando preferentemente los ejercicios de la biblioteca provista.

Estructura requerida cuando accion === 'llenar_cronograma':
{
  \"mensaje\": \"¡Listo, Sabomnim! He planificado la sesión para el grupo [Nombre Grupo] programada para el [Día/Fecha]. A continuación tienes el detalle de las 3 fases.\",
  \"accion\": \"llenar_cronograma\",
  \"cronograma\": {
    \"id_grupo\": 1, // ID numérico exacto del grupo
    \"grupo_nombre\": \"Nombre del Grupo\",
    \"fecha\": \"2026-10-09\", // Formato YYYY-MM-DD
    \"fecha_texto\": \"Viernes, 9 de Octubre de 2026\",
    \"objetivo\": \"Objetivo claro y pedagógico de la sesión...\",
    \"parte_inicial\": [
      {
        \"id_ejercicio\": 12, // ID de la biblioteca o null si es nuevo
        \"nombre\": \"Nombre del ejercicio\",
        \"tipo\": \"Coordinación\",
        \"series_o_tiempo\": \"8 min\",
        \"observaciones\": \"Calentamiento articular y activación neuromuscular\"
      }
    ],
    \"parte_central\": [
      {
        \"id_ejercicio\": 5,
        \"nombre\": \"Nombre del ejercicio\",
        \"tipo\": \"Combate\",
        \"series_o_tiempo\": \"4 series x 15 reps\",
        \"observaciones\": \"Pateo a peto buscando máxima velocidad\"
      }
    ],
    \"parte_final\": [
      {
        \"id_ejercicio\": 8,
        \"nombre\": \"Estiramiento pasivo\",
        \"tipo\": \"Flexibilidad\",
        \"series_o_tiempo\": \"5 min\",
        \"observaciones\": \"Relajación y estiramiento de tren inferior\"
      }
    ]
  }
}

C) SI EL MAESTRO PIDE IDEAS DE EJERCICIOS NUEVOS PARA LA BIBLIOTECA:
{
  \"mensaje\": \"Explicación de las propuestas...\",
  \"accion\": \"agregar_ejercicio\",
  \"ejercicios_sugeridos\": [
    {
      \"nombre\": \"Nombre técnico claro\",
      \"tipo\": \"Pliometria\",
      \"explicacion\": \"Instrucción de ejecución...\"
    }
  ]
}

D) PREGUNTAS GENERALES, DOCTRINA, REGLAMENTO O SALUDOS:
{
  \"mensaje\": \"Tu respuesta profesional y experta.\",
  \"accion\": \"texto\"
}

Recuerda: Si falta el grupo o el día, pregúntaselos primero al maestro de forma cortés para que la clase quede perfecta y lista para guardar en la base de datos.";
    }

    /**
     * Llama al endpoint de NVIDIA NIM API
     */
    private function llamarNvidiaAPI($messages, $model)
    {
        $ch = curl_init(NVIDIA_BASE_URL);

        $payload = [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => 1500,
            'temperature' => 0.4
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . NVIDIA_API_KEY,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'error' => "Error de conexión curl: $error"];
        }

        if ($httpCode !== 200) {
            return ['success' => false, 'error' => "NVIDIA API HTTP $httpCode: " . mb_substr($response, 0, 200)];
        }

        $decoded = json_decode($response, true);
        $content = $decoded['choices'][0]['message']['content'] ?? null;

        if (!$content) {
            return ['success' => false, 'error' => 'La IA no devolvió contenido'];
        }

        return ['success' => true, 'content' => $content];
    }

    /**
     * Extrae un JSON limpio incluso si viene envuelto en markdown o texto circundante
     */
    private function extraerJSON($texto)
    {
        $textoLimpio = trim($texto);

        // Si viene envuelto en ```json ... ```
        if (preg_match('/```(?:json)?\s*(\{[\s\S]*?\})\s*```/i', $textoLimpio, $matches)) {
            $textoLimpio = $matches[1];
        } else {
            // Intentar encontrar el primer '{' y el último '}'
            $primerLlave = strpos($textoLimpio, '{');
            $ultimaLlave = strrpos($textoLimpio, '}');
            if ($primerLlave !== false && $ultimaLlave !== false && $ultimaLlave > $primerLlave) {
                $textoLimpio = substr($textoLimpio, $primerLlave, ($ultimaLlave - $primerLlave) + 1);
            }
        }

        $arr = json_decode($textoLimpio, true);
        return is_array($arr) ? $arr : null;
    }

    /**
     * Completa y normaliza datos del cronograma (grupo, fecha y ejercicios)
     */
    private function completarDatosCronograma($cronograma, $biblioteca, $grupos)
    {
        // 1. Validar / mapear grupo
        $mapaGrupos = [];
        foreach ($grupos as $g) {
            $key = mb_strtolower(trim($g['nombre']), 'UTF-8');
            $mapaGrupos[$key] = $g;
        }

        if (empty($cronograma['id_grupo']) && !empty($cronograma['grupo_nombre'])) {
            $gKey = mb_strtolower(trim($cronograma['grupo_nombre']), 'UTF-8');
            foreach ($mapaGrupos as $nombreKey => $gObj) {
                if (strpos($nombreKey, $gKey) !== false || strpos($gKey, $nombreKey) !== false) {
                    $cronograma['id_grupo'] = (int)$gObj['id_grupo'];
                    $cronograma['grupo_nombre'] = $gObj['nombre'];
                    break;
                }
            }
        }

        // Si aún no tiene id_grupo y hay al menos un grupo, asignar el primero
        if (empty($cronograma['id_grupo']) && !empty($grupos)) {
            $cronograma['id_grupo'] = (int)$grupos[0]['id_grupo'];
            $cronograma['grupo_nombre'] = $grupos[0]['nombre'];
        }

        // 2. Validar fecha (formato YYYY-MM-DD)
        if (empty($cronograma['fecha']) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $cronograma['fecha'])) {
            $cronograma['fecha'] = date('Y-m-d');
        }

        // 3. Relacionar ejercicios sin ID con los de la biblioteca
        $mapaNombres = [];
        foreach ($biblioteca as $b) {
            $key = mb_strtolower(trim($b['nombre']), 'UTF-8');
            $mapaNombres[$key] = $b;
        }

        foreach (['parte_inicial', 'parte_central', 'parte_final'] as $fase) {
            if (!isset($cronograma[$fase]) || !is_array($cronograma[$fase])) continue;

            foreach ($cronograma[$fase] as &$item) {
                if (empty($item['id_ejercicio'])) {
                    $itemNombreKey = mb_strtolower(trim($item['nombre'] ?? ''), 'UTF-8');
                    if (isset($mapaNombres[$itemNombreKey])) {
                        $item['id_ejercicio'] = (int)$mapaNombres[$itemNombreKey]['id_ejercicio'];
                        if (empty($item['tipo'])) {
                            $item['tipo'] = $mapaNombres[$itemNombreKey]['tipo'];
                        }
                    }
                }
            }
        }

        return $cronograma;
    }
}
