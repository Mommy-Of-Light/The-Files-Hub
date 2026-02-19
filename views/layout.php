<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        *::-webkit-scrollbar {
            width: 8px;
        }

        *::-webkit-scrollbar-track {
            background: #2c2f33;
        }

        *::-webkit-scrollbar-thumb {
            background-color: #7289da;
            border-radius: 4px;
        }

        .progress-bar-text {
            font-weight: bold;
        }

        * {
            font-family: Consolas;
        }

        @media (max-width: 768px) {
            table thead {
                display: none;
            }

            table,
            table tbody,
            table tr,
            table td {
                display: block;
                width: 100%;
            }

            table tr {
                margin-bottom: 15px;
                background: #212529;
                padding: 10px;
                border-radius: 8px;
            }

            table td {
                text-align: center;
                position: relative;
                padding-left: 50%;
                justify-content: center;
            }

            table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                text-align: left;
                font-weight: bold;
            }
        }
    </style>
</head>

<body class="bg-dark text-light">
    <?php if ($withMenu) {
        echo $this->fetch('menu.php');
    } ?>
    <?= $content ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>