<?php declare(strict_types=1);

namespace App\Service\Register;

use App\Exception\NotFoundException;
use App\Exception\ServerErrorException;
use App\Model\Assistant;
use App\Repository\AssistantRepositoryInterface;
use Throwable;

readonly class AssistantRegisterService {
    public function __construct(
        private AssistantRepositoryInterface $assistantRepository
    ) {
    }

    /**
     * @param string $identityId
     * @return bool
     */
    public function hasAssistant(string $identityId): bool {
        return $this->assistantRepository->existsByOwnerId($identityId);
    }

    /**
     * @param string $identityId
     * @return Assistant
     * @throws NotFoundException
     */
    public function getAssistant(string $identityId): Assistant {
        return $this->assistantRepository->getByOwnerId($identityId);
    }

    /**
     * @param string $identityId
     * @return Assistant
     * @throws ServerErrorException
     */
    public function createAssistant(string $identityId): Assistant {
        $assistant = new Assistant();

        $assistant->id = $identityId;
        $assistant->owner_id = $identityId;
        $assistant->assistant_id = 'asst_6oRFNaNScY1tlmpnzCWqvVD9'; // Temporary, should be updated after creation in the openai
        $assistant->thread_id = 'thread_NQDQF9G9ey5HXvah9h3WpJ7a'; // Temporary, should be updated after creation in the openai
        $assistant->name = 'Raya';
        $assistant->language = 'ru';
        $assistant->instructions = 'default'; // Placeholder, should be updated with actual instructions
        $assistant->created_time = date('Y-m-d H:i:s');

        return $this->save($assistant);
    }

    /**
     * @param Assistant $assistant
     * @return Assistant
     * @throws ServerErrorException
     */
    public function save(Assistant $assistant): Assistant {
        try {
            $isSaved = $assistant->save();
        } catch (Throwable $exception) {
            throw new ServerErrorException($exception->getMessage(), $exception->getCode(), $exception);
        }

        if (!$isSaved) {
            throw new ServerErrorException('Assistant could not be created');
        }

        return $assistant;
    }
}
