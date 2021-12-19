<?php

namespace App\Service;

use App\Entity\Email;
use App\DTO\Email\EmailDTO;
use Psr\Log\LoggerInterface;
use App\Repository\EmailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailService extends AbstractService
{
    /**
     * @var EmailRepository
     */
    protected EmailRepository $emailRepository;

    /**
     * @param ContainerInterface     $container
     * @param LoggerInterface        $logger
     * @param EntityManagerInterface $entityManager
     * @param TagAwareCacheInterface $tagAwareCacheProvider
     */
    public function __construct(
        ContainerInterface $container,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        protected TagAwareCacheInterface $tagAwareCacheProvider,
    ) {
        parent::__construct($container, $logger, $entityManager);

        $this->emailRepository = $this->entityManager->getRepository(Email::class);
    }

    /**
     * @param EmailDTO $emailDTO
     * @param array    $requestData
     * @param bool     $useCache
     *
     * @return array|null
     */
    public function getEmails(EmailDTO $emailDTO, array $requestData, bool $useCache = true): ?array
    {
        try {
            $cacheKey = sprintf('%s%s', Email::CACHE_KEY_LIST, md5(serialize($requestData)));

            if ($useCache) {
                $cacheItem = $this->tagAwareCacheProvider->getItem($cacheKey);

                if (false !== $cacheItem->isHit()) {
                    return $cacheItem->get();
                }
            }

            $emails = $this->emailRepository->getEmails($emailDTO);

            if (!empty($emails) && $useCache) {
                $cacheItem = $this->tagAwareCacheProvider->getItem($cacheKey);
                $cacheItem->expiresAfter(Email::CACHE_LIFETIME_ALL);
                $cacheItem->tag([Email::CACHE_TAG_EMAILS]);
                $cacheItem->set($emails);

                $this->tagAwareCacheProvider->save($cacheItem);
            }

            return $emails;
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('[EmailService][getEmails] %s', $e), [
                'period' => $emailDTO->getPeriod(),
                'dateRange' => $emailDTO->getDateRange(),
                'useCache' => $useCache,
            ]);
        }

        return null;
    }
}