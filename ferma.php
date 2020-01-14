<?php

//////////////
echo '<p>';
		echo ' <h1 align="center"> Добро пожаловать к нам на ферму  </h1>';
		echo '</p>';


class Data
{
	public $name;
	public $results;
	function __construct($name,$results)
	{
		echo '<p>';
		echo '<br> ФЕРМА - ' . $name . ':';
		echo '</p>';
		foreach ($results as $subject=>$item) {
			echo '<br>' . $subject . ': ' . $item;
	}
echo '<br><hr>';
	}
}

$ferma1= new Data('Список коров',array('0001'=>8 . ' литров молока','0100'=>8 . ' литров молока', '0002'=>10 . ' литров молока', '0003'=>11 . ' литров молока','0004'=>8 . ' литров молока','0005'=>8 . ' литров молока','0006'=>9 . ' литров молока','0007'=>8 . ' литров молока','0008'=>8 . ' литров молока','0009'=>10 . ' литров молока'));

$ferma2= new Data('Список кур',array('0010'=>1 . ' яйцо за 1 кладку','0011'=>1 . ' яйцо за 1 кладку', '0020'=>0 . ' яйцо за 1 кладку', '0030'=>1 . ' яйцо за 1 кладку','0040'=>0 . ' яйцо за 1 кладку','0050'=>1 . ' яйцо за 1 кладку','0060'=>1 . ' яйцо за 1 кладку'));




///////////////////


class Farm { //класс фермы
    private $name;
    private $storage;
    private $animals = [];
    public function __construct(string $name, Storage $storage)
    {
        $this->name = $name;
        $this->storage = $storage;
    }
    public function returnMilk()
    {
        return $this->storage->howMuchMilk();
    }
    public function returnEggs()
    {
        return $this->storage->howMuchEggs();
    }
    public function addAnimal(Animal $animal)
    {
        $this->animals[] = $animal; //добавляем животное в массив
    }
    public function collectProducts() //сбор продукции
    {
        foreach ($this->animals as $animal)
        {
            if ($animal instanceOf CanGiveMilk) { //если относится к молокодающим, то сбор молока
                $milkLiters = $animal->getMilk();
                $this->storage->addMilk($milkLiters);
            }
            if ($animal instanceOf CanGiveEggs) { //с яйценесущих яйца
                $eggsCount = $animal->getEggs();
                $this->storage->addEggs($eggsCount);
            }
        }
    }
}
interface Animal {
}
interface CanGiveMilk {
    public function getMilk(): int;
}
interface CanGiveEggs {
    public function getEggs(): int;
}
class Cow implements Animal, CanGiveMilk {
    public $id;
    public function __construct()
    {
        $this->id = substr(md5(rand()), 0, 6); //получаем случаный id длинною в 6 символов
    }
    public function getMilk(): int
    {
        return rand(8, 12); //выдает 8-12 литров молока
    }
}
class Hen implements Animal, CanGiveEggs {
    public $id;
    public function __construct()
    {
        $this->id = substr(md5(rand()), 0, 6); //получаем случаный id длинною в 6 символов
    }
    public function getEggs(): int
    {
        return rand(0, 1); //выдает 0-1 яичек
    }
}
interface Storage { //хранилище
    public function addMilk(int $liters);
    public function addEggs(int $eggsCount);
    public function getFreeSpaceForMilk(): int;
    public function getFreeSpaceForEggs(): int;
    public function howMuchMilk(): int;
    public function howMuchEggs(): int;
}
class Barn implements Storage { //амбар
    private $milkLiters = 0;
    private $eggsCount = 0;
    private $milkLimit = 0;
    private $eggsLimit = 0;
    public function __construct(int $milkLimit, int $eggsLimit)
    {
        $this->milkLimit = $milkLimit; //указываем максимальную вместимость по молоку
        $this->eggsLimit = $eggsLimit; //указываем максимальную вместимость по яйцам
    }
    public function addMilk(int $liters)
    {
        $freeSpace = $this->getFreeSpaceForMilk();
        if ($freeSpace === 0) { //абмар заполнен, места нет
          return;
        }
        if ($freeSpace < $liters) { //дозаполняем амбар, насколько хватает места
          $this->milkLiters = $this->milkLimit;
          return;
        }
        $this->milkLiters += $liters; //льем все молоко, что надоили
    }
    public function addEggs(int $eggsCount) //для яиц аналогичные действия
    {
        $freeSpace = $this->getFreeSpaceForEggs();
        if ($freeSpace === 0) {
            return;
        }
        if ($freeSpace < $eggsCount) {
          $this->eggsCount = $this->eggsLimit;
          return;
        }
        $this->eggsCount += $eggsCount;
    }
    public function getFreeSpaceForMilk(): int //считаем свободное место молоко
    {
        return $this->milkLimit - $this->milkLiters;
    }
    public function getFreeSpaceForEggs(): int //считаем свободное место яйца
    {
        return $this->eggsLimit - $this->eggsCount;
    }
    public function howMuchMilk(): int
    {
        return $this->milkLiters;
    }
    public function howMuchEggs(): int
    {
        return $this->eggsCount;
    }
}

$barn = new Barn($milkLimit = 300, $eggsLimit = 500); //создаем амбар вместимостью 300 литров молока и 500 яичек
$myFarm = new Farm('MyFirstFarm', $barn);
for ($i=0;$i<40;$i++) {
    $myFarm->addAnimal(new Hen()); //сажаем в ферму курочек
}
for ($i=0;$i<10;$i++) {
    $myFarm->addAnimal(new Cow()); //и коров
}


echo '<p>';
		echo '<br> ФЕРМА - Продукция (Сбор продукции) ';
		echo '</p>';
		
$myFarm->collectProducts(); //собираем продукты
echo '<H4>';
echo 'Молока надоено(литров) '.$myFarm->returnMilk().'<br>'; //выводим результат сбора
echo 'Яиц собрано(штук) '.$myFarm->returnEggs().'<br>';
echo '</H4>';