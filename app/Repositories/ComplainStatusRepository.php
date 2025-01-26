<?php 
namespace App\Repositories;

class ComplainStatusRepository
{
    public static $ACCEPTED = 'accepted';
    public static $IGNORE = 'ignore';
    public static $SOLVED = 'solved';
    public static $INITIAL = 'initial';
    public static $RESEND = 're-send';
}