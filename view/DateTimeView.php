<?php

class DateTimeView {


	public function show() {

		$timeString = date('r');

		return '<p>' . $timeString . '</p>';
	}
}