<?php
/**
 * @copyright 2016-2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param string $this->label
 * @param string $this->content
 */
?>
<section>
    <header>
        <h1><?= $this->_('content_edit'); ?></h1>
    </header>

    <form method="post" action="<?= BASE_URI; ?>/site/updateContent">
        <fieldset>
            <legend><?= $this->_($this->label); ?></legend>
            <input name="label" type="hidden" value="<?= $this->label; ?>" />
            <textarea name="content"><?= self::escape($this->content); ?></textarea>

            <div>
            <?php
                $helper = $this->template->getHelper('saveAndCancelButtons');
                echo $helper->saveAndCancelButtons(BASE_URI.'/site');
            ?>
            </div>
        </fieldset>
    </form>
</section>
