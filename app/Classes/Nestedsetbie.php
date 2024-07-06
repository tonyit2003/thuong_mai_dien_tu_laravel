<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Nestedsetbie
{
	public $params = NULL;
	public $checked = NULL;
	public $data = NULL;
	public $count = 0;
	public $count_level = 0;
	public $lft = NULL;
	public $rgt = NULL;
	public $level = NULL;

	function __construct($params = NULL)
	{
		$this->params = $params;
		$this->checked = NULL;
		$this->data = NULL;
		$this->count = 0;
		$this->count_level = 0;
		$this->lft = NULL;
		$this->rgt = NULL;
		$this->level = NULL;
	}

	// Lấy dữ liệu từ cơ sở dữ liệu dựa trên các tham số đã được thiết lập
	public function Get()
	{
		$foreignkey = (isset($this->params['foreignkey'])) ? $this->params['foreignkey'] : 'post_catalogue_id';
		$moduleExtract = explode('_', $this->params['table']);
		$result = DB::table($this->params['table'] . ' as tb1')
			->select('tb1.id', 'tb2.name', 'tb1.parent_id', 'tb1.lft', 'tb1.rgt', 'tb1.level', 'tb1.order')
			->join($moduleExtract[0] . '_catalogue_language as tb2', 'tb1.id', '=', 'tb2.' . $foreignkey . '')
			->where('tb2.language_id', '=', $this->params['language_id'])->whereNull('tb1.deleted_at')
			->orderBy('tb1.lft', 'asc')->get()->toArray();
		$this->data = $result;
	}
	
	// Tạo một mảng quan hệ giữa các node trong cây phân cấp.
	public function Set()
	{
		if (isset($this->data) && is_array($this->data)) {
			$arr = NULL;
			foreach ($this->data as $key => $val) {
				$arr[$val->id][$val->parent_id] = 1;
				$arr[$val->parent_id][$val->id] = 1;
			}
			return $arr;
		}
	}

	// Tính toán các giá trị lft, rgt và level cho mỗi node trong cây phân cấp theo mô hình Nested Set.
	public function Recursive($start = 0, $arr = NULL)
	{
		$this->lft[$start] = ++$this->count;
		$this->level[$start] = $this->count_level;
		if (isset($arr) && is_array($arr)) {
			foreach ($arr as $key => $val) {
				if ((isset($arr[$start][$key]) || isset($arr[$key][$start])) && (!isset($this->checked[$key][$start]) && !isset($this->checked[$start][$key]))) {
					$this->count_level++;
					$this->checked[$start][$key] = 1;
					$this->checked[$key][$start] = 1;
					$this->recursive($key, $arr);
					$this->count_level--;
				}
			}
		}
		$this->rgt[$start] = ++$this->count;
	}

	// Lưu các giá trị lft, rgt và level vào cơ sở dữ liệu.
	public function Action()
	{
		if (isset($this->level) && is_array($this->level) && isset($this->lft) && is_array($this->lft) && isset($this->rgt) && is_array($this->rgt)) {

			$data = NULL;
			foreach ($this->level as $key => $val) {
				if ($key == 0) continue;
				$data[] = array(
					'id' => $key,
					'level' => $val,
					'lft' => $this->lft[$key],
					'rgt' => $this->rgt[$key],
					'user_id' => Auth::id(),
				);
			}
			if (isset($data) && is_array($data) && count($data)) {
				DB::table($this->params['table'])->upsert($data, 'id', ['level', 'lft', 'rgt']);
			}
		}
	}

	// Tạo một danh sách dạng dropdown từ dữ liệu cây phân cấp.
	public function Dropdown($param = NULL)
	{
		$this->get();
		if (isset($this->data) && is_array($this->data)) {
			$temp = NULL;
			$temp[0] = (isset($param['text']) && !empty($param['text'])) ? $param['text'] : '[Root]';
			foreach ($this->data as $key => $val) {
				$temp[$val->id] = str_repeat('|-----', (($val->level > 0) ? ($val->level - 1) : 0)) . $val->name;
			}
			return $temp;
		}
	}
}
