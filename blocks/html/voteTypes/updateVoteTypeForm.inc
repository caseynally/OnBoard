<?php
/**
 * @copyright Copyright (C) 2006-2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 */
?>
<h1>Update VoteType</h1>
<form method="post" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<fieldset><legend>VoteType Info</legend>
	<input name="id" type="hidden" value="<?php echo $this->voteType->getId(); ?>" />
	<table>

	<tr><td><label for="voteType-name">name</label></td>
		<td><input name="voteType[name]" id="voteType-name" value="<?php echo $this->voteType->getName(); ?>" /></td></tr>

	</table>

	<button type="submit" class="submit">Submit</button>
	<button type="button" class="cancel" onclick="document.location.href='<?php echo BASE_URL; ?>/voteTypes';">Cancel</button>
</fieldset>
</form>