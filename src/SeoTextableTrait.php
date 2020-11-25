<?php

namespace Belyaevad\SeoTextable;

use Belyaevad\SeoTextable\Models\SeoTextable;
use Illuminate\Database\Eloquent\Model;

trait SeoTextableTrait
{
    public array $regsClear
        = [
            '/<a .*?<\/a>/i',
            '/<h[1-6]>.*?<\/h[1-6]>/i',
            '/<table.*?<\/table>/i',
            '/<img .*?>/i',
        ];

    public function seoText($val)
    {
        $links = $this->linksText()->get();
        if ( ! $links->count()) {
            $seoText = SeoTextable::create(
                [
                    'textable_id'   => $this->getKey(),
                    'textable_type' => static::class ??
                        self::class ?? get_class(),
                ]
            );
            $seoText->save();
        } elseif ($links->first()->has_links) {
            $links = $links->first()->links;
            if (is_array($links) && sizeof($links)) {
                usort($links, function ($a, $b) {
                    return mb_strlen($b) - mb_strlen($a);
                });
                foreach ($links as $link) {
                    $search[] = strip_tags($link);
                    $replace[] = $link;
                }

                return str_replace($search, $replace, $val);
            }
        }

        return $val;
    }

    public function linksText()
    {
        return $this->morphOne(SeoTextable::class, 'textable');
    }

    public function getClearText()
    {
        if (isset($this->text)) {
            return $this->clearText($this->text);
        }

        if (isset($this->body)) {
            return $this->clearText($this->body);
        }

        return false;
    }

    private function clearText($str)
    {
        foreach ($this->regsClear as $reg) {
            $str = preg_replace($reg, "\r\n", $str);
        }

        $str = preg_replace('/<[^<]+?>/i', "\r\n", $str);
        $str = preg_replace('/[\r\n]+/', "\r\n", $str);

        return trim(strip_tags($str));
    }

}
