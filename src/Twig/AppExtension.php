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
        );
    }

    public function getTimeAgo($dateTime)
    {
        $now = date_create();
        $diff = $now->diff($dateTime);

        $range = null;
        $char = null;

        if ($diff->format('%W') > 0) {
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