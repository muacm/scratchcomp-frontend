<html>
<?php include('header.php'); ?>
<?php include('securityHeader.php'); ?>
<?php include('returnLink.php'); ?>
<p>Submission results
<p>
<?php
include('settings.php');

uploadFile();
function uploadFile() {
	$target_dir = getUploadDir();

	$team = $_SESSION["teamNumber"];

	$problem = $_POST["probRadio"];

	$success = true;

	$target_project_file = $target_dir . "/team" . $team . "prob" . $problem . "project" . basename($_FILES["projectFile"]["name"]);

	if (move_uploaded_file($_FILES["projectFile"]["tmp_name"], $target_project_file)) {
		chmod($target_project_file, 0777);
		echo "The project file " . basename($_FILES["projectFile"]["name"]) . " has been uploaded for problem " . $problem . ".<br>";
	} else {
		echo "There was an internal error uploading your project file. Ask a room proctor if this issue continues to occur.<br>";
		$success = false;
	}

	if ((int)$problem >= 4) {
		if ($_POST["designRadio"] == "file") {
			$target_design_file = $target_dir . "/team" . $team . "prob" . $problem . "design" . basename($_FILES["designFile"]["name"]);

			if (move_uploaded_file($_FILES["designFile"]["tmp_name"], $target_design_file)) {
				chmod($target_design_file, 0777);
				echo "The design document " . basename($_FILES["designFile"]["name"]) . " has been uploaded for problem " . $problem . ".<br>";
			} else {
				echo "There was an internal error uploading your design document. Ask a room proctor if this issue continues to occur.<br>";
				$success = false;
			}
		} elseif ($_POST["designRadio"] == "link") {
			$target_design_file = $target_dir . "/team" . $team . "prob" . $problem . "designLink.txt";
			$link = $_POST["designLink"];
			if (! (substr($link, 0, 7) == "http://" or substr($link, 0, 8) == "https://")) {
				$link = "https://" . $link;
			}
			$data = "[" . $link . "](" . $link . ")";
			

			if (file_put_contents($target_design_file, $data)) {
				chmod($target_design_file, 0777);
				echo "Your design link was submitted: " . $link . "<br>";
			} else {
				echo "There was an internal error uploading your design link. Ask a room proctor if this issue continues to occur.<br>";
				$success = false;
			}
		} else {
			echo "No design document was submitted. Ask a room proctor if this issue continues to occur.<br>";	
			$success = false;
		}
	}

	if ($success) {
		echo "Your submission is being processed.<br>";
	}
}

?>
