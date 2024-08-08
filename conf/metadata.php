<?php
$meta['trythings'] = array('multicheckbox',
'_choices' => array('startpage','translation','send404','pagelist','search'));
$meta['startpages'] = array('multicheckbox',
                          '_choices' => array('@start_page','@subpage_with_same_name'));
$meta['excludepatterns'] = array('multicheckbox',
                          '_choices' => array('talk:'));
                          
