import vuePlugin from '@vitejs/plugin-vue';
import { resolve } from 'node:path';
import dts from 'unplugin-dts/vite';
import { defineConfig } from 'vite';

export default defineConfig(() => {
  return {
    base: './',
    resolve: {
      alias: {
        '~shopgo': resolve('./src'),
      },
    },
    build: {
      lib: {
        entry: 'src/index.ts',
        name: 'ShopGo',
        formats: ['es'],
      },
      rollupOptions: {
        output: {
          format: 'es',
          entryFileNames: '[name].js',
          chunkFileNames: 'chunks/[name].js',
          assetFileNames: (info) => {
            // if (info.originalFileNames[0] === 'style.css') {
            //   return 'shopgo.css';
            // }

            return 'assets/[name][extname]';
          },
        },
        external: [
          '@windwalker-io/unicorn-next',
          '@lyrasoft/ts-toolkit',
          /^@lyrasoft\/ts-toolkit/,
          '@unicorn/*',
          'bootstrap',
          'sortablejs',
          '@asika32764/vue-animate',
          'bootstrap',
          'vue',
          'vue-draggable-plus',
          'vue-multi-uploader'
        ]
      },
      outDir: 'dist',
      emptyOutDir: true,
      sourcemap: true,
      minify: true,
    },
    plugins: [
      vuePlugin({
        features: {
          prodDevtools: true,
        },
        template: {
          compilerOptions: {
            // preserveWhitespace: false,
            whitespace: 'preserve',
          }
        }
      }),

      dts({
        tsconfigPath: resolve('./tsconfig.json'),
        bundleTypes: true,
      })
    ]
  };
});



