<?php
/**
 * @copyright Copyright (C) 2008 City of Bloomington, Indiana. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$tagList = new TagList();
$tagList->find();

$template = new Template();
$template->blocks[] = new Block('tags/breadcrumbs.inc');
$template->blocks[] = new Block('tags/tagList.inc',array('tagList'=>$tagList));
echo $template->render();