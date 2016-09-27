<?php

class Hop_forum_check_ext {

    var $name       = 'Hop Forum Check';
    var $version        = '1.0';
    var $description    = 'Check forum post before saving it.';
    var $settings_exist = 'y';
    var $docs_url       = ''; // 'https://ellislab.com/expressionengine/user-guide/';

    var $settings       = array();

	/**
	 * Constructor
	 *
	 * @param   mixed   Settings array or empty string if none exist.
	 */
	function __construct($settings = '')
	{
		$this->settings = $settings;
	}

	function activate_extension()
	{
		$this->settings = array(
			'words_list'   => '',
		);

		$data = array(
			'class'     => __CLASS__,
			'method'    => 'check_post',
			'hook'      => 'forum_submit_post_start',
			'settings'  => serialize($this->settings),
			'priority'  => 10,
			'version'   => $this->version,
			'enabled'   => 'y'
		);

		ee()->db->insert('extensions', $data);
	}

//	function update_extension($current = '')
//	{
//		if ($current == '' OR $current == $this->version)
//		{
//			return FALSE;
//		}
//
//		if ($current < '1.0')
//		{
//			// Update to version 1.0
//		}
//
//		ee()->db->where('class', __CLASS__);
//		ee()->db->update(
//					'extensions',
//					array('version' => $this->version)
//		);
//	}

	function disable_extension()
	{
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	function settings()
	{
		$settings = array();

		$settings['words_list'] = array('t', array('rows' => '10'), '');

		return $settings;
	}

	function check_post($forum_core)
	{
		$body = ee()->input->get_post('body');

		$words = explode(PHP_EOL, $this->settings['words_list']);

		foreach ($words as $word)
		{
			$word = trim($word);
			if (stripos($body, $word))
			{
				$forum_core->submission_error = 'Message contains invalid words';
				break;
			}
		}

		return;
	}

}

?>
