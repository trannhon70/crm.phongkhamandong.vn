<?php

/* --------------------------------------------------------

// ˵��: ���� sys_part ����

// ����: ��ҽս�� 

// ʱ��: 2013-06-10 14:00

// ----------------------------------------------------- */



class part {

	var $db;

	var $tab;



	var $part_data;

	var $part_id_name;

	var $tmp;



	function part($db, $tab='') {

		$this->db = $db;

		$this->tab = $tab;



		$this->init();

	}



	// ��ʼ�����飬��������ֻ���ȡһ�����ݿ⣬���в���ͨ���������:

	function init() {

		$this->part_data = $this->db->query("select * from ".$this->tab."sys_part order by id asc", "id");



		// ��ʼ�� id=>name ����:

		$this->part_id_name = array();

		foreach ($this->part_data as $id => $li) {

			$this->part_id_name[$id] = $li["name"];

		}

	}



	// ��ȡpart_id��Ӧ������

	function get_part_name($part_id) {

		return $this->part_id_name[$part_id];

	}



	// ��ȡ���ŵ�����

	function get_part_info($part_id) {

		return $this->part_data[$part_id];

	}



	// ����������part_id:

	function get_part_id_by_name($part_name) {

		foreach ($this->part_id_name as $id => $name) {

			if ($name == $part_name) {

				return $id;

			}

		}



		return false;

	}



	// ��ȡ����Ĳ�������:

	function get_parent($part_id) {

		// todo

	}



	// ��ȡ����Ĳ�������, ���ظ�ʽΪ����:

	function get_sub_part($part_id=0, $with_self=1, $level=0) {

		// ��ʼ��һ�����ڵݹ������:

		if ($level == 0) {

			$this->tmp = array();

		}



		// �Ƿ����������:

		if ($with_self && $part_id > 0) {

			$this->tmp[$part_id] = $level;

			$level++;

		}



		// ��ǰ:

		foreach ($this->part_data as $id => $li) {

			if ($li["pid"] == $part_id) {

				$this->tmp[$id] = $level;

			}

		}



		// �ݹ��ѯ:

		foreach ($this->part_data as $id => $li) {

			if ($li["pid"] == $part_id) {

				$this->get_sub_part($id, 0, ++$level);

			}

		}



		return $this->tmp;

	}



	// �г�������ʽ��part��Ŀ:

	function get_sub_part_list($part_id, $with_self=1, $space_char='&nbsp;&nbsp;&nbsp;&nbsp;') {

		$parts = $this->get_sub_part($part_id, $with_self);

		$out = array();

		foreach ($parts as $p_id => $p_level) {

			$out[$p_id] = str_repeat($space_char, $p_level).$this->part_id_name[$p_id];

		}



		return $out;

	}



}

?>