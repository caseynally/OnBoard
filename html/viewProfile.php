<?php
/*
	$_GET variables:	id
*/
	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		include(GLOBAL_INCLUDES."/errorMessages.inc");
		$user = new User($_GET['id']); 
		$username = $user->getUsername();
	?>
	<h1><?php echo $user->getFirstname()." ".$user->getLastname(); ?>'s Profile</h1>
		<fieldset><legend>Profile Info</legend>
			<table>
				<tr><th>Photo</th></tr>
				<tr><td><?php 
					# If the user is authenticated through LDAP then their photo is pulled from ldap,
					# Otherwise the photo is set to the user defined photo path.
					if ($user->getAuthenticationMethod() == "LDAP") 
					{
						$ldap = new LDAPEntry($user->getUsername());
						if ($ldap->getPhoto()) { $photo = "<img src=\"photo.php?user={$username}\" alt=\"{$user->getUsername()}\" />"; }
						else { $photo = "<img src=\"http://isotope.bloomington.in.gov/directory/images/nophoto.jpg\" alt=\"No Photo\" />"; }
					}
					else { $photo = "<img src=\"{$user->getPhotoPath()}\" alt=\"{$user->getUsername()}\" />"; }
					echo $photo; ?></td></tr>
			</table>
			<table>
				<tr><th>Field Name</th><th>Personal Information</th></tr>
				<tr><td><label>Name</label></td>
						<td><?php echo $user->getFirstname()." ".$user->getLastname(); ?></td>
				</tr>
				<tr><td><label>Email Address</label></td>
						<td><?php echo $user->getEmail(); ?></td>
				</tr>
				
				<tr><td><label>Home Phone Number</label></td>
						<td><?php echo $user->getHomePhone(); ?></td>
				</tr>
				
				<tr><td><label>Work Phone Number</label></td>
						<td><?php echo $user->getWorkPhone(); ?></td>
				</tr>
				
				<tr><td>----------Address-----------</td></tr>
				<tr><td><label>Address</label></td>
						<td><?php echo $user->getAddress(); ?></td>
				</tr>
				
				<tr><td><label>City, State</label></td>
						<td><?php echo $user->getCity(); ?></td>
				</tr>
				
				<tr><td><label>Zip Code</label></td>
						<td><?php echo $user->getZipcode(); ?></td>
				</tr>
				<tr><td>---------------------------------</td></tr>
				<?php 
					$seatList = new SeatList();
					$seatList->find();
					$count = 0;
					foreach($seatList as $seat) 
					{
						if ($seat->getUser() == $user) {
							$label = "Committee(s)";
							$count += 1;
							if ($count > 1) { $label = ""; }
							echo "<tr><td><label>$label</label></td><td>{$seat->getCommittee()->getName()}</td></tr>"; 
						}
					} 
					?>
				<tr><td>----------------------------------</td></tr>
				<tr><td><label>About <?php echo $user->getFirstname()." ".$user->getLastname(); ?></label></td>
						<td><?php echo $user->getAbout(); ?></td>
				</tr>
				<tr><td><label>Last Updated:</label></td>
						<td><?php echo $user->getTimestamp(); ?></td>
				</tr>
			</table>
		</fieldset>
</div>

<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>