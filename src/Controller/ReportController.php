<?php

namespace App\Controller;

use App\Repository\SubmissionRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/report")
 */
class ReportController extends AbstractController {
    /**
     * @Route("/irb", name="irb_report", methods={"GET"})
     */
    public function irbreport(): Response {

        $this->denyAccessUnlessGranted('vw_app_sb_rp');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $result = $qb
            ->select('COUNT(e.reviewer) as IRB_Members , e.affiliation as affiliation ')
            ->from('App\Entity\CallForProposal', 'e')
            ->andWhere('e.workunit = :college')
            ->setParameter('college', 1)
            ->groupBy('e.affiliation')
            ->getQuery()->getResult();

        foreach ($result as $k => $a) {
            $array[$k] = json_decode(json_encode($a));
        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(array($result));
        # [['Année', 'Recette pétrolière'],
        #        ['Mega',     39],
        #        ['Technology',     22],
        #            ['Community ',      72],
        #    ]
        #        ));
        $pieChart->getOptions()->setTitle('Publicsations');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(600);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);
        return $this->render('report/irb.html.twig', [
            'institutional_reviewers_boards' => $result,
            'piechart' => $pieChart,
        ]);
    }

    /**
     * @Route("/publications", name="publications_report", methods={"GET"})
     */
    public function publications(SubmissionRepository $submissionRepository): Response {

        $this->denyAccessUnlessGranted('vw_gn_ds');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $result = $qb
            ->select('COUNT(e.id) as Proposals , e.submission_type as Subbmission_type')
            ->from('App\Entity\Submission', 'e')
            ->andWhere('e.complete = :status')
            ->setParameter('status', 0)
            ->groupBy('e.submission_type')
            ->getQuery()->getResult();

        $totalArticles = $submissionRepository->createQueryBuilder('a')

            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $toJSON = json_encode($result);
        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(array($result));
        //    $pieChart->getData()->setArrayToDataTable(
        #);
        //  [['Publications', 'Count'],
        //[$result[0], $result['Subbmission_type']],

        //]

        //);
        $pieChart->getOptions()->setTitle('Publications');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(600);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);

        return $this->render('report/publications_report.html.twig', [
            'institutional_reviewers_boards' => $result,
            'piechart' => $pieChart,
            'total' => $totalArticles,
        ]);
    }
}
