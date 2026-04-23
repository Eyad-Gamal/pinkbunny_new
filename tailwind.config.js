import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                serif: ['Nunito', ...defaultTheme.fontFamily.serif], // Fallback to Nunito to wipe out old serif
            },
            colors: {
                bunny: {
                    primary: '#FFB5C5', // Pastel Pink 
                    'primary-light': '#FFE4E1',
                    'primary-dark': '#EFA5B5',
                    secondary: '#BAE6FD', // Sky Blue
                    'secondary-light': '#E0F2FE',
                    bg: '#F8FAFC',
                    surface: '#FFFFFF',
                    text: '#0F172A', // Pitch Black/Dark Blue-Gray for contrast
                    muted: '#64748B',
                    border: '#E2E8F0',
                    accent: '#FFDFE8',
                    'dark-bg': '#0F172A',
                    'dark-surface': '#1E293B',
                    'dark-card': '#334155',
                    'dark-text': '#F8FAFC',
                    'dark-muted': '#94A3B8',
                    'dark-border': '#475569',
                },
            },
            boxShadow: {
                soft: '0 4px 14px -2px rgba(255, 181, 197, 0.4)', // Pinkish soft
                elevated: '0 10px 25px -5px rgba(186, 230, 253, 0.5)', // Blueish elevated
                glow: '0 0 30px rgba(255, 181, 197, 0.6)', 
                glass: '0 8px 32px rgba(15, 23, 42, 0.05)',
            },
            borderRadius: {
                '4xl': '2rem',
                '5xl': '2.5rem',
            },
            keyframes: {
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'bounce-soft': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
            },
            animation: {
                'fade-in-up': 'fade-in-up 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both', // Bouncy easing
                'bounce-soft': 'bounce-soft 3s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
