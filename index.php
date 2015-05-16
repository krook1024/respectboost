<html>
	<head>
		<title>Sampforum Respect Boost</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="style.css" />
		<link rel="stylesheet" href="https://bootswatch.com/darkly/bootstrap.min.css">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
	</head>
	<body>
		<div class="container">
			<span>&nbsp;</span>

			<section id="main">
				<div class="well well-sm main">
				<ul class="nav nav-tabs">
					<li <?php echo ( !isset($_GET['p']) || $_GET['p'] == 'home' ? 'class="active"' : '' ); ?>><a href="?p=home">Tisztelet</a></li>
					<li <?php echo ( isset($_GET['p']) && $_GET['p'] == 'minus' ? 'class="active"' : '' ); ?>><a href="?p=minus">Mínusz</a></li>
				</ul>
				<?php
					if(isset($_GET['p'])) {
						if(ctype_alnum($_GET['p']) && !empty($_GET['p'])) {
							$p = $_GET['p'];
							if(file_exists("pages/".$p.".php")) {
								include("pages/".$p.".php");
							} else {
								include("pages/home.php");
							}
						} else {	
							include("pages/home.php");
						}
					} else {
						include("pages/home.php");
					}
				?>
				</div>
			</section>
		</div>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	</body>
</html>
