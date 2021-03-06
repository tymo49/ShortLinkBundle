<?php

namespace tymo49\ShortlinkBundle\Repository;

/**
 * LinkRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LinkRepository extends \Doctrine\ORM\EntityRepository
{

    public function checkNewLinkName($linkName)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT l.id, l.name, l.link
 FROM links l
 WHERE l.name = :name ");
        $statement->bindValue('name', $linkName);
        $statement->execute();
        $results = $statement->fetch();

        return $results;
    }

    public function checkNewLink($link)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT l.id, l.name, l.link
 FROM links l
 WHERE l.link = :link ");
        $statement->bindValue('link', $link);
        $statement->execute();
        $results = $statement->fetch();

        return $results;
    }

}
