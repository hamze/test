<?php

function to_english_numbers( $string ) {
	$persinaDigits1 = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
	$persinaDigits2 = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
	$allPersianDigits = array_merge($persinaDigits1, $persinaDigits2);
	$replaces = array_merge(range(0, 9), range(0, 9));

    return str_replace($allPersianDigits, $replaces , $string);
}