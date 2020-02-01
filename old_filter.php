<?php
    class Item
    {
        public $name;
        public $price;
        public $quantity;
        
        public function __construct($name, $price, $quantity) {
            $this->name = $name;
            $this->price = $price;
            $this->quantity = $quantity;
        }
    }
    
    class Items
    {
        private $items;
        private $itemProps;
    
        public function __construct($items)
        {
            $this->items = $items;
            $this->itemProps = array_keys(get_class_vars(Item::class));
        }
        
        /* Применение фильтра к коллекции */
        public function filterItems($filterExpr = '') {
            try {
	            $uniqueProp = $this->getUniqueProp($filterExpr);
				if (empty($uniqueProp)) {
					echo 'not unique prop';
					return null;
				}
				if (!in_array($uniqueProp, $this->itemProps)) {
					echo 'isn\'t exist prop';
					return null;
				}
				
				$filteredItems = [];
				foreach ($this->items as $item) {
					$$uniqueProp = $item->$uniqueProp;
					
					$expr = $this->convertFilterExpr($filterExpr, $uniqueProp);
					if (eval("return $expr;")) {
						$filteredItems[] = $item;		
					}
				}	
				return $filteredItems;
				
            } catch(ParseError $e) {
                echo 'error in filter expression\n';
            }
            
            return null;
        }
        
        /* Поиск уникального свойства в фильтре */
        private function getUniqueProp($filterExpr) {
	        $propsRegex = join('|', $this->itemProps);
			preg_match_all("/$propsRegex/", $filterExpr, $matches);
			
			$existProps = array_unique($matches[0]);
			$isUniqueProp = count($existProps) == 1;
			
			return $isUniqueProp ? $existProps[0] : null;
        }
        
        /* Преобразование исходной строки фильтра к строке с переменными */
        private function convertFilterExpr($filterExpr, $propName) {
	        $expr = preg_replace("/$propName/", "\$$propName", $filterExpr);
	        return $expr;
        }
    }
    
    $itemsList = [
        new Item('phones', 200, 25), 
        new Item('cameras', 300, 10), 
        new Item('cats', 550, 130), 
        new Item('rabbits', 700, 70), 
        new Item('cups', 30, 1500)
    ];
    $items = new Items($itemsList);
    
    $filteredItems = $items->filterItems("name[0] == 'c' && strlen(name) > 4");
    var_dump($filteredItems);
    
