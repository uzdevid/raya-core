<?php declare(strict_types=1);

namespace App\Service\Register;

use App\Model\Assistant;
use App\Model\Client;
use Yiisoft\Aliases\Aliases;

readonly class InstructionsCollectService {
    public function __construct(
        private Aliases $aliases
    ) {
    }

    /**
     * @param string $version
     * @param Assistant $assistant
     * @param Client[] $clients
     * @return string
     */
    public function collect(string $version, Assistant $assistant, array $clients): string {
        $template = $this->importTemplate($version);

        $clientsList = [];
        foreach ($clients as $model) {
            $clientApis = [];
            foreach ($model->apis as $api) {
                $apiArguments = [];
                foreach ($api->arguments as $name => $params) {
                    $apiArguments[] = sprintf(
                        '%s:%s - %s',
                        $name,
                        $params['type'],
                        $params['description']
                    );
                }

                $apiArgumentsString = implode(" | ", $apiArguments);

                $clientApis[] = sprintf(
                    '%s:%s [%s] - %s',
                    $api->code,
                    $api->returns,
                    $apiArgumentsString,
                    $api->description
                );
            }

            $clientApisString = implode("\n", $clientApis);

            $clientsList[] = sprintf(
                "Клиент ID: %s \nПлатформа: %s \nЯзык программирования: %s\n%s",
                $model->id,
                $model->platform,
                $model->language,
                $clientApisString
            );
        }

        $clientsString = implode("\n ---------- \n", $clientsList);

        return str_replace(
            ['{robot.name}', '{clients}'],
            [$assistant->name, $clientsString],
            $template
        );
    }

    private function importTemplate(string $version): string {
        $version = strtolower($version);

        $filePath = $this->aliases->get(sprintf('@instructions/%s.txt', $version));

        return file_get_contents($filePath);
    }
}
