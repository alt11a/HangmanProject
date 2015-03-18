<?php 
session_start();

if ($_SESSION['usermail'] == '')
{
	header("location:bookstorelogin.php");
}
$_SESSION['title'] = '';
$usermail = $_SESSION['usermail'];
$con = mysql_connect('localhost','root','littlepizza');
if (!$con)
{
	die("Could not connect to database".mysql_error());
}
mysql_select_db('bookstore')or die('Cannot select database bookstore');
//$usermail = htmlentities($usermail);
//echo "Your username is ".$_SESSION['usermail']."<br>";
?>
<html>
	
	<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
	
	<title>Main Page: Book Master</title>
	
	<!-- <link href="css/style.css" rel="stylesheet" /> -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
	
	<!-- Latest compiled and minified JavaScript -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	
	<!-- external css-->
	<link href="css/bookstore.css" rel="stylesheet" />
	<script src="js/bookstore.js"></script>
    <link href="css/shop-item.css" rel="stylesheet">
	<link href="css/simple-sidebar.css" rel="stylesheet">
	
	<!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
	
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<script src="//code.jquery.com/jquery-1.8.3.min.js"></script>
	<script>
		$(document).ready(function() {
		$tmp = $("#tmp").get(0);
		
		$("#sortable").sortable({
			start: function(event, ui) {
			},
			stop: function(event, ui) { 
				console.log("isNew : ", jQuery.data($tmp, "isNew"));
				console.log("resultHTML : ", jQuery.data($tmp, "resultHTML"));
			}
		});

		$("#draggable li").draggable({
			connectToSortable: "#sortable",
			start: function(event, ui) {    

				//Store info in a tmp div         
				jQuery.data($tmp, "isNew", true);
				jQuery.data($tmp, "resultHTML", "<b>Here I will add some custom html to EVENT data</b>");
				
			},
			helper: function(event) {
				return "<div class='custom-helper'>Custom helper for " + $(this).context.innerHTML + "</div>";   
			},
			revert: "invalid"
		});
	});
	</script>
	<style>
		#div1 {
			width:300px;
			height:290px;
			padding:10px;
			border:0px solid #aaaaaa;
			text-align: middle;
		}

		#sortable {
			margin-top: 16px;
			min-height: 256px;
		}
	</style>

	</head>
	<body>
	<!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">Bookmaster</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="#">Home</a>
                    </li>
                    <li>
                        <a href="#menu-toggle" class="btn btn-default" id="menu-toggle1">Wishlist</a>
                    </li>
                    <li>
                        <a href="#menu-toggle1" class="btn btn-default" id="menu-toggle">Shopping Cart</a>
                    </li>
					<li>
						<a href="bookstorelogout.php">Log Out</a>
					</li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
	<div id="wrapper" class='toggled'>
	<div id="wrapper1" class=''>
	<!-- Sidebar -->
        <div id="sidebar-wrapper" > 
            <ul class="sidebar-nav" ondrop="drop(event)" ondragover="allowDrop(event)">
				<ul id="sortable" class="ui-state-highlight" >
				<li droppable='false'><h3>Shopping Cart</h3></li>
				<?php
				if(isset($_SESSION['cart']))
				{
					foreach($_SESSION['cart'] as $value) {
						echo "<li droppable='false'><img src=img/".$value." width='150' height='200'></li>";
					}
				}
				?>
				</ul>
				<div id="tmp"></div>
            </ul>
        </div>
		<div id="sidebar-wrapper1">
		<li><h3>Wishlist</h3></li>
            <ul class="sidebar-nav1">
				<?php
				$wish = "select isbn, title from books, wishlist where books.isbn=wishlist.booknumber and usermail='$usermail';";
				$list = mysql_query($wish)or die('No: '.mysql_error());
				while ($eachbook = mysql_fetch_assoc($list))
				{
					echo "-><label>".$eachbook['title']."</label><br>";
				}
				?>
				<div id='wishlistbar'></div>
            </ul>
        </div>
	<div id="page-content-wrapper">
	<div id="page-content-wrapper1">	
		<div class='jumbotron'>
			<div class='container'>
				<div align='center'>
				<?php
				$getbooks = "Select isbn, title, image, price, category from books order by dateadded";
				$result = mysql_query($getbooks)or die("Error querying database: ".mysql_error());
				$incre = 1;
				while ($books = mysql_fetch_assoc($result))
				{
					$isbn = $books['isbn'];
					$title = $books['title'];
					echo "<div class='panel panel-default' align='center'>";
					echo "<form action='viewbook.php' method='POST' id = 'myForm".$incre."' name = 'myForm".$incre."'>";
					echo "<input type = 'hidden' value = '".$books['title']."' name = 'title'></form> ";
					echo "<p class='lead'><a href='javascript: getTitle(".$incre.")'>".$books['title']."</a></p>";
					echo "<p>Category: ".trim($books['category'])."</p>";
					echo "<div id= '".$books['image']."' name='$title' draggable='true' droppable='true' ondragstart='drag(event);'><img src='img/".$books['image']."' width='150' height='200' alt='a book'></div>";
					echo "<p>Price: $".$books['price']."</p>";
					echo "<button class='btn btn-info' onclick='addto(\"$isbn\",\"$usermail\",\"$title\")'>Add to Wishlist</button><br><br>";
					echo "</div>";
					//echo "<script>$('#edit_errors').html('<h3><em><font color=\"red\">Please Correct Errors Before Proceeding</font></em></h3>')</script>";
				$incre++;
				}
				?>
				</div>
				<?php
				mysql_close($con);
				?>
			</div>
		</div>
			<footer>
		<div class="container">
        <hr>
            <div class="row">
                <div class="col-lg-12">
                    <p>Copyright &copy; Book Master 2015</p>
                </div>
            </div>
		</div>
	
		<!-- jQuery -->
		<script src="js/jquery.js"></script>

		<!-- Bootstrap Core JavaScript -->
		<script src="js/bootstrap.min.js"></script>

		<!-- Menu Toggle Script -->
		<script>
		$("#menu-toggle").click(function(e) {
			e.preventDefault();
			$("#wrapper").toggleClass("toggled");
		});
		$("#menu-toggle1").click(function(e) {
			e.preventDefault();
			$("#wrapper1").toggleClass("toggled");
		});
		</script>
		
	</footer>
		</div>
		</div>
		</div>
		</div>
	</body>
</html>
