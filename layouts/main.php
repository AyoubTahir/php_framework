<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @styles
    <title>{{title}}</title>
</head>
<body><?php __('title') ?><?php __('dar') ?>
    <?php require_once(layouts_parts('navbar')) ?>
    <div class="container">
       @content
    </div>
        @scripts
</body>
</html>