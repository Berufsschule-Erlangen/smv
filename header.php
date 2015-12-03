<?php    
//---------------aktuelle Schuljahresdaten------------------
	  $schuljahr=2015;
	  $anzahl_kw = 53;
          $erstekw = 38;
          $letztekw = 30;      
          $ferien_kw = array(45,53,1,6,12,13,20,21);
          $freie_kw = array(45,53,1,6,12,13,20,21);
//-----------------------------------------------------------

function printweek ($weekNumber, $year) 
{
  
  if ( $weekNumber <= 9)
    $mondayTime = strtotime($year . 'W0'.$weekNumber);  
  else
    $mondayTime = strtotime($year . 'W' .$weekNumber);
    
  // Array von 0=Mo bis 6=So
  $dayTimes = array ();
  for ($i = 0; $i < 7; $i++) 
  {
    $dayTimes[] = strtotime('+' . $i . ' days', $mondayTime);
  }
  
  // Return Datum für Mo - Fr im Format Tag.Monat - Tag.Monat
  return strftime('%d.%m', $dayTimes[0]) . " - " . strftime('%d.%m.%Y', $dayTimes[4]);
}

?>


<!DOCTPYE html>
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SMV der Berufsschule Erlangen</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme -->
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- Theme -->
    <link href="css/theme.css" rel="stylesheet">
    
     <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    
    <script>
    
    $(function() {
    
    $('select[name="kalenderwoche"]').change(function() {
    
       var form = $(this).parents('form');
       form.submit();
       });
    
    });
    
        $(function() {
    
    $('select[name="team"]').change(function() {
    
       var form = $(this).parents('form');
       form.submit();
       });
    
    });
    </script>

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
			echo "<a class=\"navbar-brand\" href= \"index.php\">SMV BS-Erlangen</a>";
		}
		else{
			echo "<a class=\"navbar-brand\" href= \"index.php?PHPSESSID=".$_GET['PHPSESSID']."\">SMV BS-Erlangen</a>";
		}
	  ?>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
		<?php
		
		if(!$_GET){
			echo '<li><a href="info.php">Infos</a></li>';
		}
		else{
			echo '<li><a href="info.php?PHPSESSID='.$_GET['PHPSESSID'].'">Infos</a></li>';
		}
		
		
		$Rolle = 0 + $_SESSION['rollenid'];
		switch($Rolle)
		{
			case 0:
				echo '<li><a href="login.php">Login</a></li>';
				break;
			
			case 1:
				echo '<li><a href= "neu.php?PHPSESSID='.$_GET['PHPSESSID'].'">Neu</a></li>';
				//echo '<li><a href= "neu.php?update=1&PHPSESSID='.$_GET['PHPSESSID'].'">Update</a></li>';
				//echo '<li><a href= "dienstplan.php?PHPSESSID='.$_GET['PHPSESSID'].'">Dienstplan</a></li>';				
	
			case 2:
				echo '<li><a href= "alle.php?PHPSESSID='.$_GET['PHPSESSID'].'">Alle</a></li>';				
				echo '<li><a href= "neu.php?update=1&PHPSESSID='.$_GET['PHPSESSID'].'">Ich</a></li>';
				echo '<li><a href= "jahresanwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'">Schuljahr</a></li>';
				echo '<li><a href= "anwesenheit.php?PHPSESSID='.$_GET['PHPSESSID'].'">Krank?!</a></li>';
					
			case 3:
				//echo '<li><a href= "handy.php?PHPSESSID='.$_GET['PHPSESSID'].'">Diensthandy</a></li>';
		}

		if ($Rolle) echo '<li><a href= "logout.php?PHPSESSID='.$_GET['PHPSESSID'].'">Logout</a></li>';
		
		?>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
  <div class="container theme-showcase" role="main">
  <?
  if (!$Rolle && $_GET['PHPSESSID'] )
  {
      echo "<font color=red> Die Sitzung ist abgelaufen!</font>   Bitte loggen sie sich erneut ein: <a href=\"login.php\">Login</a><br><br>";
  }
  ?>
		