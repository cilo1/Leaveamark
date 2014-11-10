<?php
	include("function.class.php");
	$lyamFunc = new lyamFunctions();
	
//define variables and set value to empty
	$file_status=$place=$country="";
	$file_error=$place_error=$country_error="";
	
	if($_SERVER["REQUEST_METHOD"] == "POST"){	
				
		if(empty($_POST["place"])){
			$place_error = "Enter place name!";
		}elseif(!preg_match("/^[a-zA-Z ]*$/",$_POST["place"])){
			$place_error = "Only letters and white space allowed";
		}else{
			$place = $lyamFunc->checkInput($_POST["place"]);
		}
		
		if($_POST["location"] == "Select country"){
			$country_error = "Select country!";
		}else{
			$country = $lyamFunc->checkInput($_POST["country"]);
		}
		
		if(!empty($place) && !empty($country)){
		
			$file_status = $lyamFunc->uploadFile($place,$country);
			
			if(!empty($file_status)){
				$file_error = $file_status;
			} 
		}
	}
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Leave Your Mark</title>
	
	<link rel="stylesheet" href="css/style.css"/>	
</head>
<body>
<div id="wrapper">
<header>
<div id="top_menu">
<div id="logo"><img src="img/leaveamarklogo.png" /></div> 
<div id="link_menu">
<ul>
<li><a href="index.php">Home</a></li>
<li><a href="placesearch.php">Place Search</a></li>
<li><a href="mymarks.php">Your Marks</a></li>
<li><a href="">People Search</a></li>
</ul>
</div>
<div id="upload">
	<a href="" ><img src="icons/wishlist.png" title="My wishlist" alt="wishlist"/>Wishlist: 100</a>
</div>

<div id="upload">
	<a href="" ><img src="icons/mark.png" title="My wishlist" alt="wishlist"/>Place Marks: 100</a>
</div>

<div id="mark_user">
<img src="icons/user.png"/> Cecil
</div>
</div>
</header>

<div class="main_content">
	<div class="page_info"><h3>Places to visit and leave marks </h3></div>
	
	<?php
		if($lyamFunc->checkFeaturedPlace() == true){
			$lyamFunc->displayFeaturedInterestingSystemPlaces();
		}
		$lyamFunc->displayInterestingSystemPlaces();
	?>
</div>

<div class="aside_content">
	<div id="friends">		
		<ul>
			<li><a href=""><img src="icons/magnifier12.png" id="icons" /> Find friends</a></li>
			<li><a href=""><img src="icons/email20.png" id="icons" /> Invite friends</a></li>
		</ul>
	</div>
	<div id="uploadPlaces">
		<p>Upload photos of places you have visited or wish to visit and share :-)</p>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
			<span><?php echo $file_error;?></span>
			<div><input name="file" type="file" accept="image"/></div>
			<span><?php echo $place_error;?></span>
			<div><input name="place" type="text" placeholder="Place name"/></div>
			<span><?php echo $country_error;?></span>
			<div><select name="country">
				<option>Select country</option>
				<option>Kenya</option>
				<option>South Africa</option>
			<select></div>
			<div><input name="submit" type="submit" value="Upload" id="submit_btn"/></div>
		</form>
	</div>
</div>

</div>
<footer></footer>
</body>
</html>
