<?php

namespace App\Commands;

use Exception;
use Illuminate\Support\Facades\Log;

class RetryTwiceThenLogCommand extends Command
{
    protected $command;
    protected $attempts = 0;
    protected $exception; // Добавляем свойство для хранения исключения

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function execute()
    {
        try {
            $this->command->execute();
        } catch (Exception $e) {
            $this->exception = $e; // Сохраняем исключение
            $this->attempts++;
            if ($this->attempts < 2) {
                $this->execute(); // Повторяем команду
            } else {
                // После двух попыток логируем ошибку
                Log::error('Command failed after 2 attempts: ' . get_class($this->command), [
                    'exception' => $this->exception->getMessage(),
                ]);
            }
        }
    }
}