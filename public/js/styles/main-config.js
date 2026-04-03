tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                tkd: {
                    red: "#DC2626",    // Passion/Power (Hong)
                    blue: "#2563EB",   // Calm/Intelligence (Chong)
                    black: "#111827",  // Mastery
                    white: "#FFFFFF",  // Purity
                    gold: "#F59E0B",   // Accent/Medal
                    gray: "#F3F4F6",   // Light background
                }
            },
            fontFamily: {
                display: ["Oswald", "sans-serif"],
                body: ["Roboto", "sans-serif"],
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                'slide-in-right': 'slideInRight 0.5s ease-out forwards',
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(-20px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                }
            }
        },
    },
};
