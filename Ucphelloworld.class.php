<?php
namespace FreePBX\modules;

class Ucphelloworld implements \BMO {
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new Exception("Not given a FreePBX Object");
		}
		$this->FreePBX = $freepbx;
		$this->db = $freepbx->Database;
	}
	public function install(){}
	public function uninstall(){}
	public function backup() {}
	public function restore($backup) {}
	public function doConfigPageInit($page) {}
	public function ucpConfigPage($mode, $user, $action) {
		if(empty($user)) {
			$enabled = ($mode == 'group') ? true : null;
		} else {
			if($mode == 'group') {
				$enabled = $this->FreePBX->Ucp->getSettingByGID($user['id'],'Ucphelloworld','enabled');
				$enabled = !($enabled) ? false : true;
			} else {
				$enabled = $this->FreePBX->Ucp->getSettingByID($user['id'],'Ucphelloworld','enabled');
			}
		}

		$html = array();
		$html[0] = array(
			"title" => _("Hello World"),
			"rawname" => "ucphelloworld",
			"content" => load_view(dirname(__FILE__)."/views/ucp_config.php",array("mode" => $mode, "enabled" => $enabled))
		);
		return $html;
	}
	public function ucpAddUser($id, $display, $ucpStatus, $data) {
		$this->ucpUpdateUser($id, $display, $ucpStatus, $data);
	}
	public function ucpUpdateUser($id, $display, $ucpStatus, $data) {
		if($display == 'userman' && isset($_POST['type']) && $_POST['type'] == 'user') {
			if(isset($_POST['ucphelloworld_enable']) && $_POST['ucphelloworld_enable'] == 'yes') {
				$this->FreePBX->Ucp->setSettingByID($id,'Ucphelloworld','enabled',true);
			}elseif(isset($_POST['ucphelloworld_enable']) && $_POST['ucphelloworld_enable'] == 'no') {
				$this->FreePBX->Ucp->setSettingByID($id,'Ucphelloworld','enabled',false);
			} elseif(isset($_POST['ucphelloworld_enable']) && $_POST['ucphelloworld_enable'] == 'inherit') {
				$this->FreePBX->Ucp->setSettingByID($id,'Ucphelloworld','enabled',null);
			}
		}
	}
	public function ucpDelUser($id, $display, $ucpStatus, $data) {}
	public function ucpAddGroup($id, $display, $data) {
		$this->ucpUpdateGroup($id,$display,$data);
	}
	public function ucpUpdateGroup($id,$display,$data) {
		if($display == 'userman' && isset($_POST['type']) && $_POST['type'] == 'group') {
			if(isset($_POST['ucphelloworld_enable']) && $_POST['ucphelloworld_enable'] == 'yes') {
				$this->FreePBX->Ucp->setSettingByGID($id,'Ucphelloworld','enabled',true);
			} else {
				$this->FreePBX->Ucp->setSettingByGID($id,'Ucphelloworld','enabled',false);
			}
		}
	}
	public function ucpDelGroup($id,$display,$data) {}
}
