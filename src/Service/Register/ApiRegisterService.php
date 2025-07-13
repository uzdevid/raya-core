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
                'code' => 'api.importer.module({name})',
                'description' => 'импорт модуля, возвращает модуль. Не используй import.',
                'arguments' => [
                    'name' => ['type' => 'string', 'description' => 'название модуля который нужно импортировать']
                ],
                'returns' => 'подключенный модуль',
                'examples' => ["os = api.importer.module(\"os\")", "now = api.importer.module(\"time\").time"]
            ],
            [
                'code' => 'api.print({text})',
                'description' => 'Вывод текста',
                'arguments' => [
                    'text' => ['type' => 'string', 'description' => 'текст']
                ],
                'returns' => 'void',
                'examples' => ["api.print(\"текст\")"]
            ],
            [
                'code' => 'api.speak({text})',
                'description' => 'озвучка и вывод текста',
                'arguments' => [
                    'text' => ['type' => 'string', 'description' => 'текст']
                ],
                'returns' => 'void',
                'examples' => ["api.speak(\"текст\")"]
            ],
            [
                'code' => 'api.askInput({query})',
                'description' => 'Получить точные данные от пользователя',
                'arguments' => [
                    'query' => ['type' => 'string', 'description' => 'Текст запроса']
                ],
                'returns' => 'string - ответ пользователя на запрос',
                'examples' => ["url = api.askInput(\"Укажите URL страницы\")"]
            ],
            [
                'code' => 'api.languageSwitch({lang})',
                'description' => 'Для переключения языка клавиатуры',
                'arguments' => [
                    'lang' => ['type' => 'string', 'description' => 'Код языка. Например: ru, en, uz']
                ],
                'returns' => 'void',
                'examples' => ["api.languageSwitch(\"en\")"]
            ],
            [
                'code' => 'api.activeWindow.getSelectedText()',
                'description' => 'Возвращает выделенный текст',
                'arguments' => [],
                'returns' => 'string - текст',
                'examples' => ["selected_text = api.activeWindow.getSelectedText()"]
            ],
            [
                'code' => 'api.clipboard.getContent()',
                'description' => 'Возвращает буфера',
                'arguments' => [],
                'returns' => 'string - буфера',
                'examples' => ["clipboard_content = api.clipboard.getContent()"]
            ],
            [
                'code' => 'api.appManager.run({app_name})',
                'description' => 'Запускает приложение',
                'arguments' => [
                    'app_name' => ['type' => 'string', 'description' => 'Имя приложения']
                ],
                'returns' => 'boolean - true если приложение найдено в списке и запущено, иначе false, то есть нужно попробовать по другому запустить',
                'examples' => ["if not api.appManager.run(\"goland\"): \npass # попробовать найти и запустить иначе"]
            ],
            [
                'code' => 'api.appManager.close({app_name})',
                'description' => 'Закрывает приложение',
                'arguments' => [
                    'app_name' => ['type' => 'string', 'description' => 'Имя приложения']
                ],
                'returns' => 'boolean - true если приложение найдено в списке и закрыто, иначе false',
                'examples' => ["if not api.appManager.close(\"goland\"): \npass # попробовать найти и закрыть иначе"]
            ],
            [
                'code' => 'api.application.audioInputDisable()',
                'description' => 'Отключает аудио вход',
                'arguments' => [],
                'returns' => 'void',
                'examples' => ["api.application.audioInputDisable()"]
            ],
            [
                'code' => 'api.application.audioInputEnable()',
                'description' => 'Включает аудио вход',
                'arguments' => [],
                'returns' => 'void',
                'examples' => ["api.application.audioInputEnable()"]
            ],
            [
                'code' => 'api.application.audioOutputDisable()',
                'description' => 'Отключает аудио выход',
                'arguments' => [],
                'returns' => 'void',
                'examples' => ["api.application.audioOutputDisable()"]
            ],
            [
                'code' => 'api.application.audioOutputEnable()',
                'description' => 'Включает аудио выход',
                'arguments' => [],
                'returns' => 'void',
                'examples' => ["api.application.audioOutputEnable()"]
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
