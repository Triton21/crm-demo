<?php

namespace AppBundle\SearchRepository;

use FOS\ElasticaBundle\Repository;
use AppBundle\Model\MaininboxSearch;

class MaininboxRepository extends Repository {

    /**
     * Used by Elastica to transform results to model
     * 
     * @param string $entityAlias
     * @return  Doctrine\ORM\QueryBuilder
     */
    public function getQueryForSearch($maininboxSearch, $limit, $from) {
        if ($maininboxSearch->getTexthtml() != null && $maininboxSearch != '') {
            $contentQuery = new \Elastica\Query\Match();
            $contentQuery->setFieldQuery('maininbox.content', $maininboxSearch->getTexthtml());
            $contentQuery->setFieldFuzziness('maininbox.content', 0.7);
            $contentQuery->setFieldMinimumShouldMatch('maininbox.content', '80%');
            //$contentQuery->setSort(array('id' => array('order' => 'asc')));

            $subjectQuery = new \Elastica\Query\Match();
            $subjectQuery->setFieldQuery('maininbox.subject', $maininboxSearch->getTexthtml());
            $subjectQuery->setFieldFuzziness('maininbox.subject', 0.7);
            $subjectQuery->setFieldMinimumShouldMatch('maininbox.subject', '80%');

            $fromnameQuery = new \Elastica\Query\Match();
            $fromnameQuery->setFieldQuery('maininbox.fromname', $maininboxSearch->getTexthtml());
            $fromnameQuery->setFieldFuzziness('maininbox.fromname', 0.7);
            $fromnameQuery->setFieldMinimumShouldMatch('maininbox.fromname', '80%');

            $fromemailQuery = new \Elastica\Query\Match();
            $fromemailQuery->setFieldQuery('maininbox.fromname', $maininboxSearch->getTexthtml());
            $fromemailQuery->setFieldFuzziness('maininbox.fromname', 0.7);
            $fromemailQuery->setFieldMinimumShouldMatch('maininbox.fromname', '80%');
            //
        } else {
            $query = new \Elastica\Query\MatchAll();
        }
        //$baseQuery = $query;
        // then we create filters depending on the chosen criterias
        //$boolFilter = new \Elastica\Filter\Bool();
        $boolQuery = new \Elastica\Query\Bool();


        //var_dump($contentQuery);die;
        /*
          Dates filter
          We add this filter only the getIspublished filter is not at "false"
         */
        if (null !== $maininboxSearch->getDateFrom() && null !== $maininboxSearch->getDateTo()) {
            //var_dump($maininboxSearch->getDateTo());
            //var_dump($maininboxSearch->getDateFrom());die;
            $boolQuery->addMust(new \Elastica\Query\Range('maildate', array(
                'gte' => \Elastica\Util::convertDate($maininboxSearch->getDateFrom()->getTimestamp()),
                'lte' => \Elastica\Util::convertDate($maininboxSearch->getDateTo()->getTimestamp())
                    )
            ));
        }
        //var_dump($contentQuery);die;
        if ($maininboxSearch->getSettings() !== 0) {
            $boolQuery->addMust(
                    new \Elastica\Terms('maininbox.settid', array($maininboxSearch->getSettings()))
            );
        }

        //var_dump($contentQuery);die;
        $boolQuery->addMust($contentQuery);
        $boolQuery->addMust($subjectQuery);
        $boolQuery->addShould($fromnameQuery);
        $boolQuery->addShould($fromemailQuery);
        //var_dump($maininboxSearch->getDirection());die;
        //var_dump($maininboxSearch->getSort());die;
        $query = new \Elastica\Query($boolQuery);
        /*
        $query->setSort(array(
            $maininboxSearch->getSort() => array(
                'order' => $maininboxSearch->getDirection()
            )
        ));
         * 
         */
        //var_dump($maininboxSearch->getPerPage());die;
        $query->setSort(array('id' => array('order' => $maininboxSearch->getDirection())));
        if ($limit === 0) {
            $query->setSize(10000);
        } else {
            $query->setSize($limit);
        }
        $query->setFrom($from);
        /*
          $query->setSort(array('id' => array('order' => 'desc')));
          $query->setSize(100);
          $query->setFrom(0);
          //$filtered = new \Elastica\Query\Filtered($boolQuery, $boolFilter);
          //$filtered->setSort(array('id' => array('order' => 'asc')));
          //$query = \Elastica\Query::create($filtered);

          //return $this->find($query);
         * 
         */
        return $query;
    }

    public function searchMaininbox(MaininboxSearch $maininboxSearch, $limit, $from) {
        $query = $this->getQueryForSearch($maininboxSearch, $limit, $from);

        return $this->find($query);
    }

}
