tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                tkd: {
                    red: "#DC2626",    // Passion/Power (Hong)
                    blue: "#2563EB",   // Calm/Intelligence (Chong)
                    black: "#0b0f19",  // Deep premium black (updated)
                    white: "#FFFFFF",  // Purity
                    gold: "#F59E0B",   // Accent/Medal
                    gray: "#F3F4F6",   // Light background
                }
            },
            fontFamily: {
                display: ["Oswald", "sans-serif"],
                body: ["Inter", "sans-serif"],
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                'slide-in-right': 'slideInRight 0.5s ease-out forwards',
                'pulse-slow': 'pulseSlow 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'float': 'float 6s ease-in-out infinite',
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                pulseSlow: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '.5' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                }
            }
        },
    },
};
