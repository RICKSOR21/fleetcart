import { glob } from "glob";
import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";
import copy from "rollup-plugin-copy";
import path from "path";
import autoprefixer from "autoprefixer";
import postcssRTLCSS from "postcss-rtlcss";

// FleetCart version
const VERSION = "4.7.6";

export default defineConfig(async ({ command }) => {
    // Glob pattern for assets
    const assetPatterns = [
        "**/app.scss",
        "**/app.js",
        "**/main.scss",
        "**/main.js",
        "**/create.js",
        "**/edit.js",
        "modules/Storefront/Resources/assets/public/sass/vendors/*.scss",
    ];

    // Fetching the asset files asynchronously
    const assets = await glob(assetPatterns, { ignore: "node_modules/**" });

    return {
        base: "",
        plugins: [
            laravel({
                input: [
                    "modules/Admin/Resources/assets/sass/dashboard.scss",
                    "modules/Admin/Resources/assets/js/dashboard.js",
                    "modules/Order/Resources/assets/admin/sass/print.scss",
                    "modules/Storefront/Resources/assets/public/js/vendors/flatpickr.js",
                    ...assets,
                ],
                refresh: true,
            }),
            vue({
                template: {
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
            copy({
                targets: [
                    {
                        src: [
                            "public/favicon.ico",
                            "node_modules/jquery/dist/jquery.min.js",
                            "node_modules/tinymce",
                            "node_modules/selectize/dist/js/standalone/selectize.min.js",
                            "node_modules/jstree/dist/jstree.min.js",
                            "modules/Admin/Resources/assets/images/*",
                            "modules/Admin/Resources/assets/vendors/js/bootstrap.min.js",
                            "modules/Storefront/Resources/assets/public/images/*",
                        ],
                        dest: "public/build/assets",
                    },
                    {
                        src: "modules/Admin/Resources/assets/fonts",
                        dest: "public/build",
                    },
                    {
                        src: "node_modules/line-awesome/dist/line-awesome/fonts",
                        dest: "modules/Storefront/Resources/assets/public",
                    },
                ],
                copyOnce: true,
                hook: command === "build" ? "writeBundle" : "buildStart",
            }),
        ],
        css: {
            devSourcemap: false,
            postcss: {
                plugins: [
                    autoprefixer(),
                    postcssRTLCSS({
                        ltrPrefix: ".ltr",
                        rtlPrefix: ".rtl",
                        processKeyFrames: true,
                    }),
                ],
            },
        },
        resolve: {
            alias: {
                vue: path.resolve(
                    __dirname,
                    "./node_modules/vue/dist/vue.esm-bundler.js"
                ),
                "@modules": path.resolve(__dirname, "./modules"),
                "@admin": path.resolve(
                    __dirname,
                    "./modules/Admin/Resources/assets"
                ),
            },
        },
        build: {
            sourcemap: false,
            rollupOptions: {
                output: {
                    manualChunks(id) {
                        if (id.includes("node_modules")) {
                            return id.split("node_modules/")[1].split("/")[0];
                        }
                    },
                    entryFileNames: `assets/[name]-[hash]-v${VERSION}.js`,
                    chunkFileNames: `assets/[name]-[hash]-v${VERSION}.js`,
                    assetFileNames: `assets/[name]-[hash]-v${VERSION}.[ext]`,
                },
            },
        },
        esbuild: { legalComments: "none" },
    };
});
