<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\DB;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e, $request) {
            $url = env('MS_TEAMS_WEBHOOK_URL');
            $ch = curl_init( $url );
            $errorArray = get_class($e).'EXCEPTIONCLASSEPARATOR'.$e->getMessage();
            $errorArray = explode('EXCEPTIONCLASSEPARATOR',$errorArray);
            $errorClass = $errorArray[0];
            $errorMessage = $errorArray[1];
            date_default_timezone_set('Asia/Jakarta');
            $data = array (
                '@type' => 'MessageCard',
                '@context' => 'http://schema.org/extensions',
                'themeColor' => 'CD040B',
                'summary' => 'Error Summary',
                'sections' => 
                array (
                0 => 
                array (
                    'activityTitle' => 'Error reported at ' . date('Y-m-d H:i:s') ,
                    'activitySubtitle' => $errorClass,
                    'activityImage' => 'https://laravel.com/img/logomark.min.svg',
                    'facts' => 
                    array (
                    0 => 
                    array (
                        'name' => 'Laravel Version',
                        'value' => app()->version(),
                    ),
                    1 => 
                    array (
                        'name' => 'PHP Version',
                        'value' => phpversion(),
                    ),
                    2 => 
                    array (
                        'name' => 'Application Name',
                        'value' => env('APP_NAME'),
                    ),
                    2 => 
                    array (
                        'name' => 'Application Name',
                        'value' => env('APP_NAME'),
                    ),
                    3 => 
                    array (
                        'name' => 'Application Environment',
                        'value' => env('APP_ENV'),
                    ),
                    4 => 
                    array (
                        'name' => 'Endpoint',
                        'value' => $request->path(),
                    ),
                    5 => 
                    array (
                        'name' => 'Method',
                        'value' => $request->method(),
                    ),
                    6 => 
                    array (
                        'name' => 'Request Body',
                        'value' => json_encode($request->all()),
                    ),
                    7 => 
                    array (
                        'name' => 'User ID',
                        'value' => $request->user()->id,
                    ),
                    8 => 
                    array (
                        'name' => 'User Name',
                        'value' => $request->user()->name,
                    ),
                    9 => 
                    array (
                        'name' => 'Error Message',
                        'value' => $errorMessage,
                    ),
                    10 => 
                    array (
                        'name' => 'Database Version',
                        'value' => DB::select( DB::raw("select version()"))[0]->version,
                    ),
                    
                    ),
                    'markdown' => true,
                ),
                ),
                'potentialAction' => 
                array (
                0 => 
                array (
                    '@type' => 'OpenUri',
                    'name' => 'Open FAQ',
                    'targets' => 
                    array (
                    0 => 
                    array (
                        'os' => 'default',
                        'uri' => env('APP_FAQ_URL'),
                    ),
                    ),
                ),
                ),
            );
            
            $payload = json_encode($data);
            curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            
            $result = curl_exec($ch);
            curl_close($ch);

            if ($request->is('api/*')) {
                $result = [
                    'error_msg' => 'Something went wrong'
                ];
                return response()->json($result, 500);
            }
        });
    }
}
