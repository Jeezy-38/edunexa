<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiTutorController extends Controller
{
    /**
     * Handle AI Tutor interactions for enrolled students in the course player.
     */
    public function ask(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|numeric',
            'course_id' => 'required|numeric',
            'action'    => 'required|string|in:explain,summary,quiz,custom',
            'prompt'    => 'nullable|string|max:1000',
        ]);

        $lesson = Lesson::find($request->lesson_id);
        $course = Course::find($request->course_id);

        if (!$lesson || !$course) {
            return response()->json([
                'status'  => 'error',
                'message' => get_phrase('Somo au kozi haikupatikana.'),
            ], 404);
        }

        $lessonTitle = $lesson->title;
        $courseTitle = $course->title;
        $lessonSummary = strip_tags($lesson->summary ?? $lesson->description ?? '');
        $action = $request->action;
        $customPrompt = trim($request->prompt ?? '');

        // System prompt establishing the persona of Edunexa AI Tutor
        $systemInstructions = "Wewe ni Edunexa AI Tutor, mwalimu msaidizi mahiri, mtulivu, na mwenye kueleweka sana kwa wanafunzi wa jukwaa la Edunexa LMS (Tanzania & Afrika Mashariki). "
            . "Lengo lako kuu ni kumsaidia mwanafunzi kuelewa somo hili kwa undani, kwa lugha nyepesi, ya kutia moyo, na mifano halisi ya maisha. "
            . "Mada ya sasa ya somo ni: \"{$lessonTitle}\" katika kozi ya \"{$courseTitle}\". "
            . ($lessonSummary ? "Muhtasari wa somo lililopo: \"{$lessonSummary}\". " : "")
            . "Ikiwa mwanafunzi anawasiliana kwa Kiswahili, jibu kwa Kiswahili fasaha na rafiki. Weka majibu yako katika mpangilio nadhifu (bullet points au aya fupi zenye vichwa vya habari vidogo) ili yawe mepesi kusoma.";

        // Determine user query based on requested action
        switch ($action) {
            case 'explain':
                $userQuery = "Tafadhali nifafanulie mada ya somo hili la \"{$lessonTitle}\" kwa Kiswahili chepesi na kinachoeleweka. Nipe mfano 1 au 2 wa kawaida wa maisha ya kila siku ili nishike vizuri dhana hii.";
                break;

            case 'summary':
                $userQuery = "Tafadhali nipe muhtasari wa nukta 4 hadi 5 muhimu zaidi ambazo ninapaswa kuzikumbuka na kuzielewa vizuri kutoka kwenye somo hili la \"{$lessonTitle}\".";
                break;

            case 'quiz':
                $userQuery = "Tafadhali nipe maswali 3 ya haraka ya mazoezi (yakiwa na machaguo A, B, C au maswali mafupi) yanayotokana na somo hili la \"{$lessonTitle}\" ili nipime uelewa wangu. Chini ya kila swali, weka jibu sahihi na maelezo mafupi.";
                break;

            case 'custom':
            default:
                if (empty($customPrompt)) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => get_phrase('Tafadhali andika swali lako kwanza.'),
                    ], 422);
                }
                $userQuery = "Kuhusiana na somo hili la \"{$lessonTitle}\" katika kozi ya \"{$courseTitle}\": " . $customPrompt;
                break;
        }

        // Check for OpenAI API credentials
        $apiKey = function_exists('get_settings') ? get_settings('open_ai_secret_key') : null;
        if (empty($apiKey)) {
            $apiKey = env('OPENAI_API_KEY');
        }

        $model = (function_exists('get_settings') ? get_settings('open_ai_model') : null) ?: 'gpt-3.5-turbo';

        if (!empty($apiKey)) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type'  => 'application/json',
                ])->timeout(25)->post('https://api.openai.com/v1/chat/completions', [
                    'model'       => $model,
                    'messages'    => [
                        ['role' => 'system', 'content' => $systemInstructions],
                        ['role' => 'user', 'content' => $userQuery],
                    ],
                    'max_tokens'  => 800,
                    'temperature' => 0.7,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $answer = $result['choices'][0]['message']['content'] ?? null;
                    if ($answer) {
                        return response()->json([
                            'status' => 'success',
                            'answer' => $answer,
                        ]);
                    }
                } else {
                    Log::warning('OpenAI API returned non-200 in AiTutor: ' . $response->body());
                }
            } catch (\Throwable $e) {
                Log::error('OpenAI call failed in AiTutor: ' . $e->getMessage());
            }
        }

        // Fallback: Intelligent educational guidance if OpenAI API is unavailable or unconfigured
        $fallbackAnswer = $this->generateEducationalFallback($action, $lessonTitle, $courseTitle, $lessonSummary, $customPrompt);

        return response()->json([
            'status' => 'success',
            'answer' => $fallbackAnswer,
        ]);
    }

    /**
     * Provide a smart fallback response based on lesson context.
     */
    protected function generateEducationalFallback(string $action, string $lessonTitle, string $courseTitle, string $summary, string $customPrompt): string
    {
        $siteTitle = function_exists('get_settings') ? get_settings('system_title') : 'Edunexa';

        if ($action === 'explain') {
            return "### 📘 Ufafanuzi wa Somo: **{$lessonTitle}**\n\n"
                . "Karibu kwenye somo hili ndani ya kozi ya **{$courseTitle}**! Hapa kuna mambo ya msingi unayopaswa kuelewa:\n\n"
                . "1. **Dhana Kuu:** Somo hili linalenga kukupa ujuzi wa vitendo kuhusu mada ya *{$lessonTitle}* ili uweze kuitumia kitaalamu.\n"
                . ($summary ? "2. **Mtazamo wa Somo:** " . $summary . "\n" : "")
                . "3. **Mfano Halisi:** Fikiria dhana hii kama hatua ya msingi ya kujenga msingi imara. Ukielewa hatua hii, masomo yanayofuata yatakuwa mepesi sana.\n\n"
                . "💡 **Ushauri wa Mwalimu:** Rudia kutazama video au soma maelezo ya mwalimu, kisha jaribu kufanya mazoezi kwa vitendo!";
        }

        if ($action === 'summary') {
            return "### 📝 Muhtasari wa Nukta Muhimu: **{$lessonTitle}**\n\n"
                . "Hapa kuna mambo makuu 4 ya kuzingatia kutoka somo hili:\n\n"
                . "• **Umuhimu:** Kuelewa misingi ya *{$lessonTitle}* ni nguzo kuu katika kozi hii ya *{$courseTitle}*.\n"
                . "• **Ujuzi Utakaoupata:** Uwezo wa kutambua na kutatua changamoto zinazohusiana na mada hii.\n"
                . ($summary ? "• **Muhtasari wa Mwalimu:** " . $summary . "\n" : "")
                . "• **Hatua Inayofuata:** Baada ya hapa, somo linalofuata litaendeleza mada hii kwa vitendo zaidi.\n\n"
                . "✨ *Hifadhi au nakili nukta hizi kwenye daftari lako la masomo!*";
        }

        if ($action === 'quiz') {
            return "### 🎯 Jaribio la Haraka la Uelewa: **{$lessonTitle}**\n\n"
                . "**Swali 1:** Ni lengo gani kuu la kujifunza somo la \"{$lessonTitle}\"?\n"
                . "*(Jibu: Kupata ufahamu wa kina na ujuzi wa vitendo unaotumika kwenye {$courseTitle}.)*\n\n"
                . "**Swali 2:** Ni jambo gani la kwanza unalopaswa kufanya unapotumia dhana hii?\n"
                . "*(Jibu: Kuelewa misingi na kufuata maelekezo yaliyotolewa na mkufunzi kwenye video.)*\n\n"
                . "**Swali 3:** Je, unaweza kueleza mada hii kwa maneno yako mwenyewe kwa dakika 1?\n"
                . "*(Ushauri: Jaribu kueleza kwa sauti bila kuangalia skrini – ikikukwamisha, rudia video!)*\n\n"
                . "🌟 *Ukimudu haya maswali 3, uko tayari kusonga mbele kwenye somo linalofuata!*";
        }

        // Custom question fallback
        return "### 💬 Jibu la Edunexa AI Tutor\n\n"
            . "Kuhusu swali lako: *\"{$customPrompt}\"*\n\n"
            . "Kwenye somo hili la **{$lessonTitle}** (kozi ya **{$courseTitle}**), jambo muhimu ni kuhakikisha unaleta uwiano kati ya nadharia na vitendo. "
            . ($summary ? "Kumbuka maelezo ya mwalimu yanasema: \"{$summary}\". " : "")
            . "\n\nIkiwa una changamoto zaidi, unaweza pia kutumia kitufe cha **WhatsApp** kilichopo hapo chini kuwasiliana na mwalimu au wanafunzi wenzako moja kwa moja!";
    }
}
