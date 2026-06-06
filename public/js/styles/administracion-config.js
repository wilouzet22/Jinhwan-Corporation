tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                primary: "#2563EB",   // tkd-blue as primary
                tkd: {
                    red: "#DC2626",    // Passion/Power
                    blue: "#2563EB",   // Calm/Intelligence
                    black: "#0b0f19",  // Deep premium black (updated)
                    white: "#FFFFFF",  // Purity
                    gold: "#F59E0B",   // Accent/Medal
                    gray: "#F3F4F6",   // Light background
                },
                "background-light": "#F3F4F6", // tkd-gray
                "background-dark": "#0b0f19",  // tkd-black (updated)
                "surface-light": "#ffffff",
                "surface-dark": "#1F2937",     // Gray-800
                "text-light-primary": "#111827", // Gray-900
                "text-dark-primary": "#F9FAFB",  // Gray-50
                "text-light-secondary": "#4B5563", // Gray-600
                "text-dark-secondary": "#9CA3AF",  // Gray-400
                "border-light": "#E5E7EB",    // Gray-200
                "border-dark": "#374151",     // Gray-700
            },
            fontFamily: {
                display: ["Oswald", "sans-serif"],
                body: ["Inter", "sans-serif"],
                sans: ["Inter", "sans-serif"], // Override default sans
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                }
            }
        },
    },
};
