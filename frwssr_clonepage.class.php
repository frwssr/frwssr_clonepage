<?php

/**
 * A field type for cloning a (somewhat standard) page
 *
 * @package default
 * @author Nils Mielke, FEUERWASSER
 * @version v0.1 - 2022-12-08
 */
class PerchFieldType_frwssr_clonepage extends PerchAPI_FieldType
{

    /**
     * Form fields for the edit page
     *
     * @param array $details
     * @return string
     */
    public function render_inputs($details = array())
    {
        $ftPath = PERCH_LOGINPATH . '/addons/fieldtypes/frwssr_clonepage/';
        $perch = Perch::fetch();
        $perch->add_javascript($ftPath . 'init.js?v=20221208');

        $id = $this->Tag->input_id();
        $buttontext = $this->Tag->buttontext() ? $this->Tag->buttontext() : '✌️ Clone page ⚠️';
        $renamepostfix = $this->Tag->renamepostfix() ? ' data-renamepostfix="' . $this->Tag->renamepostfix() . '"' : ' data-renamepostfix=" (Copy)"';
        $buttonbg = $this->Tag->buttonbg() ? ' style="' . $this->Tag->buttonbg() . '"' : ' style="background: slategray"';

        $s = $this->Form->text($id, $buttontext, $class='frwssr_clonepage__button button button-simple', $limit=false, $type='submit', $attributes='readonly data-path="' . $ftPath . '"' . $renamepostfix . $buttonbg);

        return $s;
    }

}