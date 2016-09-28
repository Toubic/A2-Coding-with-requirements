<?php

class DateTimeView {


	public function show() {

        date_default_timezone_set('CET');

		$timeString = date('l').", the ".date('jS \of F Y').", The time is ".date('H:i:s');

		return '<p>' . $timeString . '</p>';
	}
}