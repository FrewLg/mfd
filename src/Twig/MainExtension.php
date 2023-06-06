<?php

namespace App\Twig;

use App\Entity\CoAuthor;
use App\Entity\ResearchReport;
use App\Entity\Submission;
use App\Entity\SubmissionFinalReport;
use App\Entity\User;
use App\Helper\MainHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MainExtension extends AbstractExtension
{
    private $em;
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'timeAgo']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('isCOPIAllowedToReview', [$this, 'isCOPIAllowedToReview']),
            new TwigFunction('timeAgo', [$this, 'timeAgo']),
            new TwigFunction('toRoman', [$this, 'toRoman']),
            new TwigFunction('toAlpha', [$this, 'toAlpha']),
          ];
    }

    public function timeAgo($date1, $date2 = null)
    {
        $date2 ??= new \DateTime('now');
        $days = MainHelper::getTimeElapsed($date1);
        return $days;
    }
    public function isCOPIAllowedToReview(Submission $submission, ResearchReport $researchReport, User $user)
    {
        
        return $researchReport->getSubmissionStatus() != ResearchReport::STATUS_APPROVED &&   $researchReport->getSubmittedBy() != $user && $this->em->getRepository(CoAuthor::class)->isCoPI($submission);
    }
   
    function toRoman($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    function toAlpha($number) {
       
        return chr(64+$number);
    }
}
