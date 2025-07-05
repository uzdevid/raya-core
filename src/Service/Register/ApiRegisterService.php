<?php declare(strict_types=1);

namespace App\Service\Register;

use App\Model\Api;
use App\Model\Client;

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
                'description' => 'Чтобы вывести большой текст вместо озвучки. Например, лог или разноязычные данные которые невозможно озвучить',
                'arguments' => [
                    'text' => ['type' => 'string', 'description' => 'текст который нужно вывести']
                ],
                'returns' => 'void',
                'examples' => ["api.print(\"Этот текст будет выведен\")"]
            ],
            [
                'code' => 'api.speak({text})',
                'description' => 'озвучка текста результата',
                'arguments' => [
                    'text' => ['type' => 'string', 'description' => 'текст который нужно озвучить']
                ],
                'returns' => 'void',
                'examples' => ["api.speak(\"Этот текст будет озвучен\")"]
            ],
            [
                'code' => 'api.languageSwitch({lang})',
                'description' => 'Для переключения языка клавиатуры',
                'arguments' => [
                    'lang' => ['type' => 'string', 'description' => 'Код языка. Например: ru, en, uz']
                ],
                'returns' => 'void',
                'examples' => ["api.languageSwitch(\"ru\")", "api.languageSwitch(\"en\")"]
            ],
            [
                'code' => 'api.activeWindow.getSelectedText()',
                'description' => 'Возвращает выделенный текст в активном окне',
                'arguments' => [],
                'returns' => 'string - выделенный текст',
                'examples' => ["selected_text = api.activeWindow.getSelectedText()"]
            ],
            [
                'code' => 'api.clipboard.getContent()',
                'description' => 'Возвращает содержимое буфера обмена',
                'arguments' => [],
                'returns' => 'string - содержимое буфера обмена',
                'examples' => ["clipboard_content = api.clipboard.getContent()"]
            ],
            [
                'code' => 'api.appManager.run({app_name})',
                'description' => 'Запускает приложение по его имени из списка пользовательских приложений',
                'arguments' => [
                    'app_name' => ['type' => 'string', 'description' => 'Имя приложения, которое нужно запустить']
                ],
                'returns' => 'boolean - true если приложение найдено в списке и запущено, иначе false, то есть нужно попробовать по другому запустить',
                'examples' => ["if not api.appManager.run(\"goland\"): \npass # попробовать найти и запустить иначе"]
            ],
            [
                'code' => 'api.appManager.close({app_name})',
                'description' => 'Закрывает приложение по его имени из списка пользовательских приложений',
                'arguments' => [
                    'app_name' => ['type' => 'string', 'description' => 'Имя приложения, которое нужно закрыть']
                ],
                'returns' => 'boolean - true если приложение найдено в списке и закрыто, иначе false',
                'examples' => ["if not api.appManager.close(\"goland\"): \npass # попробовать найти и закрыть иначе"]
            ],
        ]
    ];

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
}
