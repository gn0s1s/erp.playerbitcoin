Welcome a <?php echo $site_name; ?>,

Gracias por afiliarte a <?php echo $site_name; ?>.

A continuación te mostramos los detalles of tu cuenta & the link para que inicies sesión

<?php echo site_url('/auth/login/'); ?>

<?php if (strlen($username) > 0) { ?>

Tu nombre of usuario: <?php echo $username; ?>
<?php } ?>

Tu correo: <?php echo $email; ?>

<?php /* Your password: <?php echo $password; ?>

*/ ?>

Divertete
El equipo of <?php echo $site_name; ?>
