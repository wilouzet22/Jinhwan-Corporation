import React, { useState } from 'react';
import { BookOpen, Award, Volume2, Shield, Flame, CheckCircle, ChevronDown, Sparkles } from 'lucide-react';

interface StudyItem {
  id: string;
  title: string;
  subtitle?: string;
  korean?: string;
  description: string;
  steps?: string[];
  tips?: string;
  completed?: boolean;
}

export const StudyModuleTracker: React.FC = () => {
  const [activeTab, setActiveTab] = useState<'poomsae' | 'patadas' | 'terminologia' | 'principios'>('poomsae');
  const [expandedItem, setExpandedItem] = useState<string | null>('p-1');
  const [completedItems, setCompletedItems] = useState<Record<string, boolean>>({
    'p-1': true,
    'k-1': true,
    't-1': true,
  });

  const toggleComplete = (id: string, e: React.MouseEvent) => {
    e.stopPropagation();
    setCompletedItems((prev) => ({ ...prev, [id]: !prev[id] }));
  };

  const poomsaeList: StudyItem[] = [
    {
      id: 'p-1',
      title: 'Taegeuk Il Jang (1)',
      subtitle: 'Grado: Cinturón Amarillo (8º Gup)',
      korean: '태극 1장 (Keon - Cielo y Luz)',
      description: 'Representa el principio "Keon", el cielo y el cosmos. Simboliza el origen de todas las cosas y la grandeza del universo. Se enfoca en posturas básicas y golpes frontales.',
      steps: [
        'Apertura en Naranhi Seogi (Posición de atención y saludo)',
        'Giro a la izquierda en Ap Seogi + Arae Makki (Bloqueo bajo)',
        'Paso adelante en Ap Seogi + Momtong Jireugi (Golpe medio de puño)',
        'Giro de 180° a la derecha en Ap Seogi + Arae Makki',
        'Paso adelante en Ap Seogi + Momtong Jireugi',
        'Giro hacia el frente en Ap Koobi (Posición larga) + Arae Makki + Momtong Jireugi',
      ],
      tips: 'Mantener la espalda recta y el centro de gravedad bajo en las posiciones Ap Koobi.',
    },
    {
      id: 'p-2',
      title: 'Taegeuk Ee Jang (2)',
      subtitle: 'Grado: Cinturón Pinta Verde (7º Gup)',
      korean: '태극 2장 (Tae - Firmeza y Alegría)',
      description: 'Representa "Tae", la serenidad interior y la firmeza exterior, simbolizada por el lago tranquilo. Introduce defensas a la cabeza (Olgul Makki).',
      steps: [
        'Apertura en Naranhi Seogi',
        'Giro a la izquierda en Ap Seogi + Arae Makki',
        'Paso adelante en Ap Koobi + Momtong Bandae Jireugi',
        'Giro a la derecha en Ap Seogi + Arae Makki',
        'Paso adelante en Ap Koobi + Momtong Bandae Jireugi',
        'Avance frontal con Olgul Makki (Bloqueo alto)',
      ],
      tips: 'Asegurar que el bloqueo alto sobrepase la frente protegiendo la línea central.',
    },
    {
      id: 'p-3',
      title: 'Taegeuk Sam Jang (3)',
      subtitle: 'Grado: Cinturón Verde (6º Gup)',
      korean: '태극 3장 (Ri - Fuego y Pasión)',
      description: 'Representa el fuego "Ri", brillante y lleno de energía. Combina defensas con golpes de cuchillo de mano (Sonnal).',
      steps: [
        'Apertura en Naranhi Seogi',
        'Arae Makki seguido de patada frontal (Ap Chagui) y doble golpe de puño',
        'Transición rápida con defensas de canto de mano (Sonnal Mok Chigi)',
      ],
      tips: 'Coordinar la velocidad de los ataques con la respiración exhalando en el impacto.',
    },
  ];

  const patadasList: StudyItem[] = [
    {
      id: 'k-1',
      title: 'Ap Chagui (Patada Frontal)',
      korean: '앞차기',
      description: 'La patada fundamental de Taekwondo. Se golpea con el metatarso (Apchook) elevando la rodilla antes de extender velozmente.',
      steps: [
        'Flexión y elevación de rodilla apuntando al objetivo',
        'Extensión súbita de la pierna hacia el frente',
        'Impacto firme con el metatarso del pie',
        'Recogida inmediata de la rodilla antes de apoyar el pie',
      ],
      tips: 'Nunca patear con la rodilla estirada desde el inicio; la velocidad nace del pliegue de rodilla.',
    },
    {
      id: 'k-2',
      title: 'Dollyo Chagui (Patada Circular)',
      korean: '돌려차기',
      description: 'Patada semicircular de gran potencia que impacta en el costado o la cabeza con el empeine (Baldeung).',
      steps: [
        'Rotación del pie de apoyo 180° para liberar la cadera',
        'Elevación de rodilla en trayectoria diagonal/horizontal',
        'Snap explosivo de la pantorrilla con impacto de empeine',
        'Recogida a la posición inicial manteniendo guardia arriba',
      ],
      tips: 'El giro completo del talón del pie de apoyo previene lesiones de rodilla y maximiza la fuerza.',
    },
    {
      id: 'k-3',
      title: 'Yop Chagui (Patada Lateral)',
      korean: '옆차기',
      description: 'Ataque penetrante y rectilíneo que golpea con el filo del talón (Dwichook / Balnal).',
      steps: [
        'Cargar la rodilla hacia el pecho apuntando el talón al objetivo',
        'Giro del pie de base mientras se empuja en línea recta',
        'Alineación perfecta de hombro, cadera y talón',
      ],
      tips: 'El cuerpo debe formar una sola línea de transmisión de fuerza.',
    },
  ];

  const terminologiaList: StudyItem[] = [
    {
      id: 't-1',
      title: 'Charyeot / Kyung-ne',
      korean: '차렷 / 경례',
      description: 'Órdenes de "Atención / Saludo de respeto con inclinación de 45°".',
    },
    {
      id: 't-2',
      title: 'Joon-bi / Shi-jak',
      korean: '준비 / 시작',
      description: 'Órdenes de "Listos / Comenzar el ejercicio o combate".',
    },
    {
      id: 't-3',
      title: 'Dojang / Dobok / Ti',
      korean: '도장 / 도복 / 띠',
      description: 'Sala de entrenamiento (Dojang), Uniforme de Taekwondo (Dobok) y Cinturón marcial (Ti).',
    },
    {
      id: 't-4',
      title: 'Kalyo / Geuman',
      korean: '갈려 / 그만',
      description: 'Órdenes arbitrales de "Separar / Detener combate".',
    },
  ];

  const principiosList: StudyItem[] = [
    {
      id: 'pr-1',
      title: 'Ye-Ui (Cortesía)',
      korean: '예의',
      description: 'Tratar a todos con nobleza, respeto y educación sincera tanto dentro del Dojang como en la vida diaria.',
    },
    {
      id: 'pr-2',
      title: 'Yom-Chi (Integridad)',
      korean: '염치',
      description: 'Saber distinguir lo correcto de lo incorrecto y tener el coraje moral de actuar con rectitud y honestidad.',
    },
    {
      id: 'pr-3',
      title: 'In-Nae (Perseverancia)',
      korean: '인내',
      description: 'La paciencia y constancia para no rendirse ante los obstáculos hasta alcanzar la maestría técnica y espiritual.',
    },
    {
      id: 'pr-4',
      title: 'Guk-Gi (Autocontrol)',
      korean: '극기',
      description: 'El dominio absoluto de las emociones, impulsos y técnicas para usar la fuerza marcial solo con prudencia.',
    },
    {
      id: 'pr-5',
      title: 'Baekjul-Bool-Gool (Espíritu Indomable)',
      korean: '백절불굴',
      description: 'Afrontar la injusticia y las dificultades con valentía inquebrantable sin dejarse intimidar por ningún desafío.',
    },
  ];

  const getCurrentList = () => {
    switch (activeTab) {
      case 'poomsae':
        return poomsaeList;
      case 'patadas':
        return patadasList;
      case 'terminologia':
        return terminologiaList;
      case 'principios':
        return principiosList;
      default:
        return poomsaeList;
    }
  };

  const list = getCurrentList();

  return (
    <div className="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-xl space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
        <div>
          <h2 className="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
            <BookOpen className="w-5 h-5 text-rose-500" /> Centro de Estudio Técnico y Marcial
          </h2>
          <p className="text-xs text-slate-500 dark:text-slate-400">
            Material oficial de preparación técnica y examen de grado Jinhwan
          </p>
        </div>
      </div>

      {/* Tabs */}
      <div className="flex flex-wrap gap-2 p-1.5 bg-slate-100 dark:bg-slate-800/80 rounded-xl border border-slate-200 dark:border-slate-700/60">
        <button
          type="button"
          onClick={() => setActiveTab('poomsae')}
          className={`flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all ${
            activeTab === 'poomsae'
              ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-xs'
              : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
          }`}
        >
          <Award className="w-4 h-4" /> Formas (Poomsae)
        </button>
        <button
          type="button"
          onClick={() => setActiveTab('patadas')}
          className={`flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all ${
            activeTab === 'patadas'
              ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-xs'
              : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
          }`}
        >
          <Flame className="w-4 h-4" /> Técnicas (Chagui)
        </button>
        <button
          type="button"
          onClick={() => setActiveTab('terminologia')}
          className={`flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all ${
            activeTab === 'terminologia'
              ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-xs'
              : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
          }`}
        >
          <Volume2 className="w-4 h-4" /> Vocabulario Coreano
        </button>
        <button
          type="button"
          onClick={() => setActiveTab('principios')}
          className={`flex items-center gap-2 px-4 py-2 rounded-lg text-xs font-bold transition-all ${
            activeTab === 'principios'
              ? 'bg-white dark:bg-slate-700 text-rose-600 dark:text-rose-400 shadow-xs'
              : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
          }`}
        >
          <Shield className="w-4 h-4" /> Código de Honor
        </button>
      </div>

      {/* Accordion List */}
      <div className="space-y-3">
        {list.map((item) => {
          const isExpanded = expandedItem === item.id;
          const isDone = !!completedItems[item.id];

          return (
            <div
              key={item.id}
              className={`rounded-xl border transition-all ${
                isDone
                  ? 'border-emerald-200 dark:border-emerald-900/40 bg-emerald-50/30 dark:bg-emerald-950/10'
                  : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900'
              }`}
            >
              <div
                onClick={() => setExpandedItem(isExpanded ? null : item.id)}
                className="flex items-center justify-between p-4 cursor-pointer select-none"
              >
                <div className="flex items-center gap-3">
                  <button
                    type="button"
                    onClick={(e) => toggleComplete(item.id, e)}
                    className={`w-6 h-6 rounded-full flex items-center justify-center transition-all ${
                      isDone
                        ? 'bg-emerald-500 text-white'
                        : 'border-2 border-slate-300 dark:border-slate-600 hover:border-rose-500'
                    }`}
                  >
                    {isDone && <CheckCircle className="w-4 h-4" />}
                  </button>

                  <div>
                    <div className="flex items-center gap-2">
                      <h4 className="font-bold text-sm text-slate-900 dark:text-white">{item.title}</h4>
                      {item.korean && (
                        <span className="text-xs px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 font-mono">
                          {item.korean}
                        </span>
                      )}
                    </div>
                    {item.subtitle && <p className="text-xs text-slate-400 mt-0.5">{item.subtitle}</p>}
                  </div>
                </div>

                <div className="flex items-center gap-2 text-slate-400">
                  <ChevronDown className={`w-4 h-4 transition-transform duration-200 ${isExpanded ? 'rotate-180' : ''}`} />
                </div>
              </div>

              {isExpanded && (
                <div className="px-5 pb-5 pt-1 text-sm text-slate-600 dark:text-slate-300 border-t border-slate-100 dark:border-slate-800/80 space-y-3 mt-1">
                  <p>{item.description}</p>

                  {item.steps && item.steps.length > 0 && (
                    <div className="bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl space-y-2">
                      <p className="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        Secuencia de Movimientos:
                      </p>
                      <ol className="list-decimal list-inside space-y-1 text-xs">
                        {item.steps.map((step, idx) => (
                          <li key={idx} className="leading-relaxed">
                            {step}
                          </li>
                        ))}
                      </ol>
                    </div>
                  )}

                  {item.tips && (
                    <p className="text-xs text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40 p-2.5 rounded-lg border border-amber-200 dark:border-amber-900/40">
                      <strong>Consejo del Maestro:</strong> {item.tips}
                    </p>
                  )}
                </div>
              )}
            </div>
          );
        })}
      </div>
    </div>
  );
};
