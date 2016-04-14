<?php
session_start();

function __autoload($class_name) {
    require_once 'lib/' . strtolower($class_name) . '.class.php';
}