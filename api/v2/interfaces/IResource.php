<?php

namespace V2\Interfaces;

interface IResource
{
   const USERS_COLUMNS = [
      'user_id',
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
      'money',
      'create_date',
      'update_date'
   ];

   const ACCOUNTS_COLUMNS = [
      'temporal_code',
      'invite_code',
      'registration_code',
      'time_zone'
   ];

   const REFERRALS_COLUMNS = [
      'referred_id',
      'create_date'
   ];

   const HISTORY_COLUMNS = [
      'history_id',
      'user_id',
      'video_id',
      'total_views',
      'update_date'
   ];

   const VIDEOS_COLUMNS = [
      'video_id',
      'name',
      'link',
      'provider_logo',
      'question',
      'correct',
      'responses',
      'total_views',
      'create_date',
      'update_date'
   ];
}
