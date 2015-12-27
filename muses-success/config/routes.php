<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|         example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|        http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are two reserved routes:
|
|        $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|        $route['scaffolding_trigger'] = 'scaffolding';
|
| This route lets you set a "secret" word that will trigger the
| scaffolding feature for added security. Note: Scaffolding must be
| enabled in the controller in which you intend to use it.   The reserved
| routes must come before any wildcard or regular expression routes.
|
*/
$route['default_controller'] = "main";
$route['scaffolding_trigger'] = "";

$route['login-success'] = 'main/index';

// Browsing Listings

$route['browse'] = 'browse/index';
$route['browse/listing/:num/:any/:any'] = 'browse/listing';
$route['browse/createlist'] = 'browse/createlist';
$route['browse/view/:any'] = "novel/view";
$route['browse/rate/:num'] = "browse/rate";
$route['sitemap'] = 'sitemap/index';
$route['reviews'] = 'reviews/main';
$route['reviews/most-helpful'] = 'reviews/main';
$route['admin/cvs'] = 'admintasks/cvs';
// Static Pages

$route['faqs'] = 'pages/faqs';
$route['p/:any'] = 'pages/view';
$route['pages/:any'] = "pages/view";
/*
$route['import'] = 'admintasks/listingstochangeset';
$route['fix-index'] = 'admintasks/notinindex';
*/
// Contribution

$route['contribute'] = "contribute/index";
$route['contribute/add_comment'] = "contribute/add_comment";
$route['contribute/new_listing'] = "contribute/new_listing";
$route['contribute/queue'] = "contribute/queue";
/*
$route['contribute/link_request'] = "contribute/link_request";
*/
$route['contribute/update_listing'] = "contribute/update_listing";
$route['contribute/update_listing/:num'] = "contribute/update_listing";
$route['contribute/submit_review'] = "contribute/submit_review";
$route['contribute/submit_review/:num'] = "contribute/submit_review";
$route['contribute/reject_listing/:num'] = "contribute/reject_listing";
$route['contribute/approve_listing/:num'] = "contribute/approve_listing";
$route['contribute/top-contributors'] = "contribute/top_contributors";
$route['contribute/add_new_tag/:num'] = 'tag/suggest_tag';
$route['contribute/add_new_tag'] = 'tag/suggest_tag';

// Bookshelves

$route['bookshelf/:num'] = "bookshelf/index";
$route['bookshelf/:num/:any'] = "bookshelf/index";

// Recommendations

$route['recommendations'] = 'recommendations/main';
$route['recommendations/make'] = 'recommendations/make';
$route['recommendations/make/:num'] = 'recommendations/make';
/*
// Publications

$route['authorcp'] = "authorcp/main";
$route['authorcp/thumbnailreset/:num'] = 'authorcp/thumbnailreset';
$route['authorcp/thumb_admin'] = 'authorcp/thumb_admin';
*/
// User Accounts

$route['accounts'] = "accounts/index";
$route['accounts/process_login'] = "accounts/process_login";
$route['accounts/logout'] = "accounts/logout";
$route['accounts/editprofile'] = "accounts/editprofile";
$route['accounts/bookshelf'] = "bookshelf/user_bookshelf";
$route['accounts/lostpass'] = 'accounts/lostpass';
/*
$route['accounts/publications'] = "accounts/publications";
*/
$route['accounts/changepassword'] = "accounts/changepassword";
$route['accounts/login'] = "accounts/login";
$route['accounts/register'] = "accounts/register";
$route['accounts/passconf/:any'] = 'accounts/passconf';

// User Profiles

$route['report/review/:num'] = 'report/main';
$route['report/comment/:num'] = 'report/main';
$route['report/recommendation/:num/:num/:num/:num'] = 'report/main';
$route['whois/:num'] = 'profile/view';
$route['user/:num'] = 'profile/view';
$route['profile/view/:num'] = 'profile/view';
$route['profile/reviews/:num'] = 'profile/reviews';

// Listing Search

