<?php
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
class Info extends Eloquent implements UserInterface, RemindableInterface
{
	use UserTrait, RemindableTrait;
	protected $table = 'info';

	protected $hidden=array('id','created_at');
}
