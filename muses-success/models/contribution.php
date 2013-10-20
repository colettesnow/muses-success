<?php

class Contribution extends Model {
	
	function add_points($user_id, $reason, $amount, $object_type, $object_id)
	{
		$user_data = $this->users->get_user_info($user_id);
		if ($user_data != false)
		{
			$points = $user_data['contrib_points'] + $amount;
			$this->users->update_profile_field('contrib_points', $points);
			$this->users->update_profile_commit($user_id);

			$record['record_amount'] = $amount;
			$record['record_amount_result'] = $points;
			$record['record_type'] = 'addition';
			$record['record_reason'] = $reason;
			$record['record_object_type'] = $object_type;
			$record['record_object_id'] = $object_id;
			$record['record_user_id'] = $user_id;
			$this->db->insert('contrib_point_record', $record);
			return true;
		} else {
			return false;
		}
	}

	function subtract_points($user_id, $reason, $amount, $object_type, $object_id)
	{
		$user_data = $this->users->get_user_info($user_id);
		if ($user_data != false)
		{
			$points = $user_data['contrib_points'] - $amount;
			$this->users->update_profile_field('contrib_points', $points);
			$this->users->update_profile_commit($user_id);

			$record['record_amount'] = $amount;
			$record['record_amount_result'] = $points;
			$record['record_type'] = 'subtraction';
			$record['record_reason'] = $reason;
			$record['record_object_type'] = $object_type;
			$record['record_object_id'] = $object_id;
			$record['record_user_id'] = $user_id;			
			$this->db->insert('contrib_point_record', $record);
			return true;
		} else {
			return false;
		}
	}	

}