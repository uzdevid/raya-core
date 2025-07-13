<?php declare(strict_types=1);

namespace App\Service\Register;

use App\Exception\ServerErrorException;
use App\Model\Api;
use App\Model\Client;
use App\Repository\ApiRepositoryInterface;
use Throwable;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Db\Exception\Exception;
use Yiisoft\Db\Exception\InvalidConfigException;
use Yiisoft\Db\Exception\NotSupportedException;

class ApiRegisterService {
    private array $defaultApis = [
        'python' => [
            [
                'code' => 'api.imp({name})',
                'description' => 'импорт модуля. Не используй import.',
                'arguments' => [
                    'name' => ['type' => 'string', 'description' => 'название модуля']
                ],
                'returns' => 'модуль',
                'examples' => ["os = api.imp(\"os\")"]
            ],
            [
                'code' => 'api.prnt({text})',
                'description' => 'Вывод',
                'arguments' => [
                    'text' => ['type' => 'string', 'description' => 'текст']
                ],
                'returns' => 'void',
                'examples' => ["api.prnt(\"текст\")"]
            ],
            [
                'code' => 'api.spk({text})',
                'description' => 'озвучка и вывод',
                'arguments' => [
                    'text' => ['type' => 'string', 'description' => 'текст']
                ],
                'returns' => 'void',
                'examples' => ["api.spk(\"текст\")"]
            ],
            [
                'code' => 'api.ask({query})',
                'description' => 'Получить точные данные от пользователя',
                'arguments' => [
                    'query' => ['type' => 'string', 'description' => 'Запрос']
                ],
                'returns' => 'string - ответ пользователя на запрос',
                'examples' => ["url = api.ask(\"Укажи URL страницы\")"]
            ],
            [
                'code' => 'api.langSwitch({lang})',
                'description' => 'переключения языка',
                'arguments' => [
                    'lang' => ['type' => 'string', 'description' => 'Код языка: ru, en, uz']
                ],
                'returns' => 'void',
                'examples' => ["api.langSwitch(\"en\")"]
            ],
            [
                'code' => 'api.aw.getSelectedText()',
                'description' => 'Возвращает выделенный текст',
                'arguments' => [],
                'returns' => 'string - текст',
                'examples' => ["selected_text = api.aw.getSelectedText()"]
            ],
            [
                'code' => 'api.clp.get()',
                'description' => 'Возвращает буфера',
                'arguments' => [],
                'returns' => 'string - буфера',
                'examples' => ["clipboard_content = api.clp.get()"]
            ],
            [
                'code' => 'api.app.aInpDis()',
                'description' => 'Отключает аудио вход',
                'arguments' => [],
                'returns' => 'void',
                'examples' => ["api.app.aInpDis()"]
            ],
            [
                'code' => 'api.app.aInpEn()',
                'description' => 'Включает аудио вход',
                'arguments' => [],
                'returns' => 'void',
                'examples' => ["api.app.aInpEn()"]
            ],
            [
                'code' => 'api.app.aOutDis()',
                'description' => 'Отключает аудио выход',
                'arguments' => [],
                'returns' => 'void',
                'examples' => ["api.app.aOutDis()"]
            ],
            [
                'code' => 'api.app.aOutEn()',
                'description' => 'Включает аудио выход',
                'arguments' => [],
                'returns' => 'void',
                'examples' => ["api.app.aOutEn()"]
            ],
        ]
    ];

    public function __construct(
        private readonly ConnectionInterface    $connection,
        private readonly ApiRepositoryInterface $apiRepository
    ) {
    }

    /**
     * @param Client $client
     * @return void
     */
    public function createApis(Client $client): void {
        foreach ($this->defaultApis[$client->language] as $api) {
            $apiModel = new Api();
            $apiModel->client_id = $client->id;
            $apiModel->code = $api['code'];
            $apiModel->description = $api['description'];
            $apiModel->arguments = $api['arguments'];
            $apiModel->returns = $api['returns'];
            $apiModel->examples = $api['examples'];
            $apiModel->created_time = date('Y-m-d H:i:s');
            $apiModel->save();
        }
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable
     * @throws NotSupportedException
     * @throws Exception
     */
    public function updateApis(Client $client): void {
        $transaction = $this->connection->createTransaction();
        $transaction->begin();

        try {
            $this->apiRepository->deleteByClientId($client->id);
            $this->createApis($client);
            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw new ServerErrorException('Failed to update APIs: ' . $e->getMessage(), 0, $e);
        }
    }
}
