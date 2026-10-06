-- Seed de ejercicios desde ejercicios.csv
-- 20 ejercicios distribuidos por tipos/categorías

INSERT INTO `ejercicios` (`tipo`, `nombre`, `explicacion`) VALUES
    ('Fuerza general', 'Lagartijas', 'Flexiones de codo, con una alta cantidad de repeticiones para ganar fuerza'),
    ('Fuerza Especifica', 'Abducción de cadera de pie explosivas', 'De pie realizar yop chaoligui con la rodilla extendida o flexionada rapido para mejorar la fuerza rapida en el gesto tecnico y el rango de movimiento'),
    ('Pliometria', 'Saltos frontales', 'Realizar saltos hacia el frente ya sea maxima altura o maxima distancia para mejorar la fuerza explosiva'),
    ('Coordinación', 'Desplazamiento en escalera', 'Realizar ejercicios de coordinacion en la escalera seleccionados segun el nivel del deportista'),
    ('Resistencia Aerobica', 'Trabajo de desplazamientos', 'En un area especifica o Aro, se realizan todos los desplazamientos del deporte con una alta duración en el tiempo'),
    ('Resistencia anaerobica', 'Pateo repetido estatico', 'En parejas se realiza un pateo repetido a maxima velocidad durante un tiempo entre 30 y 50 segundos por pierna'),
    ('Combate', 'Combate solo anterior', 'Se realiza un combate 1 vs 1 donde siempre la primera patada lanzada debe ser la anterior, luego se puede combinar con otras técnicas.'),
    ('Flexibilidad', 'FNP (Facilitacion neuro muscular propioceptiva)', 'Estiramientos en los cuales se deben cumplir varias faces y durar en ellas entre 10 y 15 segundos. Las faces son: Estiramiento, Contracción y Relajación.'),
    ('Velocidad', 'Reacción', NULL),
    ('Fuerza Especifica', 'Abducción de cadera de pie isometrica', 'De pie realizar yop chagui sostenido a la maxima altura posible para ganar fuerza'),
    ('Coordinación', 'Desplazamiento en escalera con pateo', 'Realizar ejercicios de coordinacion en la escalera mientras se ejecutan tecnicas de pateo especificas del deporte y desplazamientos del mismo'),
    ('Velocidad', 'Gestual', NULL),
    ('Fuerza general', 'Fuerza y potencia', 'Peso muerto, pesas, bandas o compañero una cantidad de 5 sentadillas y 2 pi chaguis, por la cantidad de maximo 5 repeticiones o por tiempo ( tener en cuenta el tiempo de recuperación)'),
    ('Fuerza Especifica', 'Pliometria con lanzamiento', 'Cada sentadilla sera contada a orden sentadillas con salto en pliometria y lanzamientos ( ya sea de balon medicinal paos o cualquier objeto)'),
    ('Pliometria', 'salto con rotación de cadera', 'Salta desde abajo y al levantarse debe girar la cadera a los laterales'),
    ('Coordinación', 'Patada de reacción con obstáculo', 'pocision de caballero,a la orden se levanta salta por encima del obstáculo pasando al otro las y realiza la patada indicada'),
    ('Resistencia Aerobica', 'Entrenamiento en circuito', 'Realizar un circuito por bases enfocada en la carga aeróbica ya sea desplazamientos patadas por tiempo etc'),
    ('Fuerza Especifica', 'Sentadilla en avanzada', 'Pies separados anchura de los hombros, izquierdo sale adelante, rodilla flexionada a 90 grados y la rodilla de atrás acompaña el movimiento.'),
    ('Resistencia Aerobica', 'ejercico de estres metabolico en bases', 'se realizan esjercicos de resistenciaa anaerobica de corta duracion distribuidos en 4 bases'),
    ('Fuerza Especifica', 'Sentadilla búlgara', 'Apoyo del pie anterior en empeine una silla o superficie plana, rodilla de delante flexiona a 90 grados y sin mancuernas.');
