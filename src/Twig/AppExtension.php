<?php 

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('time_ago', array($this, 'getTimeAgo')),
            new TwigFilter('excerpt', array($this, 'stripContent')),
            new TwigFilter('exists', array($this, 'logo_exists')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_Function('file_exists', 'file_exists')
        );
    }

    public function stripContent($content, int $wordCount = 20, string $more = '[Read more]'): string {
        $content = strip_tags(html_entity_decode($content));
        $exploded = explode(' ', $content);

        if (sizeof($exploded) > $wordCount) {
            return implode(' ', array_slice($exploded, 0, $wordCount)) . ' ' . $more;
        } else {
            return implode(' ', array_slice($exploded, 0, $wordCount));
        }
    }

    public function logo_exists($filename) {
        return file_exists($filename);
    }

    public function getTimeAgo($dateTime)
    {
        $now = date_create();
        $diff = $now->diff($dateTime);

        $range = null;
        $char = null;

        if ($diff->format('%d') > 7) {
            return  $dateTime->format('d/m/Y');
        } else if ($diff->format('%d') > 0) {

            if ($diff->format('%d') == 1) {
                return 'Yesterday';
            }

            $range = 'days';
            $char = 'd';
        } else if ($diff->format('%h') > 0) {

            $range = 'hours';
            $char = 'h';

            if ($diff->format('%h') == 1)
                $range = 'hour';

        } else if ($diff->format('%i') > 0) {

            if ($diff->format('%i') == 1) {
                return 'A minute ago';
            }

            $range = 'minutes';
            $char = 'i';
        } else {
            return 'Less than a minute ago';
        }

        return $diff->format("%{$char} {$range} ago");
    }

}