<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use App\Services\WeatherService;
use App\Services\ImageService;
use App\Services\AudioService;

class ChatController extends Controller
{
    protected $weatherService;
    protected $imageService;
    protected $audioService;

    public function __construct(WeatherService $weatherService, ImageService $imageService, AudioService $audioService)
    {
        $this->weatherService = $weatherService;
        $this->imageService = $imageService;
        $this->audioService = $audioService;
    }

    public function sendMessage(Request $request)
    {
        $userMessage = $request->input('message');
        $isWeatherQuery = str_contains($userMessage, '天氣');
        $city = 'Taipei'; // 預設

        if ($isWeatherQuery) {
            foreach (['台北'=>'Taipei','台中'=>'Taichung','高雄'=>'Kaohsiung'] as $cn=>$en) {
                if (str_contains($userMessage, $cn)) $city = $en;
            }

            $weather = $this->weatherService->getWeatherByCity($city);

            if (isset($weather['weather'][0]['description']) && isset($weather['main']['temp'])) {
                $reply = "目前 {$city} 的天氣是 {$weather['weather'][0]['description']}，氣溫大約 {$weather['main']['temp']}°C。";
            } else {
                $reply = "抱歉，無法取得 {$city} 的天氣資訊。";
            }

            return response()->json([
                'reply'=>$reply,
                'debug'=>$weather
            ]);
        }

        // AI 回答
        $aiResponse = OpenAI::chat()->create([
            'model'=>'gpt-4o-mini',
            'messages'=>[
                ['role'=>'system','content'=>'你是一個客服助理，請用簡單中文回答'],
                ['role'=>'user','content'=>$userMessage]
            ]
        ]);

        $reply = $aiResponse->choices[0]->message ?? null;

        return response()->json([
            'reply'=>$reply->content ?? '',
            'debug'=>$reply
        ]);
    }

    // POST /api/chat/image
    public function chatImage(Request $request)
    {
        $request->validate(['photo'=>'required|image|max:10240']); // 10MB 上限
        $description = $this->imageService->analyzeImage($request->file('photo'));
        return response()->json(['reply'=>$description]);
    }

    // POST /api/chat/audio
    public function chatAudio(Request $request)
    {
        $request->validate(['voice'=>'required|mimes:mp3,wav,m4a|max:10240']); // 10MB 上限

        try {
            $text = $this->audioService->transcribeAudio($request->file('voice'));
            return response()->json(['reply'=>$text]);
        } catch (\Exception $e) {
            // 回傳真正的錯誤訊息
            return response()->json(['reply'=>'無法辨識語音: ' . $e->getMessage()]);
        }
    }

}
