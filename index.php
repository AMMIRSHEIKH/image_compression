<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gallery</title>
	<style type="text/css">
		p{
			text-align: center;
			background: linear-gradient(90deg,rgb(255,0,0),rgb(0,255,0),rgb(0,0,255));
		}
		nav{
			border: 1px solid black;
			padding: 5px;
			text-align: center;
		}
		nav a{
			text-decoration: none;
			color: rgb(0, 127, 0);
			margin: 5px;
			font-family: 'Arial Black' sans-serif;
			font-size: 20px;
			font-weight: 800;
			padding: 0 20px;
		}
		nav a:active{
			color: green;
		}
		nav a:hover{
			color: blue;
		}
		.container {
		  display: grid;
		  grid-template-columns: 1fr 1fr 1fr; /* Three equal columns */
		  grid-gap: 10px; /* Gap between grid items */
		}

		.container div {
		  background-color: #f2f2f2;
		  padding: 5px;
		}
		.head{
			color: rgb(127, 0, 127);
			margin: 5px;
			text-align: center;
			font-family: 'Arial Black' sans-serif;
			font-size: 40px;
			font-weight: 800;
			padding: 0 20px;
			border-bottom: 5px solid black;
		}
		.note{
			color: rgb(255, 0, 0);
			font-size: 0.7em;
			font-weight: 700;
		}
		.module{
			margin: 0 auto;
			width: 400px;
			color: rgb(0, 255, 0);
		}
	</style>
</head>
<body>
	<header>
		<p>Photo Gallery Project</p>
	</header>
	<main>
		<section>
			<nav>
				<a href="/gallery/index.php">Homepage</a>
				<a href="/gallery/index.php?ImageAction=UploadANDResize">Upload Image</a>
				<a href="/gallery/index.php?ImageAction=UploadANDResizeAPI">API</a>
			</nav>
		</section>
		<section>
			<?php
			if(isset($_GET['ImageAction'])){
				if(isset($_POST['UploadImage'])){
					// Array
					// (
					//     [name] => 12TH_SHAZIA.jpeg
					//     [full_path] => 12TH_SHAZIA.jpeg
					//     [type] => image/jpeg
					//     [tmp_name] => D:\xampp\tmp\php7C22.tmp
					//     [error] => 0
					//     [size] => 200618
					// )
					if($_FILES['image']['error'] === 0){
						if($_FILES['image']['type'] === "image/jpeg"){
							$sourceImage = imagecreatefromjpeg($_FILES['image']['tmp_name']);
						}elseif($_FILES['image']['type'] === "image/png"){
							$sourceImage = imagecreatefrompng($_FILES['image']['tmp_name']);
						}else{
							echo "<h2 class='head' style='color:red;'>ERROR: Invalid File Format!</h2>";
						}
						if(isset($sourceImage)){
							list($width,$height) = getimagesize($_FILES['image']['tmp_name']);
							if($width >= 300 && $height >= 300){
								$nwidth = 300;
								$nheight = 300;
								$newImage = imagecreatetruecolor($nwidth,$nheight);
								imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $nwidth, $nheight, $width, $height);
								$uploadedFile = time().".jpg";
								imagejpeg($newImage,"images/".$uploadedFile);
							}else{
								echo "<h2 class='head' style='color:red;'>ERROR: Please Upload Good Resolution Photo!</h2>";
							}
						}
					}else{
						echo "<h2 class='head' style='color:red;'>ERROR: Something went wrong!</h2>";
					}
				}else{
					?>
					<div class="module">
						<h1 class="head">UPLOAD IMAGE UTILITY</h1>
						<form action="#" method="POST" enctype="multipart/form-data">
							Select Image(<span class="note">Note: Only JPG / PNG Images</span>):
							<input type="file" name="image" /> 
							<input type="submit" name="UploadImage" value="Upload Image" />
						</form>
					</div>
					<?php
				}
			}else{
				?>
				<div class="container">
					<?php
					$dir = "images/";
					$images = glob($dir."*.jpg");
					if(count($images) > 0){
						foreach ($images as $img) {
							echo "<div>";
							echo "<img src='".$img."' />";
							echo "</div>";
						}
					}else{
						$fileName = "images/no.png";
						if(file_exists($fileName)){
							echo "<div>";
							echo "<img src='".$fileName."' />";
							echo "</div>";
						}else{
							$width = 300;
							$height = 300;
							$image = imagecreatetruecolor($width, $height);
							$backgroundColor = imagecolorallocate($image, 255, 0, 0);
							$textColor = imagecolorallocate($image, 0, 0, 255);
							imagefill($image, 0, 0, $backgroundColor);
							$font = 5;
							$fontSize = 24;
							$text = 'No Images Found';
							$textWidth = imagefontwidth($font) * strlen($text);
							$textHeight = imagefontheight($font);
							$textX = ($width - $textWidth) / 2;
							$textY = ($height - $textHeight) / 2;
							imagestring($image, $font, $textX, $textY, $text, $textColor);
							imagepng($image,$fileName);
							echo "<div>";
							echo "<img src='".$fileName."' />";
							echo "</div>";
						}
					}
					?>
				</div>
				<?php
			}
			?>
		</section>
	</main>
</body>
</html>