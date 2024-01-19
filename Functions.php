<?php
// преобразование строки расписания в массив
function scheduleToArray($schedule)
{
  $res = explode(" ", $schedule);
  return $res;
}
// инициализация массива расписания
function initWorkDays()
{
  $dow = array("пн", "вт", "ср", "чт", "пт", "сб", "вс");
  $working_hours = array("begin" => null, "end" => null);
  for ($i = 0; $i < count($dow); $i++) {
    $days[$dow[$i]] = $working_hours;
  }
  return $days;
}
// запись перерыва
function insertBreak(array $dayArray, $index, $parsedSchedule)
{
  $beginBreak = parseTime($parsedSchedule[$index + 2]);
  $endBreak = parseTime($parsedSchedule[$index + 4]);
  $dayArray["break"] = array("begin" => $beginBreak, "end" => $endBreak);
  return $dayArray;
}
// запись начала и окончания работы в расписание
function makeArraySchedule(array $scheduleArray, $parsedSchedule)
{
  // нахождение периода работы
  if (strpos($parsedSchedule[0], '-')) {
    $beginDay = explode("-", $parsedSchedule[0])[0];
    $endDay = explode("-", $parsedSchedule[0])[1];
  } else {
    $beginDay = $parsedSchedule[0];
    $endDay = null;
  }
  // нахождение периода рабочего времени
  $beginHour = parseTime($parsedSchedule[array_search("с", $parsedSchedule, true) + 1]);
  $endHour = parseTime($parsedSchedule[array_search("до", $parsedSchedule, true) + 1]);
  // 
  $keys = array_keys($scheduleArray);
  for ($i = 0; $i < count($scheduleArray); $i++) {
    if (($keys[$i] == $beginDay && $endDay == null) || $endDay) {
      $scheduleArray[$keys[$i]]["begin"] = $beginHour;
      $scheduleArray[$keys[$i]]["end"] = $endHour;
      $indexBreak = array_search("перерыв", $parsedSchedule, true);
      // внести в расписание если есть перерыв
      if ($indexBreak)
        $scheduleArray[$keys[$i]] = insertBreak($scheduleArray[$keys[$i]], $indexBreak, $parsedSchedule);
    }
    if ($keys[$i] == $endDay || ($endDay == null && $keys[$i] == $beginDay )) {
      break;
    }
  }
  return $scheduleArray;
}
// преобразование формата времени
function parseTime($time_string)
{
  $time_hours = date_parse($time_string)['hour'];
  if (strlen($time_hours) == 1) $time_hours = "0" . $time_hours;
  $time_minutes =  date_parse($time_string)['minute'];
  if (strlen($time_minutes) == 1) $time_minutes = "0" . $time_minutes;
  return $time_hours . ":" . $time_minutes;
}
?>