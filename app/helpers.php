<?php

function formatPhone(string $phone, string $prefix)
{
    return $prefix . $phone;
}

function formatDate($date, string $format = 'l jS F, Y, h:i a')
{
    return date($format, strtotime($date));
}
