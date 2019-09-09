<?php

/*
|--------------------------------------------------------------------------
| Genres
|--------------------------------------------------------------------------
|
| Genres are used to categorize novels in our database. The following config
| entry is an array of genres.
|
*/
$config['recaptcha_secret_key'] = '';
$config['recaptcha_site_key'] = '';
$config['listing_discussion_forum'] = false;
$config['lock_all_listings'] = false;
$config['static_resources_url'] = 'http://static.sorrowfulunfounded.com/muses-success/';

$config['genres'] = array(
        'Adventure',
        'Angst',
        'Crime',
        'Drama',
        'Family',
        'Fantasy',
        'Friendship',
        'General',
        'Horror',
        'Humor',
        'Hurt/Comfort',
        'Mystery',
        'Parody',
        'Romance',
        'Sci-Fi',
        'Superhero',
        'Spiritual',
        'Supernatural',
        'Suspense',
        'Tragedy',
        'Western');


/*
|--------------------------------------------------------------------------
| Mature Content
|--------------------------------------------------------------------------
|
| These can be used as guide by readers.
|
*/

$config['mature_content'] = array(
        'Coarse Language',
        'Occassional Coarse Language',
        'Frequent Coarse Language',
        'Sex Scenes',
        'Occassional Sex Scenes',
        'Frequent Sex Scenes',
        'Some Violence',
        'Frequent Violence',
        'Graphic Violence'
        );


/*
|--------------------------------------------------------------------------
| Update Schedules
|--------------------------------------------------------------------------
|
| Update schedules allows readers to know how often to expect an update.
|
*/        
        
$config['update_schedules'] = array(
		'Daily', 
		'Almost Daily', 
		'Every Few Days', 
    	'Weekly', 
    	'Fortnightly', 
    	'Monthly', 
    	'Quarterly', 
    	'Bi-Annually', 
    	'Annually', 
    	'Hiatus', 
    	'Almost Never', 
    	'No Longer Updated', 
    	'Completed'
    );
