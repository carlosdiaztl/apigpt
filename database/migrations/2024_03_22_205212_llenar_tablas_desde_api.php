<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $api_key  = env('GEMINI_API_KEY');
        $response = Http::withBody(json_encode([
            'contents' => [
                'parts' => [
                    ['text' => "Genera un dump de SQL para insertar datos en las siguientes tablas:
                    los ids son automaticos 
                    al menos 10 datos por cada tabla

                    Tabla: users
                    Campos: id (INT), name (VARCHAR), email (VARCHAR), image_path (VARCHAR)
                    
                    Tabla: challenges
                    Campos: id (INT), title (VARCHAR), description (TEXT), difficulty (INT), user_id (INT)
                    
                    Tabla: companies
                    Campos: id (INT), name (VARCHAR), image_path (VARCHAR), location (VARCHAR), industry (VARCHAR), user_id (INT)
                    
                    Tabla: programs
                    Campos: id (INT), title (VARCHAR), description (TEXT), start_date (DATE), end_date (DATE), user_id (INT)
                    
                    Por favor, genera un dump de SQL para insertar datos en estas tablas."]
                ]
            ]
        ]), 'application/json')
            ->post("https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent?key=$api_key");

        // Manejo de la respuesta
        if ($response->successful()) {
            $content = $response->body();
            $data = json_decode($content, true);


            $sql_query = $data['candidates'][0]['content']['parts'][0]['text'];
            $sql_query = str_replace("```", "", $sql_query);
            $sql_query = str_replace("sql", "", $sql_query);

            // Aquí tendrás la sentencia SQL generada
            $file_path = 'sql_query.txt'; // Ruta donde se guardará el archivo
            file_put_contents($file_path, $sql_query);
            $file_path = 'sql_query.txt';

            // Obtener el contenido del archivo
            $sql_query = file_get_contents($file_path);

            // Ejecutar la consulta SQL
            DB::unprepared($sql_query);
        } else {
            // Manejar el error
            $errorMessage = $response->body();
        }
    }


    /**
     * Reverse the migrations.
     */
};
