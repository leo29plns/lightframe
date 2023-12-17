<?php
    $news = new \Components\News();

    $this->asyncHtml->register($news);

    $this->addCss('fichier1.css');
    $this->addCss('fichier2.css');
    $this->addJs('fichier1.js');
?>
<h1>{{ first_h1 }}</h1><p>{{ p2 }}</p>

<?= $news->render() ?>