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
    $language = wfArray::get($GLOBALS, 'sys/settings/i18n/language');
    if(!$language){
      return $element;
    }
    if(wfArray::issetAndTrue($element, 'settings/i18n/exclude')){
      return $element;
    }
    // If key innerHTML is set.
    if(wfArray::isKey($element, 'innerHTML')){
      // And not an array.
      if(!is_array(wfArray::get($element, 'innerHTML'))){
        // Get content to do something with?
        if(wfArray::get($element, 'settings/i18n/key')){
          $innerHTML = wfArray::get($element, 'settings/i18n/key');
        }else{
          $innerHTML = wfArray::get($element, 'innerHTML');
        }
        
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
        $filename = '/theme/[theme]/i18n/'.$language.'.yml';
        if(wfFilesystem::fileExist(wfArray::get($GLOBALS, 'sys/app_dir').$filename)){
          $temp = wfSettings::getSettings($filename);
          if($temp && isset($temp[$innerHTML])){
            $innerHTML = $temp[$innerHTML];
            $element = wfArray::set($element, 'innerHTML', $innerHTML);
            return $element;
          }
        }
      }
    }
    return $element;
  }
}























