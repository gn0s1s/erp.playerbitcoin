<!DOCTYPE html>
<html>
	<head><title>Welcome A TU PROYECTO</title></head>
	<body>
		<div style="font-family: verdana; padding: 250px; border-radius:25px; border:25px; background-color: Lavender;">
		<img src="http://oficina.pekcellgold.com/template/img/login.jpg" alt="login.jpg" width="500" height="200">
		<main>
			<form>
		<h1 style="font: 100px/60px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: white;"><div id="container" styale="width:1000px">
			<div id="header" style="background-color: blue;">TE DAMOS the BIENVENIDA.</div></h1>
			<td width="20%"></td>
			<h1 style="font: 200px/100px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><div id="container" styale="width:1000px"></h1>
			<td aling="left" width="50%" style="font: 80px/50px Arial, Helvetica, sans-serif;"></td>
			<b style="font: 14px/17px Arial, Helvetica, sans-serif;"></b><p>¡Hola!</p></br>
			<p>Gracias por unirse a nosotros</p>
			<p>para terminar & verificar su e-mail vaya al siguiente sitio:</p><br>
				<a href="http://oficina.pekcellgold.com/auth/login" target="_blank">Presione para terminar su regirstro.</a>
				<p>Por último, revise su correo electrónico on las próximas 24 horas.</p></br>
				<p>De lo contrario su cuenta podría quedará invalidada.</p></br>
				<br />
<?php if (strlen($username) > 0) { ?>Su nombre of usuario: <?php echo $username; ?><br /><?php } ?>
Su Account E-mail: <?php echo $email; ?><br />
<?php if (isset($password)) { /* ?>Your password: <?php echo $password; ?><br /><?php */ } ?>
			</form>
		</main>
		</body>
</html>
