<?php

namespace App\Repository;

use App\Entity\Email;
use App\DTO\Email\EmailDTO;
use Doctrine\DBAL\Types\Type;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Email|null find($id, $lockMode = null, $lockVersion = null)
 * @method Email|null findOneBy(array $criteria, array $orderBy = null)
 * @method Email[]    findAll()
 * @method Email[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Email::class);
    }

    /**
     * @param EmailDTO $emailDTO
     *
     * @return array
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function getEmails(EmailDTO $emailDTO): array
    {
        $dateSelect = match ($emailDTO->getPeriod()) {
          'daily' => 'DATE_FORMAT(created_at, "%M-%d") AS date',
          'weekly' => 'STR_TO_DATE(CONCAT(YEARWEEK(created_at, 7), " ", "Monday"), "%X%V %W") AS date',
          'monthly' => 'DATE_FORMAT(created_at, "%Y-%M") AS date',
          'yearly' => 'DATE_FORMAT(created_at, "%Y") AS date',
          default => 'DATE_FORMAT(created_at, "%M-%d") AS date',
        };

        $start = $emailDTO->getDateRange()['start'];
        $end = $emailDTO->getDateRange()['end'];

        $sql = sprintf('SELECT %s, COUNT(id) AS email FROM emails WHERE (created_at BETWEEN "%s" AND "%s") GROUP BY date', $dateSelect, $start, $end);

        $stmt = $this->_em->getConnection()->executeQuery($sql);

        return $stmt->fetchAll();
    }
}
