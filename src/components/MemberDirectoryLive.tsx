import React, { useState, useMemo } from 'react';
import { Search, Filter, ShieldCheck, MapPin, Award, LayoutGrid, List, Download, Phone, Mail, User } from 'lucide-react';
import { Miembro } from '../types';

interface MemberDirectoryLiveProps {
  initialMembers?: Miembro[];
  sedes?: Array<{ id_sede: number; nombre: string }>;
  grados?: Array<{ id_grado: number; nombre: string }>;
  isPublic?: boolean;
}

export const MemberDirectoryLive: React.FC<MemberDirectoryLiveProps> = ({
  initialMembers = [],
  sedes = [],
  grados = [],
  isPublic = false,
}) => {
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedSede, setSelectedSede] = useState<string>('all');
  const [selectedGrado, setSelectedGrado] = useState<string>('all');
  const [selectedRol, setSelectedRol] = useState<string>('all');
  const [viewMode, setViewMode] = useState<'grid' | 'table'>('grid');
  const [activeMember, setActiveMember] = useState<Miembro | null>(null);

  const filteredMembers = useMemo(() => {
    return initialMembers.filter((m) => {
      const fullName = `${m.nombre} ${m.apellido}`.toLowerCase();
      const matchesSearch =
        fullName.includes(searchTerm.toLowerCase()) ||
        (m.correo && m.correo.toLowerCase().includes(searchTerm.toLowerCase())) ||
        (m.grado_nombre && m.grado_nombre.toLowerCase().includes(searchTerm.toLowerCase()));

      const matchesSede = selectedSede === 'all' || String(m.id_sede) === selectedSede || m.sede_nombre === selectedSede;
      const matchesGrado = selectedGrado === 'all' || String(m.id_grado) === selectedGrado || m.grado_nombre === selectedGrado;
      const matchesRol = selectedRol === 'all' || String(m.rol_id) === selectedRol || m.rol === selectedRol;

      return matchesSearch && matchesSede && matchesGrado && matchesRol;
    });
  }, [initialMembers, searchTerm, selectedSede, selectedGrado, selectedRol]);

  const exportCSV = () => {
    const headers = ['ID', 'Nombre', 'Apellido', 'Grado', 'Sede', 'Rol', 'Correo'];
    const rows = filteredMembers.map((m) => [
      m.id,
      `"${m.nombre}"`,
      `"${m.apellido}"`,
      `"${m.grado_nombre || ''}"`,
      `"${m.sede_nombre || ''}"`,
      `"${m.rol || ''}"`,
      `"${m.correo || ''}"`,
    ]);

    const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map((e) => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `miembros_jinhwan_${new Date().toISOString().slice(0, 10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const getBeltBadge = (gradoName?: string) => {
    if (!gradoName) return <span className="text-xs text-slate-400">Sin Grado</span>;
    const lower = gradoName.toLowerCase();
    let bg = 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300';
    if (lower.includes('negro')) {
      bg = 'bg-zinc-950 text-amber-400 border-amber-500/50 shadow-xs';
    } else if (lower.includes('rojo')) {
      bg = 'bg-red-600 text-white border-red-700';
    } else if (lower.includes('azul')) {
      bg = 'bg-blue-600 text-white border-blue-700';
    } else if (lower.includes('verde')) {
      bg = 'bg-emerald-600 text-white border-emerald-700';
    } else if (lower.includes('amarillo')) {
      bg = 'bg-amber-400 text-zinc-950 border-amber-500 font-bold';
    }
    return (
      <span className={`inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold border ${bg}`}>
        <Award className="w-3 h-3" /> {gradoName}
      </span>
    );
  };

  return (
    <div className="space-y-6 w-full">
      {/* Controls Bar */}
      <div className="bg-white dark:bg-slate-900 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-800 shadow-md space-y-4">
        <div className="flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between">
          {/* Search Box */}
          <div className="relative flex-1">
            <Search className="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" />
            <input
              type="text"
              placeholder="Buscar por nombre, cinturón o correo..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-rose-500 focus:outline-none text-slate-800 dark:text-slate-100"
            />
          </div>

          {/* View Toggles & Export */}
          <div className="flex items-center gap-2 shrink-0">
            <div className="flex bg-slate-100 dark:bg-slate-800 p-1 rounded-xl border border-slate-200 dark:border-slate-700">
              <button
                type="button"
                onClick={() => setViewMode('grid')}
                className={`p-2 rounded-lg transition-all ${
                  viewMode === 'grid'
                    ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-xs'
                    : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'
                }`}
                title="Vista en Tarjetas"
              >
                <LayoutGrid className="w-4 h-4" />
              </button>
              <button
                type="button"
                onClick={() => setViewMode('table')}
                className={`p-2 rounded-lg transition-all ${
                  viewMode === 'table'
                    ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-xs'
                    : 'text-slate-500 hover:text-slate-800 dark:hover:text-slate-200'
                }`}
                title="Vista en Tabla"
              >
                <List className="w-4 h-4" />
              </button>
            </div>

            {!isPublic && (
              <button
                type="button"
                onClick={exportCSV}
                className="flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 transition-colors"
              >
                <Download className="w-4 h-4" /> CSV
              </button>
            )}
          </div>
        </div>

        {/* Filter Badges */}
        <div className="flex flex-wrap gap-2 pt-2 border-t border-slate-100 dark:border-slate-800 text-xs items-center">
          <span className="text-slate-400 font-medium flex items-center gap-1 mr-1">
            <Filter className="w-3.5 h-3.5" /> Filtros:
          </span>

          {/* Sede filter */}
          <select
            value={selectedSede}
            onChange={(e) => setSelectedSede(e.target.value)}
            className="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-rose-500"
          >
            <option value="all">Todas las Sedes</option>
            {sedes.map((s) => (
              <option key={s.id_sede} value={String(s.id_sede)}>
                {s.nombre}
              </option>
            ))}
          </select>

          {/* Grado filter */}
          <select
            value={selectedGrado}
            onChange={(e) => setSelectedGrado(e.target.value)}
            className="px-3 py-1.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-1 focus:ring-rose-500"
          >
            <option value="all">Todos los Cinturones</option>
            {grados.map((g) => (
              <option key={g.id_grado} value={String(g.id_grado)}>
                {g.nombre}
              </option>
            ))}
          </select>

          <span className="ml-auto text-slate-500 dark:text-slate-400 font-semibold">
            {filteredMembers.length} {filteredMembers.length === 1 ? 'resultado' : 'resultados'}
          </span>
        </div>
      </div>

      {/* Grid View */}
      {viewMode === 'grid' && (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
          {filteredMembers.length === 0 ? (
            <div className="col-span-full py-16 text-center text-slate-400">
              <User className="w-12 h-12 mx-auto mb-3 opacity-40" />
              <p className="text-base font-semibold">No se encontraron miembros con esos criterios</p>
              <p className="text-xs mt-1">Prueba limpiando los filtros o el término de búsqueda</p>
            </div>
          ) : (
            filteredMembers.map((m) => (
              <div
                key={m.id || m.id_persona}
                onClick={() => setActiveMember(m)}
                className="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800/90 shadow-md hover:shadow-xl hover:border-rose-500/40 dark:hover:border-rose-500/30 transition-all cursor-pointer group flex flex-col justify-between"
              >
                <div>
                  <div className="flex items-start justify-between gap-3 mb-4">
                    <div className="relative">
                      {m.foto_perfil ? (
                        <img
                          src={m.foto_perfil}
                          alt={`${m.nombre} ${m.apellido}`}
                          className="w-14 h-14 rounded-2xl object-cover border-2 border-slate-200 dark:border-slate-700 shadow-sm"
                        />
                      ) : (
                        <div className="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                          {m.nombre.charAt(0)}
                          {m.apellido.charAt(0)}
                        </div>
                      )}
                      {m.rol && (
                        <span className="absolute -bottom-1.5 -right-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-900 text-white shadow-xs">
                          {m.rol}
                        </span>
                      )}
                    </div>

                    <div className="text-right">{getBeltBadge(m.grado_nombre)}</div>
                  </div>

                  <h3 className="font-bold text-base text-slate-900 dark:text-white group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">
                    {m.nombre} {m.apellido}
                  </h3>

                  {m.sede_nombre && (
                    <p className="text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1 mt-1">
                      <MapPin className="w-3.5 h-3.5 text-rose-500" /> {m.sede_nombre}
                    </p>
                  )}
                </div>

                <div className="mt-4 pt-3 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-400">
                  <span>ID #{m.id || m.id_persona}</span>
                  <span className="text-rose-600 dark:text-rose-400 font-semibold group-hover:underline">
                    Ver Perfil &rarr;
                  </span>
                </div>
              </div>
            ))
          )}
        </div>
      )}

      {/* Table View */}
      {viewMode === 'table' && (
        <div className="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-md overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm text-slate-600 dark:text-slate-300">
              <thead className="bg-slate-50 dark:bg-slate-800/80 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">
                <tr>
                  <th className="px-5 py-3.5">Miembro</th>
                  <th className="px-5 py-3.5">Grado / Cinturón</th>
                  <th className="px-5 py-3.5">Sede</th>
                  <th className="px-5 py-3.5">Rol</th>
                  <th className="px-5 py-3.5 text-right">Acción</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100 dark:divide-slate-800">
                {filteredMembers.map((m) => (
                  <tr
                    key={m.id || m.id_persona}
                    className="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors"
                  >
                    <td className="px-5 py-3.5 font-medium text-slate-900 dark:text-white flex items-center gap-3">
                      <div className="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center text-xs font-bold shrink-0">
                        {m.nombre.charAt(0)}
                      </div>
                      <div>
                        <div>
                          {m.nombre} {m.apellido}
                        </div>
                        {m.correo && <div className="text-xs text-slate-400">{m.correo}</div>}
                      </div>
                    </td>
                    <td className="px-5 py-3.5">{getBeltBadge(m.grado_nombre)}</td>
                    <td className="px-5 py-3.5 text-xs">{m.sede_nombre || 'N/A'}</td>
                    <td className="px-5 py-3.5">
                      <span className="px-2 py-0.5 rounded text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                        {m.rol || 'Estudiante'}
                      </span>
                    </td>
                    <td className="px-5 py-3.5 text-right">
                      <button
                        type="button"
                        onClick={() => setActiveMember(m)}
                        className="text-xs font-bold text-rose-600 hover:text-rose-700 dark:text-rose-400"
                      >
                        Detalles
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {/* Member Details Modal */}
      {activeMember && (
        <div
          className="fixed inset-0 z-50 bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4"
          onClick={() => setActiveMember(null)}
        >
          <div
            className="bg-white dark:bg-slate-900 rounded-3xl p-6 sm:p-8 max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl relative space-y-5 animate-in fade-in zoom-in-95 duration-200"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="flex items-start justify-between">
              <div className="flex items-center gap-4">
                <div className="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl shadow-md">
                  {activeMember.nombre.charAt(0)}
                  {activeMember.apellido.charAt(0)}
                </div>
                <div>
                  <h3 className="text-xl font-bold text-slate-900 dark:text-white">
                    {activeMember.nombre} {activeMember.apellido}
                  </h3>
                  <div className="mt-1">{getBeltBadge(activeMember.grado_nombre)}</div>
                </div>
              </div>
              <button
                type="button"
                onClick={() => setActiveMember(null)}
                className="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center"
              >
                ✕
              </button>
            </div>

            <div className="space-y-3 py-2 text-sm">
              <div className="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                <MapPin className="w-4 h-4 text-rose-500" />
                <span>Sede: {activeMember.sede_nombre || 'No asignada'}</span>
              </div>
              {activeMember.correo && (
                <div className="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                  <Mail className="w-4 h-4 text-blue-500" />
                  <span>{activeMember.correo}</span>
                </div>
              )}
              {activeMember.telefono && (
                <div className="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                  <Phone className="w-4 h-4 text-emerald-500" />
                  <span>{activeMember.telefono}</span>
                </div>
              )}
              <div className="flex items-center gap-2 text-slate-600 dark:text-slate-300">
                <ShieldCheck className="w-4 h-4 text-amber-500" />
                <span>Estado: {activeMember.activo ? 'Miembro Activo' : 'Pendiente / Inactivo'}</span>
              </div>
            </div>

            <button
              type="button"
              onClick={() => setActiveMember(null)}
              className="w-full py-3 bg-slate-900 dark:bg-slate-100 hover:bg-slate-800 text-white dark:text-slate-900 rounded-xl font-bold text-sm transition-all"
            >
              Cerrar
            </button>
          </div>
        </div>
      )}
    </div>
  );
};
