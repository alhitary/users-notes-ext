<?php
/**
*
* User's Notes extension for the phpBB Forum Software package.
*
* @copyright (c) 2015 Vinny <https://github.com/vinny>
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace vinny\usersnotes\event;

/**
* @ignore
*/
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{

	/* @var \phpbb\template\template */
	protected $template;

	/* @var \phpbb\controller\helper */
	protected $helper;
 
	static public function getSubscribedEvents()
	{
		return array(
			'core.user_setup'						=> 'load_language_on_setup',
			'core.page_header'						=> 'add_page_header_link',
		);
	}

  /**
   * Constructor
   *
   * @param \phpbb\controller\helper  $helper    Helper object
   * @param \phpbb\template\template  $template  Template object
   */
  public function __construct(\phpbb\template\template $template, \phpbb\controller\helper $helper)
  {
    $this->template = $template;
    $this->helper = $helper;
  }

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = array(
			'ext_name' => 'vinny/usersnotes',
			'lang_set' => 'notes',
		);
		$event['lang_set_ext'] = $lang_set_ext;
	}

	/**
	 * @return null
	 */
	public function add_page_header_link($event)
	{
		$this->template->assign_vars(array(
			'U_USERS_NOTES'		=> $this->helper->route('vinny_usersnotes_controller'),
		));
	}
}
