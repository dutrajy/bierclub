<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, shrink-to-fit=no">

    <title><?= $title ?? "Commercial Base Project" ?></title>
    <meta name="description" content="<?= $description ?? "Commercial Base Project" ?>">

    <link rel="stylesheet" href="/styles/cropper.min.css">
    <link rel="stylesheet" href="/styles/fontawesome.min.css">
    <link rel="stylesheet" href="/styles/bootstrap.min.css">
    <link rel="stylesheet" href="/styles/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="/styles/main.css">

    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="icon" type="image/png" href="/images/favicon.png">
</head>
<body>

    <?php $component("Navbar") ?>

    <main class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-md-2 border-right border-secondary py-2 bg-light">
                <?php $component("Sidebar") ?>
            </div>
            <div class="col-md-10 py-4">
                <?php $block("content") ?>
                    <h1>Default</h1>
                <?php $block("content") ?>
            </div>
        </div>
    </main>

    <script>
        // System wide storage
        window.COMMERCIAL = {};
    </script>
    <script src="/scripts/jquery.min.js"></script>
    <script src="/scripts/cropper.min.js"></script>
    <script src="/scripts/jquery-cropper.min.js"></script>
    <script src="/scripts/popper.min.js"></script>
    <script src="/scripts/bootstrap.min.js"></script>
    <script src="/scripts/jquery.mask.min.js"></script>
    <script src="/scripts/cep-promise.min.js"></script>
    <script src="/scripts/bootstrap-datepicker.min.js"></script>
    <script src="/scripts/bootstrap-datepicker.pt-BR..min.js"></script>

</body>
</html>
