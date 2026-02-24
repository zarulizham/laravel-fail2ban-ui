<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Fail2ban UI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #c8b6a6;
            --bs-primary-rgb: 200, 182, 166;

            --bs-success: #b7c4b3;
            --bs-danger: #d6a77a;
            --bs-warning: #d8c3a5;
            --bs-info: #cbd5e1;

            --bs-body-bg: #f7f4ef;
            --bs-body-color: #3e3e3e;
            --bs-border-color: #e5dfd8;
        }

        body {
            background-color: var(--bs-body-bg);
        }

        /* Minimal navbar */
        .navbar {
            background-color: #ffffff !important;
            border-bottom: 1px solid var(--bs-border-color);
        }

        .navbar .nav-link,
        .navbar-brand {
            color: #5a5a5a !important;
        }

        .navbar .nav-link.active {
            font-weight: 500;
        }

        /* Cards softer */
        .card {
            border: 1px solid var(--bs-border-color);
            box-shadow: none;
            border-radius: 0.75rem;
        }

        /* Buttons softer */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-outline-primary {
            color: var(--bs-primary);
            border-color: var(--bs-primary);
        }

        .btn-warning {
            background-color: #e8d8c3;
            border-color: #e8d8c3;
            color: #5c4d3a;
        }

        /* Table minimal */
        .table thead {
            background-color: #f1ece6;
        }

        .table> :not(caption)>*>* {
            border-color: var(--bs-border-color);
        }

        /* Inputs softer */
        .form-control,
        .form-select {
            border-radius: 0.5rem;
            border-color: var(--bs-border-color);
        }

        /* Badge softer */
        .badge {
            font-weight: 500;
        }
    </style>
</head>

<body class="min-vh-100 d-flex flex-column">
    <div id="fail2ban-app"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script type="module">
        import * as Vue from 'https://unpkg.com/vue@3/dist/vue.esm-browser.prod.js';
        import {
            loadModule
        } from 'https://unpkg.com/vue3-sfc-loader/dist/vue3-sfc-loader.esm.js';

        const options = {
            moduleCache: {
                vue: Vue,
            },
            async getFile(url) {
                const response = await fetch(url);

                if (!response.ok) {
                    throw new Error(`${response.status} ${response.statusText}`);
                }

                return await response.text();
            },
            addStyle(textContent) {
                const style = Object.assign(document.createElement('style'), {
                    textContent,
                });

                document.head.appendChild(style);
            },
        };

        const App = await loadModule('/fail2ban-ui/assets/App.vue', options);

        Vue.createApp(App).mount('#fail2ban-app');
    </script>
</body>

</html>
