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
			return $propValue[0] == 'c' && strlen($propValue) > 4;
		}
	}
	
	$items = new Items([
		new Item('phones', 200, 25), 
        new Item('cameras', 300, 10), 
        new Item('cats', 550, 130), 
        new Item('rabbits', 700, 70), 
        new Item('cups', 30, 1500)
	]);
	
	$filter1 = new Filter1();
	
	$filteredItems = $filter1->applyFilter($items->getItems(), 'name');
	var_dump($filteredItems);