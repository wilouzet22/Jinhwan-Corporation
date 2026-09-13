# ðŸ” Flujo NeurÃ¡lgico: AutenticaciÃ³n y AutorizaciÃ³n
#flujo #proceso #seguridad

1. Usuario ingresa credenciales en [[UI_Modulo_Autenticacion]].
2. [[Frontend_Validador_Cliente]] valida localmente y transmite por [[Frontend_Cliente_HTTP]].
3. [[API_Gateway_Enrutador]] recibe la peticiÃ³n HTTPS y la transfiere a [[Middleware_Autenticacion_JWT]].
4. Se verifica el hash en [[DB_Tabla_Usuarios]] y se consultan permisos en [[DB_Tabla_Roles_Permisos]].
5. Se emite un JWT firmado por [[Seguridad_Cifrado_Tokens]] y se guarda sesiÃ³n en [[Cache_Memoria_Volatil]].
6. El token se inyecta en el [[Frontend_Gestor_Estado]] para hidratar la sesiÃ³n del usuario.
