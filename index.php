<!DOCTYPE html>
<html>
<head>
	<title>Account Management</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
	require 'database.php';
	require 'utils.php';

	//If this page requested with POST method...
	$id = 0;
	if (!empty($_POST)) {
		$id = $_POST['id'];
		$id = filterinput($id);

		//after filter, check that there is still a value
		if (empty($id)) {
			echo '<script>window.location="http://localhost/php_app/index.php";</script>';
			die();
		}
		else {
			//perform Delete
			$pdo = Database::connect();
			$sql = "DELETE FROM whwebapp.accounts WHERE id = :id";
			$query = $pdo->prepare($sql);
			$query->bindParam(':id', $id, PDO::PARAM_INT);
			$result = $query->execute();
			Database::disconnect();
			if ($result) {
				$id = null;
				echo '<script>window.location="http://localhost/php_app/index.php";</script>';
				die();
			}
			else {
				echo '<p>Delete failed</p>';
			}
		}
	}
	//Normal GET request starts here
	$pdo = Database::connect();
	$sql = 'SELECT * FROM whwebapp.accounts ORDER BY id ASC';
?>
	<center>
		<br><br><h1>Account Management<h1><br>
	</center>
	<!-- List of accounts -->
	<table>
		<tbody class="phos">
			<tr>
				<th>
					<h3>First Name</h3>
				</th>
				<th>
					<h3>Last Name</h3>
				</th>
				<th>
					<h3>Username</h3>
				</th>
				<th colspan="2" style="text-align: center">
					<h3>Action</h3>
				</th>
			</tr>
		<?php
		//Build dynamic list of accounts
		$pdo->query($sql);
		foreach($pdo->query($sql) as $row): ?>
			<tr>
				<td>
				<form action="index.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this account?');">
					<input type="hidden" value="<?php echo $row['id'] ?>" name="id">
					<?php echo $row['firstname'] ?>
				</td>
				<td>
					<?php echo $row['lastname'] ?>
				</td>
				<td>
					<?php echo $row['username'] ?>
				</td>
				<td>
					<a href="http://localhost/php_app/details.php?id=<?php echo $row['id'] ?>"><button class="button" type="button">View/Update</button></a>
				</td>
				<td>
					<input type="submit" class="delete" value="Delete">
				</form>
				</td>
			</tr>
		<?php endforeach;
			Database::disconnect(); ?>
			<tr>
				<td colspan="5" style="text-align: center">
					<a href="http://localhost/php_app/details.php"><button class="button" type="button">Create</button></a>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
