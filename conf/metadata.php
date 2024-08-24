<?php
$meta['actions'] = array('multicheckbox',
'_choices' => array('slashtocolon','startpage','translation','send404_onlynoneditor','send404','pagelist','search'));
$meta['startpages'] = array('multicheckbox',
                          '_choices' => array('@start_page','@subpage_with_same_name'));
$meta['excludepatterns'] = array('multicheckbox',
                          '_choices' => array('talk:'));
                          
