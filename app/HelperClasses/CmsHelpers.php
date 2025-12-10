<?php

namespace App\HelperClasses;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CmsHelpers
{

    public static function getPageNameFromModel($model)
    {
        $model_name = substr($model, strrpos($model, '\\') + 1);
        $snake_case = preg_replace('/(?<!^)([A-Z])/', '_$1', $model_name);
        return strtolower($snake_case);
    }
    public static function getFullUrl($path, $name, $removeStorage = false)
    {
        $relativePath = trim($path, '/') . '/' . $name;
        $url = url(Storage::url($relativePath));
        if ($removeStorage)
            $url = str_replace('/storage/', '/', $url);
        return $url;
    }

    public static function findBySlug($model_name, $slug, $module_name)
    {
        $model_name = self::convertToClassName($model_name, $module_name);
        $model_instance = $model_name::whereSlug($slug)->firstOrFail();
        return $model_instance;
    }
    public static function findById($model_name, $id, $module_name)
    {
        $model_name = self::convertToClassName($model_name, $module_name);
        $model_instance = $model_name::whereId($id)->firstOrFail();
        return $model_instance;
    }

    public static function convertToClassName($string, $module_name)
    {
        $formattedModule = Str::studly($module_name);
        $formattedString = Str::studly($string);

        return "Modules\\{$formattedModule}\\Models\\{$formattedString}";
    }


}
