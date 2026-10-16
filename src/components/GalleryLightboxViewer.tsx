import React, { useState, useEffect, useCallback } from 'react';
import { Image as ImageIcon, X, ChevronLeft, ChevronRight, Maximize2 } from 'lucide-react';
import { MultimediaItem } from '../types';

interface GalleryLightboxProps {
  initialItems?: MultimediaItem[];
}

export const GalleryLightboxViewer: React.FC<GalleryLightboxProps> = ({ initialItems = [] }) => {
  const defaultItems: MultimediaItem[] = [
    {
      id_multimedia: 1,
      titulo: 'Examen de Grados y Cinturones',
      categoria: 'Exámenes',
      tipo: 'foto',
      url: '/public/img/slider.png',
      descripcion: 'Ceremonia solemne de graduación de cinturones y evaluación técnica.',
    },
    {
      id_multimedia: 2,
      titulo: 'Entrenamiento Selección de Combate',
      categoria: 'Torneos',
      tipo: 'foto',
      url: '/public/img/slider1.jpg',
      descripcion: 'Sesión intensiva de Kyorugi y preparación para el campeonato.',
    },
    {
      id_multimedia: 3,
      titulo: 'Seminario Internacional de Formas Poomsae',
      categoria: 'Seminarios',
      tipo: 'foto',
      url: '/public/img/slider2.jpg',
      descripcion: 'Perfeccionamiento técnico de las formas oficiales WT.',
    },
    {
      id_multimedia: 4,
      titulo: 'Exhibición y Rompimiento de Tablas',
      categoria: 'Exhibiciones',
      tipo: 'foto',
      url: '/public/img/slider3.jpg',
      descripcion: 'Demostración de potencia y enfoque mental por los alumnos de la academia.',
    },
  ];

  const rawItems = initialItems.length > 0 ? initialItems : defaultItems;

  // Función para normalizar URLs relativas según el entorno de ejecución
  const resolveUrl = (url: string) => {
    if (!url) return '';
    if (/^(https?:|\/\/|data:)/i.test(url)) return url;

    const pathname = typeof window !== 'undefined' ? window.location.pathname : '';
    const hasJinwhaPrefix = pathname.toLowerCase().includes('/jinwha');

    if (url.startsWith('/jinwha/') && !hasJinwhaPrefix) {
      return url.replace(/^\/jinwha/, '');
    }
    if (!url.startsWith('/jinwha/') && hasJinwhaPrefix && url.startsWith('/')) {
      return '/jinwha' + url;
    }
    return url;
  };

  const items = rawItems.map((item) => ({
    ...item,
    url: resolveUrl(item.url),
  }));

  const [selectedCategory, setSelectedCategory] = useState<string>('Todos');
  const [activePhotoIndex, setActivePhotoIndex] = useState<number | null>(null);

  const categories = ['Todos', ...Array.from(new Set(items.map((i) => i.categoria).filter(Boolean)))];

  const filteredItems = selectedCategory === 'Todos' ? items : items.filter((i) => i.categoria === selectedCategory);

  const openLightbox = (index: number) => {
    setActivePhotoIndex(index);
  };

  const closeLightbox = useCallback(() => {
    setActivePhotoIndex(null);
  }, []);

  const nextPhoto = useCallback(() => {
    if (activePhotoIndex !== null) {
      setActivePhotoIndex((prev) => (prev !== null ? (prev + 1) % filteredItems.length : null));
    }
  }, [activePhotoIndex, filteredItems.length]);

  const prevPhoto = useCallback(() => {
    if (activePhotoIndex !== null) {
      setActivePhotoIndex((prev) => (prev !== null ? (prev - 1 + filteredItems.length) % filteredItems.length : null));
    }
  }, [activePhotoIndex, filteredItems.length]);

  // Soporte de atajos de teclado (ESC, Flechas) y bloqueo de scroll
  useEffect(() => {
    if (activePhotoIndex === null) return;

    const handleKeyDown = (e: KeyboardEvent) => {
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowRight') nextPhoto();
      if (e.key === 'ArrowLeft') prevPhoto();
    };

    const originalOverflow = document.body.style.overflow;
    document.body.style.overflow = 'hidden';
    window.addEventListener('keydown', handleKeyDown);

    return () => {
      document.body.style.overflow = originalOverflow;
      window.removeEventListener('keydown', handleKeyDown);
    };
  }, [activePhotoIndex, closeLightbox, nextPhoto, prevPhoto]);

  const handleImageError = (e: React.SyntheticEvent<HTMLImageElement, Event>, originalUrl: string) => {
    const target = e.currentTarget;
    if (target.dataset.triedFallback === 'true') {
      target.style.opacity = '0.4';
      return;
    }
    target.dataset.triedFallback = 'true';

    // Probar alternando prefijo /jinwha
    if (originalUrl.includes('/jinwha/')) {
      target.src = originalUrl.replace('/jinwha/', '/');
    } else if (originalUrl.startsWith('/')) {
      target.src = '/jinwha' + originalUrl;
    } else {
      target.src = 'public/img/slider.png';
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
            className={`px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer ${
              selectedCategory === cat
                ? 'bg-rose-600 text-white shadow-md shadow-rose-600/30 ring-2 ring-rose-500/20 scale-102'
                : 'bg-white dark:bg-slate-800/90 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/80 border border-slate-200 dark:border-slate-700/70'
            }`}
          >
            {cat}
          </button>
        ))}
      </div>

      {/* Grid */}
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6">
        {filteredItems.map((item, idx) => (
          <div
            key={item.id_multimedia || idx}
            onClick={() => openLightbox(idx)}
            className="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl border border-slate-200/80 dark:border-slate-800 bg-slate-900 cursor-pointer aspect-4/3 transition-all duration-300 hover:-translate-y-1"
          >
            <img
              src={item.url}
              alt={item.titulo}
              className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              onError={(e) => handleImageError(e, item.url)}
              loading="lazy"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-between p-5">
              <div className="flex justify-between items-start">
                <span className="px-3 py-1 rounded-full text-[10px] font-bold bg-rose-600/90 backdrop-blur-sm text-white uppercase tracking-wider shadow-sm">
                  {item.categoria}
                </span>
                <span className="w-8 h-8 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center shadow-sm">
                  <Maximize2 className="w-4 h-4" />
                </span>
              </div>
              <div>
                <h4 className="text-base font-bold text-white leading-snug drop-shadow-sm">{item.titulo}</h4>
                {item.descripcion && (
                  <p className="text-xs text-slate-300 line-clamp-2 mt-1.5 leading-relaxed">{item.descripcion}</p>
                )}
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Lightbox Modal */}
      {currentItem && (
        <div
          className="fixed inset-0 z-50 bg-black/95 backdrop-blur-md flex items-center justify-center p-4 sm:p-8 animate-in fade-in duration-200"
          onClick={closeLightbox}
          role="dialog"
          aria-modal="true"
        >
          <button
            type="button"
            onClick={closeLightbox}
            className="absolute top-5 right-5 z-50 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center text-lg transition-colors cursor-pointer"
            title="Cerrar (Esc)"
          >
            <X className="w-6 h-6" />
          </button>

          {filteredItems.length > 1 && (
            <>
              <button
                type="button"
                onClick={(e) => {
                  e.stopPropagation();
                  prevPhoto();
                }}
                className="absolute left-4 sm:left-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer"
                title="Foto anterior (←)"
              >
                <ChevronLeft className="w-7 h-7" />
              </button>

              <button
                type="button"
                onClick={(e) => {
                  e.stopPropagation();
                  nextPhoto();
                }}
                className="absolute right-4 sm:right-6 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors cursor-pointer"
                title="Siguiente foto (→)"
              >
                <ChevronRight className="w-7 h-7" />
              </button>
            </>
          )}

          <div
            className="max-w-4xl max-h-[85vh] flex flex-col items-center justify-center gap-4"
            onClick={(e) => e.stopPropagation()}
          >
            <img
              src={currentItem.url}
              alt={currentItem.titulo}
              className="max-w-full max-h-[70vh] rounded-2xl object-contain shadow-2xl border border-white/10"
              onError={(e) => handleImageError(e, currentItem.url)}
            />
            <div className="text-center text-white px-4 max-w-xl">
              <span className="text-xs font-bold uppercase tracking-wider text-rose-400">
                {currentItem.categoria}
              </span>
              <h3 className="text-lg font-bold mt-0.5">{currentItem.titulo}</h3>
              {currentItem.descripcion && (
                <p className="text-xs text-slate-300 leading-relaxed mt-1.5">{currentItem.descripcion}</p>
              )}
              {filteredItems.length > 1 && (
                <p className="text-[11px] text-slate-500 font-mono mt-2">
                  {(activePhotoIndex ?? 0) + 1} de {filteredItems.length}
                </p>
              )}
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
