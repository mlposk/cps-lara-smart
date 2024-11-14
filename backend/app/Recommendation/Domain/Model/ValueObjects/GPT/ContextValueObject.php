<?php

namespace App\Recommendation\Domain\Model\ValueObjects\GPT;

class ContextValueObject
{
    private array $context = [
        [
            'role' => 'system',
            'content' => 'You are a helpful assistant.',
        ],
    ];

    private bool $isContextNeeded;

    public function __construct(bool $isContextNeeded = true)
    {
        $this->isContextNeeded = $isContextNeeded;
        $this->init();
    }

    private function init(): void
    {
        $this->initContext();
    }

    private function initContext(): void
    {
        if ($this->isContextNeeded) {
            $this->context[] =
                [
                    'role' => 'user',
                    'content' => '
                Сформируй название задачи по методологии SMART для следующей задачи:
                Название задачи: "Формирование выгрузки по примененному фильтру в файл формата xlsx".
                Описание задачи: "При нажатии кнопки "Экспорт в файл" будет происходить выгрузка выборки в файл формата Excel, с учетом установленных фильтров и сортировок"
                Проект: "Кабинет ГД Томск".
                Верни следующее:
                {Только название задачи по SMART без вводного слова} {символ %d%} {Почему она не попадает под SMART, распиши критерии, но не пиши про Time Bound}
                ',
                ];
            $this->context[] =
                [
                    'role' => 'assistant',
                    'content' => 'КР Томск: Реализация функционала выгрузки списка подробного чек-листа
             загруженных отчетов с учетом фильтров и сортировок в xlsx-файл в административном модуле. %d% Это название уточняет, что нужно сделать, как измерить успех (функционал выгрузки данных) и для какого проекта это делается.',
                ];
        }
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
