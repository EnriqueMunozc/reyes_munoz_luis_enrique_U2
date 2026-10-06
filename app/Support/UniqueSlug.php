<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UniqueSlug
{
    /**
     * @param  class-string<Model>  $modelClass
     */
    public function generate(string $modelClass, string $value, int $maxLength): string
    {
        $base = Str::slug($value) ?: 'articulo';
        $base = Str::limit($base, $maxLength, '');
        $candidate = $base;
        $suffixNumber = 2;

        while ($modelClass::query()->where('slug', $candidate)->exists()) {
            $suffix = '-'.$suffixNumber;
            $candidate = Str::limit($base, $maxLength - strlen($suffix), '').$suffix;
            $suffixNumber++;
        }

        return $candidate;
    }
}
