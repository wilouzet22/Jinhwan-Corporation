import React from 'react';
import { createRoot } from 'react-dom/client';
import './styles/index.css';

import { BeltProgressVisualizer } from './components/BeltProgressVisualizer';
import { DashboardCharts } from './components/DashboardCharts';
import { MemberDirectoryLive } from './components/MemberDirectoryLive';
import { StudyModuleTracker } from './components/StudyModuleTracker';
import { GalleryLightboxViewer } from './components/GalleryLightboxViewer';
import { InteractiveCalendar } from './components/InteractiveCalendar';

// Global Component Map
const componentMap: Record<string, React.FC<any>> = {
  BeltProgressVisualizer,
  DashboardCharts,
  MemberDirectoryLive,
  StudyModuleTracker,
  GalleryLightboxViewer,
  InteractiveCalendar,
};

// Auto-mount: scans [data-react-component] and renders the matching React component.
// All other UI interactions (hamburger menu, theme toggle, dropdowns)
// are handled exclusively by public/js/modules/navigation.js
function mountReactComponents() {
  const mountPoints = document.querySelectorAll<HTMLElement>('[data-react-component]');

  mountPoints.forEach((element) => {
    const componentName = element.getAttribute('data-react-component');
    if (!componentName) return;

    const Component = componentMap[componentName];
    if (!Component) {
      console.warn(`[React Mount] Component "${componentName}" not found in componentMap.`);
      return;
    }

    let props = {};
    const rawProps = element.getAttribute('data-props');
    if (rawProps) {
      try {
        props = JSON.parse(rawProps);
      } catch (err) {
        console.error(`[React Mount] Error parsing props for "${componentName}":`, err);
      }
    }

    const root = createRoot(element);
    root.render(
      <React.StrictMode>
        <Component {...props} />
      </React.StrictMode>
    );
  });
}

// Execute after DOM is ready
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', mountReactComponents);
} else {
  mountReactComponents();
}
