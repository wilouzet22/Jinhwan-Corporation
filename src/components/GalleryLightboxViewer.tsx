import React, { useState } from 'react';
import { Image as ImageIcon, Video, X, ChevronLeft, ChevronRight, Maximize2, Tag } from 'lucide-react';
import { MultimediaItem } from '../types';

interface GalleryLightboxProps {
  initialItems?: MultimediaItem[];
}

export const GalleryLightboxViewer: React.FC<GalleryLightboxProps> = ({ initialItems = [] }) => {
  const defaultItems: MultimediaItem[] = [
    {
      id_multimedia: 1,
      titulo: 'Examen de Grados y Cinturones Negros 2026',
      categoria: 'Exámenes',
      tipo: 'foto',
      url: '/jinwha/public/img/slider.png',
      descripcion: 'Ceremonia solemne de graduación de cinturones negros y grados avanzados.',
    },
    {
      id_multimedia: 2,
      titulo: 'Entrenamiento Selección de Combate',
      categoria: 'Torneos',
      tipo: 'foto',
      url: '/jinwha/public/img/slider1.jpg',
      descripcion: 'Sesión intensiva de Kyorugi y preparación para el campeonato departamental.',
    },
    {
      id_multimedia: 3,
      titulo: 'Seminario Internacional de Formas Poomsae',
      categoria: 'Seminarios',
      tipo: 'foto',
      url: '/jinwha/public/img/slider2.jpg',
      descripcion: 'Perfeccionamiento técnico de las formas oficiales WT.',
    },
    {
      id_multimedia: 4,
      titulo: 'Exhibición y Rompimiento de Tablas Dojang Principal',
      categoria: 'Exhibiciones',
      tipo: 'foto',
      url: '/jinwha/public/img/slider3.jpg',
      descripcion: 'Demostración de potencia y enfoque mental por los alumnos de la academia.',
    },
  ];

  const items = initialItems.length > 0 ? initialItems : defaultItems;
  const [selectedCategory, setSelectedCategory] = useState<string>('Todos');
  const [activePhotoIndex, setActivePhotoIndex] = useState<number | null>(null);

  const categories = ['Todos', ...Array.from(new Set(items.map((i) => i.categoria).filter(Boolean)))];

  const filteredItems = selectedCategory === 'Todos' ? items : items.filter((i) => i.categoria === selectedCategory);

  const openLightbox = (index: number) => {
    setActivePhotoIndex(index);
  };

  const closeLightbox = () => {
    setActivePhotoIndex(null);
  };

  const nextPhoto = () => {
    if (activePhotoIndex !== null) {
      setActivePhotoIndex((activePhotoIndex + 1) % filteredItems.length);
    }
  };

  const prevPhoto = () => {
    if (activePhotoIndex !== null) {
      setActivePhotoIndex((activePhotoIndex - 1 + filteredItems.length) % filteredItems.length);
    }
  };

  const currentItem = activePhotoIndex !== null ? filteredItems[activePhotoIndex] : null;

  return (
    <div className="space-y-6 w-full">
      {/* Category Tabs */}
      <div className="flex flex-wrap gap-2 justify-center sm:justify-start">
        {categories.map((cat) => (
          <button
            key={cat}
            type="button"
            onClick={() => setSelectedCategory(cat)}
            className={`px-4 py-2 rounded-xl text-xs font-bold transition-all ${
              selectedCategory === cat
                ? 'bg-rose-600 text-white shadow-md shadow-rose-600/30'
                : 'bg-white dark:bg-slate-800/90 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700'
            }`}
          >
            {cat}
          </button>
        ))}
      </div>

      {/* Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        {filteredItems.map((item, idx) => (
          <div
            key={item.id_multimedia || idx}
            onClick={() => openLightbox(idx)}
            className="group relative rounded-2xl overflow-hidden shadow-md border border-slate-200 dark:border-slate-800 bg-slate-900 cursor-pointer aspect-4/3"
          >
            <img
              src={item.url}
              alt={item.titulo}
              className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              onError={(e) => {
                (e.currentTarget as HTMLImageElement).src = '/jinwha/public/img/slider.png';
              }}
            />
            <div className="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-4">
              <div className="flex justify-between items-start">
                <span className="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-600 text-white uppercase">
                  {item.categoria}
                </span>
                <span className="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center">
                  <Maximize2 className="w-4 h-4" />
                </span>
              </div>
              <div>
                <h4 className="text-sm font-bold text-white leading-tight">{item.titulo}</h4>
                {item.descripcion && (
                  <p className="text-xs text-slate-300 line-clamp-2 mt-1">{item.descripcion}</p>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Lightbox Modal */}
      {currentItem && (
        <div
          className="fixed inset-0 z-50 bg-black/90 backdrop-blur-md flex items-center justify-center p-4 sm:p-8 animate-in fade-in duration-200"
          onClick={closeLightbox}
        >
          <button
            type="button"
            onClick={closeLightbox}
            className="absolute top-4 right-4 z-50 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-lg"
          >
            <X className="w-6 h-6" />
          </button>

          <button
            type="button"
            onClick={(e) => {
              e.stopPropagation();
              prevPhoto();
            }}
            className="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center"
          >
            <ChevronLeft className="w-7 h-7" />
          </button>

          <button
            type="button"
            onClick={(e) => {
              e.stopPropagation();
              nextPhoto();
            }}
            className="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center"
          >
            <ChevronRight className="w-7 h-7" />
          </button>

          <div
            className="max-w-4xl max-h-[85vh] flex flex-col items-center justify-center gap-3"
            onClick={(e) => e.stopPropagation()}
          >
            <img
              src={currentItem.url}
              alt={currentItem.titulo}
              className="max-w-full max-h-[70vh] rounded-xl object-contain shadow-2xl"
            />
            <div className="text-center text-white px-4">
              <span className="text-xs font-bold uppercase tracking-wider text-rose-400">
                {currentItem.categoria}
              </span>
              <h3 className="text-lg font-bold">{currentItem.titulo}</h3>
              {currentItem.descripcion && (
                <p className="text-xs text-slate-300 max-w-xl mx-auto mt-1">{currentItem.descripcion}</p>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
