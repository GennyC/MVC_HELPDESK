<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HELPDESK</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <header>
        <h1>Helpdesk System</h1>
    </header>

    <main>
        <?php
        // Aquí se muestran las páginas dinámicas segun ?axn=
        $mvc = new MvcController();
        $mvc->enlacespaginascontrol();
        ?>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> Helpdesk</p>
    </footer>
</body>
</html>
