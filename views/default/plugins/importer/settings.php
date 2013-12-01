<?php
        /**
         * Elgg importer plugin
         *
         * @author Gerard Kanters
         * @copyright Centillien 2013
         */

        $importer_name = $vars['entity']->importer_name;
        $importer_code = $vars['entity']->importer_code;

        echo elgg_echo('importer:importer_code');
        echo elgg_view('input/text', array('name'=>'params[importer_code]', 'value'=>$importer_code));

        echo elgg_echo('importer:importer_name');
        echo elgg_view('input/text', array('name'=>'params[importer_name]', 'value'=>$importer_name));
	echo '<br>';
