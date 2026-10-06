<?php

/**
 * Configuración de la IA — NVIDIA NIM API
 * Modelos disponibles con la API Key actual:
 *  - openai/gpt-oss-20b (Ultra rápido, respuestas en ~2s, excelente en español y JSON)
 *  - meta/llama-3.2-90b-vision-instruct
 *  - deepseek-ai/deepseek-v4.1-flash
 */

define('NVIDIA_API_KEY',        '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');
define('NVIDIA_BASE_URL',       'https://integrate.api.nvidia.com/v1/chat/completions');
define('NVIDIA_MODEL',          'openai/gpt-oss-20b');
define('NVIDIA_FALLBACK_MODEL', 'meta/llama-3.2-90b-vision-instruct');
