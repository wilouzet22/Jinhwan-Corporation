tailwind.config = {
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                primary: "#2563EB",   
                tkd: {
                    red: "#DC2626",    
                    blue: "#2563EB",   
                    black: "#0b0f19",  
                    white: "#FFFFFF",  
                    gold: "#F59E0B",   
                    gray: "#F3F4F6",   
                },
                "background-light": "#F3F4F6", 
                "background-dark": "#0b0f19",  
                "surface-light": "#ffffff",
                "surface-dark": "#1F2937",     
                "text-light-primary": "#111827", 
                "text-dark-primary": "#F9FAFB",  
                "text-light-secondary": "#4B5563", 
                "text-dark-secondary": "#9CA3AF",  
                "border-light": "#E5E7EB",    
                "border-dark": "#374151",     
            },
            fontFamily: {
                display: ["Oswald", "sans-serif"],
                body: ["Inter", "sans-serif"],
                sans: ["Inter", "sans-serif"], 
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
