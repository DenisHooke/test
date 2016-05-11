<?php


namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Track;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\ValueObject\GenreValue;

class LoadTrackData implements FixtureInterface
{

    const REMOTE_URL = "http://www.100bestsongs.ru";

    public function load(ObjectManager $manager)
    {

        $limit = 40;

        $remoteData[GenreValue::HEAVY_METALL] = $this->getRemoteData(101, $limit); // Хэви-метал
        $remoteData[GenreValue::RUSSIAN_ROCK] = $this->getRemoteData(73, $limit); // Русский рок
        $remoteData[GenreValue::BLUES_ROCK] = $this->getRemoteData(79, $limit); // Блюз-рок
        $remoteData[GenreValue::FUNK] = $this->getRemoteData(85, $limit); // Фанк


        foreach ($remoteData as $genre => $data) {

            foreach($data as $rowData) {

                $track = $this->appendData($rowData, GenreValue::translate($genre));

                if ($track) {
                    $manager->persist($track);
                }
            }
            
        }

        $manager->flush();

    }


    public function getRemoteData($data, $limit = 100)
    {
        $result = array();
        global $kernel;

        if ('AppCache' == get_class($kernel)) {
            $kernel = $kernel->getKernel();
        }

        $queryData = $kernel->getContainer()->get('browser')->request(self::REMOTE_URL, array("tag_id" => $data));

        $htmlDocument = new \DOMDocument();
        @$htmlDocument->loadHTML($queryData);

        $xpath = new \DOMXPath($htmlDocument);
        $tableRows = $xpath->evaluate('//table[@class="table-rating"]//tr');


        foreach ($tableRows as $key => $row) {

            # First row is dirty, hasn't values
            if ($key == 0) continue;

            # Limit items
            if ($limit >= 100) break;


            $rowColumns = $row->childNodes;
            if ($rowColumns instanceof \DOMNodeList) {

                $columnsData = array();
                foreach ($rowColumns as $column) {

                    $columnsData[] = $column->textContent;

                }
            }

            $result[] = $columnsData;
        }

        return $result;
    }



    public function appendData($rowData, $genre)
    {

        if (!isset($rowData[1])) return;

        $parseName = explode('—', $rowData[1]);
        if (sizeof($parseName) != 2) return false;


        $track = new Track();
        $track
            ->setName($parseName[1])
            ->setDuration((int)($rowData[4] * 60))
            ->setGenre($genre)
            ->setSinger($parseName[0])
            ->setYear($rowData[2]);

        return $track;

    }
}