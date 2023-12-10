<!DOCTYPE html>
<html lang="<?= $_SESSION['LF_LOCALE'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $template['LF_TITLE'] ?? '{{ title }}' ?> - <?= SITE_NAME ?></title>
</head>
<body>
    <h1><?= $template['LF_ERRCODE'] ?></h1>
    <p>{{ <?= $template['LF_ERRMESSAGE'] ?> }}</p>
    <a href="{: / :}">{{ return_home }}</a>
</body>
</html>