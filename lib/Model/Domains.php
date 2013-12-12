<?php

class Model_Domains extends Model {
	public function getDomainlist($order = "name", $dir = "ASC", $ids = false) {
		$data = $this->db->getAll("SELECT * FROM domains".($ids !== false ? " WHERE id IN ('".implode("','", $ids)."')" : "")." ORDER BY ".$order." ".$dir);
		if(is_array($data)) {
			foreach($data as $key => $entry) {
                                foreach($entry as $colname => $colvalue) {
                                        $data[$key][$colname."_clean"] = $colvalue;
				}
			}
		}
		return $data;
	}

	public function searchDomainlist($search, $order = "name", $dir = "ASC", $ids = false) {
		$sql = "SELECT * FROM domains WHERE ";
		$sql.= "name LIKE '%".addslashes($search)."%'";
		$sql.= ($ids !== false ? " AND id IN ('".implode("','", $ids)."')" : "");
		$sql.= " ORDER BY ".addslashes($order)." ".addslashes($dir);
		$data = $this->db->getAll($sql);

		if(is_array($data)) {
			foreach($data as $key => $entry) {
				foreach($entry as $colname => $colvalue) {
					$data[$key][$colname."_clean"] = $colvalue;
					$data[$key][$colname] = str_replace($search, '<span class="search_highlight">'.$search.'</span>', $colvalue);
				}
			}
		}
		return $data;
	}

	public function getNameById($id) {
		return $this->db->getOne("SELECT name FROM domains WHERE id = ".(int)$id);
	}
}
