<?php
/**
<p>
</p>
 */
class PluginWfI18n{
  /**
  <p>
  Run event document_render_element for this.
  </p>
  #code-yml#
  plugin: 'wf/i18n'
  method: 'translate'
  #code#
   */
  public static function event_translate($value, $element){
    /**
     * Get language settings.
     */
    $language = wfArray::get($GLOBALS, 'sys/settings/i18n/language');
    if(!$language){
      return $element;
    }
    /**
     * Check if element not to be translated.
     */
    if(wfArray::issetAndTrue($element, 'settings/i18n/exclude')){
      return $element;
    }
    /**
     * Check if key innerHTML exist.
     */
    if(wfArray::isKey($element, 'innerHTML')){
      /**
       * innerHTML must be a string.
       */
      if(!is_array(wfArray::get($element, 'innerHTML'))){
        /**
         * Pick up innerHTML, can also be a separate value in element settings.
         */
        if(wfArray::get($element, 'settings/i18n/key')){
          $innerHTML = wfArray::get($element, 'settings/i18n/key');
        }else{
          $innerHTML = wfArray::get($element, 'innerHTML');
        }
        /**
         * From element.
         */
        if(wfArray::get($element, 'settings/i18n/plugin')){
          $filename = '/theme/[theme]/i18n/plugin/'.wfArray::get($element, 'settings/i18n/plugin').'/'.$language.'.yml';
          if(wfFilesystem::fileExist(wfArray::get($GLOBALS, 'sys/app_dir').$filename)){
            $temp = wfSettings::getSettings($filename);
            if($temp && isset($temp[$innerHTML])){
              $innerHTML = $temp[$innerHTML];
              $element = wfArray::set($element, 'innerHTML', $innerHTML);
              return $element;
            }
          }
          $filename = '/plugin/'.wfArray::get($element, 'settings/i18n/plugin').'/i18n/'.$language.'.yml';
          if(wfFilesystem::fileExist(wfArray::get($GLOBALS, 'sys/app_dir').$filename)){
            $temp = wfSettings::getSettings($filename);
            if($temp && isset($temp[$innerHTML])){
              $innerHTML = $temp[$innerHTML];
              $element = wfArray::set($element, 'innerHTML', $innerHTML);
              return $element;
            }
          }
        }
        /**
         * From theme.
         */
        $filename = '/theme/[theme]/i18n/'.$language.'.yml';
        if(wfFilesystem::fileExist(wfArray::get($GLOBALS, 'sys/app_dir').$filename)){
          $temp = wfSettings::getSettings($filename);
          if($temp && isset($temp[$innerHTML])){
            $innerHTML = $temp[$innerHTML];
            $element = wfArray::set($element, 'innerHTML', $innerHTML);
            return $element;
          }
        }
//        $i18n = new PluginWfI18n();
//        return $i18n->translate('/theme/[theme]/i18n/'.$language.'.yml', $element, $innerHTML);
        
      }
    }
    return $element;
  }
  /**
   * 
   * @param string $innerHTML Just any string.
   * @return string
   */
  public function translateFromTheme($innerHTML, $replace = null){
    /**
     * Retreive language.
     */
    $language = wfArray::get($GLOBALS, 'sys/settings/i18n/language');
    if($language){
      /**
       * Check from theme.
       */
      $filename = '/theme/[theme]/i18n/'.$language.'.yml';
      if(wfFilesystem::fileExist(wfArray::get($GLOBALS, 'sys/app_dir').$filename)){
        $temp = wfSettings::getSettings($filename);
        if($temp && isset($temp[$innerHTML])){
          $innerHTML = $temp[$innerHTML];
        }
      }
    }
    /**
     * Replace.
     */
    if($replace){
      foreach ($replace as $key => $value) {
        $innerHTML = str_replace($key, $value, $innerHTML);
      }
    }
    return $innerHTML;
  }
}
























