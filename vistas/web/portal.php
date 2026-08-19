<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Jinhwa Corporation' ?></title>
    <link rel="stylesheet" href="<?= asset('styles/output.css') ?>">
    <link rel="icon" type="image/x-icon" href="<?= asset('img/visual/logo.svg') ?>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Oswald:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        
        body {
            background-color: #0b0f19;
            color: #ffffff;
            margin: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .portal-container {
            height: 100vh;
            width: 100vw;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            background: radial-gradient(circle at center, #111827 0%, #0b0f19 100%);
            z-index: 1;
            padding: 20px;
        }

        .portal-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2v-4h4v-2h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2v-4h4v-2H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.5;
            z-index: -1;
        }

        .logo-wrapper {
            width: 220px;
            height: 220px;
            z-index: 10;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo-wrapper.minimized {
            transform: scale(0.65) translateY(-140px);
        }

        .svg-container {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 0 35px rgba(220,38,38,0.25));
        }

        .svg-container svg {
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .svg-container svg.ready {
            opacity: 1;
        }

        .svg-container svg path {
            fill-opacity: 0;
            stroke-width: 1.5px;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: stroke-dashoffset 1.0s cubic-bezier(0.4, 0, 0.2, 1), 
                        fill-opacity 0.5s ease, 
                        stroke-opacity 0.3s ease;
        }

        .actions-container {
            display: flex;
            gap: 30px;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            z-index: 20;
            width: 100%;
            justify-content: center;
            margin-top: -120px;
            padding: 0 20px;
        }

        .actions-container.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .portal-btn-group {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            width: 280px;
            group;
        }

        .btn-main {
            width: 100%;
            padding: 20px;
            font-family: 'Oswald', sans-serif;
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            transition: all 0.4s ease;
            text-align: center;
            position: relative;
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(16px);
            border-radius: 12px;
        }

        .btn-sitio {
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .btn-sitio::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 5%;
            width: 0;
            height: 2px;
            background-color: #2563EB; 
            transition: width 0.4s ease;
        }

        .btn-sitio:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(37, 99, 235, 0.4);
            box-shadow: 0 0 30px rgba(37, 99, 235, 0.2);
        }
        
        .btn-sitio:hover::after {
            width: 90%;
        }

        .btn-acceder {
            border: 1px solid rgba(220, 38, 38, 0.3);
            color: #ffffff;
            background: rgba(220, 38, 38, 0.08);
        }

        .btn-acceder::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 5%;
            width: 0;
            height: 2px;
            background-color: #DC2626; 
            transition: width 0.4s ease;
        }

        .btn-acceder:hover {
            background: rgba(220, 38, 38, 0.15);
            border-color: rgba(220, 38, 38, 0.6);
            box-shadow: 0 0 35px rgba(220, 38, 38, 0.35);
        }

        .btn-acceder:hover::after {
            width: 90%;
        }

        .btn-subtitle {
            margin-top: 18px;
            font-size: 0.9rem;
            color: #94a3b8;
            font-weight: 400;
            text-align: center;
            opacity: 0.7;
            letter-spacing: 0.05em;
            transition: opacity 0.4s ease, color 0.4s ease;
        }

        .portal-btn-group:hover .btn-subtitle {
            opacity: 1;
            color: #cbd5e1;
        }

        @media (max-width: 768px) {
            .actions-container {
                flex-direction: column;
                gap: 24px;
                margin-top: 30px;
                padding: 0 16px;
            }
            .logo-wrapper {
                width: 180px;
                height: 180px;
            }
            .logo-wrapper.minimized {
                transform: scale(0.6) translateY(-30px);
            }
            .portal-btn-group {
                width: 100%;
                max-width: 360px;
            }
            .btn-main {
                padding: 16px;
                font-size: 1.2rem;
            }
        }
        @media (max-width: 400px) {
            .logo-wrapper {
                width: 140px;
                height: 140px;
            }
            .logo-wrapper.minimized {
                transform: scale(0.55) translateY(-20px);
            }
        }
    </style>
</head>
<body>

    <div class="portal-container">
        <div class="logo-wrapper" id="logoWrapper">
            <div class="svg-container">
                <?php 
                    $svgPath = __DIR__ . '/../../public/img/visual/logo.svg';
                    if (file_exists($svgPath)) {
                        echo file_get_contents($svgPath);
                    } else {
                        echo "<!-- Logo SVG not found at $svgPath -->";
                    }
                ?>
            </div>
        </div>

        <div class="actions-container" id="actionsContainer">
            
            <a href="<?= base_url('/inicio') ?>" class="portal-btn-group">
                <div class="btn-main btn-sitio">Sitio Web</div>
                <div class="btn-subtitle">Explora nuestra esencia</div>
            </a>

            <a href="<?= base_url('/login') ?>" class="portal-btn-group">
                <div class="btn-main btn-acceder">Acceder</div>
                <div class="btn-subtitle">Acceso exclusivo para la familia Jinhwa</div>
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const svgElement = document.querySelector('.svg-container svg');
            if (!svgElement) return;

            const paths = Array.from(svgElement.querySelectorAll('path'));
            const logoWrapper = document.getElementById('logoWrapper');
            const actionsContainer = document.getElementById('actionsContainer');

            const svgBox = svgElement.getBBox();
            const centerX = svgBox.x + svgBox.width / 2;
            const centerY = svgBox.y + svgBox.height / 2;

            const pathData = paths.map((path) => {
                const length = path.getTotalLength();
                if (length === 0) return null;

                const bbox = path.getBBox();
                const pathCenterX = bbox.x + bbox.width / 2;
                const pathCenterY = bbox.y + bbox.height / 2;
                const distance = Math.sqrt(Math.pow(pathCenterX - centerX, 2) + Math.pow(pathCenterY - centerY, 2));
                
                let fillColor = path.getAttribute('fill') || window.getComputedStyle(path).fill;
                
                if (!fillColor || fillColor === 'none' || fillColor === 'rgba(0, 0, 0, 0)') {
                   fillColor = "#ffffff"; 
                }

                return { path, length, distance, fillColor };
            }).filter(p => p !== null);

            pathData.sort((a, b) => a.distance - b.distance);

            pathData.forEach((item, index) => {
                const { path, length, fillColor } = item;
                path.style.strokeDasharray = length;
                path.style.strokeDashoffset = length;
                path.style.stroke = fillColor;
                path.style.fill = fillColor;

                const seqDelay = index * 0.005;
                path.style.transitionDelay = `${seqDelay}s, ${seqDelay + 0.5}s, ${seqDelay + 1.0}s`;
            });

            svgElement.getBoundingClientRect(); 
            svgElement.classList.add('ready');

            setTimeout(() => {
                pathData.forEach(item => {
                    item.path.style.strokeDashoffset = '0';
                    item.path.style.fillOpacity = '1';
                    
                    setTimeout(() => {
                        item.path.style.strokeOpacity = '0';
                    }, 1200);
                });

                setTimeout(() => {
                    logoWrapper.classList.add('minimized');
                    setTimeout(() => {
                        actionsContainer.classList.add('show');
                    }, 200); 
                }, 1800);

            }, 200);
        });
    </script>
</body>
</html>
