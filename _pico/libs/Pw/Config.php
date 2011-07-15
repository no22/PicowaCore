<?php
/**
 * Pw_Config
 * 
 * @package		Picowa
 * @since		2010-04-09
 */
class Pw_Config
{
	protected $_configPath = null;
	protected $_cachedData = null;
	protected $_extension = '.json';

	public function __construct($aDefault = null, $sPath = null)
	{
		$this->_configPath = PICOWA_CONF_PATH . (is_null($sPath) ? 'config' : $sPath) . $this->_extension ;
		if (!is_null($aDefault)) {
			if (!file_exists($this->_configPath)) {
				file_put_contents($this->_configPath, $this->_encode($aDefault), LOCK_EX);
			}
			else {
				$aConfig = $this->_loadConfig();
				$isMod = false;
				foreach ($aDefault as $key => $value) {
					if (!isset($aConfig[$key])) {
						$isMod = true;
						$aConfig[$key] = $value;
					}
				}
				if ($isMod) { $this->_saveConfig($aConfig); }
			}
		}
	}

	protected function _encode($aConfig)
	{
		return json_encode($aConfig);
	}

	protected function _decode($sConfig)
	{
		return json_decode($sConfig, true);
	}

	protected function _loadConfig()
	{
		if (is_null($this->_cachedData)) {
			$this->_cachedData = $this->_decode(file_get_contents($this->_configPath));
		}
		return $this->_cachedData;
	}

	protected function _saveConfig($aConfig)
	{
		$iResult = file_put_contents($this->_configPath, $this->_encode($aConfig), LOCK_EX);
		$this->_cachedData = null;
		return $iResult;
	}

	public function __get($sName)
	{
		$aConfig = $this->_loadConfig();
		return isset($aConfig[$sName]) ? $aConfig[$sName] : null ;
	}

	public function __set($sName, $mValue)
	{
		$aConfig = $this->_loadConfig();
		$aConfig[$sName] = $mValue;
		return $this->_saveConfig($aConfig);
	}

	public function __call($sName, $aArgs)
	{
		$aConfig = $this->_loadConfig();
		if (count($aArgs) < 2) {
			list($sKey) = $aArgs;
			return isset($aConfig[$sName][$sKey]) ? $aConfig[$sName][$sKey] : null ;
		}
		else {
			list($sKey, $mValue) = $aArgs;
			$aConfig[$sName][$sKey] = $mValue;
			$this->_saveConfig($aConfig);
			return $mValue;
		}
	}

}
