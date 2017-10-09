<!DOCTYPE html>
<html>
<head>
	<title>Account Details</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php

	require 'database.php';
	require 'utils.php';

	//if called by POST request...
	if (!empty($_POST)) {
		$firstNameError = null;
		$lastNameError = null;
		$usernameError = null;
		$pwordError = null;

		//track post values
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$username = $_POST['username'];
		$pword = $_POST['pword'];
		$cupdate = $_POST['update'];

		//validate and sanitize input
		$valid = true;
		if (empty($firstname)) {
			$firstNameError = 'Please enter first name';
			$valid = false;
		}
		else {
			$firstname = filterinput($firstname);
		}
		if (empty($lastname)) {
			$lastNameError = 'Please enter last name';
			$valid = false;
		}
		else {
			$lastname = filterinput($lastname);
		}
		if (empty($username)) {
			$usernameError = 'Please enter username';
			$valid = false;
		}
		else {
			$username = filterinput($username);
		}
		//if password is empty and this is not an update request...
		if (empty($pword) && empty($cupdate)) {
			$pwordError = 'Please enter password';
			$valid = false;
		}
		else {
			$pword = filterinput($pword);
		}
		//hash password
		$pword = password_hash($pword, PASSWORD_DEFAULT);

		//If all data is valid...
		if ($valid) {
			$pdo = Database::connect();
			//If this is an update...
			if (!empty($cupdate)) {
				$cupdate = filterinput($cupdate);
				$sql = 'UPDATE whwebapp.accounts SET firstname=:firstname, lastname=:lastname, username=:username, pword=:pword WHERE id = :cupdate';
				$query = $pdo->prepare($sql);
				$query->bindParam(':firstname', $firstname, PDO::PARAM_STR);
				$query->bindParam(':lastname', $lastname, PDO::PARAM_STR);
				$query->bindParam(':username', $username, PDO::PARAM_STR);
				$query->bindParam(':pword', $pword, PDO::PARAM_STR);
				$query->bindParam(':cupdate', $cupdate, PDO::PARAM_INT);
			}
			//Otherwise this is an insert
			else {
				$sql = 'INSERT INTO whwebapp.accounts(firstname, lastname, username, pword) VALUES(:firstname,:lastname,:username,:pword)';
				$query = $pdo->prepare($sql);
				$query->bindParam(':firstname', $firstname, PDO::PARAM_STR);
				$query->bindParam(':lastname', $lastname, PDO::PARAM_STR);
				$query->bindParam(':username', $username, PDO::PARAM_STR);
				$query->bindParam(':pword', $pword, PDO::PARAM_STR);
			}
			$result = $query->execute();
			Database::disconnect();
			if ($result) {
				echo '<script>window.location="http://localhost/php_app/index.php";</script>';
				die();
			}
		}
	}
	//called by clicking update button
	else if (!empty($_GET['id'])) {
		$id = $_GET['id'];
		//sanitize id value
		$id = filterinput($id);

		if (null==$id) {
			echo '<script>window.location="http://localhost/php_app/index.php";</script>';
		}
		else {
			$pdo = Database::connect();
			$sql = 'SELECT * FROM whwebapp.accounts WHERE id = :id';
			$query = $pdo->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$result = $query->execute();
			$data = $query->fetch(PDO::FETCH_ASSOC);
			Database::disconnect();
			//if select query returned false, go back to index
			if (empty($data)) {
				echo '<script>window.location="http://localhost/php_app/index.php";</script>';
				die();
			}
			$firstname = $data['firstname'];
			$lastname = $data['lastname'];
			$username = $data['username'];
			$update = $id;
		}
	}

?>
	<center>
		<br><br><h1>Account Details<h1><br>
		<h3><?php if (isset($result) && empty($result)) {
			echo 'Execute failed'/*.$query->errorInfo();
			print_r($query->errorInfo())*/;}?></h3>
	</center>
	<form method="POST" action="http://localhost/php_app/details.php">
	<table>
		<tbody>
			<tr>
				<td>
					<h3 class="phos">First Name</h3>
				</td>
				<td>
					<input type="text" name="firstname" value="<?php echo !empty($firstname)?$firstname:''; ?>" size="30" maxlength="128">
				</td>
				<td>
				<?php if (!empty($firstNameError)): ?>
					<p><?php echo $firstNameError;?></p>
				<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3 class="phos">Last Name</h3>
				</td>
				<td>
					<input type="text" name="lastname" value="<?php echo !empty($lastname)?$lastname:''; ?>" size="30" maxlength="128">
				</td>
				<td>
				<?php if (!empty($lastNameError)): ?>
					<p><?php echo $lastNameError;?></p>
				<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3 class="phos">Username</h3>
				</td>
				<td>
					<input type="text" name="username" value="<?php echo !empty($username)?$username:''; ?>" size="30" maxlength="128">
				</td>
				<td>
				<?php if (!empty($usernameError)): ?>
					<p><?php echo $usernameError;?></p>
				<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td>
					<h3 class="phos">Password</h3>
				</td>
				<td>
					<input type="password" name="pword" size="30" maxlength="128">
				</td>
				<td>
				<?php if (!empty($pwordError)): ?>
					<p><?php echo $pwordError;?></p>
				<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="text-align: right">
					<input type="hidden" value="<?php echo $update?>" name="update">
					<a href="index.php"><button class="button" type="button">Back</button></a>
					&nbsp;
					<input type="submit" value="Create" name="submit">
				</td>
			</tr>
		</tbody>
	</table>
	</form>


</body>


</html>
