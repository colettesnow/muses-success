<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Pages extends Controller {

        function Pages() {
                parent::Controller();
        }

        function view()
        {
	       	$page_slug = $this->uri->segment(2);
        	
        	$query = $this->db->get_where('pages', array('page_slug' => $page_slug));
        	
        	$page_data = $query->row();
        	
        	if ($query->num_rows() == 1)
        	{
        		$this->load->helper('markdownextra');        	
	        	
	        	$head = array();
	        	$head['page_title'] = $page_data->page_title;
	        	$head['breadcrumbs'] = array($page_data->page_title);
	        	$head['canonical'] = site_url('p/'.$page_data->page_slug);
        		
        		$page = array();
	        	$page['page_title'] = $head['page_title'];
	        	$page['content'] = markdown($page_data->page_content);
      	
	        	$this->load->view('header', $head);
	        	$this->load->view('page_template', $page);
	        	$this->load->view('footer');
        
        	} else {
        		show_404('p/'.$page_slug);	
        	}
        
        }
        
        function faqs()
        {
        	/* It really is not worth making a model for this just yet, at least not until I have an admin interface for it
        	 * which isn't a high priority as I am the only person really managing it. */
        	$this->load->helper('markdown');    
        	
        	$query = $this->db->query('SELECT * FROM `faqs_categories` ORDER BY `cat_order` ASC');
        	$faqs = array();
        	$i = 0;
        	foreach ($query->result() as $question_category)
        	{
        		++$i;
        		$faqs[$i] = array();
        		$faqs[$i]['id'] = $i;
        		$faqs[$i]['cat_name'] = $question_category->cat_name;
        		$faqs[$i]['questions'] = array();
        		$questions = $this->db->query('SELECT * FROM `faqs_questions` WHERE `question_category` = '.$question_category->cat_id.' ORDER BY `question_question` ASC');
        		$c = 0;
        		foreach ($questions->result() as $question)
        		{
        			++$c;
        			$faqs[$i]['questions'][$c] = array();
        			$faqs[$i]['questions'][$c]['id'] = $c;
        			$faqs[$i]['questions'][$c]['perma-id'] = $question->question_id;
        			$faqs[$i]['questions'][$c]['question'] = $question->question_question;
        			$faqs[$i]['questions'][$c]['answer'] = parse_markdown($question->question_answer);         			
        		}
        	}
        	
        	$head_data = array();
        	$head_data['page_title'] = 'Frequently Asked Questions';
        	$head_data['breadcrumbs'] = array('Frequently Asked Questions');
        	$page_data = array();
        	$page_data['faqs'] = &$faqs;	
        	
        	$this->load->view('header', $head_data);
        	$this->load->view('pages/faqs', $page_data);
        	$this->load->view('footer');
        	
        }
        
        function about()
        {

                $data['page_title'] = 'About Muse\'s Success';
                $data['content']    = '
                <p>Museâ€™s Successâ€™ mission is to create a catalog of each and every web based novel (or serial) available on the World Wide Web. In addition, we allow our visitors to review stories within our catalog, and also provide a community in which authors and readers of web based novels can interact and communicate.</p>
                <p><strong>Contact Us</strong></p>
                <p>We can be contacted at <a href="mailto:muses-success@sorrowfulunfounded.com">muses-success@sorrowfulunfounded.com</a>. If you want to submit a listing or review, please use the appropriate form. Submissions sent to this e-mail address will be ignored.</p>
                <p><strong>Credit for Idea</strong></p>
                <p>Original credit for the idea of a web novel listing site should go to <a href="http://pagesunbound.com/">Pages Unbound</a> and <a href="http://alexandraerin.com">Alexandra Erin</a>. For a time, Pages Unbound was announced to be closing and we created Museâ€™s Success to fill the void created in its absence. With the announcement that Alexandra Erin was to reopen Pages Unbound, we decided to go ahead anyway in an effort to give back to the community that has provided us with many hours of enjoyment.</p>
                <p><strong>Donations</strong></p>
                <p>This is just one of many ways which you can support Muse\'s Success. We are a free service and will always remain free but Muse\'s Success costs time and money to run. You can help offset these costs by making a donation.</p>
                <p><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypal.com/en_AU/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="">
<img alt="" border="0" src="https://www.payInfopal.com/en_AU/i/scr/pixel.gif" width="1" height="1">
</form>
</p>
<p><strong>Powering Muse\'s Success</strong></p>

<ul>
<li><strong>Scripting:</strong> Object-Orientated PHP5</li>
<li><strong>Data Storage:</strong> MySQL 5</li>
<li><strong>Frameworks:</strong> <a href="http://codeigniter.com">CodeIgniter</a>, <a href="http://jquery.com">JQuery</a></li>
<li><strong>Lines of Code (as of October 17th):</strong> 8,105 lines (not including CSS, JavaScript or the CodeIgniter core, its just the application directory)</li>
<li><strong>Hosted By:</strong> <a href="http://bihira.com">Bihira.com</a></li>
</ul>
                ';

                $this->load->view('header', array('page_title' => 'About Us'));
                $this->load->view('page_template', $data);
                $this->load->view('footer');
        }

        function linkus()
        {
                $data['page_title'] = 'Link to Muse\'s Success';
                $data['content'] = '<p>One of the best ways you can help Muse\'s Success grow is to link to us! Currently, this can be done in  two ways, as a plain text link or a button.</p>
<p><strong>Plain Text Link:</strong></p>
<p><a href="http://muses-success.info/" title="Muse\'s Success - Web Fiction Listings and Reviews">Muse\'s Success</a></p>
<textarea cols="60" rows="5"><a href="http://muses-success.info/" title="Muse\'s Success - Web Fiction Listings and Reviews">Muse\'s Success</a></textarea>
<p><strong>Image Button:</strong></p>
<p><a href="http://muses-success.info/" title="Muse\'s Success - Web Fiction Listings and Reviews"><img src="http://static.sorrowfulunfounded.com/muses-success/images/muses-success-btn.jpg" alt="Muse\'s Success" /></a></p>
<textarea cols="60" rows="5"><a href="http://muses-success.info/" title="Muse\'s Success - Web Fiction Listings and Reviews"><img src="http://static.sorrowfulunfounded.com/muses-success/images/muses-success-btn.jpg" alt="Muse\'s Success" /></a></textarea>';
                $this->load->view('header', array('page_title' => 'Link to Us'));
                $this->load->view('page_template', $data);
                $this->load->view('footer');
        }
        
        function guidelines()
        {
                $this->load->view('header', array('page_title' => 'Submission Guidelines'));
                $this->load->view('pages/guidelines');
                $this->load->view('footer');                    
        }

        function faqs_old()
        {
                $data['page_title'] = 'Frequently Asked Questions';
                $data['content'] = '';

                $listings = array(
                        'How do I get my web fiction listed?' => 'You will need to use the appropriate form under <a href="'.site_url('contribute/').'">Contribute</a> or click <a href="'.site_url('contribute/new_listing/').'">here</a>. An account is required.',
                        'Why wasn\'t my web fiction accepted?' => 'It probably didn\'t meet our minimum submission guidelines which are clearly presented when you view the submission form.',
                        'Do I have to be the author to make a submission?' => 'No. We do not require that you are an author of a listing to submit it.',
                        'If you don\'t list pornography, why list Tales of MU?' => 'Tales of MU status is questionable, but it deserves recognition as one of the first web novels to be successful. The level of sexual content displayed in Tales of MU is at the limit we will list, unless the author has a good explanation or artistic purpose.'
                );

                $accounts = array(
                        'How do I register?' => 'You can register using our <a href="'.site_url('accounts/register/').'">Register</a> page.',
                        'I forgot my password! How do I recover it?' => 'You can recover your password by going <a href="'.site_url('accounts/lostpass').'">here</a> and entering in the email address currently associated with your account. Remember, to keep your email addrees up-to-date.',
                        'What are the user levels?' => 'The user levels along with requirements are as follows:<ul>
                        <li><strong>-2: Banned:</strong> This user has inexcusably violated the rules and can no longer access the site.</li>
                        <li><strong>-1: Suspended:</strong> This user has broken either two or more minor rules, or one or more major rules and is unable to post in the forums or contribute, but can still browse the site.</li>
                        <li><strong>1: Reader:</strong> This is just your everyday, run of the mill user. This user has less then 15 contribution points.</li>
                        <li><strong>2: Avid Reader:</strong> This user has between 15 and 100 contribution points.</li>
                        <li><strong>3: Book-worm:</strong> This user has between 100 and 300 contribution points.</li>
                        <li><strong>4: Book-lover:</strong> This user between 300 and 600 contribution points.</li>
                        <li><strong>5: Book-addict:</strong> This user has between 600 and 1000 contribution points.</li>
                        <li><strong>6: God:</strong> This user has more than 1000 contribution points. We love them!</li>
                        <li><strong>7: VIP:</strong> This user is recognised for their contributions to the web fiction community.</li>
                        <li><strong>8: Moderator:</strong> This user is a member of the staff and is responsible for keeping the forums running smoothly.</li>
                        <li><strong>9: Editor:</strong> This user deals with the contribution queue of both reviews and submissions of work. This user may also moderate the forums, but this is not their main responsibility.</li>
                        <li><strong>10: Administrator:</strong> This user has full control over every aspect of the site.</li>
                        </ul>',
                        'What are contribution points? How do I gain them?' => 'Each contribution you make to Muse\'s Success will gain you points. They are used to determine your user-level which in-turn determines what abilities you have, and affects the weight of the ratings you give.',
                        'How many points do I get for X action?' => 'Currently, the points are rewarded as follows:<ul><li>New Listings (without synopsis): 5 points</li><li>New Listings (with synopsis): 10-15 points (editor discretion)</li><li>Update to Existing Listing: 2.5 points</li><li>New Synopsis: 8 points</li><li>Reviews: 10 points</li><li>Rating: 0 points</li></ul><p>We reserve the right to alter these at anytime.</p>'                        );

                $forums = array(
                        'What are the rules?' => 'Our rules are outlined on our <a href="'.site_url('pages/forumrules').'">forum rules</a> page.',
                        'How do I get an avatar?' => 'We use <a href="http://gravatar.com">Gravatar.com</a> for avatars. Simply sign up there with the email address currently associated with your Muse\'s Success account, upload an avatar, and it should appear on the forums alongside any posts you make.',
                        'How can I format my posts?' => 'You can use BBCode. You can use the following BBCode:<br /><br /><strong>Bold:</strong> [b] marks the beginning of <strong>bold</strong> text, [/b] ends it.<br /><strong>Italic:</strong> [i] marks the beginning of <em>italic</em> text, [/i] ends it.<br /><strong>Underline:</strong> [u] marks the beginning of <u>underlined</u> text, [/u] ends it.<br /><br />Please note that if these features are abused in any way, they will be removed.',
                        'How can I track updates to my posts?' => 'Every topic on Muse\'s Success has an associated RSS feed that contains the 10 most recent replies to a topic. Simply subscribe with your favourite RSS reader.',
                        'How can I change my signature?' => 'Click on <a href="'.site_url('accounts/').'">Manage Account</a> at the top right of your screen, then on that page click <a href="'.site_url('accounts/editprofile/').'">Edit Profile</a>. From there, you will be able to edit your signature amoung other settings.',
                        'Can I be a moderator?' => 'If you had to ask, the answer is no. When we need moderators, we will let you know.',
                        'Who are the current moderators?' => '<a href="'.site_url('profile/view/1/').'">Colette</a>, <a href="'.site_url('profile/view/3/').'">Darkthorn</a>'
                        );

                $data['content'] .= '<h3>Listing Questions</h3>';


                foreach ($listings as $question => $answer)
                {
                        $data['content'] .= '<p><strong>'.$question.'</strong><br />'.$answer.'</p>';
                }

                $data['content'] .= '<h3>Account Questions</h3>';

                foreach ($accounts as $question => $answer)
                {
                        $data['content'] .= '<p><strong>'.$question.'</strong><br />'.$answer.'</p>';
                }

                $data['content'] .= '<h3>Forum Questions</h3>';

                foreach ($forums as $question => $answer)
                {
                        $data['content'] .= '<p><strong>'.$question.'</strong><br />'.$answer.'</p>';
                }

                $this->load->view('header', array('page_title' => 'Frequently Asked Questions'));
                $this->load->view('page_template', $data);
                $this->load->view('footer');
        }

        function links()
        {

                $data['page_title'] = 'Links';
                $data['content'] = '
                <p>These are just some of the sites we think you should check out. :)</p>
                <p><strong>Web Fiction Listing and Review Sites</strong></p>
                <ul>
                        <li><a href="http://webfictionguide.com">Web Fiction Guide</a> - an editor-centric review site</li>
                </ul>

                <p><strong>Web Fiction Related Blogs</strong></p>
                <ul>
                        <li><a href="http://novelr.com">Novelr.com</a> - the definitive blook blog</li>
                        <li><a href="http://blog.blogfiction.org/">Blog Fiction</a> - the definitive blook blog</li>
                </ul>

                <p><strong>Hosting</strong></p>
                <ul>
                        <li><a href="http://digitalnovelists.com/">Digital Novelists</a> - application based, selective - using Drupal</li>
                        <li><a href="http://wordpress.com/">WordPress</a> - free hosting using WordPress Blogging platform</li>
                        <li><a href="http://bihira.com/">Bihira.com</a></li>
                </ul>

                <p>Do you know of any other sites that deserve to be on this page? If you do, <a href="mailto:muses-success@sorrowfulunfounded.com">contact us</a>.</p>
                ';
                $this->load->view('header', array('page_title' => 'Community Links'));
                $this->load->view('page_template', $data);
                $this->load->view('footer');
        }

        function forumrules()
        {
                $data['page_title'] = 'Forum Rules';
                $data['content'] = '<p>By posting, you are asserting that:</p>
<ul>
<li>You are 13 years of age or older.</li>
<li>You agree to abide by the rules on this page.</li>
</ul>

<p>You cannot:</p>

<ul>
<li>Break United States or Australian Law</li>
<li>Flame</li>
<li>Troll</li>
<li>Flood</li>
<li>Discriminate</li>
<li>Advertise a product or service</li>
<li>Harass or insult other users</li>
<li>Threaten other users</li>
</ul>

<p>You can:</p>

<ul>
<li>Link to adult websites when clearly marked</li>
<li>Request critique of your website in a social setting</li>
<li>Swear within reason</li>
<li>Do anything legal that isn\'t otherwise prohibited</li>
</ul>

<p>You must:</p>

<ul>
<li>Use proper spelling and grammar.</li>
<li>Attempt to keep on topic.</li>
<li>Post topics on the correct forum. If the topic doesn\'t fit, use Chit Chat</li>
</ul>

<p>Penalties for breaking these rules include:</p>

<ul>
        <li>Loss of Contribution Points</li>
        <li>Temporary Suspension</li>
        <li>Permanent Banning</li>
</ul>

<p>We reserve the right to delete any post for any reason at any time at our sole discretion.</p>

<p>Basically, just use common sense and you\'ll be fine.</p>

<p>These rules are subject to change without notice.</p>';
                $this->load->view('header', array('page_title' => 'Forum Rules'));
                $this->load->view('page_template', $data);
                $this->load->view('footer');
        }

        function copyright()
        {
                $data['page_title'] = 'Copyright Policy';
                $data['content']    = '
                        <p><strong>Listings</strong> will be released under the terms of the <a href="http://creativecommons.org/licenses/by-nc-sa/2.5/au/">Creative Commons Attribution-Noncommercial-Share Alike 2.5 Australia License</a>.</p>

                        <p>We are not responsible for the content of the fiction we list.</p>

                        <p><strong>Attribution</strong> should be in the form of a link with reasonable visibility to the listing page in question.</p>

                        <p><strong>Reviews</strong> remain under the sole copyright and ownership of the reviewer. Muse\'s Success claims no ownership over the content of reviews posted to our service nor do they in any way represent the opinion of Muse\'s Success. Those wishing to use or quote a review should contact the reviewer for authourization.</p>

                ';

                $this->load->view('header', array('page_title' => 'Copyright Policy'));
                $this->load->view('page_template', $data);
                $this->load->view('footer');
        }

        function privacy()
        {
                $data['page_title'] = 'Privacy Policy for Muse\'s Success';
                $data['content']    = '
    <p>The privacy of our visitors to Muse\'s Success is important to us.</p>

    <p>At Muse\'s Success, we recognize that privacy of your personal information is important. Here is information on what types of personal information we receive and collect when you use visit Muse\'s Success, and how we safeguard your information.  We never sell your personal information to third parties.</p>

    <p><strong>Log Files</strong></p>
    <p>As with most other websites, we collect and use the data contained in log files.  The information in the log files include  your IP (internet protocol) address, your ISP (internet service provider, such as AOL or Shaw Cable), the browser you used to visit our site (such as Internet Explorer or Firefox), the time you visited our site and which pages you visited throughout our site.</p>

    <p><strong>Cookies and Web Beacons</strong></p>
    <p>We do use cookies to store information, such as your personal preferences when you visit our site.  This could include only showing you a popup once in your visit, or the ability to login to some of our features, such as forums.</p>

    <p>We also use third party advertisements on Muse\'s Success to support our site.  Some of these advertisers may use technology such as cookies and web beacons when they advertise on our site, which will also send these advertisers (such as Google through the Google AdSense program) information including your IP address, your ISP , the browser you used to visit our site, and in some cases, whether you have Flash installed.  This is generally used for geotargeting purposes (showing New York real estate ads to someone in New York, for example) or showing certain ads based on specific sites visited (such as showing cooking ads to someone who frequents cooking sites).</p>

    <p>You can chose to disable or selectively turn off our cookies or third-party cookies in your browser settings, or by managing preferences in programs such as Norton Internet Security.  However, this can affect how you are able to interact with our site as well as other websites.  This could include the inability to login to services or programs, such as logging into forums or accounts.</p>

    <p><small>AdSense Privacy Policy Provided by <a href="http://www.JenSense.com">JenSense</a></small></p>';

                $this->load->view('header', array('page_title' => 'Privacy Policy'));
                $this->load->view('page_template', $data);
                $this->load->view('footer');
        }

        function terms_of_use()
        {

                $data['page_title'] = 'Terms and Conditions';
                $data['content'] = '<p>Welcome to our website. If you continue to browse and use this website you are agreeing to comply with and be bound by the following terms and conditions of use, which together with our privacy policy govern Muse\'s Success\' relationship with you in relation to this website.</p>
<p>The term â€œusâ€� or â€œweâ€� refers to Muse\'s Success. The term â€œyouâ€� refers to the user or viewer of our website.</p>
<p>The use of this website is subject to the following terms of use:</p>
<ul>
        <li>The content of the pages of this website is for your general information and use only. It is subject to change without notice.</li>
        <li>Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness or suitability of the information and materials found or offered on this website for any particular purpose. You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.</li>
        <li>Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. It shall be your own responsibility to ensure that any products, services or information available through this website meet your specific requirements.</li>
        <li>This website contains material which is owned by or licensed to us. This material includes, but is not limited to, the design, layout, look, appearance and graphics. Reproduction is prohibited other than in accordance with the copyright notice, which forms part of these terms and conditions.</li>
        <li>All trademarks reproduced in this website, which are not the property of, or licensed to the operator, are acknowledged on the website.</li>
        <li>Unauthorised use of this website may give to a claim for damages and/or be a criminal offence.</li>
        <li>From time to time this website may also include links to other websites. These links are provided for your convenience to provide further information. They do not signify that we endorse the website(s). We have no responsibility for the content of the linked website(s).</li>
        <li>Your use of this website and any dispute arising out of such use of the website is subject to the laws of Australia and the United States of America.</li>
</ul>
';
                $this->load->view('header', array('page_title' => 'Terms and Conditions'));
                $this->load->view('page_template', $data);
                $this->load->view('footer');
        }

}

