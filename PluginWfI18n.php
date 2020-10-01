<?php
/**
<p>
Use event_translate_string as your first choice.
</p>
 */
class PluginWfI18n{
  /**
   * This event must listen for event document_render_string. It¨s occure before Buto writes the innerHTML param to the browser.
   */
  public static function event_translate_string($value, $string){
    $i18n = new PluginWfI18n();
    $string = $i18n->translateFromTheme($string);
    return $string;
  }
  /**
  <p>This event must listen for event document_render_element.</p>
  <p>Settings in /theme/xx/yy/config/settings.yml</p>
  #code-yml#
  i18n:
    language: sv
    fallback:
      - en
  #code#
  <p>Create file /theme/xx/yy/i18n/sv.yml</p>
  #code-yml#
  Home: Hem
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
        }
        return $element;
      }
    }
    return $element;
  }
  /**
   * 
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