<?php
	include('inc/class.smf.php');
?>
<h2>Tisztelet</h2>
<form method="post">
	<div class="input-group">
		<input type="text" name="name" class="form-control" placeholder="Felhasználónév">
	</div>
	
	<div class="input-group">
		<input type="password" name="pass" class="form-control" placeholder="Jelszó">
	</div>
	
	<div class="input-group">
		<input type="text" name="uid" class="form-control" placeholder="UserID">
	</div>
	
	<input type="submit" name="subm" id="boost" class="btn btn-md btn-warning btn-block" value="Boost!"></input>
	
	<br>
	
	<div class="alert alert-info" id="loading" style="display: none;">
		<i class="fa fa-cog fa-spin"></i>&nbsp;<strong>Töltés..</strong>
	</div>
</form>


<script>
	$("#boost").click(function(){
		$("#loading").show();
	});
</script>
<?php
	if(isset($_POST['subm'])) {
		$rnd = mt_rand(100, 999);
		$smf = new SMF('http://sampforum.hu/', "$rnd_cookies.txt");
		
		if($smf -> SMF_login($_POST['name'], $_POST['pass'])) {
			for($i = 1; $i < 40; $i ++) {
				$smf -> SMF_hitposts($_POST['uid'], $i*10, 0);
			}
			echo '
				<script type="text/javascript">
					$("#loading").hide();
				</script>
			';
			
			echo '<div class="alert alert-success">Kész.</div>';
		} else {
			echo '
				<div class="alert alert-danger" role="alert">Nem sikerült bejelentkezni.</div>
			';
		}
	}
?>
