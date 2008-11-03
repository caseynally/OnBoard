<?php
/**
 * @copyright Copyright (C) 2006-2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @param TopicList $this->topicList
 */
$addTopicButton = '';
if (isset($this->committee) && userHasRole(array('Administrator','Clerk')))
{
	$url = new URL(BASE_URL.'/topics/addTopic.php');
	$url->committee_id = $this->committee->getId();
	$addTopicButton = "<a class=\"add button\" href=\"$url\">Add</a>";
}

?>
<div class="interfaceBox">
	<h1><?php echo $addTopicButton; ?>
		Legislation
	</h1>
	<table>
	<tr><th></th>
		<th>Date</th>
		<th>Description</th>
		<th>Number</th>
		<th>Type</th>
	</tr>
	<?php
		foreach($this->topicList as $topic)
		{
			$editButton = '';
			if (userHasRole(array('Administrator','Clerk')))
			{
				$url = new URL(BASE_URL.'/topics/updateTopic.php');
				$url->topic_id = $topic->getId();
				$editButton = "<a class=\"edit button\" href=\"$url\">Edit</a>";
			}
			$type = View::escape($topic->getTopicType());
			$number = View::escape($topic->getNumber());
			$date = $topic->getDate('n/j/Y');
			$description = View::escape($topic->getDescription());
			$synopsis = View::escape($topic->getSynopsis());
			echo "
			<tr><td>$editButton</td>
				<td>$date</td>
				<td>$description</td>
				<td><a href=\"{$topic->getURL()}\">$number</a></td>
				<td>$type</td>
			</tr>
			";
		}
	?>
	</table>
</div>