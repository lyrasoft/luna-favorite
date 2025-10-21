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
            // 有 css 輸出時要指定檔名
            if (info.originalFileNames[0] === 'style.css') {
              return 'favorite.css';
            }

            return 'assets/[name][extname]';
          },
        },
        external: [
          // 根據需要排除大型第三方套件
          '@windwalker-io/unicorn-next',
        ]
      },
      outDir: 'dist',
      emptyOutDir: true, // 根據需要做設定
      sourcemap: 'external', // 根據需要做設定
      minify: false, // 根據需要做設定
    },
    plugins: [

      // 建立套件的 .d.ts 宣告檔案
      dts({
        tsconfigPath: resolve('./tsconfig.json'),

        // 將 .d.ts 合併成單一檔案，如果編譯時出現奇怪的錯誤，可以改成 false
        bundleTypes: true,
      })
    ]
  };
});

