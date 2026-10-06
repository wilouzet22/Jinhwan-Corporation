<?php

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Security.php';
require_once __DIR__ . '/../../config/ia.php';
require_once __DIR__ . '/../../modelos/Ejercicio.php';

class MaestroIAController extends Controller
{
    private $ejercicioModel;

    public function __construct()
    {
        Security::verifySession();
        Security::verifyMaestro();
        $this->ejercicioModel = new Ejercicio();
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
        $mapaEjercicios = [];
        foreach ($biblioteca as $ej) {
            $catalogoTexto .= "- [ID: {$ej['id_ejercicio']}] \"{$ej['nombre']}\" (Tipo: {$ej['tipo']}): " . mb_substr($ej['explicacion'] ?? '', 0, 100) . "\n";
            $mapaEjercicios[mb_strtolower($ej['nombre'], 'UTF-8')] = $ej;
        }

        // Construir System Prompt especializado
        $systemPrompt = $this->construirSystemPrompt($contexto, $catalogoTexto);

        // Armar el arreglo de mensajes para la API
        $messagesPayload = [
            ['role' => 'system', 'content' => $systemPrompt]
        ];

        // Añadir historial previo si existe (limitado a los últimos 6 mensajes)
        if (is_array($historial) && !empty($historial)) {
            $historialReciente = array_slice($historial, -6);
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

        // Si es llenado de cronograma, asociar IDs de biblioteca si faltaron
        if (($parsed['accion'] ?? '') === 'llenar_cronograma' && isset($parsed['cronograma'])) {
            $parsed['cronograma'] = $this->completarIdsEjercicios($parsed['cronograma'], $biblioteca);
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
     * Construye el system prompt según el contexto
     */
    private function construirSystemPrompt($contexto, $catalogoTexto)
    {
        $tiposValidos = "Fuerza general, Fuerza Especifica, Pliometria, Coordinación, Resistencia Aerobica, Resistencia anaerobica, Combate, Flexibilidad, Velocidad, Otro";

        return "Eres el Asistente Inteligente de Taekwondo para Maestros e Instructores de la Corporación Jinhwan.
Tu misión es asistir a los profesores a planificar sesiones de entrenamiento de Taekwondo estructuradas, profesionales y metodológicamente correctas, o sugerir nuevos ejercicios y contestar consultas técnicas.

IMPORTANTE: DEBES RESPONDER EXCLUSIVAMENTE CON UN OBJETO JSON VÁLIDO. No añadas bloques markdown como ```json ... ```, no saludes fuera del JSON, entrega estrictamente el JSON crudo.

Biblioteca de ejercicios actualmente registrada en la escuela:
{$catalogoTexto}

Tipos válidos de ejercicios: [{$tiposValidos}].

FORMATOS DE RESPUESTA JSON REQUERIDOS:

CASO 1: Si el maestro pide crear o generar una clase, cronograma, sesión o entrenamiento:
{
  \"mensaje\": \"Breve resumen explicativo del enfoque pedagógico de la clase (2-3 oraciones).\",
  \"accion\": \"llenar_cronograma\",
  \"cronograma\": {
    \"objetivo\": \"Objetivo claro y medible de la sesión (ej: Desarrollar potencia en Bandal Chagui y velocidad de anticipación).\",
    \"parte_inicial\": [
      {
        \"id_ejercicio\": 12, // Usa el ID de la biblioteca si existe, o null si es nuevo
        \"nombre\": \"Nombre del ejercicio\",
        \"tipo\": \"Coordinación\", // Uno de los tipos válidos
        \"series_o_tiempo\": \"8 min\",
        \"observaciones\": \"Calentamiento articular y activación neuromuscular\"
      }
    ],
    \"parte_central\": [
      {
        \"id_ejercicio\": 5,
        \"nombre\": \"Nombre del ejercicio central\",
        \"tipo\": \"Combate\",
        \"series_o_tiempo\": \"4 series x 15 reps\",
        \"observaciones\": \"Pateo a peto buscando máxima velocidad en el impacto\"
      }
    ],
    \"parte_final\": [
      {
        \"id_ejercicio\": 8,
        \"nombre\": \"Nombre ejercicio de calma\",
        \"tipo\": \"Flexibilidad\",
        \"series_o_tiempo\": \"5 min\",
        \"observaciones\": \"Estiramiento estático de isquiotibiales y cadera\"
      }
    ]
  }
}

CASO 2: Si el maestro pide ideas de ejercicios nuevos para registrar en la biblioteca:
{
  \"mensaje\": \"Explicación de los ejercicios sugeridos y su aplicación.\",
  \"accion\": \"agregar_ejercicio\",
  \"ejercicios_sugeridos\": [
    {
      \"nombre\": \"Nombre técnico claro\",
      \"tipo\": \"Pliometria\", // Debe ser uno de los tipos válidos
      \"explicacion\": \"Instrucción detallada de ejecución, postura y recomendaciones de seguridad.\"
    }
  ]
}

CASO 3: Preguntas teóricas, dudas de reglamento, poomsae o saludos:
{
  \"mensaje\": \"Tu respuesta detallada y profesional como maestro experimentado de Taekwondo.\",
  \"accion\": \"texto\"
}

Reglas clave:
- Siempre prioriza utilizar los ejercicios de la biblioteca provista si encajan con el objetivo. Si un ejercicio no está en la biblioteca, puedes proponerlo con 'id_ejercicio': null.
- Organiza la sesión coherentemente: parte inicial (calentamiento/movilidad), parte central (trabajo principal técnico/táctico/físico), parte final (vuelta a la calma y estiramiento).
- Toda respuesta debe ser en perfecto español.";
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
     * Intenta relacionar ejercicios sin ID con los nombres exactos o parecidos de la biblioteca
     */
    private function completarIdsEjercicios($cronograma, $biblioteca)
    {
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
