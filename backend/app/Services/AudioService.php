<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;

class AudioService
{
    public function transcribeAudio($file)
    {
        $response = OpenAI::audio()->transcribe([
            'model' => 'whisper-1',
            'file' => fopen($file->getRealPath(), 'r'),
        ]);

        return $response->text ?? '無法辨識語音';
    }
}
