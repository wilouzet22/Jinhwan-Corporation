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

export const DashboardCharts: React.FC<DashboardChartsProps> = ({
  distribucionGrados = [],
  distribucionSedes = [],
}) => {
  const gradoLabels = distribucionGrados.map((g) => g.nombre);
  const gradoData = distribucionGrados.map((g) => Number(g.cantidad));

  const sedeLabels = distribucionSedes.map((s) => s.nombre.replace('Sede ', ''));
  const sedeData = distribucionSedes.map((s) => Number(s.cantidad));

  const doughnutData = {
    labels: gradoLabels.length > 0 ? gradoLabels : ['Blanco', 'Amarillo', 'Verde', 'Azul', 'Rojo', 'Negro 1 Dan'],
    datasets: [
      {
        label: 'Practicantes',
        data: gradoData.length > 0 ? gradoData : [12, 8, 15, 6, 9, 4],
        backgroundColor: [
          'rgba(241, 245, 249, 0.9)',
          'rgba(250, 204, 21, 0.9)',
          'rgba(34, 197, 94, 0.9)',
          'rgba(59, 130, 246, 0.9)',
          'rgba(239, 68, 68, 0.9)',
          'rgba(15, 23, 42, 0.95)',
          'rgba(168, 85, 247, 0.9)',
        ],
        borderColor: 'rgba(255, 255, 255, 0.2)',
        borderWidth: 2,
      },
    ],
  };

  const barData = {
    labels: sedeLabels.length > 0 ? sedeLabels : ['San Cristóbal', 'Santa Mónica', 'Itagüí'],
    datasets: [
      {
        label: 'Miembros Activos',
        data: sedeData.length > 0 ? sedeData : [24, 38, 19],
        backgroundColor: 'rgba(225, 29, 72, 0.85)',
        borderRadius: 8,
        hoverBackgroundColor: 'rgba(225, 29, 72, 1)',
      },
    ],
  };

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'bottom' as const,
        labels: {
          boxWidth: 12,
          padding: 15,
          font: { size: 12, family: 'Inter' },
        },
      },
    },
  };

  return (
    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 w-full">
      {/* Grados Distribution Card */}
      <div className="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-lg flex flex-col justify-between">
        <div>
          <h3 className="text-base font-bold text-slate-900 dark:text-white mb-1">
            Distribución por Cinturones y Grados
          </h3>
          <p className="text-xs text-slate-500 dark:text-slate-400 mb-4">
            Alumnos activos registrados en la academia
          </p>
        </div>
        <div className="h-64 w-full relative">
          <Doughnut data={doughnutData} options={chartOptions} />
        </div>
      </div>

      {/* Sedes Distribution Card */}
      <div className="bg-white dark:bg-slate-900 rounded-2xl p-6 border border-slate-200 dark:border-slate-800 shadow-lg flex flex-col justify-between">
        <div>
          <h3 className="text-base font-bold text-slate-900 dark:text-white mb-1">
            Población de Miembros por Sede
          </h3>
          <p className="text-xs text-slate-500 dark:text-slate-400 mb-4">
            Comparativa de practicantes por dojang
          </p>
        </div>
        <div className="h-64 w-full relative">
          <Bar
            data={barData}
            options={{
              ...chartOptions,
              plugins: { legend: { display: false } },
              scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(148, 163, 184, 0.15)' } },
                x: { grid: { display: false } },
              },
            }}
          />
        </div>
      </div>
    </div>
  );
};
