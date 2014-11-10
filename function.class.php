class lyamFunctions{	
		
		public $sqlResult="";
	
		function __construct(){
			
			$this->connect();
		}
		
		function connect(){
			error_reporting(E_ALL ^ E_NOTICE);
			$con = @mysql_connect("localhost", "root", "")or die("Cannot connect to server".mysql_error());			
			mysql_select_db("lyam", $con) or die("Cannot find the db ".mysql_error());
		}
		
		function newUser($username, $email, $pwd){
			$usernameStatus = $this->checkIFUsernameExist($username);
			$emailStatus = $this->checkIFEmailExist($email);
			
			if($usernameStatus == true){
				return "Username has already been taken";
			}elseif($emailStatus == true){
				return "Email already in use";			
			}else{
				$sqlResult = $this->enterNewUser($username, $email, $pwd);
				
				return $sqlResult;
			}			
		}
		
		function checkIFUsernameExist($username){
			$sql="SELECT * FROM users WHERE username = '$username'";			
			$query = mysql_query($sql)or die(mysql_error());
			if(mysql_num_rows($query) != 0){
				return true;
			}			
		}
		
		function enterNewUser($username, $email, $pwd){
			
			$sql = "INSERT INTO users (username,email,password) VALUES('$username', '$email', '$pwd')";			
			$sqlResult = $this->queryMysql($sql);
			
			if($sqlResult == true){
				return "Registration successful!";
			}else{
				return "Registration failed!";
			}
		}
		
		function checkIFEmailExist($email){
			$sql="SELECT * FROM users WHERE email = '$email'";			
			$query = mysql_query($sql)or die(mysql_error());
			if(mysql_num_rows($query) != 0){
				return true;
			}
			
		}
		
		function login_user($email,$pwd){
			$sql="SELECT * FROM users WHERE email='$email' AND password='$pwd'";
			$query = mysql_query($sql)or die(mysql_error());
			
			$count = mysql_num_rows($query);
			
			if($count == 1){
				
				$row = mysql_fetch_array($query);
				
				session_start();
				
				$_SESSION['user'] = $row['user_id'];
				
				//echo $_session['user'];
				header("Location: userprofile.php");
				
			}
		
		}
		
		function checkInput($data){
			$data = trim($data);//remove white spaces
			$data = stripslashes($data);//remove slashes
			$data = htmlspecialchars($data);//remove any kind of special characters
			return $data;
		}
		
		function queryMysql($sql){
			
			$query = mysql_query($sql)or die(mysql_error());
			
			if($query){
				return true;
			}			
		}
		
		function uploadPlaceDb($userID,$place_name,$place_desc,$location,$place_img){
			
			$sql = "INSERT INTO places (user_id,place_name, place_desc, place_location, place_pic) VALUES('$userID','$place_name','$place_desc','$location','$place_img')";
			return $this->queryMysql($sql);
		}
		
		function visitedMarkedPlaceDb($user,$place_id,$whoToview,$place_img){
			$sql = "INSERT INTO visited (user_id,place_id, whotoview, visit_pic) VALUES('$user','$place_id','$whoToview','$place_img')";
			return $this->queryMysql($sql);
		}
		
		function wishlistPlaceDb($user,$place_id,$wishVisitTime){
			$sql = "INSERT INTO wishlist (place_id, user_id, wishTime,DateAndTime) VALUES('$place_id','$user','$wishVisitTime',Now())";
			return $this->queryMysql($sql);
		}
		
		function displayMyMarkedPlaces($userID){
			
			$sql = "SELECT * FROM places WHERE user_id = '$userID'";
			$query = mysql_query($sql)or die(mysql_error());
			
			while($row = mysql_fetch_array($query)){				
				
					echo "<div class='img_content'>
				<div id='img_holder'><a href='markwishlike.php?pid=".$row['place_id']."'><img src='mark_photos/".$row['place_pic']."'/></a></div>
				<div id='img_label'>".$row['place_name'].", ".$row['place_location']."</div>
				<div id='place_marks'><span id='icon'>100 Marks</span> | <span id='icon'><a href=''>300 Views</a></span> | </div>
				
				<div id='read_more'><a href='".$row['place_id']."'>Place info</a></div>
				</div>";
				}	
			
		}
		
		function displayInterestingSystemPlaces(){
			
			$sql = "SELECT * FROM system_places WHERE system_places_type = 'Normal'";
			$query = mysql_query($sql)or die(mysql_error());
			
			$count = 0;
			$Data = array();
			$level = 0;
			while($row = mysql_fetch_array($query)){				
				
					echo "<div class='img_content'>
				<div id='img_holder'><a href='markwishlike.php?pid=".$row['system_places_id']."'><img src='img/".$row['system_places_pic']."'/></a></div>
				<div id='img_label'>".$row['system_places_name'].", ".$row['system_places_location']."</div>
				<div id='place_marks'><span id='icon'>100 Marks</span> | <span id='icon'><a href=''>2000 Views</a></span> | </div>
				
				<div id='read_more'><a href='".$row['system_places_id']."'>Place info</a></div>
				</div>";
				}	
			
		}
		
		function displayFeaturedInterestingSystemPlaces(){
			$sql = "SELECT * FROM system_places WHERE system_places_type = 'Featured'";
			$query = mysql_query($sql)or die(mysql_error());
			$row = mysql_fetch_array($query);
				echo "<div class='img_content_featured'>
		<span id='featured_label'>FEATURED Place of the day</span>
		<div id='img_holder'><a href=''><img src='img/".$row['system_places_pic']."'/></a></div>
		<div id='img_label'>".$row['system_places_name'].", ".$row['system_places_location']."</div>
		<div id='place_marks'><span id='icon'>101 Marks | <span id='icon'><a href=''>100,000 Views</a></span> | </div>
		
		<div id='read_more'><a href=''>Place info</a></div>
		</div>";
	}
	
	function checkFeaturedPlace(){
			$sql="SELECT * FROM system_places WHERE system_places_type = 'Featured'";			
			$query = mysql_query($sql)or die(mysql_error());
			if(mysql_num_rows($query) != 0){
				return true;
			}
	}
	
	function selectedPlace($placeID){
		$sql = "SELECT * FROM system_places WHERE system_places_id = '$placeID'";
		$query = mysql_query($sql)or die(mysql_error());
		$row = mysql_fetch_array($query);
		
		return $row;
		
	}
	
	function placeSearchDb($item){
		
		$sql = "SELECT * FROM system_places WHERE system_places_name LIKE '%$item%'";
			$query = mysql_query($sql)or die(mysql_error());			
			
			while($row = mysql_fetch_array($query)){
				
				echo "<div id='placeSearch'><div>".$row['system_places_name']."</div>
					<div><img src='img/".$row['system_places_pic']."'/></div>
					<div><a href='markwishlike.php?pid=".$row['system_places_id']."'>Select</a></div>
					</div>";
			}	
	}
	
	function insertNewMarks($userID,$place,$newMarkImg,$country){
		
		$sql="INSERT INTO places(user_id,place_name,place_desc,place_location,place_pic) VALUES('$userID','$place','none','$country','$newMarkImg')";
				
		return $this->queryMysql($sql);
	}
	
	function uploadFile($place, $country){
		
		$file_error = "";
		
		$allowedExts = array("gif", "jpeg", "jpg", "png");
		$temp = explode(".", $_FILES["file"]["name"]);
		$extension = end($temp);

		if ((($_FILES["file"]["type"] == "image/gif")
		|| ($_FILES["file"]["type"] == "image/jpeg")
		|| ($_FILES["file"]["type"] == "image/jpg")
		|| ($_FILES["file"]["type"] == "image/pjpeg")
		|| ($_FILES["file"]["type"] == "image/x-png")
		|| ($_FILES["file"]["type"] == "image/png"))
		&& ($_FILES["file"]["size"] < 20000)
		&& in_array($extension, $allowedExts)) {
		  if ($_FILES["file"]["error"] > 0) {
			$file_error =  "Return Code: " . $_FILES["file"]["error"];
		  } else {
			
			if (file_exists("mark_photos/" . $_FILES["file"]["name"])) {
			  $file_error = $_FILES["file"]["name"] . " already exists. ";
			} else {
				
				$uploadStatus="";
				$userID = "1";
								
				$temp = explode(".",$_FILES["file"]["name"]);
				$newMarkImg = $place.'_'.rand(1,99999) . '.' .end($temp);
				
				$uploadPlaceStatus = $this->insertNewMarks($userID,$place,$newMarkImg,$country);
				
				if($uploadPlaceStatus == true){
				
					move_uploaded_file($_FILES["file"]["tmp_name"],"mark_photos/" . $newMarkImg);
					header("Location: mymarks.php?");
				}else{
					$file_error = "Unable to upload this mark!";
				}
			}
		  }
		} else {
				$file_error = "Invalid file";
		}
		
		return $file_error;
	}	
	
}
	
?>
