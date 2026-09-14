import React from 'react';
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
} from 'chart.js';
import { Doughnut, Bar } from 'react-chartjs-2';

ChartJS.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale, BarElement, Title);

interface DashboardChartsProps {
  distribucionGrados?: Array<{ nombre: string; cantidad: number | string }>;
  distribucionSedes?: Array<{ nombre: string; cantidad: number | string }>;
}

const getBeltColor = (name: string): string => {
  const n = name.toLowerCase();
  if (n.includes('pinta amarillo')) return '#fde047'; // amarillo suave
  if (n.includes('amarillo')) return '#eab308';       // amarillo puro
  if (n.includes('pinta verde')) return '#86efac';    // verde claro
  if (n.includes('verde')) return '#22c55e';          // verde puro
  if (n.includes('pinta azul')) return '#93c5fd';     // azul claro
  if (n.includes('azul')) return '#3b82f6';           // azul puro
  if (n.includes('pinta rojo')) return '#fca5a5';     // rojo claro
  if (n.includes('rojo')) return '#ef4444';           // rojo puro
  if (n.includes('pinta negro')) return '#c084fc';    // púrpura/negro
  if (n.includes('negro') || n.includes('dan')) return '#1e293b'; // negro pizarra
  if (n.includes('blanco')) return '#cbd5e1';         // gris plata/blanco
  return '#64748b';
};

export const DashboardCharts: React.FC<DashboardChartsProps> = ({
  distribucionGrados = [],
  distribucionSedes = [],
}) => {
  // Filtrar solo grados que tienen practicantes (> 0)
  const activeGrados = distribucionGrados.filter((g) => Number(g.cantidad) > 0);
  const gradoLabels = activeGrados.length > 0
    ? activeGrados.map((g) => g.nombre)
    : ['Blanco', 'Amarillo', 'Verde', 'Azul', 'Rojo', 'Negro 1 Dan'];
  const gradoData = activeGrados.length > 0
    ? activeGrados.map((g) => Number(g.cantidad))
    : [4, 6, 8, 5, 3, 2];
  const gradoColors = gradoLabels.map(getBeltColor);

  // Sedes
  const sedeLabels = distribucionSedes.map((s) => s.nombre);
  const sedeData = distribucionSedes.map((s) => Number(s.cantidad));
  const sedeColors = [
    'rgba(59, 130, 246, 0.85)',  // Azul
    'rgba(244, 63, 94, 0.85)',   // Rosa / Rojo TKD
    'rgba(245, 158, 11, 0.85)',  // Ámbar
    'rgba(16, 185, 129, 0.85)',  // Esmeralda
  ];

  const doughnutData = {
    labels: gradoLabels,
    datasets: [
      {
        label: 'Alumnos',
        data: gradoData,
        backgroundColor: gradoColors,
        borderColor: 'rgba(255, 255, 255, 0.25)',
        borderWidth: 2,
        hoverOffset: 6,
      },
    ],
  };

  const barData = {
    labels: sedeLabels.length > 0 ? sedeLabels : ['San Cristóbal', 'Santa Mónica', 'Itagüí'],
    datasets: [
      {
        label: 'Miembros Activos',
        data: sedeData.length > 0 ? sedeData : [8, 42, 0],
        backgroundColor: sedeColors.slice(0, sedeLabels.length || 3),
        borderRadius: 10,
        maxBarThickness: 56,
      },
    ],
  };

  return (
    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
      {/* Grados Distribution Card */}
      <div className="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
        <div className="flex items-center justify-between mb-4">
          <div>
            <h3 className="text-base font-bold text-slate-900 dark:text-white">
              Distribución por Grados Activos
            </h3>
            <p className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
              Alumnos registrados con practicantes vigentes
            </p>
          </div>
          <span className="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 dark:bg-blue-950/50 text-tkd-blue border border-blue-200 dark:border-blue-900">
            {gradoData.reduce((a, b) => a + b, 0)} Total
          </span>
        </div>
        <div className="h-72 w-full relative flex items-center justify-center">
          <Doughnut
            data={doughnutData}
            options={{
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  position: 'right',
                  labels: {
                    boxWidth: 10,
                    boxHeight: 10,
                    padding: 10,
                    usePointStyle: true,
                    pointStyle: 'circle',
                    font: { size: 11, family: 'Inter', weight: 600 },
                  },
                },
                tooltip: {
                  callbacks: {
                    label: (context) => ` ${context.label}: ${context.raw} alumnos`,
                  },
                },
              },
              cutout: '62%',
            }}
          />
        </div>
      </div>

      {/* Sedes Distribution Card */}
      <div className="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col justify-between">
        <div className="flex items-center justify-between mb-4">
          <div>
            <h3 className="text-base font-bold text-slate-900 dark:text-white">
              Población de Miembros por Sede
            </h3>
            <p className="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
              Comparativa de practicantes por dojang
            </p>
          </div>
          <span className="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
            {sedeLabels.length} Sedes
          </span>
        </div>
        <div className="h-72 w-full relative flex items-center justify-center">
          <Bar
            data={barData}
            options={{
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
                tooltip: {
                  callbacks: {
                    label: (context) => ` ${context.raw} alumnos activos`,
                  },
                },
              },
              scales: {
                y: {
                  beginAtZero: true,
                  grid: { color: 'rgba(148, 163, 184, 0.12)' },
                  ticks: { precision: 0, font: { size: 11 } },
                },
                x: {
                  grid: { display: false },
                  ticks: {
                    maxRotation: 0,
                    minRotation: 0,
                    font: { size: 12, weight: 600 },
                  },
                },
              },
            }}
          />
        </div>
      </div>
    </div>
  );
};