$route['search'] = "search/index";
$route['search/results'] = "search/results";
$route['search/results/:num/page/:num'] = "search/results";
$route['search/results/:num'] = "search/results";

// Private Messaging

$route['pms'] = "pms/index";
$route['pms/compose'] = "pms/compose";
$route['pms/compose/:num'] = "pms/compose";
$route['pms/compose/:num/:num'] = "pms/compose";
$route['pms/outbox'] = "pms/outbox";
$route['pms/sent'] = "pms/sent";
$route['pms/trash'] = "pms/trash";
$route['pms/view/:num'] = "pms/view";

// RSS Feeds

$route['rss'] = "rss/index";
$route['rss/reviews'] = "rss/reviews";
$route['rss/listing/:any'] = 'rss/listing';
$route['rss/bookshelf/:num'] = "rss/bookshelf";

// Change sets
/*
$route['admintasks/listingstochangeset'] = 'admintasks/listingstochangeset';
$route['admintasks/fixchangecount'] = 'admintasks/fixchangecount';
*/
$route['changeset/history/:num'] = 'changeset/history';
$route['changeset/revision/:num'] = 'changeset/revision';
$route['changeset/diff/:num/:num/:num'] = 'changeset/diff';
$route['changeset/compare'] = 'changeset/compare';
$route['changeset/user/:num'] = '';
$route['recent-changes'] = 'changeset/recent';
$route['recent-changes/page'] = 'changeset/recent';
$route['recent-changes/page/:num'] = 'changeset/recent';
$route['recent-changes/vote'] = 'changeset/vote';
/*
// Web Forums

$route['forums'] = "forums/main/index";
$route['forums/viewforum/:num'] = "forums/viewforum/index";
$route['forums/viewtopic/:num'] = "forums/viewtopic/index";
$route['forums/post/topic/:num'] = "forums/post/topic";
$route['forums/post/message/:num'] = "forums/post/message";
*/

$route['novel/reviews/:any/helpful/:num'] = 'novel/reviews';
$route['novel/reviews/:any/nothelpful/:num'] = 'novel/reviews';

// Tags
$route['tags'] = 'tag/browse';
$route['tags/mod-action/approve/:num'] = 'tag/mod_action';
$route['tags/mod-action/deny/:num'] = 'tag/mod_action';
$route['tags/special:denied-tags'] = 'tag/denied_tags';
$route['tags/special:pending-tags'] = 'tag/pending_tags';
$route['api/fetch_tags'] = 'tag/fetch_tags';
$route['tags/manage/:num/:any'] = 'tag/manage';
$route['tags/:any'] = 'tag/view';
$route['tags/:any/:num'] = 'tag/view';

// Administration Section

$route['admin/manage_reports'] = 'admin/manage_reports';
$route['admin/listings'] = 'admin/manage_listings';
$route['admin/manage_reports/:num'] = 'admin/manage_reports/view';

// Listing Pages

$route['browse/view/:any'] = 'novel/view';
$route['browse/add_library'] = 'browse/add_library';
$route['browse/reviews/:any'] = 'novel/reviews';
$route['browse/readers/:any'] = "novel/readers";
$route['reviews/view/:num'] = "reviews/view";
$route['reviews/view/:num/:any'] = "reviews/view";
$route['api/submit-rating'] = "browse/rate";
$route[':any/reviews/helpful/:num'] = 'novel/reviews';
$route[':any/reviews/nothelpful/:num'] = 'novel/reviews';
$route[':any/reviews/:num'] = 'reviews/view';
$route[':any/reviews'] = 'novel/reviews';
$route[':any/readers'] = 'novel/readers';
$route[':any/recommendations'] = 'novel/recommendations';
$route[':any'] = 'novel/view';

// API
/*
$route['api/xmlrpc'] = 'api/xml_rpc/main';
*/
$route['api/bookshelfs/addchapter'] = 'bookshelf/add_chapter';
$route['api/bookshelfs/minuschapter'] = 'bookshelf/minus_chapter';
/* End of file routes.php */
/* Location: ./system/application/config/routes.php */
