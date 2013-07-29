<?php
/**
* @package   ZS Elements
* @author    Lech H. Conde http://www.zyrx.com.mx
* @copyright Copyright (C) Lech H. Conde @zyrx
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

// register ElementRepeatable class
App::getInstance('zoo')->loader->register('ElementRepeatable', 'elements:repeatable/repeatable.php');

/**
 * Class: ElementYoutubeVideo
 * 		The YouTube Video element class
 * 
 * @author Lech H. Conde <@zyrx>
 *
 */
class ElementYoutubeVideo extends ElementRepeatable implements iRepeatSubmittable {

	/**
	 * Function: render
	 * 		Renders the repeatable element.
	 * @param 		$params - render parameter
	 * @return 		String - html
	 */
	protected function _render($params = array()) {

		$value = $this->get('value', $this->config->get('default'));
		
		if( $this->_onContentPrepare( $value ) )
		{
			// render layout
			if ($layout = $this->getLayout()) {
				return $this->renderLayout($layout, array('value' => $value));
			}
			
			return $value;
		}
		
	}
	
	/**
	 * Function: _onContentPrepare
	 * 		Prepare content method
	 *
	 * @param 		$value - The URL
	 * @return		Boolean - true, on success
	 */
	protected function _onContentPrepare( &$value ){
		// Test the domain
		if ( !preg_match( '#^https?://www.youtube.com/#', $value ) ) {
			return true;
		}
		
		$value = preg_replace('|(https?://www.youtube.com/watch\?v=([a-zA-Z0-9_-]+))|e', '$this->_youtubeCodeEmbed("\2")', $value);
		
		return true;
	}
	
	/**
	 * Function: _youtubeCodeEmbed
	 * 		
	 * 
	 * @param 		$vCode
	 * @return 		String - html
	 */
	protected function _youtubeCodeEmbed( $vCode )
	{
		$width = $this->config->get('width', 425);
		$height = $this->config->get('height', 344);
	
		return '<object width="'.$width.'" height="'.$height.'"><param name="movie" value="http://www.youtube.com/v/'.$vCode.'"></param><param name="allowFullScreen" value="true"></param><embed src="http://www.youtube.com/v/'.$vCode.'" type="application/x-shockwave-flash" allowfullscreen="true" width="'.$width.'" height="'.$height.'"></embed></object>';
	}
	
	/**
	 * Function: _hasValue
	 * 		Checks if the repeatables element's value is se
	 * 
	 * @param 		$params - render parameter
	 * @return 		Boolean - true, on success
	 */
	protected function _hasValue($params = array()) {
		$value = $this->get('value', $this->config->get('default'));
		return !empty($value) || $value === '0';
	}

	/**
	 * Function: _getSearchData
	 * 		Get repeatable elements search data.
	 * 
	 * @return		String - Search data
	 */
	protected function _getSearchData() {
		return $this->get('value', $this->config->get('default'));
	}

	/**
	 * Function: _edit
	 * 		Renders the repeatable edit form field.
	 * 
	 * @return		String - html
	 */
	protected function _edit() {
		return $this->app->html->_('control.text', $this->getControlName('value'), $this->get('value', $this->config->get('default')), 'size="60" maxlength="255"');
	}


	/**
	 * Function: _renderSubmission
	 * 		Renders the element in submission.
	 * 
	 * @param 		$params - AppData submission parameters
	 * @return		String - html
	 */
	public function _renderSubmission($params = array()) {
        return $this->_edit();
	}

}