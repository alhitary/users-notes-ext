<?php
/**
*
* User's Notes extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Vinny <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\usersnotes\controller;

class main
{
	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\user */
	protected $user;

	/* @var \phpbb\controller\helper */
	protected $helper;
 
	/** @var \phpbb\request\request */
	protected $request;

	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\template\template $template, \phpbb\user $user, \phpbb\controller\helper $helper, \phpbb\request\request $request)
	{
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->helper = $helper;
		$this->request = $request;
	}

	public function base()
	{
		$note	= utf8_normalize_nfc($this->request->variable('note', '', true));
		$submit = (isset($_POST['submit'])) ? true : false;
		$error = array();

		// check if user s logged in, since this page can be used only after registration...
		if (!$this->user->data['is_registered'])
		{
			login_box($this->helper->route('vinny_usersnotes_controller'));
		}

		// ... and also this is not for bots (especially for bad ones :)
		if ($this->user->data['is_bot'])
		{
			redirect(append_sid("{$this->phpbb_root_path}index.$this->phpEx"));
		}

		$s_action = $this->helper->route('vinny_usersnotes_controller');
		$s_hidden_fields = '';
		add_form_key('postform');

		// create a template variables
		$this->template->assign_vars(array(
			'S_POST_ACTION'			=> $s_action,
			'S_HIDDEN_FIELDS'		=> $s_hidden_fields,
			'ERROR'					=> (sizeof($error)) ? implode('<br />', $error) : '',
		));

		if ($submit)
		{
			/*if(!check_form_key('postform'))
			{
				trigger_error('FORM_INVALID');
			}*/

			$sql = 'UPDATE ' . USERS_TABLE . '
				SET user_note = "' . $note . '"
				WHERE user_id = ' . $this->user->data['user_id'];
			$this->db->sql_query($sql);

			meta_refresh(3, $this->helper->route('vinny_usersnotes_controller'));
			trigger_error(sprintf($this->user->lang['NOTES_SAVED'], $this->helper->route('vinny_usersnotes_controller')));
		}

		// create a template variables
		$this->template->assign_vars(array(
			'NOTE'				=> $this->user->data['user_note'],
		));

		$this->template->assign_block_vars('navlinks', array(
			'FORUM_NAME' 	=> ($this->user->lang['NOTES']),
		));

		return $this->helper->render('notes.html', $this->user->lang['NOTES']);
	}
}
