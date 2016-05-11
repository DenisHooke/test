<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TrackRepository")
 * @ORM\Table(name="tracks")
 *
 */
class Track
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $genre;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $singer;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Track
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Track
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Track
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Track
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set singer
     *
     * @param string $singer
     *
     * @return Track
     */
    public function setSinger($singer)
    {
        $this->singer = $singer;

        return $this;
    }

    /**
     * Get singer
     *
     * @return string
     */
    public function getSinger()
    {
        return $this->singer;
    }

}
