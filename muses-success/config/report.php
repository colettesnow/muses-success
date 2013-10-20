<?php

$config['report_types'] = array(
	'listing' => 'Listing',
	'changeset' => 'Changeset',
	'review' => 'Review',
	'post' => 'Forum Post',
	'recommendation' => 'Similar Recommendation',
	'comment' => 'Comment',
	'user' => 'User Profile'
);

$config['report_reasons'] = array(); 
$config['report_reasons']['listing'] = array(
	0 => array('Broken Link', 'This listing either no longer exists or has moved. If you know where the web fiction has moved, please edit it\'s listing instead.'),
	1 => array('Erotica', 'This listing is erotica. It has either changed direction since acceptance or should never have been accepted in the first place.'),
	2 => array('Vandalism', 'This listing has been vandalised. Requires investigation and cleanup. If possible rather then reporting the entire listing, report the changeset in which the vandalism occured.'),								
	3 => array('Fan-fiction', 'This listing is fan fiction, not original fiction.')
);

$config['report_reasons']['changeset'] = array(
	0 => array('Plagiarism', 'This changeset add\'s a synopsis from a third party source but does not cite it\'s source.'),
	1 => array('Vandalism', 'This changeset is destructive.'),
	2 => array('Affiliate Link', 'This changeset either adds or replaces a purchase url with an <dfn title="Link to an online store that contains information the store can use to provide compensation to the creator of the link.">affiliate</a> link.')
);

$config['report_reasons']['review'] = array(
	0 => array('Author Bashing', 'Merely an excuse to insult the author.'),
	1 => array('Offensive', 'Sexually explicit/racist/hate speech and threats.'),
	2 => array('Foul Language or Censor Bypassing', 'Contains swearing or otherwise coarse language, or bypasses the automatic censor.'),
	3 => array('SPAM', 'Not a review at all. Stupid, pointless, annoying.'),
	4 => array('Self Promotion', 'Author is reviewing their own work.')
);

$config['report_reasons']['post'] = array(
	0 => array('Offensive', 'Sexually explicit/racist/hate speech and threats'),
	1 => array('Censor Bypassing', 'Not completely obscuring offensive/banned words'),
	3 => array('Trolling', 'Intending solely to annoy and/or offend other posters'),
	4 => array('Flaming', 'Clear insults of other forum users'),
	5 => array('Advertising', 'Advertising Web Sites, Other Boards, For Sale/Rent/Trade'),
	6 => array('Illegal Activities', 'OM/Warez/MP3/Cracks Begging and/or Providing'),
	7 => array('Spoiler with no Warning', 'Revealing critical plot details with no warning'),
	8 => array('Disruptive Posting', 'ALL CAPS, large blank posts, multiple hard-to-read posts, mass bumping, etc.'),			
	9 => array('Off-Topic Posting', 'Topics outside of the forum description')
);

$config['report_reasons']['recommendation'] = array(
	0 => array('Sequel or Prequel', 'Recommends a sequel or prequel of the web fiction in question. Useless.'),
	1 => array('Offensive', 'Sexually explicit/racist/hate speech and threats'),
	2 => array('Spoiler', 'Good recommendation but reveals a spoiler while explaining the similarity.'),
	3 => array('SPAM', 'Not a review at all. Stupid, pointless, annoying.')
	);
	
$config['report_reasons']['comment'] = array(
	0 => array('Offensive', 'Sexually explicit/racist/hate speech and threats'),
	1 => array('Censor Bypassing', 'Not completely obscuring offensive/banned words'),
	3 => array('Trolling', 'Intending solely to annoy and/or offend other commenters'),
	4 => array('Flaming', 'Clear insults of other users'),
	5 => array('Advertising', 'Advertising Web Sites, Other Forums, For Sale/Rent/Trade'),
	6 => array('Illegal Activities', 'OM/Warez/MP3/Cracks Begging and/or Providing'),
	7 => array('Spoiler with no Warning', 'Revealing critical plot details with no warning'),
	8 => array('Disruptive Posting', 'ALL CAPS, large blank posts, multiple hard-to-read posts, mass bumping, etc.'),			
	9 => array('Off-Topic Posting', 'Comments unrelated to the listing.')
);

$config['report_reasons']['user'] = array(
	0 => array('Offensive', 'Sexually explicit/racist/hate speech and threats in profile.'),
	2 => array('SPAM', 'Not a user at all. Stupid, pointless, annoying.')
	);

?>
