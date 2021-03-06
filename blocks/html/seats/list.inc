<?php
/**
 * @copyright 2009-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/agpl.html GNU/AGPL, see LICENSE.txt
 * @param Zend\Db\ResultSet $this->seats
 * @param Committee         $this->committee      (optional)
 * @param boolean           $this->disableButtons (optional)
 * @param string            $this->title          (optional)
 */
use Application\Models\Person;
use Application\Templates\Helpers\ButtonLink;

$helper = $this->template->getHelper('buttonLink');
$title  = $this->title ? self::escape($this->title) : $this->_(['seat', 'seats', count($this->seats)]);
?>
<section>
    <header>
        <h1><?= $title; ?></h1>
    </header>
    <table>
        <thead>
            <tr><th></th>
                <th><?= $this->_('code'); ?></th>
                <th><?= $this->_('name'); ?></th>
                <th><?= $this->_(['appointer', 'appointers', 1]); ?></th>
                <th><?= $this->_('startDate'); ?></th>
                <th><?= $this->_('endDate'); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            $editButton = '';

            foreach ($this->seats as $seat) {
                $seat_id = $seat->getId();

                if (!$this->disableButtons && Person::isAllowed('seats', 'edit')) {
                    $editButton = $helper->buttonLink(
                        BASE_URI."/seats/update?seat_id=$seat_id",
                        $this->translate('seat_edit'),
                        'edit'
                    );
                }
                $code      = self::escape($seat->getCode());
                $name      = self::escape($seat->getName());
                $appointer = self::escape($seat->getAppointer());
                $uri  = BASE_URI."/seats/view?seat_id=$seat_id";


                echo "
                <tr><td>$editButton</td>
                    <td>$code</td>
                    <td><a href=\"$uri\">$name</a></td>
                    <td>$appointer</td>
                    <td>{$seat->getStartDate(DATE_FORMAT)}</td>
                    <td>{$seat->getEndDate(DATE_FORMAT)}</td>
                </tr>
                ";
            }
        ?>
        </tbody>
    </table>
    <?php
        if (!$this->disableButtons && isset($this->committee) && Person::isAllowed('seats', 'add')) {
            echo $helper->buttonLink(
                BASE_URI."/seats/update?committee_id={$this->committee->getId()}",
                $this->translate('seat_add'),
                'add'
            );
        }
    ?>
</section>
