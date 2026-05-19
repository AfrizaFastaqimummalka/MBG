/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
            },
            colors: {
                ink: {
                    DEFAULT: '#0D1F3C',
                    2: '#2D4B78',
                    3: '#6B8DB8',
                },
                brand: {
                    DEFAULT: '#2563EB',
                    mid: '#1D4ED8',
                    dark: '#1E40AF',
                    light: '#DBEAFE',
                },
            },
            borderRadius: {
                '2xl': '16px',
                '3xl': '20px',
            },
            animation: {
                'fade-up': 'fadeUp .32s cubic-bezier(.16,1,.3,1) both',
                'slide-up': 'slideUp .36s cubic-bezier(.16,1,.3,1) both',
                'fade-in': 'fadeIn .20s ease both',
            },
            keyframes: {
                fadeUp: {
                    from: { opacity: '0', transform: 'translateY(12px)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                slideUp: {
                    from: { opacity: '0', transform: 'translateY(100%)' },
                    to: { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    from: { opacity: '0' },
                    to: { opacity: '1' },
                },
            },
        },
    },
    plugins: [],
}
