<?php


namespace App\Recommendation\Domain\Model\ValueObjects\GigaChat;

class ContextValueObject
{
    private array $context = [
        [
            'role' => 'system',
            'content' => 'You are a helpful assistant.'
        ]
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
                Ответ верни в следующем формате:
                {Название задачи по SMART} ### {Краткое объяснение, почему название соответствует критериям Specific, Measurable, Achievable, Relevant (не упоминай Time Bound). Используй общий текст, не расписывай каждую категорию подробно.}
                '
                ];
            $this->context[] =
                [
                    'role' => 'assistant',
                    'content' => 'КР Томск: Реализация функционала выгрузки списка подробного чек-листа
             загруженных отчетов с учетом фильтров и сортировок в xlsx-файл в административном модуле. ### Это название уточняет, что нужно сделать, как измерить успех (функционал выгрузки данных) и для какого проекта это делается.'
                ];
        }
    }

    public function getContext(): array
    {
        return $this->context;
    }
}
