<?php
/*

    This file is part of the Muse's Success Web Fiction Directory script.

    Muse's Success Web Fiction Directory is free software: you can redistribute 
    it and/or modify it under the terms of the GNU Affero General Public License
    as published by the Free Software Foundation, either version 3 of the 
    License, or (at your option) any later version.

    Muse's Success Web Fiction Directory is distributed in the hope that it 
    will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty 
    of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero 
    General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with Muse's Success Web Fiction Directory. If not, see 
    <http://www.gnu.org/licenses/>.

*/

/**
 * Authorcp
 *
 * Class containing all the pages for the "author control panel."
 * 
 */
class Authorcp extends Controller {
	
	/**
	 * Authorcp
	 *
	 * Constructor, load's publications model and checks if the user is logged
	 * in - since all pages in this controller will require that the user is
	 * logged in - so may as well only do this once.	 
	 *
	 * @return void
	 */
	function Authorcp()
	{
		
		parent::Controller();
		
		$this->load->model('publications');

		if ($this->users->logged_in == false)
			redirect('accounts/login');
		
	}
	
	/**
	 * main
	 * 
	 * The main author control panel - displays a list of novels the user has
	 * written and links to various author-specific options.
	 *
	 * @return void
	 */
	function main()
	{
				
		$data = array('publications' => $this->publications->list_publications());

        $this->load->view('header');
        $this->load->view('authorcp/publications', $data);                           
        $this->load->view('footer');
		
	}

    /**
	 * thumbnailreset
	 *
	 * Allows the author to request that the thumbnail for their listing is
	 * reset - because thumbshots or our image is out of date.
	 * 
	 * @return void
	 */
	function thumbnailreset()
	{

		$novel_id = $this->uri->segment(3);
		
        $this->load->model('novels', 'thumbnails');
        
        if ($this->novels->novel_exists_id($novel_id) == true)
        {
        	$novel = $this->novels->get_novel($novel_id);
		
			$data = array();
			$data['listing'] = $novel;
			
			$this->load->library('validation');
			
			$rules = array('reset' => 'required');
			$fields = array();
			
			$month_ago = (time()-(((60*60)*24)*31));
			
			$this->validation->set_rules($rules);

			$date = $this->thumbnails->last_reset($novel_id);
			
			$data['too_soon'] = 0;
			$data['date_req'] = date("d/m/Y h:iA", $date);			
			if ($date < $month_ago)
			{
				
				$data['too_soon'] = 1;
				
			}
			
			if ($this->validation->run() == FALSE)
			{
				
		        $this->load->view('header');
		        $this->load->view('authorcp/reset_thumb', $data);                           
		        $this->load->view('footer');
	
			} else {
					
                $this->thumbnails->request($novel_id);
					
		        $this->load->view('header');
		        $this->load->view('authorcp/reset_thumb_success', $data);                           
		        $this->load->view('footer');				
					
			}

        }		        
        
	}
	
}

?>