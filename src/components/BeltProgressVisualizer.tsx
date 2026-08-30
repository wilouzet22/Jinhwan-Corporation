import React, { useState } from 'react';
import { Award, CheckCircle2, Circle, Clock, Flame, ChevronRight, Sparkles, BookOpen } from 'lucide-react';
import confetti from 'canvas-confetti';

interface BeltProgressProps {
  currentGrade: string;
  nextGrade?: string;
  timeInGrade?: string;
  examDate?: string;
  initialRequirements?: Array<{
    id: string;
    title: string;
    category: string;
    completed: boolean;
  }>;
}

export const BeltProgressVisualizer: React.FC<BeltProgressProps> = ({
  currentGrade = 'Blanco',
  nextGrade = 'Pinta Amarillo',
  timeInGrade = '4 meses',
  examDate = '15 Octubre 2026',
  initialRequirements,
}) => {
  const defaultRequirements = [
    { id: '1', title: 'Poomsae Taegeuk correspondiente dominado con ritmo y potencia', category: 'Poomsae', completed: true },
    { id: '2', title: 'Técnicas de patada básica y combinaciones fluidas', category: 'Técnica', completed: true },
    { id: '3', title: 'Terminología marcial y órdenes en idioma coreano', category: 'Teoría', completed: false },
    { id: '4', title: 'Defensa personal y combate reglado (Kyorugi)', category: 'Combate', completed: false },
    { id: '5', title: 'Asistencia mínima del 85% a los entrenamientos de sede', category: 'Disciplina', completed: true },
  ];

  const [requirements, setRequirements] = useState(initialRequirements || defaultRequirements);

  const toggleReq = (id: string) => {
    setRequirements((prev) => {
      const updated = prev.map((r) => (r.id === id ? { ...r, completed: !r.completed } : r));
      const allDone = updated.every((r) => r.completed);
      if (allDone) {
        confetti({
          particleCount: 80,
          spread: 70,
          origin: { y: 0.6 },
          colors: ['#dc2626', '#2563eb', '#f59e0b', '#ffffff'],
        });
      }
      return updated;
    });
  };

  const completedCount = requirements.filter((r) => r.completed).length;
  const progressPercent = Math.round((completedCount / requirements.length) * 100);

  // Helper to render belt styling
  const getBeltStyle = (name: string) => {
    const lower = name.toLowerCase();
    if (lower.includes('negro')) {
      const danMatch = lower.match(/(\d+)\s*dan/);
      const dan = danMatch ? parseInt(danMatch[1]) : 1;
      return {
        bg: 'bg-gradient-to-r from-zinc-950 via-zinc-900 to-zinc-950',
        text: 'text-amber-400',
        border: 'border-zinc-800',
        isDan: true,
        danCount: dan,
      };
    }
    if (lower.includes('rojo') && lower.includes('pinta')) {
      return { bg: 'bg-gradient-to-r from-red-600 via-zinc-900 to-red-600', text: 'text-white', border: 'border-red-700' };
    }
    if (lower.includes('rojo')) {
      return { bg: 'bg-gradient-to-r from-red-600 via-red-500 to-red-600', text: 'text-white', border: 'border-red-700' };
    }
    if (lower.includes('azul') && lower.includes('pinta')) {
      return { bg: 'bg-gradient-to-r from-blue-600 via-red-600 to-blue-600', text: 'text-white', border: 'border-blue-700' };
    }
    if (lower.includes('azul')) {
      return { bg: 'bg-gradient-to-r from-blue-600 via-blue-500 to-blue-600', text: 'text-white', border: 'border-blue-700' };
    }
    if (lower.includes('verde') && lower.includes('pinta')) {
      return { bg: 'bg-gradient-to-r from-emerald-600 via-blue-600 to-emerald-600', text: 'text-white', border: 'border-emerald-700' };
    }
    if (lower.includes('verde')) {
      return { bg: 'bg-gradient-to-r from-emerald-600 via-emerald-500 to-emerald-600', text: 'text-white', border: 'border-emerald-700' };
    }
    if (lower.includes('amarillo') && lower.includes('pinta')) {
      return { bg: 'bg-gradient-to-r from-amber-400 via-emerald-500 to-amber-400', text: 'text-zinc-900', border: 'border-amber-500' };
    }
    if (lower.includes('amarillo')) {
      return { bg: 'bg-gradient-to-r from-amber-400 via-yellow-300 to-amber-400', text: 'text-zinc-900', border: 'border-amber-500' };
    }
    return { bg: 'bg-gradient-to-r from-slate-100 via-white to-slate-200', text: 'text-zinc-900', border: 'border-slate-300' };
  };

  const currentStyle = getBeltStyle(currentGrade);

  return (
    <div className="bg-white dark:bg-slate-900/90 rounded-2xl p-6 shadow-xl border border-slate-200 dark:border-slate-800 backdrop-blur-md transition-all">
      {/* Header Banner */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-6 border-b border-slate-100 dark:border-slate-800">
        <div>
          <div className="flex items-center gap-2 mb-1">
            <span className="px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-rose-100 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/40">
              Rango Actual
            </span>
            <span className="flex items-center gap-1 text-xs text-slate-500 dark:text-slate-400">
              <Clock className="w-3.5 h-3.5" /> {timeInGrade}
            </span>
          </div>
          <h2 className="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
            {currentGrade}
          </h2>
        </div>

        <div className="flex items-center gap-3">
          <div className="text-right">
            <p className="text-xs font-medium text-slate-400 dark:text-slate-500 uppercase">Siguiente Grado</p>
            <p className="text-base font-bold text-slate-800 dark:text-slate-200 flex items-center justify-end gap-1">
              {nextGrade} <ChevronRight className="w-4 h-4 text-rose-500" />
            </p>
          </div>
        </div>
      </div>

      {/* Realistic Belt Visual */}
      <div className="py-6">
        <div className="relative group">
          <div
            className={`w-full h-14 rounded-xl shadow-lg border-2 ${currentStyle.border} ${currentStyle.bg} flex items-center justify-between px-6 relative overflow-hidden transition-transform duration-300 hover:scale-[1.01]`}
          >
            {/* Belt Fabric texture overlay */}
            <div className="absolute inset-0 bg-gradient-to-b from-white/20 via-transparent to-black/30 pointer-events-none" />

            {/* Left Belt End with Korean Martial Arts patch */}
            <div className="relative z-10 flex items-center gap-2">
              <div className="w-8 h-8 rounded bg-black/60 border border-amber-400/50 flex items-center justify-center text-[10px] font-bold text-amber-300 tracking-tighter">
                진환
              </div>
              <span className={`font-black text-sm uppercase tracking-widest ${currentStyle.text} drop-shadow-sm`}>
                Jinhwan TKD
              </span>
            </div>

            {/* Right side: Dan Bars or Tip indicator */}
            <div className="relative z-10 flex items-center gap-1.5">
              {currentStyle.isDan ? (
                Array.from({ length: currentStyle.danCount || 1 }).map((_, i) => (
                  <div
                    key={i}
                    className="w-2.5 h-10 bg-gradient-to-b from-amber-200 via-amber-400 to-amber-500 rounded-sm shadow-sm border-x border-amber-600"
                    title={`Dan ${i + 1}`}
                  />
                ))
              ) : (
                <div className="px-3 py-1 rounded bg-black/40 backdrop-blur-xs text-xs font-bold text-white border border-white/20">
                  Gup
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Progress Bar & Stats */}
      <div className="space-y-2 mb-6">
        <div className="flex justify-between items-center text-sm font-semibold">
          <span className="text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
            <Flame className="w-4 h-4 text-rose-500" /> Preparación para Examen
          </span>
          <span className={`px-2 py-0.5 rounded-full text-xs font-bold ${
            progressPercent === 100
              ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400'
              : 'bg-rose-100 dark:bg-rose-950 text-rose-600 dark:text-rose-400'
          }`}>
            {progressPercent}% Completado
          </span>
        </div>
        <div className="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden p-0.5 border border-slate-200 dark:border-slate-700/60">
          <div
            className="bg-gradient-to-r from-rose-500 via-amber-500 to-emerald-500 h-full rounded-full transition-all duration-700 ease-out shadow-sm"
            style={{ width: `${progressPercent}%` }}
          />
        </div>
      </div>

      {/* Checklist of Promotion Requirements */}
      <div>
        <div className="flex items-center justify-between mb-3">
          <h3 className="text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
            <Award className="w-4 h-4 text-amber-500" /> Requisitos de Ascenso ({completedCount}/{requirements.length})
          </h3>
          {progressPercent === 100 && (
            <span className="inline-flex items-center gap-1 text-xs font-bold text-emerald-500 animate-pulse">
              <Sparkles className="w-3.5 h-3.5" /> ¡Listo para postulación!
            </span>
          )}
        </div>

        <div className="space-y-2.5">
          {requirements.map((req) => (
            <div
              key={req.id}
              onClick={() => toggleReq(req.id)}
              className={`flex items-start gap-3 p-3 rounded-xl border transition-all cursor-pointer select-none ${
                req.completed
                  ? 'bg-emerald-50/70 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/40 text-slate-800 dark:text-slate-200'
                  : 'bg-slate-50 dark:bg-slate-800/50 border-slate-200/80 dark:border-slate-800 text-slate-600 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-700'
              }`}
            >
              <button type="button" className="mt-0.5 shrink-0 focus:outline-none">
                {req.completed ? (
                  <CheckCircle2 className="w-5 h-5 text-emerald-500 fill-emerald-100 dark:fill-emerald-950" />
                ) : (
                  <Circle className="w-5 h-5 text-slate-400 dark:text-slate-600" />
                )}
              </button>
              <div className="flex-1">
                <div className="flex items-center gap-2">
                  <span className="text-xs font-bold px-2 py-0.5 rounded bg-slate-200/70 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                    {req.category}
                  </span>
                  <p className={`text-sm font-medium ${req.completed ? 'line-through opacity-80' : ''}`}>
                    {req.title}
                  </p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};
