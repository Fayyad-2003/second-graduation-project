import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", "Figtree", ...defaultTheme.fontFamily.sans],
                mono: ["JetBrains Mono", ...defaultTheme.fontFamily.mono],
            },
            fontSize: {
                "display-xl": [
                    "4.5rem",
                    { lineHeight: "1.1", letterSpacing: "-0.02em" },
                ],
                "display-lg": [
                    "3.75rem",
                    { lineHeight: "1.1", letterSpacing: "-0.02em" },
                ],
                "display-md": [
                    "3rem",
                    { lineHeight: "1.2", letterSpacing: "-0.01em" },
                ],
                "display-sm": [
                    "2.25rem",
                    { lineHeight: "1.2", letterSpacing: "-0.01em" },
                ],
                "heading-xl": [
                    "1.875rem",
                    { lineHeight: "1.3", letterSpacing: "-0.01em" },
                ],
                "heading-lg": [
                    "1.5rem",
                    { lineHeight: "1.3", letterSpacing: "-0.01em" },
                ],
                "heading-md": [
                    "1.25rem",
                    { lineHeight: "1.4", letterSpacing: "-0.01em" },
                ],
                "heading-sm": [
                    "1.125rem",
                    { lineHeight: "1.4", letterSpacing: "-0.01em" },
                ],
                "body-xl": ["1.125rem", { lineHeight: "1.6" }],
                "body-lg": ["1rem", { lineHeight: "1.6" }],
                "body-md": ["0.875rem", { lineHeight: "1.6" }],
                "body-sm": ["0.8125rem", { lineHeight: "1.5" }],
                "body-xs": ["0.75rem", { lineHeight: "1.5" }],
                caption: [
                    "0.6875rem",
                    { lineHeight: "1.5", letterSpacing: "0.02em" },
                ],
            },
            spacing: {
                0: "0",
                px: "1px",
                0.5: "0.125rem",
                1: "0.25rem",
                1.5: "0.375rem",
                2: "0.5rem",
                2.5: "0.625rem",
                3: "0.75rem",
                3.5: "0.875rem",
                4: "1rem",
                5: "1.25rem",
                6: "1.5rem",
                7: "1.75rem",
                8: "2rem",
                9: "2.25rem",
                10: "2.5rem",
                11: "2.75rem",
                12: "3rem",
                14: "3.5rem",
                16: "4rem",
                20: "5rem",
                24: "6rem",
                28: "7rem",
                32: "8rem",
                36: "9rem",
                40: "10rem",
                44: "11rem",
                48: "12rem",
                52: "13rem",
                56: "14rem",
                60: "15rem",
                64: "16rem",
                72: "18rem",
                80: "20rem",
                96: "24rem",
            },
            colors: {
                // Modern Brand Palette - Refined & Accessible
                brand: {
                    50: "#f0f4ff",
                    100: "#e0e9ff",
                    200: "#c7d7fe",
                    300: "#a5b8fc",
                    400: "#8892f7",
                    500: "#6366f1",
                    600: "#4f46e5",
                    700: "#4338ca",
                    800: "#3730a3",
                    900: "#312e81",
                    950: "#1e1b4b",
                },
                // Primary - Used for primary actions, links, focus states
                primary: {
                    50: "#f0f9ff",
                    100: "#e0f2fe",
                    200: "#bae6fd",
                    300: "#7dd3fc",
                    400: "#38bdf8",
                    500: "#0ea5e9",
                    600: "#0284c7",
                    700: "#0369a1",
                    800: "#075985",
                    900: "#0c4a6e",
                    950: "#082f49",
                    primary: "#0284c7",
                    secondary: "#0ea5e9",
                    dark: "#0c4a6e",
                    light: "#e0f2fe",
                },
                // Secondary - Used for secondary actions, accents
                secondary: {
                    50: "#fdf4ff",
                    100: "#fae8ff",
                    200: "#f5d0fe",
                    300: "#f0abfc",
                    400: "#e879f9",
                    500: "#d946ef",
                    600: "#c026d3",
                    700: "#a21caf",
                    800: "#86198f",
                    900: "#701a75",
                    950: "#4a044e",
                },
                // Accent - Used for highlights, special actions
                accent: {
                    50: "#fff7ed",
                    100: "#ffedd5",
                    200: "#fed7aa",
                    300: "#fdba74",
                    400: "#fb923c",
                    500: "#f97316",
                    600: "#ea580c",
                    700: "#c2410c",
                    800: "#9a3412",
                    900: "#7c2d12",
                    950: "#431407",
                },
                // Success - Positive states, confirmations
                success: {
                    50: "#f0fdf4",
                    100: "#dcfce7",
                    200: "#bbf7d0",
                    300: "#86efac",
                    400: "#4ade80",
                    500: "#22c55e",
                    600: "#16a34a",
                    700: "#15803d",
                    800: "#166534",
                    900: "#14532d",
                    950: "#052e16",
                },
                // Warning - Caution states
                warning: {
                    50: "#fffbeb",
                    100: "#fef3c7",
                    200: "#fde68a",
                    300: "#fcd34d",
                    400: "#fbbf24",
                    500: "#f59e0b",
                    600: "#d97706",
                    700: "#b45309",
                    800: "#92400e",
                    900: "#78350f",
                    950: "#451a03",
                },
                // Danger - Errors, destructive actions
                danger: {
                    50: "#fef2f2",
                    100: "#fee2e2",
                    200: "#fecaca",
                    300: "#fca5a5",
                    400: "#f87171",
                    500: "#ef4444",
                    600: "#dc2626",
                    700: "#b91c1c",
                    800: "#991b1b",
                    900: "#7f1d1d",
                    950: "#450a0a",
                },
                // Neutral - Text, borders, backgrounds
                neutral: {
                    50: "#fafafa",
                    100: "#f5f5f5",
                    200: "#e5e5e5",
                    300: "#d4d4d4",
                    400: "#a3a3a3",
                    500: "#737373",
                    600: "#525252",
                    700: "#404040",
                    800: "#262626",
                    900: "#171717",
                    950: "#0a0a0a",
                },
                // Surface - Card/container backgrounds
                surface: {
                    50: "#f8fafc",
                    100: "#f1f5f9",
                    200: "#e2e8f0",
                    300: "#cbd5e1",
                    400: "#94a3b8",
                    500: "#64748b",
                    600: "#475569",
                    700: "#334155",
                    800: "#1e293b",
                    900: "#0f172a",
                    950: "#020617",
                },
            },
            boxShadow: {
                // Modern layered shadows with depth
                xs: "0 1px 2px 0 rgb(0 0 0 / 0.03)",
                sm: "0 1px 3px 0 rgb(0 0 0 / 0.04), 0 1px 2px -1px rgb(0 0 0 / 0.04)",
                base: "0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.03)",
                md: "0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.03)",
                lg: "0 20px 25px -5px rgb(0 0 0 / 0.05), 0 8px 10px -6px rgb(0 0 0 / 0.03)",
                xl: "0 25px 50px -12px rgb(0 0 0 / 0.08)",
                "2xl": "0 35px 60px -15px rgb(0 0 0 / 0.1)",
                // Colored glows for interactive elements
                "glow-sm":
                    "0 0 0 1px rgb(14 165 233 / 0.1), 0 0 8px rgb(14 165 233 / 0.15)",
                glow: "0 0 0 1px rgb(14 165 233 / 0.15), 0 0 16px rgb(14 165 233 / 0.2)",
                "glow-lg":
                    "0 0 0 1px rgb(14 165 233 / 0.2), 0 0 32px rgb(14 165 233 / 0.25)",
                "glow-brand":
                    "0 0 0 1px rgb(99 102 241 / 0.15), 0 0 16px rgb(99 102 241 / 0.2)",
                "glow-success":
                    "0 0 0 1px rgb(34 197 94 / 0.15), 0 0 16px rgb(34 197 94 / 0.2)",
                "glow-warning":
                    "0 0 0 1px rgb(245 158 11 / 0.15), 0 0 16px rgb(245 158 11 / 0.2)",
                "glow-danger":
                    "0 0 0 1px rgb(239 68 68 / 0.15), 0 0 16px rgb(239 68 68 / 0.2)",
                // Inner shadows for pressed states
                "inner-sm": "inset 0 1px 2px 0 rgb(0 0 0 / 0.03)",
                inner: "inset 0 2px 4px 0 rgb(0 0 0 / 0.05)",
                "inner-lg": "inset 0 4px 8px 0 rgb(0 0 0 / 0.08)",
            },
            borderRadius: {
                none: "0",
                xs: "0.125rem",
                sm: "0.25rem",
                base: "0.375rem",
                md: "0.5rem",
                lg: "0.75rem",
                xl: "1rem",
                "2xl": "1.25rem",
                "3xl": "1.5rem",
                "4xl": "2rem",
                full: "9999px",
            },
            borderWidth: {
                0: "0",
                px: "1px",
                1: "1px",
                2: "2px",
                3: "3px",
                4: "4px",
            },
            transitionDuration: {
                0: "0ms",
                50: "50ms",
                75: "75ms",
                100: "100ms",
                150: "150ms",
                200: "200ms",
                300: "300ms",
                500: "500ms",
                700: "700ms",
                1000: "1000ms",
            },
            transitionTimingFunction: {
                "ease-in": "cubic-bezier(0.4, 0, 1, 1)",
                "ease-out": "cubic-bezier(0, 0, 0.2, 1)",
                "ease-in-out": "cubic-bezier(0.4, 0, 0.2, 1)",
                spring: "cubic-bezier(0.34, 1.56, 0.64, 1)",
                bounce: "cubic-bezier(0.68, -0.55, 0.265, 1.55)",
            },
            animation: {
                // Entrance animations
                "fade-in": "fadeIn 200ms ease-out",
                "fade-in-slow": "fadeIn 400ms ease-out",
                "fade-out": "fadeOut 200ms ease-in",
                "slide-up": "slideUp 300ms ease-out",
                "slide-down": "slideDown 300ms ease-out",
                "slide-left": "slideLeft 300ms ease-out",
                "slide-right": "slideRight 300ms ease-out",
                "scale-in": "scaleIn 200ms ease-out",
                "scale-in-spring":
                    "scaleIn 300ms cubic-bezier(0.34, 1.56, 0.64, 1)",
                // Micro-interactions
                "bounce-subtle": "bounceSubtle 2s ease-in-out infinite",
                "pulse-soft": "pulseSoft 3s ease-in-out infinite",
                float: "float 6s ease-in-out infinite",
                shimmer: "shimmer 2s ease-in-out infinite",
                "spin-slow": "spin 3s linear infinite",
                // Focus/hover
                "glow-pulse": "glowPulse 2s ease-in-out infinite",
                "ring-pulse": "ringPulse 2s ease-in-out infinite",
            },
            keyframes: {
                fadeIn: {
                    "0%": { opacity: "0" },
                    "100%": { opacity: "1" },
                },
                fadeOut: {
                    "0%": { opacity: "1" },
                    "100%": { opacity: "0" },
                },
                slideUp: {
                    "0%": { opacity: "0", transform: "translateY(16px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                slideDown: {
                    "0%": { opacity: "0", transform: "translateY(-16px)" },
                    "100%": { opacity: "1", transform: "translateY(0)" },
                },
                slideLeft: {
                    "0%": { opacity: "0", transform: "translateX(16px)" },
                    "100%": { opacity: "1", transform: "translateX(0)" },
                },
                slideRight: {
                    "0%": { opacity: "0", transform: "translateX(-16px)" },
                    "100%": { opacity: "1", transform: "translateX(0)" },
                },
                scaleIn: {
                    "0%": { opacity: "0", transform: "scale(0.95)" },
                    "100%": { opacity: "1", transform: "scale(1)" },
                },
                bounceSubtle: {
                    "0%, 100%": { transform: "translateY(0)" },
                    "50%": { transform: "translateY(-4px)" },
                },
                pulseSoft: {
                    "0%, 100%": { opacity: "1" },
                    "50%": { opacity: "0.7" },
                },
                float: {
                    "0%, 100%": { transform: "translateY(0px)" },
                    "50%": { transform: "translateY(-8px)" },
                },
                shimmer: {
                    "0%": { backgroundPosition: "-200% 0" },
                    "100%": { backgroundPosition: "200% 0" },
                },
                glowPulse: {
                    "0%, 100%": { boxShadow: "0 0 0 0 rgb(14 165 233 / 0.4)" },
                    "50%": { boxShadow: "0 0 0 8px rgb(14 165 233 / 0)" },
                },
                ringPulse: {
                    "0%, 100%": { boxShadow: "0 0 0 0 rgb(99 102 241 / 0.4)" },
                    "50%": { boxShadow: "0 0 0 8px rgb(99 102 241 / 0)" },
                },
            },
            backdropBlur: {
                xs: "2px",
                sm: "4px",
                base: "8px",
                md: "12px",
                lg: "16px",
                xl: "24px",
                "2xl": "40px",
                "3xl": "64px",
            },
            backgroundImage: {
                "gradient-radial": "radial-gradient(var(--tw-gradient-stops))",
                "gradient-conic":
                    "conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))",
                "mesh-gradient":
                    "linear-gradient(135deg, var(--tw-gradient-stops))",
                "mesh-gradient-1":
                    "linear-gradient(135deg, #667eea 0%, #764ba2 100%)",
                "mesh-gradient-2":
                    "linear-gradient(135deg, #f093fb 0%, #f5576c 100%)",
                "mesh-gradient-3":
                    "linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)",
                "mesh-gradient-4":
                    "linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)",
                "mesh-gradient-5":
                    "linear-gradient(135deg, #fa709a 0%, #fee140 100%)",
                "mesh-gradient-6":
                    "linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)",
                "mesh-gradient-7":
                    "linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%)",
                "mesh-gradient-8":
                    "linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)",
            },
            backgroundSize: {
                "300%": "300% 300%",
            },
            zIndex: {
                0: "0",
                10: "10",
                20: "20",
                30: "30",
                40: "40",
                50: "50",
                60: "60",
                70: "70",
                80: "80",
                90: "90",
                100: "100",
                dropdown: "1000",
                sticky: "1100",
                fixed: "1200",
                "modal-backdrop": "1300",
                modal: "1400",
                popover: "1500",
                tooltip: "1600",
                toast: "1700",
            },
        },
    },

    plugins: [forms],
};
