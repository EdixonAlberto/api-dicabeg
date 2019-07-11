<?php

namespace V2\Interfaces;

interface IResource
{
   const USERS_COLUMNS = [
      'user_id',
      'activated',
      'username',
      'email',
      'balance',
      'names',
      'lastnames',
      'age',
      'avatar',
      'phone',
      'player_id',
      'invite_code',
      'password',
      'create_date',
      'update_date'
   ];

   const ACCOUNTS_COLUMNS = [
      'temporal_code',
      'referred_id',
      'time_zone'
   ];

   const TRANSFERS_COLUMNS = [
      'user_id',
      'transfer_code',
      'concept',
      'username',
      'amount',
      'previous_balance',
      'current_balance',
      'create_date'
   ];

   const REFERREDS_COLUMNS = [
      'user_id',
      'referred_id',
      'create_date'
   ];

   const HISTORY_COLUMNS = [
      'history_id',
      'user_id',
      'video',
      'total_views',
      'update_date'
   ];

   const VIDEOS_COLUMNS = [
      'video_id',
      'name',
      'link',
      'size',
      'provider_logo',
      'question',
      'correct',
      'responses',
      'total_views',
      'create_date',
      'update_date'
   ];
}
