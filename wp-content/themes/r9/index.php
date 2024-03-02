<!doctype html>
<html <?php language_attributes();
      echo is_rtl() ? ' dir="rtl"' : ' dir="ltr"'; ?>>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
  <link rel="shortcut icon" type="image/png" href="<?php echo get_theme_mod('favicon') ?>" />
  <?php wp_head(); ?>
  <style>
    body.admin-bar {
      /*margin-top: 32px;*/
    }

    body.admin-bar .app-header {
      top: 32px !important;
    }
  </style>
</head>

<body <?php body_class(); ?>>
  <?php wp_body_open(); ?>
  <?php do_action('get_header'); ?>
  <?php echo view(app('sage.view'), app('sage.data'))->render(); ?>
  <?php do_action('get_footer'); ?>
  <?php wp_footer(); ?>
</body>

</html>