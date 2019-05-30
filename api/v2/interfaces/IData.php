<?php namespace V2\Interfaces;

interface IData
{
    const INPUT_DATA = [
      // USERS
        'player_id',
        'username',
        'email',
        'password',
        'names',
        'lastnames',
        'age',
        'avatar',
        'phone',
        'points',
        'balance',
        'send_email',

      // ACCOUNTS
        'time_zone',

      // TRANSFERS
        'concept',
        'username',
        'amount',
        'previous_balance',
        'current_balance',

      // VIDEOS
        'name',
        'link',
        'provider_logo',
        'question',
        'correct',
        'responses'
    ];
}
