<!DOCTPYE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sanitäter Dienstplan</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- Theme -->
    <link href="css/theme.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
		  <?php
			if(!$_GET){
				echo "<a class=\"navbar-brand\" href= \"index.php\">Online Sanitäterplan</a>";
			}
			else{
				echo "<a class=\"navbar-brand\" href= \"index.php?PHPSESSID=".$_GET['PHPSESSID']."\">Online Sanitäterplan</a>";
			}

		  ?>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
			<?php
				if(!$_GET){
					echo "<li><a href=\"login.php\">Login</a></li>";
				}
				else {

					$Rolle = $_SESSION['rollenid'];

					switch($Rolle){
						case 1:
							echo '<li><a href= "anwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'">Anwesenheit</a></li>';
							break;
						case 2:
							echo '<li><a href= "dienstplan.php?PHPSESSID='.$_GET['PHPSESSID'].'">Dienstplan bearbeiten</a></li>';
							echo '<li><a href= "saniAnlegen.php?PHPSESSID='.$_GET['PHPSESSID'].'">Sanitäter hinzufügen</a></li>';
							break;
						case 3:
							echo '<li><a href= "handy.php?PHPSESSID='.$_GET['PHPSESSID'].'">Handy</a></li>';
					}
					echo '<li><a href= "profilBearbeiten.php?PHPSESSID='.$_GET['PHPSESSID'].'">Persönliche Daten ändern</a></li>';
					echo '<li><a href= "logout.php?PHPSESSID='.$_GET['PHPSESSID'].'">Logout</a></li>';

				}

			?>

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
  <div class="container theme-showcase" role="main">
