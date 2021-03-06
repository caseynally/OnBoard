<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param Zend\Db\Result $this->files
 * @param Committee      $this->committee (optional)
 * @param stdClass       $this->sort
 */
use Application\Models\MeetingFile;
use Application\Models\MeetingFilesTable;
use Application\Models\Person;
use Blossom\Classes\Url;
?>
<section>
    <header>
        <h1><?= $this->_(['meetingFile', 'meetingFiles', count($this->files)]); ?></h1>
        <div class="tools">
        <?php
            if ($this->committee && Person::isAllowed('meetingFiles', 'add')) {
                $actions      = [];
                $committee_id = $this->committee->getId();
                $helper       = $this->template->getHelper('dropdown');
                foreach (MeetingFile::$types as $type) {
                    $actions[] = [
                        'label' => $type,
                        'url'   => BASE_URI."/meetingFiles/update?type=$type;committee_id=$committee_id"
                    ];
                }
                echo $helper->dropdown($actions, $this->_('add'), 'meetingFile_add');
            }
        ?>
        </div>
    </header>
    <table>
    <thead>
        <tr><?php
                $url = new Url(Url::current_url(BASE_HOST));

                $fields = ['title', 'filename', 'meetingDate'];
                foreach ($fields as $f) {
                    $url->sort = ($this->sort->field == $f && $this->sort->direction == 'desc') ? "$f asc" : "$f desc";
                    $class     =  $this->sort->field == $f ? " class=\"sort {$this->sort->direction}\"" : '';
                    echo "<th><a href=\"$url\"$class>{$this->_($f)}</a></th>";
                }
            ?>
            <th><form method="get">
                    <fieldset>
                        <?php
                            foreach ($_GET as $k=>$v) {
                                if ($k != 'type') {
                                    $k = parent::escape($k);
                                    $v = parent::escape($v);
                                    echo "<input name=\"$k\" type=\"hidden\" value=\"$v\" />";
                                }
                            }
                        ?>
                        <select name="type" onChange="this.form.submit();">
                            <option value=""><?= $this->_('type'); ?></option>
                            <?php
                                $type = !empty($_GET['type']) ? $_GET['type'] : null;
                                foreach (MeetingFilesTable::$types as $t) {
                                    $selected = !empty($_GET['type']) && $_GET['type']==$t ? 'selected="true"' : '';
                                    echo "<option $selected>$t</option>";
                                }
                            ?>
                        </select>
                    </fieldset>
                </form>
            </th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php
        $userCanEdit   = Person::isAllowed('meetingFiles', 'edit'  );
        $userCanDelete = Person::isAllowed('meetingFiles', 'delete');

        foreach ($this->files as $f) {
            $id       = $f->getId();
            $title    = parent::escape($f->getTitle());
            $filename = parent::escape($f->getFilename());
            $actions  = [];

            if ($userCanEdit) {
                $actions[] = [
                    'url'   => BASE_URI."/meetingFiles/update?meetingFile_id=$id",
                    'label' => $this->_('meetingFile_edit')
                ];
            }
            if ($userCanDelete) {
                $actions[] = [
                    'url'   => BASE_URI."/meetingFiles/delete?meetingFile_id=$id",
                    'label' => $this->_('meetingFile_delete')
                ];
            }
            $dropdown = $actions
                      ? $helper->dropdown($actions, $this->_('more'), "meetingFile_$id")
                      : '';

            $url = $f->getDownloadUri();
            echo "
            <tr><td>$title</td>
                <td><a href=\"$url\">$filename</a></td>
                <td>{$f->getMeetingDate(DATE_FORMAT)}</td>
                <td>{$f->getType()}</td>
                <td>$dropdown</td>
            </tr>
            ";
        }
    ?>
    </tbody>
    </table>
</section>
