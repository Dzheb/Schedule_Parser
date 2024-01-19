<?php
require_once "Functions.php";
// исходные данные
$schedule = "пн-ср с 9.00 до 20.00";
$schedule = "вт с 10:00 до 20:00";
$schedule = "пн-вс с 10.20 до 20.00, перерыв с 12:00 до 13.00";
// 
// вывод расписания
$scheduleParsed = array_values(array_filter(scheduleToArray($schedule)));
echo "Расписание работы";
echo "<br>";
$formedScheduleArray = makeArraySchedule(initWorkDays(), $scheduleParsed);
echo "<br>";
foreach ($formedScheduleArray as $key => $value) {
  if ($value["begin"] != null) {
    echo $key . " : начало - " . $value["begin"] . " окончание - " . $value["end"];
    if ($value["break"]) {
      echo " , перерыв : начало - " . $value["break"]["begin"]
        . " окончание - " . $value["break"]["end"];
    }
    echo "<br>";
  }
}
?>
