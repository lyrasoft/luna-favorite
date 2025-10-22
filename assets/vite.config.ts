import { resolve } from 'node:path';
import dts from 'unplugin-dts/vite';
import { defineConfig } from 'vite';

export default defineConfig(({ mode }) => {

  return {
    base: './',
    resolve: {
      alias: {
        '~favorite': resolve('./src'),
      },
      dedupe: ['vue']
    },
    build: {
      lib: {
        entry: 'src/index.ts',
        name: 'Favorite',
        formats: ['es'],
      },
      rollupOptions: {
        output: {
          format: 'es',
          entryFileNames: '[name].js',
          chunkFileNames: 'chunks/[name].js',
          assetFileNames: (info) => {
            if (info.originalFileNames[0] === 'style.css') {
              return 'favorite.css';
            }

            return 'assets/[name][extname]';
          },
        },
        external: [
          '@windwalker-io/unicorn-next',
        ]
      },
      outDir: 'dist',
      emptyOutDir: true,
      sourcemap: 'external',
      minify: false,
    },
    plugins: [
      dts({
        tsconfigPath: resolve('./tsconfig.json'),
        bundleTypes: true,
      })
    ]
  };
});

