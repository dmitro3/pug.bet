const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
        presets: [
        require('./vendor/ph7jack/wireui/tailwind.config.js')
    ],
      darkMode: false, 
    purge: [
        "./config/livewire-notifier.php",
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/ph7jack/wireui/resources/**/*.blade.php',
        './vendor/ph7jack/wireui/ts/**/*.ts',
        './vendor/ph7jack/wireui/src/View/**/*.php'
    ],
    theme: {
    
    extend: {
    colors: {},
    textColor: {
      primary: 'var(--color-text-primary)',
      primaryfocus: 'var(--color-text-primary-focus)',
      secondary: 'var(--color-text-secondary)',
      default: 'var(--color-text-default)',
      'default-soft': 'var(--color-text-default-soft)',
      inverse: 'var(--color-text-inverse)',
      'inverse-soft': 'var(--color-text-inverse-soft)',
    },
    backgroundColor: {
      primary: 'var(--color-bg-primary)',
      primarysoft: 'var(--color-bg-primary-soft)',
      primarylight: 'var(--color-bg-primary-light)',
      secondary: 'var(--color-bg-secondary)',
      component: 'var(--color-bg-component)',

      default: 'var(--color-bg-default)',
      inverse: 'var(--color-bg-inverse)',
    },
    fontWeights: {
      normal: 'var(--font-weight-normal)',
      display: 'var(--font-weight-display)',
      btn: 'var(--font-weight-btn)',
    },
    borderRadius: {
      none: '0',
      component: 'var(--rounded-component)',
    },
    boxShadow: {
        component:  'var(--box-shadow-component)',
    },

   fontFamily: {
      display: 'var(--font-display)',
      bodysans: 'var(--font-body-sans)',
      body: 'var(--font-body)',
    },

        },
    },
    plugins: [require('@tailwindcss/forms'), 

  require('tailwindcss-animatecss')({
        classes: ['animate__animated', 'pulse'],
        settings: {
          animatedSpeed: 750,
          heartBeatSpeed: 750,
          hingeSpeed: 2000,
          bounceInSpeed: 650,
          bounceOutSpeed: 750,
          animationDelaySpeed: 200
        },
        variants: ['responsive', 'hover', 'reduced-motion'],
      }),



    require('@tailwindcss/typography')],
};


