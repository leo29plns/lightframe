<?php if (isset($template['LF_PHPTOJS'])) {echo $template['LF_PHPTOJS'];} ?>

    <script src="{: lf/js/phpToJs.js :}"></script>

<?php foreach ($template['LF_JSFILES'] as $jsFile): ?>
    <script src="<?= $_ENV['LF_WEBROOT'] . '/js/' . $jsFile ?>"></script>
<?php endforeach; ?>
    <?php require('html' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'commons' . DIRECTORY_SEPARATOR . 'footer.php') ?>
</body>