export interface Sede {
  id_sede: number;
  nombre: string;
  direccion: string | null;
  telefono: string | null;
  horario: string | null;
  maestro?: string;
  total_alumnos?: number;
}

export interface Grado {
  id_grado: number;
  nombre: string;
  color_hex?: string;
  es_dan?: boolean;
  dan_num?: number;
}

export interface Miembro {
  id: number;
  id_persona?: number;
  nombre: string;
  apellido: string;
  correo: string;
  telefono?: string;
  num_doc?: string;
  tipo_documento?: string;
  grado_nombre?: string;
  id_grado?: number;
  sede_nombre?: string;
  id_sede?: number;
  categoria_nombre?: string;
  id_categoria?: number;
  rol?: string;
  rol_id?: number | string;
  activo?: number | boolean;
  foto_perfil?: string | null;
  es_publico?: number | boolean;
  tiempo_en_grado?: string;
  fecha_ultimo_ascenso?: string;
}

export interface Evento {
  id_evento: number;
  titulo: string;
  descripcion: string | null;
  fecha_inicio: string;
  fecha_fin?: string;
  tipo: 'examen' | 'torneo' | 'seminario' | 'clase_especial' | 'general';
  id_sede?: number;
  sede_nombre?: string;
}

export interface MultimediaItem {
  id_multimedia: number;
  titulo: string;
  descripcion?: string;
  tipo: 'foto' | 'video';
  url: string;
  categoria: string;
  fecha_subida?: string;
}

export interface SolicitudAscenso {
  id_solicitud: number;
  id_persona: number;
  alumno_nombre: string;
  alumno_foto?: string;
  grado_actual: string;
  grado_solicitado: string;
  id_grado_solicitado: number;
  sede_nombre: string;
  fecha_solicitud: string;
  estado: 'pendiente' | 'aprobada' | 'rechazada';
  observaciones?: string;
}

export interface RequisitoAscenso {
  id: string;
  titulo: string;
  descripcion: string;
  completado: boolean;
  categoria: 'tecnica' | 'poomsae' | 'terminologia' | 'combate' | 'teoria';
}
