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

        if ($this->getdiff($diff, 'W') > 0) {
            return  $dateTime->format('d/m/Y');
        } else if ($this->getdiff($diff, 'd') > 0) {

            if ($this->getdiff($diff, 'd') == 1) {
                return 'Yesterday';
            }

            $range = 'days';    
            $char = 'd';
        } else if ($this->getdiff($diff, 'h') > 0) {

            $range = 'hours';   
            $char = 'h';

            if ($this->getdiff($diff, 'h') == 1)
                $range = 'hour';

        } else if ($this->getdiff($diff, 'i') > 0) {

            if ($this->getdiff($diff, 'i') == 1) {
                return 'A minute ago';
            }

            $range = 'minutes';   
            $char = 'i';
        } else {
            return 'Less than a minute ago';
        }

        return $diff->format("%{$char} {$range} ago");
    }

    private function getDiff($diff, $char) {
        return $diff->format('%' . $char . '');
    }

}