<?php
	class Item {
		public $name;
		public $price;
		public $quantity;
		
		public function __construct($name, $price, $quantity) {
			$this->name = $name;
			$this->price = $price;
			$this->quantity = $quantity;
		}
	}
	
	class Items {
		private $items;
		
		public function __construct($items) {
			$this->items = $items;
		}
		
		public function getItems() {
			return $this->items;
		}
	}
	
	abstract class Filter {
		protected $propName;
		
		public function applyFilter($items, $propName) {
			$this->propName = $propName;
			$filteredItems = array_filter($items, array($this, 'filterFunc'), ARRAY_FILTER_USE_BOTH);
			
			return $filteredItems;
		}
		
		abstract protected function filterFunc($val, $key);
	}
	
	class Filter1 extends Filter {
		protected function filterFunc($val, $key) {
			$propValue = $val->{$this->propName};
			return $propValue == 'item_name1';
		}
	}
	
	class Filter2 extends Filter {
		protected function filterFunc($val, $key) {
			$propValue = $val->{$this->propName};
			return $propValue > 100;
		}
	}
	
	class Filter3 extends Filter {
		protected function filterFunc($val, $key) {
			$propValue = $val->{$this->propName};
			return $propValue < 10;
		}
	}
	
	$items = new Items([
		new Item('item_name1', 100, 25), 
        new Item('item_name1', 300, 20), 
        new Item('item_name1', 115, 7), 
        new Item('item_name2', 290, 3), 
        new Item('item_name3', 30, 1500)
	]);
	
	$filter1 = new Filter1();
	$filter2 = new Filter2();
	$filter3 = new Filter3();
	
	$filteredItems = $filter1->applyFilter($items->getItems(), 'name');
	$filteredItems1 = $filter2->applyFilter($filteredItems, 'price');
	$filteredItems2 = $filter3->applyFilter($filteredItems, 'quantity');
	$filteredItems = array_merge($filteredItems1, $filteredItems2);
	
	$items = new Items($filteredItems);
	
	var_dump($items->getItems());