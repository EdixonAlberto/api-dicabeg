<?php

namespace V2\Interfaces;

interface IBody
{
    const INPUT_DATA = [
        // USERS
        'username',
        'email',
        'balance',
        'names',
        'lastnames',
        'age',
        'avatar',
        'phone',
        'player_id',
        'send_email',
        'invite_code',
        'password',

        // ACCOUNTS
        'temporal_code',
        'time_zone',

        // TRANSFERS
        'concept',
        'username',
        'amount',

        // VIDEOS
        'name',
        'link',
        'size',
        'provider_logo',
        'question',
        'correct',
        'responses'
    ];
}
