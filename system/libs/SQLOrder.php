<?php
	if(! defined('BASEPATH') ){ exit('Unable to view file.'); }
	
	class SQLOrder
	{
		private $rows = array(), $methods = array('DESC', 'ASC'), $current, $orderRow, $orderMethod, $get_param, $defaultRow;
		
		function __construct($rows, $default, $get_param = 'order')
		{
			$this->rows = $rows;
			
			$this->defaultRow = $default;
			$this->get_param = $get_param;
			
			$this->current = $_GET[$get_param];
			$this->current = explode('|', $this->current);
			
			if (count($this->current) < 2)
				$this->current = array($default, $this->methods[0]);
			
			$this->orderRow = $this->current[0];
			$this->orderMethod = $this->current[1];
			
			$row = $this->rows[$this->orderRow];
			if (!$row)
			{
				$row = $default;
				$this->orderRow = $row;
				$this->current[0] = $row;
			}
			
			$method = $this->orderMethod;
			if (!in_array($method, $this->methods))
			{
				$method = $this->methods[0];
				$this->orderMethod = $method;
				$this->current[1] = $method;
			}
		}
		
		function GetOrderRow()
		{
			$row = $this->rows[$this->orderRow];
			if (!$row)
				$row = $this->defaultRow;
			return $row;
		}
		function GetOrderMethod()
		{
			return $this->orderMethod;
		}
		
		function GetParam($rowKey)
		{
			$row = $this->rows[$rowKey];
			if (!$row)
				$rowKey = '';
			
			$method = $this->orderMethod == $this->methods[0] ? $this->methods[1] : $this->methods[0];
			
			return $this->get_param . '=' . $rowKey . '|' . $method;
		}
	}
?>