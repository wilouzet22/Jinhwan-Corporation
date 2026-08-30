import React, { useState } from 'react';
import { Calendar as CalendarIcon, Clock, MapPin, Tag, ChevronLeft, ChevronRight, Plus } from 'lucide-react';
import { Evento } from '../types';

interface CalendarProps {
  initialEvents?: Evento[];
  canCreate?: boolean;
}

export const InteractiveCalendar: React.FC<CalendarProps> = ({ initialEvents = [], canCreate = false }) => {
  const defaultEvents: Evento[] = [
    {
      id_evento: 1,
      titulo: 'Examen de Ascenso Nacional de Cinturones',
      descripcion: 'Evaluación técnica, formas Taegeuk y combate para aspirantes a todos los grados.',
      fecha_inicio: '2026-09-15 09:00:00',
      fecha_fin: '2026-09-15 14:00:00',
      tipo: 'examen',
      sede_nombre: 'Sede Principal Santa Mónica',
    },
    {
      id_evento: 2,
      titulo: 'Torneo Intercolegial Abierto de Taekwondo',
      descripcion: 'Competencia en modalidades de Poomsae y Kyorugi todas las categorías.',
      fecha_inicio: '2026-09-28 08:00:00',
      fecha_fin: '2026-09-28 18:00:00',
      tipo: 'torneo',
      sede_nombre: 'Sede San Cristóbal',
    },
    {
      id_evento: 3,
      titulo: 'Seminario de Arbitraje y Reglas WT 2026',
      descripcion: 'Actualización sobre nuevas normativas de puntaje electrónico y sanciones.',
      fecha_inicio: '2026-10-10 10:00:00',
      tipo: 'seminario',
      sede_nombre: 'Sede Itagüí',
    },
  ];

  const events = initialEvents.length > 0 ? initialEvents : defaultEvents;
  const [selectedFilter, setSelectedFilter] = useState<string>('all');

  const filteredEvents = selectedFilter === 'all' ? events : events.filter((e) => e.tipo === selectedFilter);

  const getEventBadge = (tipo: string) => {
    switch (tipo) {
      case 'examen':
        return 'bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-900';
      case 'torneo':
        return 'bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-900';
      case 'seminario':
        return 'bg-blue-100 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-900';
      default:
        return 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-200 dark:border-slate-700';
    }
  };

  return (
    <div className="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-slate-100 dark:border-slate-800">
        <div>
          <h2 className="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <CalendarIcon className="w-5 h-5 text-rose-500" /> Calendario Oficial de Eventos
          </h2>
          <p className="text-xs text-slate-500 dark:text-slate-400">
            Exámenes, torneos y seminarios programados de Jinhwan Corporation
          </p>
        </div>

        {/* Filters */}
        <div className="flex items-center gap-2">
          <select
            value={selectedFilter}
            onChange={(e) => setSelectedFilter(e.target.value)}
            className="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-rose-500"
          >
            <option value="all">Todos los Eventos</option>
            <option value="examen">Exámenes de Grado</option>
            <option value="torneo">Torneos y Competencias</option>
            <option value="seminario">Seminarios y Talleres</option>
          </select>
        </div>
      </div>

      {/* Events List */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        {filteredEvents.map((evt) => {
          const dateObj = new Date(evt.fecha_inicio);
          const day = dateObj.getDate() || 15;
          const monthStr = dateObj.toLocaleDateString('es-ES', { month: 'short' }).toUpperCase();

          return (
            <div
              key={evt.id_evento}
              className="bg-slate-50 dark:bg-slate-800/50 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 hover:border-rose-500/40 dark:hover:border-rose-500/30 transition-all flex flex-col justify-between group"
            >
              <div>
                <div className="flex items-start justify-between gap-3 mb-3">
                  <div className="w-12 h-12 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col items-center justify-center text-center shrink-0">
                    <span className="text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase leading-none">
                      {monthStr}
                    </span>
                    <span className="text-base font-black text-slate-900 dark:text-white leading-tight">
                      {day}
                    </span>
                  </div>

                  <span className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase border ${getEventBadge(evt.tipo)}`}>
                    {evt.tipo}
                  </span>
                </div>

                <h3 className="font-bold text-base text-slate-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                  {evt.titulo}
                </h3>

                {evt.descripcion && (
                  <p className="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2 leading-relaxed">
                    {evt.descripcion}
                  </p>
                )}
              </div>

              <div className="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-800/80 space-y-1.5 text-xs text-slate-500 dark:text-slate-400">
                <div className="flex items-center gap-1.5">
                  <Clock className="w-3.5 h-3.5 text-slate-400" />
                  <span>{evt.fecha_inicio.substring(11, 16) || '09:00 AM'}</span>
                </div>
                {evt.sede_nombre && (
                  <div className="flex items-center gap-1.5">
                    <MapPin className="w-3.5 h-3.5 text-rose-500" />
                    <span>{evt.sede_nombre}</span>
                  </div>
                )}
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
};
