<?php

//use Hashids;

function current_theme() {
    return Theme::current()->name;
}
function current_locale() {
    return LaravelLocalization::getCurrentLocale();
}
function default_locale() {
    return LaravelLocalization::getDefaultLocale();
}
function current_locale_direction() {
    return LaravelLocalization::getCurrentLocaleDirection();
}
function current_locale_native() {
    return LaravelLocalization::getCurrentLocaleNative();
}
function supported_locales() {
    return LaravelLocalization::getSupportedLocales();
}
function get_localized_url($locale_code) {
    return LaravelLocalization::getLocalizedURL($locale_code, null, [], ($locale_code != default_locale()));
}
function widget($widget_class, $params = []) {
    try {
        return \Widget::run($widget_class, $params);
    } catch(\Exception $e) {

    }
}

function cart_content_quantity($cart_item) {
    $listing = App\Models\Listing::find($cart_item);
    return $listing->quantity;
}

function cart_content($cart_item) {
    $listing = App\Models\Listing::find($cart_item);
    return $listing->carousel[0];
}

function asyncWidget($widget_class, $params = []) {
    try {
        return \AsyncWidget::run($widget_class, $params);
    } catch(\Exception $e) {

    }
}

function module_enabled($alias) {
    $module = \Module::findByAlias($alias);
    return (bool) ($module && $module->enabled());
}
function flatten($elements, $depth) {
    $result = array();

    foreach ($elements as $element) {
        $element['depth'] = $depth;

        if (isset($element['child'])) {
            $children = $element['child'];
            unset($element['child']);
        } else {
            $children = null;
        }

        $result[] = $element;

        if (isset($children)) {
            $result = array_merge($result, flatten($children, $depth + 1));
        }
    }

    return $result;
}

function flattenhome($elements, $depth, $count) {
    $result = array();

    foreach ($elements as $element) {
        $element['depth'] = $depth;

        if (isset($element['child'])) {
           if($depth == 0){
             if(count($element['child']) > 0){
               $children = $element['child'];
               unset($element['child']);
               $result[] = $element;

               if (isset($children)) {
                $result = array_merge($result, flattenhome($children, $depth + 1, $count));
               }
            }
          }else{
            $children = $element['child'];
            unset($element['child']);
            $result[] = $element;

            if (isset($children)) {
                $result = array_merge($result, flattenhome($children, $depth + 1, $count));
            }
          }

        } else {
            //$children = null;
        }

     }

    return $result;
}

function getCatImage($catId){
  $cat_image = "/images/no_image.png";
  $cat_obj = App\Models\Category::find($catId);
  if($cat_obj){
    $cat_image = $cat_obj->category_image;
  }
  return $cat_image;
}

function format_money($price, $currency) {
	$placement = 'before';
	try {
		$currency = new \Gerardojbaez\Money\Currency($currency);
		$currency->setSymbolPlacement($placement);
		if($price > 1000) {
			$currency->setPrecision(0);
		}
		$money = new \Gerardojbaez\Money\Money($price, $currency);
		return $money->format();
	} catch(\Exception $e) {
		if($placement == 'before')
			return $currency . ' '. number_format($price, 2);
		else
			return number_format($price, 2) . ' ' . $currency ;
	}
}
function getDir($id, $levels_deep = 32) {
    $file_hash   = md5($id);
    $dirname     = implode("/", str_split(
        substr($file_hash, 0, $levels_deep)
    ));
    return $dirname;
}
function store($dirname, $filename) {
    return $dirname . "/" . $filename;
}

function storedir($dirname, $filename) {
    return $dirname . "/";
}

function jsdeliver_combine($theme = 'default', $type = 'js') {
    $jsdeliver_js = "";
    if(file_exists(themes_path($theme.'/jsdeliver.json'))) {
        $files = json_decode(file_get_contents(themes_path($theme.'/jsdeliver.json')), true);
        $jsdeliver_js = implode(",", $files[$type]);
    }
    return $jsdeliver_js;
}

use Barryvdh\TranslationManager\Models\Translation;
function save_language_file($file, $strings) {
    /*$file = resource_path("views/langs/$file.php");
    $output = "<?php\n\n";
    foreach($strings as $string) {
        $output .= "__('".$string."');\n";
    }
    \File::put($file, $output);
    foreach($strings as $string) {
        $output .= "__('".$string."');\n";
    }*/
    //insert it into the database
    $selected_locale = 'en';
    if ($strings instanceof Illuminate\Support\Collection) {
        $strings = $strings->toArray();
    }
    $strings = array_unique($strings);
    $strings = array_filter($strings);
    foreach($strings as $string) {
        $translation_row = Translation::where('locale', $selected_locale)
            ->where('group', '_json')
            ->where('key', $string)
            ->first();
        if(!$translation_row) {
            $translation_row = new Translation();
            $translation_row->key = $string;
            $translation_row->locale = $selected_locale;
            $translation_row->group = '_json';
            $translation_row->value = null;
            $translation_row->save();
        }
    }
}

function menu($location = null, $locale = null) {
    if(!$locale)
        $locale = LaravelLocalization::getCurrentLocale();
    if(!$location)
        $location = 'top';
    $menu = \App\Models\Menu::where('location', $location)->where('locale', $locale)->first();
    if($menu)
        return $menu->items;
    return [];
}
function _l($string) {
    return __($string);
}
function amtopm($string){
  $time_moment = "AM";
   try{
   $time_string = str_replace(":",".", $string);
   $int = (float)$time_string;
   if($int >= 12){
    $time_moment = "PM";
   }
 }catch(\Exception $e){
   $time_moment = "";
 }
   return $time_moment;
 }

 function catbrands($cat_id){
   $brands = [];
   $brands = \App\Models\BrandCategory::where('category_id', $cat_id)->take(5)->get();
   return $brands;
 }

 function categoryUrl($cat_id, $slug){
   return route('shopping.category', [\Hashids::encode($cat_id), $slug]);
 }
