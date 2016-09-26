<?php

class DateTimeView {


	public function show() {

		$timeString = date('l').", the ".date('jS \of F Y').", The time is ".date('h:i:s');

		return '<p>' . $timeString . '</p>';
	}
}