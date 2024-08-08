<?php
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_whennotfound extends DokuWiki_Action_Plugin {
    function getInfo(){ return conf_loadfile(dirname(__FILE__).'/info.txt'); }
	  function register($contr){
    $contr->register_hook('ACTION_ACT_PREPROCESS','BEFORE',$this,'handle_action',array());
    $contr->register_hook('TPL_CONTENT_DISPLAY', 'AFTER', $this, 'handle_content', array());
   }
  function handle_action(&$e, $param){
    if($e->data != 'show') return;
    global $ID;
    global $INFO; ve($INFO); die();
    if($_GET['whennotfounded']){
      msg("You are automatically redirected here from the non-existent page [".hsc($_GET['whennotfounded'])."].".(auth_quickaclcheck($_GET['whennotfounded'])>=AUTH_CREATE ? " If you did not want to be redirected, you may also <a href='".wl($_GET['whennotfounded'], "do=edit")."'>create and edit [".hsc($_GET['whennotfounded'])."]</a>":''));
      return;
    }
    if(is_file(wikiFN($ID))) return;
    global $INFO;
    global $conf;


    $excludes=$this->getConf('excludepatterns');
    if(is_string($excludes)) $excludes=explode(',',$excludes);
    foreach($excludes as $exclude){
      if(strpos($ID,$exclude)!==false) return;
    }    
    $actions=$this->getConf('actions');
    if(is_string($actions)) $actions=explode(',',$actions);
    foreach($actions as $action){
      $func="do_$action";
      if(is_callable([$this,$func])){
        if($this->$func($e)) break;
      }
      else dbg("Undefined whennotfound thing to do [".hsc($action)."]");
    }
  }
  function handle_content(&$e, $param){
    global $action_plugin_whennotfound_pagelist;
    if($action_plugin_whennotfound_pagelist) echo $action_plugin_whennotfound_pagelist;
  }

  function do_send404(&$e){
    header('HTTP/1.0 404 Not Found');
    die();
  }
  function do_startpage(&$e){
    global $ID;
    $startpages = explode(',',$this->getConf('startpages'));
    foreach($startpages as $index){
      if($index == '@subpage_with_same_name') $index = noNS($ID);
      if(is_file(wikiFN("$ID:$index"))){
        #Redirect. Add whennotfounded param if user has CREATE permission, so on the resulting page they can be prompted if they intended to create this $ID page instead of being redirected there.
        $get=$_GET; unset($get['id']);
        if(auth_quickaclcheck($ID)>=AUTH_CREATE) $get['whennotfounded']=$ID;
        header("Location: ".wl("$ID:$index", $get,null,'&'));
        die();
      }
    }
  }
  function do_translation(&$e){
    global $ID;
    global $conf;
    if(!($h =& plugin_load('helper', 'translation'))) return;
    if($id=$h->getavailableid($ID)){
      $get=$_GET;
      $get['lang']=$conf['lang'];
      if(auth_quickaclcheck($ID)>=AUTH_CREATE) $get['whennotfounded']=$ID;
      header("Location: ".wl($id, $get,null,'&'));
      die();
    }
  }
  function do_pagelist(&$e){
    global $ID;
    if(!is_dir(dirname(wikiFN("$ID:dummy"))) || !($h =& plugin_load('syntax', 'indexmenu_indexmenu'))) return;
    if(!($renderer = p_get_renderer('xhtml'))) return;
    $handler = new Doku_Handler();
    $handled = $h->handle("{{indexmenu>:$ID#2|js#default}}",$renderer,null,$handler);
    $h->render('xhtml',$renderer, $handled);

    global $action_plugin_whennotfound_pagelist;
    $action_plugin_whennotfound_pagelist=$renderer->doc; #this'll be printed later in handle_content
    msg("The page you requested [".hsc($ID)."] is a namespace and does not exist as a separate page. A search action is triggered for that page name below."
      .( auth_quickaclcheck($ID)>=AUTH_CREATE ? " You may also <a href='".wl($ID, "do=edit")."'>create and edit [".hsc($ID)."]</a>." : "") 
    );
    return true;
   }

   function do_search(&$e){
    global $ID;
    if(!actionOK('search')) return;
    msg("The page you requested [".hsc($ID)."] is a namespace and does not exist as a separate page. A search action is triggered for that page name below."
      .( auth_quickaclcheck($ID)>=AUTH_CREATE ? " You may also <a href='".wl($ID, "do=edit")."'>create and edit [".hsc($ID)."]</a>." : "") );
    $e->data = 'search';
    return true;
  }
}
